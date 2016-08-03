<div class="content">
  <ul class="breadcrumb">
    <div class="page-title"> <i class="icon-custom-left"></i>
      <h3>
        <span class="go-back">Go <span class="semi-bold">Back</span></span>
      </h3>
    </div>
    <li>
      <p>Users</p>
    </li>
    <li><a href="#" class="active">Details Summary</a></li>
  </ul>
  <div class="row-fluid">
    <div class="span12">
      <div class="grid simple ">
        <div class="grid-title">
          <h3>User Details Summary</h3>
        </div>
        <div class="grid-body ">
          <div class="row">
            <div class="col-md-12">
              <p><strong>First Name:</strong> {{ $user_info[0]->first_name }}</p>
              <p><strong>Last Name:</strong> {{ $user_info[0]->last_name }}</p>
              <p><strong>User Type:</strong> {{ $user_info[0]->user_type }}</p>
              <p><strong>Email:</strong> {{ $user_info[0]->email }}</p>
              <p><strong>Telephone:</strong> {{ $user_info[0]->telephone or 'N/A' }}</p>
              <p><strong>Username:</strong> {{ $user_info[0]->username }}</p>
              <!--projects-->
              <div class="content-wrapper">
                <div class="left-text"><p><strong>Projects:&nbsp;</strong></p>
                </div>
                <div class="right-text">
                  <p>
                    @if($user_info[0]->projects->isEmpty())
                      N/A
                    @else
                      <?php $sn = 1; ?>
                      @foreach($user_info[0]->projects as $project)
                        @if(Helper::verifyUserProject($project->id))
                          <a href="javascript:;" class="body-link" data-controller="project" data-mode="show" data-param="{{$project->id}}">{{$sn.') '}}{{$project->name}}</a><br>
                        @else
                          {{$sn.') '}}{{$project->name}}<br>
                        @endif
                          <?php $sn++; ?>
                      @endforeach
                    @endif
                  </p>
                </div>
              </div><!--projects-->
            </div><!--.row-->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>