<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Projects</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      @include('shared.notifications')
      <div class="grid simple ">
        <div class="grid-title">
          <h3>Project Details Summary</h3>
        </div>
        <div class="grid-body ">
          <div class="row">
            <div class="col-md-8">
              <p><strong>Project Name:</strong> {{ $project_info[0]->name }}</p>
              <p>
                <strong>Client:</strong>
                @if(Auth::user()->user_type == "viewer")
                  {{$project_info[0]->clients->name}}
                @else
                  <a href="javascript:;" class="body-link" data-controller="client" data-mode="show" data-param="{{$project_info[0]->clients->id}}">{{$project_info[0]->clients->name}}</a>
                @endif
              </p>
              <p><strong>Project Description:</strong> {{ $project_info[0]->description }}</p>
              <p>
                <strong>First Issue Launch Date:</strong>
                {{$project_info[0]->first_issue_launch_date or 'N/A'}}
              </p>
              <p><strong>Circulation:</strong> {{ $project_info[0]->circulation or 'N/A' }}</p>
              <!--Client Contact-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Client Contact:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    <?php $sn = 1 ; ?>
                    @foreach ($project_info[0]->contacts as $contact)
                      @if(Auth::user()->user_type == "viewer")
                        {{ $sn.') '.$contact->first_name.' '.$contact->last_name }}
                      @else
                        <a href="javascript:;" class="body-link" data-controller="contact" data-mode="show" data-param="{{$contact->id}}">{{ $sn.') '.$contact->first_name.' '.$contact->last_name }}</a><br>
                      @endif
                      <?php $sn++; ?>
                    @endforeach
                  </p>
                </div>
              </div><!--Client Contacat ends-->
              <!--JI Contact-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>JI Contact:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    <?php $sn = 1 ; ?>
                    @foreach ($project_info[0]->users as $user)
                      @if(Auth::user()->user_type == "viewer")
                        {{ $sn.') '.$user->first_name.' '.$user->last_name }}
                      @else
                        <a href="javascript:;" class="body-link" data-controller="user" data-mode="show" data-param="{{$user->id}}">{{ $sn.') '.$user->first_name.' '.$user->last_name }}</a><br>
                      @endif

                      <?php $sn++; ?>
                    @endforeach
                  </p>
                </div>
              </div><!--JI Contacat-->
              <!--Project Categories-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Categories:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  @if($project_info[0]->categories->isEmpty())
                    N/A
                  @else
                    <p>
                      <?php $sn = 1 ; ?>
                      @foreach ($project_info[0]->categories as $category)
                        {{ $sn.') '.$category->label }}<br>
                        <?php $sn++; ?>
                      @endforeach
                    </p>
                  @endif
                </div>
              </div><!--Project Categories end here-->
              <!--Languages-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Language:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>{{ $project_info[0]->languages->label }}</p>
                </div>
              </div><!--Languages end here-->
              <p><strong>Note:</strong> {{ $project_info[0]->note or 'N/A' }}</p>
            </div>
              @if($project_info[0]->logo)
                <div class="col-md-4 show_projectlogo_wrapper" style="border-left: 1px solid #dddddd;">
                  @if(Auth::user()->user_type == "admin")
                    <h4><a href="javascript:;" style="text-decoration: underline; color: #c61639;" data-mode="delete-project-logo" data-controller="project" data-id="{{$project_info[0]->id}}" id="show_projectlogo_delete">Delete Logo</a></h4>
                  @endif
                  <img src="{{ asset('images/projects').'/'.$project_info[0]->logo }}" class="img-responsive">
                </div>
              @else
              <div class="col-md-4 show_projectlogo_wrapper"></div>
              @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>