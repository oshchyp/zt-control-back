<?php

namespace app\modules\api\controllers;

use app\components\filter\Filter;
use app\components\filter\FilterDataInterface;
use app\components\filter\FilterDataTrait;
use app\components\models\ModelAsExtraDataInterface;
use app\components\models\ModelAsResourceInterface;
use Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
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

    /**
     * @var ModelAsResourceInterface
     */
    public $resource;

    /**
     * @var ActiveQuery
     */
    public $query;

    public $params = [];

    /**
     * @var ModelAsResourceInterface|ModelAsResourceInterface[]
     */
    public $responseData = [];

    public $responseExtraData = [];

    public $validationInfo = [];

    public $responseErrors = [];

    public $responseCode;

    public $rowsPerPageDefault = 10;

    public function init()
    {


       Yii::$app->response->on(Response::EVENT_BEFORE_SEND, function ($event) {
            $response = $event->sender;
            if ($response->statusCode == 500 && YII_DEBUG) {
                $this->responseData = $response->data;
            }
            $response->data = $this->responseMsg($this->responseCode ? $this->responseCode : $response->statusCode);
            $response->data['data'] = $this->getResponseData();
            $response->data['extraData'] = $this->responseExtraData;
            $response->data['validationInfo'] = $this->validationInfo;
            $response->data['errors'] = $this->responseErrors;
            $response->statusCode = 200;
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

    /**
     * @param ModelAsResourceInterface $activeRecord
     */
    public function setResource(ModelAsResourceInterface $activeRecord)
    {
        $this->resource = $activeRecord;
        $this->query = $this->resource::find();
        $this->validationInfo = $this->resource->getRestValidators();
    }

    /**
     * @param \app\components\models\ModelAsExtraDataInterface $modelAsExtraData
     * @param string $key
     */
    public function addResponseExtraData(ModelAsExtraDataInterface $modelAsExtraData, $key='extraData'){
       $this->responseExtraData[$key] = $modelAsExtraData::find()->with($modelAsExtraData::relations())->asArray()->all();
    }

    /**
     * @return ModelAsResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return ActiveQuery
     */
    public function getQuery()
    {
        return $this->query;
    }

    public function setResponseParams($responseParamKey)
    {
        $defaultRule = ['code' => 200];
        $rules = $this->rulesSetResponseParams();

        if (!$this->responseData) {
            $condition = 'ifDataEmpty';
        } elseif (is_object($this->responseData) && method_exists($this->responseData,'getErrors') && $this->responseData->getErrors()) {
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
        } elseif (is_object($this->responseData) && method_exists($this->responseData,'getErrors') && $this->responseData->getErrors()) {
            $this->responseErrors = $this->responseData->getErrors();
        }

        if (isset($rules['setData'])) {
            $this->responseData = $rules['setData'];
        }
    }

    public function getRequestData($key = null)
    {
        $result = [];
        if (Yii::$app->request->getRawBody()) {
            $result += Json::decode(Yii::$app->request->getRawBody(), true);
        } elseif (Yii::$app->request->getBodyParams()) {
            $result += Yii::$app->request->getBodyParams();
        }
        if ($key) {
            $result = isset($result[$key]) ? $result[$key] : null;
        }

        return $result;
    }

    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * @param FilterDataInterface $className
     */
    public function filter(FilterDataInterface $className)
    {
        $className::search($this->getRequestData('filter'), $this->getQuery());
    }


    public function getPage(){
        $page  = (int)ArrayHelper::getValue($this->getRequestData(), 'pagination.page', 1)-1;
        return $page <  0 ? 0 : $page;
    }

    public function setPagination()
    {

        $query = clone($this->getQuery());
        $pages = new Pagination(['totalCount' => count($query->asArray()->all())]);
        $pages->setPage($this->getPage());
        $pages->setPageSize(ArrayHelper::getValue($this->getRequestData(), 'pagination.rowsPerPage', $this->rowsPerPageDefault));

        $this->responseExtraData['pagination'] = [
            'links' => $pages->getLinks(),
            'pageCount' => $pages->getPageCount(),
            'pageSize' => $pages->getPageSize(),
            'totalCount' => $pages->totalCount,
        ];

        $this->getQuery()->offset($pages->offset)->limit($pages->limit);

        return $this;
    }

    public function activeIndex()
    {
        $this->query->with($this->resource::relations());
        $this->responseData = $this->query->all();
        $this->setResponseParams(static::RESPONSE_PARAMS_VIEW_DATA_ALL);
    }

    public function activeView($id)
    {
        $this->responseData = $this->query->where(['id'=>$id])->one();
        $this->setResponseParams(static::RESPONSE_PARAMS_VIEW_DATA_ONE);
    }

    public function activeCreate($events=null)
    {
        $this->responseData = $this->resource;
        if ($events) {
            foreach ($events as $item) {
                $this->responseData->on($item[0], $item[1]);
            }
        }
        $this->responseData->attributes = $this->getRequestData();
        $this->responseData->save();
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

    public function activeUpdate($id = null, $events = null)
    {

        if ($id) {
            $this->query->andWhere(['id' => $id]);
        }

        $this->responseData = $this->query->one();
        if ($this->responseData) {
            if ($events) {
                foreach ($events as $item) {
                    $this->responseData->on($item[0], $item[1]);
                }
            }
            $this->responseData->attributes = $this->getRequestData();
            $this->responseData->save();
        }
        $this->setResponseParams(static::RESPONSE_PARAMS_SAVE);
    }

    public function activeDelete($id=null)
    {
        if ($this->getRequestData()) {
            $id = $this->getRequestData();
        }
        if ($id) {
            $this->query->andWhere(['IN', 'id', $id]);
        }

        if (is_array($id)) {
            $this->responseData = $this->query->all();
        } else {
            $this->responseData = $this->query->one();
        }
        if ($this->responseData) {
            if (is_array($this->responseData) && count($this->responseData) < 11) {
                foreach ($this->responseData as $model) {
                    $model->delete();
                }
            } elseif (is_object($this->responseData)) {
                $this->responseData->delete();
            } else {
                $this->responseData = null;
            }
        }
        $this->setResponseParams(static::RESPONSE_PARAMS_DELETE);
    }
}
