<?php
/**
 * Class Columna
 * PHP Version 7.0.0
 *
 * @category  Models
 * @package   Columna
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/01/18 10:07 PM
 */

namespace app\models\queries;

use app\components\UiComponent;
use app\controllers\BaseController;
use app\models\Colucolumnas;
use app\models\Csgcaptionsugerir;
use app\models\Tabltablas;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
/**
 * Class Bitacora
 *
 * @category Bitacora
 * @package  Models
 * @author   Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @license  Private license
 * @version  Release: <release_id>
 * @link     https://appwebd.github.io
 */
class Columna extends Colucolumnas
{
    /**
     * Get Description of table colu_columnas for column given colu_id_columna
     *
     * @param integer $coluIdcolumna Primary key table colu_columnas
     *
     * @return string description name
     */
    public static function getColucolumnasName($coluIdcolumna)
    {
        $model = Colucolumnas::find()->where(
            [self::COLU_ID_COLUMNA => $coluIdcolumna]
        )->one();
        $return = ' ';
        if (isset($model->tabl_nombre)) {
            $return = $model->tabl_nombre;
        }

        return $return;
    }

    /**
     * Get array from Colu_columnas
     *
     * @return array
     */
    public static function getColucolumnasList()
    {
        $droptions = Colucolumnas::find()->where([self::ACTIVE => 1])
            ->orderBy([self::COLU_NOMBRE => SORT_ASC])
            ->asArray()->all();
        return ArrayHelper::map(
            $droptions,
            self::COLU_ID_COLUMNA,
            self::COLU_NOMBRE
        );
    }

    /**
     * Get all columns with colu_bol_caption = 0
     *
     * @param int $proyIdProyecto Clave primaria en tabla proy_proyectos
     *
     * @return array
     */
    public static function getColumnasSinCaption($proyIdProyecto)
    {
        $sqlcode = "SELECT colu_nombre, count(*) as CONTEO
                    FROM `colu_columnas`
                    WHERE colu_bol_caption =0
                        and proy_id_proyecto = $proyIdProyecto
                    GROUP BY colu_nombre
                    HAVING CONTEO >=1
                    ORDER BY CONTEO DESC";

        return Columna::getColumnasBySQL($sqlcode);

    }

