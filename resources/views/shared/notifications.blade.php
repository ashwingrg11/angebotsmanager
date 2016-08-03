@if(session('added_notice'))
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <button class="close" data-dismiss="alert"></button>
    Success: {{ session('added_notice') }}
  </div>
@endif
@if(session('updated_notice'))
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <button class="close" data-dismiss="alert"></button>
    Success: {{ session('updated_notice') }}
  </div>
@endif
@if(session('deleted_notice'))
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <button class="close" data-dismiss="alert"></button>
    Success: {{ session('deleted_notice') }}
  </div>
@endif
@if(isset($deleted_notice))
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <button class="close" data-dismiss="alert"></button>
    Success: {{ $deleted_notice }}
  </div>
@endif
@if(isset($common_notice))
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <button class="close" data-dismiss="alert"></button>
    Success: {{ $common_notice }}
  </div>
@endif
@if(session('error_notice'))
  <div class="alert alert-success">
    <i class="fa fa-check"></i>
    <button class="close" data-dismiss="alert"></button>
    Error: {{ session('error_notice') }}
  </div>
@endif
