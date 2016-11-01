<?php
namespace app\components;

class Common{

    /**
     * @param $sql
     * @return int
     * @throws \yii\db\Exception
     * app_system 原声sql执行
     */
    public static  function dbExecute($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->execute();
    }

    /**
     * @param $sql
     * @return mixed
     *  cake 原声sql查询
     */
    public static function cakeQuery($sql)
    {
        $connection = \Yii::$app->cake;
        return $connection->createCommand($sql)->queryAll();
    }

    public static function dbQuery($sql)
    {
        $connection = \Yii::$app->db;
        return $connection->createCommand($sql)->queryAll();
    }

}

