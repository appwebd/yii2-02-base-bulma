<?php
/**
 * Common routines
 *
 * @package     Common funcions
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-08-23 19:19:35
 * @version     1.0
 */

namespace app\models\queries;

use app\models\Action;
use app\models\Controllers;
use app\models\Permission;
use app\models\User;
use Closure;
use yii;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\Query;

class Common extends ActiveRecord
{
    const DENY_ACCESS = 0;
    const PERMIT_ACCESS = 1;
    const PROFILE_ID_ADMINISTRATOR = 99;
    const PROFILE_ID_VISIT = 0;
    const DONT_REMOVE = 1;
    const ROWS_ZERO = 0;
    const ROWS_ONE = 1;
    const USER_ID_VISIT = 0;

    /**
     * Get database name current conextion
     *
     * @return string
     * @throws Exception
     */
    public static function getDatabase()
    {
        return Yii::$app->db->createCommand("SELECT DATABASE()")->queryScalar();
    }

    /**
     * Get the difference of two dates (one delivered and the other is now ())
     *
     * @param string $dateInitial in format YYYY-mm-dd H:i:s
     *
     * @return string Get date time
     */
    public static function getDateDiffNow($dateInitial)
    {

        try {
            $now = Common::getNow();

            $return = 10000; // default any value greather than zero
            $differenceMinutes = (strtotime($now) - strtotime($dateInitial)) / 60; // answer in minutes
            if (isset($differenceMinutes)) {
                $return = $differenceMinutes;
            }
            return $return;
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register($exception, 'app\models\queries\common::getDateDiffNow', MSG_ERROR);
        }
        return 10000; // default any value greather than zero
    }

    /**
     * Get date time of database
     *
     * @return string Get date time
     * @throws Exception
     */
    public static function getNow()
    {
        $result = ((new Query())->select('now()')
            ->limit(1)->createCommand())->queryColumn();

        $return = date(DATEFORMAT);
        if (isset($result[0])) {
            $return = $result[0];
        }
        return $return;
    }

    /**
     * Get column description of table
     *
     * @param string $table  name of table
     * @param string $column name of column
     * @param string $field  name of column to compare with value
     * @param string $value  value of column to compare
     *
     * @return string description value
     */
    public function getDescription($table, $column, $field, $value)
    {
        $return = '';
        try {
            $result = ((new Query())->select($column)
                ->from($table)
                ->where([$field => $value])
                ->limit(1)->createCommand())->queryColumn();
            $return = '';
            if (isset($result[0])) {
                $return = $result[0];
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Common::getDescription',
                MSG_ERROR
            );
        }

        return $return;
    }

    /**
     * Get the count of a table
     *
     * @param string $table table name
     *
     * @return int value of count nro of records in table
     */
    public static function getNroRows($table)
    {

        $count = (new Query())->select('COUNT(*)')->from($table)->limit(1);
        $return = Common::ROWS_ZERO;
        if (isset($count)) {
            $return = $count->count();
        }
        return $return;
    }

    /**
     * Get the numbers of rows in other tables with integrity referential found.
     *
     * @param string $table name of table in database
     * @param string $field column name in table
     * @param string $value value of column field
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    public function getNroRowsForeignkey($table, $field, $value)
    {
        $count = (new Query())->select('count(*)')
            ->from($table)
            ->where(["$field" => $value])
            ->limit(1);

        $return = Common::ROWS_ZERO;
        if (isset($count)) {
            $return = $count->count();
        }
        return $return;
    }

    /**
     * Get formatted token template of widget gridView
     *
     * @param string $showButtons what buttons should show in the view
     *
     * @return string
     */
    public static function getProfilePermissionString($showButtons = '111')
    {
        $aButton = str_split($showButtons, 1);

        $template = '';
        if ($aButton[0] && Common::getProfilePermission(ACTION_VIEW)) {
            $template .= '{view} ';
        }

        if ($aButton[1] && Common::getProfilePermission(ACTION_UPDATE)) {
            $template .= ' {update} ';
        }

        if ($aButton[2] && Common::getProfilePermission(ACTION_DELETE)) {
            $template .= ' {delete}';
        }
        return $template;
    }

    /**
     * Check user permission for any resources like tables (Controllers/Action)
     *
     * @param string $actionName Name of action
     *
     * @return int grant or deny access
     * @throws yii/db/Exception
     */
    public static function getProfilePermission($actionName)
    {
        try {
            $usero = new UserMethods();
            $userId = $usero->getUserId();
            $profileId = $usero->getProfileUser($userId);
            if (!$profileId) {
                $profileId = Common::PROFILE_ID_VISIT;
            }

            $actionPermission = Common::DENY_ACCESS;

            if ($profileId == Common::PROFILE_ID_ADMINISTRATOR) {
                return Common::PERMIT_ACCESS;
            }

            $controllerName = Yii::$app->controller->id;  // controller name
            $controllerId = Controllers::getControllerId($controllerName);
            $actionId = Action::getActionId($actionName);

            if (isset($controllerId) && isset($actionId)) {
                $actionPermission = Permission::getPermission(
                    $actionId,
                    $controllerId,
                    $profileId
                );
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Common::getProfilePermission',
                MSG_ERROR
            );
            $actionPermission = Common::DENY_ACCESS;
        }

        return $actionPermission;
    }

