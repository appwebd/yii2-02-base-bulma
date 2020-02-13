<?php
/**
 * Profiles
 *
 * @category  Controller
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2018-08-09 14:26:44
 */

namespace app\controllers;

use app\components\DeleteRecord;
use app\models\Profile;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\search\ProfileSearch;
use Exception;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class ProfileController
 *
 * @category  Controller
 * @package   Profile
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   BSD 3-clause Clear license
 * @release   1.0
 * @link      https://appwebd.github.io
 * @date      11/1/18 4:25 PM
 * @php       version 7.2
 */
class ProfileController extends BaseController
{
    const ACTION_TOGGLE_ACTIVE = 'toggle';
    const PROFILE_ID = 'profile_id';

    /**
     * Before action instructions for to do before call actions
     *
     * @param object $action action
     *
     * @return mixed \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {

        if ($this->checkBadAccess($action->id)) {
            return $this->redirect(['/']);
        }
        $bitacora = new Bitacora();
        $bitacora->register(
            Yii::t(
                'app',
                'showing the view'
            ),
            'beforeAction',
            MSG_INFO
        );

        return parent::beforeAction($action);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function behaviors()
    {
        return $this->behaviorsCommon();
    }

    /**
     * Creates a new Profile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {

        $model = new Profile();
        if ($model->load(Yii::$app->request->post())) {
            $this->_saveRecord($model);
        }

        return $this->render(
            ACTION_CREATE,
            [
                MODEL => $model,
                'titleView' => 'Create'
            ]
        );
    }

    /**
     * Save profile record
     *
     * @param object $model app\models\Profile
     *
     * @return bool|Response
     */
    private function _saveRecord($model)
    {
        try {
            $common = new Common();
            $status = $common->transaction($model, 'save');
            $this->saveReport($status);
            if ($status) {
                $primaryKey = BaseController::stringEncode($model->profile_id);
                return $this->redirect([ACTION_VIEW, 'id' => $primaryKey]);
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $exception,
                'saveRecord',
                MSG_ERROR
            );
        }
        return false;
    }

    /**
     * Deletes an existing row of Profile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id primary key of table profile
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $deleteRecord = new DeleteRecord();
        if (!$deleteRecord->isOkPermission(ACTION_DELETE)) {
            return $this->redirect([ACTION_INDEX]);
        }

        $model = $this->findModel($id);
        if ($this->_fkCheck($model->profile_id) > 0) {
            $deleteRecord->report(2);
            return $this->redirect([ACTION_INDEX]);
        }

        try {
            $common = new Common();
            $status = $common->transaction($model, ACTION_DELETE);
            $deleteRecord->report($status);
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash($exception, 'actionDelete', MSG_ERROR);
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int|string $profileId primary key of table Profile
     *
     * @return object Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($profileId)
    {
        $profileId = self::stringDecode($profileId);
        if (($model = Profile::findOne($profileId)) !== null) {
            return $model;
        }
        $event = Yii::t(
            'app',
            'The requested page does not exist {id}',
            ['id' => $profileId]
        );
        $bitacora = new Bitacora();
        $bitacora->register(
            $event,
            'findModel',
            MSG_SECURITY_ISSUE
        );
        throw new NotFoundHttpException(
            Yii::t(
                'app',
                'The requested page does not exist.'
            )
        );
    }

    /**
     * Check nro. records found in other tables related.
     *
     * @param integer $profileId integer Primary Key of table Profile
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    private function _fkCheck($profileId)
    {
        $common = new Common();
        $nro_regs = $common->getNroRowsForeignkey(
            'permission',
            self::PROFILE_ID,
            $profileId
        );

        return $nro_regs + $common->getNroRowsForeignkey(
            'user',
            self::PROFILE_ID,
            $profileId
        );
    }

    /**
     * Lists all Profile models.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $sm_profile = new ProfileSearch();
        $dp_profile = $sm_profile->search(Yii::$app->request->queryParams);

        $pageSize = $this->pageSize();
        $dp_profile->pagination->pageSize = $pageSize;

        return $this->render(
            ACTION_INDEX,
            [
                'searchModelProfile' => $sm_profile,
                'dataProviderProfile' => $dp_profile,
                'pageSize' => $pageSize
            ]
        );
    }

    /**
     * Delete many records of this table Profile
     *
     * @return mixed
     */
    public function actionRemove()
    {

        $result = Yii::$app->request->post('selection');
        $deleteRecord = new DeleteRecord();

        if (!$deleteRecord->isOkPermission(ACTION_DELETE)
            || !$deleteRecord->isOkSelection($result)
        ) {
            return $this->redirect([ACTION_INDEX]);
        }

        $nroSelections = sizeof($result);
        $status = ['','','',''];
        // 0: KO Error deleting record,  1:OK was deleted,
        // 2: Used in the system,        3: Not found record in the system

        for ($counter = 0; $counter < $nroSelections; $counter++) {
            try {
                $primaryKey = $result[$counter];
                $model = Profile::findOne($primaryKey);
                $fkCheck = $this->_fkCheck($primaryKey);
                $item = $deleteRecord->remove($model, $fkCheck);
                $status[$item] .= $primaryKey . ',';
            } catch (Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->registerAndFlash(
                    $exception,
                    'actionRemove',
                    MSG_ERROR
                );
            }
        }

        $deleteRecord->summaryDisplay($status);
        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Toggle the value active in the table Profile
     *
     * @param int $id primary Key of table profile
     *
     * @return Response
     */
    public function actionToggle($id)
    {

        if (!Yii::$app->request->isPost || !isset($id)) {
            return $this->redirect([ACTION_INDEX]);
        }

        if (!Profile::toggleActive($id)) {
            $event = Yii::t(
                'app',
                'Record {id} was not possible to update the value active',
                ['id' => $id]
            );
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash($event, 'actionToggle', MSG_ERROR);
        }

        return $this->redirect([ACTION_INDEX]);
    }

    /**
     * Updates an existing Profile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id primary key of table Profile
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            return $this->_saveRecord($model);
        }

        return $this->render(
            ACTION_CREATE,
            [
                MODEL => $model,
                'titleView' => 'Update'
            ]
        );
    }

    /**
     * Displays a single Profile model.
     *
     * @param integer $id primary key of table Profile
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $event = Yii::t('app', 'view record {id}', ['id' => $model->profile_id]);
        $bitacora = new Bitacora();
        $bitacora->register($event, 'actionView', MSG_INFO);

        return $this->render(ACTION_VIEW, [MODEL => $model]);
    }
}
