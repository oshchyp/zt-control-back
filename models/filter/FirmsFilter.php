<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 20.10.2018
 * Time: 10:22
 */

namespace app\models\filter;


use app\models\FirmCultures;
use app\models\Firms;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Class FirmsFilter
 * @package app\models\filter
 * @property $processedSquare
 */

class FirmsFilter extends Firms
{

    use FilterDataTrait;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['square', 'processedSquare'], 'number'],
            [['name', 'rdpu', 'regionUID', 'pointUID', 'region.name','point.name','stringForSearchAll'], 'string', 'max' => 250],
            [['square|sort'],'sortValidate'],
            ['sender', 'integer'],
        ];
    }

    public function rulesFilter(){
        return [
           [['square','processedSquare'],'range',['>','=']],
         // [['processedSquare'],'processedSquare'],
           [['name','rdpu'],'andWhere',['like']],
           [['pointUID','regionUID','sender'],'andWhere',['=']],
           [['square|sort'],'sort'],
           [['stringForSearchAll'],'search',[['name', 'rdpu','region.name','point.name']]]
        ];
    }

    public function attributesAdd()
    {
        return ['region.name','point.name','stringForSearchAll','square|sort','processedSquare'];
    }

    public function attributesNameInQuery($attribute)
    {
        return [
            'square|sort' => 'square',
            'processedSquare' => '('.
                FirmCultures::find()->select('SUM(firmCultures.square)')
                    ->where('firmCultures.firmUID = firms.uid AND firmCultures.year = '.date('Y'))
                    ->createCommand()->getRawSql()
                .')',
        ];
    }

    public function getProcessedSquare(){
        return $this->processedSquare;
    }

    public function FilterQueryProcessedSquare(){
        $field = FirmCultures::find()->select('SUM(firmCultures.square)')
            ->where('firmCultures.firmUID = firms.uid AND firmCultures.year = '.date('Y'))
            ->createCommand()->getRawSql();
        $field = '('.$field.')';
        $this->getQuery()
            ->andWhere('@processedSquare = '.$field)
            ->andFilterWhere([
                'or',
               // ['@processedSquare = '.$field],
                ['>','@processedSquare',$this->getAttribute('processedSquare')],
                ['=','@processedSquare',$this->getAttribute('processedSquare')]
            ]);
    }

//    public function getProcessedSquare(){
//        return $this->processedSquare;
//    }
//
//    public function filterQueryProcessedSquare($attribute,$value,$type){
//       //  $this->filterQueryAndOrWhere(FirmCultures::find()->select('Sum(`square`)')->where(['firmUID'=>'firms.id'])->createCommand(),$value,$type);
//     //   $this->getQuery()->select(['`firms`.*'])->addSelect(['`firms`.`processedSquare`' => FirmCultures::find()->select('Sum(`square`)')->where(['firmUID'=>'firms.id'])]);
//         dump($this->getQuery()->createCommand()->getRawSql());
//    }


}


//{
//    "searchString": "строка для поиска по всем полям",
//	"name": "поиск по имени агента",
//	"rdpu": "поиск по имени рдпу",
//	"square" : "минимальное значение площади",
//	"regionUID": "UID выбранного региона",
//	"pointUID":"UID выбранного Месторасположение",
//	"sender" : "id Статуса рассылки",
//	"square|sort": "DESC",
//	"name|sort" : "ASC",
//	"processedSquare" : 213
//}