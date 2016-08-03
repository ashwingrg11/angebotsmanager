<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @yield('page-title')
  <!-- <title>Microsite1 EN</title> -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/boostrapv3/css/bootstrap.min.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('assets/microsite/css/style.css') }}" type="text/css"> -->
  <!-- open sans font-->
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
  <!-- open sans ends -->
  @yield('link-css')
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favico.png') }}">
</head>
<body>
  @yield('page-content')
  <!--scripts-->
  <script src="{{ asset('assets/plugins/jquery-1.8.3.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
  <script>
    $('textarea[name="limitedtextarea"], textarea[name="future_collaboration_text"], textarea[name="collective_feedback_text"], textarea[name="overall_success_text"]').on("keyup, keydown", function(e){
      $this = $(this);
      var limitNum = 1000;
      var limitCount = $this.next('label').find('span.remain');
      var limitField = $this;
      var t = $(this).val();
      $this.closest('span.comment').removeClass('red-text');
      if (limitField.val().length > limitNum) {
        this.value = this.value.substring(0, limitNum);
      }
      else {
        limitCount.html(limitNum - limitField.val().length);
      }
      if(limitCount.text() == '0'){
        limitCount.parent('span.comment').addClass('red-text');
      }
    });
    //min height on the maincontent
    //calculate screen height
    var height = $(window).height();
    //calculate the height of the header
    var height_header = $('header').height();
    //subtract the height og screen from the height of header
    var min_height = height - height_header - 80 + 5;
    console.log(min_height);
    //assign to the maincontent as min-height
    $('.maincontent').css('min-height', min_height);
  </script>
</body>
</html>