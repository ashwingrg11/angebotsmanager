<?php
  if(!empty(session('errors')) || !($errors->isEmpty())){
    session(['project_id' => old('project_id'),
            'placement_ids' => old('placement_ids'),
            'partner_id' => old('partner_id'),
            'contact_id' => old('contact_id'),
            'offer_title_en' => old('title_en'),
            'offer_title_de' => old('title_de'),
            'offer_type_category_masterlist_id' => old('offer_type_category_masterlist_id'),
            'general_offer_type_masterlist_id' => old('general_offer_type_masterlist_id'),
            'detailed_offer_type_masterlist_id' => old('detailed_offer_type_masterlist_id'),
            'placement_ids_form_error' => $errors->first('placement_ids'),
            'partner_id_form_error' => $errors->first('partner_id'),
            'contact_id_form_error' => $errors->first('contact_id'),
            'offer_type_category_form_error' => $errors->first('offer_type_category_masterlist_id'),
            'general_offer_type_form_error' => $errors->first('general_offer_type_masterlist_id'),
            'detailed_offer_type_form_error' => $errors->first('detailed_offer_type_masterlist_id'),
            'offer_titleen_form_error' => $errors->first('title_en'),
            'offer_titlede_form_error' => $errors->first('title_de'),
            'ji_user_ids' => old('ji_user_ids')
    ]);
  }else{
    session()->forget(['project_id', 'placement_ids', 'partner_id', 'contact_id', 'offer_type_category_masterlist_id', 'general_offer_type_masterlist_id', 'detailed_offer_type_masterlist_id', 'placement_ids_form_error', 'partner_id_form_error', 'contact_id_form_error', 'offer_type_category_form_error', 'general_offer_type_form_error', 'detailed_offer_type_form_error', 'offer_titleen_form_error', 'offer_titlede_form_error', 'offer_title_en', 'offer_title_de', 'ji_user_ids']);
  };
  if(isset($offer_details)){
    session(['offer_details' => $offer_details]);
  }else{
    session()->forget(['offer_details']);
  };
 ?>
 <?php //session()->forget('form_errors'); ?>
