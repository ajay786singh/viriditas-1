
/* TO-DO:
1. add sass-linting
2. Fonts

*/

// Pull in gulp plugins and assign to variables
var gulp 		= require('gulp'),
	uglify 		= require('gulp-uglifyjs'),
	plumber    	= require('gulp-plumber'),
	sass 		= require('gulp-ruby-sass'),
	imagemin 	= require('gulp-imagemin'),
	pngquant 	= require('imagemin-pngquant'),
	livereload 	= require('gulp-livereload'),
	notify 		= require('gulp-notify'),
	sassbeautify = require('gulp-sassbeautify'),
	jshint 		= require('gulp-jshint');

// Create custom variables to make life easier
var outputDir = 'dist';

var scriptList = [
	//'src/components/jquery/dist/jquery.js', 
	'src/components/jquery-viewport-checker/src/jquery.viewportchecker.js',
	'src/components/readmore/readmore.js',
	'src/components/magnific-popup/dist/jquery.magnific-popup.js',
	'src/js/custom/jquery.ui.map.min.js',
	'src/js/custom/map.js',
	'src/js/custom/equal-height.js',
	'src/js/custom/appointments.js',
	'src/js/custom/readmore.js',
	'src/js/custom/jquery.classie.js',
	'src/js/custom/dropkick.js',
	'src/js/custom/modal-box.js',
	'src/js/custom/menu.js',
	'src/js/custom/menu-function.js',
	'src/js/custom/products.js',
	'src/js/custom/tabs.js',
	'src/js/custom/header-scroll.js',
	'src/js/custom/courses.js',
	'src/js/custom/accordion.js',
	'src/js/custom/login.js',
	'src/js/custom/single-cart.js',
	'src/js/custom/compound.js',
	'src/js/custom/scroll-to-url-hash.js',
];

var fontIcons = [
	'src/components/fontawesome/fonts/**.*', 
	'src/components/monosocialiconsfont/MonoSocialIconsFont*.*'
];

var sassOptions = {
	style: 'compressed'
};

//Beautify sass files
gulp.task('beautify-scss', function () {
  gulp.src('src/**/*.scss')
    .pipe(sassbeautify())
    .pipe(gulp.dest('src'))
})

// Create sass compile task
gulp.task('sass', function() {
    return sass('src/sass/style.scss', sassOptions) 
    .on('error', function (err) { console.error('Error!', err.message); })
    .pipe(gulp.dest(''))
    .pipe(livereload())
    .pipe(notify("sass task finished"));
});

// Create image minification task
gulp.task('imagemin', function () {
    return gulp.src('src/images/*')
    	//.pipe(cache())
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest(outputDir + '/images'))
        .pipe(notify("image task finished"));
});

// Create js scripts concat and minify task.
gulp.task('js', function() {
 	return gulp.src(scriptList)
 		.pipe(jshint('.jshintrc'))
    	.pipe(jshint.reporter('default'))
 		.pipe(plumber({errorHandler: notify.onError("Error: <%= error.message %>")}))
    	.pipe(uglify('app.min.js', {outSourceMap: true}))
    	.pipe(gulp.dest(outputDir + '/js'))
    	.pipe(livereload())
    	.pipe(notify("js task finished"));
});


// Create fonticons compile task
gulp.task('icons', function() { 
    return gulp.src(fontIcons) 
        .pipe(gulp.dest(outputDir + '/fonts')); 
});

// Create watch task
gulp.task('watch', function() {
	gulp.watch('src/js/**/*.js', ['js']);
	gulp.watch('src/sass/**/*.scss', ['sass']);
	gulp.watch('src/images/*', ['imagemin']);
	livereload.listen();
	gulp.watch('*.php').on('change', livereload.changed);
});

// Create default task so you can gulp whenever you don't want to watch
gulp.task('default', ['js', 'sass', 'imagemin', 'icons','beautify-scss']);