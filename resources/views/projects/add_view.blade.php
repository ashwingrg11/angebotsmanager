<?php
  if(!empty(session('errors')) || !($errors->isEmpty())){
    session(['client_contact' => old('client_contact')]);
  }else{
    session()->forget(['client_contact']);
  }
  if(isset($project_details)){
    session(['project_details' => $project_details]);
  }else{
    session()->forget(['project_details']);
  }
 ?>
<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="project" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Projects</p>
    </li>
    <li>
      <a href="#" class="active">{{ isset($project_details) ? 'Edit' : 'Add' }}</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="project_add_form" data-controller="project" data-method="{{ isset($project_details) ? 'update' : 'create'}}" method="post" enctype="multipart/form-data" data-type="multipart">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>{{ isset($project_details) ? 'Edit' : 'Add' }}<span class="semi-bold">&nbsp;Project</span></h4>
          </div>
          <div class="grid-body">
            <div class="row form-row">
              <!--Project Name-->
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="name">Project Name</label>
                    <input name="name" id="name" type="text" class="form-control" placeholder="Project Name" value="{{ $project_details[0]->name or old('name') }}">
                  <span class="error">
                    <label for="name" class="error">{{ $errors->first('name') }}</label>
                  </span>
                </div>
                <!--Project Name ends-->
                <!-- Project Logo -->
                <div class="form-group">
                  <label for="logo">Select Project Logo</label>
                  <input type="file" name="logo" id="logo" class="form-control file" style="display:none;">
                  <div class="col-md-6 col-sm-6" style="padding:0px;">
                    <input type="text" name="logo_text" id="logo_text" class="form-control file" style="width:98%;" disabled="disabled">
                  </div>
                  <div class="col-md-4 col-sm-4" style="padding:0px;">
                    <a class="btn n-btn " onclick="$('#logo').click();"><i class="fa fa-folder-open">&nbsp;</i>Browse</a>
                  </div>
                  <div class="clearfix"></div>
                  <p>Max height:1024px</p>
                  <p>Max Width:1024px</p>
                  <span class="error">
                    <label for="" class="error">{{ $errors->first('logo') }}</label>
                  </span>
                </div>
                <!-- Project Logo ends-->
              </div>
              <div class="col-sm-4" style="margin-top:2em;">
                <!-- Image Preview -->
                  <label class="form-label" id="add_project_logo_wrapper">
                    <a href="javascript:;" style="text-decoration: underline; display:none;" id="project_delete_logo">Delete Logo</a>
                  </label>
                  <label class="form-label">Image Preview</label>
                  <div id="img_preview" style="background: url('assets/images/image-preview.png');width: 165px;height: 165px;">
                    @if(isset($project_details) && $project_details[0]->logo)
                      <img src="{{ asset('images/projects').'/'.$project_details[0]->logo }}" alt="{{ 'projects/'.$project_details[0]->logo }}" height="165" width="165" id="project_logo_img">
                      <img src="{{ asset('images/projects').'/'.$project_details[0]->logo }}" alt="{{ 'projects/'.$project_details[0]->logo }}" height="165" width="165" id="old_project_logo_img" style="display:none">
                    @endif
                  </div>
                <!-- Image Preview ends -->
              </div>
            </div>
            <div class="row form-row">
              <!-- Client -->
              <div class="col-md-5">
                <label for="">Select Client</label>
                <select name="client" id="client" class="select2 form-control">
                  <option value="">Select Below</option>
                  @foreach ($data['clients'] as $client)
                    <option value="{{ $client->id }}"
                    @if(isset($project_details))
                      @if($project_details[0]->clients->id == $client->id)
                        selected="selected"
                      @endif
                    @else
                      @if(old('client') == $client->id)
                        selected="selected"
                      @endif
                    @endif
                    if
                    >{{ $client->name }}</option>
                  @endforeach
                </select>
                <span class="error">
                  <label for="client" class="error">
                    {{ $errors->first('client') }}
                  </label>
                </span>
              </div>
              <!-- Client -->
            </div>
            <div class="row form-row">
              <!-- Project Description -->
              <div class="col-md-12">
                <label for="description">Project Description</label>
                <textarea class="form-control" placeholder="Project Description" name="description" id="description" rows="5">{{ $project_details[0]->description or old('description') }}</textarea>
                <span class="error">
                  <label for="description" class="error">
                    {{ $errors->first('description') }}
                  </label>
                </span>
              </div>
              <!-- Project Description -->
            </div>
            <div class="row form-row">
              <!-- Client Contact -->
              <div class="col-md-6">
                <label for="">Select Client Contact</label>
                <select name="client_contact[]" id="client_contact" class="form-control" multiple="multiple">
                  {{-- <option value="">N/A</option> --}}
                  {{-- @import('projects.select_client_contact_partial.blade.php') --}}
                  @include('projects.select_client_contact_partial')
                </select>
                <span class="error">
                  <label for="client_contact" class="error">
                    {{ $errors->first('client_contact') }}
                  </label>
                </span>
              </div>
              <!-- Client Contact -->
              <!-- JI Contact -->
              <div class="col-md-6">
                <label for="">Select JI Contact</label>
                <select name="ji_contact[]" id="ji_contact" class="form-control" multiple="multiple">
                  @foreach($ji_users as $user)
                    <option value="{{ $user->id }}"
                      @if(isset($project_details))
                        @foreach ($project_details[0]->users as $ji_user)
                          @if($ji_user->id == $user->id)
                            selected="selected"
                          @endif
                        @endforeach
                      @else
                        @if(old('ji_contact'))
                          @if(in_array($user->id, old('ji_contact')))
                            selected="selected"
                          @endif
                        @endif
                      @endif
                    >{{ $user->first_name.' '.$user->last_name }}</option>
                  @endforeach
                </select>
                <span class="error">
                  <label for="ji_contact" class="error">
                    {{ $errors->first('ji_contact') }}
                  </label>
                </span>
              </div>
              <!-- JI Contact -->
            </div>
            <div class="row form-row">
              <!--Category-->
              <div class="col-md-6">
                <div class="form-group">
                  <label for="category">Select Categories</label>
                  @if($categories->isEmpty())
                    <p>N/A</p>
                  @else
                    <?php $sn = 1; ?>
                    @foreach ($categories as $category)
                      <div class="checkbox check-success checkbox-circle form_checkbox">
                        <input id="c_{{$sn}}" value="{{$category->id}}" type="checkbox" name="category[]"
                        @if(isset($project_details))
                          @foreach($project_details[0]->categories as $project_category)
                            @if($project_category->id == $category->id)
                              checked="checked"
                            @endif
                          @endforeach
                        @else
                          @if(old('category'))
                            @if(in_array($category->id, old('category')))
                              checked="checked"
                            @endif
                          @endif
                        @endif
                        >
                        <label for="c_{{$sn}}">{{$category->label}}</label>
                      </div>
                      <?php $sn++; ?>
                    @endforeach
                    <span class="error">
                      <label for="category" class="error">
                        {{ $errors->first('category') }}
                      </label>
                    </span>
                  @endif
                </div>
              </div>
              <!--Category ends-->
              <!-- Language -->
              <div class="col-md-6">
                <label for="">Language</label>
                <select name="language" id="language" class="form-control select2">
                  <option value="">Select Below</option>
                  @foreach ($languages as $language)
                    <option value="{{$language->id}}"
                    @if(isset($project_details))
                      @if($project_details[0]->language_id == $language->id)
                        selected="selected"
                      @endif
                    @else
                      @if(old('language') == $language->id)
                        selected="selected"
                      @endif
                    @endif
                    >{{$language->label}}</option>
                  @endforeach
                </select>
                <span class="error">
                  <label for="language" class="error">
                    {{ $errors->first('language') }}
                  </label>
                </span>
              </div>
              <!-- Language ends -->
            </div>
            <div class="row form-row">
              <!--First issue launch date-->
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="first_issue_launch_date">First Issue Launch Date</label>
                    <input name="first_issue_launch_date" id="first_issue_launch_date" type="text" class="form-control date" placeholder="First Issue Launch Date" value="{{ $project_details[0]->first_issue_launch_date or old('first_issue_launch_date') }}">
                  <span class="error">
                    <label for="name" class="error">{{ $errors->first('first_issue_launch_date') }}</label>
                  </span>
                </div>
              </div>
              <!--First issue launch date ends-->
              <!--Circulation-->
              <div class="col-sm-6">
                <div class="form-group">
                  <label for="circulation">Circulation</label>
                    <input name="circulation" id="circulation" type="text" class="form-control" placeholder="Circulation" value="{{ $project_details[0]->circulation or old('circulation') }}">
                  <span class="error">
                    <label for="name" class="error">{{ $errors->first('circulation') }}</label>
                  </span>
                </div>
              </div>
              <!--Circulation ends-->
            </div>
            <div class="row form-row">
              <!-- Project Note-->
              <div class="col-md-12">
                <label for="note">Note</label>
                <textarea class="form-control" placeholder="Enter Note" name="note" id="note" rows="3">{{ $project_details[0]->note or old('note') }}</textarea>
                <span class="error">
                  <label for="note" class="error">
                    {{ $errors->first('note') }}
                  </label>
                </span>
              </div>
              <!-- Project Note -->
            </div>
            <div class="form-actions">
              <div class="">
                @if(isset($project_details))
                  <input type="hidden" name="project_id" value="{{ $project_details[0]->id }}">
                @endif
                <button class="btn btn-danger btn-cons" type="submit">{{ isset($project_details)?'UPDATE':'ADD' }}</button>
                <button class="btn btn-danger btn-cons body-link" type="button" data-controller="project" data-mode="index">CANCEL</button>
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
  client_id = $('#client').val();
  if(client_id != ''){
    $.ajax({
      url: 'client/client-contact/'+client_id,
      type: "GET",
      success: function(data){
        if(typeof(data.exc) !== 'undefined'){
          alert('Whoops! Your request can not be performed at this time. Please try again later.');
        }
        else if(data == ''){
          $('#client_contact').html('<option disabled>N/A</option>');
        }
        else{
          $('#client_contact').html(data);
        }
        return false;
      }
    });
  }else{
    $('#client_contact').html('<option disabled>N/A</option>');
    return false;
  }
})();
</script>