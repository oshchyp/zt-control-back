<?php

namespace app\models;

use app\components\MyBehavior;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int    $id
 * @property string $firstName
 * @property string $lastName
 * @property string $phone
 * @property string $token
 * @property int    $code
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $perm;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone'], 'unique'],
            [['code'], 'integer'],
            [['firstName', 'lastName', 'token'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 10],
            [['perm'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'phone' => 'Phone',
            'token' => 'Token',
            'code' => 'Code',
        ];
    }

    public function fields()
    {
        return [
            'id', 'firstName', 'lastName', 'phone',
         ];
    }


    public function afterSave($insert, $changedAttributes){
        if ($this->perm && is_array($this->perm)){
            Yii::$app->authManager->db->createCommand()
                ->delete(Yii::$app->authManager->assignmentTable, ['user_id' => $this->id])
                ->execute();
            foreach ($this->perm as $item) {
                $roleObj = Yii::$app->authManager->createRole($item);
                Yii::$app->authManager->assign($roleObj,$this->id);
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     *
     * @return IdentityInterface|null the identity object that matches the given ID
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     *
     * @return IdentityInterface|null the identity object that matches the given token
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // die('45tg4545t');

        return static::findOne(['token' => $token]);
    }

    public static function findIdentityByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     *
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validateCode($code)
    {
        if ((int) $this->code !== (int) $code || !$this->code) {
            $this->addError('code', 'The code is not valid');

            return false;
        }

        return true;
    }

    public function setToken($token = null)
    {
        $this->token = $token ? $token : Yii::$app->security->generateRandomString().'_token';
    }

    public function setCode($char = 8)
    {
        $min = pow(10, $char - 1);
        $max = pow(10, $char) - 1;
        $this->code = rand($min, $max);
        $this->code = 35;
    }

    public function sendMsg()
    {
    }


    public function getActions(){
        $permissions = Yii::$app->authManager->getPermissionsByUser($this->id);
        return array_keys($permissions);
    }


    public function getPermissions()
    {
        $permissions = Yii::$app->authManager->getPermissionsByUser($this->id);
        $permissionsInfo = Permissions::getPermissions();
        $result=[];
        if ($permissions){
            foreach ($permissions as $k=>$v){
                $permissionName = isset($permissionsInfo[$k]['convertName']) ? $permissionsInfo[$k]['convertName'] : $k;
                $path = Permissions::permissionPath($k);
                if (isset($path[0]) && !in_array($path[0].'/view',$result)){
                    $result[] = $path[0].'/view';
                }
                $path[] = $permissionName;
                if (!in_array(implode('/',$path),$result)) {
                    $result[] = implode('/', $path);
                }
            }
        }
        return $result;
    }


    public static function authPhone($phone = '')
    {
        $userInfo = self::findIdentityByPhone($phone);
        if (!$userInfo) {
            return false;
        }
        $userInfo->setCode();
        $userInfo->save();
        $userInfo->sendMsg();

        return $userInfo;
    }

    public static function auth($data)
    {
        $userInfo = self::findIdentityByPhone(ArrayHelper::getValue($data, 'phone'));
        if (!$userInfo) {
            return false;
        }
        if ($userInfo->validateCode(ArrayHelper::getValue($data, 'code'))) {
            $userInfo->code = 0;
            if (!$userInfo->token) {
                $userInfo->setToken();
            }
            $userInfo->save();
        }

        return $userInfo;
    }


}
