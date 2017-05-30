require('dotenv').config();
// Include gulp
var requireDir    = require('require-dir'),
    gulp          = require("gulp"),

// Include Our Plugins
    livereload    = require("gulp-livereload");

requireDir('./gulp', { recurse: true });

// Watch Files For Changes
gulp.task("startwatch", ["lint", "sass", "scripts", "svgstore", "watch"]);

gulp.task("watch", function() {
  "use strict";
  livereload.listen();
  gulp.watch(
    process.env.DEV_FILES_DIR+'/'+process.env.JS_DIR+"/**/**/*.js",
    ["lint", "scripts"]
  );
  gulp.watch(
    process.env.DEV_FILES_DIR+'/'+process.env.SCSS_DIR+"/**/**/*.scss",
    ["sass"]
  );
  gulp.watch(
    process.env.DEV_FILES_DIR+'/'+process.env.SVG_DIR+"/**/**/*.svg",
    ["svgstore"]
  );
  gulp.watch("web/wp-content/**/**/**/*.twig").on("change", function(file){
    livereload.reload(file);
    notify("Templates Refreshed");
  });
});

gulp.task("build", ["lint", "sass", "scripts", "svgstore"]);

gulp.task("default", ["lint", "sass", "scripts", "svgstore"]);
