// sudo npm install -g gulp
// npm install --save gulp-concat gulp-uglifyjs gulp-sass gulp-minify-css gulp-watch gulp-notify gulp-plumber gulp-jsvalidate gulp-cssnano


var gulp = require('gulp');
var terser = require('gulp-terser');
var concat = require('gulp-concat');
var order = require('gulp-order');
var uglify = require('gulp-uglifyjs');
var sass = require('gulp-sass');
var minify = require('gulp-minify-css');
var watch = require('gulp-watch');
var notify = require("gulp-notify");
var plumber = require('gulp-plumber');
var jsValidate = require('gulp-jsvalidate');


var onError = function (err) {
    notify({
        title: 'Gulp Task Error',
        message: 'Check the console.'
    }).write(err);
    console.log(err.toString());
    this.emit('end');
};


// ------------------------------------------------
//  ADMIN. gulp Plugin Scripts
// ------------------------------------------------
gulp.task('admin_js', function () {
    console.log("Validate JavaScript");
    return gulp.src([
        './resources/js/admin/libs/jquery/dist/jquery.js',
        './resources/js/admin/libs/bootstrap/dist/js/bootstrap.js',
        './resources/js/admin/libs/waves/dist/waves.js',
        './resources/js/admin/libs/toastr/toastr.min.js',
        './resources/js/admin/libs/bootstrap-select/bootstrap-select.js',
        './resources/js/admin/libs/tagsinput/bootstrap-tagsinput.js',
        './resources/js/admin/libs/dropzone/dropzone.js',
        './resources/js/admin/libs/sortablejs/Sortable.js',
        './resources/js/admin/libs/moment/moment-with-locales.js',
        './resources/js/admin/libs/jscolor/jscolor.min.js',
        './resources/js/admin/libs/jquery-ui/jquery-ui.js',
        './resources/js/admin/libs/jquery-ui/ui-touch.js',
        './resources/js/admin/libs/fastselect/fastselect.js',
        './resources/js/admin/libs/multiselect/js/jquery.quicksearch.js',
        './resources/js/admin/libs/multiselect/js/jquery.multi-select.js',
        './resources/js/admin/libs/fastselect/select2.js',
        './resources/js/admin/libs/inputmask/inputmask.js',
        './resources/js/admin/libs/inputmask/inputmask.extensions.js',
        './resources/js/admin/libs/inputmask/jquery.inputmask.js',
        './resources/js/admin/libs/inputmask/inputmask.numeric.extensions.js',
        './resources/js/admin/libs/daterangepicker.js',

        './resources/js/admin/libs/js-year-calendar.min.js',

        './resources/js/admin/libs/bootstrap-datepicker.js',
        './resources/js/admin/scripts/ui-load.js',
        './resources/js/admin/scripts/ui-jp.config.js',
        './resources/js/admin/scripts/ui-jp.js',
        './resources/js/admin/scripts/ui-nav.js',
        './resources/js/admin/scripts/ui-toggle.js',
        './resources/js/admin/scripts/ui-form.js',
        './resources/js/admin/scripts/ui-waves.js',
        './resources/js/admin/scripts/ui-client.js',
        './resources/js/admin/scripts/confirm-modal.js',
        './resources/js/admin/custom/*.js',
        './resources/js/admin/all-functions.js',
        './resources/js/admin/libs/datatables/media/js/datatables.min.js',
        './resources/js/admin/libs/magnific-popup/jquery.magnific-popup.min.js',
        './resources/js/admin/libs/bootstrap-material-datetimepicker/js/datetimepicker.js',
        './resources/js/admin/libs/maps/leaflet.js',
    ]).pipe(concat('admin-global.min.js'))
        .pipe(jsValidate())
        .on("error", notify.onError(function (error) {
            return error.message;
        }))
        .pipe(uglify())
        .pipe(gulp.dest('./public/js'))
        .pipe(notify({
            message: 'JavaScript complete'
        }));
});


