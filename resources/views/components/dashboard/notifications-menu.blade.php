<!-- Be present above all else. - Naval Ravikant -->

<!-- Notifications dropdown -->
<li class="nav-item dropdown">
    <a class="nav-link" data-toggle="dropdown" href="#">
      <i class="far fa-bell"></i>
      @if ($newCount)
        <span class="badge badge-warning navbar-badge">{{ $newCount }}</span>          
      @endif
    </a>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
      <span class="dropdown-item dropdown-header">{{ $newCount }} Notifications</span>
      <div class="dropdown-divider"></div>
  
      @foreach ($notifications as $notification)
        <a href="{{ $notification->data['url'] ?? '#' }}?notification_id={{ $notification->id }}" class="dropdown-item">
          <i class="{{ $notification->data['icon'] }} mr-2"></i> {{ $notification->data['message'] }}
          <span class="float-right text-muted @if($notification->unread()) text-bold @endif">{{ $notification->created_at->diffForHumans() }}</span>
        </a>
        <div class="dropdown-divider"></div>
      @endforeach
  
      <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
    </div>
  </li>