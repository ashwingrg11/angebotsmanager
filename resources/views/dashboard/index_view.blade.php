@extends('layouts.master')
@section('main-content')
<div class="content">
  <ul class="breadcrumb">
  <li>
    <p>YOU ARE HERE</p>
  </li>
  <li>
    <a href="#" class="active">Dashboard</a>
  </li>
</ul>
<div class="row-fluid">
  <div class="span12">
    <div class="grid simple ">
      <div class="grid-title">
        <h4>
          Dashboard
          <span class="semi-bold">Page</span>
        </h4>
      </div>
      <div class="grid-body ">
        @if(Auth::user()->user_type != "viewer")
          <a href="http://localhost/angebotmanager/guide/" target="_blank" title="user guide">Click to View User Guide</a>
        <!--<a href="http://p277509.mittwaldserver.info/guide/" target="_blank" title="user guide">Click to View User Guide</a>-->
        <!--<a href="http://ji-offermanager.com/guide/" target="_blank" title="user guide">Click to View User Guide</a>-->
        @endif
      </div>
      <!--grid body ends--> </div>
  </div>
</div>
</div>
@stop