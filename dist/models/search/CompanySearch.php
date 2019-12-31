<?php
/**
 * Class CompanySearch
 * Company
 * PHP Version 7.2.0
 *
 * @category  Company
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
use app\models\Company;

/**
 * Class CompanySearch represents the model behind the search
 * form about `app\models\Company`.
 * Company
 * PHP Version 7.2.0
 *
 * @category  Company
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:02
 */
class CompanySearch extends Company
{

 

    /**
     * Rules of validation
     *
     * @return array
     */
    public function rules()
    {
        return [
            [[self::COMPANY_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_EMAIL,
              self::CONTACT_PERSON,
              self::CONTACT_PHONE_1,
              self::CONTACT_PHONE_2,
              self::CONTACT_PHONE_3,
              self::WEBPAGE], 'safe'],

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
        $query = Company::find();


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
                'defaultOrder' => ['company_id'=>SORT_ASC],

            ]
        );
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(
            [
                                'company.active'                => $this->active,
                'company.company_id'            => $this->company_id,

            ]
        );

        $query->andFilterWhere(
            [
                'like',
                'company.address',
                $this->address
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.company_name',
                $this->company_name
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.contact_email',
                $this->contact_email
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.contact_person',
                $this->contact_person
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.contact_phone_1',
                $this->contact_phone_1
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.contact_phone_2',
                $this->contact_phone_2
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.contact_phone_3',
                $this->contact_phone_3
            ]
        )
             ->andFilterWhere(
            [
                'like',
                'company.webpage',
                $this->webpage
            ]
        );



        return $dataProvider;
    }
    
}
