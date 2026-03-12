{{-- 
    Navigation Bar Component — Shown at the top of every page.
    
    Shows different links based on authentication status:
    - Logged in (@auth): Edit Profile link + Logout button
    - Guest (@guest): Sign in link + Register button
--}}
<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        {{-- Logo (left side) --}}
        <div>
            <a href=""><img src="/images/logo.svg" alt="Idea logo" width="100"></a>
        </div>

        {{-- Navigation links (right side) --}}
        <div class="flex gap-x-5 items-center">
            {{-- Show these links only when the user is LOGGED IN --}}
            @auth
                <a href="{{ route('profile.edit') }}" class="nav-link">Edit Profile</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="nav-link">Logout</button>
                </form>
            @endauth

            {{-- Show these links only when the user is NOT logged in --}}
            @guest
                <a href="{{ route('login') }}" class="nav-link">Sign in</a>
                <a href="{{ route('register') }}" class="btn">Register</a>
            @endguest
        </div>
    </div>
</nav>