    /**
     * @return int profile of User
     */
    public static function getProfile()
    {
        $profileId = Common::PROFILE_ID_VISIT;

        if (isset(Yii::$app->user->identity->profile->profile_id)) {
            $profileId = Yii::$app->user->identity->profile->profile_id;
        }

        return $profileId;
    }

    /**
     * @return Closure
     */
    public static function isActive()
    {
        return function ($model) {
            return ($model->active == 1) ? Yii::t('app', 'Yes') : 'No';
        };
    }

    /**
     * Get information for dropdown list
     *
     * @param mixed  $model         Object defined in app\models to get information
     * @param string $parentModelId column related model
     * @param int    $valueId       id to search in model
     * @param int    $ookey         column to get column code
     * @param string $value         column to get column description
     * @param string $orderBy       Order by column
     * @param string $selected      Selected column
     *
     * @return string String
     */
    public static function relatedDropdownList(
        $model,
        $parentModelId,
        $valueId,
        $ookey,
        $value,
        $orderBy,
        $selected = ''
    ) {
        $rowso = $model::find()->where([$parentModelId => $valueId])->orderBy([$orderBy => SORT_ASC])->all();

        $dropdown = '<select>';
        $dropdown .= HTML_OPTION . Yii::t('app', 'Please select one option') . HTML_OPTION_CLOSE;

        if (count($rowso) > 0) {
            foreach ($rowso as $column) {
                if ($selected == $column->$ookey) {
                    $dropdown .= '<option value="' . $column->$ookey . '" selected>' .
                        $column->$value
                        . HTML_OPTION_CLOSE;
                } else {
                    $dropdown .= '<option value="' . $column->$ookey . '">'
                        . $column->$value
                        . HTML_OPTION_CLOSE;
                }
            }
        } else {
            $dropdown .= HTML_OPTION . Yii::t('app', 'No results found') . HTML_OPTION_CLOSE;
        }

        return $dropdown . '</select>';
    }

    /**
     * Toggle some value of table tabl_tablas
     *
     * @param string $tableName  Name of table
     * @param string $columnName toggle Column to do toggle
     * @param string $pkName     primary key of table $tableName
     * @param int    $pkValue    value
     *
     * @return bool
     * @throws \Exception
     */
    public static function toggleColumn(
        $tableName,
        $columnName,
        $pkName,
        $pkValue
    ) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $sqlcode = 'UPDATE ' . $tableName .
                ' SET ' . $columnName . '= not(' . $columnName . ')
                WHERE
                        ' . $pkName . ' = ' . $pkValue;

            Yii::$app->db->createCommand($sqlcode)->execute();
            $msg = Yii::t(
                'app',
                'Successfully updated column!'
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $msg,
                'app\models\queries\Common::toggleColumn',
                MSG_SUCCESS
            );
            $transaction->commit();
            return true;
        } catch (Exception $exception) {
            $transaction->rollBack();
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $exception,
                'app\models\queries\Common::toggleColumn',
                MSG_ERROR
            );
            throw $exception;
        }
    }

    /**
     * Execute the database transactions
     *
     * @param object $model  mixed class defined in @app\models\
     * @param string $method the method associated with the model (save, delete)
     *
     * @return bool Success o failed to create/update a $model in this view
     */
    public function transaction(&$model, $method)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->$method()) {
                $transaction->commit();
                $bitacora = new Bitacora();
                $bitacora->register(
                    'OK transaction method:' . $method,
                    'app\models\queries\Common::transaction',
                    MSG_SUCCESS
                );
                return true;
            }
            $transaction->rollBack();
        } catch (Exception $exception) {
            $transaction->rollBack();
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Common::transaction::' . $method,
                MSG_ERROR
            );
        }

        return false;
    }

    /**
     * Execute SQLCreate command
     *
     * @param string $sqlcode string sql instruction
     *
     * @return bool int true/false answer: query was executed with errors?
     */
    public static function sqlCreateCommand($sqlcode)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Yii::$app->db->createCommand($sqlcode)->execute();
            $transaction->commit();
            return true;
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Common::sqlCreateCommand::' . $sqlcode,
                MSG_ERROR
            );
            $transaction->rollBack();
        }
        return false;
    }
}
