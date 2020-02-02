<?php
/**
 * Class UiComponent
 * PHP Version 7.3
 *
 * @category  Components
 * @package   Ui
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Patricio Rojas Ortiz
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      06/28/18 02:33 PM
 */

namespace app\components;

use app\models\queries\Bitacora;
use app\models\Status;
use Exception;
use Yii;
use yii\base\Component;
use yii\helpers\Html;

/**
 * Ui Components
 * PHP version 7.3
 *
 * @category  Ui
 * @package   Components
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <package_id>
 * @link      https://appwebd.github.io
 * @date      6/18/18 10:34 AM
 */
class UiComponent extends Component
{
    const HTML_TOOLTIP = 'tooltip';
    const HTML_DATA_TOGGLE = 'data-toggle';
    const HTML_DATA_PLACEMENT = 'data-placement';
    const HTML_DATA_PLACEMENT_VALUE = 'top';
    const HTML_SPACE = '&nbsp;';
    const HTML_4XSPACES = '&nbsp;&nbsp;&nbsp;&nbsp;';
    const STR_PER_PAGE = 'per-page';
    const STR_PAGESIZE = 'pageSize';
    const HTML_CARD_FOOTER_OPEN
        = <<< HTML

                    </div></div>
                    <footer class="card-footer has-text-left footer-before">
HTML;
    const HTML_CARD_FOOTER_CLOSE = '</footer></div>';


    /**
     * Show page header and navigation buttons of the index page.
     *
     * @param string $icon         icon header
     * @param string $color        color header
     * @param string $pageTitle    Title of view
     * @param string $subHeader    Subtitle of view
     * @param string $showButtons  111 means (in correlative order)
     *                             1:Show button New
     *                             1: Show button Refresh
     *                             1: Show button Delete
     * @param bool   $showPageSize Show pageSize in header of view
     * @param string $colorText    CSS color of card-header-title
     *
     * @return void
     */
    public function cardHeader(
        $icon = 'user',
        $color = 'card-header-background-gray',
        $pageTitle = 'User',
        $subHeader = 'Users',
        $showButtons = '111',
        $showPageSize = false,
        $colorText = ' has-text-primary '
    ) {
        echo '<div class="card ">
                 <header class="card-header ', $color, ' ">
                    <div class="card-header-title ', $colorText, '">

                        <span class="icon ">',
                            self::HTML_SPACE,
                            self::HTML_SPACE,
                            self::HTML_SPACE,
                            '<i class=" ', $icon, '  "></i>
                        </span>',
                        self::HTML_4XSPACES,
                        '<section class="hero">
                            <div class="hero-body">
                                <p class="is-size-5 ">',
                                    $pageTitle,
                                '</p>
                                <p class="is-size-7">',
                                    $subHeader,
                                '</p>
                            </div>
                        </section>

                    </div>
                    <div class="block is-left card-header-icon" aria-label="more options">

                    ';

        if ($showButtons) {
            try {
                $uiButtons = new UiButtons();
                echo $uiButtons->buttonsAdmin($showButtons);
            } catch (Exception $exception) {
                $bitacora = new Bitacora();
                $bitacora->register(
                    $exception,
                    'app\components\UiComponent\buttonsAdmin',
                    MSG_ERROR
                );
            }
        }

        if ($showPageSize) {
            $pageSize = self::pageSize();
            echo $this->pageSizeDropDownList($pageSize);
        }

        echo '&nbsp;&nbsp;&nbsp;&nbsp; </div></header>
              <div class="card-content">
                 <div class="content">
                    ';
    }

    /**
     * Get pageSize information in a view (save this information per view)
     *
     * @return array|mixed
     */
    public static function pageSize()
    {

        $session = Yii::$app->session;
        $pageSize = Yii::$app->request->get(self::STR_PER_PAGE);
        $token = Yii::$app->controller->id.'.'.self::STR_PAGESIZE;

        if (! isset($pageSize)) {
            $pageSize = Yii::$app->request->post(self::STR_PER_PAGE);
            if (! isset($pageSize)) {
                $pageSize = $session[$token];
                if (!isset($pageSize)) {
                    $pageSize = Yii::$app->params['pageSizeDefault'];
                }
            }
        }


        $session->set($token, $pageSize);

        return $pageSize;
    }