    /**
     * Get array style (colu_nombre, colu_nombre)
     *
     * @param string $sqlcode Query to execute
     *
     * @return array
     */
    public static function getColumnasBySQL($sqlcode)
    {
        try {
            $resulset = Yii::$app->db->createCommand($sqlcode)->queryAll();
            $list = [];
            foreach ($resulset as $row) {
                $list[] = [
                    self::COLU_NOMBRE => $row[self::COLU_NOMBRE],
                    self::COLU_NOMBRE => $row[self::COLU_NOMBRE]
                ];
            }

            return ArrayHelper::map(
                $list,
                self::COLU_NOMBRE,
                self::COLU_NOMBRE
            );
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $exception,
                'app\models\queries\Columna::getColumnasSinObjectos',
                MSG_ERROR
            );
        }
    }

    /**
     * Get an array of all columns with colu_bol_objecto=0
     *
     * @param int $proyIdproyecto Primary key of table proy_proyectos
     *
     * @return array
     */

    public static function getColumnasSinObjetos($proyIdproyecto)
    {
        $sqlcode = "SELECT colu_nombre, count(*) as CONTEO
                    FROM `colu_columnas`
                    WHERE colu_bol_objeto =0
                        and proy_id_proyecto = $proyIdproyecto
                    GROUP BY colu_nombre
                    HAVING CONTEO >=1
                    ORDER BY CONTEO DESC";

        return Columna::getColumnasBySQL($sqlcode);
    }

    /**
     * Get an array of all columns with of project
     *
     * @param int $proyIdproyecto Primary key of table proy_proyectos
     *
     * @return array
     */
    public static function getColumnasEnProyecto($proyIdproyecto)
    {
        $sqlcode = "SELECT DISTINCT colu_nombre, colu_nombre
                    FROM `colu_columnas`
                    WHERE proy_id_proyecto = $proyIdproyecto
                    ORDER BY colu_nombre ASC ";

        return Columna::getColumnasBySQL($sqlcode);
    }

    /**
     * Get array from Tabl_tablas
     *
     * @param int $proyIdProyecto primary key of table proy_proyectos
     *
     * @return array
     */
    public static function getColumnasListPerProyecto($proyIdProyecto)
    {
        $sqlcode = "SELECT DISTINCT colu_nombre, colu_nombre
                    FROM colu_columnas
                    WHERE proy_id_proyecto = $proyIdProyecto
                    ORDER BY colu_nombre ASC";
        $rows = Colucolumnas::findBySql($sqlcode)->asArray()->all();
        return ArrayHelper::map(
            $rows,
            self::COLU_NOMBRE,
            self::COLU_NOMBRE
        );
    }

    /**
     * Get information for dropdown list
     *
     * @param app/models/Tabltablas $model defined in app\models to get information
     * @param int $proyIdProyecto primary key of table proy_proyectos
     * @param integer $key integer column to get column code
     * @param string $value string column to get column description
     * @param string $selected
     *
     * @return string String
     */
    public static function relatedDropdownListSql(
        $model,
        $proyIdProyecto,
        $key,
        $value,
        $selected = ''
    ) {
        $sqlcode = 'SELECT distinct colu_nombre, colu_nombre
                    FROM colu_columnas
                    WHERE proy_id_proyecto = ' . $proyIdProyecto . '
                    ORDER BY colu_nombre';
        $rows = $model::findBySql($sqlcode)->all();

        $dropdown = '<select>';
        $dropdown .= HTML_OPTION . Yii::t('app', 'Please select one option') . HTML_OPTION_CLOSE;

        if (count($rows) > 0) {
            foreach ($rows as $row) {
                if ($selected == $row->$key) {
                    $dropdown .= '<option value="' . $row->$key . '" selected>' . $row->$value . HTML_OPTION_CLOSE;
                } else {
                    $dropdown .= '<option value="' . $row->$key . '">' . $row->$value . HTML_OPTION_CLOSE;
                }


            }
        } else {
            $dropdown .= HTML_OPTION . Yii::t('app', 'No results found') . HTML_OPTION_CLOSE;
        }

        return $dropdown . '</select>';
    }

    /**
     * Get a column in particular from table colu_columnas
     *
     * @param string $columnName
     * @param int $coluIdColumna primary key of table colu_columnas
     * @param string $defaultValue default value
     * @return string
     */
    final public static function getColumn($columnName, $coluIdColumna, $defaultValue)
    {
        $sqlcode = "SELECT $columnName
                    FROM colu_columnas
                    WHERE colu_id_columna = :colu_id_columna";
        try {
            $result = Yii::$app->db->createCommand($sqlcode,
                [
                    ':colu_id_columna' => $coluIdColumna
                ]
            )->queryScalar();
            if (!isset($result) || empty($result)) {
                $result = $defaultValue;
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register($exception, 'app\models\queries\Columna::getColumn', MSG_ERROR);
            $result = $defaultValue;
        }


        return $result;
    }

    /**
     * Update all caption of columns in proyect proy_id_proyecto with the
     * same md5 property
     *
     * @param int    $proyIdProyecto     Primary table key proy_proyectos
     * @param string $proyNomDatabase    Name of database
     * @param string $coluNombre         Name of column
     * @param string $coluCaption        Caption of column (column comment)
     * @param string $coluDesMd5         Md5 MySQL (concat of column_name+type)
     * @param string $coluDesTitle       Title for column object
     * @param string $coluDesPlaceholder Placeholder for column objet
     * @param string $coluDesHlp         Help for column object
     *
     * @return void
     */
    final public function updateAllCaption(
        $proyIdProyecto,
        $proyNomDatabase,
        $coluNombre,
        $coluCaption,
        $coluDesMd5,
        $coluDesTitle,
        $coluDesPlaceholder,
        $coluDesHlp
    )
    {

        $sqlcode = "SELECT  colu_id_columna,
                            tabl_nombre
                    FROM colu_columnas
                    WHERE colu_bol_caption=0
                    AND proy_id_proyecto= " . $proyIdProyecto .
            " AND colu_des_md5 like '" . $coluDesMd5 . "'";
        try {
            $resulset = Yii::$app->db->createCommand($sqlcode)->queryAll();
            foreach ($resulset as $row) {

                $coluIdColumna = $row[Colucolumnas::COLU_ID_COLUMNA];
                $tablNombreX = $row[Colucolumnas::TABL_NOMBRE];

                $this->updateCaption(
                    $coluIdColumna,
                    $coluCaption,
                    $coluDesTitle,
                    $coluDesPlaceholder,
                    $coluDesHlp
                );

                $this->alterColumnComment(
                    $proyNomDatabase,
                    $tablNombreX,
                    $coluNombre,
                    $coluCaption
                );

            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $exception,
                'app\models\queries\Columna::updateAllCaption',
                MSG_ERROR
            );
        }

    }

    final public function updateCaption(
        $coluIdColumna,
        $coluCaption,
        $coluDesTitle,
        $coluDesPlaceholder,
        $coluDesHlp
    ) {
        try {
            $colu_bol_caption = (strlen($coluCaption) > 0 ? 1 : 0);
            $sqlcode = "UPDATE colu_columnas
                        SET colu_caption ='$coluCaption',
                          colu_des_title ='$coluDesTitle',
                          colu_des_placeholder = '$coluDesPlaceholder',
                          colu_des_hlp = '$coluDesHlp',
                          colu_bol_caption = $colu_bol_caption
                        WHERE colu_id_columna = $coluIdColumna and colu_bol_caption=0";

            $status = Common::sqlCreateCommand($sqlcode);
        } catch (Exception $exception) {
            $status = false;
            $bitacora = new Bitacora();
            $bitacora->registerAndFlash(
                $exception,
                'app\models\queries\Columna::updateCaption sql:' . $sqlcode,
                MSG_ERROR
            );
        }
        return $status;
    }

    /**
     * ALTER TABLE `colu_columnas` CHANGE `colu_des_md5` `colu_des_md5`
     * CHAR(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT
     * 'Checksum columna';
     *
     * @return void
     */
    final public function alterColumnComment(
        $proy_nom_database,
        $tabl_nombre,
        $coluNombre,
        $coluCaption
    ) {

        try {
            $sqlcode = "SHOW COLUMNS
                        FROM $proy_nom_database.$tabl_nombre
                        WHERE field like '$coluNombre'";
            $definition = Yii::$app->db->createCommand($sqlcode)->queryAll();

            $structure = ' ' . $definition[0]['Type'];

            $apostrofe = ' ';
            if (preg_match('/char|varchar|text|tinytext/', $definition[0]['Type'])) {
                $structure .= ' CHARACTER SET utf8 COLLATE utf8_general_ci ';
                $apostrofe = " '";
            }

            if ($definition[0]['Null'] === 'NO') {
                $structure .= ' NOT NULL ';
            }

            if ($definition[0]['Extra'] === 'auto_increment') {
                $structure .= ' AUTO_INCREMENT ';
            }

            if ($definition[0]['Default'] !== '') {
                $structure .= ' DEFAULT '
                    . $apostrofe
                    . $definition[0]['Default']
                    . $apostrofe;
            }

            $sqlcode = "ALTER TABLE $proy_nom_database.$tabl_nombre
                        CHANGE COLUMN `$coluNombre` `$coluNombre`  $structure
                        COMMENT '$coluCaption'";
            Yii::$app->db->createCommand($sqlcode)->execute();
        } catch (\Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Columna::alterColumnComment',
                MSG_ERROR
            );
        }

    }

    /**
     * Update all Objects of columns in proyect proy_id_proyecto with the same
     * md5 property
     *
     * @param  int    $proyIdProyecto       Primary key of table proy_proyectos
     * @param  string $coluDesMd5           MD5 verification
     * @param  int    $csg_id_caption_sugerir Objeto csg_caption_sugerir
     * @param  bool   $colu_bol_objeto        Indica si objeto fue asignado por
     *                                        Usuario
     * @return void
     */
    final public function updateAllOject(
        $proyIdProyecto,
        $coluDesMd5,
        $csg_id_caption_sugerir,
        $colu_bol_objeto
    )
    {

        $sqlcode = "SELECT colu_id_columna
                    FROM colu_columnas
                    WHERE colu_bol_objeto =0
                    AND proy_id_proyecto= " . $proyIdProyecto .
            " AND colu_des_md5 like '" . $coluDesMd5 . "'";

        try {
            $resulset = Yii::$app->db->createCommand($sqlcode)->queryAll();
            foreach ($resulset as $row) {
                $coluIdColumna = $row[Colucolumnas::COLU_ID_COLUMNA];
                $columna = new Columna();
                $columna->updateObject(
                    $coluIdColumna,
                    $csg_id_caption_sugerir,
                    0,
                    $colu_bol_objeto,
                    false
                );
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Columna::updateAllOject',
                MSG_ERROR
            );
        }

    }

    final public function updateObject(
        $coluIdColumna,
        $csg_id_caption_sugerir,
        $tabl_id_tabla_origen,
        $colu_bol_objeto,
        $updateTablaOrigen
    ) {
        try {

            $sqlcode = "UPDATE colu_columnas
                        SET csg_id_caption_sugerir = $csg_id_caption_sugerir,";
            if ($updateTablaOrigen) {
                $sqlcode .= "tabl_id_tabla_origen = $tabl_id_tabla_origen,";
            }
            $sqlcode .= " colu_bol_objeto = $colu_bol_objeto
                        WHERE colu_id_columna = $coluIdColumna;";


            $status = Common::sqlCreateCommand($sqlcode);
        } catch (Exception $exception) {
            $status = false;
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Columna::updateObject sql: ' . $sqlcode,
                MSG_ERROR
            );
        }
        return $status;
    }

    /**
     * Set a new value of column colu_bol_object
     *
     * @param int $coluIdColumna primary key of table colu_columnas
     * @param string $columnName Column name to update value
     * @param bool $newValue new value to set in table.
     * @return bool
     */
    final public function setColumnUpdateBolean(
        $coluIdColumna,
        $columnName,
        $newValue
    ) {

        try {
            $sqlcode = "UPDATE colu_columnas
                    SET $columnName = $newValue
                    WHERE colu_id_columna = $coluIdColumna";
            $status = Common::sqlCreateCommand($sqlcode);
        } catch (Exception $exception) {
            $status = false;
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'app\models\queries\Columna::setColumnUpdateBolean sql: ' . $sqlcode,
                MSG_ERROR
            );
        }
        return $status;

    }

    /**
     * Grid a Mostrar en app/views/columnobjects/index.php y
     * app/views/columnobjects/columnas.php
     *
     * @param object $dataProviderColucolumnas Object data Provider
     * @param object $searchModelColucolumnas  Object Search Model
     * @param int    $proyIdProyecto           Primary table key proy_proyectos
     *
     * @return array
     */
    final public function getColColumnaGrid(
        $dataProviderColucolumnas,
        $searchModelColucolumnas,
        $proyIdProyecto
    ) {
        $uiComponent = new UiComponent();

        return
        [
            DATA_PROVIDER => $dataProviderColucolumnas,
            'filterModel' => $searchModelColucolumnas,
            'filterSelector' => 'select[name="per-page"]',
            'layout' => GRIDVIEW_LAYOUT,
            'tableOptions' => [STR_CLASS => GRIDVIEW_CSS],
            'rowOptions' => function ($model) {
                $primaryKey = BaseController::stringEncode($model->colu_id_columna);
                return [
                    'onclick' => 'js:selectItemo("' . $primaryKey . '","' .
                        $model->colu_nombre . '","' .
                        $model->proy_id_proyecto . '","' .
                        $model->csg_id_caption_sugerir . '","' .
                        $model->tabl_id_tabla_origen . '","' .
                        $model->colu_bol_objeto . '")',
                ];
            },
            'columns' => [
                [
                    ATTRIBUTE => Colucolumnas::COLU_NRO_COLUMNA,
                    HEADER => '#',
                    HEADER_OPTIONS => [STR_CLASS => WIDTH5PX],
                    FILTER_INPUT_OPTIONS => [STR_CLASS => WIDTH5PX],
                    STR_CLASS => GRID_DATACOLUMN,
                    FORMAT => 'raw'
                ],
                [
                    ATTRIBUTE => Colucolumnas::COLU_NOMBRE,
                    FORMAT => 'raw',
                    HEADER_OPTIONS => [STR_CLASS => 'is-3'],
                    FILTER => Columna::getColumnasEnProyecto($proyIdProyecto),
                    FILTER_INPUT_OPTIONS => [STR_CLASS => MAXWIDTH],
                    STR_CLASS => GRID_DATACOLUMN,
                ],
                [
                    ATTRIBUTE => Colucolumnas::CSG_ID_CAPTION_SUGERIR,
                    FORMAT => 'raw',
                    HEADER_OPTIONS => [STR_CLASS => WIDTH20],
                    FILTER_INPUT_OPTIONS => [STR_CLASS => MAXWIDTH],
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => function ($model) {
                        return Csgcaptionsugerir::getCsgnomobjeto(
                            $model->csg_id_caption_sugerir
                        );
                    }
                ],
                [
                    ATTRIBUTE => Colucolumnas::TABL_ID_TABLA_ORIGEN,
                    FORMAT => 'raw',
                    HEADER_OPTIONS => [STR_CLASS => WIDTH20],
                    FILTER_INPUT_OPTIONS => [STR_CLASS => MAXWIDTH],
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => function ($model) {
                        return Tabltablas::getTabltablasName(
                            $model->tabl_id_tabla_origen
                        );
                    }
                ],
                [
                    ATTRIBUTE => Colucolumnas::COLU_BOL_OBJETO,
                    FILTER => $uiComponent->yesOrNoArray(),
                    FORMAT => 'raw',
                    HEADER_OPTIONS => [STR_CLASS => WIDTH10PX],
                    STR_CLASS => GRID_DATACOLUMN,
                    VALUE => function ($model) {
                        $uiComponent = new UiComponent();
                        return $uiComponent->yesOrNoTag(
                            $model->colu_bol_objeto,
                            0,
                            'is-danger'
                        );
                    },
                ],
                [
                    STR_CLASS => GRID_CHECKBOXCOLUMN,
                    OPTIONS => [
                        STR_CLASS => '  width5px ',
                        TITLE => Yii::t(
                            'app',
                            'Set new Object status attribute'
                        )
                    ],
                ],
            ]
        ];
    }
}
