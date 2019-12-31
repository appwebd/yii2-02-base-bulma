<?php
/**
 * Class Message
 * Translation of messages
 * PHP Version 7.2.0
 *
 * @category  Models
 * @package   Message
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
use app\models\Blocked;
use app\models\Session;
use app\models\Sourcemessage;


/**
 * Class Message
 * Translation of messages
 *
 * @category  Message
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 * 
 * @property integer         id              id
 * @property string          language        Language
 * @property string          translation     Translation
 * @property \yii\db\ActiveQuery $blocked
 * @property \yii\db\ActiveQuery $session
 * @property \yii\db\ActiveQuery $sourcemessage
 */
class Message extends ActiveRecord
{
    const PRIMARY_KEY = 'primaryKey';
    const ID = 'id';
    const LANGUAGE = 'language';
    const TRANSLATION = 'translation';
    const TABLE = 'message';
    const TITLE = 'Translation of messages';

    const ICON = 'fas fa-globe fa-2x';

    public $primaryKey;
    
    /**
     * All Attributes of table message
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ID,
              self::LANGUAGE], 'required'],
            [self::LANGUAGE, STRING, LENGTH => [1, 16]],
            [[self::ID], 'integer'],
            [[self::TRANSLATION], 'string'],
            [[self::LANGUAGE,
              self::TRANSLATION], 'trim'],
            [[self::LANGUAGE,
              self::TRANSLATION], function ($attribute) {
                $this->$attribute = HtmlPurifier::process($this->$attribute);
              }
            ],
            [self::LANGUAGE, STR_DEFAULT, VALUE => ' '],

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
            self::ID => Yii::t(
                'app',
                'id'
            ),
            self::LANGUAGE => Yii::t(
                'app',
                'Language'
            ),
            self::TRANSLATION => Yii::t(
                'app',
                'Translation'
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
    public function getSession()
    {
        return $this->hasOne(
            Session::class,
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
     * @param int $id Primary Key of table message
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
                'session',
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
     * Get Description of table message for column given id
     *
     * @param integer $id Primary key of table message
     *
     * @return string description name
     */
    public static function getMessageName($id)
    {
        $model = Message::find()->where(
            [
                self::ID => $id
            ]
        )->one();
        $return = ' ';
        if (isset($model->translation)) {
            $return = $model->translation;
        }

        return $return;
    }

    /**
     * Get array from Translation of messages
     *
     * @param bool $active Record is active? (if active=2 show all records)
     *
     * @return array
     */
    public static function getMessageList($active = 1)
    {
        $where = [self::ACTIVE => $active];
        if ($active == 2) {
            $where = [];
        }

        $droptions = Message::find()->where($where)
            ->orderBy([self::TRANSLATION => SORT_ASC])
            ->asArray()->all();

        return ArrayHelper::map(
            $droptions,
            self::ID,
            self::TRANSLATION
        );
    }

    /**
     * The name of the table associated with this ActiveRecord class.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'message';
    }
}
