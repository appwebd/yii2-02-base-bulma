<?php
/**
 * Class Session
 * Sessions of this web application
 * PHP Version 7.2.0
 *
 * @category  Models
 * @package   Session
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private Commercial License
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:02
 */

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\queries\Common;
use app\models\Blocked;
use app\models\Message;
use app\models\Sourcemessage;


/**
 * Class Session
 * Sessions of this web application
 *
 * @category  Session
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:05:02
 * 
 * @property string          data       data token
 * @property integer         expire     date time expire session
 * @property string          id         id
 * @property \yii\db\ActiveQuery $blocked
 * @property \yii\db\ActiveQuery $message
 * @property \yii\db\ActiveQuery $sourcemessage
 * @property \yii\db\ActiveQuery $blocked
 * @property \yii\db\ActiveQuery $message
 * @property \yii\db\ActiveQuery $sourcemessage
 */
class Session extends ActiveRecord
{
    const PRIMARY_KEY = 'primaryKey';
    const DATA = 'data';
    const EXPIRE = 'expire';
    const ID = 'id';
    const TABLE = 'session';
    const TITLE = 'Sessions of this web application';

    const ICON = 'fas fa-globe fa-2x';

    public $primaryKey;
    
    /**
     * All Attributes of table session
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ID], 'required'],
            [self::EXPIRE, FILTER, FILTER => 'intval', 'skipOnEmpty' => true],
            [self::ID, STRING, LENGTH => [1, 40]],
            [[self::EXPIRE], 'integer'],
            [[self::DATA], 'string'],
            [[self::DATA,
              self::ID], 'trim'],
            [[self::DATA,
              self::ID], function ($attribute) {
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
            self::DATA => Yii::t(
                'app',
                'data token'
            ),
            self::EXPIRE => Yii::t(
                'app',
                'date time expire session'
            ),
            self::ID => Yii::t(
                'app',
                'id'
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
     * Table reference has one
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBlocked()
    {
        return $this->hasOne(
            Blocked::class,
            [self::ID => self::ID]
        );
    }


    /**
     * Table reference has one
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(
            Message::class,
            [self::ID => self::ID]
        );
    }


    /**
     * Table reference has one
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource_message()
    {
        return $this->hasOne(
            Sourcemessage::class,
            [self::ID => self::ID]
        );
    }



    /**
     * Check nro. records found in other tables related.
     *
     * @param int $id Primary Key of table session
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    final public function fkCheck($id)
    {
        $common = new Common();
        return 
                $common->getNroRowsForeignkey(
                'blocked',
                self::ID,
                $id
            )
            +$common->getNroRowsForeignkey(
                'message',
                self::ID,
                $id
            )
            +$common->getNroRowsForeignkey(
                'source_message',
                self::ID,
                $id
            )
;
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
        return 'session';
    }
}
