<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.11.2018
 * Time: 13:03
 */

namespace app\models\excelparser;


use app\models\RegionCultures;
use app\models\Regions;
use app\models\RegionsCultures;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use yii\db\ActiveRecord;

class RegionCulturesModel extends RegionCultures implements ModelInterface
{


    public static $regions;

    public $cultures;

    /**
     * @param RowData $object
     * @param Row $row
     * @param ExcelParser $excelParser
     * @return ModelInterface|void|ActiveRecord
     * @throws \Exception
     */
    public static function instanceExcel(RowData $object, Row $row, ExcelParser $excelParser)
    {
        $uid = static::findRegionUIDByName($object->attributes['region']);
        if (!$uid) {
            throw new \Exception('region not exist ' . $object->attributes['region']);
        }

        $arr = [];
        $regions = RegionCultures::find()->where(['regionUID' => $uid])->all();
        if ($regions) {
            foreach ($regions as $item) {
                $arr[$item->cultureUID] = $item;
            }
        }

        foreach (static::cultures() as $uidC) {
            if (!isset($arr[$uidC]) && $weight = $object->attributes[$uidC]) {
                $model = new RegionCultures();
                $model->attributes = [
                    'regionUID' => $uid,
                    'cultureUID' => $uidC,
                    'weight' => $weight
                ];
                $model->save();
            }
        }

    }

    public static function cultures()
    {
        return [
            '48f9e143-f6ca-11e7-aa53-6466b304e311',
            '3314c918-ebc0-11e7-8660-60a44cafafcb',
            '22301bb8-699f-11e8-aad6-6466b304e311',
            'RsrYStth-D4TK-4tyh-KtB5-HyQar2StQana',
            '2cfcb900-ebc0-11e7-8660-60a44cafafcb',
        ];
    }

    // public static function culturesByReg

    public static function regions()
    {
        if (!static::$regions) {
            $arr = [];
            foreach (Regions::find()->all() as $item) {
                $arr[$item->name] = $item->uid;
            }
            static::$regions = array_merge($arr, [
                "Арцизский" => '5EG8hHit-TSQR-YBN3-tyGS-TT8f2fHynkhB',
                'Білгород-Дністровський' => 'eBBfDrn3-y2e8-GZ3i-ySEr-Ea3rE8ZKtzza'
            ]);
        }

        return static::$regions;
    }

    public static function findRegionUIDByName($name)
    {
        foreach (static::regions() as $k => $v) {
            if ($k == $name) {
                return $v;
            }
        }
        return null;
    }

    /**
     * @param RowData $object
     * @param ExcelParser $excelParser
     * @param Row $row
     * @return mixed
     */
    public function runExcel(RowData $object, Row $row, ExcelParser $excelParser)
    {
        // TODO: Implement runExcel() method.
    }
}