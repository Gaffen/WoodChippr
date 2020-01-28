var gulp = require("gulp"),
  webpackStream = require("webpack-stream"),
  webpack = require("webpack"),
  livereload = require("gulp-livereload"),
  notify = require("gulp-notify"),
  config = require("../config.json"),
  babelOptions = require("./babelConfig.js"),
  modernizrConfig = require("./modernizrConfig.js");

// compile scripts
gulp.task("scripts", function() {
  "use strict";

  var base = "./" + config.tooling.devDir + "/" + config.tooling.jsDir + "/";

  var entryPoints = config.bundles.reduce(function(accum, src) {
    accum.push(base + src);
    return accum;
  }, []);

  var options = {
    entry: entryPoints,
    output: {
      filename: "[name].bundle.js"
    }
  };

  if (config.tooling.useBabel) {
    options.module = babelOptions.module;
  }

  if (config.tooling.useModernizr) {
    options.resolve = modernizrConfig.resolve;
  }

  if (process.env.NODE_ENV === "development") {
    options.devtool = "source-map";
    options.mode = "development";
  } else {
    options.mode = "production";
  }

  return gulp
    .src(entryPoints, { base: base })
    .pipe(webpackStream(options, webpack))
    .on("error", function() {
      this.emit("end");
      notify.onError();
    })
    .pipe(
      gulp.dest(
        config.projectDir +
          process.env.WP_CONTENT_DIR +
          "/themes/" +
          config.themeName +
          "/" +
          "/js"
      )
    )
    .pipe(livereload());
});
