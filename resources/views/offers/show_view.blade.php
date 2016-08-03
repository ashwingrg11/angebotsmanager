<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Offers</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Offer Details Summary</h3>
        </div>
        <div class="grid-body">
          <div class="row">
            <div class="col-md-12">
                <h4>General Info.</h4>
                <p><strong>Offer Title(en):</strong> {{ $offer_info[0]->title_en or 'N/A' }}</p>
                <p><strong>Offer Title(de):</strong> {{ $offer_info[0]->title_de or 'N/A' }}</p>
                <p>
                  <strong>Project: </strong>
                  @if(Helper::verifyUserProject($offer_info[0]->projects->id))
                    <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$offer_info[0]->projects->id}}">{{$offer_info[0]->projects->name}}</a>
                  @else
                    {{$offer_info[0]->projects->name}}
                  @endif
                </p>
                <!--placement-->
                <div class="col-md-1" style="padding:0px;">
                  <p><strong>Placement(s):</strong></p>
                </div>
                <div class="col-md-11">
                  <?php $sn = 1; ?>
                  @foreach($offer_info[0]->placements as $placement)
                    <p>
                      {{ $sn.') Title: '}}
                      <a href="javascript:;" class="body-link" data-controller="placement" data-mode="show" data-param="{{$placement->id}}">{{$placement->title}}</a>
                      {{', Start Date: '.$placement->pivot->placement_start_date.', End Date: '.$placement->pivot->placement_end_date }}
                    </p>
                  <?php $sn += 1; ?>
                  @endforeach
                </div>
                <!--placement-->
                <p>
                  <strong>Partner:</strong>
                  @if(Auth::user()->user_type == "viewer")
                    {{$offer_info[0]->partners->partner_name}}
                  @else
                    <a href="javascript:;" class="body-link" data-controller="partner" data-mode="show" data-param="{{$offer_info[0]->partners->id}}">{{$offer_info[0]->partners->partner_name}}</a>
                  @endif
                </p>
                <p>
                  <strong>Partner Contact:</strong>
                  @if(Auth::user()->user_type == "viewer")
                    {{$offer_info[0]->contacts->first_name.' '.$offer_info[0]->contacts->last_name}}
                  @else
                    <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$offer_info[0]->contacts->id}}">{{$offer_info[0]->contacts->first_name.' '.$offer_info[0]->contacts->last_name}}</a>
                  @endif
                </p>
                <p><strong>General Offer Type:</strong> {{ $offer_info[0]->general_offer_type_masterlist->label or 'N/A'}}</p>
                <p><strong>Detailed Offer Type:</strong> {{ $offer_info[0]->detailed_offer_type_masterlist->label or 'N/A'}}</p>
                <p><strong>Offer Type Category:</strong> {{ $offer_info[0]->offer_type_category_masterlist->label or 'N/A'}}</p><hr>
                <h4>Detailed Info.</h4>
                <p><strong>Location:</strong> {{ $offer_info[0]->location or 'N/A' }}</p>
                <p><strong>Valid From:</strong> {{ Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_from)->format('Y-m-d') }}</p>
                <p><strong>Valid Until:</strong> {{ Carbon\Carbon::createFromFormat('d/m/Y', $offer_info[0]->valid_until)->format('Y-m-d') }}</p>
                <p><strong>Street 1:</strong> {{ $offer_info[0]->street1 or 'N/A' }}</p>
                <p><strong>Street 2:</strong> {{ $offer_info[0]->street2 or 'N/A' }}</p>
                <p><strong>Street 3:</strong> {{ $offer_info[0]->street3 or 'N/A' }}</p>
                <p><strong>Postal Code:</strong> {{ $offer_info[0]->postal_code or 'N/A' }}</p>
                <p><strong>City:</strong> {{ $offer_info[0]->city or 'N/A' }}</p>
                <p><strong>County:</strong> {{ $offer_info[0]->county or 'N/A' }}</p>
                <p><strong>State:</strong> {{ $offer_info[0]->state or 'N/A' }}</p>
                <p><strong>Country:</strong> {{ $offer_info[0]->countries->label or 'N/A' }}</p>
                <p><strong>Market:</strong> {{ $offer_info[0]->market or 'N/A' }}</p>
                <!--Offer(en)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Offer(en):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->offer_en)
                        {!! nl2br(e($offer_info[0]->offer_en)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Offer(en)-->
                <!--Offer(de)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Offer(de):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->offer_de)
                        {!! nl2br(e($offer_info[0]->offer_de)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Offer(de)-->
                <!--Privilege/Benefit(en)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Privilege/Benefit(en):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->benefit_en)
                        {!! nl2br(e($offer_info[0]->benefit_en)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Privilege/Benefit(en)-->
                <!--Privilege/Benefit(de)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Privilege/Benefit(de):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->benefit_de)
                        {!! nl2br(e($offer_info[0]->benefit_de)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Privilege/Benefit(de)-->
                <!--Price(en)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Price(en):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->price_en)
                        {!! nl2br(e($offer_info[0]->price_en)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Price(en)-->
                <!--Price(de)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Price(de):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->price_de)
                        {!! nl2br(e($offer_info[0]->price_de)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Price(de)-->
                <!--further information en-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Further Information(en):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->further_information_en)
                        {!! nl2br(e($offer_info[0]->further_information_en)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--further information en ends-->
                <!--further information de-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Further Information(de):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->further_information_de)
                        {!! nl2br(e($offer_info[0]->further_information_de)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--further information de ends-->
                <p><strong>Reservation Telephone:</strong> {{ $offer_info[0]->reservation_telephone or 'N/A' }}</p>
                <p><strong>Reservation Fax:</strong> {{ $offer_info[0]->reservation_fax or 'N/A' }}</p>
                <p><strong>Reservation Email:</strong> {{ $offer_info[0]->reservation_email or 'N/A' }}</p>
                <p><strong>Reservation URL:</strong> {{ $offer_info[0]->reservation_url or 'N/A' }}</p>
                <!--How to book en-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>How to book(en):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->how_to_book_en)
                        {!! nl2br(e($offer_info[0]->how_to_book_en)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--How to book(en) ends-->
                <!--How to book(de)-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>How to book(de):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->how_to_book_de)
                        {!! nl2br(e($offer_info[0]->how_to_book_de)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--How to book(de) ends-->
                <hr>
                <h4>Admin Info.</h4>
                <!--Exclusive Advantage-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Exclusive Advantage:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->exclusive_advantage)
                        {!! nl2br(e($offer_info[0]->exclusive_advantage)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Exclusive Advantage-->
                <!--Editorial Note-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Editorial Note:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->editorial_note)
                        {!! nl2br(e($offer_info[0]->editorial_note)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Editorial Note-->
                <!--Note-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Note:&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    <p>
                      @if($offer_info[0]->note)
                        {!! nl2br(e($offer_info[0]->note)) !!}
                      @else
                        N/A
                      @endif
                    </p>
                  </div>
                </div><!--Note-->
                <p>
                  <strong>JI Contact:</strong>
                  @if(Auth::user()->user_type == "viewer")
                    {{ $offer_info[0]->ji_contact->first_name.' '.$offer_info[0]->ji_contact->last_name }}
                  @else
                    <a href="javascript:;" class="body-link" data-controller="user" data-mode="show" data-param="{{$offer_info[0]->ji_contact->id}}">{{ $offer_info[0]->ji_contact->first_name.' '.$offer_info[0]->ji_contact->last_name }}</a>
                  @endif
                </p>
                <!--Secondary JI Users-->
                <div class="content-wrapper">
                  <div class="left-text"><p><strong>Secondary JI User(s):&nbsp;</strong></p>
                  </div>
                  <div class="right-text">
                    @if(!$offer_info[0]->users->isEmpty())
                      <p>
                        <?php $sn = 1; ?>
                        @foreach ($offer_info[0]->users as $ji_user)
                          @if(Auth::user()->user_type == "viewer")
                            {{ $sn.') '.$ji_user->first_name.' '.$ji_user->last_name }}
                          @else
                            <a href="javascript:;" class="body-link" data-controller="user" data-mode="show" data-param="{{$ji_user->id}}">{{ $sn.') '.$ji_user->first_name.' '.$ji_user->last_name }}</a><br>
                          @endif

                          <?php $sn++; ?>
                        @endforeach
                      </p>
                    @else
                      <p>N/A</p>
                    @endif
                  </div>
                </div><!--Secondary JI Users-->
                <p><strong>Contract Status:</strong>
                  @if($offer_info[0]->contract_status_id)
                    {{ $offer_info[0]->contract_status->label }}
                  @else
                    N/A
                  @endif
                </p>
                <!--Offer Status-->
                <div class="col-md-1" style="padding:0px;">
                  <p><strong>Offer Status:</strong></p>
                </div>
                <div class="col-md-11">
                @if(!$offer_info[0]->offer_status->isEmpty())
                  <?php $sn = 1; ?>
                  @foreach($offer_info[0]->offer_status as $offer_status)
                    <p>
                      {{ $sn.') '.ucfirst($offer_status->label).' --' }}@if($offer_status->pivot->type == "na") {{ "N/A" }} @else {{ "Yes" }} @endif
                    </p>
                  <?php $sn += 1; ?>
                  @endforeach
                @else
                  <p>N/A</p>
                @endif
                </div><!--Offer Status-->
                <p><strong>Communication Package:</strong> {{ $offer_info[0]->has_communication_package }}</p>
            </div><!--col-md-12 ends-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>