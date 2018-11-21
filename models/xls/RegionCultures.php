<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.11.2018
 * Time: 12:31
 */

namespace app\models\xls;


use yii\base\Model;

class RegionCultures extends Model implements ModelExcelInterface
{

    use ModelExcelTrait;

    public $RCData;

    public static function excelRules()
    {
        return [
            'I'=>'region',

            'N'=>'48f9e143-f6ca-11e7-aa53-6466b304e311',
            'Q'=>'3314c918-ebc0-11e7-8660-60a44cafafcb',
            'M'=>'22301bb8-699f-11e8-aad6-6466b304e311',
            'T'=>'22301bb8-699f-11e8-aad6-6466b304e311',
            'W'=>'RsrYStth-D4TK-4tyh-KtB5-HyQar2StQana',
            'Z'=>'2cfcb900-ebc0-11e7-8660-60a44cafafcb'
        ];
    }

    /**
     * @param $loadInfo
     * @return mixed|void
     */
    public function loadModelInstance($loadInfo)
    {
      //  $this->RCData =
    }


    /**
     * @param $data
     * @return mixed
     */
    public function runObjectDataProcessing($data)
    {
        // TODO: Implement runObjectDataProcessing() method.
    }
}