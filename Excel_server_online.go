package main

import (
	"fmt"
	"net/http"
	"time"
	"log"
	_ "github.com/go-sql-driver/mysql"
	"database/sql"
	"github.com/tealeg/xlsx"
)

var (
	database string
	selectSql string
	putFileName string
)

func sqlToExcel(w http.ResponseWriter, r *http.Request) {
	r.ParseForm()  //解析参数，默认是不解析
	//fmt.Println(r.Form)  //
	//fmt.Println("path", r.URL.Path)
	//fmt.Println("scheme", r.URL.Scheme)
	//fmt.Println(r.Form["url_longr"])


	//简版实现功能为主
	query := r.URL.Query()
	//判断是否有get请求sql参数
	if _, ok := query["sql"]; ok{
		selectSql = query["sql"][0]
		//判断库
		if _, ok := query["db"]; ok {
			database = query["db"][0]
		}else{
			database = "iphonecake"
		}
		if _,ok := query["name"];ok{
			putFileName = query["name"][0]
		}else{
			putFileName = time.Now().String()
		}
		//链接库
		db, err := sql.Open("mysql", "iphonecake:iphonecakepass@tcp(appcake.cbhzsxlq4jlh.us-west-2.rds.amazonaws.com:3306)/"+database+"?charset=utf8")
		if err != nil {
			panic(err.Error())
		}
		defer db.Close()

		//执行查询语句
		rows, err := db.Query(selectSql)
		if err != nil {
			panic(err.Error())
		}

		//列
		columns, err := rows.Columns()
		if err != nil {
			panic(err.Error())
		}

		//存储每行字段值
		values := make([]sql.RawBytes, len(columns))
		//excel类
		new_file := xlsx.NewFile()
		new_sheet, err := new_file.AddSheet("Sheet1")
		scanArgs := make([]interface{}, len(values))
		for i := range values {
			scanArgs[i] = &values[i]
		}

		//遍历写入内容
		titleIndex := 0
		for rows.Next() {

			titleIndex ++
			err = rows.Scan(scanArgs...)
			if err != nil {
				panic(err.Error())
			}


			var value string
			if titleIndex == 1{
				new_row := new_sheet.AddRow()
				for i, col := range values {
					if col == nil {
						value = "NULL"
					} else {
						value = string(col)
					}
					new_cell := new_row.AddCell()
					new_cell.Value = columns[i]
				}

			}
			new_row := new_sheet.AddRow()
			for _, col := range values {
				if col == nil {
					value = "NULL"
				} else {
					value = string(col)
				}


				new_cell := new_row.AddCell()
				new_cell.Value = value
				//fmt.Println(i)
				//fmt.Println(columns[i], ": ", value)
			}
			//fmt.Println("-----------------------------------")
		}
		new_file.Save("./Excel/"+putFileName+".xlsx")






		fmt.Fprintf(w, "{\"code\":10000,\"message\":\"success\",\"name\":\""+putFileName+"\"}")
	}else{
		fmt.Fprintf(w, "{\"code\":10001,\"message\":\"error\"}")
	}

}

func main() {
	http.HandleFunc("/", sqlToExcel) //设置访问的路由
	err := http.ListenAndServe(":9010", nil) //设置监听的端口
	if err != nil {
		log.Fatal("ListenAndServe: ", err)
	}
}