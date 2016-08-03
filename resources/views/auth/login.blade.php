<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  <meta charset="utf-8" />
  <title>JI Offer Tool | Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta content="" name="description" />
  <meta content="" name="author" />
  <!-- BEGIN CORE CSS FRAMEWORK -->
  <link href="{{ asset('assets/plugins/pace/pace-theme-flash.css') }}" rel="stylesheet" type="text/css" media="screen"/>
  <link href="{{ asset('assets/plugins/boostrapv3/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/plugins/boostrapv3/css/bootstrap-theme.min.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/css/animate.min.css') }}" rel="stylesheet" type="text/css"/>
  <!-- favicon icon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favico.png') }}">
  <!-- favicon icon ends here -->
  <!-- END CORE CSS FRAMEWORK -->
  <!-- BEGIN CSS TEMPLATE -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
  <!-- <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css"/>
  -->
  <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet" type="text/css"/>
  <link href="{{ asset('assets/css/custom-icon-set.css') }}" rel="stylesheet" type="text/css"/>
  <!-- END CSS TEMPLATE -->
</head>
  <!-- END HEAD -->
  <!-- BEGIN BODY -->
<body class="error-body no-top">
  <div class="container">
    <div class="row login-container column-seperation">
      <div class="col-md-5 col-md-offset-1">
        <h2 style="color:#123f6f;">Sign in to JI Offer Tool</h2>
        <a href="javascript:void()" style="margin-left:30px">
          <img src="{{ asset('assets/images/logo2_new.png') }}" alt=""></a>
        <br></div>
      <div class="col-md-5 ">
        <br>
        <!--login error message-->
        @if(session('loginError'))
          <div class="error">There was an error with your E-Mail/Password combination.<br> Please try again.</div>
        @endif
        <!--login error message-->
        <!--logout message-->
        @if(session('logout_msg'))
          <div class="logout-notification" style="color: #2a475d;"><label>You have just successfully logged out.</label></div>
        @endif
        <!--logout message-->
        <!--login form starts-->
        <form id="login-form" class="login-form" action="" method="post">
          {{ csrf_field() }}
          <!--email input-->
          <div class="row">
            <div class="form-group col-md-10">
              <label class="form-label" style="color:#2a475d;">Email</label>
              <div class="controls">
                <div class="input-with-icon right"> <i class=""></i>
                  <input type="text" name="email" id="email" class="form-control" value="{{old('email')}}">
                </div>
                <span class="error">{{ $errors->first('email') }}</span>
              </div>
            </div>
          </div><!--email input ends here-->
          <!--password field-->
          <div class="row">
            <div class="form-group col-md-10">
              <label class="form-label" style="color:#2a475d;">Password</label>
              <span class="help"></span>
              <div class="controls">
                <div class="input-with-icon  right"> <i class=""></i>
                  <input type="password" name="password" id="password" class="form-control">
                </div>
                <span class="error">{{ $errors->first('password') }}</span>
              </div>
            </div>
          </div><!--password field ends here-->
          <!--form actions-->
          <div class="row">
            <div class="col-md-10" style="padding-right:10px;">
              <button class="btn btn-primary btn-cons pull-right btn2" type="submit">Login</button>
            </div>
          </div><!--form actions-->
        </form><!--login form ends here-->
      </div>
    </div>
  </div>
  <!-- END CONTAINER -->
  <!-- BEGIN CORE JS FRAMEWORK-->
  <script src="{{ asset('assets/plugins/jquery-1.8.3.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/pace/pace.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/login.js') }}" type="text/javascript"></script>
  <!-- BEGIN CORE TEMPLATE JS -->
  <!-- END CORE TEMPLATE JS -->
  <script>
    setTimeout(fade_out, 2000);
    function fade_out() {
      $("div.logout-notification").fadeOut();
    }
  </script>
</body>
</html>