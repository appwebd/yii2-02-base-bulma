<?php
/**
 * Class Company
 * Company
 * PHP Version 7.2.0
 *
 * @category  Models
 * @package   Company
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private Commercial License
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:01
 */

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\queries\Common;


/**
 * Class Company
 * Company
 *
 * @category  Company
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:01
 * 
 * @property boolean         active              Active
 * @property string          address             Address
 * @property integer         company_id          Company
 * @property string          company_name        Name
 * @property string          contact_email       Contact email
 * @property string          contact_person      Contact person
 * @property string          contact_phone_1     Contact phone
 * @property string          contact_phone_2     Phone additional
 * @property string          contact_phone_3     Phone additional
 * @property string          webpage             URL Webpage
 */
class Company extends ActiveRecord
{
    const PRIMARY_KEY = 'primaryKey';
    const ACTIVE = 'active';
    const ADDRESS = 'address';
    const COMPANY_ID = 'company_id';
    const COMPANY_NAME = 'company_name';
    const CONTACT_EMAIL = 'contact_email';
    const CONTACT_PERSON = 'contact_person';
    const CONTACT_PHONE_1 = 'contact_phone_1';
    const CONTACT_PHONE_2 = 'contact_phone_2';
    const CONTACT_PHONE_3 = 'contact_phone_3';
    const WEBPAGE = 'webpage';
    const TABLE = 'company';
    const TITLE = 'Company';

    const ICON = 'fas fa-globe fa-2x';

    public $primaryKey;
    
    /**
     * All Attributes of table company
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTIVE,
              self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_PERSON], 'required'],
            [self::ADDRESS, STRING, LENGTH => [1, 100]],
            [self::COMPANY_NAME, STRING, LENGTH => [1, 60]],
            [self::CONTACT_EMAIL, STRING, 'max' => 254],
            [self::CONTACT_PERSON, STRING, LENGTH => [1, 80]],
            [self::CONTACT_PHONE_1, STRING, 'max' => 20],
            [self::CONTACT_PHONE_2, STRING, 'max' => 20],
            [self::CONTACT_PHONE_3, STRING, 'max' => 20],
            [self::WEBPAGE, STRING, 'max' => 254],
            [[self::COMPANY_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_EMAIL,
              self::CONTACT_PERSON,
              self::CONTACT_PHONE_1,
              self::CONTACT_PHONE_2,
              self::CONTACT_PHONE_3,
              self::WEBPAGE], 'trim'],
            [[self::ADDRESS,
              self::COMPANY_NAME,
              self::CONTACT_EMAIL,
              self::CONTACT_PERSON,
              self::CONTACT_PHONE_1,
              self::CONTACT_PHONE_2,
              self::CONTACT_PHONE_3,
              self::WEBPAGE], function ($attribute) {
                $this->$attribute = HtmlPurifier::process($this->$attribute);
              }
            ],
        ];
    }

    /**
     * Attribute Labels
     *
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            self::ACTIVE => Yii::t(
                'app',
                'Active'
            ),
            self::ADDRESS => Yii::t(
                'app',
                'Address'
            ),
            self::COMPANY_ID => Yii::t(
                'app',
                'Company'
            ),
            self::COMPANY_NAME => Yii::t(
                'app',
                'Name'
            ),
            self::CONTACT_EMAIL => Yii::t(
                'app',
                'Contact email'
            ),
            self::CONTACT_PERSON => Yii::t(
                'app',
                'Contact person'
            ),
            self::CONTACT_PHONE_1 => Yii::t(
                'app',
                'Contact phone'
            ),
            self::CONTACT_PHONE_2 => Yii::t(
                'app',
                'Phone additional'
            ),
            self::CONTACT_PHONE_3 => Yii::t(
                'app',
                'Phone additional'
            ),
            self::WEBPAGE => Yii::t(
                'app',
                'URL Webpage'
            ),

        ];
    }

    /**
     * Behaviors of table
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [
                        'created_at',
                        'updated_at'
                    ],
                    ActiveRecord::EVENT_BEFORE_UPDATE => [
                        'updated_at'
                    ],
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }



    /**
     * Check nro. records found in other tables related.
     *
     * @param int $companyId Primary Key of table company
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    final public function fkCheck($companyId)
    {
        $common = new Common();
        return  0;
    }

    /**
     * Get primary key id
     *
     * @return integer primary key
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }



    /**
     * The name of the table associated with this ActiveRecord class.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'company';
    }
}
