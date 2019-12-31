<?php
/**
 * Actions
 *
 * @package     Model Search of Action
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-08-02 20:07:02
 * @version     1.0
 */

namespace app\models\search;

use app\models\Action;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ActionSearch represents the model behind the search form about `app\models\Action`.
 *
 * @property string action_description     Description
 * @property int action_id              Actions
 * @property int controller_id          Controller Id associated
 * @property string action_name            Name
 * @property int active                 Active
 *
 */
class ActionSearch extends Action
{
    const ACTION_DESCRIPTION = 'action_description';
    const ACTION_ID = 'action_id';
    const ACTION_NAME = 'action_name';
    const ACTIVE = 'active';
    const CONTROLLER_ID = 'controller_id';

    /**
     * Get array from Action
     * @param $controllerID int primary key of table action
     * @param $table string name of table
     * @return array
     */
    public static function getActionListSearch($controllerID, $table)
    {
        if (isset($controllerID) && is_numeric($controllerID)) {
            $sqlcode = "SELECT DISTINCT " . self::ACTION_ID . ", " . self::ACTION_NAME . "
                    FROM action
                    WHERE controller_id=$controllerID and " .
                self::ACTION_ID . " in (SELECT DISTINCT " . self::ACTION_ID . "
                                            FROM $table)
                    ORDER BY action_name";
        } else {
            $sqlcode = "SELECT DISTINCT " . self::ACTION_ID . ", " . self::ACTION_NAME . "
                    FROM action
                    WHERE " . self::ACTION_ID . " in (SELECT DISTINCT " . self::ACTION_ID . "
                                            FROM $table)
                    ORDER BY " . self::ACTION_NAME;
        }

        $droptions = Action::findBySql($sqlcode)->asArray()->all();
        return ArrayHelper::map($droptions, self::ACTION_ID, self::ACTION_NAME);
    }

    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'controllers.controller_id',
            'logs.action_id',
            'permission.action_id',
        ]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTION_ID,
                self::CONTROLLER_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::ACTION_DESCRIPTION,
                self::ACTION_NAME], 'safe'],

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
        $query = Action::find();
        $query->joinWith(['controllers']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => ['action_id' => SORT_ASC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'action.action_id' => $this->action_id,
            'action.active' => $this->active,
            'action.controller_id' => $this->controller_id,
            'controllers.controller_id' => $this->controller_id,
            'logs.action_id' => $this->action_id,
            'permission.action_id' => $this->action_id,

        ]);

        $query->andFilterWhere(['like', 'action.action_description', $this->action_description])
            ->andFilterWhere(['like', 'action.action_name', $this->action_name]);

        return $dataProvider;
    }
}
