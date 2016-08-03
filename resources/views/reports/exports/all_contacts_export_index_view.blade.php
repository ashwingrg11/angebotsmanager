<?php
  if(!empty(session('errors')) || !($errors->isEmpty())){
    session(['project_ids' => old('project_ids'), 'placement_ids' => old('placement_ids')]);
  }else{
    session()->forget(['placement_ids', 'project_ids']);
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
    <li><a href="#" class="active">All Contacts Export</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h4>All Contacts Export</h4>
          <a href="{{url('report/download-all-contacts')}}" style="display:none" target="_blank" id="all_contacts_download">Download</a>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <!--Call center data export form wrapper-->
          <div class="channelForm" id="channelForm">
            <!-- export form -->
            <form class="form-no-horizontal-spacing" id="all_contacts_export_form" data-controller="report" data-type="normal" data-method="all-contacts-export">
              <!--form first-row starts-->
              <div class="row form-row">
                <!--projects-->
                <div class="col-md-6">
                  <label for="all_contact_export_project">Select Project(s)</label>
                  <select name="project_ids[]" id="all_contact_export_project" class="select2 form-control" multiple="multiple">
                    @if(!isset($projects))
                      <option value="" disabled="disabled">N/A</option>
                    @else
                      @if(empty($projects))
                        <option value="" disabled="disabled">N/A</option>
                      @else
                        @foreach ($projects as $project)
                          <option value="{{ $project->id }}"
                          @if(session('project_ids'))
                            @if(in_array($project->id, session('project_ids')))
                              selected="selected"
                            @endif
                          @endif
                          >{{ $project->name }}</option>
                        @endforeach
                        @if(Auth::user()->user_type == "admin")
                          <option value="n_a"
                          @if(session('project_ids'))
                            @if(in_array('n_a', session('project_ids')))
                              selected="selected"
                            @endif
                          @endif
                          >N/A</option>
                        @endif
                      @endif
                    @endif
                  </select>
                  <span class="error">
                    <label for="all_contact_export_project" class="error">
                      {{ $errors->first('project_ids') }}
                    </label>
                  </span>
                </div><!--projects-->
                <!--placements-->
                <div class="col-md-6">
                  <label for="all_contacts_placement">Select Placement(s)</label>
                  <select name="placement_ids[]" id="all_contacts_placement" class="select2 form-control" multiple="multiple">
                    @include('reports.exports.all_contacts_project_placements')
                  </select>
                  <span class="error">
                    <label for="all_contacts_placement" class="error">
                      {{ $errors->first('placement_ids') }}
                    </label>
                  </span>
                </div><!--placements-->
              </div><!--form first-row ends-->
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
    // project_id = $('#call_center_project').val();
    var projects_selected = $('#all_contact_export_project').val();
    // console.log(projects_selected);
    if(projects_selected != null){
      $.ajax({
        url: "report/projects-placements",
        type: "post",
        data: {project_ids: projects_selected},
        success: function(data){
          $('#all_contacts_placement').html(data);
          return false;
        }
      });
    }
    else {
      user_type = '<?php echo Auth::user()->user_type ?>';
      na_option = '<option value="" disabled="disabled">N/A</option>';
      if(user_type == "admin"){
        na_option = '<option value="n_a">N/A</option>';
      }
      $('#all_contacts_placement').html(na_option);
    }
  })();
</script>
