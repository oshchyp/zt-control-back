<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 14.08.2018
 * Time: 16:42
 */

namespace app\models;


use yii\base\Model;

class Posts extends Model
{
    public $id;

    public $name;

    public $director = false;

    public static function data(){
        return [
            [
                'id' => 0,
                'name' => 'Не известно'
            ],
            [
                'id' => 1,
                'name' => 'Директор',
                'director' => true
            ],
            [
                'id' => 2,
                'name' => 'ФОП'
            ],
            [
                'id' => 3,
                'name' => 'Голова'
            ],
            [
                'id' => 4,
                'name' => 'Керівник'
            ]

        ];
    }

    public static function findById($id){
        $data = static::data();
        foreach ($data as $value){
            if ($value['id'] === $id){
                return new static($value);
            }
        }
        return null;
    }

    public static function findAll(){
        $data = static::data();
        $result = [];
        foreach ($data as $value){
            $result[] = new static($value);
        }
        return $result;
    }
}