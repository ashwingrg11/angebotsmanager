<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>User Profile</p>
    </li>
    <li>
      <a href="#" class="active">Summary</a>
    </li>
  </ul>
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple add_offer_simple_grid">
          <div class="grid-title">
            <h4>
              User Profile
              <span class="semi-bold">Details Summary</span>
            </h4>
          </div>
        </div>
      </div>
      <!--second-->
      <div class="col-md-12">
        <div class="row-fluid add_offer_fluid">
          <div class="span12">
            <div role="tabpanel">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs add_offer_tabs" role="tablist">
                <li role="presentation" class="active" id="tab_one_li"><a href="#show_profile" aria-controls="show_profile" role="tab" data-toggle="tab"><i class="fa fa-user fa-secondary"></i> &nbsp;My Profile</a></li>
                <li role="presentation" class="" id="tab_two_li"><a href="#edit_profile" aria-controls="edit_profile" role="tab" data-toggle="tab"><i class="fa fa-pencil fa-secondary"></i> &nbsp;Edit Profile</a></li>
                <li role="presentation" class="" id="tab_three_li"><a href="#reset_password" aria-controls="reset_password" role="tab" data-toggle="tab"><i class="fa fa-repeat fa-secondary"></i> &nbsp;Reset Password</a></li>
              </ul><!--nav tabs-->
              <!-- Tab panes -->
              <div class="tab-content" style="padding-bottom:0; margin-bottom: 0px;">
                <!--  User profile details -->
                <div role="tabpanel" class="tab-pane active" id="show_profile">
                  <h3>John Doe</h3>
                  <p><strong>First Name:</strong> {{ $user_info[0]->first_name }}</p>
                  <p><strong>Last Name:</strong> {{ $user_info[0]->last_name }}</p>
                  <p><strong>User Type:</strong> {{ $user_info[0]->user_type }}</p>
                  <p><strong>Email:</strong> {{ $user_info[0]->email }}</p>
                  <p><strong>Telephone:</strong> {{ $user_info[0]->telephone or 'N/A' }}</p>
                  <p><strong>Username:</strong> {{ $user_info[0]->username }}</p>
                  <!--projects-->
                  <div class="content-wrapper">
                    <div class="left-text"><p><strong>Projects:&nbsp;</strong></p>
                    </div>
                    <div class="right-text">
                      <p>
                        @if($user_info[0]->projects->isEmpty())
                          N/A
                        @else
                          <?php $sn = 1; ?>
                          @foreach($user_info[0]->projects as $project)
                            <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{$sn.') '}}{{$project->name}}</a><br>
                            <?php $sn++; ?>
                          @endforeach
                        @endif
                      </p>
                    </div>
                  </div><!--projects-->
                </div><!--  user profile details -->
                <!--  user profile edit -->
                <div role="tabpanel" class="tab-pane" id="edit_profile">
                  <form class="form-no-horizontal-spacing" id="profile_edit_form" data-controller="user" data-type="normal" data-method="edit-profile" method="post">
                    @if(session('updated_notice'))
                      <div class="alert alert-success">
                        <i class="fa fa-check"></i>
                        <button class="close" data-dismiss="alert"></button>
                        Success: {{ session('updated_notice') }}
                      </div>
                    @endif
                    <h3>Edit your profile</h3>
                    <div class="row form-row">
                      <div class="col-md-12">
                        <div class="row form-row">
                          <!-- First Name -->
                          <div class="col-md-6">
                            <label for="first_name">First Name</label>
                            <input name="first_name" id="first_name" type="text"  class="form-control" placeholder="First Name" value="{{ $user_info[0]->first_name }}">
                            <span class="error">
                              <label for="first_name" class="error">{{ $errors->first('first_name') }}</label>
                            </span>
                          </div><!--First Name ends-->
                          <!--Last Name-->
                          <div class="col-md-6">
                            <label for="last_name">Last Name</label>
                            <input name="last_name" id="last_name" type="text"  class="form-control" placeholder="Last Name" value="{{ $user_info[0]->last_name }}">
                            <span class="error">
                              <label for="last_name" class="error"></label>
                              {{ $errors->first('last_name') }}
                            </span>
                          </div><!--Last Name ends here-->
                        </div>
                        <div class="row form-row">
                          <!-- Email -->
                          <div class="col-md-6">
                            <label for="email">Email</label>
                            <input name="email" id="email" type="text"  class="form-control" placeholder="Email" value="{{ $user_info[0]->email }}">
                            <span class="error">
                              <label for="email" class="error">{{ $errors->first('email') }}</label>
                            </span>
                          </div><!--Email ends here-->
                          <!--Telephone-->
                          <div class="col-md-6">
                            <label for="telephone">Telephone</label>
                            <input name="telephone" id="telephone" type="text"  class="form-control" placeholder="Telephone" value="{{ $user_info[0]->telephone }}">
                            <span class="error">
                              <label for="telephone" class="error"></label>
                              {{ $errors->first('telephone') }}
                            </span>
                          </div><!--Telephone ends-->
                        </div>
                        <div class="row form-row">
                          @if(Auth::user()->user_type == "admin")
                          <!-- User Type -->
                          <div class="col-md-6">
                            <label for="user_type">User Type</label>
                            <select name="user_type" class="form-control select2">
                              <option value="">Select Below</option>
                              <option value="admin"
                              @if($user_info[0]->user_type == 'admin')
                                selected="selected"
                              @endif
                              >Admin</option>
                              <option value="general"
                              @if($user_info[0]->user_type == 'general')
                                selected="selected"
                              @endif
                              >General User</option>
                              <option value="viewer"
                              @if($user_info[0]->user_type == 'viewer')
                                selected="selected"
                              @endif
                              >Viewer</option>
                            </select>
                            <span class="error">
                              <label for="user_type" class="error">{{ $errors->first('user_type') }}</label>
                            </span>
                          </div><!--User Type ends here-->
                          @endif
                          <!--Username-->
                          <div class="col-md-6">
                            <label for="username">Username</label>
                            <input name="username" id="username" type="text"  class="form-control" placeholder="Username" value="{{ $user_info[0]->username }}">
                            <span class="error">
                              <label for="username" class="error"></label>
                              {{ $errors->first('username') }}
                            </span>
                          </div><!--Username ends-->
                        </div>
                      </div><!--.col-md-12-->
                    </div>
                    <!--form actions-->
                    <div class="form-actions" style="margin-bottom:0; padding: 0px; background: white;">
                      <input type="hidden" name="id" value="{{$user_info[0]->id}}">
                      <button class="btn btn-danger btn-cons" type="submit">SUBMIT</button>
                      <button class="btn btn-danger btn-cons body-link" type="button" data-controller="user" data-mode="profile">CANCEL</button>
                    </div><!--form actions-->
                  </form>
                </div><!--  user profile edit -->
                <!--  reset password -->
                <div role="tabpanel" class="tab-pane" id="reset_password">
                  <form class="form-no-horizontal-spacing" id="password_reset_from" data-controller="user" data-type="normal" data-method="reset-password" method="post">
                    @if(session('password_reset_success'))
                      <div class="alert alert-success">
                        <i class="fa fa-check"></i>
                        <button class="close" data-dismiss="alert"></button>
                        Success: {{ session('password_reset_success') }}
                      </div>
                    @endif
                    <h3>Reset your password</h3>
                    <div class="row form-row">
                      <div class="col-md-12">
                        <div class="row form-row">
                          <!-- Current Password -->
                          <div class="col-md-6">
                            <label for="current_password">Current Password</label>
                            <input name="current_password" id="current_password" type="password" class="form-control" placeholder="**********" value="{{ old('current_password') }}">
                            <span class="error">
                              <label for="current_password" class="error">{{ $errors->first('current_password') }}</label>
                            </span>
                          </div><!--Current Password ends-->
                        </div>
                        <div class="row form-row">
                          <!--New Password-->
                          <div class="col-md-6">
                            <label for="new_password">New Password</label>
                            <input name="new_password" id="new_password" type="password" class="form-control" placeholder="**********" value="{{ old('new_password') }}">
                            <span class="error">
                              <label for="new_password" class="error">{{ $errors->first('new_password') }}</label>
                            </span>
                          </div><!--New Password ends here-->
                          <!--Confirm Password-->
                          <div class="col-md-6">
                            <label for="new_password_confirmation">Confirm Password</label>
                            <input name="new_password_confirmation" id="new_password_confirmation" type="password"  class="form-control" placeholder="**********" value="{{ old('new_password_confirmation') }}">
                            <span class="error">
                              <label for="new_password_confirmation" class="error">{{ $errors->first('new_password_confirmation') }}</label>
                            </span>
                          </div><!--Confirm Password ends here-->
                        </div>
                        <!--form actions-->
                        <div class="form-actions" style="margin-bottom:0; padding: 0px; background: white;">
                          <button class="btn btn-danger btn-cons" type="submit">SUBMIT</button>
                          <button class="btn btn-danger btn-cons body-link" data-controller="user" data-mode="profile" type="button" >CANCEL</button>
                        </div><!--form actions-->
                      </div>
                    </div>
                  </form>
                </div><!--  reset password -->
              </div>
              <!--tab panes-->
            </div><!-- /.tabpanel -->
          </div><!-- /.span12 -->
        </div>
      </div><!--second-->
    </div>
