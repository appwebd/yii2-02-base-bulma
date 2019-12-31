<?php
/**
 * Class VirtualminSearch
 * virtualmin
 * PHP Version 7.2.0
 *
 * @category  Virtualmin
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
use app\models\Virtualmin;

/**
 * Class VirtualminSearch represents the model behind the search
 * form about `app\models\Virtualmin`.
 * virtualmin
 * PHP Version 7.2.0
 *
 * @category  Virtualmin
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 */
class VirtualminSearch extends Virtualmin
{

 

    /**
     * Rules of validation
     *
     * @return array
     */
    public function rules()
    {
        return [
            [[self::VIRTUALMIN_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::DOMAIN,
              self::PASSWORD,
              self::SERVER_URL,
              self::USERNAME], 'safe'],

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
        $query = Virtualmin::find();


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
                'defaultOrder' => ['virtualmin_id'=>SORT_ASC],

            ]
        );
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(
            [
                                'virtualmin.active'              => $this->active,
                'virtualmin.virtualmin_id'       => $this->virtualmin_id,

            ]
        );

        $query->andFilterWhere(
            [
                'like',
                'virtualmin.domain',
                $this->domain
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'virtualmin.password',
                $this->password
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'virtualmin.server_url',
                $this->server_url
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'virtualmin.username',
                $this->username
            ]
        );



        return $dataProvider;
    }
    
}
