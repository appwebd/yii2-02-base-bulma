<?php
/**
 * Ipv4 Blocked
 *
 * @package     Model Search of Blocked
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:23
 * @version     1.0
 */

namespace app\models\search;

use app\models\Blocked;
use app\models\Status;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * BlockedSearch represents the model behind the search form about `app\models\Blocked`.
 */
class BlockedSearch extends Blocked
{
    const DATE = 'date';
    const ID = 'id';
    const IPV4_ADDRESS = 'ipv4_address';
    const IPV4_ADDRESS_INT = 'ipv4_address_int';
    const STATUS_ID = 'status_id';
    const SESSION_ID = 'session_id';

    /**
     * Get array from Status
     * @return array
     */
    public static function getStatusListSearch()
    {
        $sqlcode = "SELECT DISTINCT " . self::STATUS_ID . " ," . self::STATUS_NAME . "
                    FROM status
                    WHERE " . self::STATUS_ID . " in (SELECT DISTINCT " . self::STATUS_ID . "
                                                    FROM  blocked)
                    ORDER BY " . self::STATUS_NAME;
        $droptions = Status::findBySql($sqlcode)->asArray()->all();
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
            'status.status_id',
            self::SESSION_ID,

        ]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ID,
                self::IPV4_ADDRESS_INT,
                self::STATUS_ID], 'integer'],
            [[self::DATE], 'datetime'],
            [[self::IPV4_ADDRESS], 'safe'],

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
        $query = Blocked::find();
        $query->joinWith(['status']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => ['blocked.id' => SORT_ASC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'blocked.id' => $this->id,
            'blocked.ipv4_address_int' => $this->ipv4_address_int,
            'blocked.status_id' => $this->status_id,
            self::SESSION_ID => $this->id,
            'status.status_id' => $this->status_id,
        ]);

        $query->andFilterWhere(['like', 'blocked.date', $this->date]);
        $query->andFilterWhere(['like', 'blocked.ipv4_address', $this->ipv4_address]);


        return $dataProvider;
    }
}
