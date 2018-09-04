<?php

namespace app\models;

use app\models\helper\Main;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "contacts".
 *
 * @property int $id
 * @property string $uid
 * @property string $name
 * @property string $post
 */
class Contacts extends ActiveRecord
{

    public static $allInstances = null;

    public $addInstanceAfterSave = false;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contacts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
             [['firmUID'], 'required'],

            [['name'], 'required', 'when' => function () {
                return !$this->phone && !$this->email;
            }],
            [['phone'], 'required', 'when' => function () {
                return !$this->name && !$this->email;
            }],
            [['email'], 'required', 'when' => function () {
                return !$this->phone && !$this->name;
            }],
            [['postID'], 'integer'],
            [['firmUID', 'name', 'email', 'phone'], 'string', 'max' => 250],
            ['email','email']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firmUID' => 'firm UID',
            'name' => 'Name',
            'post' => 'Post',
        ];
    }

    public static function viewFields(){
        return ['firmUID','name','post','postID','phone','email'];
    }

    public static function viewRelations(){
        return [];
    }

    public function getPost()
    {
         return Posts::findById($this->postID);
    }

    public function getFirmRelation(){
        return $this->hasOne(Firms::className(),['uid'=>'firmUID']);
    }

    public function getFirm(){
        return $this->firmRelation ? ArrayHelper::toArray($this->firmRelation,[Firms::className()=>Firms::viewFields()]) : null;
    }

    public function existenceFirm(){
        return $this->firm ? true : false;
    }

    public static function getInstanceByUID($uid){
        if ($instance = static::find()->where(['uid'=>$uid])->one()){
            return $instance;
        } else {
            return new static();
        }
    }

    public static function getInstanceByUIDWithoutFirm($uid){
        $instance = static::getInstanceByUID($uid);
        return $instance->existenceFirm() ? new static() : $instance;
    }

}
