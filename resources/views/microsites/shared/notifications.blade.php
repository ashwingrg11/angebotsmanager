@if(session('exc'))
  <div class="row">
    <div class="alert alert-danger alert-dismissable" style="margin-top:50px;">
      <i class="fa fa-ban"></i>
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      {{ session('exc') }}
    </div>
  </div>
  @endif
  @if(session('updated'))
  <div class="row">
    <div class="alert alert-success alert-dismissable"  style="margin-top:50px;">
      <i class="fa fa-check"></i>
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      {{ session('updated') }}
    </div>
  </div>
@endif