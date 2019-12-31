<?php
/**
 * Logs (user bitacora)
 *
 * @package     Model Search of Logs
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:23
 * @version     1.0
 */

namespace app\models\search;

use app\models\Action;
use app\models\Controllers;
use app\models\Logs;
use app\models\Status;
use app\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * LogsSearch represents the model behind the search form about `app\models\Logs`.
 */
class LogsSearch extends Logs
{
    const ACTION_ID = 'action_id';
    const ACTION_NAME = 'action_name';
    const CONFIRMED = 'confirmed';
    const CONTROLLER_ID = 'controller_id';
    const DATE = 'date';
    const EVENT = 'event';
    const IPV4_ADDRESS = 'ipv4_address';
    const IPV4_ADDRESS_INT = 'ipv4_address_int';
    const LOGS_ID = 'logs_id';
    const STATUS_ID = 'status_id';
    const SELECT_DISTINCT = 'SELECT DISTINCT ';
    const USER_AGENT = 'user_agent';
    const USER_ID = 'user_id';

    /**
     * Get array from Action
     * @param $controllerId int primary key of table logs
     * @return array
     */
    public static function getActionListSearch($controllerId)
    {
        if (isset($controllerId) && is_numeric($controllerId)) {
            $sqlcode = self::SELECT_DISTINCT . self::ACTION_ID . ', ' . self::ACTION_NAME . '
                        FROM action
                        WHERE controller_id =' . $controllerId . ' and
                              ' . self::ACTION_ID . ' in ( ' . self::SELECT_DISTINCT .
                self::ACTION_ID . ' FROM logs)
                        ORDER BY ' . self::ACTION_NAME;
        } else {
            $sqlcode = self::SELECT_DISTINCT . self::ACTION_ID . ', ' . self::ACTION_NAME . '
                        FROM action
                        WHERE ' . self::ACTION_ID . ' in (' . self::SELECT_DISTINCT . self::ACTION_ID . '
                                            FROM logs)
                        ORDER BY ' . self::ACTION_NAME;
        }

        $droptions = Action::findBySql($sqlcode)->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }

    /**
     * Get array from Controllers
     * @return array
     */
    public static function getControllersListSearch()
    {

        $sqlcode = self::SELECT_DISTINCT . self::CONTROLLER_ID . ", controller_name
                    FROM controllers
                    WHERE " . self::CONTROLLER_ID . " in (SELECT DISTINCT " . self::CONTROLLER_ID . "
                                            FROM logs)
                    ORDER BY controller_name";
        $droptions = Controllers::findBySql($sqlcode)->asArray()->all();
        return ArrayHelper::map($droptions, self::CONTROLLER_ID, 'controller_name');
    }

    /**
     * Get array from Status
     * @return array
     */
    public static function getStatusListSearch()
    {
        $sqlcode = self::SELECT_DISTINCT . self::STATUS_ID . ", status_name
                    FROM status
                    WHERE " . self::STATUS_ID . ' in (' . self::SELECT_DISTINCT . self::STATUS_ID . '
                                            FROM logs)
                    ORDER BY status_name';
        $droptions = Status::findBySql($sqlcode)->asArray()->all();
        return ArrayHelper::map($droptions, self::STATUS_ID, 'status_name');
    }

    /**
     * Get array from Users
     * @return array
     */
    public static function getUserList()
    {
        $sqlcode = self::SELECT_DISTINCT . self::USER_ID . ", concat(firstName, ' ', lastName) as name
                    FROM user
                    WHERE " . self::USER_ID . " in (SELECT " . self::USER_ID . "
                                                FROM logs)
                    ORDER BY name";
        $droptions = User::findBySql($sqlcode)
            ->asArray()->all();
        return ArrayHelper::map($droptions, self::USER_ID, 'name');
    }

    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'action.action_id',
            'controllers.controller_id',
            'status.status_id',
            'user.user_id'
        ]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTION_ID,
                self::CONTROLLER_ID,
                self::IPV4_ADDRESS_INT,
                self::LOGS_ID,
                self::STATUS_ID,
                self::USER_ID], 'integer'],
            [[self::CONFIRMED], 'boolean'],
            [[self::DATE], 'datetime'],
            [[self::EVENT,
                self::IPV4_ADDRESS,
                self::USER_AGENT], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Logs::find();
        $query->joinWith(['action',
            'controllers',
            'status',
            'user',
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,

        ]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => ['logs_id' => SORT_DESC],
        ]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'logs.action_id' => $this->action_id,
            'logs.confirmed' => $this->confirmed,
            'logs.controller_id' => $this->controller_id,
            'logs.ipv4_address_int' => $this->ipv4_address_int,
            'logs.logs_id' => $this->logs_id,

            'logs.status_id' => $this->status_id,
            'logs.user_id' => $this->user_id,
            'action.action_id' => $this->action_id,
            'controllers.controller_id' => $this->controller_id,
            'status.status_id' => $this->status_id,
            'user.user_id' => $this->user_id,

        ]);

        $query->andFilterWhere(['like', 'logs.date', $this->date]);
        $query->andFilterWhere(['like', 'logs.event', $this->event])
            ->andFilterWhere(['like', 'logs.functionCode', $this->functionCode])
            ->andFilterWhere(['like', 'logs.ipv4_address', $this->ipv4_address])
            ->andFilterWhere(['like', 'logs.user_agent', $this->user_agent]);

        return $dataProvider;
    }
}
