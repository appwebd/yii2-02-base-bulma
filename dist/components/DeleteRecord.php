<?php
/**
 * Contiene las funciones necesarias para eliminar un registro de base de datos.
 * PHP Version 7.0.0
 *
 * @category  DeleteRecord
 * @package   Components
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */

namespace app\components;

use app\models\queries\Bitacora;
use app\models\queries\Common;
use Exception;
use Yii;
use yii\base\Component;

/**
 * Class TaskDelete
 *
 * @category  Components
 * @package   Task
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   Release: <package_version>
 * @link      https://appwebd.github.io
 * @date      11/1/18 11:01 AM
 */
class DeleteRecord extends Component
{
    /**
     * Delete records in one table given the primary key
     *
     * @param string $table      table name
     * @param string $columnName Column name
     * @param int    $primaryKey primary key
     *
     * @return bool
     */
    public function deleteRecord($table, $columnName, $primaryKey)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $sqlcode = "DELETE FROM $table WHERE $columnName = $primaryKey";
            if (Yii::$app->db->createCommand($sqlcode)->execute()) {
                $transaction->commit();

                $bitacora = new Bitacora();
                $bitacora->register(
                    "table: $table delete record $primaryKey",
                    'DeleteRecord::deleteRecord',
                    MSG_SUCCESS
                );
                return true;
            }

            $transaction->rollBack();
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'DeleteRecord::deleteRecord',
                MSG_ERROR
            );
            $transaction->rollBack();
        }

        return false;
    }

    /**
     * Verify permissions to delete records
     *
     * @param string $action valid if this request is Post and get profile permission
     *
     * @return boolean
     */
    public function isOkPermission($action)
    {
        if (!Yii::$app->request->isPost) {
            $event = Yii::t(
                'app',
                'Page not valid Please do not repeat this requirement.
                All site traffic is being monitored'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'isOkPermission',
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
            $bitacora->registerAndFlash(
                $event,
                'isOkPermission',
                MSG_SECURITY_ISSUE
            );
            return false;
        }

        return true;
    }

    /**
     * Verify if the variable $result has information
     * (used for delete records of gridview)
     *
     * @param string $result The message should be showed only if have results
     *
     * @return bool
     */
    public function isOkSelection($result)
    {
        if (!isset($result)) {
            $event = Yii::t(
                'app',
                'called to remove items,
    but has not send selection of records to remove: Possible Security issue event?'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $event,
                'isOkSeleccionItems',
                MSG_SECURITY_ISSUE
            );
            return false;
        }
        return true;
    }

    /**
     * To show information about status delete record
     *
     * @param integer $status false=0/true=1/2 of transaction delete record in table
     *
     * @return void
     */
    public function report($status)
    {
        switch ($status) {
            default:
            case 0:
                $msgText = 'There was an error removing the record';
                $msgStatus = ERROR;
                break;
            case 1:
                $msgText = 'Record has been deleted';
                $msgStatus = SUCCESS;
                break;
            case 2:
                $msgText = 'Record could not be deleted
                because it is being used in the system';
                $msgStatus = ERROR;
                break;
            case 3:
                $msgText = 'Not found record in the system';
                $msgStatus = SUCCESS;
                break;
        }

        $msgText = Yii::t('app', $msgText);
        Yii::$app->session->setFlash($msgStatus, $msgText);
    }

    /**
     * Delete records
     *
     * @param object $model   Model defined inapp/models/
     * @param int    $fkCheck Number of records in related (Foreign key)
     *
     * @return bool
     */
    public function remove($model, $fkCheck)
    {
        $okTransaction = 0; // 0: There was an error removing the record
        if ($model == null) {
            $okTransaction = 3;  // 3: Not found record in the system
        }
        if ($fkCheck > 0) {
            $okTransaction = 2; // Record used in the system
        }

        if ($okTransaction == 0) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $id = $model->getId();
                $bitacora = new Bitacora();
                if ($model->delete()) {
                    $transaction->commit();
                    $okTransaction = 1;
                    $status = MSG_SUCCESS;
                    $msg =  'OK removed record:' . $id;
                } else {
                    $transaction->rollBack();
                    $okTransaction = 0;
                    $status = MSG_ERROR;
                    $msg = 'Error removing record: '. $id;
                }
                $bitacora->register(
                    $msg,
                    'app\components\DeleteRecord::remove',
                    $status
                );
            } catch (Exception $exception) {
                $transaction->rollBack();
                $okTransaction = 0;
                $bitacora = new Bitacora();
                $bitacora->register(
                    $exception,
                    'app\composer\DeleteRecord::remove',
                    MSG_ERROR
                );
            }
        }
        return $okTransaction;
    }

    /**
     * Remove a record table
     *
     * @param object $result      Result post
     * @param object $modelObject ClassName model
     * @param bool   $fkcheckBol  Check Referential integrity
     *
     * @return void
     */
    public function removeRecord($result, $modelObject, $fkcheckBol)
    {
        $nroSelections = sizeof($result);
        $status = ['','','',''];
        // 0: OK was deleted,      1: KO Error deleting record,
        // 2: Used in the system,  3: Not found record in the system

        for ($counter = 0; $counter < $nroSelections; $counter++) {
            try {
                $primaryKey = $result[$counter];
                $ifkCheck = 0;
                $model = $modelObject::findOne($primaryKey);
                if ($fkcheckBol) {
                    $ifkCheck = $model->fkCheck($primaryKey);
                }

                $item = $this->remove($model, $ifkCheck);
                $status[$item] .= $primaryKey . ',';
            } catch (Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->registerAndFlash(
                    $exception,
                    'app\components\DeleteRecord::removeRecord',
                    MSG_ERROR
                );
            }
        }

        self::summaryDisplay($status);
    }

    /**
     * Resume of operation
     *
     * @param array $status String with summary of all the records deleted
     *
     * @return void
     */
    public function summaryDisplay($status)
    {


        if (isset($status[0]) && strlen($status[0])>0) {
            $ids = $status[0];
            $msg = 'Selected records:
            \'{ids}\' a problem occurred removing the record';
            $this->summaryItem($msg, $ids, MSG_ERROR);
        }

        if (isset($status[1]) && strlen($status[1])>0) {
            $ids = $status[1];
            $msg = 'Records selected: \'{ids}\' has been deleted.';
            $this->summaryItem($msg, $ids, MSG_SUCCESS);

        }

        if (isset($status[2]) && strlen($status[2])>0) {
            $ids = $status[2];
            $msg = 'Selected records:
            \'{ids}\' have not been deleted,
            they are being used in the system';
            $this->summaryItem($msg, $ids, MSG_ERROR);
        }

        if (isset($status[3])  && strlen($status[3])>0) {
            $ids = $status[3];
            $msg = 'Selected records: \'{ids}\' was not found in the database';
            $this->summaryItem($msg, $ids, MSG_ERROR);
        }
    }

    /**
     * Show message and ids of deleted records
     *
     * @param string $msg      Message to show
     * @param string $ids      Deleted record set
     * @param int    $statusId Message Status
     *
     * @return void
     */
    public function summaryItem($msg, $ids, $statusId)
    {
        $event = Yii::t('app', $msg, ['ids' => $ids]);
        $bitacora = new Bitacora();
        $bitacora->registerAndFlash($event, 'summaryDisplayItem', $statusId);
    }
}
