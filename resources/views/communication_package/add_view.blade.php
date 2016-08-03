<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="communication-package" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Communication Package Send Dates</p>
    </li>
    <li>
      <a href="#" class="active">Modify</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="communication_add_form" data-controller="communication-package" data-type="normal" data-method="{{ isset($communication_details) ? 'update' : 'create'}}" method="post">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              Modify
              <span class="semi-bold">Communication Package Send Dates</span>
            </h4>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                <div class="row form-row">
                  <!-- Project -->
                  <div class="col-md-6">
                    <label for="communication_project_select">Select Project</label>
                    <select name="project_id" id="communication_project_select" class="select2 form-control"  >
                      <option value="">Select Below</option>
                      @if(isset($projects))
                        @foreach ($projects as $project)
                          <option value="{{ $project->id }}"
                          @if(isset($communication_details))
                            @if($communication_details[0]->project->id == $project->id)
                              selected="selected"
                            @endif
                          @else
                            @if(old('project_id') == $project->id)
                              selected="selected"
                            @endif
                          @endif
                          >{{ $project->name }}</option>
                        @endforeach
                      @endif
                    </select>
                    <span class="error">
                      <label for="communication_project_select" class="error">{{ $errors->first('project_id') }}</label>
                    </span>
                  </div><!--Project-->
                </div>
                <div class="row form-row">
                  <!-- Offer Activation Email -->
                  <div class="col-md-6">
                    <label for="activation">Prior Duration(days) - Activation Email</label>
                    <input name="activation" id="activation" type="text"  class="form-control" placeholder="Enter number of days" value="{{ $communication_details[0]->activation or old('activation') }}">
                    <span class="error">
                      <label for="activation" class="error">{{ $errors->first('activation') }}</label>
                    </span>
                  </div><!--Offer Activation Email-->
                  <!-- Microsite1 Monthly Email -->
                  <div class="col-md-6">
                    <label for="microsite1_monthly">After Duration(days) - Microsite1 Monthly Email</label>
                    <input name="microsite1_monthly" id="microsite1_monthly" type="text"  class="form-control" placeholder="Enter number of days" value="{{ $communication_details[0]->microsite1_monthly or old('microsite1_monthly') }}">
                    <span class="error">
                      <label for="microsite1_monthly" class="error">{{ $errors->first('microsite1_monthly') }}</label>
                    </span>
                  </div><!--Microsite1 Monthly Email-->
                </div>
                <div class="row form-row">
                  <!-- Microsite2 Final Email -->
                  <div class="col-md-6">
                    <label for="microsite2_final">After Duration(days) - Microsite2 Final Email</label>
                    <input name="microsite2_final" id="microsite2_final" type="text"  class="form-control" placeholder="Enter number of days" value="{{ $communication_details[0]->microsite2_final or old('microsite2_final') }}">
                    <span class="error">
                      <label for="microsite2_final" class="error">{{ $errors->first('microsite2_final') }}</label>
                    </span>
                  </div><!--Microsite2 Final Email-->
                  <!-- Microsite2 Reminder Email -->
                  <div class="col-md-6">
                    <label for="microsite2_reminder">After Duration(days) - Microsite2 Reminder Email</label>
                    <input name="microsite2_reminder" id="microsite2_reminder" type="text"  class="form-control" placeholder="Enter number of days" value="{{ $communication_details[0]->microsite2_reminder or old('microsite2_reminder') }}">
                    <span class="error">
                      <label for="microsite2_reminder" class="error">{{ $errors->first('microsite2_reminder') }}</label>
                    </span>
                  </div><!--Microsite2 Reminder Email-->
                </div>
              </div>
            </div>
            @if(isset($communication_details))
            <input type="hidden" name="id" value="{{ $communication_details[0]->id }}">
            <input type="hidden" name="old_project_id" value="{{ $communication_details[0]->project_id }}">
            @endif
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" type="submit">{{ isset($communication_details)?'UPDATE':'ADD' }}</button>
                <button class="btn btn-danger btn-cons body-link" type="button" data-controller="communication-package" data-mode="index">CANCEL</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>
