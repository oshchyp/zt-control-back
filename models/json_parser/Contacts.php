<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 09.08.2018
 * Time: 11:10
 */

namespace app\models\json_parser;


class Contacts extends \app\models\Contacts
{
    use ModelTrait;

    public $Contact_post;

    public $rules = [
        'Contact_post' => 'post'
    ];

    public static $uniqueField = ['uid','uid'];

}