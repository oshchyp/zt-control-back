<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 15:09
 */

namespace app\models\json_parser;


class Firms extends \app\models\Firms implements ModelInterface
{

    use ModelTrait;

    public $kodrdpu;
    public $Contact;
  //  public $Contact_post;

    public $rules = [
        'kodrdpu' => 'rdpu',
        'Contact' => 'contactUID',
     ];

    public static $uniqueField = ['uid','uid'];

}