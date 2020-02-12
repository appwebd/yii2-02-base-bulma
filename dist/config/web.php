<?php
/**
 * Web configuration
 * PHP Version 7.3.0
 *
 * @category  Config
 * @package   Web
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-06-16 23:03:06
 */

$bundles = require __DIR__ . '/bundles.php';
$db = require __DIR__ . '/db.php';
$params = require __DIR__ . '/params.php';

$config = [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        [
            'class' => 'app\components\LanguageSelector',
            'supportedLang' => ['en', 'es'],
        ],
    ],

//'catchAll' => self::env('MAINTENANCE', false) ? ['site/maintenance'] : null,
// https://www.yiiframework.com/doc/api/2.0/yii-filters-hostcontrol
// the following configuration is only preferred like last resource (is preferable web server configuration instead)
    /*
        'as hostControl' => [
            'class' => 'yii\filters\HostControl',
            'allowedHosts' => [
                'base.local',
                '*.base.local',
            ],
            'fallbackHostInfo' => 'https://base.local',
        ],
    */

    'charset' => 'UTF-8',
    'components' => [
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => false,
            'class' => 'yii\web\AssetManager',
            'bundles' => $bundles,
            'forceCopy' => true
        ],
        'cache' => DISABLE_CACHE ?
            'yii\caching\DummyCache' :
            [
                'class' => 'yii\caching\FileCache',
            ],
        'db' => $db,
        'errorHandler' => [
            'maxSourceLines' => 20,
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'dateFormat' => 'd-M-Y',
            'datetimeFormat' => 'd-M-Y H:i:s',
            'timeFormat' => 'H:i:s',

            'locale' => 'es-ES', //your language locale
            'defaultTimeZone' => 'Chile/Continental', // time zone
        ],
        'i18n' => [
            'translations' => [
                'yii' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath' => '@app/messages',
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                    'on missingTranslation' => ['app\components\TranslationEvent', 'missingTrans']
                ],
            ],
        ],
        'log' => [
            'flushInterval' => 1,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                'file' => [
                    STR_CLASS => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'logFile' => '@runtime/logs/app.log',
                    'except' => [
                      'yii\web\HttpException:404',
                    ],
                ],
                /*
                'file'=>[
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info'],
                    'logFile' => '@runtime/logs/sql.log',
                    'categories' => [
                        'yii\db\*',
                    ],
                    'except' => [
                      'yii\web\HttpException:404',
                    ],
                ],
                'email' => [
                    'class' => 'yii\log\EmailTarget',
                    'except' => ['yii\web\HttpException:404'],
                    'levels' => ['error', 'warning'],
                    //'categories' => ['yii\db\*'],
                    'message' => ['from' => 'pro@localhost', 'to' => 'pro@localhost'],
                    'subject' => 'Database errors at example.com',
                ],*/
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@app/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'root@dev-master.local',
                'password' => 'password', // your password
                'port' => '25',
//                'encryption' => 'tls',
            ],
        ],
        'cookies' => [
            'class' => 'yii\web\Cookie',
            'httpOnly' => true,
            'secure' => true
        ],
        'request' => [
            // !!! insert a secret key in the following
            // (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'BCF5F4707D8FE67F7FDB8CB153D05E12907F6F82',
            'enableCsrfValidation' => true,
            'enableCookieValidation' => true,
/*
            'csrfCookie' => [
                'httpOnly' => true,
                'secure' => true
            ]
*/
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'sessionTable' => 'session',
            //'name' => 'MYAPPSID',
            //'savePath' => '@app/tmp/sessions',
            'timeout' => 1440, //24 minutes?
            'cookieParams' => [
                'httpOnly' => true,
                'secure' => true
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['/login/index'],
/*
            'identityCookie' => [
                'name' => '_identity',
                'httpOnly' => true,
                'secure' => true,
            ],
*/

        ],
/*
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // Disable r= routes
            'enablePrettyUrl' => true,
            // Disable index.php
            'showScriptName' => true,
//            'enableStrictParsing' => true,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                'defaultRoute' => '/site/index',
                '' => '/site/index' // En caso de esablecer enableStrictParsing=>true
            ],
        ],
*/
    ],

    'defaultRoute' => 'site/index',
    'id' => 'basic',
    'language' => 'en',
    'layoutPath' => '@app/views/layouts',
    'name' => 'Base',
    'params' => $params,
    'sourceLanguage' => 'en',
    'vendorPath' => '@app/vendor',
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP
        // if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP
        // if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['components']['assetManager']['forceCopy'] = true;
}

return $config;