<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="add-back-link body-link" data-controller="offer" data-mode="index">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Offers</p>
    </li>
    <li>
      <a href="#" class="active">{{ isset($offer_details) ? 'Edit' : 'Add' }}</a>
    </li>
  </ul>
    <div class="row">
      <div class="col-md-12">
        <div class="grid simple add_offer_simple_grid">
          <div class="grid-title">
            <h4>
              {{ isset($offer_details) ? 'Edit' : 'Add' }}
              <span class="semi-bold">Offer</span>
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
                <li role="presentation" class="active" id="tab_one_li"><a href="#basic_info" aria-controls="basic_info" role="tab" data-toggle="tab">General Offer Information</a></li>
                <li role="presentation" class="" id="tab_two_li"><a href="#detailed_info" aria-controls="detail_info" role="tab" data-toggle="tab">Detailed Offer Information</a></li>
                <li role="presentation" class="" id="tab_three_li"><a href="#admin_info" aria-controls="admin_info" role="tab" data-toggle="tab">Admin Information</a></li>
                <li role="presentation" class="" id="tab_four_li"><a href="#communication_package" aria-controls="communication_package" role="tab" data-toggle="tab">Communication Package</a></li>
              </ul><!--nav tabs-->
              <form class="form-no-horizontal-spacing" id="offer_add_form" data-controller="offer" data-type="normal" data-modaltype="no" data-method="{{ isset($offer_details) ? 'update' : 'create' }}">
                <!-- Tab panes -->
                <div class="tab-content" style="padding-bottom:0;">
                  <!--  General Offer Information -->
                  <div role="tabpanel" class="tab-pane active" id="basic_info">
                    <h3>General Offer Info</h3>
                    <div class="row form-row">
                      <!-- Project -->
                      <div class="col-md-6">
                        <label for="offer_project_select">Select Project</label>
                        <select name="project_id" id="offer_project_select" class="select2 form-control"  >
                          {{-- <option value="">Select Below</option> --}}
                          <option value="">Select Below</option>
                          <?php /** ?>@if(!$projects->isEmpty()) <?php **/ ?>
                          @if(!empty($projects))
                            @foreach($projects as $project)
                              <option value="{{ $project->id }}"
                              @if(isset($offer_details))
                                @if($offer_details[0]->projects->id == $project->id)
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
                          <label for="project_id" class="error">{{ $errors->first('project_id') }}</label>
                        </span>
                      </div><!--Project-->
                    </div>
                    <!--select project view wrapper-->
                    <div id="select_project_view_wrapper">
                      @include('offers.select_project_selects_partial')
                    </div><!--select project view wrapper ends here-->
                  </div><!--  General Offer Information ends here -->
                  <!--  Detailed Offer Info -->
                  <div role="tabpanel" class="tab-pane" id="detailed_info">
                    <h3>Detailed Offer Info</h3>
                    <div class="row form-row">
                      <!-- Location -->
                      <div class="col-md-12">
                        <label for="location">Location</label>
                        <input name="location" id="location" type="text"  class="form-control" placeholder="Enter Location" value="{{ $offer_details[0]->location or old('location') }}">
                        <span class="error">
                          <label for="location" class="error">{{ $errors->first('location') }}</label>
                        </span>
                      </div><!--Location-->
                    </div>
                    <div class="row form-row">
                      <!-- Valid From -->
                      <div class="col-md-6">
                        <label for="valid_from">Valid From</label>
                        <input name="valid_from" id="valid_from" type="text"  class="form-control date" placeholder="Valid From"
                        @if(isset($offer_details))
                          value="{{Carbon\Carbon::createFromFormat('d/m/Y', $offer_details[0]->valid_from)->format('Y-m-d')}}"
                        @else
                          value="{{old('valid_from')}}"
                        @endif
                        >
                        <span class="error">
                          <label for="valid_from" class="error">{{ $errors->first('valid_from') }}</label>
                        </span>
                      </div><!--Valid From-->
                      <!-- Valid Until -->
                      <div class="col-md-6">
                        <label for="valid_until">Valid Until</label>
                        <input name="valid_until" id="valid_until" type="text"  class="form-control date" placeholder="Valid Until"
                        @if(isset($offer_details))
                          value="{{Carbon\Carbon::createFromFormat('d/m/Y', $offer_details[0]->valid_until)->format('Y-m-d')}}"
                        @else
                          value="{{old('valid_until')}}"
                        @endif
                        >
                        <span class="error">
                          <label for="valid_until" class="error">{{ $errors->first('valid_until') }}</label>
                          @if(Session::has('placement_notice') || isset($placement_notice))
                            <label for="valid_until" class="error">Offer end date value did not match with placement duration. <br> Recommended minimum offer end date value is {{$placement_notice or Session::get('placement_notice')}}</label>
                          @endif
                        </span>
                      </div><!--Valid Until-->
                    </div>
                    <h4>Offer Address</h4>
                    <div class="row form-row">
                      <!-- Street1 -->
                      <div class="col-md-6">
                        <label for="street1">Street 1</label>
                        <input name="street1" id="street1" type="text"  class="form-control" placeholder="Street 1" value="{{ $offer_details[0]->street1 or old('street1') }}">
                        <span class="error">
                          <label for="street1" class="error">{{ $errors->first('street1') }}</label>
                        </span>
                      </div><!--Street1-->
                      <!-- Street2 -->
                      <div class="col-md-6">
                        <label for="street2">Street 2</label>
                        <input name="street2" id="street2" type="text"  class="form-control" placeholder="Street 2" value="{{ $offer_details[0]->street2 or old('street2') }}">
                        <span class="error">
                          <label for="street2" class="error">{{ $errors->first('street2') }}</label>
                        </span>
                      </div><!--Street2-->
                    </div>
                    <div class="row form-row">
                      <!-- Street3 -->
                      <div class="col-md-6">
                        <label for="street3">Street 3</label>
                        <input name="street3" id="street3" type="text"  class="form-control" placeholder="Street 3" value="{{ $offer_details[0]->street3 or old('street3') }}">
                        <span class="error">
                          <label for="street3" class="error">{{ $errors->first('street3') }}</label>
                        </span>
                      </div><!--Street3-->
                      <!-- Postal Code -->
                      <div class="col-md-6">
                        <label for="postal_code">Postal Code</label>
                        <input name="postal_code" id="postal_code" type="text"  class="form-control" placeholder="Postal Code" value="{{ $offer_details[0]->postal_code or old('postal_code') }}">
                        <span class="error">
                          <label for="postal_code" class="error">{{ $errors->first('postal_code') }}</label>
                        </span>
                      </div><!--Postal Code-->
                    </div>
                    <div class="row form-row">
                      <!-- City -->
                      <div class="col-md-6">
                        <label for="city">City</label>
                        <input name="city" id="city" type="text"  class="form-control" placeholder="City" value="{{ $offer_details[0]->city or old('city') }}">
                        <span class="error">
                          <label for="city" class="error">{{ $errors->first('city') }}</label>
                        </span>
                      </div><!--City-->
                      <!-- County -->
                      <div class="col-md-6">
                        <label for="county">County</label>
                        <input name="county" id="county" type="text"  class="form-control" placeholder="County" value="{{ $offer_details[0]->county or old('county') }}">
                        <span class="error">
                          <label for="county" class="error">{{ $errors->first('county') }}</label>
                        </span>
                      </div><!--County Ends-->
                    </div>
                    <div class="row form-row">
                      <!-- State -->
                      <div class="col-md-6">
                        <label for="state">State</label>
                        <input name="state" id="state" type="text"  class="form-control" placeholder="State" value="{{ $offer_details[0]->state or old('state') }}">
                        <span class="error">
                          <label for="state" class="error">{{ $errors->first('state') }}</label>
                        </span>
                      </div><!--State-->
                      <!-- Country -->
                      <div class="col-md-6">
                        <label for="offer_country_select">Select Country</label>
                        <select name="country_id" id="offer_country_select" class="select2 form-control"  >
                          <option value="">Select Below</option>
                          @if(!$countries->isEmpty())
                            @foreach ($countries as $country)
                              <option value="{{$country->id}}"
                                @if(isset($offer_details[0]))
                                  @if($offer_details[0]->countries)
                                    @if($offer_details[0]->countries->id == $country->id)
                                      selected="selected"
                                    @endif
                                  @endif
                                @else
                                  @if(old('country_id') == $country->id)
                                    selected="selected"
                                  @endif
                                @endif
                              >{{$country->label}}</option>
                            @endforeach
                          @endif
                        </select>
                        <span class="error">
                          <label for="country_id" class="error">{{ $errors->first('country_id') }}</label>
                        </span>
                      </div><!--Country Ends-->
                    </div>
                    <div class="row form-row">
                      <!-- Market -->
                      <div class="col-md-12">
                        <label for="market">Market</label>
                        <input name="market" id="market" type="text"  class="form-control" placeholder="Enter Market" value="{{ $offer_details[0]->market or old('market') }}">
                        <span class="error">
                          <label for="market" class="error">{{ $errors->first('market') }}</label>
                        </span>
                      </div><!--Market ends here-->
                    </div>
                    <div class="row form-row">
                      <!-- Offer(EN)-->
                      <div class="col-md-6">
                        <label for="offer_en">Offer (EN)</label>
                        <textarea class="form-control" placeholder="Offer: EN" name="offer_en" id="offer_en" rows="3">{{ $offer_details[0]->offer_en or old('offer_en') }}</textarea>
                        <span class="error">
                          <label for="offer_en" class="error">
                            {{ $errors->first('offer_en') }}
                          </label>
                        </span>
                      </div><!-- Offer(EN)-->
                      <!-- Offer(DE)-->
                      <div class="col-md-6">
                        <label for="offer_de">Offer (DE)</label>
                        <textarea class="form-control" placeholder="Offer: DE" name="offer_de" id="offer_de" rows="3">{{ $offer_details[0]->offer_de or old('offer_de') }}</textarea>
                        <span class="error">
                          <label for="offer_de" class="error">
                            {{ $errors->first('offer_de') }}
                          </label>
                        </span>
                      </div><!-- Offer(DE)-->
                    </div>
                    <div class="row form-row">
                      <!-- Privilege/Benefit (EN)-->
                      <div class="col-md-6">
                        <label for="benefit_en">Privilege/Benefit (EN)</label>
                        <textarea class="form-control" placeholder="Privilege / Benefit: EN" name="benefit_en" id="benefit_en" rows="4">{{ $offer_details[0]->benefit_en or old('benefit_en') }}</textarea>
                        <span class="error">
                          <label for="benefit_en" class="error">
                            {{ $errors->first('benefit_en') }}
                          </label>
                        </span>
                      </div><!-- Privilege/Benefit (EN)-->
                      <!-- Privilege/Benefit (DE)-->
                      <div class="col-md-6">
                        <label for="benefit_de">Privilege/Benefit (DE)</label>
                        <textarea class="form-control" placeholder="Privilege / Benefit: DE" name="benefit_de" id="benefit_de" rows="4">{{ $offer_details[0]->benefit_de or old('benefit_de') }}</textarea>
                        <span class="error">
                          <label for="benefit_de" class="error">
                            {{ $errors->first('benefit_de') }}
                          </label>
                        </span>
                      </div><!-- Privilege/Benefit (DE)-->
                    </div>
                    <div class="row form-row">
                      <!-- Price(EN)-->
                      <div class="col-md-6">
                        <label for="price_en">Price (EN)</label>
                        <textarea class="form-control" placeholder="Price: EN" name="price_en" id="price_en" rows="3">{{ $offer_details[0]->price_en or old('price_en') }}</textarea>
                        <span class="error">
                          <label for="price_en" class="error">
                            {{ $errors->first('price_en') }}
                          </label>
                        </span>
                      </div><!-- Price(EN)-->
                      <!-- Price(DE)-->
                      <div class="col-md-6">
                        <label for="price_de">Price (DE)</label>
                        <textarea class="form-control" placeholder="Price: DE" name="price_de" id="price_de" rows="3">{{ $offer_details[0]->price_de or old('price_de') }}</textarea>
                        <span class="error">
                          <label for="price_de" class="error">
                            {{ $errors->first('price_de') }}
                          </label>
                        </span>
                      </div><!-- Price(DE)-->
                    </div>
                    <div class="row form-row">
                      <!-- Further Information(en)-->
                      <div class="col-md-6">
                        <label for="further_information_en">Further Information(en)</label>
                        <textarea class="form-control" placeholder="Further Information in English" name="further_information_en" id="further_information_en" rows="5">{{ $offer_details[0]->further_information_en or old('further_information_en') }}</textarea>
                        <span class="error">
                          <label for="further_information_en" class="error">
                            {{ $errors->first('further_information_en') }}
                          </label>
                        </span>
                      </div><!-- Further Information(en) ends-->
                      <!-- Further Information(de)-->
                      <div class="col-md-6">
                        <label for="further_information_de">Further Information(de)</label>
                        <textarea class="form-control" placeholder="Further Information in German" name="further_information_de" id="further_information_de" rows="5">{{ $offer_details[0]->further_information_de or old('further_information_de') }}</textarea>
                        <span class="error">
                          <label for="further_information_de" class="error">
                            {{ $errors->first('further_information_de') }}
                          </label>
                        </span>
                      </div><!-- Further Information(de) ends-->
                    </div>
                    <div class="row form-row">
                      <!-- Reservation Telephone -->
                      <div class="col-md-6">
                        <label for="reservation_tel">Reservation Telephone</label>
                        <input name="reservation_telephone" id="reservation_tel" type="text"  class="form-control" placeholder="Reservation Telephone" value="{{ $offer_details[0]->reservation_telephone or old('reservation_telephone') }}">
                        <span class="error">
                          <label for="reservation_telephone" class="error">{{ $errors->first('reservation_telephone') }}</label>
                        </span>
                      </div><!--Reservation Telephone-->
                      <!-- Reservation Fax -->
                      <div class="col-md-6">
                        <label for="reservation_fax">Reservation Fax</label>
                        <input name="reservation_fax" id="reservation_fax" type="text"  class="form-control" placeholder="Reservation Fax" value="{{ $offer_details[0]->reservation_fax or old('reservation_fax') }}">
                        <span class="error">
                          <label for="reservation_fax" class="error">{{ $errors->first('reservation_fax') }}</label>
                        </span>
                      </div><!--Reservation Fax-->
                    </div>
                    <div class="row form-row">
                      <!-- Reservation Email -->
                      <div class="col-md-6">
                        <label for="reservation_email">Reservation Email</label>
                        <input name="reservation_email" id="reservation_email" type="text"  class="form-control" placeholder="Reservation Email" value="{{ $offer_details[0]->reservation_email or old('reservation_email') }}">
                        <span class="error">
                          <label for="reservation_email" class="error">{{ $errors->first('reservation_email') }}</label>
                        </span>
                      </div><!--Reservation Email-->
                      <!-- Reservation URL -->
                      <div class="col-md-6">
                        <label for="reservation_url">Reservation URL</label>
                        <input name="reservation_url" id="reservation_url" type="text"  class="form-control" placeholder="Reservation URL" value="{{ $offer_details[0]->reservation_url or old('reservation_url') }}">
                        <span class="error">
                          <label for="reservation_url" class="error">{{ $errors->first('reservation_url') }}</label>
                        </span>
                      </div><!--Reservation URL-->
                    </div>
                    <div class="row form-row">
                      <!-- How to book(en)-->
                      <div class="col-md-6">
                        <label for="how_to_book_en">How to book(en)</label>
                        <textarea class="form-control" placeholder="How to book in English" name="how_to_book_en" id="how_to_book_en" rows="5">{{ $offer_details[0]->how_to_book_en or old('how_to_book_en') }}</textarea>
                        <span class="error">
                          <label for="how_to_book_en" class="error">
                            {{ $errors->first('how_to_book_en') }}
                          </label>
                        </span>
                      </div><!-- How to book(en) ends-->
                      <!-- How to book(de)-->
                      <div class="col-md-6">
                        <label for="how_to_book_de">How to book(de)</label>
                        <textarea class="form-control" placeholder="How to book in German" name="how_to_book_de" id="how_to_book_de" rows="5">{{ $offer_details[0]->how_to_book_de or old('how_to_book_de') }}</textarea>
                        <span class="error">
                          <label for="how_to_book_de" class="error">
                            {{ $errors->first('how_to_book_de') }}
                          </label>
                        </span>
                      </div><!-- How to book(de) ends-->
                    </div>
                  </div><!--  Detailed Offer Info -->
                  <!--  Admin Info -->
                  <div role="tabpanel" class="tab-pane" id="admin_info">
                    <h3>Admin Info</h3>
                    <div class="row form-row">
                      <!-- Exclusive Advantage-->
                      <div class="col-md-6">
                        <label for="exclusive_advantage">Exclusive Advantage</label>
                        <textarea class="form-control" placeholder="Exclusive Advantage" name="exclusive_advantage" id="exclusive_advantage" rows="5">{{ $offer_details[0]->exclusive_advantage or old('exclusive_advantage') }}</textarea>
                        <span class="error">
                          <label for="exclusive_advantage" class="error">
                            {{ $errors->first('exclusive_advantage') }}
                          </label>
                        </span>
                      </div><!-- Exclusive Advantage-->
                      <!-- Note-->
                      <div class="col-md-6">
                        <label for="note">Note</label>
                        <textarea class="form-control" placeholder="Enter Note" name="note" id="note" rows="5">{{ $offer_details[0]->note or old('note') }}</textarea>
                        <span class="error">
                          <label for="note" class="error">
                            {{ $errors->first('note') }}
                          </label>
                        </span>
                      </div><!-- Note-->
                    </div>
                    <div class="row form-row">
                      <!-- JI Contacts -->
                      <div class="col-md-6">
                        <label for="offer_ji_contact_select">Select JI Contact</label>
                        <select name="ji_contact_id" id="offer_ji_contact_select" class="select2 form-control"  >
                          <option value="">Select Below</option>
                          @foreach($ji_users as $user)
                            <option value="{{ $user->id }}"
                              @if(isset($offer_details))
                                @if($offer_details[0]->ji_contact_id == $user->id)
                                  selected="selected"
                                @endif
                              @else
                                @if(old('ji_contact_id') == $user->id)
                                  selected="selected"
                                @endif
                              @endif
                            >{{ $user->first_name.' '.$user->last_name }}</option>
                          @endforeach
                        </select>
                        <span class="error">
                          <label for="ji_contact_id" class="error">{{ $errors->first('ji_contact_id') }}</label>
                        </span>
                      </div><!--JI Contacts-->
                      <!-- Secondary JI User(s) -->
                      <div class="col-md-6" id="secondary_users_wrapper">
                        @include('offers.select_secondary_users_partial');
                      </div><!--Secondary JI User(s)-->
                    </div>
                    <div class="row form-row">
                      <!-- Contract Status -->
                      <div class="col-md-6">
                        <label for="offer_contract_status_select">Contract Status</label>
                        @if(!$contract_statuses->isEmpty())
                          <select name="contract_status_id" id="offer_contract_status_select" class="select2 form-control"  >
                            <option value="">Select Below</option>
                              @foreach($contract_statuses as $contract_status)
                                <option value="{{ $contract_status->id }}"
                                @if(isset($offer_details))
                                  @if($offer_details[0]->contract_status_id == $contract_status->id)
                                    selected="selected"
                                  @endif
                                @else
                                  @if(old('contract_status_id') == $contract_status->id)
                                    selected="selected"
                                  @endif
                                @endif
                                >{{ ucfirst(trans($contract_status->label)) }}</option>
                              @endforeach
                          </select>
                        @else
                          <p class="label">No matching records found.</p>
                        @endif
                        <span class="error">
                          <label for="contract_status_id" class="error">{{ $errors->first('contract_status_id') }}</label>
                        </span>
                      </div><!--Contract Status-->
                    </div>
                    <div class="row form-row">
                      <!-- Editoral Note -->
                      <div class="col-md-6">
                        <label for="editorial_note">Editorial Note</label>
                        <textarea class="form-control" placeholder="Editorial Note" name="editorial_note" id="editorial_note" rows="5">{{ $offer_details[0]->editorial_note or old('editorial_note') }}</textarea>
                        <span class="error">
                          <label for="editorial_note" class="error">
                            {{ $errors->first('editorial_note') }}
                          </label>
                        </span>
                      </div><!--Editoral Note-->
                      <!-- Offer Status -->
                      <div class="col-md-6" id="offer_status_checkbox">
                        <label for="">Offer Status
                        {{-- @foreach ($offer_details[0]->offer_status as $offer_status)
                          @if($offer_status->id)
                            {{ $offer_status->id }}
                            {{ $offer_status->pivot->type }}
                          @endif
                        @endfor --}}
                        </label>
                        @if(!$offer_statuses->isEmpty())
                          <?php $sn = 1; ?>
                          @foreach($offer_statuses as $offer_status)
                            <div class="checkbox check-success checkbox-circle form_checkbox">
                              <input id="offer_status_{{ $sn }}" value="{{ $offer_status->id }}" type="checkbox" name="offer_status[ids][]"
                              @if(isset($offer_details))
                                @foreach ($offer_details[0]->offer_status as $details_offer_status)
                                  @if($details_offer_status->id == $offer_status->id && $details_offer_status->pivot->type == 'yes')
                                    checked="checked"
                                  @endif
                                @endforeach
                              @else
                                @if(isset(old('offer_status')['ids']))
                                  @if(in_array($offer_status->id, old('offer_status')['ids']))
                                    checked="checked"
                                  @endif
                                @endif
                              @endif
                              >
                              <label for="offer_status_{{ $sn }}" class="offer_status_label">{{ ucfirst(trans($offer_status->label)) }}</label>
                            </div>
                            <?php $sn = $sn+1; ?>
                            <div class="checkbox check-success checkbox-circle form_checkbox">
                              <input id="offer_status_{{ $sn }}" value="{{ $offer_status->id }}" type="checkbox" name="offer_status[na][]"
                              @if(isset($offer_details))
                                @foreach ($offer_details[0]->offer_status as $details_offer_status)
                                  @if($details_offer_status->id == $offer_status->id && $details_offer_status->pivot->type == 'na')
                                    checked="checked"
                                  @endif
                                @endforeach
                              @else
                                @if(isset(old('offer_status')['na']))
                                  @if(in_array($offer_status->id, old('offer_status')['na']))
                                    checked="checked"
                                  @endif
                                @endif
                              @endif
                              >
                              <label for="offer_status_{{ $sn }}" class="offer_status_na_label">N/A</label>
                            </div>
                            <div class="clearfix"></div>
                            <?php $sn = $sn+1; ?>
                          @endforeach
                        @else
                          <p class="label">No matching records found.</p>
                        @endif
                        <span class="error">
                          <label for="offer_status" class="error">{{ $errors->first('offer_status') }}</label>
                        </span>
                      </div><!--Offer Status-->
                    </div>
                  </div><!--  Admin Info -->
                  <!--Communication Package-->
                  <div role="tabpanel" class="tab-pane" id="communication_package">
                    <div class="row form-row">
                      <div class="col-md-8">
                        <label>Will this offer receive the communication package?</label>
                        <div class="radio radio-success">
                          <input id="has_communication_package" type="radio" name="has_communication_package" value="yes"
                          @if(isset($offer_details))
                            @if($offer_details[0]->has_communication_package == 'yes')
                              checked="checked"
                            @endif
                          @else
                            @if(old('has_communication_package') == 'yes')
                              checked="checked"
                            @endif
                          @endif
                          >
                          <label for="has_communication_package" class="offer_communication_radio">Yes</label>
                          <input id="no_communication_package" type="radio" name="has_communication_package" value="no"
                          @if(isset($offer_details))
                            @if($offer_details[0]->has_communication_package == 'no')
                              checked="checked"
                            @endif
                          @else
                            @if(old('has_communication_package') == 'no')
                              checked="checked"
                            @elseif (old('has_communication_package') != 'yes')
                              checked="checked"
                            @endif
                          @endif
                          >
                          <label for="no_communication_package" class="offer_communication_radio">No</label>
                        </div>
                        <span class="error">
                          <label for="has_communication_package" class="error">{{ $errors->first('has_communication_package') }}</label>
                        </span>
                      </div>
                    </div>
                  </div><!--Communication Package ends-->
                  <!--form actions-->
                  <div class="form-actions" style="margin-bottom:0;">
                    @if(isset($offer_details))
                      <input type="hidden" name="offer_id" value="{{ $offer_details[0]->id }}">
                      <input type="hidden" name="old_has_communication_package" value="{{ $offer_details[0]->has_communication_package }}">
                    @endif
                    <button class="btn btn-danger btn-cons" type="submit">SAVE OFFER</button>
                    <button class="btn btn-danger btn-cons body-link" type="button" data-controller="offer" data-mode="index">CANCEL</button>
                  </div><!--form actions-->
                </div>
                <!--<input type="submit" name="offer_submit" class="btn btn-danger btn-cons btn2" value="">-->
              </form>
            </div><!-- /.tabpanel -->
          </div><!-- /.span12 -->
        </div>
      </div><!--second-->
    </div>




    <!-- =====================================Modal to add offer placement=============================================== -->
    <div class="modal fade" id="add_new_placement" role="dialog" tabindex="-1" aria-labelledby="myModal1" aria-hidden="true" style="display: none;">
      <div class="modal-dialog" >
        @include('offers.add_placement_modal')
      </div><!-- /.modal-dialog -->
    </div>
    <!-- =====================================Modal to add new placement=============================================== -->

    <!-- =====================================Modal to add offer-project partner============================================ -->
    <!-- <div class="modal fade" id="add_new_partner" role="dialog" tabindex="-1" aria-labelledby="myModal2" aria-hidden="true" style="display: none;">
      <div class="modal-dialog" >
        {{--@include('offers.add_partner_modal')--}}
      </div>/.modal-dialog
    </div> -->
    <!-- ===================================Modal to add offer-project partner=============================================== -->

    <!-- =====================================Modal to list partners=========================================== -->
   <div class="modal fade" id="list_offer_partners" role="dialog" tabindex="-1" aria-labelledby="myModal3" aria-hidden="true" style="display: none;">
     <div class="modal-dialog" id="list_offer_partners_dialog">
       @include('offers.partners_list_modal')
     </div>
   </div>
    <!-- ===================================Modal to list partners============================================== -->

    <!-- =====================================Modal to assign partner contacts=========================================== -->
    <div class="modal fade" id="assign_offer_partners" role="dialog" tabindex="-1" aria-labelledby="myModal4" aria-hidden="true" style="display: none;">
      <div class="modal-dialog" id="assign_partner_dialog">
        @include('offers.assign_contact_to_modal')
      </div><!-- /.modal-dialog -->
    </div>
    <!-- ===================================Modal to assign partner contacts============================================== -->




