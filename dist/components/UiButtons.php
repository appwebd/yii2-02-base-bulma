<?php
/**
 * Class UiButtons based Bulma css framework
 * PHP version 7.2.0
 *
 * @category  UiButtons
 * @package   Components
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 * @note      The constants of Yii::t objects have been removed
 *            for example (for both category OR message)
 *            Yii::(STR_APP, OTHER_CONSTANT)
 *            They do not allow to obtain the values ​​for message
 */

namespace app\components;

use app\controllers\BaseController;
use app\models\queries\Bitacora;
use app\models\queries\Common;
use app\models\Status;
use Exception;
use Yii;
use yii\base\Component;
use yii\helpers\Html;

/**
 * Ui Buttons
 *
 * @category  Ui
 * @package   Components
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */
class UiButtons extends Component
{
    // Permite establecer el típo de ícono+texto para botones con el valor de
    // numérico asignado a BUTTON_ICON_WITH_TEXT por ejemplo:
    // 0--, 1:sólo texto, 2: sólo ícono,3: ícono+texto
    const BUTTON_ICON_WITH_TEXT = 3;
    const BUTTON_ICON_BACK_INDEX = '<i class="fas fa-list"></i>&nbsp;';
    const BUTTON_ICON_DELETE = '<i class="fas fa-trash"></i>&nbsp;';
    const BUTTON_ICON_NEW = '<i class="fas fa-plus"></i>&nbsp;';
    const BUTTON_ICON_REFRESH = '<i class="fas fa-sync-alt"></i>&nbsp;';
    const BUTTON_ICON_UPDATE = '<i class="fas fa-pencil-alt"></i>&nbsp;';
    const BUTTON_ICON_SAVE = '<i class="fas fa-save"></i>&nbsp;';

    const CSS_BTN_DEFAULT = 'button is-rounded ';
    const CSS_BTN_PRIMARY = 'button is-rounded is-primary ';
    const CSS_BTN_DANGER = 'button is-rounded is-danger ';
    const HTML_TOOLTIP = 'tooltip';
    const HTML_DATA_TOGGLE = 'data-toggle';
    const HTML_DATA_PLACEMENT = 'data-placement';
    const HTML_DATA_PLACEMENT_VALUE = 'top';
    const HTML_TITLE = TITLE;
    const HTML_SPACE = '&nbsp;';
    const STR_CONFIRM = 'confirm';
    const HTML_DATA_PJAX = 'data-pjax';
    const STR_SPAN_CLASS = '<span class="';
    const STR_SPAN_CLOSE = '"></span>';

    /**
     * Return the html span badget status
     *
     * @param int    $statusId number equivalent of badget status
     * @param string $status   Description or comment of status
     *
     * @return string
     */
    public static function badgetStatus($statusId, $status)
    {
        $badge = Status::getStatusBadge($statusId);

        return '<span class="badge badge-'.$badge.'">'.$status.'</span>';
    }

    /**
     * Show a toggle link button
     *
     * @param string $tableName   Name of table
     * @param string $columnName  name of column
     * @param bool   $columnValue value of columna
     * @param string $pkName      Primary key of tableName
     * @param int    $pkValue     primary Key of table $tableName
     * @param string $action      Action of this link
     *
     * @return string
     */
    public function toggleButton(
        $tableName,
        $columnName,
        $columnValue,
        $pkName,
        $pkValue,
        $action = 'base/toggle'
    ) {
        $redirect = Yii::$app->controller->id
            .'/'
            .Yii::$app->controller->action->id;

        $string = $tableName
            .'|'
            .$columnName
            .'|'
            .$columnValue
            .'|'
            .$pkName
            .'|'
            .$pkValue
            .'|'.$redirect;
        $string = BaseController::stringEncode($string);

        $url = [$action, ID => $string];
        $uiComponent = new UiComponent();

        return Html::a(
            self::STR_SPAN_CLASS
            .$uiComponent->yesOrNoGlyphicon($columnValue)
            .self::STR_SPAN_CLOSE,
            $url,
            [
                TITLE => Yii::t('app', 'Toggle value'),
                'data-value' => $columnValue,
                'data' => [
                    'method' => 'post',
                ],
                self::HTML_DATA_PJAX => 'w0',
            ]
        );
    }

    /**
     * Show icon action column in grid view Widget
     *
     * @return array
     */
    public function buttonsActionColumn()
    {
        return [
            ACTION_VIEW => function ($url, $model, $key) {
                return self::getUrlButtonAction(
                    ACTION_VIEW_ICON,
                    '/view',
                    $key,
                    Yii::t('app', 'Show more details')
                );
            },

            ACTION_UPDATE => function ($url, $model, $key) {
                return self::getUrlButtonAction(
                    ACTION_UPDATE_ICON,
                    '/update',
                    $key,
                    Yii::t('app', 'Update'),
                );
            },
            ACTION_DELETE => function ($url, $model, $key) {

                $primaryKey = BaseController::stringEncode($key);
                return Html::a(
                    self::STR_SPAN_CLASS.ACTION_DELETE_ICON
                    .self::STR_SPAN_CLOSE,
                    [
                        Yii::$app->controller->id.'/delete',
                        ID => $primaryKey,
                    ],
                    [
                        STR_CLASS => self::HTML_TOOLTIP,
                        'data-tooltip' => Yii::t('app', 'Delete record'),

                        'data-confirm' => Yii::t(
                            'app',
                            'Are you sure you want to delete this item?'
                        ),
                        'data-value' => $primaryKey,
                        'data' => [
                            'method' => 'post',
                        ],
                        STR_DATA_PJAX => '0',

                    ]
                );
            },
        ];
    }

