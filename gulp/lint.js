var gulp          = require("gulp"),
    eslint        = require("gulp-eslint"),
    notify        = require("gulp-notify");

gulp.task("lint", function() {
    "use strict";
    var base = process.env.DEV_FILES_DIR+'/'+process.env.JS_DIR+'/';
    return gulp.src(base+"*.js")
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failOnError())
        .on("error", notify.onError());
});
