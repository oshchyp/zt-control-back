<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 10.12.2018
 * Time: 14:12
 */

namespace app\models\farms;


use app\components\filter\FilterDataInterface;
use app\components\filter\FilterDataTrait;
use app\components\validators\ValidatorTrait;

class FarmsFilter extends Farms implements FilterDataInterface
{
    use FilterDataTrait;
    use ValidatorTrait;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['square'],'number'],
            [['pointUID','regionUID', 'cultures.cultureUID'],'arrayValidate'],
            [['firm.name','region.name','point.name','cultures.culture.name', 'stringForSearchAll'],'string']
        ];
    }

    public function rulesFilter(){
        return [
            [['pointUID','regionUID', 'cultures.cultureUID'],'andWhere',['in']],
            [['square'],'range',['>','=']],
            [['firm.name','point.name'],'andWhere',['like']],
            [['stringForSearchAll'],'search',[['firm.name','region.name','point.name','cultures.culture.name']]]
        ];
    }

    public function attributesAdd()
    {
        return ['firm.name','region.name','point.name','cultures.culture.name','cultures.cultureUID','stringForSearchAll'];
    }


    public function culturesCultureJoin(){
        $this->setJoinUniq(['LEFT','farmCultures AS cultures','cultures.farmUID = farms.uid']);
        $this->setJoinUniq(['LEFT','culture AS culture','culture.uid = cultures.cultureUID']);
    }

    public function culturesCultureNameSetJoin(){
        return $this->culturesCultureJoin();
    }

    public function culturesCultureUIDSetJoin(){
        return $this->culturesCultureJoin();
    }

    public function attributesNameInQuery($attribute)
    {
        return [
            'cultures.culture.name' => 'culture.name',
        ];
    }

    public function afterSearch()
    {
       // dump($this->getQuery()->createCommand()->getRawSql());
    }

}