    /**
     * Get Buttons action for action in gridview
     *
     * @param string $icon  Icon style for example glyphicon glyphicon-eye-open
     * @param string $url   Url
     * @param string $key   key encoded
     * @param string $title title of links
     *
     * @return string
     */
    public function getUrlButtonAction($icon, $url, $key, $title)
    {
        $url = Yii::$app->controller->id.$url;

        return Html::a(
            self::STR_SPAN_CLASS.$icon.self::STR_SPAN_CLOSE,
            [
                $url,
                ID => BaseController::stringEncode($key),
            ],
            [
                STR_CLASS => self::HTML_TOOLTIP,
                'data-tooltip' => $title,
                STR_DATA_PJAX => '0',
            ]
        );
    }

    /**
     * Show (echo) buttons in view admin
     *
     * @param string $sshowButtons to show Create, refresh, delete buttons.
     * @param bool   $buttonHeader true if these buttons are showing in
     *                             the header view
     *
     * @return string
     */
    public function buttonsAdmin($sshowButtons = '111', $buttonHeader = true)
    {
        $strButtons = '';
        try {
            $showButtons = str_split($sshowButtons, 1);

            $buttonCreate = '';
            if ($showButtons[0]
                && Common::getProfilePermission(ACTION_CREATE)
            ) {
                $caption = $this->buttonCaption(
                    self::BUTTON_ICON_NEW,
                    Yii::t('app', 'New')
                );
                $buttonCreate = $this->button(
                    $caption,
                    self::CSS_BTN_PRIMARY,
                    Yii::t('app', 'Create a new record'),
                    [ACTION_CREATE]
                );
            }

            $buttonDelete = '';
            if ($showButtons[2]
                && Common::getProfilePermission(ACTION_DELETE)
            ) {
                $buttonDelete = $this->buttonDelete(
                    [ACTION_REMOVE],
                    self::CSS_BTN_DEFAULT
                );
            }

            $buttonRefresh = '';
            if ($showButtons[1]) {
                $buttonRefresh = $this->buttonRefresh();
            }

            if ($buttonHeader) {
                $strButtons = $buttonCreate.self::HTML_SPACE.
                    $buttonRefresh.self::HTML_SPACE.
                    $buttonDelete.self::HTML_SPACE.self::HTML_SPACE;
            } else {
                $strButtons = $buttonDelete.self::HTML_SPACE.
                    $buttonRefresh.self::HTML_SPACE.
                    $buttonCreate.self::HTML_SPACE;
            }
        } catch (Exception $exception) {
            $bitacora = new Bitacora();
            $bitacora->register(
                $exception,
                'UiButtons::buttonsAdmin',
                MSG_ERROR
            );
        }

        return $strButtons;
    }

    /**
     * Set the caption for a button link (icon+text | icon| text only)
     *
     * @param string $icon icon from awesome font
     * @param string $text Message text
     *
     * @return string
     */
    final public function buttonCaption($icon, $text)
    {
        $caption = $text; // 1: Sólo texto
        if (self::BUTTON_ICON_WITH_TEXT==2) {
            $caption = $icon;
        }
        if (self::BUTTON_ICON_WITH_TEXT==3) {
            $caption = $icon . $text;
        }

        return $caption;
    }

    /**
     * Show a button link in the view
     *
     * @param string $caption       caption of button
     * @param string $css           style of button class
     * @param string $buttonToolTip string help tooltip
     * @param array  $aAction       array of string with action to do
     *
     * @return string
     */
    public function button($caption, $css, $buttonToolTip, $aAction = [])
    {
        return Html::a(
            $caption,
            $aAction,
            [
                STR_CLASS => $css,
                self::HTML_TITLE => $buttonToolTip,
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
            ]
        );
    }

    /**
     * Show a delete button link. Generally this button
     * is invoked from the index.php or view.php
     *
     * @param array  $action array action
     * @param string $css    string class style
     *
     * @return string
     */
    public function buttonDelete($action, $css)
    {
        $caption = $this->buttonCaption(
            self::BUTTON_ICON_DELETE,
            Yii::t('app', 'Delete')
        );

        return Html::a(
            $caption,
            $action,
            [
                STR_CLASS => $css,
                self::HTML_TITLE => Yii::t(
                    'app',
                    'Delete the selected records'
                ),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                'data' => [
                    self::STR_CONFIRM => Yii::t(
                        'app',
                        'Are you sure you want to delete this item?'
                    ),
                    METHOD => STR_POST,
                ],
            ]
        );
    }

