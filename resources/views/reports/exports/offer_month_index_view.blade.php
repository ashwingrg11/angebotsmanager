<?php
  if(!empty(session('errors')) || !($errors->isEmpty())){
    session(['placement_ids' => old('placement_ids')]);
  }else{
    session()->forget(['placement_ids']);
  }
?>
<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Reporting</p>
    </li>
    <li><a href="#" class="active">Offer Reporting</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>Offer Reporting Data Export</h4>
          <a href="{{url('report/download-offer-month-report')}}" style="display:none" target="_blank" id="offer-month-download">Download</a>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <!--Call center data export form wrapper-->
          <div class="channelForm" id="channelForm">
            <!-- export form -->
            <form class="form-no-horizontal-spacing" id="call_center_export_form" data-controller="report" data-type="normal" data-method="offer-month-report">
              <!--form first-row starts-->
              <div class="row form-row">
                <!-- Project -->
                <div class="col-md-4">
                  <label for="call_center_project">Select Project</label>
                  <select name="project" id="call_center_project" class="select2 form-control">
                    <option value="">Select Below</option>
                    @foreach ($projects as $project)
                      <option value="{{ $project->id }}"
                      @if(old('project') == $project->id)
                        selected="selected"
                      @endif
                      >{{ $project->name }}</option>
                    @endforeach
                  </select>
                  <span class="error">
                    <label for="project" class="error">
                      {{ $errors->first('project') }}
                    </label>
                  </span>
                </div><!-- Project ends here -->
                <!--placements-->
                <div class="col-md-4">
                  <label for="call_center_placement">Select Placements</label>
                  <select name="placement_ids[]" id="call_center_placement" class="select2 form-control" multiple="multiple">
                    @include('reports.exports.offer_month_project_placements')
                  </select>
                </div>
              </div><!--form first-row ends-->
              <!--form second-row starts-->
              <div class="row form-row">
                <!-- Range Date-from -->
                <div class="col-md-4">
                  <label for="date_from">Date From</label>
                  <input type="text" class="form-control month_year_date" placeholder="Date From" name="date_from" id="date_from" value="{{ old('date_from') }}">
                  <span class="error">
                    <label for="date_from" class="error">
                      {{ $errors->first('date_from') }}
                    </label>
                  </span>
                </div><!--date-range from ends-->
                <!-- Range Date-until -->
                <div class="col-md-4">
                  <label for="date_until">Date Until</label>
                  <input type="text" class="form-control month_year_date" placeholder="Date Until" name="date_until" id="date_until" value="{{ old('date_until') }}">
                  <span class="error">
                    <label for="date_until" class="error">
                      {{ $errors->first('date_until') }}
                    </label>
                  </span>
                </div><!--date-range until ends-->
              </div><!--form second-row ends here-->
              <!--form row-->
              <div class="row form-row">
                <!--form actions-->
                <div class="col-md-4">
                  <label for="name" style="visibility: hidden">export</label>
                  <button class="btn btn-danger btn-cons" name="country_submit" type="submit"><i class="fa fa-download"></i>&nbsp;&nbsp;Export</button>
                </div><!--form actions end-->
              </div><!--form row ends here-->
            </form><!--export form ends--><br>
          </div><!--call center data export form wrapper-->
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  (function(){
    project_id = $('#call_center_project').val();
    if(project_id != ''){
      $.ajax({
        url: 'placement/placements-call-center/'+project_id,
        type: "GET",
        success: function(data){
          if(typeof(data.exc) !== 'undefined'){
            alert('Whoops! Your request can not be performed at this time. Please try again later.');
          }
          else{
            $("#call_center_placement").html(data);
          }
          return false;
        }
      });
    }else{
      na_option = '<option value="" disabled="disabled">N/A</option>';
      $('select#call_center_placement').html(na_option);
    }
  })();
</script>
