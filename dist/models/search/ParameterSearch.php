<?php
/**
 * Class ParameterSearch
 * Parameters
 * PHP Version 7.2.0
 *
 * @category  Parameter
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2020-02-07 21:45:04
 */

namespace app\models\search;

use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use app\models\Parameter;

/**
 * Class ParameterSearch represents the model behind the search
 * form about `app\models\Parameter`.
 * Parameters
 * PHP Version 7.2.0
 *
 * @category  Parameter
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2020-02-07 21:45:04
 */
class ParameterSearch extends Parameter
{

 

    /**
     * Rules of validation
     *
     * @return array
     */
    public function rules()
    {
        return [
            [[self::PARAMETER_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::DESCRIPTION,
              self::KEY,
              self::VALUE], 'safe'],

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
        $query = Parameter::find();


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
                'defaultOrder' => ['parameter_id'=>SORT_ASC],

            ]
        );
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(
            [
                                'parameter.active'             => $this->active,
                'parameter.parameter_id'       => $this->parameter_id,

            ]
        );

        $query->andFilterWhere(
            [
                'like',
                'parameter.description',
                $this->description
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'parameter.key',
                $this->key
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'parameter.value',
                $this->value
            ]
        );



        return $dataProvider;
    }
    
}