    /**
     * Create object button refresh. Generally this button
     * is invoked from the _form.php view
     *
     * @param string $caption Caption to show on button.
     *
     * @return string
     */
    public function buttonRefresh($caption = 'New / Refresh')
    {
        $caption = Yii::t('app', $caption);
        $caption = $this->buttonCaption(
            self::BUTTON_ICON_REFRESH,
            $caption
        );

        return Html::a(
            $caption,
            [Yii::$app->controller->action->id],
            [
                STR_CLASS => self::CSS_BTN_DEFAULT,
                self::HTML_TITLE => Yii::t('app', 'Refresh view'),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
            ]
        );
    }

    /**
     * Show actions with buttons, similar a la funcionalidad de buttonsAdmin
     *
     * @param int    $primaryKey   Clave primaria
     * @param string $sshowButtons Personalizar la vista con mostrar botones
     *                             El orden de los botones a mostrar es
     *                             Create, delete, update,
     *
     * @return string
     */
    public function buttonsViewBottom($primaryKey, $sshowButtons = '111')
    {
        $primaryKey = BaseController::stringEncode($primaryKey);
        $showButtons = str_split($sshowButtons, 1);
        $buttonCreate = '';
        if ($showButtons[0] && Common::getProfilePermission(ACTION_CREATE)) {
            $caption = $this->buttonCaption(
                self::BUTTON_ICON_NEW,
                Yii::t('app', 'New')
            );
            $buttonCreate = $this->button(
                $caption,
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Create a new record'),
                [ACTION_CREATE]
            );
        }

        $buttonDelete = '';
        if ($showButtons[1] && Common::getProfilePermission(ACTION_DELETE)) {
            $buttonDelete = $this->buttonDelete(
                [ACTION_DELETE, ID => $primaryKey],
                self::CSS_BTN_DANGER
            );
        }

        $buttonUpdate = '';
        if ($showButtons[2] && Common::getProfilePermission(ACTION_UPDATE)) {
            $caption = $this->buttonCaption(
                self::BUTTON_ICON_UPDATE,
                Yii::t('app', 'Update')
            );
            $buttonUpdate = $this->button(
                $caption,
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Update the current record'),
                [ACTION_UPDATE, ID => $primaryKey]
            );
        }

        $caption = $this->buttonCaption(
            self::BUTTON_ICON_BACK_INDEX,
            Yii::t('app', 'Back to admin list')
        );

        return $buttonCreate.self::HTML_SPACE.
            $buttonUpdate.self::HTML_SPACE.
            $buttonDelete.self::HTML_SPACE.
            $this->button(
                $caption,
                self::CSS_BTN_PRIMARY,
                Yii::t('app', 'Back to administration view'),
                [ACTION_INDEX]
            );
    }

    /**
     * Display standard buttons in create view
     *
     * @param int  $tabIndex        number of index sequence in the view
     * @param bool $showBackToIndex Show button Back to index link
     *
     * @return string
     */
    public function buttonsCreate($tabIndex, $showBackToIndex = true)
    {
        $buttonSave = '';
        if (Common::getProfilePermission(ACTION_CREATE)) {
            $buttonSave = $this->buttonSave($tabIndex);
        }

        $caption = $this->buttonCaption(
            self::BUTTON_ICON_REFRESH,
            Yii::t('app', 'Refresh')
        );

        $strFooter = $buttonSave.self::HTML_SPACE.
            '<button type=\'reset\' class=\''.self::CSS_BTN_DEFAULT.'\' '.
            self::HTML_TITLE.'=\''
            .Yii::t('app', 'Refresh').'\' '.
            self::HTML_DATA_TOGGLE.'=\''.self::HTML_TOOLTIP.'\' '.
            self::HTML_DATA_PLACEMENT.'=\''.self::HTML_DATA_PLACEMENT_VALUE.
            '\'>'.$caption.'</button>'.
            self::HTML_SPACE;

        if ($showBackToIndex) {
            $caption = $this->buttonCaption(
                self::BUTTON_ICON_BACK_INDEX,
                Yii::t('app', 'Back to admin list')
            );
            $strFooter .= $this->button(
                $caption,
                self::CSS_BTN_DEFAULT,
                Yii::t('app', 'Back to administration view'),
                [ACTION_INDEX]
            );
        }

        return $strFooter;
    }

    /**
     * Create a link with a new button. Generally this
     * button is invoked from the _form.php view
     *
     * @param int $tabIndex tab of object
     *
     * @return string
     */
    public function buttonSave($tabIndex = 99)
    {
        $caption = $this->buttonCaption(
            self::BUTTON_ICON_SAVE,
            Yii::t('app', 'Save')
        );

        return Html::submitButton(
            $caption,
            [
                STR_CLASS => self::CSS_BTN_PRIMARY,
                self::HTML_TITLE => Yii::t(
                    'app',
                    'Save the information of this form'
                ),
                self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                'name' => 'save-button',
                ID => 'Save',
                VALUE => 'save-button',
                AUTOFOCUS => AUTOFOCUS,
                TABINDEX => $tabIndex,
            ]
        );
    }
}
