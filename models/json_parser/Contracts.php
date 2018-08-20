<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 08.08.2018
 * Time: 11:32
 */

namespace app\models\json_parser;

class Contracts extends \app\models\Contracts implements ModelInterface
{
    use ModelTrait;

    public $UID;
    public $contract_name;
    public $firm_uid;
    public $culture_uid;
    public $region_uid;
    public $receiver_point_uid;

    public $rules = [
        'UID' => 'uid',
        'contract_name' => 'name',
        'firm_uid' => 'firmUID',
        'culture_uid' => 'cultureUID',
        'region_uid' => 'regionUID',
        'receiver_point_uid' => 'receiverPointUID',
    ];

    public static $uniqueField = ['UID','uid'];

}