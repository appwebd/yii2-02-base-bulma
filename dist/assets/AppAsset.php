<?php
/**
 * Class AppAsset
 * PHP Version 7.4
 *
 * @category  Assets
 * @package   Assets
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   https://appwebd.github.io/License.html GNU
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/17/18 5:05 PM
 */
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class AppAsset
 * PHP Version 7.4
 *
 * @category  Assets
 * @package   Assets
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019 (C) Copyright - Web Application development
 * @license   https://appwebd.github.ui/License.html GNU
 * @version   Release: <release_id>
 * @link      https://appwebd.github.io
 * @date      11/17/18 5:05 PM
 */
class AppAsset extends AssetBundle
{
    public $basePath  = '@webroot';
    public $jsOptions = ['position' => View::POS_END];

    public $css = [
        ['/css/style.min.css',],
    ];

    public $js = [
        ['/js/javascript-distr.min.js', 'async' => true],
    ];

    // Enable yii\web\YiiAsset to load jquery
    // used in alerts and extensions

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
