<?php

namespace app\modules\api\models;

use yii\helpers\ArrayHelper;

class LogisticAPI extends \yii\base\Model
{
    public $responseData = [];

    public $requestData = [];

    public $methodRequest = 'GET';

    public $domain = 'http://logic.comnd-x.com';

    public $apiUrl = 'crmApi';

    private $_token = 'HRfGwLo6ZIQliZ2QBJvNEsey0rvinl0F_token';

    private $_username = 'zt-control';

    private $_password = '123456';


    public function setMethodRequest($method){
        $this->methodRequest = $method;
        return $this;
    }

    public function setRequestData($data){
        $this->requestData = $data;
        return $this;
    }


    /**
     * Get the value of rquestData.
     */
    public function getRequestData()
    {
        return $this->requestData + ['token' => $this->_token];
    }

    public function init()
    {
    }

    public function fields()
    {
        return ['responseData'];
    }

    public function formName()
    {
        return '';
    }

    public function curlApi($method = '')
    {
        $url = $this->domain.'/'.$this->apiUrl.'/'.$method;
        $this->responseData = static::curl($url, $this->getRequestData(), $this->methodRequest);
       //  dump($url, 1);

        return $this;
    }

    public function createResponseApi($objController)
    {
        $errorsArr = $this->getPropertyResponseData('errors', []);
        $this->addErrors(ArrayHelper::toArray($errorsArr));
        if ($this->getPropertyResponseData('status') == 'error') {
            if ($this->getPropertyResponseData('message')) {
                $this->addError('apiError', $this->getPropertyResponseData('message'));
            } elseif ($this->getPropertyResponseData('msg')) {
                $this->addError('apiError', $this->getPropertyResponseData('msg'));
            }
        }
        $objController->responseData = $this;
      //  $objController->responseData
        $objController->setResponseParams($objController::RESPONSE_PARAMS_API);
        $objController->responseData = $this->getPropertyResponseData('data');

        return $this;
    }

    public function getPropertyResponseData($property, $default = null)
    {
        if (!is_object($this->responseData)) {
            return $default;
        }

        return property_exists($this->responseData, $property) ? $this->responseData->$property : $default;
    }

    public function roadsHistory()
    {
        $this->curlApi('roads-history');

        return $this;
    }

    public function roadsHistoryStatistics()
    {
        $this->curlApi('roads-history/statistics');

        return $this;
    }

    public function roads()
    {
        $this->curlApi('roads');

        return $this;
    }

    public function users($id=null)
    {
        $this->curlApi($id ? 'users/'.$id : 'users');

        return $this;
    }

    public function contracts()
    {
        $this->curlApi('contracts');

        return $this;
    }

    public function firms()
    {
        $this->curlApi('firms');

        return $this;
    }

    public function elevators()
    {
        $this->curlApi('elevators');

        return $this;
    }

    public static function curl($url = '', $data = [], $methodRequest = 'GET')
    {
       // dump($data,1);
        $headers = [
            'Content-Type: application/json',
            'Content-Length: '.strlen(json_encode($data)),
            'X-Requested-With: XMLHttpRequest',
            'Accept: application/json, text/javascript, */*; q=0.01',
         ];
        if (isset($data['token'])) {
            $headers[] = 'Authorization: Bearer '.(string) $data['token'];
        }
        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_CUSTOMREQUEST, $methodRequest);
        curl_setopt($s, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($s, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        $result = json_decode(curl_exec($s));
       //   dump(curl_exec($s), 1);
        curl_close($s);

        return $result;
    }
}
