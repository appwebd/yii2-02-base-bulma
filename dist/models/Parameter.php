<?php
/**
 * Class Parameter
 * Parameters
 * PHP Version 7.2.0
 *
 * @category  Models
 * @package   Parameter
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2018-2019 Patricio Rojas Ortiz
 * @license   Private Commercial License
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      2020-02-07 21:45:03
 */

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;
use yii\db\ActiveRecord;
use yii\db\Expression;
use app\models\queries\Common;
use app\models\queries\Bitacora;

/**
 * Class Parameter
 * Parameters
 *
 * @category  Parameter
 * @package   Model
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Patricio Rojas Ortiz
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      2020-02-07 21:45:03
 * 
 * @property  boolean         active           Active
 * @property  string          description      Description
 * @property  string          key              Key
 * @property  integer         parameter_id     Parameter
 * @property  string          value            Value
 */
class Parameter extends ActiveRecord
{
    const PRIMARY_KEY = 'primaryKey';
    const ACTIVE = 'active';
    const DESCRIPTION = 'description';
    const KEY = 'key';
    const PARAMETER_ID = 'parameter_id';
    const VALUE = 'value';
    const TABLE = 'parameter';
    const TITLE = 'Parameters';

    const ICON = 'fas fa-globe fa-2x';

    public $primaryKey;
    
    /**
     * All Attributes of table parameter
     *
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [[self::ACTIVE,
              self::DESCRIPTION,
              self::KEY,
              self::VALUE], 'required'],
            [self::DESCRIPTION, STRING, LENGTH => [1, 80]],
            [self::KEY, STRING, LENGTH => [1, 60]],
            [self::VALUE, STRING, LENGTH => [1, 40]],
            [[self::PARAMETER_ID], 'integer'],
            [[self::ACTIVE], 'boolean'],
            [[self::DESCRIPTION,
              self::KEY,
              self::VALUE], 'trim'],
            [[self::DESCRIPTION,
              self::KEY,
              self::VALUE], function ($attribute) {
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
            self::DESCRIPTION => Yii::t(
                'app',
                'Description'
            ),
            self::KEY => Yii::t(
                'app',
                'Key'
            ),
            self::PARAMETER_ID => Yii::t(
                'app',
                'Parameter'
            ),
            self::VALUE => Yii::t(
                'app',
                'Value'
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
     * @param int $parameterId Primary Key of table parameter
     *
     * @return int numbers of rows in other tables with integrity referential found.
     */
    final public function fkCheck($parameterId)
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
     * Save a new value of parameter on this platform
     *
     * @param string $key         A token to search on parameter table
     * @param string $value       Value of key
     * @param string $description Descriptive value of column
     *
     * @return bool
     */
    public function saveParameter($key, $value, $description)
    {
        try {

            $model = $this->getParameter($key);
            if ($model === null) {
                $model = new Parameter();
            }
            $model->description = $description;
            $model->key = $key;
            $model->value = $value;
            $model->active = 1;

            return $model->save();

        } catch (\yii\db\Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app/models/Parameter::saveParameter',
                MSG_ERROR
            );
        }

        return false;

    }

    /**
     * @param $key
     *
     * @return Parameter|null
     */
    public function getParameter($key)
    {
        try {

            if (($model = Parameter::findOne(['key' => $key])) !== null) {
                return $model;
            }
        } catch (\yii\db\Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app/model/Paramater::getParameter',
                MSG_ERROR
            );
        }
        return null;
    }

    /**
     * The name of the table associated with this ActiveRecord class.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'parameter';
    }
}
