var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  //mix.sass('app.scss');
  /*************** mix header styles ***************/
  mix.styles([
    "plugins/jquery-polymaps/style.css",
    "plugins/jquery-metrojs/MetroJs.css",
    "plugins/shape-hover/css/demo.css",
    "plugins/shape-hover/css/component.css",
    "plugins/owl-carousel/owl.carousel.css",
    "plugins/owl-carousel/owl.theme.css",
    "plugins/pace/pace-theme-flash.css",
    "plugins/jquery-slider/css/jquery.sidr.light.css",
    "plugins/jquery-ricksaw-chart/css/rickshaw.css",
    "plugins/jquery-isotope/isotope.css",
    "plugins/boostrapv3/css/bootstrap.min.css",
    "plugins/boostrapv3/css/bootstrap-theme.min.css",
    /**"plugins/font-awesome/css/font-awesome.css",**/
    "css/animate.min.css",
    "plugins/bootstrap-select2/select2.css",
    /**"css/style.css",**/
    "css/responsive.css",
    /**"css/custom-icon-set.css",**/
    "css/magic_space.css",
    "css/tiles_responsive.css",
    /**"plugins/jquery-datatable/css/jquery.dataTables.css",**/
    "plugins/boostrap-checkbox/css/bootstrap-checkbox.css",
    "plugins/datatables-responsive/css/datatables.responsive.css",
    /**"css/custom.css",**/
    /**"plugins/bootstrap-datepicker/css/datepicker.css"**/
  ], 'public/build/css/header_everything.css', 'public/assets');
  mix.styles([
    "css/custom.css"
  ], 'public/build/css/custom.css', 'public/assets');
  /*************** mix header styles ***************/
  mix.scripts([
    "custom/my_custom.js"
  ], 'public/build/js/my_custom.js', 'public/assets' );
});
