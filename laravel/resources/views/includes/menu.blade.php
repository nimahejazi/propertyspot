<div class="menu-container-light">
    <div class="container">
        <div class="avatar-container">
            <div></div>
            <nav class="avatar-icon">
                <figure id="avatar"><p>{{$user->getInitial()}}</p></figure>
                <div class="avatar-menu">
                    <ul>
                        <li>{{$user->getName()}}</li>
                        <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                        <li><a href="{{route('profile')}}">Profile</a></li>
                        <li><a href="/signout">Sign out</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
</div>
