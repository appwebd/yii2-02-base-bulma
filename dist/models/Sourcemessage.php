<?php
/**
 * Class Sourcemessage
 * Source message
 * PHP Version 7.2.0
 *
 * @category  Models
 * @package   Sourcemessage
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
use app\models\Message;
use app\models\Session;


/**
 * Class Sourcemessage
 * Source message
 *
 * @category  Sourcemessage
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2019-12-05 11:10:02
 * 
 * @property string          category     Category
 * @property integer         id           id
 * @property string          message      Message
 * @property \yii\db\ActiveQuery $blocked
 * @property \yii\db\ActiveQuery $message
 * @property \yii\db\ActiveQuery $session
 * @property \yii\db\ActiveQuery $blocked
 * @property \yii\db\ActiveQuery $message
 * @property \yii\db\ActiveQuery $session
 */
class Sourcemessage extends ActiveRecord
{
    const PRIMARY_KEY = 'primaryKey';
    const CATEGORY = 'category';
    const ID = 'id';
    const MESSAGE = 'message';
    const TABLE = 'source_message';
    const TITLE = 'Source message';

    const ICON = 'fas fa-globe fa-2x';

    public $primaryKey;
    
    /**
     * All Attributes of table source_message
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [self::CATEGORY, STRING, 'max' => 255],
            [[self::ID], 'integer'],
            [[self::MESSAGE], 'string'],
            [[self::CATEGORY,
              self::MESSAGE], 'trim'],
            [[self::CATEGORY,
              self::MESSAGE], function ($attribute) {
                $this->$attribute = HtmlPurifier::process($this->$attribute);
              }
            ],
            [self::CATEGORY, STR_DEFAULT, VALUE => ' '],

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
            self::CATEGORY => Yii::t(
                'app',
                'Category'
            ),
            self::ID => Yii::t(
                'app',
                'id'
            ),
            self::MESSAGE => Yii::t(
                'app',
                'Message'
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
    public function getSession()
    {
        return $this->hasOne(
            Session::class,
            [self::ID => self::ID]
        );
    }



    /**
     * Check nro. records found in other tables related.
     *
     * @param int $id Primary Key of table source_message
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
                'session',
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
     * Get Description of table source_message for column given id
     *
     * @param integer $id Primary key of table source_message
     *
     * @return string description name
     */
    public static function getSourcemessageName($id)
    {
        $model = Sourcemessage::find()->where(
            [
                self::ID => $id
            ]
        )->one();
        $return = ' ';
        if (isset($model->message)) {
            $return = $model->message;
        }

        return $return;
    }

    /**
     * Get array from Source message
     *
     * @param bool $active Record is active? (if active=2 show all records)
     *
     * @return array
     */
    public static function getSourcemessageList($active = 1)
    {
        $where = [self::ACTIVE => $active];
        if ($active == 2) {
            $where = [];
        }

        $droptions = Sourcemessage::find()->where($where)
            ->orderBy([self::MESSAGE => SORT_ASC])
            ->asArray()->all();

        return ArrayHelper::map(
            $droptions,
            self::ID,
            self::MESSAGE
        );
    }

    /**
     * The name of the table associated with this ActiveRecord class.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'source_message';
    }
}
