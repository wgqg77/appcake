<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "auth_rule".
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property integer $type
 * @property integer $status
 * @property string $condition
 * @property integer $pid
 * @property integer $sort
 * @property integer $create_time
 */
class AuthRule extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auth_rule';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['status', 'pid','type'], 'integer'],
            [['pid'], 'required'],
            [['name'], 'string', 'max' => 80],
            [['title'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '方法',
            'title' => '名称',
            'type' => '是否作为菜单显示',
            'status' => '菜单位置',
            'condition' => 'Condition',
            'pid' => '父级',
            'sort' => '排序',
            'create_time' => '创建时间',
        ];
    }

    public function getNamePrefix($leve){
        $str = '|—';
        if($leve >= 1){
            for($i=0;$i<=$leve;$i++){
                $str .='—';
            }
        }else{
            $str = '';
        }
        return $str;
    }

    public function toArrayTree($data,$id='id',$name='title',$leve=0,$resultData=array())
    {
        foreach ($data as $k => $v){
            $namePrefix = $this->getNamePrefix($leve);
            $resultData[] = $v['id'].','.$namePrefix . $v[$name];

            $temp = $this->find()->where('pid ='.$v[$id])->select(array($id,$name))->asArray()->all();
            if(count($temp) > 0){
                $leve ++ ;
               $tempData = $this->toArrayTree($temp,$id,$name,$leve,$resultData);
                $resultData =   array_merge($resultData + $tempData);
                $leve = 0 ;
            }
        }

        return $resultData;
    }

    public function getRulesToTree()
    {
        $topRules = $this->find()->where("pid = 0")->asArray()->all();
        $data = $this->toArrayTree($topRules,'id','title');
        $return = array();
        foreach($data as $k => $v){
            $tem = explode(',',$v);
            $id = $tem[0];
            $name = $tem[1];
            $return[$id]= $name;
        }
       return $return;
    }


    public function getChildRules($data,$id='id',$name='title',$leve=0,$resultData=array(),$fid=0,$field='')
    {
        foreach ($data as $k => $v){
            $namePrefix = $this->getNamePrefix($leve);
            $v['_'.$name] = $namePrefix . $v[$name];
            $v['level'] = $leve;
            $v['fid'] = $fid;

            $resultData[] = $v;

            $temp = $this->find()->where('pid ='.$v[$id])->select($field)->asArray()->all();
            if(count($temp) > 0){
                $leve ++ ;
                $fid = $leve == 1 ? $v['id'] : $fid;
                $tempData = $this->getChildRules($temp,$id,$name,$leve,$resultData,$fid);
                $resultData =   array_merge($resultData + $tempData);
                $leve = 0 ;
            }
        }

        return $resultData;
    }

    //二级菜单
    public function getChildMenu($data,$id='id',$name='title',$field='',$position=1)
    {
        $resultData = array();
        foreach ($data as $k => $v){
            $resultData[$k] = $v;

            $temp = $this->find()->where("pid =$v[$id] and status = 0")->select($field)->asArray()->all();
            if(count($temp) > 0){
                $resultData[$k]['sub'] = $temp;
            }
        }

        return $resultData;
    }
    public function getTreeRules()
    {
        $field = array('id','title','pid');
        $topRules = $this->find()->where("pid = 0")->select($field)->asArray()->all();
        $data = $this->getChildRules($topRules,'id','title',0,array(),0,$field);

        return $data;
    }

    public function getMenuByPosition($position=1){
        $field = array('id','title','pid','name');
        $menu = $this->find()->where("type = {$position} and pid = 0 and status = 0 ")->select($field)->orderBy('sort asc')->asArray()->all();
        $data = $this->getChildMenu($menu,'id','title',$field,$position);
        return $data;
    }

    public function getRulesInId($ids){
        if($ids == false ) return array();
        return $this->find()->where("id in($ids)")->asArray()->all();
    }
}
