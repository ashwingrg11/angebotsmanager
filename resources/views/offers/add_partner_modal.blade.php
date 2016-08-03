<div class="content">
  <form class="form-no-horizontal-spacing" id="partner_add_form_modal" data-controller="partner" data-type="normal" data-method="create" method="post" data-modal="yes">
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple">
          <div class="grid-title">
            <h4>Add&nbsp;<span class="semi-bold">Partner</span></h4>
            <button type="button" class="close"  data-toggle="modal" aria-hidden="true" data-target="#add_new_partner">×</button>
          </div>
          <div class="grid-body">
            <div class="row column-seperation">
              <div class="col-md-12">
                @include('shared.notifications')
                <h4>Partner Information</h4>
                <div class="row form-row">
                  <!-- First Name -->
                  <div class="col-md-12">
                    <label for="">Select Project</label>
                    <select name="project[]" id="project" class="select2 form-control" multiple="multiple">
                      @if(!empty($projects))
                        @foreach($projects as $project)
                          <option value="{{ $project->id }}"
                            @if(old('project'))
                              @if(in_array($project->id, old('project')))
                                selected="selected"
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
                </div>
                <div class="row form-row">
                  <!-- First Name -->
                  <div class="col-md-6">
                    <label for="">Partner Name</label>
                    <input name="partner_name" id="partner_name" type="text"  class="form-control" placeholder="Partner Name" value="{{ old('partner_name') }}">
                    <span class="error">
                      <label for="partner_name" class="error">{{ $errors->first('partner_name') }}</label>
                    </span>
                  </div>
                  <div class="col-md-6">
                    <label for="">Region</label>
                    <input name="region" id="region" type="text"  class="form-control" placeholder="Partner Region" value="{{ old('region') }}">
                    <span class="error">
                      <label for="region" class="error"></label>
                      {{ $errors->first('region') }}
                    </span>
                  </div>
                </div>
                <div class="row form-row">
                  <div class="col-md-12">
                    <label for="">Notes</label>
                    <textarea class="form-control" placeholder="Notes" name="notes" id="notes">{{ old('notes') }}</textarea>
                    <span class="error">
                      <label for="notes" class="error"></label>
                      {{ $errors->first('notes') }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div id="address_form_wrapper" style="display: none;">
              <h4>Partner Address Information</h4>
              <div class="row form-row">
                <!-- Street 1 -->
                <div class="col-md-6">
                  <label for="">Street 1</label>
                  <input name="street1" id="street1" type="text"  class="form-control" placeholder="Street 1" value="{{ old('street1') }}">
                  <span class="error">
                    <label for="street1" class="error">{{ $errors->first('street1') }}</label>
                  </span>
                </div>
                <!-- Street 1 ends -->
                <!-- Street 2 -->
                <div class="col-md-6">
                  <label for="">Street 2</label>
                  <input name="street2" id="street2" type="text"  class="form-control" placeholder="Street 2" value="{{ old('street2') }}">
                  <span class="error">
                    <label for="street2" class="error">{{ $errors->first('street2') }}</label>
                  </span>
                </div>
                <!-- Street 2 ends --> </div>
              <div class="row form-row">
                <!-- Street 3 -->
                <div class="col-md-6">
                  <label for="">Street 3</label>
                  <input name="street3" id="street3" type="text"  class="form-control" placeholder="Street 3" value="{{ old('street3') }}">
                  <span class="error">
                    <label for="street3" class="error">{{ $errors->first('street3') }}</label>
                  </span>
                </div>
                <!-- Street 3 ends -->
                <!-- County -->
                <div class="col-md-6">
                  <label for="">County</label>
                  <input name="county" id="county" type="text"  class="form-control" placeholder="County" value="{{ old('county') }}">
                  <span class="error">
                    <label for="county" class="error">{{ $errors->first('county') }}</label>
                  </span>
                </div><!-- County -->
              </div>
              <div class="row form-row">
                <!-- Postcode -->
                <div class="col-md-6">
                  <label for="">Postcode</label>
                  <input name="post_code" id="post_code" type="text"  class="form-control" placeholder="Postcode" value="{{ old('post_code') }}">
                  <span class="error">
                    <label for="post_code" class="error">{{ $errors->first('post_code') }}</label>
                  </span>
                </div><!-- Postcode -->
                <!-- City -->
                <div class="col-md-6">
                  <label for="">City</label>
                  <input name="city" id="city" type="text"  class="form-control" placeholder="City" value="{{ old('city') }}">
                  <span class="error">
                    <label for="city" class="error">{{ $errors->first('city') }}</label>
                  </span>
                </div>
                <!-- City ends -->
              </div>
              <div class="row form-row">
                <!-- Country -->
                <div class="col-md-6">
                  <label for="country_id">Select Country</label>
                  <select name="country_id" id="country_id" class="select2 form-control">
                    <option value="">Select Below</option>
                    @foreach ($countries as $country)
                      <option value="{{$country->id}}"
                        @if (old('country_id') == $country->id)
                          selected="selected"
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
                  <input name="state" id="state" type="text"  class="form-control" placeholder="State" value="{{ old('state') }}">
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
                  <textarea class="form-control" placeholder="Display Address" name="display_address" id="display_address">{{old('display_address')}}</textarea>
                  <span class="error">
                    <label for="display_address" class="error">{{ $errors->first('display_address') }}</label>
                  </span>
                </div>
                <!-- Display Address -->
              </div>
              <input type="hidden" name="is_modal" value="yes">
            </div>
            <div class="form-actions">
              <div class="">
                <button class="btn btn-danger btn-cons" type="submit">ADD</button>
                <button type="button" class="btn btn-danger address_form_btn btn-cons" name="adduser_submit" type="submit" id="address_form_btn">Add Address</button>
                {{-- <button type="button" class="btn btn-primary" data-dismiss="modal">CANCEL</button> --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
  <!--form ends-->
</div>
