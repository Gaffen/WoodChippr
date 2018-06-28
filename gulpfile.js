require('dotenv').config();
// Include gulp
var requireDir = require('require-dir'),
	gulp = require('gulp'),
	// Include Our Plugins
	notify = require('gulp-notify'),
	livereload = require('gulp-livereload');

requireDir('./gulp', { recurse: true });

gulp.task('watch', function() {
	'use strict';
	livereload.listen();
	gulp.watch(
		process.env.DEV_FILES_DIR + '/' + process.env.JS_DIR + '/**/**/*.js',
		gulp.parallel('lint', 'scripts')
	);
	gulp.watch(
		process.env.DEV_FILES_DIR + '/' + process.env.SCSS_DIR + '/**/**/*.scss',
		gulp.parallel('sass')
	);
	gulp.watch(
		process.env.DEV_FILES_DIR + '/' + process.env.SVG_DIR + '/**/**/*.svg',
		gulp.parallel('svgstore')
	);
	var liveReloadWatcher = gulp.watch(
		'web/wp-content/**/**/**/*.twig',
		function() {
			return true;
		}
	);
	liveReloadWatcher.on('change', function(file) {
		livereload.reload(file);
		notify('Templates Refreshed');
	});
});

// Watch Files For Changes
gulp.task(
	'startwatch',
	gulp.series('lint', 'sass', 'scripts', 'svgstore', 'watch', function() {
		done();
	})
);

gulp.task(
	'build',
	gulp.series('lint', 'sass', 'scripts', 'svgstore'),
	function() {
		done();
	}
);

gulp.task(
	'default',
	gulp.series('lint', 'sass', 'scripts', 'svgstore'),
	function() {
		done();
	}
);
