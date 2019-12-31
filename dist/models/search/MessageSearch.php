<?php
/**
 * Class MessageSearch
 * Translation of messages
 * PHP Version 7.2.0
 *
 * @category  Message
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
use app\models\Message;

/**
 * Class MessageSearch represents the model behind the search
 * form about `app\models\Message`.
 * Translation of messages
 * PHP Version 7.2.0
 *
 * @category  Message
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 */
class MessageSearch extends Message
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
                'session.id',
                'source_message.id',

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
            [[self::LANGUAGE,
              self::TRANSLATION], 'safe'],

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
        $query = Message::find();

        $query->joinWith(
            [
                                'blocked',
                'session',
                'source_message',

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
                'defaultOrder' => ['language'=>SORT_ASC],

            ]
        );
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(
            [
                                'message.id'                => $this->id,
                'blocked.id'                => $this->id,
                'session.id'                => $this->id,
                'source_message.id'         => $this->id,

            ]
        );

        $query->andFilterWhere(
            [
                'like',
                'message.language',
                $this->language
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'message.translation',
                $this->translation
            ]
        );



        return $dataProvider;
    }
    
    /**
     * Get array from Translation of messages
     *
     * @param string $table Table relation
     *
     * @return array
     */
    public static function getMessageListSearch($table)
    {
        $sqlcode = 'SELECT DISTINCT '. self::ID . ', ' . self::TRANSLATION . '
                    FROM message
                    WHERE '.self::ID.' in (SELECT DISTINCT '.self::ID . '
                                                          FROM '.$table .')
                    ORDER BY ' . self::TRANSLATION;
        $droptions = Message::findBySql($sqlcode)
            ->orderBy([self::TRANSLATION => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map(
            $droptions,
            self::ID,
            self::TRANSLATION
        );
    }

}
