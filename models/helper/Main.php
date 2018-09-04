<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 13.08.2018
 * Time: 11:29
 */

namespace app\models\helper;


use yii\helpers\ArrayHelper;

class Main
{

    public static function generateString($length=8)
    {

        $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
        $numChars = strlen($chars);
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= substr($chars, rand(1, $numChars) - 1, 1);
        }
        return $string;

    }

    public static function generateUid($id=null){
        $resultArr=[];
        foreach ([8,4,4,4,12] as $length){
            $resultArr[] = self::generateString($length);
        }
        $result = implode('-',$resultArr);
        if ($id){
            $result = $id.'_'.$result;
        }
        return $result;
    }

    public static function generateUidUniq($arrayUids=[]){
        $uid = static::generateUid();
        if (in_array($uid,$arrayUids)){
            $uid = static::generateUidUniq($arrayUids);
        }
        return $uid;
    }

    public static function generateUidUniqModel($model,$uidAttr='uid'){
        $array = $model::find()->asArray()->all();
        return static::generateUid(ArrayHelper::getColumn($array,$uidAttr));
    }

}