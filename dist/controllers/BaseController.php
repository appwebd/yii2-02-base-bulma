<?php
/**
 * Class BaseController
 * PHP Version 7.2
 *
 * @category  Controller
 * @package   Ui
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 8:13 PM
 */

namespace app\controllers;

use app\models\Action;
use app\models\Controllers;
use app\models\Logs;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\Status;
use app\models\User;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Class BaseController
 *
 * @category Controllers
 * @package  BaseController
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  BSD 3-clause Clear license
 * @link     https://appwebd.github.io
 */
class BaseController extends Controller
{

    const ACTION_TOGGLE_ACTIVE = 'toggle';
    const SHA256 = 'sha256';
    const ENCRIPTED_METHOD = 'AES-256-CBC';
    const SECRET_KEY = 'money20343';
    const SECRET_IV = '2034312280';
    const STR_PER_PAGE = 'per-page';
    const STR_PAGESIZE = 'pageSize';

    /**
     * Behaviors Common
     *
     * @return array
     */
    public function behaviorsCommon()
    {
        return [
            'access' => [
                STR_CLASS => AccessControl::class,
                'only' => [
                    ACTION_CREATE,
                    ACTION_DELETE,
                    ACTION_INDEX,
                    self::ACTION_TOGGLE_ACTIVE,
                    ACTION_REMOVE,
                    ACTION_UPDATE,
                    ACTION_VIEW
                ],
                'rules' => [
                    [
                        ACTIONS => [
                            ACTION_CREATE,
                            ACTION_DELETE,
                            ACTION_INDEX,
                            self::ACTION_TOGGLE_ACTIVE,
                            ACTION_REMOVE,
                            ACTION_UPDATE,
                            ACTION_VIEW
                        ],
                        ALLOW => true,
                        ROLES => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                STR_CLASS => VerbFilter::class,
                ACTIONS => [
                    ACTION_CREATE => ['get',  'post'],
                    ACTION_DELETE => ['post'],
                    ACTION_INDEX => ['get'],
                    self::ACTION_TOGGLE_ACTIVE => ['post'],
                    ACTION_REMOVE => ['post'],
                    ACTION_UPDATE => ['get',  'post'],
                    ACTION_VIEW => ['get'],
                ],
            ],
        ];
    }

    /**
     * To show information about status delete record
     *
     * @param integer $status false=0/true=1/2 of transaction delete record in table
     *
     * @return void
     */
    public function deleteReport($status)
    {

        switch ($status) {
            case 0:
                $msgText = 'There was an error removing the record';
                $msgStatus = ERROR;
                break;
            case 1:
                $msgText = 'Record has been deleted';
                $msgStatus = SUCCESS;
                break;
            default:
                $msgText = 'Record could not be deleted because it is being used in the system';
                $msgStatus = ERROR;
                break;
        }

        $msgText = Yii::t('app', $msgText);
        Yii::$app->session->setFlash($msgStatus, $msgText);
    }

    /**
     * Get the common Directory to upload files.
     *
     * @return string
     */
    public static function getDirectoryUpload()
    {
        $uploadDirectory = Yii::$app->params['upload_directory'];
        if (!isset($uploadDirectory)) {
            $uploadDirectory = '/web/uploads/';
        }

        if (!file_exists(Yii::$app->basePath . $uploadDirectory)) {
            mkdir(Yii::$app->basePath . $uploadDirectory, 0777);
            $bitacora = new Bitacora();
            $event =  Yii::t(
                'app',
                'To upload files was created the directory: {dir}',
                ['dir' => $uploadDirectory]
            );
            $bitacora->register(
                $event,
                'getDirectoryUpload',
                MSG_ERROR
            );
        }

        return $uploadDirectory;
    }

    /**
     * Get a $_POST variable or get/set a session variable
     *
     * @param string $name        Name
     * @param bool   $boolSession Get or set variable session for remember
     *                            saved value
     * @param string $defautValue Default value
     *
     * @return string
     */
    public static function getPostVar($name, $boolSession, $defautValue = 0)
    {
        $request = Yii::$app->request->post($name);
        if (isset($request)) {
            $return = $request;
            if ($boolSession) {
                Yii::$app->session->set($name . '_selected', (int)$request);
            }
        } else {
            if ($boolSession) {
                $return = Yii::$app->session->get($name . '_selected', $defautValue);
            }
        }
        if (!isset($return) || empty($return)) {
            $return = $defautValue;
        }
        return $return;
    }

    /**
     * Previous requirement to remove a records
     *
     * @param string $action Valid if this request is Post and get profile
     *                       permission
     *
     * @return boolean
     */
    public function okRequirements($action)
    {
        if (!Yii::$app->request->isPost) {
            $bitacora = new Bitacora();
            $event = Yii::t(
                'app',
                'Page not valid Please do not repeat this requirement.
                All site traffic is being monitored'
            );
            $bitacora->registerAndFlash(
                $event,
                'app/controllers/BaseController::okRequirements',
                MSG_SECURITY_ISSUE
            );
            return false;
        }

        if (!Common::getProfilePermission($action)) {
            $event = Yii::t(
                'app',
                'Your account don\'t have priviledges for this action,
                please do not repeat this requirement. All site traffic is being monitored'
            );
            $bitacora = new Bitacora();
            $bitacora->register(
                $event,
                'app/controllers/BaseController::okRequirements',
                MSG_ERROR
            );

            return false;
        }

        return true;
    }

    /**
     * Verify if the variable $result has information (used for delete records
     * of gridview)
     *
     * @param string $result
     *
     * @return bool
     */
    public function okSeleccionItems($result)
    {
        if (!isset($result)) {
            $event = Yii::t(
                'app',
                'called to remove items,
                but has not send selection of records to remove:
                Possible Security issue event?'
            );
            $bitacora = new Bitacora();
            $bitacora->register(
                $event,
                'app/controllers/BaseController::okSeleccionItems',
                MSG_SECURITY_ISSUE
            );
            return false;
        }
        return true;
    }
    /**
     * Get / remember pageSize saved in session
     *
     * @return array|mixed
     */
    public function pageSize()
    {

        $session = Yii::$app->session;
        $pageSize = Yii::$app->request->get(self::STR_PER_PAGE);
        $token = Yii::$app->controller->id.'.'.self::STR_PAGESIZE;
        if (!isset($pageSize)) {
            $pageSize = Yii::$app->request->post(self::STR_PER_PAGE);
            if (!isset($pageSize)) {
                $pageSize = $session[$token];
                if (!isset($pageSize)) {
                    $pageSize = Yii::$app->params['pageSizeDefault'];
                }
            }
        }

        $session->set($token, $pageSize);
        return $pageSize;
    }
    /**
     * Resume of operation
     *
     * @param string $deleteOK String with all the records deleted
     * @param string $deleteKO string with all the records not deleted for some reason.
     * @param string $deleteUs String with all the records it are using in the system.
     * @param string $deleteNf String with all the records not found in database
     */
    public function summaryDisplay($deleteOK, $deleteKO, $deleteUs, $deleteNf)
    {
        if (isset($deleteOK{1})) {
            $event = Yii::t(
                'app',
                'Records selected: \'{ids}\' has been deleted.',
                ['ids' => $deleteOK]
            );
            $bitacora = new Bitacora();
            $bitacora->register(
                $event,
                'app/controllers/BaseController::summaryDisplay',
                MSG_ERROR
            );
        }

        if (isset($deleteKO{1})) {
            $event =  Yii::t(
                'app',
                'Selected records: \'{ids}\' a problem occurred removing the record',
                ['ids' => $deleteKO]
            );
            $bitacora = new Bitacora();
            $bitacora->register(
                $event,
                'app/controllers/BaseController::summaryDisplay',
                MSG_ERROR
            );
        }

        if (isset($deleteUs{1})) {
            $event = Yii::t(
                'app',
                'Selected records: \'{ids}\' have not been deleted, they are being used in the system',
                ['ids' => $deleteUs]
            );
            $bitacora = new Bitacora();
            $bitacora->register(
                $event,
                'app/controllers/BaseController::summaryDisplay',
                MSG_ERROR
            );
        }

        if (isset($deleteNf{1})) {
            $event = Yii::t(
                'app',
                'Selected records: \'{ids}\' was not found in the database',
                ['ids' => $deleteNf]
            );
            $bitacora = new Bitacora();
            $bitacora->register(
                $event,
                'app/controllers/BaseController::summaryDisplay',
                MSG_ERROR
            );
        }
    }

    /**
     * Generate a random string (default length 20 chars)
     *
     * @param int $length length chars to generate string
     *
     * @return string random string
     */
    public static function randomString($length = 20)
    {

        $randstr = '';
        srand((double)microtime(true) * 1000000);
        //our array add all letters and numbers if you wish
        $chars = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'p',
            'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '1', '2', '3', '4', '5',
            '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $totalChars = count($chars) - 1;
        for ($iterator = 0; $iterator <= $length; $iterator++) {
            $random = 0;
            try {
                $random = random_int(0, $totalChars);
            } catch (\Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->register(
                    $exception,
                    'app/controllers/BaseController::randomString',
                    MSG_ERROR
                );
            }
            $randstr .= $chars[$random];
        }
        return $randstr;
    }

    /**
     * Show a status message of saving record
     *
     * @param boolean $status
     *
     * @return void
     */
    public function saveReport($status)
    {
        $msgType = ERROR;
        $msg = 'Error saving record';

        if ($status) {
            $msgType = SUCCESS;
            $msg = 'Record saved successfully';
        }

        Yii::$app->session->setFlash(
            $msgType,
            Yii::t(
                'app',
                $msg
            )
        );
    }

    /**
     * Encode a string
     *
     * @param string $plaintext plain text (text to encode)
     *
     * @return string
     */
    public static function stringEncode($plaintext)
    {
        if (is_array($plaintext)) {
            $newPlainText = '';
            foreach ($plaintext as $value) {
                $newPlainText .=  $value. '|';
            }
            $plaintext = $newPlainText;
        }

        $encryptmethod = self::ENCRIPTED_METHOD;
        $secretkey = self::SECRET_KEY;
        $secretiv = self::SECRET_IV;

        // hash
        $keyValue = hash(self::SHA256, $secretkey);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $ivencripted = substr(hash(self::SHA256, $secretiv), 0, 16);
        $output = openssl_encrypt($plaintext, $encryptmethod, $keyValue, 0, $ivencripted);

        return base64_encode($output);
    }

    /**
     * Remove a file
     *
     * @param  string $file full path of file to remove
     * @return bool
     */
    public static function removeFile($file)
    {
        try {
            if (file_exists($file)) {
                unlink($file);
                $event = Yii::t(
                    'app',
                    'file: {file} was removed',
                    [
                        'file' => $file
                    ]
                );
                $bitacora = new Bitacora();
                $bitacora->register(
                    $event,
                    'app\controllers\BaseControllers::removeFile',
                    MSG_SUCCESS
                );
                return true;
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\controllers\BaseControllers::removeFile',
                MSG_ERROR
            );
        }
        return false;
    }

    /**
     * Check if user profile has access privilege over one controller/action
     *
     * @param string $action action name
     *
     * @return bool
     */
    public function checkBadAccess($action)
    {
        if (!Common::getProfilePermission($action)) {
            $event = Yii::t(
                'app',
                'Your account don\'t have priviledges for this action,
                    please do not repeat this requirement. All site traffic is being monitored'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'checkBadAccess',
                MSG_SECURITY_ISSUE
            );
            return true;
        }

        return false;
    }

    /**
     * Save/retrieve Query Params
     *
     * @param string $stringParam Params to retrieve or save.
     *
     * @return array|mixed
     */
    public function getQueryParams($stringParam)
    {
        $params = Yii::$app->request->queryParams;

        if (count($params) <= 1) {
            $params = Yii::$app->session[$stringParam];
        } else {
            Yii::$app->session[$stringParam] = $params;
        }
        return $params;
    }

    /**
     * Toggle the some values of table status
     *
     * @param string $id String encoded with BaseController::stringEncode that
     *                   contains
     *        string $tableName
     *        string $columnName
     *        string $pkName
     *        int $pkValue integer primary Key of table $tableName
     *        string $redirect
     *
     * @return Response
     * @throws \Exception
     */
    public function actionToggle($id)
    {

        $string = BaseController::stringDecode($id);

        list($tableName,
            $columnName,
            $columnValue,
            $pkName,
            $pkValue,
            $redirect) = explode('|', $string);

        if (!Yii::$app->request->isPost || !isset($pkValue)) {
            return $this->redirect([$redirect]);
        }

        Common::toggleColumn($tableName, $columnName, $pkName, $pkValue);

        return $this->redirect([$redirect]);
    }

    /**
     * Decode a string
     *
     * @param  string $ciphertext text to decode
     * @return string decoded
     */
    public static function stringDecode($ciphertext)
    {

        $encryptmethod = self::ENCRIPTED_METHOD;
        $secretkey = self::SECRET_KEY;
        $secretiv = self::SECRET_IV;

        // hash
        $keyValue = hash(self::SHA256, $secretkey);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $ivencripted = substr(hash(self::SHA256, $secretiv), 0, 16);
        return openssl_decrypt(base64_decode($ciphertext), $encryptmethod, $keyValue, 0, $ivencripted);
    }
}
