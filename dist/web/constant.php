<?php

// comment out the following three lines when deployed to production

defined('YII_DEBUG') || define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') || define('YII_TRACE_LEVEL', 3);
// 'dev' for development environment and 'prod' is for production environment
defined('YII_ENV') || define('YII_ENV', 'dev');

defined('DISABLE_CACHE') || define('DISABLE_CACHE', true);


// BEGIN GLOBAL CONSTANTS of this web application --------------------------

defined('ACTION_CREATE') || define('ACTION_CREATE', 'create');
defined('ACTION_DELETE') || define('ACTION_DELETE', 'delete');
defined('ACTION_INDEX') || define('ACTION_INDEX', 'index');
defined('ACTION_LOGOUT') || define('ACTION_LOGOUT', 'logout');
defined('ACTION_REMOVE') || define('ACTION_REMOVE', 'remove');

defined('ACTION_VIEW_ICON') || define('ACTION_VIEW_ICON', 'glyphicon glyphicon-eye-open');
defined('ACTION_UPDATE_ICON') || define('ACTION_UPDATE_ICON', 'glyphicon glyphicon-pencil');
defined('ACTION_DELETE_ICON') || define('ACTION_DELETE_ICON', 'glyphicon glyphicon-trash');

defined('ACTIONS') || define('ACTIONS', 'actions');
defined('ACTION_UPDATE') || define('ACTION_UPDATE', 'update');
defined('ACTION_VIEW') || define('ACTION_VIEW', 'view');
defined('ACTIVE') || define('ACTIVE', 'active');
defined('ALLOW') || define('ALLOW', 'allow');
defined('A_HREF') || define('A_HREF', '<a href=\'');
defined('ATTRIBUTE') || define('ATTRIBUTE', 'attribute');
defined('ATTRIBUTES') || define('ATTRIBUTES', 'attributes');
defined('AUTOCOMPLETE') || define('AUTOCOMPLETE', 'autocomplete');
defined('AUTOFOCUS') || define('AUTOFOCUS', 'autofocus');

defined('BREADCRUMBS') || define('BREADCRUMBS', 'breadcrumbs');
defined('BUTTONS') || define('BUTTONS', 'buttons');
defined('BREAK_LINE') || define('BREAK_LINE', '<br>');
defined('CAPTION_OPTIONS') || define('CAPTION_OPTIONS', 'captionOptions');
defined('CSS_SELECT_MAXWIDTH') || define('CSS_SELECT_MAXWIDTH', ' select-css maxwidth ');
defined('CSS_SELECT') || define('CSS_SELECT', ' select-css ');
defined('COLSM1') || define('COLSM1', ' is-1 ');
defined('COLSM2') || define('COLSM2', ' is-2 ');
defined('COLSM6') || define('COLSM6', ' is-6 ');
defined('COLSM11') || define('COLSM11', ' is-11 ');
defined('CONTENT') || define('CONTENT', 'content');
defined('CONTENT_OPTIONS') || define('CONTENT_OPTIONS', 'contentOptions');
defined('DANGER') || define('DANGER', 'danger');
defined('DATA_PROVIDER') || define('DATA_PROVIDER', 'dataProvider');
defined('DATEFORMAT') || define('DATEFORMAT', 'dd-MM-yyyy');
defined('DATETIMEFORMAT') || define('DATETIMEFORMAT', 'DD-MM-YYYY HH:mm');
defined('DETAILVIEW_CLASS') || define('DETAILVIEW_CLASS', 'table is-striped is-hoverable is-fullwidth detail-view');
defined('ERROR') || define('ERROR', 'error');
defined('ERROR_MODULE') || define('ERROR_MODULE', 'Failed to {module}, error: {error}');
defined('ERROR_SUMMARY') || define('ERROR_SUMMARY', 'error-summary');

defined('FILTER') || define('FILTER', 'filter');
defined('FILTER_OPTIONS') || define('FILTER_OPTIONS', 'filterOptions');
defined('FILTER_INPUT_OPTIONS') || define('FILTER_INPUT_OPTIONS', 'filterInputOptions');
defined('FORMAT') || define('FORMAT', 'format');
defined('GRID_DATACOLUMN') || define('GRID_DATACOLUMN', 'yii\grid\DataColumn');
defined('GRID_ACTIONCOLUMN') || define('GRID_ACTIONCOLUMN', 'yii\grid\ActionColumn');
defined('GRID_CHECKBOXCOLUMN') || define('GRID_CHECKBOXCOLUMN', 'yii\grid\CheckboxColumn');

