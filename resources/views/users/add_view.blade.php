<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="user_add_back" data-controller="user" data-mode="index" style="cursor: pointer;">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Users</p>
    </li>
    <li>
      <a href="#" class="active">{{ isset($user_details) ? 'Edit' : 'Add' }}</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="user_add_form" data-controller="user" data-type="normal" data-method="{{ isset($user_details) ? 'update' : 'create'}}" method="post">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              {{ isset($user_details) ? 'Edit' : 'Add' }}
              <span class="semi-bold">User</span>
            </h4>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                <div class="row form-row">
                  <!-- First Name -->
                  <div class="col-md-6">
                    <label for="first_name">First Name</label>
                    <input name="first_name" id="first_name" type="text"  class="form-control" placeholder="First Name" value="{{ $user_details[0]->first_name or old('first_name') }}">
                    <span class="error">
                      <label for="first_name" class="error">{{ $errors->first('first_name') }}</label>
                    </span>
                  </div><!--First Name ends-->
                  <!--Last Name-->
                  <div class="col-md-6">
                    <label for="last_name">Last Name</label>
                    <input name="last_name" id="last_name" type="text"  class="form-control" placeholder="Last Name" value="{{ $user_details[0]->last_name or old('last_name') }}">
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
                    <input name="email" id="email" type="text"  class="form-control" placeholder="Email" value="{{ $user_details[0]->email or old('email') }}">
                    <span class="error">
                      <label for="email" class="error">{{ $errors->first('email') }}</label>
                    </span>
                  </div><!--Email ends here-->
                  <!--Telephone-->
                  <div class="col-md-6">
                    <label for="telephone">Telephone</label>
                    <input name="telephone" id="telephone" type="text"  class="form-control" placeholder="Telephone" value="{{ $user_details[0]->telephone or old('telephone') }}">
                    <span class="error">
                      <label for="telephone" class="error"></label>
                      {{ $errors->first('telephone') }}
                    </span>
                  </div><!--Telephone ends-->
                </div>
                <div class="row form-row">
                  <!-- User Type -->
                  <div class="col-md-6">
                    <label for="user_type">User Type</label>
                    <select name="user_type" class="form-control select2">
                      <option value="">Select Below</option>
                      <option value="admin"
                      @if(isset($user_details))
                        @if($user_details[0]->user_type == 'admin')
                          selected="selected"
                        @endif
                      @else
                        @if(old('user_type') == 'admin')
                          selected="selected"
                        @endif
                      @endif
                      >Admin</option>
                      <option value="general"
                      @if(isset($user_details))
                        @if($user_details[0]->user_type == 'general')
                          selected="selected"
                        @endif
                      @else
                        @if(old('user_type') == 'general')
                          selected="selected"
                        @endif
                      @endif
                      >General User</option>
                      <option value="viewer"
                      @if(isset($user_details))
                        @if($user_details[0]->user_type == 'viewer')
                          selected="selected"
                        @endif
                      @else
                        @if(old('user_type') == 'viewer')
                          selected="selected"
                        @endif
                      @endif
                      >Viewer</option>
                      <?php /** ?><option value="external_partner"
                      @if(isset($user_details))
                        @if($user_details[0]->user_type == 'external_partner')
                          selected="selected"
                        @endif
                      @else
                        @if(old('user_type') == 'external_partner')
                          selected="selected"
                        @endif
                      @endif
                      >Ext. Partner</option> <?php **/ ?>
                    </select>
                    <span class="error">
                      <label for="user_type" class="error">{{ $errors->first('user_type') }}</label>
                    </span>
                  </div><!--User Type ends here-->
                  <!--Username-->
                  <div class="col-md-6">
                    <label for="username">Username</label>
                    <input name="username" id="username" type="text"  class="form-control" placeholder="Username" value="{{ $user_details[0]->username or old('username') }}">
                    <span class="error">
                      <label for="username" class="error"></label>
                      {{ $errors->first('username') }}
                    </span>
                  </div><!--Username ends-->
                </div>
                <div class="row form-row">
                  <!-- Password -->
                  <div class="col-md-6">
                    <label for="password">Password</label>
                    <input name="password" id="password" type="password"  class="form-control" placeholder="*********" value="{{ old('password') }}">
                    <span class="error">
                      <label for="password" class="error">{{ $errors->first('password') }}</label>
                    </span>
                  </div><!--Password ends-->
                  <!--Password Confirmation-->
                  <div class="col-md-6">
                    <label for="password_confirmation">Confirm Password</label>
                    <input name="password_confirmation" id="password_confirmation" type="password"  class="form-control" placeholder="*********" value="{{ old('password_confirmation') }}">
                    <span class="error">
                      <label for="password_confirmation" class="error"></label>
                      {{ $errors->first('password_confirmation') }}
                    </span>
                  </div><!--Password Confirmation ends-->
                </div>
              </div>
            </div>
            @if(isset($user_details))
            <input type="hidden" name="id" value="{{ $user_details[0]->
            id }}">
            @endif
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" name="addclient_submit" type="submit">{{ isset($user_details)?'UPDATE':'ADD' }}</button>
                <button class="btn btn-danger btn-cons body-link" name="adduser_submit" type="button" data-controller="user" data-mode="index">CANCEL</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>
<?php session()->forget('_old_input'); ?>