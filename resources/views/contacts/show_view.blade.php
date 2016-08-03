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
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Contact Details Summary</h3>
        </div>
        <div class="grid-body ">
          <div class="row">
            <div class="col-md-12">
              <p><strong>Language:</strong> {{ $contact_info[0]->languages->label }}</p>
              <p><strong>Title:</strong> {{ ucfirst($contact_info[0]->title) }}</p>
              <p><strong>First Name:</strong> {{ $contact_info[0]->first_name or 'N/A' }}</p>
              <p><strong>Last Name:</strong> {{ $contact_info[0]->last_name or 'N/A' }}</p>
              <!--Greeting-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Greeting:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$contact_info[0]->greeting}}</p>
                </div>
              </div><!--Greeting ends-->
              <p><strong>Company:</strong> {{ $contact_info[0]->company }}</p>
              <p><strong>Department:</strong> {{ $contact_info[0]->department or 'N/A' }}</p>
              <p><strong>Job Title:</strong> {{ $contact_info[0]->job_title or 'N/A' }}</p>
              <p><strong>E-mail:</strong> {{ $contact_info[0]->email }}</p>
              <p><strong>Phone:</strong> {{ $contact_info[0]->phone or 'N/A' }}</p>
              <p><strong>Mobile Phone:</strong> {{ $contact_info[0]->mobile_phone or 'N/A' }}</p>
              <!--Notes-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Notes:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$contact_info[0]->notes}}</p>
                </div>
              </div><!--Notes-->
              <!--partners-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Partners:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($contact_info[0]->partners->isEmpty())
                      N/A
                    @else
                      <?php $sn=1; $project_ids = Helper::getUserProjectIds(); $contact_projects = []; $flag_project_ids = []; ?>
                      @foreach ($contact_info[0]->partners as $key => $value)
                        <?php $partner_flag = false; ?>
                        @foreach ($value->projects as $partner_project)
                          @if(in_array($partner_project->id, $project_ids))
                            <?php $partner_flag = true; ?>
                          @endif
                          <?php if(!in_array($partner_project->id, $flag_project_ids)){
                            $flag_project_ids[] = $partner_project->id;
                            $contact_projects[] = $partner_project->name;
                          }?>
                        @endforeach
                        @if(Auth::user()->user_type == "general" && $partner_flag == false)
                          {{$sn.') '.$value->partner_name}}<br>
                        @else
                          <a href="javascript:;" class="body-link" data-controller="partner" data-mode="show" data-param="{{$value->id}}">{{$sn.') '.$value->partner_name}}</a><br>
                        @endif
                        <?php $sn++; ?>
                      @endforeach

                    @endif
                  </p>
                </div>
              </div><!--partners-->
              <!--clients-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Clients:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($contact_info[0]->clients->isEmpty())
                      N/A
                    @else
                      <?php $sn = 1; ?>
                      @foreach($contact_info[0]->clients as $client)
                        @if(Auth::user()->user_type == "general")
                          @if(Helper::verifyUserClient($client->id))
                            <a href="javascript:;" class="body-link" data-controller="client" data-mode="show" data-param="{{$client->id}}">{{$sn.') '.$client->name}}</a><br>
                          @else
                            {{$sn.') '.$client->name}}
                          @endif
                        @else
                          {{$sn.') '}}
                          <a href="javascript:;" class="body-link" data-controller="client" data-mode="show" data-param="{{$client->id}}">{{$client->name}}</a><br>
                          <?php $sn++; ?>
                        @endif
                      @endforeach
                    @endif
                  </p>
                </div>
              </div><!--clients-->
              <!--projects-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Projects:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if(empty($contact_projects))
                      N/A
                    @else
                      <?php $sn = 1; ?>
                      @foreach($contact_projects as $key => $project)
                        @if(Auth::user()->user_type == "general")
                          @if(Helper::verifyUserProject($flag_project_ids[$key]))
                            <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$flag_project_ids[$key]}}">{{$sn.') '.$project}} </a><br>
                          @else
                            {{$sn.') '.$project}}<br>
                          @endif
                        @else
                          <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$flag_project_ids[$key]}}">{{$sn.') '.$project}} </a><br>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    @endif
                  </p>
                </div>
              </div><!--projects-->
              <!--offers-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Offers:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($contact_info[0]->offers->isEmpty())
                      N/A
                    @else
                      <?php $sn = 1; ?>
                      @foreach($contact_info[0]->offers as $offer)
                        @if(Auth::user()->user_type == "general")
                          @if(Helper::verifyUserProject($offer->project_id))
                            <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">{{$sn.') '}}{{$offer->title_en or $offer->title_de}} </a><br>
                          @else
                          {{$sn.') '}}{{$offer->title_en or $offer->title_de}} <br>
                          @endif
                        @else
                          <a href="javascript:;" class="body-link" data-controller="offer" data-mode="show" data-param="{{$offer->id}}">{{$sn.') '}}{{$offer->title_en or $offer->title_de}} </a><br>
                        @endif
                        <?php $sn++; ?>
                      @endforeach
                    @endif
                  </p>
                </div>
              </div><!--offers-->
              <hr>
              <h4>Address Info.</h4>
              <!--Street1-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street1:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$contact_info[0]->address->street1 or 'N/A'}}</p>
                </div>
              </div><!--Street1 ends-->
              <!--Street2-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street2:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$contact_info[0]->address->street2 or 'N/A'}}</p>
                </div>
              </div><!--Street2 ends-->
              <!--Street3-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Street3:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{$contact_info[0]->address->street3 or 'N/A'}}</p>
                </div>
              </div><!--Street3 ends-->
              <p><strong>Post Code:</strong> {{ $contact_info[0]->address->post_code or 'N/A' }}</p>
              <p><strong>City:</strong> {{ $contact_info[0]->address->city or 'N/A' }}</p>
              <p><strong>County:</strong> {{ $contact_info[0]->address->county or 'N/A' }}</p>
              <p><strong>State:</strong> {{ $contact_info[0]->address->state or 'N/A' }}</p>
              <!--country-->
              <p><strong>Country:</strong>
                @if($contact_info[0]->address->countries)
                  {{$contact_info[0]->address->countries->label}}
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
                    @if($contact_info[0]->address->display_address)
                      {!! nl2br(e($contact_info[0]->address->display_address)) !!}
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