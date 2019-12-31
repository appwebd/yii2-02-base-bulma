<?php


namespace app\helpers;


use app\components\UiComponent;
use app\controllers\BaseController;
use Yii;
use yii\helpers\Html;

class TooggleButton
{
    /**
     * Show a toggle link button
     *
     * @param string $table_name Name of table
     * @param string $column_name name of column
     * @param bool $column_value value of columna
     * @param string $pk_name Primary key of tableName
     * @param int $pk_value primary Key of table $table_name
     * @param string $action Action of this link
     *
     * @return string
     */
    public static function toggleButton(
        $table_name,
        $column_name,
        $column_value,
        $pk_name,
        $pk_value,
        $action = 'base/toggle'
    ) {
        $redirect = Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;

        $string = $table_name . '|'
            . $column_name
            . '|'
            . $column_value
            . '|'
            . $pk_name
            . '|'
            . $pk_value
            . '|'
            . $redirect;
        $string = BaseController::stringEncode($string);

        $urloo = [$action, 'id' => $string];
        return Html::a(
            '<span class="' . UiComponent::yesOrNoGlyphicon($column_value) . '"></span>',
            $urloo,
            [
                'title' => Yii::t('app', 'Toggle value'),
                'data-value' => $column_value,

                'data' => [
                    METHOD => 'post',
                ],
                self::HTML_DATA_PJAX => 'w0',
            ]
        );
    }
}