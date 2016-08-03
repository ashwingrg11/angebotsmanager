<!-- END CONTAINER -->
<!-- BEGIN CORE JS FRAMEWORK-->
<!-- for loader -->
<div class="loaderbg hide" style="z-index: 9999">
  <div id="circularG">
    <div id="circularG_1" class="circularG">
    </div>
    <div id="circularG_2" class="circularG">
    </div>
    <div id="circularG_3" class="circularG">
    </div>
    <div id="circularG_4" class="circularG">
    </div>
    <div id="circularG_5" class="circularG">
    </div>
    <div id="circularG_6" class="circularG">
    </div>
    <div id="circularG_7" class="circularG">
    </div>
    <div id="circularG_8" class="circularG">
    </div>
  </div>
</div>
<!-- end loader -->
<!--[if lt IE 9]>
<script src="assets/plugins/respond.js"></script>
<![endif]-->
<script src="{{ asset('assets/plugins/jquery-1.8.3.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-ui/jquery-ui-1.10.1.custom.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/breakpoints.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-unveil/jquery.unveil.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-block-ui/jqueryblockui.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-lazyload/jquery.lazyload.min.js') }}" type="text/javascript"></script>
<!-- END CORE JS FRAMEWORK -->
<!-- BEGIN PAGE LEVEL JS -->
<script src="{{ asset('assets/plugins/jquery-slider/jquery.sidr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/webarchScroll.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-numberAnimate/jquery.animateNumbers.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-sparkline/jquery-sparkline.js') }}"></script>
<script src="{{ asset('assets/plugins/skycons/skycons.js') }}"></script>
<script src="{{ asset('assets/plugins/owl-carousel/owl.carousel.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-polymaps/polymaps.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/bootstrap-select2/select2.min.js') }}" type="text/javascript"></script>
<!-- <script src="{{ asset('assets/plugins/jquery-datatable/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script> -->
<!-- <script src="{{ asset('assets/plugins/jquery-datatable/js/jquery.dataTables.min.js') }}" type="text/javascript" ></script> -->
{{-- <script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript" ></script> --}}
<script src="{{ asset('assets/js/datatablesnew.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-datatable/extra/js/TableTools.min.js') }}" type="text/javascript" ></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/datatables.responsive.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/plugins/datatables-responsive/js/lodash.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-flot/jquery.flot.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/plugins/jquery-flot/jquery.flot.resize.min.js') }}" type="text/javascript"></script>
<!-- <script src="assets/plugins/jquery-metrojs/MetroJs.min.js" type="text/javascript" ></script> -->
<!-- END PAGE LEVEL PLUGINS -->
<!--<script src="{{ asset('assets/js/app.js') }}"></script>-->
<!-- BEGIN CORE TEMPLATE JS -->
<!--<script src="{{ asset('assets/js/custom.js') }}" type="text/javascript"></script>-->
<!-- CK-EDITOR JS PLUGIN -->
<script src="{{ asset('assets/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
<!-- CK-EDITOR JS PLUGIN -->
<!-- Datepicker js -->
<script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript" ></script>
<!--App Custom JS-->
<script src="{{ asset('build/js/my_custom.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/core.js') }}" type="text/javascript"></script>
<!--App Custom JS-->
<!-- END CORE TEMPLATE JS -->
<script type="text/javascript">
  $('body').tooltip({
    selector: '[rel=tooltip]'
  });
  /**
   * This monitors all AJAX calls that have an error response. If a user's
   * session has expired, then the system will return a 401 status,
   * "Unauthorized", which will trigger this listener and so prompt
   * the user to be redirected to the login page.
   */
  $(document).ajaxError(function(event, jqxhr, settings, exception) {
    if (exception == 'Unauthorized') {
      // Prompt user if they'd like to be redirected to the login page
      var redirect = alert("Your session has expired. You will be redirected to the login page.");
      var url = '<?php echo url("login"); ?>';
      window.location = url;
    }
  });
</script>
</body>
</html>
