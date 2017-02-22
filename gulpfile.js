var gulp = require('gulp');
var $ = require('gulp-load-plugins')({lazy: false});

var assetsPath = './package/templates/default/controllers/neomessenger/';

gulp.task('templates', function () {
    return gulp.src('src/templates/*.html')
        .pipe($.templateCompile({
            namespace: 'icms.neomessenger.templates',
            name: function (file) {
                return file.relative.replace('.html', '');
            }
        }))
        .pipe($.concat('templates.min.js'))
        .pipe($.uglify())
        .pipe(gulp.dest(assetsPath + 'js'));
});

gulp.task('scripts:libs', function () {
    return gulp.src([
        'src/js/libs/*.js'
    ])
        .pipe($.concat('libs.min.js'))
        .pipe($.uglify())
        .pipe(gulp.dest(assetsPath + 'js'));
});

gulp.task('scripts', ['scripts:libs']);

gulp.task('default', ['templates', 'scripts'], function () {});