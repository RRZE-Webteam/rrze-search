'use strict';

const gulp = require('gulp');
const sass = require('gulp-sass');
const rename = require("gulp-rename");
const uglify = require("gulp-uglify");

gulp.task('sass', function () {
    return gulp.src('./assets/sass/**/*.scss')
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(gulp.dest('./assets/css'));
});

gulp.task('js', function () {
    return gulp.src(['./assets/js/*.js', '!./assets/js/*.min.js'])
        .pipe(uglify()).on('error', function (err) { console.log(err.toString()); })
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('./assets/js'));
});

gulp.task('watch', function () {
    gulp.watch('./assets/sass/**/*.scss', ['sass']);
    gulp.watch('./assets/js/*.js', ['js']);
});