<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 14:12
 */

namespace app\modules\api\models;


use app\models\filter\FilterDataInterface;
use app\models\filter\FilterDataTrait;

class FarmsFilter extends Farms implements FilterDataInterface
{
    use FilterDataTrait;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['square'],'number'],
            [['firm.name','region.name','point.name','culture.name', 'stringForSearchAll'],'string']
        ];
    }

    public function rulesFilter(){
        return [
            [['square'],'range',['>','=']],
            [['firm.name','region.name','point.name','culture.name'],'andWhere',['like']],
            [['stringForSearchAll'],'search',[['firm.name','region.name','point.name','culture.name']]]
        ];
    }

    public function attributesAdd()
    {
        return ['firm.name','region.name','point.name','culture.name','stringForSearchAll'];
    }

}