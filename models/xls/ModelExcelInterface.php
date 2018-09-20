<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 03.09.2018
 * Time: 11:41
 */

namespace app\models\xls;


interface ModelExcelInterface
{

    /**
     * @param $loadInfo
     * @return mixed
     */
    public static function excelDataProcessingInstance($loadInfo);

    /**
     * @param $loadInfo
     * @return mixed
     */
    public function setLoadInfo($loadInfo);

    /**
     * @return mixed
     */
    public function loadModelInstance();

    /**
     * @param $data
     * @return mixed
     */
    public function runObjectDataProcessing($data);

    /**
     * @return mixed
     */
    public static function excelRules();



}