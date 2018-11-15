<?php
/**
 * Created by PhpStorm.
 * User: programmer_5
 * Date: 26.10.2018
 * Time: 09:50
 */

namespace app\modules\api1\controllers;


use app\models\filter\RTFilter;
use app\models\ReflectionClass;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\web\Response;

class Controller extends ActiveController
{

    /**
     * @var ActiveRecord
     */
    public $modelClass;

    /**
     * @var ActiveRecord|ActiveRecord[]
     */
    public $responseData;

    /**
     * @var array
     */
    public $responseErrors = [];

    /**
     * @var array
     */
    public $responseExtraData;

    /**
     * @var string
     */
    public $responseStatus;

    /**
     * @var bool
     */
    public $enableResponseProcessing = true;

    /**
     * @var bool
     */
    public $responseProcessed = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if ($this->modelClass() === null) {
            throw new InvalidConfigException('The "modelClass" property must be set.');
        }
    }

    /**
     * @return ActiveRecord
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }

    /**
     * @param $modelClass
     */
    public function setModelClass($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    /**
     * @return ActiveRecord|ActiveRecord[]
     */
    public function getResponseData()
    {
        return $this->responseData;
    }

    /**
     * @param $responseData
     */
    public function setResponseData($responseData)
    {
        $this->responseData = $responseData;
    }

    /**
     * @return array
     */
    public function getResponseErrors()
    {
        return $this->responseErrors;
    }

    /**
     * @param $responseErrors
     */
    public function setResponseErrors($responseErrors)
    {
        $this->responseErrors = $responseErrors;
    }

    /**
     * @param $responseErrors
     */
    public function addResponseErrors($responseErrors)
    {
        if ($this->getResponseErrors()) {
            array_merge($responseErrors, $this->responseErrors);
        } else {
            $this->setResponseErrors($responseErrors);
        }
    }


    /**
     * @return array
     */
    public function getResponseExtraData()
    {
        return $this->responseExtraData;
    }

    /**
     * @param $responseExtraData
     */
    public function setResponseExtraData($responseExtraData)
    {
        $this->responseExtraData = $responseExtraData;
    }

    /**
     * @return string
     */
    public function getResponseStatus()
    {
        return $this->responseStatus;
    }

    /**
     * @param $responseStatus
     */
    public function setResponseStatus($responseStatus)
    {
        $this->responseStatus = $responseStatus;
    }


    /**
     * @return array
     */
    public function behaviors()
    {
        return $this->addBehaviors() + $this->standardBehaviors();
    }

    /**
     * @return array
     */
    public function standardBehaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats'] = [
            'json' => Response::FORMAT_JSON
        ];
    //    $behaviors['authenticator'] = ['class' => HttpBearerAuth::className()];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function addBehaviors()
    {
        return [];
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = $this->addActions() + $this->standardActions();
        if ($actions) {
            foreach ($actions as $key => $val) {
                if (!in_array($key, $this->allowActions()) || in_array($key, $this->disableActions())) {
                    unset($actions[$key]);
                    continue;
                }

                if (!isset($val['modelClass']) && property_exists($val['class'], 'modelClass')) {
                    if (method_exists($this, $key . 'ModelClass')) {
                        $methodModelClass = $key . 'ModelClass';
                    } else {
                        $methodModelClass = 'modelClass';
                    }
                    $actions[$key]['modelClass'] = $this->$methodModelClass();
                }
            }
        }
        return $actions;
    }

    /**
     * @return array
     */
    public function standardActions()
    {
        return [
            'index' => [
                'class' => 'app\modules\api1\controllers\actions\RestIndex',
                'prepareDataProvider' => method_exists(static::className(), 'indexDataProvider') ? [$this, 'indexDataProvider'] : null,
                'checkAccess' => [$this, 'checkAccess'],
                'responseProcessing' => [$this, 'responseProcessing']
            ],
            'view' => [
                'class' => 'app\modules\api1\controllers\actions\RestView',
                'checkAccess' => [$this, 'checkAccess'],
                'responseProcessing' => [$this, 'responseProcessing']
            ],
            'create' => [
                'class' => 'app\modules\api1\controllers\actions\RestCreate',
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
                'responseProcessing' => [$this, 'responseProcessing']
            ],
            'update' => [
                'class' => 'app\modules\api1\controllers\actions\RestUpdate',
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->updateScenario,
                'responseProcessing' => [$this, 'responseProcessing']
            ],
            'delete' => [
                'class' => 'app\modules\api1\controllers\actions\RestDelete',
                'checkAccess' => [$this, 'checkAccess'],
                'responseProcessing' => [$this, 'responseProcessing']
            ],
            'options' => [
                'class' => 'app\modules\api1\controllers\actions\RestOptions',
            ],
        ];
    }

    /**
     * @return array
     */
    public function addActions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function allowActions()
    {
        if ($this->standardActions()) {
            return array_keys($this->standardActions());
        }
        return [];
    }

    /**
     * @return array
     */
    public function disableActions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    protected function standardVerbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
            'list' => ['POST'],
            'extra-data' => ['GET', 'HEAD'],
        ];
    }

    protected function addVerbs()
    {
        return [];
    }

    protected function verbs()
    {
        return $this->addVerbs() + $this->standardVerbs();
    }

    /**
     * @return ActiveRecord
     */
    public function modelClass()
    {

        if (\Yii::$app->controller && \Yii::$app->controller->action) {
            $method = \Yii::$app->controller->action->id . 'ModelClass';
            if (method_exists($this, $method)) {
                return $this->$method();
            }
        }
        return $this->modelClass;
    }


    /**
     * @param $model
     * @return array
     */
    public static function modelRelations($model)
    {
        if (method_exists($model,'relations')){
            return $model::relations();
        } else
        return ReflectionClass::getInstance($model)->relationsId();
    }

    /**
     * @return array
     */
    public function addModelExtraData()
    {
        return [];
    }

    /**
     * @return array
     */
    public function modelExtraData()
    {
        $result = [];
        $model = $this->modelClass();
        if ($relations = $this->modelRelations($model)) {
            foreach ($relations as $attribute) {
                $getter = 'get' . $attribute;
                $className = $model::instance()->$getter()->modelClass;
                $query = $className::find();
                if ($relations = static::modelRelations($className)) {
                    $query->with($relations);
                }
                $result[$attribute] = $query->all();
            }
        }
        return $this->addModelExtraData() + $result;
    }

    /**
     * @return ActiveRecord
     */
    public function indexModelClass()
    {
        return $this->modelClass;
    }

    /**
     *
     */
    public function setErrorsAction()
    {
        if (is_object($this->getResponseData()) && $this->getResponseData() instanceof Model && $this->getResponseData()->getErrors()) {
            $this->addResponseErrors($this->getResponseData()->getErrors());
        }
    }

    /**
     *
     */
    public function setStatusAction()
    {
        if ($this->getResponseErrors()) {
            $this->setResponseStatus('error');
        } else {
            $this->setResponseStatus('success');
        }
    }


    /**
     * @param $action
     * @param $result
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        if (!$this->responseProcessed) {
            $result = $this->responseProcessing($result, $action->id);
        }
        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }

    /**
     * @param $data
     * @param $id
     * @param null $actionObject
     * @return array
     */
    public function responseProcessing($data, $id, $actionObject = null)
    {
        $this->responseProcessed = true;
        $this->setResponseData($data);


        $beforeActionMethod = 'beforeAction' . $id;
        if (method_exists($this, $beforeActionMethod)) {
            return $this->$beforeActionMethod($id, $actionObject = null);
        }

        $methods = [
            'setErrorsAction', 'setStatusAction', 'setExtraDataAction'
        ];

        foreach ($methods as $method) {
            $methodAction = $method . $id;
            if (method_exists($this, $methodAction)) {
                $this->$methodAction($data, $id, $actionObject);
            } else if (method_exists($this, $method)) {
                $this->$method($data, $id, $actionObject);
            }
        }

        $responseProcessingMethod = 'responseProcessing' . $id;
        if (method_exists($this, $responseProcessingMethod)) {
            return $this->$responseProcessingMethod($id, $actionObject = null);
        }


        return [
            'status' => $this->getResponseStatus(),
            'data' => $this->getResponseData(),
            'errors' => $this->getResponseErrors(),
            'extraData' => $this->getResponseExtraData()
        ];
    }

    /**
     * @return array|null
     */
    public function filterParams()
    {
        return \Yii::$app->request->getBodyParam('filter');
    }

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     */
    public function paginationParams()
    {
        $pagination = \Yii::$app->request->getBodyParams('pagination');
        $page = (int)ArrayHelper::getValue($pagination, 'page', 1) - 1;
        $rowsPerPage = (int)ArrayHelper::getValue($pagination, 'rowsPerPage', 10);
        if ($page < 1) {
            $page = 1;
        }

        if ($rowsPerPage < 10) {
            $rowsPerPage = 10;
        }
        return [
            'page' => $page,
            'rowsPerPage' => $rowsPerPage
        ];
    }


    public function paginate($query)
    {
        $pages = new Pagination(['totalCount' => $query->count()]);
        $pages->setPage($this->paginationParams()['page']);
        $pages->setPageSize($this->paginationParams()['rowsPerPage']);

        $this->responseExtraData['pagination'] = [
            'links' => $pages->getLinks(),
            'pageCount' => $pages->getPageCount(),
            'pageSize' => $pages->getPageSize(),
            'totalCount' => $pages->totalCount,
        ];

        $query->offset($pages->offset)->limit($pages->limit);
    }

    /**
     * ACTIONS
     */

    public function actionList()
    {
        $model = $this->modelClass();
        $query = method_exists($model, 'search') ? $model::search($this->filterParams()) : $model::find();
        if (static::modelRelations($model)) {
            dump(static::modelRelations($model),1);
            $query->with(static::modelRelations($model));
        }
        $this->paginate($query);
        return $query->all();
    }

    public function actionExtraData()
    {
        return $this->modelExtraData();
    }


}