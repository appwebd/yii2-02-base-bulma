<?php
/**
 * Permission
 *
 * @package     Model Search of Permission
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:24
 * @version     1.0
 */

namespace app\models\search;

use app\models\Permission;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PermissionSearch represents the model behind the search form about `app\models\Permission`.
 */
class PermissionSearch extends Permission
{
    const ACTION_ID = 'action_id';
    const ACTION_PERMISSION = 'action_permission';
    const CONTROLLER_ID = 'controller_id';
    const PERMISSION_ID = 'permission_id';
    const PROFILE_ID = 'profile_id';

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
            'profile.profile_id',

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
                self::PERMISSION_ID,
                self::PROFILE_ID], 'integer'],
            [[self::ACTION_PERMISSION], 'boolean'],

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
        $query = Permission::find();
        $query->joinWith([
            'action',
            'controllers',
            'profile',
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => ['permission_id' => SORT_ASC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'permission.action_id' => $this->action_id,
            'permission.action_permission' => $this->action_permission,
            'permission.controller_id' => $this->controller_id,
            'permission.permission_id' => $this->permission_id,
            'permission.profile_id' => $this->profile_id,
            'action.action_id' => $this->action_id,
            'controllers.controller_id' => $this->controller_id,
            'profile.profile_id' => $this->profile_id,

        ]);

        return $dataProvider;
    }
}
