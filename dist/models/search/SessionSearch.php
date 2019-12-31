<?php
/**
 * Class SessionSearch
 * Sessions of this web application
 * PHP Version 7.2.0
 *
 * @category  Session
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:02
 */

namespace app\models\search;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Session;

/**
 * Class SessionSearch represents the model behind the search
 * form about `app\models\Session`.
 * Sessions of this web application
 * PHP Version 7.2.0
 *
 * @category  Session
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:02
 */
class SessionSearch extends Session
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
                'source_message.id',
                'blocked.id',
                'message.id',
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
            [[self::EXPIRE], 'integer'],
            [[self::DATA,
              self::ID], 'safe'],

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
        $query = Session::find();

        $query->joinWith(
            [
                                'blocked',
                'message',
                'source_message',
                'blocked',
                'message',
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
                'defaultOrder' => ['id'=>SORT_ASC],

            ]
        );
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(
            [
                                'session.expire'       => $this->expire,
                'blocked.id'           => $this->id,
                'message.id'           => $this->id,
                'source_message.id'    => $this->id,
                'blocked.id'           => $this->id,
                'message.id'           => $this->id,
                'source_message.id'    => $this->id,

            ]
        );

        $query->andFilterWhere(
            [
                'like',
                'session.data',
                $this->data
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'session.id',
                $this->id
            ]
        );



        return $dataProvider;
    }
    
}
