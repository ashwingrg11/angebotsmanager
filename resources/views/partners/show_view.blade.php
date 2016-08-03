<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Partners</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Partner Details Summary</h3>
        </div>
        <div class="grid-body ">
          <div class="row">
            <div class="col-md-12">
              <p><strong>Partner Name:</strong> {{ $partner_info[0]->partner_name }}</p>
              <!--Region-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Region:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$partner_info[0]->region or 'N/A'}}</p>
                </div>
              </div><!--Region ends-->
              <!--Notes-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Notes:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$partner_info[0]->notes or 'N/A'}}</p>
                </div>
              </div><!--Notes ends-->
              <!--Contacts-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Contacts:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  @if($partner_info[0]->contacts->isEmpty())
                    <p>N/A</p>
                  @else
                    <p>
                      <?php $sn = 1; ?>
                      @foreach ($partner_info[0]->contacts as $contact)
                        {{$sn.') '}}
                        <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$contact->id}}">{{$contact->first_name.' '.$contact->last_name}}</a><br>
                      <?php $sn++; ?>
                      @endforeach
                    </p>
                  @endif
                </div>
              </div><!--Contacts ends-->
              <!--Projects-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Projects:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  @if($partner_info[0]->projects->isEmpty())
                    <p>N/A</p>
                  @else
                    <p>
                      <?php $sn = 1; ?>
                      @foreach ($partner_info[0]->projects as $project)
                        {{$sn.') '}}
                        @if(Auth::user()->user_type == "general")
                          @if(Helper::verifyUserProject($project->id))
                            <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{$project->name}}</a><br>
                          @else
                            {{$project->name}}<br>
                          @endif
                        @else
                          <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{$project->name}}</a><br>
                        @endif
                      <?php $sn++; ?>
                      @endforeach
                    </p>
                  @endif
                </div>
              </div><!--Projects ends-->
               <!--Offers-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Offers:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  @if($partner_info[0]->offers->isEmpty())
                    <p>N/A</p>
                  @else
                    <p>
                      <?php $sn=1; ?>
                      @foreach($partner_info[0]->offers as $offer)
                        {{$sn.') '}}
                        @if (Auth::user()->user_type == "general")
                          @if(Helper::verifyUserProject($offer->project_id))
                            <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">
                              @if($offer->title_en)
                                {{$offer->title_en}}
                              @else
                                {{$offer->title_de}}
                              @endif
                            </a><br>
                          @else
                            @if($offer->title_en)
                              {{$offer->title_en}}<br>
                            @else
                              {{$offer->title_de}}<br>
                            @endif
                          @endif
                        @else
                          <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">
                            @if($offer->title_en)
                              {{$offer->title_en}}
                            @else
                              {{$offer->title_de}}
                            @endif
                          </a><br>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    </p>
                  @endif
                </div>
              </div><!--Offers ends-->
              <hr>
              <h4>Address Info.</h4>
              <!--Street1-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street1:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$partner_info[0]->address->street1 or 'N/A'}}</p>
                </div>
              </div><!--Street1 ends-->
              <!--Street2-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street2:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$partner_info[0]->address->street2 or 'N/A'}}</p>
                </div>
              </div><!--Street2 ends-->
              <!--Street3-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street3:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$partner_info[0]->address->street3 or 'N/A'}}</p>
                </div>
              </div><!--Street3 ends-->
              <p><strong>Post Code:</strong> {{ $partner_info[0]->address->post_code or 'N/A' }}</p>
              <p><strong>City:</strong> {{ $partner_info[0]->address->city or 'N/A' }}</p>
              <p><strong>County:</strong> {{ $partner_info[0]->address->county or 'N/A' }}</p>
              <p><strong>State:</strong> {{ $partner_info[0]->address->state or 'N/A' }}</p>
              <!--country-->
              <p><strong>Country:</strong>
                @if($partner_info[0]->address->countries)
                  {{$partner_info[0]->address->countries->label}}
                @else
                  N/A
                @endif
              </p><!--country ends-->
              <!--Display Address-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Display Address:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($partner_info[0]->address->display_address)
                      {!! nl2br(e($partner_info[0]->address->display_address)) !!}
                    @else
                      N/A
                    @endif
                  </p>
                </div>
              </div><!--Display Address ends-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>