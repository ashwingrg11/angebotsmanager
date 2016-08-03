<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="contact" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Contacts</p>
    </li>
    <li><a href="#" class="active">{{ isset($contact_details) ? 'Edit' : 'Add' }}</a></li>
  </ul>
  <form class="form-no-horizontal-spacing" id="contact_add_form" data-controller="contact" data-type="normal" data-method="{{ isset($contact_details) ? 'update' : 'create'}}" method="post">
  <div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title">
          <h4>{{ isset($contact_details) ? 'Edit' : 'Add' }}<span class="semi-bold"> Contact</span></h4>
        </div>
        <div class="grid-body">
          <div class="row column-seperation">
            <div class="col-md-12">
              <h4>Contact Information</h4>
                <div class="row form-row">
                  <!-- Language -->
                  <div class="col-md-6">
                    <label for="contact-languag">Select Language</label>
                    <select name="language" id="contact-language" class="select2 form-control">
                      <option value="">Select Below</option>
                      @foreach ($languages as $language)
                        <option value="{{$language->id}}"
                          @if (isset($contact_details))
                            @if($contact_details[0]->languages->id == $language->id)
                              selected="selected"
                            @endif
                          @else
                            @if (old('language') == $language->id)
                              selected="selected"
                            @endif
                          @endif
                        >{{$language->label}}</option>
                      @endforeach
                    </select>
                    <span class="error">
                      <label for="contact-language" class="error">
                        {{ $errors->first('language') }}
                      </label>
                    </span>
                  </div>
                  <!-- Language -->
                  <!-- Title -->
                  <div class="col-md-6">
                    <label for="contact-title">Select Title</label>
                    <select name="title" id="contact-title" class="select2 form-control"  >
                      <option value="" id="title_select_below">Select Below</option>
                      <option id="title_mr" value="mr"
                      @if (isset($contact_details))
                        @if($contact_details[0]->title == "mr")
                          selected="selected"
                        @endif
                      @else
                        @if (old('title') == "mr")
                          selected="selected"
                        @endif
                      @endif
                      >Mr.</option>
                      <option id="title_mrs" value="mrs"
                      @if (isset($contact_details))
                        @if($contact_details[0]->title == "mrs")
                          selected="selected"
                        @endif
                      @else
                        @if (old('title') == "mrs")
                          selected="selected"
                        @endif
                      @endif
                      >Mrs.</option>
                      <option id="title_ms" value="ms"
                      @if (isset($contact_details))
                        @if($contact_details[0]->title == "ms")
                          selected="selected"
                        @endif
                      @else
                        @if (old('title') == "ms")
                          selected="selected"
                        @endif
                      @endif
                      >Ms.</option>
                      <!--german titles start-->
                      <option id="title_herr" value="herr"
                      @if (isset($contact_details))
                        @if($contact_details[0]->title == "herr")
                          selected="selected"
                        @endif
                      @else
                        @if (old('title') == "herr")
                          selected="selected"
                        @endif
                      @endif
                      >Herr</option>
                      <option id="title_frau" value="frau"
                      @if (isset($contact_details))
                        @if($contact_details[0]->title == "frau")
                          selected="selected"
                        @endif
                      @else
                        @if (old('title') == "frau")
                          selected="selected"
                        @endif
                      @endif
                      >Frau</option>
                    </select>
                    <span class="error">
                        <label for="contact-title" class="error">
                          {{ $errors->first('title') }}
                        </label>
                      </span>
                  </div>
                  <!-- Title -->
                </div>
                <div class="row form-row">
                  <!-- First Name -->
                  <div class="col-md-6">
                    <label for="">First Name</label>
                    <input name="first_name" id="first_name" type="text"  class="form-control" placeholder="First Name" value="{{ $contact_details[0]->first_name or old('first_name') }}">
                    <span class="error">
                      <label for="first_name" class="error">
                        {{ $errors->first('first_name') }}
                      </label>
                    </span>
                  </div>
                  <!-- End First Name -->
                  <!-- Last Name -->
                  <div class="col-md-6">
                    <label for="">Last Name</label>
                    <input name="last_name" id="last_name" type="text"  class="form-control" placeholder="Last Name" value="{{ $contact_details[0]->last_name or old('last_name') }}">
                    <span class="error">
                      <label for="last_name" class="error"></label>
                        {{ $errors->first('last_name') }}
                    </span>
                  </div>
                  <!-- End Last Name -->
                </div>
                <div class="row form-row">
                  <!-- Greeting -->
                  <div class="col-md-6">
                    <label for="greeting">Greeting</label>
                    <textarea class="form-control" placeholder="Greeting" name="greeting" id="greeting">{{ $contact_details[0]->greeting or old('greeting') }}</textarea>
                    <span class="error">
                      <label for="greeting" class="error">
                        {{ $errors->first('greeting') }}
                      </label>
                    </span>
                  </div>
                  <!-- Greeting -->
                </div>
                <div class="row form-row">
                  <!-- Company -->
                  <div class="col-md-6">
                    <label for="">Company</label>
                    <input name="company" id="company" type="text"  class="form-control" placeholder="Company Name" value="{{ $contact_details[0]->company or old('company') }}">
                    <span class="error">
                      <label for="company" class="error">
                        {{ $errors->first('company') }}
                      </label>
                    </span>
                  </div>
                  <!-- Company ends -->
                  <!-- Department -->
                  <div class="col-md-6">
                    <label for="">Department</label>
                    <input name="department" id="department" type="text"  class="form-control" placeholder="Department" value="{{ $contact_details[0]->department or old('department') }}">
                    <span class="error">
                      <label for="department" class="error"></label>
                        {{ $errors->first('department') }}
                    </span>
                  </div>
                  <!-- Department ends -->
                </div>
                <div class="row form-row">
                  <!-- Job Title -->
                  <div class="col-md-6">
                    <label for="job_title">Job Title</label>
                    <input name="job_title" id="job_title" type="text"  class="form-control" placeholder="Job Title" value="{{ $contact_details[0]->job_title or old('job_title') }}">
                    <span class="error">
                      <label for="job_title" class="error">
                        {{ $errors->first('job_title') }}
                      </label>
                    </span>
                  </div>
                  <!-- Job Title ends -->
                  <!-- Email -->
                  <div class="col-md-6">
                    <label for="email">E-mail</label>
                    <input name="email" id="email" type="text"  class="form-control" placeholder="email@address.com" value="{{ $contact_details[0]->email or old('email') }}">
                    <span class="error">
                      <label for="email" class="error">
                        {{ $errors->first('email') }}
                      </label>
                    </span>
                  </div>
                  <!-- End Email -->
                </div>
                <div class="row form-row">
                  <!-- Phone -->
                  <div class="col-md-6">
                    <label for="phone">Phone</label>
                    <input name="phone" id="phone" type="text"  class="form-control" placeholder="Phone" value="{{ $contact_details[0]->phone or old('phone') }}">
                    <span class="error">
                      <label for="phone" class="error">
                        {{ $errors->first('phone') }}
                      </label>
                    </span>
                  </div>
                  <!-- End Phone -->
                  <!-- Mobile Phone -->
                  <div class="col-md-6">
                    <label for="mobile_phone">Mobile Phone</label>
                    <input name="mobile_phone" id="mobile_phone" type="text"  class="form-control" placeholder="Mobile Phone" value="{{ $contact_details[0]->mobile_phone or old('mobile_phone') }}">
                    <span class="error">
                      <label for="mobile_phone" class="error">
                        {{ $errors->first('mobile_phone') }}
                      </label>
                    </span>
                  </div>
                  <!-- End Mobile Phone -->
                  @if(isset($contact_details))
                    <input type="hidden" name="id" value="{{ $contact_details[0]->id }}">
                  @endif
                </div>
                <!--notes-->
                <div class="row form-row">
                  <div class="col-md-12">
                    <label for="">Notes</label>
                    <textarea class="form-control" placeholder="Notes" name="notes" id="notes">{{ $contact_details[0]->notes or old('notes') }}</textarea>
                    <span class="error">
                      <label for="notes" class="error"></label>
                      {{ $errors->first('notes') }}
                    </span>
                  </div>
                </div><!--notes-->
                <div id="address_form_wrapper" style="display: none;">
                  <h4>Contact Address Information</h4>
                  <div class="row form-row">
                    <!-- Street 1 -->
                    {{-- print_r($contact_details[0]->address->id) --}}
                    <div class="col-md-6">
                      <label for="">Street 1</label>
                      <input name="street1" id="street1" type="text"  class="form-control" placeholder="Street 1" value="{{ $contact_details[0]->address->street1 or old('street1') }}">
                      <span class="error">
                        <label for="street1" class="error">
                          {{ $errors->first('street1') }}
                        </label>
                      </span>
                    </div>
                    <!-- Street 1 ends -->
                    <!-- Street 2 -->
                    <div class="col-md-6">
                      <label for="">Street 2</label>
                      <input name="street2" id="street2" type="text"  class="form-control" placeholder="Street 2" value="{{ $contact_details[0]->address->street2 or old('street2') }}">
                      <span class="error">
                        <label for="street2" class="error">
                          {{ $errors->first('street2') }}
                        </label>
                      </span>
                    </div>
                    <!-- Street 2 ends -->
                  </div>
                  <div class="row form-row">
                    <!-- Street 3 -->
                    <div class="col-md-6">
                      <label for="">Street 3</label>
                      <input name="street3" id="street3" type="text"  class="form-control" placeholder="Street 3" value="{{ $contact_details[0]->address->street3 or old('street3') }}">
                      <span class="error">
                        <label for="street3" class="error">
                          {{ $errors->first('street3') }}
                        </label>
                      </span>
                    </div>
                    <!-- Street 3 ends -->
                    <!-- County -->
                    <div class="col-md-6">
                      <label for="">County</label>
                      <input name="county" id="county" type="text"  class="form-control" placeholder="County" value="{{ $contact_details[0]->address->county or old('county') }}">
                      <span class="error">
                        <label for="county" class="error">
                          {{ $errors->first('county') }}
                        </label>
                      </span>
                    </div>
                    <!-- County -->
                  </div>
                  <div class="row form-row">
                    <!-- Postcode -->
                    <div class="col-md-6">
                      <label for="">Postcode</label>
                      <input name="post_code" id="post_code" type="text"  class="form-control" placeholder="Postcode" value="{{ $contact_details[0]->address->post_code or old('post_code') }}">
                      <span class="error">
                        <label for="post_code" class="error">
                          {{ $errors->first('post_code') }}
                        </label>
                      </span>
                    </div>
                    <!-- Postcode -->
                    <!-- City -->
                    <div class="col-md-6">
                      <label for="">City</label>
                      <input name="city" id="city" type="text"  class="form-control" placeholder="City" value="{{ $contact_details[0]->address->city or old('city') }}">
                      <span class="error">
                        <label for="city" class="error">
                          {{ $errors->first('city') }}
                        </label>
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
                        @foreach($countries as $country)
                          <option value="{{$country->id}}"
                            @if (isset($contact_details))
                              @if ($contact_details[0]->address->country_id == $country->id)
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
                        <label for="country_id" class="error">
                          {{ $errors->first('country_id') }}
                        </label>
                      </span>
                    </div>
                    <!-- Country -->
                    <!-- State -->
                    <div class="col-md-6">
                      <label for="">State</label>
                      <input name="state" id="state" type="text"  class="form-control" placeholder="State" value="{{ $contact_details[0]->address->state or old('state') }}">
                      <span class="error">
                        <label for="state" class="error">
                          {{ $errors->first('state') }}
                        </label>
                      </span>
                    </div>
                    <!-- State ends -->
                  </div>
                  <div class="row form-row">
                    <!-- Display Address -->
                    <div class="col-md-6">
                      <label for="display_address">Display Address</label>
                      <textarea class="form-control" placeholder="Display Address" name="display_address" id="display_address">{{$contact_details[0]->address->display_address or old('display_address')}}</textarea>
                      <span class="error">
                        <label for="display_address" class="error">
                          {{ $errors->first('display_address') }}
                        </label>
                      </span>
                    </div>
                    <!-- Display Address -->
                  </div>
                  @if(isset($contact_details))
                    <input type="hidden" name="address_id" value="{{ $contact_details[0]->address->id }}">
                  @endif
                </div>
              </div>
            </div>
          <div class="form-actions">
            <div class="">
            <button class="btn btn-danger btn-cons" id="" name="addcontact_submit" type="submit">{{ isset($contact_details)?'UPDATE':'ADD' }}</button>
            <button class="btn btn-danger btn-cons address_form_btn" name="adduser_submit" type="submit" id="address_form_btn">Add Address</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </form><!--form ends-->
</div>
<script type="text/javascript">
(function(){
  language_id = $('#contact-language').val();
  all_titles = $('#title_mr, #title_mrs, #title_ms, #title_herr, #title_frau');
  german_titles = $('#title_herr, #title_frau');
  english_titles = $('#title_mr, #title_mrs, #title_ms');
  if(language_id != ''){
    if(language_id == 2){
      english_titles.hide();
      german_titles.show();
      return false;
    }else{
      german_titles.hide();
      english_titles.show();
      return false;
    }
  }else{
    all_titles.hide();
    $('#contact-title').val('Select Below');
    return false;
  }
})();
</script>