<script type="text/javascript">
  (function(){
    project_id = $('#offer_project_select').val();
    if(project_id != ''){
      $.ajax({
        url: 'offer/form-data-by-project/'+project_id,
        type: "GET",
        success: function(data){
          if(typeof(data.exc) !== 'undefined'){
            alert('Whoops! Your request can not be performed at this time. Please try again later.');
          }
          else{
            $('#select_project_view_wrapper').html(data);
          }
          return false;
        }
      });
      $.ajax({
        url: 'offer/project-ji-users/'+project_id,
        type: "GET",
        success: function(data){
          if(typeof(data.exc) !== 'undefined'){
            alert('Whoops! Your request can not be performed at this time. Please try again later.');
          }
          else{
            select_na = '<option value="">Select Below</option>';
            select_na_disabled = '<option value="" disabled="disabled">N/A</option>';
            $('#offer_ji_contact_select').html(select_na);
            $('#offer_secondary_jiuser_select').html('');
            var obj = jQuery.parseJSON(data);
            $.each(obj, function(key, value){
              option_value_primary = '<option value="'+value.id+'">'+value.first_name+' '+value.last_name+"</option>";
              option_value_secondary = '<option value="'+value.id+'">'+value.first_name+' '+value.last_name+"</option>";
              <?php if(isset($offer_details)){ ?>
                if(value.id == <?php echo $offer_details[0]->ji_contact_id ?>){
                    option_value_primary = '<option value="'+value.id+'" selected="selected">'+value.first_name+' '+value.last_name+"</option>";
                  }
              <?php } else{
                if(old('ji_contact_id')){ ?>
                  if(value.id == <?php echo old('ji_contact_id') ?>){
                    option_value_primary = '<option value="'+value.id+'" selected="selected">'+value.first_name+' '+value.last_name+"</option>";
                  }
              <?php }} ?>
              $('#offer_ji_contact_select').append(option_value_primary);
              $('#offer_secondary_jiuser_select').append(option_value_secondary);
            });

          }
          return false;
        }
      });
    }else{
      // na_option = '<p class="label">No matching records found.</p>'
      // $('#offer_placement_select_wrapper, #offer_partner_select_wrapper, #offer_offer_tc_select_wrapper, #offer_general_ot_select_wrapper, #offer_detailed_ot_select_wrapper').html(na_option);
      select_na = '<option value="">Select Below</option>';
      select_na_disabled = '<option value="" disabled="disabled">N/A</option>';
      na_option = '<p class="label">No matching records found.</p>'
      $('#offer_placement_select_wrapper, #offer_partner_select_wrapper, #offer_offer_tc_select_wrapper, #offer_general_ot_select_wrapper, #offer_detailed_ot_select_wrapper, #offer_partner_contact_select_wrapper').html(na_option);
      $('#offer_ji_contact_select').html(select_na);
      $('#offer_secondary_jiuser_select').html(select_na_disabled);
    }

    //set form-tab active on validation error
    <?php if(!empty(session('errors')) || !($errors->isEmpty()) || isset($placement_notice)){ ?>
    var tab_one_inputs = ['title', 'project_id', 'placement_ids', 'partner_id', 'offer_type_category_masterlist_id', 'general_offer_type_masterlist_id', 'detailed_offer_type_masterlist_id', 'contact_id'];
    var tab_two_inputs = ['location', 'valid_from', 'valid_until', 'street1', 'street2', 'street3', 'postal_code', 'city', 'county', 'state', 'country_id', 'market', 'price', 'offer_en', 'offer_de', 'benefit_en', 'benefit_de', 'further_information', 'reservation_telephone', 'reservation_fax', 'reservation_email', 'reservation_url', 'how_to_book'];
    var tab_three_inputs = ['exclusive_advantage', 'note', 'ji_contact_id', 'ji_user_ids', 'contract_status_id', 'editorial_note', 'offer_status'];
    var tab_four_inputs = ['has_communication_package'];
    $('span.error').each(function(e){
      $this = $(this);
      label = $this.children('label');
      error_text = label.text();
      for_attribute = label.attr('for');
      if($.trim(error_text).length > 0){
        flag = $.inArray(for_attribute, tab_one_inputs);
        if(flag >= 0){
          flag = 1;
          return false;
        }
        else{
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
            }else{
              flag = $.inArray(for_attribute, tab_four_inputs);
              if(flag >= 0){
                flag = 4;
                return false;
              }
            }
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
      case 4:
        $('#tab_four_li').children('a').click();
        break;
      default:
        //code
    }
    <?php } ?>

    //Select Secondary ji users
    ji_contact_id = $('#offer_ji_contact_select').val();
    if(ji_contact_id == ''){
      ji_contact_id = 0;
    }
    if(project_id != ''){
      $.ajax({
        url: 'offer/secondary-users-data/'+ji_contact_id+'/'+project_id,
        type: "GET",
        success: function(data){
          if(typeof(data.exc) !== 'undefined'){
            alert('Whoops! Your request can not be performed at this time. Please try again later.');
          }
          else{
            $('#secondary_users_wrapper').html(data);
          }
          return false;
        }
      });
    }
  })();
</script>

<?php
  //echo "<pre>";
  //print_r(session()->all());
?>