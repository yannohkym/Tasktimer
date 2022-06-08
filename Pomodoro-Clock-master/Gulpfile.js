var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var minifycss = require('gulp-minify-css');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');
var jshint =require("gulp-jshint")
var livereload = require("gulp-livereload")
var del = require('del');

gulp.task('styles',function(){
	gulp.src("sass/**/*.scss")
		.pipe(sass().on("error",sass.logError))
		.pipe(autoprefixer())
		.pipe(gulp.dest("css/"))
		.pipe(rename({suffix:'.min'}))
		.pipe(minifycss())
		.pipe(gulp.dest('css/'))
})

gulp.task('scripts', function(){
	gulp.src('test-js/**/*.js')
		.pipe(jshint())
		.pipe(jshint.reporter('default'))
		.pipe(concat('main.js'))
		.pipe(gulp.dest('js/'))
		.pipe(rename({suffix:'.min'}))
		.pipe(uglify())
		.pipe(gulp.dest("js/"))
})

gulp.task("default",function(){
	gulp.watch('sass/**/*.scss',['styles']);
	gulp.watch('test-js/**/*.js',['scripts']);

	livereload.listen();

	gulp.watch(["sass/**/*.scss","./index.html","test-js/**/*.js"]).on("change",livereload.changed);
})