<?php
/**
 * Profiles
 *
 * @package     Model Search of Profile
 * @author      Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        2018-07-30 20:29:24
 * @version     1.0
 */

namespace app\models\search;

use app\models\Profile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ProfileSearch represents the model behind the search form about `app\models\Profile`.
 */
class ProfileSearch extends Profile
{

    const ACTIVE = 'active';
    const PROFILE_ID = 'profile_id';
    const PROFILE_NAME = 'profile_name';

    /**
     * Get array from Profile
     * @param $table
     * @return array
     */
    public static function getProfileListSearch($table)
    {
        $sqlcode = "SELECT DISTINCT " . self::PROFILE_ID . ", " . self::PROFILE_NAME . "
                    FROM profile
                    WHERE " . self::PROFILE_ID . " in (SELECT DISTINCT " . self::PROFILE_ID . "
                                                          FROM $table)
                    ORDER BY " . self::PROFILE_NAME;
        $droptions = Profile::findBySql($sqlcode)
            ->orderBy([self::PROFILE_NAME => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map($droptions, self::PROFILE_ID, self::PROFILE_NAME);
    }

    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'permission.profile_id',
            'user.profile_id',

        ]);
    }

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::PROFILE_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::PROFILE_NAME], 'safe'],

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
        $query = Profile::find();
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        /**
         * Setup your sorting attributes
         */
        $dataProvider->setSort([
            'defaultOrder' => ['profile_id' => SORT_ASC],
        ]);
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'profile.active' => $this->active,
            'profile.profile_id' => $this->profile_id,
            'permission.profile_id' => $this->profile_id,
            'user.profile_id' => $this->profile_id,

        ]);

        $query->andFilterWhere(['like', 'profile.profile_name', $this->profile_name]);


        return $dataProvider;
    }
}