// ------------------------------------------------
// ADMIN. gulp Sass
// ------------------------------------------------
gulp.task('admin_sass', function () {
    return gulp.src([
        './resources/js/admin/libs/bootstrap/dist/css/bootstrap.css',
        './resources/sass/admin/admin_style.scss'])
        .pipe(plumber({errorHandle: onError}))
        .pipe(sass())
        .on('error', onError)
        .pipe(minify())
        .pipe(concat('admin-style.min.css'))
        .pipe(gulp.dest('./public/css'))
        .pipe(notify({
            message: 'SASS complete'
        }));
});


// ------------------------------------------------
//  FRONT. gulp Plugin Scripts
// ------------------------------------------------
gulp.task('front_js', function () {
    console.log("Validate JavaScript");
    return gulp.src([
        './resources/js/front/vendors/jquery.min.js',                //  jQuery              v2.2.4
        './resources/js/front/vendors/popper.js',                    //  Popper              v1.14.3
        './resources/js/front/vendors/bootstrap.min.js',             //  Bootstrap           v4.3.1
        './resources/js/front/vendors/jquery-ui.js',
        './resources/js/front/libs/sweetalert-dev.min.js',
        './resources/js/front/libs/jquery.magnific-popup.min.js',
        './resources/js/front/libs/jquery.fancybox.js',
        './resources/js/front/libs/owl.js',
        './resources/js/front/libs/appear.js',
        './resources/js/front/libs/wow.js',
        './resources/js/front/libs/leaflet-src.js',                  //  Leaflet
        './resources/js/front/libs/leaflet.markercluster-src.js',    //  Leaflet
        './resources/js/front/libs/map.js',
        './resources/js/front/libs/scrollbar.js',
        './resources/js/front/libs/validate.js',
        './resources/js/front/libs/element-in-view.js',
        './resources/js/front/libs/fotorama.js',                     //  Fotorama            v4.6.4//  Colour Detect: 1.2
        './resources/js/admin/libs/bootstrap-datepicker.js',

        './resources/js/admin/libs/inputmask/inputmask.js',
        './resources/js/admin/libs/inputmask/inputmask.extensions.js',
        './resources/js/admin/libs/inputmask/jquery.inputmask.js',
        './resources/js/admin/libs/inputmask/inputmask.numeric.extensions.js',
        //
        //'./resources/js/front/libs/slider/jquery.themepunch.revolution.min.js',
        //'./resources/js/front/libs/slider/jquery.themepunch.tools.min.js',
        //
        './resources/js/front/custom/*.js',
        './resources/js/front/all-functions.js' //  Plugins Functions
    ])
        .pipe(concat('front-global.min.js'))
        .pipe(jsValidate())
        .on("error", notify.onError(function (error) {
            return error.message;
        }))
        .pipe(terser())
        .pipe(gulp.dest('./public/js'))
        .pipe(notify({
            message: 'JavaScript complete'
        }));
});

// ------------------------------------------------
// FRONT. gulp Sass
// ------------------------------------------------
gulp.task('front_sass', function () {
    return gulp.src('./resources/sass/front/style.scss')
        .pipe(plumber({errorHandle: onError}))
        .pipe(sass())
        .on('error', onError)
        .pipe(minify())
        .pipe(concat('front-style.min.css'))
        .pipe(gulp.dest('./public/css'))
        .pipe(notify({
            message: 'SASS complete'
        }));
});

// ------------------------------------------------
//  FONT SASS
// ------------------------------------------------

var fontFiles = './resources/sass/front/myFonts.scss',
    fontDest = './public/css';

gulp.task('fonts', function () {
    return gulp.src(fontFiles)
        .pipe(plumber({errorHandle: onError}))
        .pipe(sass())
        .on('error', onError)
        .pipe(cssnano({
            discardComments: {removeAll: true},
            discardUnused: {fontFace: false}
        }))
        .pipe(concat('fonts.min.css'))
        .pipe(gulp.dest(fontDest))
        .pipe(notify({
            message: 'SASS complete'
        }));
});