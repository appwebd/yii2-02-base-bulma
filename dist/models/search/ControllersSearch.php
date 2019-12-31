<?php
/**
 * Controllers
 *
 * @package     Model Search of Controllers
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:23
 * @version     1.0
 */

namespace app\models\search;

use app\models\Controllers;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ControllersSearch represents the model behind the search form about `app\models\Controllers`.
 */
class ControllersSearch extends Controllers
{
    const ACTIVE = 'active';
    const CONTROLLER_DESCRIPTION = 'controller_description';
    const CONTROLLER_ID = 'controller_id';
    const CONTROLLER_NAME = 'controller_name';
    const MENU_BOOLEAN_PRIVATE = 'menu_boolean_private';
    const MENU_BOOLEAN_VISIBLE = 'menu_boolean_visible';

    /**
     * Get array from Controllers
     * @param $table string name of table
     * @return array
     */
    public static function getControllersListSearch($table)
    {

        $sqlcode = "SELECT DISTINCT " . self::CONTROLLER_ID . ", " . self::CONTROLLER_NAME . "
                    FROM controllers
                    WHERE " . self::CONTROLLER_ID . " in (SELECT DISTINCT " . self::CONTROLLER_ID . "
                                            FROM $table)
                    ORDER BY " . self::CONTROLLER_NAME;
        $droptions = Controllers::findBySql($sqlcode)
            ->orderBy([self::CONTROLLER_NAME => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map($droptions, self::CONTROLLER_ID, self::CONTROLLER_NAME);
    }

    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'action.controller_id',
            'logs.controller_id',
            'permission.controller_id',
        ]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::CONTROLLER_ID], 'integer'],
            [[self::ACTIVE,
                self::MENU_BOOLEAN_PRIVATE,
                self::MENU_BOOLEAN_VISIBLE], 'boolean'],
            [[self::CONTROLLER_DESCRIPTION,
                self::CONTROLLER_NAME], 'safe'],

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
        $query = Controllers::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => ['controller_id' => SORT_ASC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'controllers.active' => $this->active,
            'controllers.controller_id' => $this->controller_id,
            'controllers.menu_boolean_private' => $this->menu_boolean_private,
            'controllers.menu_boolean_visible' => $this->menu_boolean_visible,
            'action.controller_id' => $this->controller_id,
            'logs.controller_id' => $this->controller_id,
            'permission.controller_id' => $this->controller_id,

        ]);

        $query->andFilterWhere(['like', 'controllers.controller_description', $this->controller_description])
            ->andFilterWhere(['like', 'controllers.controller_name', $this->controller_name]);


        return $dataProvider;
    }
}
