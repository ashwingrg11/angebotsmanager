<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Clients</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Client Details Summary</h3>
        </div>
        <div class="grid-body ">
          <div class="row">
            <div class="col-md-12">
              <p><strong>Client Name:</strong> {{ $client_info[0]->name }}</p>
              <!--Region-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Region:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$client_info[0]->region or 'N/A'}}</p>
                </div>
              </div><!--Region ends-->
              <!--Notes-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Notes:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$client_info[0]->notes or 'N/A'}}</p>
                </div>
              </div><!--Notes ends-->
              <!--Contacts-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Contacts:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  @if($client_info[0]->contacts->isEmpty())
                    <p>N/A</p>
                  @else
                    <p>
                      <?php $sn = 1; ?>
                      @foreach ($client_info[0]->contacts as $contact)
                        <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$contact->id}}">{{$sn.') '}}{{$contact->first_name.' '.$contact->last_name}}</a><br>
                      <?php $sn++; ?>
                      @endforeach
                    </p>
                  @endif
                </div>
              </div><!--Contacts ends-->
              <hr>
              <h4>Address Info.</h4>
              <!--Street1-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street1:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$client_info[0]->address->street1 or 'N/A'}}</p>
                </div>
              </div><!--Street1 ends-->
              <!--Street2-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street2:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$client_info[0]->address->street2 or 'N/A'}}</p>
                </div>
              </div><!--Street2 ends-->
              <!--Street3-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street3:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$client_info[0]->address->street3 or 'N/A'}}</p>
                </div>
              </div><!--Street3 ends-->
              <p><strong>Post Code:</strong> {{ $client_info[0]->address->post_code or 'N/A' }}</p>
              <p><strong>City:</strong> {{ $client_info[0]->address->city or 'N/A' }}</p>
              <p><strong>County:</strong> {{ $client_info[0]->address->county or 'N/A' }}</p>
              <p><strong>State:</strong> {{ $client_info[0]->address->state or 'N/A' }}</p>
              <!--country-->
              <p><strong>Country:</strong>
                @if($client_info[0]->address->countries)
                  {{$client_info[0]->address->countries->label}}
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
                    @if($client_info[0]->address->display_address)
                      {!! nl2br(e($client_info[0]->address->display_address)) !!}
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