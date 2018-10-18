var gulp = require("gulp"),
  webpackStream = require("webpack-stream"),
  webpack = require("webpack"),
  livereload = require("gulp-livereload"),
  notify = require("gulp-notify"),
  config = require("../config.json"),
  UglifyJSPlugin = require("uglifyjs-webpack-plugin"),
  babelOptions = require("./babelConfig.js"),
  modernizrConfig = require("./modernizrConfig.js");

// compile scripts
gulp.task("scripts", function() {
  "use strict";

  var base = "./" + process.env.DEV_FILES_DIR + "/" + process.env.JS_DIR + "/";

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

  if (process.env.USE_BABEL) {
    options.module = babelOptions.module;
  }

  if (process.env.USE_MODERNIZR) {
    var moduleSettingsRules = [];

    if (
      options.hasOwnProperty("module") &&
      options.module.hasOwnProperty("rules")
    ) {
      moduleSettingsRules = moduleSettingsRules.concat(
        options.module.rules,
        modernizrConfig.module.rules
      );
    } else {
      moduleSettingsRules = modernizrConfig.module.rules;
    }

    options.module.rules = moduleSettingsRules;

    options.resolve = modernizrConfig.resolve;
  }

  if (process.env.NODE_ENV === "development") {
    options.devtool = "source-map";
  } else {
    Object.assign(options, {
      plugins: [new webpack.optimize.UglifyJsPlugin()]
    });
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
          process.env.THEME_NAME +
          "/" +
          "/js"
      )
    )
    .pipe(livereload());
});
