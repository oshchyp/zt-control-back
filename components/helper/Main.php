<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 13.08.2018
 * Time: 11:29
 */

namespace app\components\helper;


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
        return base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    public static function stringToProperName($string){
        $result = [];
        foreach (explode(' ',$string) as $str){
            $result[] = static::mbUcfirst(mb_strtolower($str));
        }
        return implode(' ',$result);
    }

    public static function mbUcfirst($str) {
        $fc = mb_strtoupper(mb_substr($str, 0, 1));
        return $fc.mb_substr($str, 1);
    }

}