defined('GRIDVIEW_CSS') || define('GRIDVIEW_CSS', 'table  is-striped is-narrow is-hoverable is-fullwidth '); //is-bordered
defined('GRIDVIEW_LAYOUT') || define('GRIDVIEW_LAYOUT', '{items}<div class="columns"><div class="column"><br>{summary}</div><div class="column">{pager}</div></div><br>');
defined('HEADER') || define('HEADER', 'header');
defined('HEADER_OPTIONS') || define('HEADER_OPTIONS', 'headerOptions');
defined('HTML_BTN_BTN_DEFAULT') || define('HTML_BTN_BTN_DEFAULT', 'button is-default');
defined('HTML_BTN_BTN_PRIMARY') || define('HTML_BTN_BTN_PRIMARY', 'button is-primary');
defined('HTML_CLOSE_DIV_OPEN_COL_SM_8') || define('HTML_CLOSE_DIV_OPEN_COL_SM_8', '</div><div class="column is-8">');
defined('HTML_COLUMN') || define('HTML_COLUMN', '</div><div class="column">');
defined('HTML_COLUMNS') || define('HTML_COLUMNS', '<div class="columns"><div class="column">');
defined('HTML_COLUMNS_CLOSE') || define('HTML_COLUMNS_CLOSE', '</div></div>');
defined('HTML_DIV_CLOSE') || define('HTML_DIV_CLOSE', '</div>');
defined('HTML_DIV_CLOSEX2') || define('HTML_DIV_CLOSEX2', '</div></div>');
defined('HTML_MODEL_LG') || define('HTML_MODEL_LG', 'model-lg');
defined('HTML_OPTION_CLOSE') || define('HTML_OPTION_CLOSE', '</option>');
defined('HTML_OPTION') || define('HTML_OPTION', '<option>');
defined('HTML_ROW_OPEN_COL_SM_4') || define('HTML_ROW_OPEN_COL_SM_4', '<div class="columns"><div class="column is-4">');
defined('HTML_SPACEX2') || define('HTML_SPACEX2', '&nbsp; &nbsp;');
defined('HTML_WEBPAGE_CLOSE') || define('HTML_WEBPAGE_CLOSE', '</div>');

defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_3') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_3', '</div><div class="column is-3">');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4', '</div><div class="column is-4">');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_8') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_8', '</div><div class="column is-8">');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_9') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_9', '</div><div class="column is-9">');

defined('HTML_WEBPAGE_OPEN_COL_SM_3') || define('HTML_WEBPAGE_OPEN_COL_SM_3', '<div class="columns"><div class="column is-3">');
defined('HTML_WEBPAGE_OPEN_COL_SM_4') || define('HTML_WEBPAGE_OPEN_COL_SM_4', '<div class="columns"><div class="column is-4">');
defined('HTML_WEBPAGE_OPEN_COL_SM_8') || define('HTML_WEBPAGE_OPEN_COL_SM_8', '<div class="columns"><div class="column is-8">');
defined('HTML_WEBPAGE_OPEN_COL_SM_9') || define('HTML_WEBPAGE_OPEN_COL_SM_9', '<div class="columns"><div class="column is-9">');


defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_3S') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_3S', '</div><div class="column is-3 stickySidebar">');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4S') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_4S', '</div><div class="column is-4 stickySidebar">');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_8S') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_8S', '</div><div class="column is-8 stickySidebar">');
defined('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_9S') || define('HTML_WEBPAGE_CLOSE_OPEN_COL_SM_9S', '</div><div class="column is-9 stickySidebar">');

defined('HTML_WEBPAGE_OPEN_COL_SM_3S') || define('HTML_WEBPAGE_OPEN_COL_SM_3S', '<div class="columns stickyWrapper"><div class="column is-3">');
defined('HTML_WEBPAGE_OPEN_COL_SM_4S') || define('HTML_WEBPAGE_OPEN_COL_SM_4S', '<div class="columns stickyWrapper"><div class="column is-4">');
defined('HTML_WEBPAGE_OPEN_COL_SM_8S') || define('HTML_WEBPAGE_OPEN_COL_SM_8S', '<div class="columns stickyWrapper"><div class="column is-8">');
defined('HTML_WEBPAGE_OPEN_COL_SM_9S') || define('HTML_WEBPAGE_OPEN_COL_SM_9S', '<div class="columns stickyWrapper"><div class="column is-9">');

defined('HTML_ROW_DIV6') || define('HTML_ROW_DIV6', '<div class="columns"><div class="column is-6">');
defined('HTML_DIV_CLOSE_DIV6_OPEN') || define('HTML_DIV_CLOSE_DIV6_OPEN', '</div><div class="column is-6">');


defined('HTML_WEBPAGE_OPEN') || define('HTML_WEBPAGE_OPEN', '<div class="webpage">');
defined('ID') || define('ID', 'id');
defined('ICON_LEFT') || define('ICON_LEFT', 'icon-left');
defined('ICON_RIGHT') || define('ICON_RIGHT', 'icon-right');
defined('ICON_HELP') || define('ICON_HELP', 'icon-help');

