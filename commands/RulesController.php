<?php

namespace app\commands;

use app\components\Common;
use app\modules\admin\models\AuthRule;
use yii\console\Controller;
use Yii;
use ReflectionClass;
class RulesController extends Controller
{
    /**
     * 读取配置项module
     * 根据模块加载控制器 添加方法
     */
    public function actionIndex()
    {
        $path = Yii::$app->params['autoRulesPath'];
        $rules = $this->getRules();
        $sql = '';$num = 0;
        foreach($path as $k => $v){
            $controllerPath =  Yii::$app->getBasePath().$v;
            $files = scandir($controllerPath);
            foreach($files as $fk => $fv){

                if($fv == '.' || $fv == '..') continue;
                $controllerClass = "app". str_replace("/","\\",$v) ."\\" . rtrim($fv,'.php');
                $ref = new ReflectionClass($controllerClass);

                $methods = $ref->getMethods();

                foreach($methods as $method){


                    if(strpos($method->name,'action') !== false && $method->name !== 'actions'){

                        $name = strtolower(substr($fv,0,strlen($fv)-14));
                        //显示菜单 默认index显示

                        if($name == 'index'){
                            $status = 0;
                        }else{
                            $status = 1;
                        }
                        $pid = 0;
                        $action=preg_split("/(?=[A-Z])/",ltrim($method->name,'action'));
                        $action = ltrim(strtolower(implode('-',$action)),'-');
                        $name .= "/".$action;
                        $modelName = str_replace(array('/modules','controllers'),'',$v);
                        $type = $this->getType($modelName);
                        $rule = $modelName . $name;
                        $time = time();

                        $des = $method->getDocComment();
                        preg_match("/@title(.*?)\n/",$des,$match);
                        $title = isset($match[1]) ? $match[1] : $rule;

                        if(!in_array($rule,$rules)){
                            $rules[] = $rule;
                            $num ++ ;
                            $sql .= "insert into `auth_rule` (name,title,type,status,pid,create_time)
                                     values('{$rule}','{$title}',{$type},{$status},{$pid},$time);";
                        }
                    }
                }
            }
        }
        if(!empty($sql)){
            Common::dbExecute($sql);
        }
        echo '共执行条数:' . $num ."\n";
    }


    public function getRules(){
        $rules = AuthRule::find()->select("name")->asArray()->all();
        if($rules){
            $rules = array_column($rules,'name');
        }else{
            $rules = array();
        }
        return $rules;
    }

    public function getType($modelName)
    {
        if($modelName == 'weike'){
            $type = 3;
        }else{
            $type = 3;
        }

        return $type;
    }
}
