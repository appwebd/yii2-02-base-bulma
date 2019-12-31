<?php
/**
 * @app/view/partials/menuAdmin.php
 *
 * @package     @app/view/partials/menuAdmin.php
 * @authors     Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright   (C) Copyright - Web Application development
 * @license     Private license
 * @link        https://appwebd.github.io
 * @date        6/18/18 10:34 AM
 * @version     1.0
 */

use app\components\UiComponent;
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\BulmaMenu;

$menu = new BulmaMenu();
$menu->navbarBegin();
	echo '<div class="navbar-menu"><div class="navbar-start ">';

		echo $menu->navBarItemIcon('fa-home', 'home', '/site/index');
		echo $menu->navBarItem('Logs', '/logs/index');
		echo $menu->navBarItem('Users', '/user/index');
		echo $menu->navBarItem('Profile', '/profile/index');
		echo $menu->navBarItem('Permission', '/permission/index');

        
		echo $menu->navbarDropdownOpen('Config');
		echo $menu->navBarItem('Action', '/logs/actions');
		echo $menu->navBarItem('Blocked', '/logs/blocked');
		echo $menu->navBarItem('Controllers', '/logs/controllers');
		echo $menu->navBarItem('Status', '/logs/status');
		echo $menu->navbarDropdownClose();

	echo "</div>"; //  <div class="navbar-start

	echo $menu->navbarUser();


$menu->navBarEnd();
