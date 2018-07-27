<?php

namespace app\modules\api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\Controller as MainController;
use yii\web\Response;

/**
 * Default controller for the `api` module.
 */
class Controller extends MainController
{
    /*
     * Renders the index view for the module.
     *
     * @return string
     */

    const RESPONSE_PARAMS_AUTH_PHONE = 'response_params_auth_phone';

    const RESPONSE_PARAMS_AUTH = 'response_params_auth';

    const RESPONSE_PARAMS_SAVE = 'response_params_save';

    const RESPONSE_PARAMS_VIEW_DATA_ONE = 'response_params_view_data_one';

    const RESPONSE_PARAMS_VIEW_DATA_ALL = 'response_params_view_data_all';

    const RESPONSE_PARAMS_DELETE = 'response_params_delete';

    const RESPONSE_PARAMS_API = 'response_params_api';

    public $resource;

    public $query;

    public $params = [];

    public $addRequestData = [];

    public $responseData = [];

    public $responseErrors = [];

    public $responseCode;

    public function init()
    {
        Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            if ($response->statusCode == 500 && YII_DEBUG) {
                $this->responseData = $response->data;
            }
            $response->data = $this->responseMsg($this->responseCode ? $this->responseCode : $response->statusCode);
            $response->data['data'] = $this->responseData;
            $response->data['errors'] = $this->responseErrors;
            $response->statusCode = 200;
            $this->setLogs();
        });
    }

    public function behaviors()
    {
        $behaviors = [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            [
                'class' => HttpBearerAuth::className(),
                'only' => isset($this->params['HttpBearerAuth']['only']) ? $this->params['HttpBearerAuth']['only'] : [],
            ],
        ];

        return $behaviors;
    }

    public function responseMsg($respCode)
    {
        $responseMsg = [
            200 => [
                'status' => 'success',
                'msg' => 'Успешно',
            ],
            400 => [
                'status' => 'error',
                'msg' => 'Ошибка',
            ],
            401 => [
                'status' => 'error',
                'msg' => 'ошибка токена',
            ],
            402 => [
                'status' => 'error',
                'msg' => 'Не верный код',
            ],
            403 => [
                'status' => 'error',
                'msg' => 'Доступ запрещен',
            ],
            404 => [
                'status' => 'error',
                'msg' => 'Ресурс не найден',
            ],
            405 => [
                'status' => 'error',
                'msg' => 'Пользователь с этим номером не найден',
            ],
            500 => [
                'status' => 'error',
                'msg' => 'Ошибка сервера',
            ],
        ];
        $responseDefault = 500;

        return isset($responseMsg[$respCode]) ? $responseMsg[$respCode] : $responseMsg[$responseDefault];
    }

    public function rulesSetResponseParams()
    {
        return [
            static::RESPONSE_PARAMS_AUTH_PHONE => [
                'ifDataEmpty' => ['code' => 405, 'setData' => ['auth' => false]],
                'ifSuccess' => ['code' => 200, 'setData' => ['auth' => true]],
            ],
            static::RESPONSE_PARAMS_AUTH => [
                'ifDataEmpty' => ['code' => 403, 'setData' => ['auth' => false]],
                'ifSuccess' => ['code' => 200],
                'ifErrors' => ['code' => 402, 'setData' => ['auth' => false]],
            ],
            static::RESPONSE_PARAMS_SAVE => [
                'ifErrors' => ['code' => 400, 'setData' => []],
                'ifDataEmpty' => ['code' => 404],
            ],
            static::RESPONSE_PARAMS_DELETE => [
                'ifDataEmpty' => ['code' => 404],
            ],
            static::RESPONSE_PARAMS_VIEW_DATA_ONE => [
                'ifDataEmpty' => ['code' => 404],
            ],
            static::RESPONSE_PARAMS_VIEW_DATA_ALL => [
                'ifSuccess' => ['code' => 200],
            ],
            static::RESPONSE_PARAMS_API => [
                'ifErrors' => ['code' => 400, 'setData' => []],
                'ifDataEmpty' => ['code' => 200, 'setData' => []],
            ],
        ];
    }

    public function setResource($obj = null)
    {
        if (is_string($obj) && class_exists($obj)) {
            $this->resource = new $obj();
        } elseif (is_object($obj)) {
            $this->resource = $obj;
        }
        if ($model = $this->resource) {
            // $this->query = $model::find();
        }
    }

    public function setResponseParams($responseParamKey)
    {
        $defaultRule = ['code' => 200];
        $rules = $this->rulesSetResponseParams();

        if (!$this->responseData) {
            $condition = 'ifDataEmpty';
        } elseif (is_object($this->responseData) && $this->responseData->getErrors()) {
            $condition = 'ifErrors';
        } else {
            $condition = 'ifSuccess';
        }

        $rules = ArrayHelper::getValue($rules, $responseParamKey.'.'.$condition, $defaultRule);
        $this->_setResponseParams($rules);
    }

    private function _setResponseParams($rules)
    {
        $this->responseCode = $rules['code'];

        if (isset($rules['setErrors'])) {
            $this->responseErrors = $rules['setErrors'];
        } elseif (is_object($this->responseData) && $this->responseData->getErrors()) {
            $this->responseErrors = $this->responseData->getErrors();
        }

        if (isset($rules['setData'])) {
            $this->responseData = $rules['setData'];
        }
    }

    public function getRequestData($key = null)
    {
        $result = $this->addRequestData;
        if (Yii::$app->request->getRawBody()) {
            $result += json_decode(Yii::$app->request->getRawBody(), true);
        } elseif (Yii::$app->request->getBodyParams()) {
            $result += Yii::$app->request->getBodyParams();
        }
        if ($key) {
            $result = isset($result[$key]) ? $result[$key] : null;
        }

        return $result;
    }

    public function setLogs()
    {
        $file = $this->logDir().'/'.Yii::$app->controller->id.'_'.Yii::$app->controller->action->id.'_'.date('H:i:s').'.json';
        $data = [
            'responseData' => $this->responseData,
            'responseErrors' => $this->responseErrors,
            'requestData' => $this->getRequestData(),
            'requestHeaders' => getallheaders(),
        ];
        file_put_contents($file, json_encode($data));
    }

    public function logDir()
    {
        $dir = 'logs/api/'.date('Y').'/'.date('m').'/'.date('d');
        $dirAll = Yii::getAlias('@app');
        $dirArr = explode('/', $dir);
        foreach ($dirArr as $v) {
            $dirAll .= '/'.$v;
            if (!is_dir($dirAll)) {
                mkdir($dirAll);
            }
        }

        return $dirAll;
    }

    public function activeIndex()
    {
        $this->responseData = $this->resource::find()->all();
        $this->setResponseParams(static::RESPONSE_PARAMS_VIEW_DATA_ALL);
    }

    public function activeView($id)
    {
        $this->responseData = $this->resource::findOne($id);
        $this->setResponseParams(static::RESPONSE_PARAMS_VIEW_DATA_ONE);
    }

    public function activeCreate()
    {
        $this->responseData = $this->resource;
        $this->responseData->attributes = $this->getRequestData();
        $this->responseData->save();
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

    public function activeUpdate($id)
    {
        $this->responseData = $this->resource::findOne($id);
        if ($this->responseData) {
            $this->responseData->attributes = $this->getRequestData();
            $this->responseData->save();
        }
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

    public function activeDelete($id)
    {
        $this->responseData = $this->resource::findOne($id);
        if ($this->responseData) {
            $this->responseData->delete();
        }
        $this->setResponseParams(static::RESPONSE_PARAMS_DELETE);
    }
}
