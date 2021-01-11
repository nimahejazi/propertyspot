<div class="navbar-menu" id="navbarMenu2">
    <div class="navbar-end">
        <div class="navbar-item has-dropdown is-hoverable">
            <div class="navbar-link">
                <div class="avatar-container">
                    <a class="avatar-icon"
                    ><figure><p>{{$user->getInitial()}}</p></figure></a
                    >
                </div>
            </div>
            <div class="navbar-dropdown is-right">
                <div class="navbar-item">{{$user->getName()}}</div>
                <hr class="navbar-divider" />
                <a href='{{route('dashboard')}}' class="navbar-item">Dashboard</a>
                <a href='{{route('profile')}}' class="navbar-item">Profile</a>
                <a href='/signout' class="navbar-item">Sign out</a>
            </div>
        </div>
    </div>
</div>