    /**
     * Panel with header title
     * @param string $title Title of panel.
     *
     * @return string
     */
    public function panelHeader($title)
    {
            return '<nav class="panel">
                    <p class="panel-heading">'. $title .'
                    </p>';
    }

    /**
     * Close the panel
     * @return string
     */
    public function panelClose()
    {
        return '</div>
                    </nav>';
    }
    /**
     * Show pageSize Dropdown in view
     *
     * @param int $pageSize numbers of rows to show per page in gridView.
     *
     * @return string
     */
    public static function pageSizeDropDownList($pageSize)
    {
        return '<div class="select " >'.
            Html::dropDownList(
                self::STR_PER_PAGE,
                $pageSize,
                array(
                    5 => 5,
                    10 => 10,
                    15 => 15,
                    25 => 25,
                    40 => 40,
                    65 => 65,
                    105 => 105,
                    170 => 170,
                    275 => 275,
                    445 => 445,
                    720 => 720,
                ),
                array(
                    'id' => self::STR_PER_PAGE,
                    'onChange' => 'js:window.location.reload(true)',
                    //'onChange' => 'js:window.location.submit()',
                    'class' => ' ',
                    self::HTML_DATA_TOGGLE => self::HTML_TOOLTIP,
                    self::HTML_DATA_PLACEMENT => self::HTML_DATA_PLACEMENT_VALUE,
                )
            ).'</div>';
    }

    /**
     * Close a card style function
     *
     * @param string $footer buttons links or message in footer of card.
     *
     * @return void
     */
    public function cardFooter($footer = '')
    {
        echo '        </div>
              </div>';
        if ($footer !== '') {
            echo '<footer class="card-footer has-text-left footer-before">',
            $footer,
            '</footer> ';
        }
        echo '</div>';
    }

    /**
     * Show grlyphicon of yes/not in the view
     *
     * @param bool $boolean boolean 1 or 0 values.
     *
     * @return string
     */
    public function yesOrNoGlyphicon($boolean)
    {
        return $boolean
            ? 'glyphicon glyphicon-ok-circle'
            :
            'glyphicon glyphicon-remove-circle';
    }

    /**
     * Return the html span badget status
     *
     * @param int    $statusId number equivalent of badget status
     * @param string $status   Description or comment of status
     *
     * @return string
     */
    public function badgetStatus($statusId, $status)
    {
        $badge = Status::getStatusBadge($statusId);

        return '<span class="badge badge-'.$badge.'">'.$status.'</span>';
    }

    /**
     * Return an array with the yes/not values
     *
     * @return array
     */
    public function yesOrNoArray()
    {
        return [1 => Yii::t('app', 'Yes'), 0 => 'No'];
    }

    /**
     * Show Yes or No given a boolean value
     *
     * @param bool $boolean boolean 1 or 0 values.
     *
     * @return string yes or no
     */
    public function yesOrNoBadge($boolean)
    {
        $status = ($boolean === 1) ? Yii::t('app', 'Yes') : 'No';
        $badge = ($boolean === 1) ? SUCCESS : WARNING;

        return '<span class="badge badge-'.$badge.'">'.$status.'</span>';
    }

    /**
     * Display a tag attribute item
     *
     * @param bool   $boolean Value boolean yes/no to show with tag
     * @param string $value   Value to span tags
     * @param string $tag     Tag Class identifier
     *
     * @return string
     */
    public function yesOrNoTag($boolean, $value, $tag)
    {
        $return = $this->yesOrNo($boolean);
        if ($boolean === $value) {
            $return = "<span class='tag $tag'>$return</span>";
        }

        return $return;
    }

    /**
     * Show Yes or No given a boolean value
     *
     * @param bool $boolean boolean 1 or 0 values.
     *
     * @return string yes or no
     */
    public function yesOrNo($boolean)
    {
        return ($boolean === 1) ? Yii::t('app', 'Yes') : 'No';
    }
}
