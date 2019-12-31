<?php

// for Bulma bundles: Disable all javascript and css bootstrap loading


 $bundles = [
			'yii\web\JqueryAsset' => [
		       'js' => ['jquery.js']
			],
			'yii\bootstrap\BootstrapAsset' => [
				 'css' => [], //'css/bootstrap.min.css'
			],
			'yii\bootstrap\BootstrapPluginAsset' => [
				 'js' => [] //'js/bootstrap.js'
			]
 ];

return $bundles;
