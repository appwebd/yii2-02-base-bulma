#!/bin/bash
rm web/css/style.min.css
rm web/js/javascript-distr.min.js
#rm cache/*
gulp concatenation
#gulp uncss
gulp creating_file_style_css
gulp creating_file_login_css
gulp concat_and_minify_javascript
