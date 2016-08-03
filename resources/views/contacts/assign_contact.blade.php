<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Contacts</p>
    </li>
    <li><a href="#" class="active">Assign Contact</a> </li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @if(session('added_contact'))
        <div class="alert alert-success">
          <i class="fa fa-check"></i>
          <button class="close" data-dismiss="alert"></button>
          Success: {{ session('added_contact') }}
        </div>
      @endif
      <div class="grid simple ">
        <div class="grid-title">
          <h4>Assign <span class="semi-bold">Contacts</span></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body ">
          <form class="form-no-horizontal-spacing" id="contact_assign_form" data-controller="contact" data-type="normal" data-method="" method="post">
            {{-- {!! csrf_field() !!} --}}
            <div class="row column-seperation">
              <div class="col-md-12">
                <!--<h4>Basic Contact Information</h4>-->
                  <div class="row form-row">
                    <!-- Language -->
                    <div class="col-md-6">
                      <label for="">Select Type</label>
                      <select name="contact_type" id="contact_type" class="select2 form-control">
                        <option value="">Select Below</option>
                        <option value="">Partner Contact</option>
                        <option value="">Client Contact</option>
                        <option value="">JI Contact</option>
                      </select>
                      <span class="error">
                        <label for="contact_type" class="error">
                          {{ $errors->first('contact_type') }}
                        </label>
                      </span>
                    </div>
                    <!-- Language -->
                  </div>
              </div>
            </div><br>
            <!--table-->
            <table class="table datatable" id="assign_contact_table" data-page-length='50'>
              <thead>
                <tr>
                  <th>First Name</th>
                  <th>Last Name</th>
                  <th>Email</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td>N/A</td>
                    <td>N/A</td>
                  </tr>
              </tbody>
            </table><br>
            <!--table-->
            <div class="form-actions">
              <div class="">
              <button class="btn btn-danger btn-cons" name="adduser_submit" type="submit" disabled="disabled">SUBMIT</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>