defined('INFO') || define('INFO', 'info');
defined('INPUT') || define('INPUT', 'input');
defined('INPUT_OPTIONS') || define('INPUT_OPTIONS', 'inputOptions');
defined('INPUT_TEMPLATE') || define('INPUT_TEMPLATE', 'inputTemplate');
defined('ITEMS') || define('ITEMS', 'items');
defined('LABEL') || define('LABEL', 'label');
defined('LABELOPTIONS') || define('LABELOPTIONS', 'labelOptions');
defined('LANGUAGE') || define('LANGUAGE', 'label');
defined('LENGTH') || define('LENGTH', 'length');
defined('MAXLENGTH') || define('MAXLENGTH', 'maxlength');
defined('MAXWIDTH') || define('MAXWIDTH', 'maxwidth');
defined('METHOD') || define('METHOD', 'method');
defined('MODEL') || define('MODEL', 'model');
defined('MODULE') || define('MODULE', 'module');
defined('MSG_ERROR') || define('MSG_ERROR', 40);
defined('MSG_INFO') || define('MSG_INFO', 10);
defined('MSG_SECURITY_ISSUE') || define('MSG_SECURITY_ISSUE', 50);
defined('MSG_SUCCESS') || define('MSG_SUCCESS', 20);
defined('MSG_WARNING') || define('MSG_WARNING', 30);
defined('OPTIONS') || define('OPTIONS', 'options');
defined('ONCLICK') || define('ONCLICK', 'onClick');
defined('PAGE_SIZE') || define('PAGE_SIZE', 'pageSize');
defined('PATTERN_DATE') || define('PATTERN_DATE', 'd-m-Y');
defined('PATTERN_DATETIME') || define('PATTERN_DATETIME', 'Y-m-d H:m:i');
defined('PATTERN_DATE_YMD') || define('PATTERN_DATE_YMD', 'Y-m-d');
defined('PATTERN') || define('PATTERN', 'pattern');
defined('PATTERN_PHONE') || define('PATTERN_PHONE', '[0-9]{3}-[0-9]{4}-[0-9]{4}');
defined('PLACEHOLDER') || define('PLACEHOLDER', 'placeholder');
defined('PROMPT') || define('PROMPT', 'prompt');
defined('RAW') || define('RAW', 'raw');
defined('RANGE') || define('RANGE', 'range');
defined('REQUIRED') || define('REQUIRED', 'required');
defined('ROLES') || define('ROLES', 'roles');
defined('SEARCH_MODEL') || define('SEARCH_MODEL', 'searchModel');
defined('STICKY_OPEN') || define('STICKY_OPEN', '<div class="stickyWrapper"><div class="stickyMain">');
defined('STICKY_SIDEBAR') || define('STICKY_SIDEBAR', '</div><div class="stickySidebar">');
defined('STICKY_CLOSE') || define('STICKY_CLOSE', '</div> </div>');

defined('STR_FA_CHECK') || define('STR_FA_CHECK', 'fa-check');
defined('STR_CLASS') || define('STR_CLASS', 'class');
defined('STR_DEFAULT') || define('STR_DEFAULT', 'default');
defined('STR_GET') || define('STR_GET', 'get');
defined('STR_POST') || define('STR_POST', 'post');
defined('STR_SAVE') || define('STR_SAVE', 'save');
defined('STR_DATA_PJAX') || define('STR_DATA_PJAX', 'data-pjax');
defined('STR_FORM_TITLE') || define('STR_FORM_TITLE', '<p class=" formTitle">');
defined('STRING') || define('STRING', 'string');
defined('SUCCESS') || define('SUCCESS', 'success');
defined('TABINDEX') || define('TABINDEX', 'tabindex');
defined('TEMPLATE') || define('TEMPLATE', 'template');
defined('TITLE') || define('TITLE', 'title');
defined('TRANSACTION_MODULE') || define('TRANSACTION_MODULE', '{module} {method} {error}');
defined('TRANSACTION_MODULE_ID') || define('TRANSACTION_MODULE_ID', '{module}, {method} record:{id} error:{error}');
defined('TYPE') || define('TYPE', 'type');
defined('UNCHECK') || define('UNCHECK', 'uncheck');
defined('VALUE') || define('VALUE', 'value');
defined('VISIBLE') || define('VISIBLE', 'visible');

defined('X_MOZ_ERROR_MESSAGE')  || define('X_MOZ_ERROR_MESSAGE', 'x-moz-errormessage');
defined('WARNING') || define('WARNING', 'warning');
defined('WIDTH5PX') || define('WIDTH5PX', 'width5px');
defined('WIDTH10PX') || define('WIDTH10PX', 'width10px');
defined('WIDTH20') || define('WIDTH20', 'width20');
