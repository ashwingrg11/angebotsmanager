<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="partner" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Partners</p>
    </li>
    <li>
      <a href="#" class="active">{{ isset($partners_details) ? 'Edit' : 'Add' }}</a>
    </li>
  </ul>
  <form class="form-no-horizontal-spacing" id="partner_add_form" data-controller="partner" data-type="normal" data-method="{{ isset($partners_details) ? 'update' : 'create'}}" method="post">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>
              {{ isset($partners_details) ? 'Edit' : 'Add' }}
              <span class="semi-bold">Partner</span>
            </h4>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                <h4>Partner Information</h4>
                <div class="row form-row">
                  @if((!isset($partners_details) && Auth::user()->user_type == "general") || Auth::user()->user_type == "admin")
                  <!-- Project -->
                  <div class="col-md-12">
                    <label for="">Select Project</label>
                    <select name="project[]" id="project" class="select2 form-control" multiple="multiple">
                      @if(!empty($projects))
                        @foreach($projects as $project)
                          <option value="{{ $project->id }}"
                            @if(isset($partners_details))
                              @foreach ($partners_details[0]->projects as $partner_project)
                                @if($partner_project->id == $project->id)
                                  selected="selected"
                                @endif
                              @endforeach
                            @else
                              @if(old('project'))
                                @if(in_array($project->id, old('project')))
                                  selected="selected"
                                @endif
                              @endif
                            @endif
                          >{{ $project->name }}</option>
                        @endforeach
                      @else
                        <option disabled>N/A</option>
                      @endif
                    </select>
                    <span class="error">
                      <label for="project" class="error">{{ $errors->first('project') }}</label>
                    </span>
                  </div>
                  <!--Project ends-->
                  @endif
                </div>
                <div class="row form-row">
                  <!-- Partner Name -->
                  <div class="col-md-6">
                    <label for="">Partner Name</label>
                    <input name="partner_name" id="partner_name" type="text"  class="form-control" placeholder="Partner Name" value="{{ $partners_details[0]->
                    partner_name or old('partner_name') }}">
                    <span class="error">
                      <label for="partner_name" class="error">{{ $errors->first('partner_name') }}</label>
                    </span>
                  </div>
                  <div class="col-md-6">
                    <label for="">Region</label>
                    <input name="region" id="region" type="text"  class="form-control" placeholder="Partner Region" value="{{ $partners_details[0]->
                    region or old('region') }}">
                    <span class="error">
                      <label for="region" class="error"></label>
                      {{ $errors->first('region') }}
                    </span>
                  </div>
                </div>
                <div class="row form-row">
                  <div class="col-md-12">
                    <label for="">Notes</label>
                    <textarea class="form-control" placeholder="Notes" name="notes" id="notes">{{ $partners_details[0]->notes or old('notes') }}</textarea>
                    <span class="error">
                      <label for="notes" class="error"></label>
                      {{ $errors->first('notes') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            @if(isset($partners_details))
            <input type="hidden" name="id" value="{{ $partners_details[0]->
            id }}">
            @endif
            <div id="address_form_wrapper" style="display: none;">
              <h4>Partner Address Information</h4>
              <div class="row form-row">
                <!-- Street 1 -->
                {{-- print_r($partners_details[0]->address->id) --}}
                <div class="col-md-6">
                  <label for="">Street 1</label>
                  <input name="street1" id="street1" type="text"  class="form-control" placeholder="Street 1" value="{{ $partners_details[0]->
                  address->street1 or old('street1') }}">
                  <span class="error">
                    <label for="street1" class="error">{{ $errors->first('street1') }}</label>
                  </span>
                </div>
                <!-- Street 1 ends -->
                <!-- Street 2 -->
                <div class="col-md-6">
                  <label for="">Street 2</label>
                  <input name="street2" id="street2" type="text"  class="form-control" placeholder="Street 2" value="{{ $partners_details[0]->
                  address->street2 or old('street2') }}">
                  <span class="error">
                    <label for="street2" class="error">{{ $errors->first('street2') }}</label>
                  </span>
                </div>
                <!-- Street 2 ends --> </div>
              <div class="row form-row">
                <!-- Street 3 -->
                <div class="col-md-6">
                  <label for="">Street 3</label>
                  <input name="street3" id="street3" type="text"  class="form-control" placeholder="Street 3" value="{{ $partners_details[0]->
                  address->street3 or old('street3') }}">
                  <span class="error">
                    <label for="street3" class="error">{{ $errors->first('street3') }}</label>
                  </span>
                </div><!-- Street 3 ends -->
                <!-- County -->
                <div class="col-md-6">
                  <label for="">County</label>
                  <input name="county" id="county" type="text"  class="form-control" placeholder="County" value="{{ $partners_details[0]->
                  address->county or old('county') }}">
                  <span class="error">
                    <label for="county" class="error">{{ $errors->first('county') }}</label>
                  </span>
                </div><!-- County -->
              </div>
              <div class="row form-row">
                <!-- Postcode -->
                <div class="col-md-6">
                  <label for="">Postcode</label>
                  <input name="post_code" id="post_code" type="text"  class="form-control" placeholder="Postcode" value="{{ $partners_details[0]->
                  address->post_code or old('post_code') }}">
                  <span class="error">
                    <label for="post_code" class="error">{{ $errors->first('post_code') }}</label>
                  </span>
                </div><!-- Postcode -->
                <!-- City -->
                <div class="col-md-6">
                  <label for="">City</label>
                  <input name="city" id="city" type="text"  class="form-control" placeholder="City" value="{{ $partners_details[0]->
                  address->city or old('city') }}">
                  <span class="error">
                    <label for="city" class="error">{{ $errors->first('city') }}</label>
                  </span>
                </div>
                <!-- City ends -->
                <!-- County -->
              </div>
              <div class="row form-row">
                <!-- Country -->
                <div class="col-md-6">
                  <label for="country_id">Select Country</label>
                  <select name="country_id" id="country_id" class="select2 form-control">
                    <option value="">Select Below</option>
                    @foreach ($countries as $country)
                      <option value="{{$country->id}}"
                        @if (isset($partners_details))
                          @if ($partners_details[0]->address->country_id == $country->id)
                            selected="selected"
                          @endif
                        @else
                          @if (old('country_id') == $country->id)
                            selected="selected"
                          @endif
                        @endif
                        >{{$country->label}}</option>
                    @endforeach
                  </select>
                  <span class="error">
                    <label for="country_id" class="error">{{ $errors->first('country_id') }}</label>
                  </span>
                </div><!-- Country -->
                <!-- State -->
                <div class="col-md-6">
                  <label for="">State</label>
                  <input name="state" id="state" type="text"  class="form-control" placeholder="State" value="{{ $partners_details[0]->
                  address->state or old('state') }}">
                  <span class="error">
                    <label for="state" class="error">{{ $errors->first('state') }}</label>
                  </span>
                </div>
                <!-- State ends -->
              </div>
              <div class="row form-row">
                <!-- Display Address -->
                <div class="col-md-6">
                  <label for="display_address">Display Address</label>
                  <textarea class="form-control" placeholder="Display Address" name="display_address" id="display_address">{{$partners_details[0]->address->display_address or old('display_address')}}</textarea>
                  <span class="error">
                    <label for="display_address" class="error">{{ $errors->first('display_address') }}</label>
                  </span>
                </div>
                <!-- Display Address --> </div>
              @if(isset($partners_details))
              <input type="hidden" name="address_id" value="{{ $partners_details[0]->
              address->id }}">
                @endif
            </div>
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" name="addpartner_submit" type="submit">{{ isset($partners_details)?'UPDATE':'ADD' }}</button>
                <button class="btn btn-danger btn-cons address_form_btn" name="adduser_submit" type="submit" id="address_form_btn">Add Address</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>