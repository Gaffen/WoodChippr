var gulp = require("gulp"),
  eslint = require("gulp-eslint"),
  notify = require("gulp-notify"),
  config = require("../config.json");

gulp.task("lint", function() {
  "use strict";
  var base = config.tooling.devDir + "/" + config.tooling.jsDir + "/";
  return gulp
    .src(base + "*.js")
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failOnError())
    .on("error", notify.onError());
});
