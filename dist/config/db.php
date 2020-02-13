<?php
/**
 * Allows you to configure the database connection in the application
 * PHP Version 7.0.0
 *
 * @category  Config
 * @package   Db
 * @author    Patricio Rojas Ortiz <patricio-rojaso@outlook.com>
 * @copyright 2019  Copyright - Web Application development
 * @license   BSD 3-clause Clear license
 * @version   GIT: <git_id>
 * @link      https://appwebd.github.io
 * @date      11/1/18 10:07 PM
 */

if (YII_DEBUG) {
    $enableSchemaCache = false;
    $schemaCacheDuration = 3600;
} else {
    $enableSchemaCache = true;
    $schemaCacheDuration = 3600;
}

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=db_base',
    'username' => 'root',
    'password' => 'password',
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    'enableSchemaCache' => $enableSchemaCache,
    'schemaCacheDuration' => $schemaCacheDuration,
    'schemaCache' => 'cache',
];
