<?php
/**
 * Class SourcemessageSearch
 * Source message
 * PHP Version 7.2.0
 *
 * @category  Sourcemessage
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 */

namespace app\models\search;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Sourcemessage;

/**
 * Class SourcemessageSearch represents the model behind the search
 * form about `app\models\Sourcemessage`.
 * Source message
 * PHP Version 7.2.0
 *
 * @category  Sourcemessage
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 */
class SourcemessageSearch extends Sourcemessage
{


    /**
     * Add related fields to searchable attributes
     *
     * @return array with attributes
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
                                'blocked.id',
                'message.id',
                'session.id',
                'blocked.id',
                'message.id',
                'session.id',

            ]
        );
    }

    /**
     * Rules of validation
     *
     * @return array
     */
    public function rules()
    {
        return [
            [[self::ID], 'integer'],
            [[self::CATEGORY,
              self::MESSAGE], 'safe'],

        ];
    }

    /**
     * Scenarios of model
     *
     * @return array
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params Parameter to look forfor
     *
     * @return ActiveDataProvider
     */
    final public function search($params)
    {
        $query = Sourcemessage::find();

        $query->joinWith(
            [
                                'blocked',
                'message',
                'session',
                'blocked',
                'message',
                'session',

            ]
        );

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
                'pagination'=> [
                    'pageSize' => 5,
                ],
            ]
        );

        /**
        * Setup your sorting attributes
        */
        $dataProvider->setSort(
            [
                'defaultOrder' => ['id'=>SORT_ASC],

            ]
        );
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(
            [
                                'source_message.id'             => $this->id,
                'blocked.id'                    => $this->id,
                'message.id'                    => $this->id,
                'session.id'                    => $this->id,
                'blocked.id'                    => $this->id,
                'message.id'                    => $this->id,
                'session.id'                    => $this->id,

            ]
        );

        $query->andFilterWhere(
            [
                'like',
                'source_message.category',
                $this->category
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'source_message.message',
                $this->message
            ]
        );



        return $dataProvider;
    }
    
    /**
     * Get array from Source message
     *
     * @param string $table Table relation
     *
     * @return array
     */
    public static function getSourcemessageListSearch($table)
    {
        $sqlcode = 'SELECT DISTINCT '. self::ID . ', ' . self::MESSAGE . '
                    FROM source_message
                    WHERE '.self::ID.' in (SELECT DISTINCT '.self::ID . '
                                                          FROM '.$table .')
                    ORDER BY ' . self::MESSAGE;
        $droptions = Sourcemessage::findBySql($sqlcode)
            ->orderBy([self::MESSAGE => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map(
            $droptions,
            self::ID,
            self::MESSAGE
        );
    }

}
