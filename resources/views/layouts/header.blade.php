<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title>JI Offer Tool || {{ucfirst(Auth::user()->user_type)}} User Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description"/>
<meta content="" name="author" />
<meta name="_token" content="{!! csrf_token() !!}"/>
<link rel="stylesheet" href="{{ asset('build/css/header_everything.css') }}">
<link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/css/custom-icon-set.css') }}" rel="stylesheet" type="text/css"/>
<link href="{{ asset('assets/plugins/jquery-datatable/css/jquery.dataTables.css') }}" rel="stylesheet" type="text/css"/>
<!--CUSTOM CSS-->
<link rel="stylesheet" href="{{ asset('build/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-datepicker/css/datepicker.css') }}">
<!--END CUSTOM CSS-->
<!-- favicon icon -->
<link rel="icon" type="image/png" href="{{ asset('assets/img/favico.png') }}">
<!-- favicon icon ends here -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">
    <div class="header-seperation">
      <span><img src="{{ asset('assets/img/jilogonewnew.png') }}" alt=""></span>
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
        <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" >
          <div class="iconset top-menu-toggle-white"></div>
          </a> </li>
      </ul>
      <ul class="nav pull-right notifcation-center">
        <li class="dropdown" id="header_task_bar"> <a href="" class="dropdown-toggle active" data-toggle="">
          <div class="iconset top-home"></div>
          </a>
        </li>
      </ul>
    </div>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <div class="header-quick-nav" >
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="pull-left">
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
            <div class="iconset top-menu-toggle-dark"></div>
            </a>
          </li>
        </ul>
        <ul class="nav quick-section" style="margin-left:0px;">
          <li class="m-r-10 input-prepend inside search-form no-boarder"> <span class="add-on"> <span class="iconset top-search"></span></span>
            <input name="entire_search" type="text" class="no-boarder" id="entire_search" placeholder="Search here..." style="width:80%;">
          </li>
        </ul>
      </div>
      <!-- END TOP NAVIGATION MENU -->
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right">
        <ul class="nav quick-section">
          <li class="quicklinks"> <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
            <div class="iconset top-settings-dark "></div>
            </a>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="user-options">
              <li><a href="javascript:;" class="main_module_link main_link user_profile" data-controller="user" data-mode="profile"><i class="fa fa-user"></i>&nbsp;&nbsp;My Account</a></li>
              <li class="divider"></li>
              <li><a href="{{ url('logout') }}"><i class="fa fa-power-off"></i>&nbsp;&nbsp;Log Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
      <!-- END CHAT TOGGLER -->
    </div>
    <!-- END TOP NAVIGATION MENU -->
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
