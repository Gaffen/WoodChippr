require("dotenv").config();

var gulp = require("gulp"),
  gulpif = require("gulp-if"),
  plumber = require("gulp-plumber"),
  sass = require("gulp-sass"),
  prefix = require("gulp-autoprefixer"),
  notify = require("gulp-notify"),
  sourcemaps = require("gulp-sourcemaps"),
  csso = require("gulp-csso"),
  // merge         = require('merge-stream');
  config = require("../config.json"),
  livereload = require("gulp-livereload");

// Compile Our Sass
gulp.task("sass", function() {
  "use strict";

  var base = config.tooling.devDir + "/" + config.tooling.scssDir + "/";

  var entryPoints = config.sassfiles.reduce(function(accum, src) {
    accum.push(base + src);
    return accum;
  }, []);

  return gulp
    .src(entryPoints)
    .pipe(
      plumber({
        errorHandler: notify.onError("Error: <%= error.message %>")
      })
    )
    .pipe(gulpif(process.env.NODE_ENV !== "production", sourcemaps.init()))
    .pipe(
      sass({
        style: "compact",
        errLogToConsole: false
      })
    )
    .pipe(prefix(config.tooling.autoprefixerArgs))
    .pipe(gulpif(process.env.NODE_ENV !== "production", sourcemaps.write()))
    .pipe(gulpif(process.env.NODE_ENV === "production", csso()))
    .pipe(
      gulp.dest(
        config.projectDir +
          process.env.WP_CONTENT_DIR +
          "/themes/" +
          config.themeName +
          "/" +
          "css/"
      )
    )
    .pipe(livereload())
    .pipe(notify("updated"));
});
