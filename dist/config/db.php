<?php

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
