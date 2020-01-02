<?php
/**
 * Class BulmaMenu
 * PHP version 7.2.0
 *
 * @category  Components
 * @package   BulmaMenu
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 11:01 AM
 */

namespace app\components;

use Yii;
use yii\base\Component;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class BulmaMenu
 * PHP version 7.2.0
 *
 * @category  Components
 * @package   BulmaMenu
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   Private license
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 11:01 AM
 */
class BulmaMenu extends Component
{
    /**
     * Open Bulma Menu navbar
     *
     * @param string $classNavbar Class properties of main Navbar
     *
     * @return void
     */
    public function navBarBegin($classNavbar = ' is-primary ')
    {

        echo '<nav class="navbar is-fixed-top  ' .$classNavbar.'"
                   role="navigation"
                   aria-label="main navigation"
            >
            <div class="navbar-brand">
                <a class="navbar-item" href="'.Url::home('https').'">
                    <img src="'.Url::base().'/images/logo.png"
                            alt="Logo"
                            class="logo">
                </a>
                    <a role="button"
                    class="navbar-burger burger"
                    aria-label="menu"
                    aria-expanded="false"
                    data-target="navbar-principal">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            <div id="navbar-principal" class="navbar-menu">

        ';
    }

    /**
     * Close Bulma Menu navbar
     *
     * @return void
     */
    public function navBarEnd()
    {
        echo '</div></nav>';
    }

    /**
     * Close Bulma tag Div
     *
     * @return void
     */
    public function navBarBlockClose()
    {
        echo '</div>';
    }

    /**
     * Show a item Url of Bulma Menu navbar
     *
     * @param string $caption Caption of Url
     * @param string $action  Action where do you want to go
     * @param string $class   CSS Class Style
     *
     * @return string
     */
    public function navBarItem($caption, $action, $class = ' ')
    {
        $caption = Yii::t('app', $caption);
        return Html::a(
            $caption,
            Url::to([$action]),
            [
                STR_CLASS => "navbar-item $class"
            ]
        );
    }
    /**
     * Echo a segment or block of Bulma menu
     *
     * @return void
     */
    final public function navBarBlockOpen($class = 'navbar-start has-text-left')
    {
        echo '<div class=" '. $class .' ">';
    }
    /**
     * Show a item Url of Bulma Menu navbar with a icon
     *
     * @param string $icon    Icon fontaresome
     * @param string $caption Caption of Url
     * @param string $action  Action where do you want to go
     * @param string $class   CSS Class style
     *
     * @return string
     */
    public function navBarItemIcon($icon, $caption, $action, $class = ' ')
    {
        $captionIcon = '<i class="fas '.$icon.'"></i> &nbsp; <span>'
                        . $caption .'</span>';

        echo Html::a(
            $captionIcon,
            Url::to([$action]),
            [STR_CLASS => "navbar-item $class"]
        );
    }

    /**
     * Open tag for dropdown menu Bulma Style
     *
     * @param string $caption Caption of Url
     *
     * @return string
     */
    public function navbarDropdownOpen($caption)
    {
        return '<div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">'.Yii::t('app', $caption).'</a>
                        <div class="navbar-dropdown">';
    }

    /**
     * Close Bulma Style dropdown menu
     *
     * @return string
     */
    public function navbarDropdownClose()
    {
        return '</div></div>';
    }

    /**
     * Show user information : login/logout
     *
     * @return string
     */
    public function navbarUser()
    {
        $label = Yii::$app->user->isGuest
            ?
            (
            [
                LABEL => Yii::t('app', 'Login'),
                'url' => ['/login/index'],
            ]
            )
            : (
                Html::beginForm(['/login/logout'], 'post')
                .Html::submitButton(
                    'Logout ('.Yii::$app->user->identity->username.')',
                    [STR_CLASS => ' button is-white logout navbar-item']
                ).Html::endForm()
            );

        echo  '<div class="navbar-end  has-text-right">
                    <div class="navbar-item">'
                    . $label .'&nbsp;
                    </div>
                </div>';
    }

    /**
     * Show signUp / Login buttons in like a navbar-item
     *
     * @return string
     */
    public function navbarSignUpLogin()
    {
        return '<div class="navbar-item">'.
            Html::a(
                Yii::t('app', 'Sign up'),
                Url::to(['signup/index']),
                [STR_CLASS => 'button is-primary']
            ).
            '&nbsp;'.
            Html::a(
                Yii::t('app', 'Login'),
                Url::to(['login/index']),
                [STR_CLASS => 'button']
            ).
            '&nbsp</div>';
    }
}
