var gulp = require('gulp'),
    browserSync = require('browser-sync').create(),
    sass = require('gulp-sass'),
    concat = require('gulp-concat'),
    babel = require('gulp-babel'),
    uglify = require('gulp-uglify'),
    browserify2 = require('browserify'),
    babelify = require('babelify'),
    source = require('vinyl-source-stream'),
    buffer = require('vinyl-buffer'),
    autoprefixer = require('gulp-autoprefixer');

sass.compiler = require('node-sass');

gulp.task('sass', function () {
    return gulp.src('templates/pianino_new/front_end/scss/**/*.scss')
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false,
        }))
        .pipe(gulp.dest('templates/pianino_new/public/css/'))
        .pipe(browserSync.stream());
});

gulp.task('common-js', function () {
    return browserify2([
            "templates/pianino_new/front_end/js/index.js"
        ], {
            debug: true
        })
        .transform(babelify.configure({
            presets: ["@babel/preset-env"]
        }))
        .bundle()
        .pipe(source('app.min.js'))
        .pipe(buffer())
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(concat('app.min.js'))
        .pipe(uglify({
            toplevel: true
        }))
        .pipe(gulp.dest('templates/pianino_new/public/js/'))
});

gulp.task('watch', function () {
    // browserSync.init({
    //     proxy: "doublebitaxe.local"
    // });
    gulp.watch('templates/pianino_new/front_end/scss/**/*.scss', gulp.series('sass'));
    gulp.watch('templates/pianino_new/front_end/js/**/*.js', gulp.series('common-js'));
})

gulp.task('build-dist', function (done) {
    done();
});

gulp.task('build', gulp.series('sass', 'build-dist', 'common-js'));

// gulp.task('default', gulp.series(
//     gulp.parallel('sass', 'common-js'),
//     'watch'
// ));