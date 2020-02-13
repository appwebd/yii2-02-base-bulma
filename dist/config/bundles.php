<?php
/**
 * Bundle file
 * PHP Version 7.4.0
 *
 * @category Config
 * @package  Bundles
 * @author   Patricio Rojas Ortiz  <patricio-rojaso@outlook.com>
 * @license   BSD 3-clause Clear license
 * @version  GIT: <git_id>
 * @link     https://appwebd.github.io
 * @date     02/13/2020 10:10 PM
 */

 // for Bulma bundles: Disable all javascript and css bootstrap loading

if (YII_ENV) {
    $bundles = [
        'yii\web\JqueryAsset' => [
            'js' => ['jquery.min.js']
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'css' => [],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'js' => []
        ]
    ];
} else {
    $bundles = [
        'yii\web\JqueryAsset' => [
            'js' => ['jquery.min.js']
        ],
        'yii\bootstrap\BootstrapAsset' => [
            'css' => [],
        ],
        'yii\bootstrap\BootstrapPluginAsset' => [
            'js' => []
        ]
    ];
}

return $bundles;
