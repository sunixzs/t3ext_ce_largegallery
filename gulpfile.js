const gulp = require("gulp");
const uglify = require("gulp-uglify");
const sass = require("gulp-sass");
const autoprefixer = require("autoprefixer");
const postcss = require("gulp-postcss");

var config = {
    // generate css from scss + autoprefixer
    styles: {
        filesWatchSource: "./Resources/Public/style/scss/**/*.scss",
        files: {
            "./Resources/Public/style/scss/Largegallery.scss": "./Resources/Public/style/",
        }
    },
    // minify js
    js: {
        filesWatchSource: "./Resources/Public/script/**/*.js",
        files: {
            "./Resources/Public/script/src/Largegallery.js": "./Resources/Public/script/",
        }
    }
};

// Minifies the js files
gulp.task("js", function(cb) {
    for (var key in config.js.files) {
        console.log("js -> uglify: " + key);
        console.log("          to: " + config.js.files[key]);
        gulp.src(key)
            .pipe(uglify())
            .pipe(gulp.dest(config.js.files[key]));
    }
    cb();
});

// Compiles SASS into CSS
gulp.task("scss", function(cb) {
    var plugins = [autoprefixer()];
    for (var key in config.styles.files) {
        console.log("scss -> css: " + key);
        console.log("         to: " + config.styles.files[key]);
        gulp.src(key)
            .pipe(
                sass({
                    indentType: "space",
                    indentWidth: 4,
                    sourceMap: true,
                    outputStyle: "expanded"
                }).on("error", sass.logError)
            )
            .pipe(postcss(plugins))
            .pipe(gulp.dest(config.styles.files[key]));
    }
    cb();
});

gulp.task("watch_scss", function() {
    return gulp.watch(config.styles.filesWatchSource, gulp.series("scss"));
});

gulp.task("watch_js", function() {
    return gulp.watch(config.js.filesWatchSource, gulp.series("js"));
});