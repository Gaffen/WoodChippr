var gulp          = require("gulp"),
    webpackStream = require('webpack-stream'),
    webpack       = require('webpack'),
    livereload    = require("gulp-livereload"),
    config        = require("../config.json");

// compile scripts
gulp.task("scripts", function() {
  "use strict";

  var base = './'+process.env.DEV_FILES_DIR+'/'+process.env.JS_DIR+'/';

  var entryPoints = config.bundles.reduce(function(accum, src){
    accum.push(base+src);
    return accum;
  }, []);

  var options = {
    entry: entryPoints,
    output: {
      filename: "[name].bundle.js"
    }
  }

  if(process.env.NODE_ENV === "development"){
    options.devtool = 'source-map';
  } else {
    options.plugins = [new webpack.optimize.UglifyJsPlugin()];
  }

  return gulp.src(entryPoints, {base:base})
    .pipe(webpackStream(options, webpack))
    .pipe(gulp.dest(config.projectDir + process.env.WP_CONTENT_DIR + '/themes/' + process.env.THEME_NAME + '/' + "/js"))
    .pipe(livereload());
});
