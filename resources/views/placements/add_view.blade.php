<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="placement" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Placements</p>
    </li>
    <li>
      <a href="#" class="active">{{ isset($placement_details) ? 'Edit' : 'Add' }}</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="placement_add_form" data-controller="placement" data-type="normal" data-method="{{ isset($placement_details) ? 'update' : 'create'}}" method="post">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              {{ isset($placement_details) ? 'Edit' : 'Add' }}
              <span class="semi-bold">Placement</span>
            </h4>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                <div class="row form-row">
                  <!-- Project -->
                  <div class="col-md-6">
                    <label for="placement_project_select">Select Project</label>
                    <select name="project" id="placement_project_select" class="select2 form-control"  >
                      <option value="">Select Below</option>
                      @if(isset($projects))
                        @foreach ($projects as $project)
                          <option value="{{ $project->id }}"
                          @if(isset($placement_details))
                            @if($placement_details[0]->projects->id == $project->id)
                              selected="selected"
                            @endif
                          @else
                            @if(old('project') == $project->id)
                              selected="selected"
                            @endif
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
                      {{-- <option value="">Select Below</option> --}}
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
                    <input name="title" id="title" type="text"  class="form-control" placeholder="Title" value="{{ $placement_details[0]->title or old('title') }}">
                    <span class="error">
                      <label for="title" class="error">{{ $errors->first('title') }}</label>
                    </span>
                  </div><!--Placement Title-->
                  <!-- Start Date -->
                  <div class="col-md-6">
                    <label for="start_date">Start Date</label>
                    <input name="start_date" id="start_date" type="text"  class="form-control date" placeholder="Start Date" value="@if(isset($placement_details)){{Carbon\Carbon::createFromFormat('d/m/Y',$placement_details[0]->start_date)->format('Y-m-d')}}@else{{old('start_date')}}@endif">
                    <span class="error">
                      <label for="start_date" class="error">{{ $errors->first('start_date') }}</label>
                    </span>
                  </div><!--Start Date-->
                </div>
                @if(isset($placement_details))
                  {{-- @if($placement_details[0]->offers->isEmpty()) --}}
                    <div class="row form-row">
                      <!-- End Date -->
                      <div class="col-md-6">
                        <label for="end_date">End Date</label>
                        <input name="end_date" id="end_date" type="text" class="form-control date" placeholder="End Date"
                        @if(isset($placement_details))
                          @if($placement_details[0]->end_date)
                            value="{{Carbon\Carbon::createFromFormat('d/m/Y', $placement_details[0]->end_date)->format('Y-m-d')}}"
                          @endif
                        @else
                          value="{{old('end_date')}}"
                        @endif
                        <span class="error">
                          <label for="end_date" class="error">{{ $errors->first('end_date') }}</label>
                        </span>
                      </div><!-- End Date -->
                    </div>
                  {{-- @endif --}}
                @else
                  <div class="row form-row">
                    <!-- End Date -->
                    <div class="col-md-6">
                      <label for="end_date">End Date</label>
                      <input name="end_date" id="end_date" type="text" class="form-control date" placeholder="End Date" value="{{ $placement_details[0]->end_date or old('end_date') }}">
                      <span class="error">
                        <label for="end_date" class="error">{{ $errors->first('end_date') }}</label>
                      </span>
                    </div><!-- End Date -->
                  </div>
                @endif
              </div>
            </div>
            @if(isset($placement_details))
            <input type="hidden" name="id" value="{{ $placement_details[0]->id }}">
            @endif
            <div class="form-actions">
              <div class="">
                @if(isset($placement_details))
                  <input type="hidden" name="placement_id" value="{{ $placement_details[0]->id }}">
                @endif
                <button class="btn btn-danger btn-cons" name="addplacement_submit" type="submit">{{ isset($placement_details)?'UPDATE':'ADD' }}</button>
                <button class="btn btn-danger btn-cons body-link" type="button" data-controller="placement" data-mode="index">CANCEL</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>
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
