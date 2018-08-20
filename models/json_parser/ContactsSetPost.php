<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.08.2018
 * Time: 12:15
 */

namespace app\models\json_parser;


class ContactsSetPost extends \app\models\Contacts
{

    use ModelTrait;

    public $Contact_post;


    public $rules=[
        'Contact_post' => 'post'
    ];

    public function rules()
    {
        return [
            ['Contact_post','safe']
        ];
    }

    public static $uniqueField = ['Contact','uid'];

    public function beforeSave($insert)
    {
        if (!$this->uid){
            return false;
        }
        return true;
    }

}