<script type="text/javascript">
  //set form-tab active on validation error
  <?php if(!empty(session('errors')) || !($errors->isEmpty())){ ?>
  var flag = null;
  var tab_two_inputs = ['first_name', 'last_name', 'email', 'telephone', 'user_type', 'username'];
  var tab_three_inputs = ['current_password', 'new_password', 'new_password_confirmation'];
  $('span.error').each(function(e){
    $this = $(this);
    label = $this.children('label');
    error_text = label.text();
    for_attribute = label.attr('for');
    if($.trim(error_text).length > 0){
      flag = $.inArray(for_attribute, tab_two_inputs);
      if(flag >= 0){
        flag = 2;
        return false;
      }
      else{
        flag = $.inArray(for_attribute, tab_three_inputs);
        if(flag >= 0){
          flag = 3;
          return false;
        }
        else{
          flag = 1;
          return false;
        }
      }
    }
  });
  tabs = $("li[role='presentation']");
  tabs.each(function(){
    if($(this).hasClass('active')){
      $(this).removeClass('active');
    }
  });
  switch(flag) {
    case 1:
      $('#tab_one_li').children('a').click();
      break;
    case 2:
      $('#tab_two_li').children('a').click();
      break;
    case 3:
      $('#tab_three_li').children('a').click();
      break;
    default:
      //code
  }
  <?php } ?>
  <?php if(session('updated_notice')){ ?>
    $('#tab_two_li').children('a').click();
  <?php } ?>
  <?php if(session('password_reset_success')){ ?>
    $('#tab_three_li').children('a').click();
  <?php } ?>
  <?php if(session('user_type_notice')){ ?>
    var reload_page = alert("Your user type has been changed. Page will be reloaded");
    window.location.reload();
  <?php } ?>
</script>



