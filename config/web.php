<?php

$params = require __DIR__.'/params.php';
$db = require __DIR__.'/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'Kz2hP74_oPfQuYCwEMa4AJLkoUOZTdKR',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Users',
            'enableAutoLogin' => true,
            'enableSession' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [


                ////USERS
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/users' => 'api/users'],
                ],


                ///////LOGISTICS USERS
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/logistics-users' => 'api/logistics-users'],
                ],


               ///////////FIRMS
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/firms' => 'api/firms'],
                ],
                'POST api/firms/list' => 'api/firms/index',


                ///////REGIONS
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/regions' => 'api/regions'],
                ],




                //////////railway transit
                'PUT,PATCH api/railway-transit' => 'api/railway-transit/update',
                'DELETE api/railway-transit' => 'api/railway-transit/delete',
                'PUT api/railway-transit/complete' => 'api/railway-transit/complete',
                'POST api/railway-transit/completed' => 'api/railway-transit/completed',
                'POST api/railway-transit/list' => 'api/railway-transit/list',
                'GET api/railway-transit/extra-data' => 'api/railway-transit/extra-data',
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['api/railway-transit' => 'api/railway-transit'],
                ],


            ],
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
    ],
    'modules' => [
        'api' => [
            'class' => 'app\modules\api\Module',
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '46.250.12.55'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '213.231.31.66'],
    ];
}

return $config;
