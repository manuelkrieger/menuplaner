'use strict'

var gulp = require('gulp');
var browserSync = require('browser-sync').create();
var sass = require('gulp-sass');
var concat = require('gulp-concat');
var filter = require('gulp-filter');
var mainBowerFiles = require('main-bower-files');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var del = require('del');
var runSequence = require('run-sequence');
var cleanCSS = require('gulp-clean-css');
var replace = require('gulp-replace');

gulp.paths = {
    src: 'app/Resources/assets',
    dist: 'web'
};

var paths = gulp.paths;

gulp.task('watch', ['sass', 'scripts'], function () {
    gulp.watch('app/Resources/assets/sass/**/*.scss', ['sass']);
    gulp.watch('app/Resources/assets/js/*.js', ['scripts']);
});

gulp.task('sass', function () {
    return gulp.src('app/Resources/assets/sass/style.scss')
        .pipe(sass())
        .pipe(rename({suffix: '.min'}))
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest(paths.dist + '/css'))
        .pipe(browserSync.stream());
});

gulp.task('clean:dist', function () {
    return del(paths.dist);
});

gulp.task('copy:bower', function () {
    return gulp.src(mainBowerFiles(['**/*.js', '!**/*.min.js']))
        .pipe(gulp.dest(paths.dist + '/js/libs'))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(paths.dist + '/js/libs'));
});

gulp.task('copy:css', function () {
    gulp.src(paths.src + '/css/**/*')
        .pipe(gulp.dest(paths.dist + '/css'));

});

gulp.task('copy:img', function () {
    return gulp.src(paths.src + '/img/**/*')
        .pipe(gulp.dest(paths.dist + '/img'));
});

gulp.task('copy:fonts', function () {
    return gulp.src(paths.src + '/fonts/**/*')
        .pipe(gulp.dest(paths.dist + '/fonts'));
});

// Scripts
gulp.task('scripts', function () {
    return gulp.src(paths.src + '/js/**/*')
        .pipe(concat({path: 'main.js'}))
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(paths.dist + '/js'))
});

gulp.task('build:dist', function (callback) {
    runSequence('clean:dist', 'copy:bower', 'copy:css', 'copy:img', 'copy:fonts', 'sass', 'scripts', callback);
});

gulp.task('default', ['sass', 'scripts']);
