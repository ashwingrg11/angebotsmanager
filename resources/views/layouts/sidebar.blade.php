`<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">
  <!-- BEGIN SIDEBAR -->
  <div class="page-sidebar left-sidebar" id="main-menu">
    <!-- BEGIN MINI-PROFILE -->
    <div class="page-sidebar-wrapper" id="main-menu-wrapper">
      <div class="user-info-wrapper">
        <div class="user-info left-sidebar">
          <div class="greeting">Welcome</div>
          <div class="username">{{ Auth::user()->first_name.' '.Auth::user()->last_name }}</div>
        </div>
      </div>
      <!-- END MINI-PROFILE -->
      <!-- BEGIN SIDEBAR MENU -->
      <ul>
        <!-- Dashboard -->
        <li class="start active sub-menu dashboard_menu dashboard_active">
          <a href="{{ url('dashboard') }}"> <i class="icon-custom-home"></i>
            <span class="title">Dashboard</span>
            <span class="selected"></span>
            <span class="arrow open"></span>
          </a>
        </li>
        <!-- Dashboard -->
        <!-- Offers -->
        <li class="closed">
          <a href="javascript:;" class="main_link main_module_link" data-controller="offer" data-mode="index"> <i class="fa fa-list-alt"></i>
            <span class="title">Offers</span>
            <span class="arrow"></span>
          </a>
          <ul class="sub-menu">
            @if(Auth::user()->user_type != "viewer")
            <!-- Add Offer -->
            <li>
              <a href="javascript:;" data-controller="offer" class="link task_link" data-mode="create">Add Offer</a>
            </li><!-- Add Offer -->
            @endif
            <!-- List/Edit Offers-->
            <li>
              <a href="javascript:;" data-controller="offer" class="link task_link" data-mode="index">
                @if(Auth::user()->user_type == "viewer")
                  List Offers
                @else
                  List/Edit Offers
                @endif
              </a>
            </li>
            <!-- List/Edit Offers-->
            <li> <a href="javascript:;" class="link task_link" data-controller="offer_type" data-mode="list-all">Offer-Offer Types</a> </li>
          </ul>
        </li>
        <!-- Offers -->
        <!-- Placements -->
        <li class="closed">
          <a href="javascript:;" class="main_link main_module_link" data-controller="placement" data-mode="index"> <i class="fa fa-th-list"></i>
            <span class="title">Placements</span>
            <span class="arrow "></span>
          </a>
          <ul class="sub-menu">
            @if(Auth::user()->user_type != "viewer")
              <!-- Add Placement -->
              <li>
                <a href="javascript:;" data-controller="placement" class="link task_link" data-mode="create">Add Placement</a>
              </li>
              <!-- Add Placement -->
            @endif
            <!-- List/Edit Placements-->
            <li>
              <a href="javascript:;" data-controller="placement" class="link task_link" data-mode="index">List/Edit Placements</a>
            </li>
            <!-- List/Edit Placements-->
          </ul>
        </li><!-- Placements -->
        @if(Auth::user()->user_type != "viewer")
          <!-- Partners -->
          <li class="closed">
            {{-- <a href="javascript:;"> <i class="fa fa-male"></i> --}}
            <a href="javascript:;" class="main_link main_module_link" data-controller="partner" data-mode="index"> <i class="fa fa-users"></i>
              <span class="title">Partners</span>
              <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
              <!-- Add partner -->
              <li>
                <a href="javascript:;" data-controller="partner" data-mode="create" class="link task_link">Add partner</a>
              </li><!-- Add partner -->
              <!-- List/Edit Partners-->
              <li>
                <a href="javascript:;" data-controller="partner" data-mode="index" class="link task_link">List/Edit Partners</a>
              </li>
              <!-- List/Edit Partners-->
            </ul>
          </li>
          <!-- Partners -->
          <!-- Partner Emails -->
          <li class="closed">
            <a href="javascript:;" class="main_link main_module_link" data-controller="email" data-mode="queue-emails"> <i class="fa fa-envelope"></i>
              <span class="title">Partner Emails</span>
              <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
              <!-- Email Templates -->
              <li class=""> <a href="javascript:;" class="sub_task_link"> <span class="title">Email Templates</span> <span class="arrow"></span> </a>
                <ul style="display: none;" class="sub-menu">
                  @if(Auth::user()->user_type == "admin")
                    <li>
                      <a href="javascript:;" class="link task_link" data-controller="email-template" data-mode="create" >Add Template</a>
                    </li>
                  @endif
                  <li> <a href="javascript:;" class="link task_link" data-controller="email-template" data-mode="index" >List/Edit Templates</a> </li>
                </ul>
              </li><!-- Email Templates -->
              <!-- List/Edit Partner Emails -->
              <li>
                <a href="javascript:;" data-controller="email" data-mode="queue-emails" class="link task_link">List/Edit Queue Emails</a>
              </li><!-- List/Edit Partner Emails -->
              <!-- List/Edit Partner Emails -->
              <li>
                <a href="javascript:;" data-controller="email" data-mode="sent-emails" class="link task_link">List Sent Emails</a>
              </li>
              <!-- List/Edit Partner Emails -->
              <!-- Communication Package-->
              <li class=""> <a href="javascript:;" class="sub_task_link"> <span class="title">Communication Package</span> <span class="arrow"></span> </a>
                <ul style="display: none;" class="sub-menu">
                  @if(Auth::user()->user_type == "admin")
                    <li> <a href="javascript:;" class="link task_link" data-controller="communication-package" data-mode="create" >Modify Commn. Package Send Dates</a> </li>
                  @endif
                  <li> <a href="javascript:;" class="link task_link" data-controller="communication-package" data-mode="index" >List/Edit Commn. Package</a> </li>
                </ul>
              </li><!-- Communication Package -->
            </ul>
          </li><!-- Partner Emails -->
          <!-- Reporting -->
          <li class="closed">
            <a href="javascript:;" class="main_link main_module_link" data-controller="report" data-mode="microsite-feedback"> <i class="fa fa-align-left"></i>
              <span class="title">Reporting</span>
              <span class="arrow "></span>
            </a>
            <ul class="sub-menu">
              <!-- Activation Link Report-->
              <li>
                <a href="javascript:;" data-controller="report" data-mode="offer-activation" class="link task_link">Activation Link Report</a>
              </li>
              <!-- Activation Link Report-->
              <!-- Microsite Feedback Summary -->
              <li>
                <a href="javascript:;" data-controller="report" data-mode="microsite-feedback" class="link task_link">Microsite Feedback Summary</a>
              </li>
              <!-- Microsite Feedback Summary -->
              <li>
                <!-- <a href="javascript:;" data-controller="report" data-mode="microsite1-feedback" class="link">Microsite1 Feedback Summary</a> -->
              </li>
              <!-- Microsite Feedback Summary -->
              <!-- Microsite Feedback Summary -->
              <li>
                <a href="javascript:;" data-controller="report" data-mode="microsite2-feedback" class="link task_link">Microsite2 Feedback Summary</a>
              </li>
              <!-- Microsite Feedback Summary -->
              <!-- Offers Reporting -->
              <li>
                {{-- <a href="javascript:;" data-controller="dashboard" data-mode="test-excel" class="link">Offers Reporting</a> --}}
                <!-- <a href="{{--url('report/offer-month')--}}" data-controller="dashboard" data-mode="test-excel" target="_blank" class="">Offers Reporting</a> -->
                <a href="javascript:;" data-controller="report" data-mode="offer-month-report" class="link task_link">Offer Reporting</a>
              </li>
              <!-- Offers Reporting -->
              <!-- Call Center Briefing -->
              <li>
                <a href="javascript:;" data-controller="report" data-mode="export-call-center" class="link task_link">Call Center Briefing</a>
              </li>
              <!-- Call Center Briefing ends -->
              @if(Auth::user()->user_type == "admin")
                <!-- All Raw Data Export -->
                <li>
                  <a href="{{ url('report/raw-data-export') }}" target="_blank" class="">Raw Data Export</a>
                </li>
                <!-- All Raw Data Export -->
              @endif
              <!-- All Contacts Export -->
              <li>
                <a href="javascript:;" data-controller="report" data-mode="all-contacts-export" class="link task_link">All Contacts Export</a>
              </li>
                <!-- All Contacts Export -->
            </ul>
          </li>
          <!-- Partner Emails -->
          @if(Auth::user()->user_type == "admin" || Auth::user()->user_type == "general")
            <!-- Contacts -->
            <li class="closed">
              <a href="javascript:;" class="main_link main_module_link" data-controller="contact" data-mode="index"> <i class="fa fa-book"></i>
                <span class="title">Contacts</span>
                <span class="arrow "></span>
              </a>
              <ul class="sub-menu">
                <!-- Add Contact -->
                <li>
                  <a href="javascript:;" data-controller="contact" data-mode="create" class="link task_link">Add Contact</a>
                </li>
                <!-- Add Contact -->
                <!-- List/Edit Contacts -->
                <li>
                  <a href="javascript:;" data-controller="contact" data-mode="index" class="link task_link">List/Edit Contacts</a>
                </li>
                <!-- List/Edit Contacts -->
              </ul>
            </li>
            <!-- Contacts -->
          @endif
        @endif
        <!-- Additional Information/Settings -->
        <li class="closed"> <a href="javascript:;" class="main_link main_module_link add_info_menu" data-controller="project" data-mode="index">
          <i class="fa fa-file-text"></i> <span class="title">Add. Information</span> <span class="arrow"></span> </a>
          <ul style="display: none;" class="sub-menu">
            @if(Auth::user()->user_type == "admin" || Auth::user()->user_type == "general")
              @if(Auth::user()->user_type == "admin")
                <!-- Clients -->
                <li class=""> <a href="javascript:;" class="sub_task_link"> <span class="title">Clients</span> <span class="arrow"></span> </a>
                  <ul style="display: none;" class="sub-menu">
                    <li> <a href="javascript:;" class="link task_link" data-controller="client" data-mode="create" >Add Client</a> </li>
                    <li> <a href="javascript:;" class="link task_link" data-controller="client" data-mode="index" >List Clients</a> </li>
                  </ul>
                </li><!-- Clients -->
              @endif

            @endif
            <!-- Projects -->
            <li class=""> <a href="javascript:;" class="sub_task_link sub_task_projects"> <span class="title">Projects</span> <span class="arrow"></span> </a>
              <ul style="display: none;" class="sub-menu">
                @if(Auth::user()->user_type == "admin")
                  <li> <a href="javascript:;" class="link task_link" data-controller="project" data-mode="create">Add Project</a> </li>
                @endif
                <li> <a href="javascript:;" class="link task_link" data-controller="project" data-mode="index">List Projects</a> </li>
                @if(Auth::user()->user_type == "admin")
                  <li> <a href="javascript:;" class="link task_link" data-controller="category" data-mode="index">Project Categories</a> </li>
                @endif
              </ul>
            </li><!-- Projects -->
            @if(Auth::user()->user_type == "admin" || Auth::user()->user_type == "general")
              @if(Auth::user()->user_type == "admin")
                <!-- Users -->
                <li class=""> <a href="javascript:;" class="sub_task_link"> <span class="title">Users</span> <span class="arrow"></span> </a>
                  <ul style="display: none;" class="sub-menu">
                    <li> <a href="javascript:;" class="link task_link" data-controller="user" data-mode="create" >Add User</a> </li>
                    <li> <a href="javascript:;" class="link task_link" data-controller="user" data-mode="index" >List Users</a> </li>
                  </ul>
                </li><!-- Users -->
              @endif
            @endif
            @if(Auth::user()->user_type == "admin" && Auth::user()->user_type == "admin" || Auth::user()->user_type == "general")
              <li> <a href="javascript:;" class="link task_link" data-controller="channel" data-mode="index">Channels</a> </li>
            @endif
            @if(Auth::user()->user_type != "viewer")
              <li> <a href="javascript:;" class="link task_link" data-controller="offer_type" data-mode="index">List/Edit Offer Types</a></li>
            @endif
            @if(Auth::user()->user_type == "admin")
              <li> <a href="javascript:;" class="link task_link" data-controller="country" data-mode="index">Countries</a> </li>
              <li> <a href="javascript:;" class="link task_link" data-controller="language" data-mode="index">Languages</a> </li>
            @endif
          </ul>
        </li>
        <!-- Additional Information/Settings -->

      <div class="clearfix"></div>
      <!-- END SIDEBAR MENU --> </div>
  </div>
  <!-- END SIDEBAR -->