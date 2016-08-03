<div class="content">
  <form class="form-no-horizontal-spacing" id="placement_add_form_modal" data-controller="placement" data-type="normal" data-method="create" data-modal="yes" method="post">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>Add<span class="semi-bold">&nbsp;Placement</span></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true" data-target="#add_new_placement">×</button>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                @include('shared.notifications')
                <div class="row form-row">
                  <!-- Project -->
                  <div class="col-md-6">
                    <label for="placement_project_select">Select Project</label>
                    <select name="project" id="placement_project_select" class="select2 form-control"  >
                      <option value="">Select Below</option>
                      @if(isset($projects))
                        @foreach ($projects as $project)
                          <option value="{{ $project->id }}"
                          @if(old('project') == $project->id)
                            selected="selected"
                          @endif
                          >{{ $project->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <span class="error">
                      <label for="project" class="error">{{ $errors->first('project') }}</label>
                    </span>
                  </div><!--Project-->
                  <!-- Channel -->
                  <div class="col-md-6">
                    <label for="placement_channel_select">Select Channel</label>
                    <select name="channel" id="placement_channel_select" class="select2 form-control">
                      @include('placements.select_channel_partial')
                    </select>
                    <span class="error">
                      <label for="channel" class="error">{{ $errors->first('channel') }}</label>
                    </span>
                  </div><!--Project-->
                </div>
                <div class="row form-row">
                  <!-- Placement Title -->
                  <div class="col-md-6">
                    <label for="">Placement Title</label>
                    <input name="title" id="title" type="text"  class="form-control" placeholder="Title" value="{{ old('title') }}">
                    <span class="error">
                      <label for="title" class="error">{{ $errors->first('title') }}</label>
                    </span>
                  </div><!--Placement Title-->
                  <!-- Start Date -->
                  <div class="col-md-6">
                    <label for="start_date">Start Date</label>
                    <input name="start_date" id="start_date" type="text" class="form-control date" placeholder="Start Date" value="{{old('start_date')}}">
                    <span class="error">
                      <label for="start_date" class="error">{{ $errors->first('start_date') }}</label>
                    </span>
                  </div><!--Start Date-->
                </div>
                <div class="row form-row">
                  <!-- End Date -->
                  <div class="col-md-6">
                    <label for="end_date">End Date</label>
                    <input name="end_date" id="end_date" type="text" class="form-control date" placeholder="End Date" value="{{ old('end_date') }}">
                    <span class="error">
                      <label for="end_date" class="error">{{ $errors->first('end_date') }}</label>
                    </span>
                  </div><!-- End Date -->
                </div>
              </div>
            </div>
            <div class="form-actions">
              <input type="hidden" name="is_modal" value="yes">
              <div class="">
                <button type="submit" class="btn btn-primary btn-cons">ADD</button>
                <button type="button" class="btn btn-primary btn-cons" data-dismiss="modal">CANCEL</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>
<?php //echo "<pre>"; print_r(session()->all()); ?>
<script type="text/javascript">
  (function(){
    project_id = $('#placement_project_select').val();
    old_channel_id = '{{ old('channel') or '' }}';
    edit_details_channel = '{{ $placement_details[0]->channels->id or '' }}';
    if(project_id != '' && old_channel_id != ''){
      url_val = 'placement/old-channel/'+project_id+'/{{ old("channel") }}';
    }else{
      url_val = 'placement/channel-by-project/'+project_id;
    }
    if(edit_details_channel != ''){
      url_val = 'placement/old-channel/'+project_id+'/'+edit_details_channel;
    }
    if(project_id != ''){
      $.ajax({
        url: url_val,
        type: "GET",
        success: function(data){
          if(typeof(data.exc) !== 'undefined'){
            alert('Whoops! Your request can not be performed at this time. Please try again later.');
          }
          else{
            $('#placement_channel_select').html(data);
          }
          return false;
        }
      });
    }else{
      // debugger;
      $('#placement_channel_select').html('<option value="">Select Below</option>');
      return false;
    }
  })();
</script>
