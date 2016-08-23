'use strict';

var gulp     = require('gulp');
var template = require('gulp-template-compile');
var replace  = require('gulp-replace');
var concat   = require('gulp-concat');

gulp.task('templates', function () {
    gulp.src('src/templates/*.html')
        .pipe(template({
            namespace: 'icms.neomessenger.templates',
            name: function (file) {
                return file.relative.replace('.html', '');
            }
        }))
        .pipe(concat('templates.js'))
        .pipe(gulp.dest('package/templates/default/controllers/neomessenger/js/'));
});

gulp.task('default', ['templates'], function () {});