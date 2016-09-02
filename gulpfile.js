var gulp = require('gulp');
var plugins = require('gulp-load-plugins')();

gulp.task('vendor', function ()
{
	gulp.src('bower_components/**/*.*', { base: 'bower_components' })
		.pipe(gulp.dest('public/vendor'))
	;
});
gulp.task('bs4', function ()
{
	gulp.src('src/bootstrap/scss/bootstrap-custom.scss')
		.pipe(plugins.plumber())
		.pipe(plugins.sourcemaps.init())
		.pipe(plugins.sass({
			includePaths: ['bower_components/bootstrap/scss']
		}))
		.on('error', function ()
		{
			plugins.sass.logError();
		})
		.pipe(plugins.autoprefixer({
			browsers: [
				'last 2 versions', 
				'> 1%', 
			]
		}))
		.pipe(plugins.sourcemaps.write('.'))
		.pipe(gulp.dest('public/build/bootstrap/dist/css'))
		.on('end', function ()
		{
			console.log('bootstrap-custom.scss compilation finished!!');
		})
	;
});
gulp.task('watch', function ()
{
	gulp.watch('src/bootstrap/scss/**/*.*', ['bs4']);
});