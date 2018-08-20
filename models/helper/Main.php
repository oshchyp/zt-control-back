<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 13.08.2018
 * Time: 11:29
 */

namespace app\models\helper;


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

    public static function generateUid(){
        $resultArr=[];
        foreach ([8,4,4,4,12] as $length){
            $resultArr[] = self::generateString($length);
        }
        return implode('-',$resultArr);
    }

}