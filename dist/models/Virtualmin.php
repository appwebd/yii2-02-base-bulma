<?php
/**
 * Class Virtualmin
 * virtualmin
 * PHP Version 7.2.0
 *
 * @category  Models
 * @package   Virtualmin
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private Commercial License
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 */

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\queries\Common;


/**
 * Class Virtualmin
 * virtualmin
 *
 * @category  Virtualmin
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 * 
 * @property boolean         active            Active
 * @property string          domain            domain
 * @property string          password          root password
 * @property string          server_url        Server URL
 * @property string          username          root username
 * @property integer         virtualmin_id     Virtualmin
 */
class Virtualmin extends ActiveRecord
{
    const PRIMARY_KEY = 'primaryKey';
    const ACTIVE = 'active';
    const DOMAIN = 'domain';
    const PASSWORD = 'password';
    const SERVER_URL = 'server_url';
    const USERNAME = 'username';
    const VIRTUALMIN_ID = 'virtualmin_id';
    const TABLE = 'virtualmin';
    const TITLE = 'virtualmin';

    const ICON = 'fas fa-globe fa-2x';

    public $primaryKey;
    
    /**
     * All Attributes of table virtualmin
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTIVE,
              self::DOMAIN,
              self::PASSWORD,
              self::SERVER_URL,
              self::USERNAME], 'required'],
            [self::DOMAIN, STRING, LENGTH => [1, 100]],
            [self::PASSWORD, STRING, LENGTH => [1, 100]],
            [self::SERVER_URL, STRING, LENGTH => [1, 254]],
            [self::USERNAME, STRING, LENGTH => [1, 100]],
            [[self::VIRTUALMIN_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::DOMAIN,
              self::PASSWORD,
              self::SERVER_URL,
              self::USERNAME], 'trim'],
            [[self::DOMAIN,
              self::PASSWORD,
              self::SERVER_URL,
              self::USERNAME], function ($attribute) {
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
            self::DOMAIN => Yii::t(
                'app',
                'domain'
            ),
            self::PASSWORD => Yii::t(
                'app',
                'root password'
            ),
            self::SERVER_URL => Yii::t(
                'app',
                'Server URL'
            ),
            self::USERNAME => Yii::t(
                'app',
                'root username'
            ),
            self::VIRTUALMIN_ID => Yii::t(
                'app',
                'Virtualmin'
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
     * @param int $virtualminId Primary Key of table virtualmin
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    final public function fkCheck($virtualminId)
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
        return 'virtualmin';
    }
}
