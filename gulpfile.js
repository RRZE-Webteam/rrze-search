'use strict';

const
    {src, dest, watch, series} = require('gulp'),
    sass = require('gulp-sass')(require('sass')),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    uglify = require('gulp-uglify'),
    bump = require('gulp-bump'),
    semver = require('semver'),
    info = require('./package.json'),
    wpPot = require('gulp-wp-pot'),
    touch = require('gulp-touch-cmd'),
    rename = require('gulp-rename'),
    cssnano = require('cssnano')

;


function css() {
    var plugins = [
        autoprefixer(),
        cssnano()
    ];
    return src('./src/sass/*.scss', {
            sourcemaps: false
        })
        .pipe(sass())
        .pipe(postcss(plugins))
        .pipe(dest(info.target.css))
	.pipe(touch());
}
function cssdev() {
     var plugins = [
        autoprefixer()
    ];
    return src('./src/sass/*.scss', {
            sourcemaps: true
        })
        .pipe(sass())
        .pipe(postcss(plugins))
        .pipe(dest(info.target.css))
	.pipe(touch());
}

function makefrontendjs() {
    return src([info.source.js + 'rrze-search.js'])
   // .pipe(uglify())
    .pipe(rename("rrze-search.js"))
    .pipe(dest(info.target.js))
    .pipe(touch());
}
function makebackendjs() {
    return src([info.source.js + 'rrze-search-admin.js'])
    .pipe(uglify())
    .pipe(rename("rrze-search-admin.js"))
    .pipe(dest(info.target.js))
    .pipe(touch());
}


function patchPackageVersion() {
    var newVer = semver.inc(info.version, 'patch');
    return src(['./package.json', './' + info.main])
        .pipe(bump({
            version: newVer
        }))
        .pipe(dest('./'))
	.pipe(touch());
};
function prereleasePackageVersion() {
    var newVer = semver.inc(info.version, 'prerelease');
    return src(['./package.json', './' + info.main])
        .pipe(bump({
            version: newVer
        }))
	.pipe(dest('./'))
	.pipe(touch());;
};

function updatepot()  {
  return src("**/*.php")
  .pipe(
      wpPot({
        domain: info.textdomain,
        package: info.name,
	team: info.author.name,
	bugReport: info.repository.issues,
	ignoreTemplateNameHeader: true
 
      })
    )
  .pipe(dest(`languages/${info.textdomain}.pot`))
  .pipe(touch());
};


function startWatch() {
    watch('./src/sass/*.scss', css);
    watch('./src/js/*.js', js);
}

exports.css = css;
exports.js = series(makefrontendjs, makebackendjs);
exports.mainjs = makefrontendjs;
exports.backendjs =makebackendjs;
exports.dev = series(makefrontendjs, makebackendjs, cssdev, prereleasePackageVersion);
exports.build = series(makefrontendjs, makebackendjs, css, patchPackageVersion);
exports.pot = updatepot;

exports.default = css;
