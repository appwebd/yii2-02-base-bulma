<?php
/**
 * Informative status of events in all the platform
 *
 * @package     Model Search of Status
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:24
 * @version     1.0
 */

namespace app\models\search;

use app\models\Status;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * StatusSearch represents the model behind the search form about `app\models\Status`.
 */
class StatusSearch extends Status
{
    const ACTIVE = 'active';
    const STATUS_ID = 'status_id';
    const STATUS_NAME = 'status_name';

    /**
     * Get array from Status (Only records that exists on table $table)
     * @param $table
     * @return array
     */
    public static function getStatusListSearch($table)
    {
        $sqlcode = 'SELECT DISTINCT ' . self::STATUS_ID . ', ' . self::STATUS_NAME . '
                    FROM status
                    WHERE ' . self::STATUS_ID . ' in (SELECT DISTINCT ' . self::STATUS_ID . '
                                            FROM ' . $table . '
                                       )  ORDER BY ' . self::STATUS_NAME;
        $droptions = Status::findBySql($sqlcode)
            ->orderBy([self::STATUS_NAME => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map($droptions, self::STATUS_ID, self::STATUS_NAME);
    }

    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'blocked.status_id',
            'logs.status_id',

        ]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::STATUS_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::STATUS_NAME], 'safe'],

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
        $query = Status::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => [self::STATUS_ID => SORT_ASC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'status.active' => $this->active,
            'status.status_id' => $this->status_id,
            'blocked.status_id' => $this->status_id,
            'logs.status_id' => $this->status_id,

        ]);


        $query->andFilterWhere(['like', 'status.status_name', $this->status_name]);


        return $dataProvider;
    }
}
