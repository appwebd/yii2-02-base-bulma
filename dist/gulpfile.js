const {series, parallel} = require('gulp');
const gulp = require('gulp');
const concat = require('gulp-concat');  // Concatenate files
const cssnano = require('gulp-cssnano'); // minifier of css projaso
const minify = require('gulp-minify');
const rename = require('gulp-rename');
const sourcemaps = require('gulp-sourcemaps');
const cleanCSS = require('gulp-clean-css');

function clean(cb)
{
    // body omitted
    cb();
}

function concatenation(cb)
{
    gulp.src(
        [
            "./vendor/bower-asset/bulma/css/bulma.css",
            "./vendor/npm-asset/bulma-extensions/bulma-badge/dist/css/bulma-badge.min.css",
            "./vendor/npm-asset/bulma-extensions/bulma-checkradio/dist/css/bulma-checkradio.min.css",
            "./vendor/npm-asset/bulma-extensions/bulma-tooltip/dist/css/bulma-tooltip.min.css",
            "./web/css/site.css"
        ]
    )

        .pipe(concat("style.css"))
        .pipe(gulp.dest("./web/css"));
    cb();
}

function cssGenerate(cb)
{
    gulp.src('./web/css/style.css')
        .pipe(rename('style.min.css'))


        .pipe(sourcemaps.init())
        .pipe(cleanCSS({compatibility: "ie8"}))
        .pipe(
            cssnano(
                {
                    mergeRules: true,
                    discardUnused: true,
                    discardDuplicates: true,
                    discardEmpty: true,
                    discardComments: {removeAll: true},
                    minimize: true,
                    zindex: false,
                    minifyFontValues: false,
                    preferredQuote: "single",
                    normalizeString: true
                }
            )
        )
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest("./web/css/"));
    cb();
}

function cssLoginGenerate(cb)
{
    return gulp.src("./web/css/login.css")
        .pipe(rename("login.min.css"))
        .pipe(sourcemaps.init())
        .pipe(
            cssnano(
                {
                    discardComments: {removeAll: true},
                    minimize: true,
                    zindex: false,
                    discardDuplicates: true,
                    discardEmpty: true,
                    minifyFontValues: false,
                    normalizeString: true
                }
            )
        )
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest("./web/css/"));
    cb();
}

function javascript(cb)
{
    gulp.src(
        [
        "./web/js/all.js",  // If you want to use icons with Bulma, don't forget to include Font Awesome 5:
        "./web/js/bulma.js",
//        "./web/js/custom.js"

        ]
    )

    .pipe(concat("javascript-distr.js"))
    .pipe(gulp.dest("./web/js/"));
    cb();

}
function minifyJS(cb)
{
    gulp.src(
        [
            "./web/js/javascript-distr.js"
        ]
    )
    .pipe(rename("javascript-distr.min.js"))
    .pipe(sourcemaps.init())
    .pipe(gulp.dest("./web/js/"))
    .pipe(minify())
    .pipe(sourcemaps.write("."))
    .pipe(gulp.dest("./web/js/"));
    cb();
}
exports.build = series(clean, parallel(cssGenerate, cssLoginGenerate, javascript), minifyJS);
exports.default = series(clean, concatenation, parallel(cssGenerate, javascript), minifyJS);
