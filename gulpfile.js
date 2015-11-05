var gulp = require('gulp');
var replace = require('gulp-replace');
var path = require('path');
var zip = require('gulp-zip');
var del = require('del');
var pj = require('./package.json');

function getFormatDate () {
    var date = new Date();
    var dd = date.getDate();
    if (dd < 10) dd = "0" + dd;
    var mm = date.getMonth() + 1;
    if (mm < 10) mm = "0" + mm;
    var yy = date.getFullYear();
    return "" + yy + mm + dd;
};

gulp.task('clean', function () {
    return del('dist/*');
});

gulp.task('build', ['update-manifest'], function () {
    return gulp.src('**/*', {cwd:  path.join(process.cwd(), '/src')})
        .pipe(zip('neomessenger_v' + pj.version + '.zip'))
        .pipe(gulp.dest('dist'));
});

gulp.task('update-manifest', function () {
    return gulp.src('src/manifest.*.ini')
        .pipe(replace(/date = ".*"/, 'date = "' + getFormatDate() + '"'))
        .pipe(gulp.dest('src/'));
});

gulp.task('default', ['clean'], function () {
    gulp.start('build');
});