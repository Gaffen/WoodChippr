// Include gulp
var gulp          = require("gulp"),

// Include Our Plugins
    eslint        = require("gulp-eslint")
    livereload    = require("gulp-livereload"),
    csso          = require("gulp-csso"),
    sass          = require("gulp-sass"),
    gulpif        = require("gulp-if"),
    prefix        = require("gulp-autoprefixer"),
    notify        = require("gulp-notify"),
    svgstore      = require("gulp-svgstore"),
    svgmin        = require("gulp-svgmin"),
    rename        = require("gulp-rename"),
    cheerio       = require("gulp-cheerio"),
    plumber       = require("gulp-plumber"),
    sourcemaps    = require("gulp-sourcemaps"),
    webpackStream = require('webpack-stream');
    webpack       = require('webpack');

var config = require("./config.json");

var env = process.env.NODE_ENV || "development";

gulp.task("lint", function() {
    "use strict";
    return gulp.src("dev/js/*.js")
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failOnError())
        .on("error", notify.onError());
});

// Compile Our Sass
gulp.task("sass", function() {
    "use strict";
    return gulp.src("dev/scss/*.scss")
          .pipe(plumber({
              errorHandler: notify.onError("Error: <%= error.message %>")
          }))
          .pipe(gulpif(env !== "production", sourcemaps.init()))
          .pipe(sass({
            style: "compact",
            errLogToConsole: false
          }))
          .pipe(prefix("last 2 version", "> 1%", "ie 9", "ie 8"))
          .pipe(gulpif(env !== "production", sourcemaps.write()))
          .pipe(gulpif(env === "production", csso()))
          .pipe(gulp.dest(config.projectDir + "css/"))
          .pipe(livereload())
          .pipe(notify("updated"));
});

// compile scripts
gulp.task("scripts", function() {
  "use strict";

  return gulp.src(config.bundles.main)
    .pipe(webpackStream({
      entry: config.bundles,
      output: {
        filename: "[name].bundle.js"
      },
      devtool: 'source-map',
      plugins:[new webpack.optimize.UglifyJsPlugin()]
    }, webpack))
    .pipe(gulp.dest(config.projectDir+"/js"));
});

gulp.task("svgstore", function () {
  "use strict";
  return gulp
      .src("dev/svg/*.svg")
      .pipe(cheerio({
        run: function ($) {
            $("[fill]").removeAttr("fill");
        },
        parserOptions: { xmlMode: true }
      }))
      .pipe(svgmin())
      .pipe(svgstore({inlineSvg: true}))
      .pipe(cheerio({
        run: function ($) {
            $("svg").attr("width", 0);
            $("svg").attr("height", 0);
        },
        parserOptions: { xmlMode: true }
      }))
      .pipe(rename("inline-svgsprite.svg.twig"))
      .pipe(gulp.dest(config.projectDir + "views/partials/"))
      .pipe(notify("SVGs Exported"));
});

// Watch Files For Changes
gulp.task("watch", function() {
    "use strict";
    livereload.listen();
    gulp.watch("dev/js/**/**/*.js", ["lint", "scripts"]);
    gulp.watch("dev/scss/**/**/*.scss", ["sass"]);
    gulp.watch("dev/svg/**/**/*.svg", ["svgstore"]);
    gulp.watch("wp-content/**/**/**/*.twig").on("change", function(file){
      livereload.reload(file);
      notify("Templates Refreshed");
    });
});

gulp.task("default", ["lint", "sass", "scripts", "svgstore", "watch"]);
