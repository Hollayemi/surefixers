@php
    $setting = App\Models\Setting::first();
@endphp

<div class="main-sidebar">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="{{ route('provider.dashboard') }}">{{ $setting->sidebar_lg_header }}</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ route('provider.dashboard') }}">{{ $setting->sidebar_sm_header }}</a>
      </div>
      <ul class="sidebar-menu">
          <li class="{{ Route::is('provider.dashboard') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.dashboard') }}"><i class="fas fa-home"></i> <span>{{__('user.Dashboard')}}</span></a></li>

          <li class="nav-item dropdown {{ Route::is('provider.all-booking') || Route::is('provider.pending-booking') || Route::is('provider.booking-show') || Route::is('provider.awaiting-booking') || Route::is('provider.active-booking')  || Route::is('provider.completed-booking') || Route::is('provider.declined-booking') || Route::is('provider.complete-request') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-shopping-cart"></i><span>{{__('user.My Bookings')}}</span></a>

            <ul class="dropdown-menu">

                <li class="{{ Route::is('provider.all-booking') || Route::is('provider.booking-show') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.all-booking') }}">{{__('user.All Bookings')}}</a></li>

                <li class="{{ Route::is('provider.awaiting-booking') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.awaiting-booking') }}">{{__('user.Awaiting Approval')}}</a></li>

                <li class="{{ Route::is('provider.active-booking') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.active-booking') }}">{{__('user.Active Bookings')}}</a></li>

                <li class="{{ Route::is('provider.completed-booking') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.completed-booking') }}">{{__('user.Completed Bookings')}}</a></li>

                <li class="{{ Route::is('provider.complete-request') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.complete-request') }}">{{__('user.Complete Request')}}</a></li>

                <li class="{{ Route::is('provider.declined-booking') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.declined-booking') }}">{{__('user.Declined Bookings')}}</a></li>


            </ul>
          </li>

          @php
                $user = Auth::guard('web')->user();
                $unseenMessages = App\Models\TicketMessage::where(['unseen_user' => 0, 'user_id' => 5])->groupBy('ticket_id')->get();
                $count = $unseenMessages->count();
            @endphp

          <li class="{{ Route::is('provider.ticket') || Route::is('provider.ticket-show') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.ticket') }}"><i class="fas fa-envelope-open-text"></i> <span>{{__('user.Support Ticket')}} <sup class="badge badge-danger">{{ $count }}</sup></span></a></li>


          <li class="nav-item dropdown {{ Route::is('provider.service.*') || Route::is('provider.awaiting-for-approval-service') || Route::is('provider.active-service') ||  Route::is('provider.banned-service') ||  Route::is('provider.review-list') || Route::is('provider.show-review') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('user.Manage Services')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('provider.service.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.service.index') }}">{{__('user.All Service')}}</a></li>

                <li class="{{ Route::is('provider.awaiting-for-approval-service') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.awaiting-for-approval-service') }}">{{__('user.Awaiting for Approval')}}</a></li>

                <li class="{{ Route::is('provider.active-service') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.active-service') }}">{{__('user.Active Service')}}</a></li>

                <li class="{{ Route::is('provider.banned-service') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.banned-service') }}">{{__('user.Banned Service')}}</a></li>


                <li class="{{ Route::is('provider.review-list') || Route::is('provider.show-review') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.review-list') }}">{{__('user.Service Review')}}</a></li>



            </ul>
          </li>

          <li class="{{ Route::is('provider.appointment-schedule.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.appointment-schedule.index') }}"><i class="far fa-newspaper"></i> <span>{{__('user.Appointment Schedule')}}</span></a></li>

          <li class="{{ Route::is('provider.live-chat') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.live-chat') }}"><i class="far fa-newspaper"></i> <span>{{__('user.Live Chat')}}</span></a></li>

          <li class="nav-item dropdown {{ Route::is('provider.coupon.*') || Route::is('provider.coupon-history') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-th-large"></i><span>{{__('user.Manage Coupon')}}</span></a>

            <ul class="dropdown-menu">
                <li class="{{ Route::is('provider.coupon.*') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.coupon.index') }}">{{__('Coupon')}}</a></li>

                <li class="{{ Route::is('provider.coupon-history') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.coupon-history') }}">{{__('Coupon Histories')}}</a></li>

            </ul>
          </li>


          <li class="{{ Route::is('seller.my-withdraw.index') ? 'active' : '' }}"><a class="nav-link" href="{{ route('provider.my-withdraw.index') }}"><i class="far fa-newspaper"></i> <span>{{__('user.My Withdraw')}}</span></a></li>

        </ul>

    </aside>
  </div>

