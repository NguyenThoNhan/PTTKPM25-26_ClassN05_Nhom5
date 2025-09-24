<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <!-- Navigation Links (Màn hình lớn) -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        {{ __('Trang chủ') }}
                    </x-nav-link>
                    <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                        {{ __('Sự kiện') }}
                    </x-nav-link>

                    {{-- Link chỉ hiển thị cho KHÁCH và USER THƯỜNG (không phải admin) --}}
                    @cannot('is-admin')
                    <x-nav-link :href="route('virtual-library.index')" :active="request()->routeIs('virtual-library.index')">
        {{ __('Thư viện ảo') }}
    </x-nav-link>
                        <x-nav-link :href="route('about.index')" :active="request()->routeIs('about.index')">
                            {{ __('Về Chúng Tôi') }}
                        </x-nav-link>
                        <x-nav-link :href="route('contact.show')" :active="request()->routeIs('contact.show')">
                            {{ __('Liên hệ') }}
                        </x-nav-link>
                    @endcannot
                    
                    {{-- Link chỉ hiển thị cho USER ĐÃ ĐĂNG NHẬP --}}
                    @auth
                         <x-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
        {{ __('Tủ sách') }}
    </x-nav-link>
                        <x-nav-link :href="route('history.my')" :active="request()->routeIs('history.my')">
                            {{ __('Lịch sử mượn') }}
                        </x-nav-link>
                        <x-nav-link :href="route('gamification.dashboard')" :active="request()->routeIs('gamification.*')">
                            {{ __('Gamification') }}
                        </x-nav-link>
                        <x-nav-link :href="route('social.feed')" :active="request()->routeIs('social.*')">
                            {{ __('Social') }}
                        </x-nav-link>
                        <x-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                            {{ __('Thông báo') }}
                        </x-nav-link>
                        <x-nav-link :href="route('qr.scanner')" :active="request()->routeIs('qr.*')">
                            {{ __('QR Scanner') }}
                        </x-nav-link>
                        <x-nav-link :href="route('offline.index')" :active="request()->routeIs('offline.*')">
                            {{ __('Offline') }}
                        </x-nav-link>
                        {{-- Link chỉ hiển thị cho ADMIN --}}
                        @can('is-admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Admin Dashboard') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.books.index')" :active="request()->routeIs('admin.books.*')">
                                {{ __('Quản Lý Sách') }}
                            </x-nav-link>
                             <x-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                                {{ __('Quản Lý Danh Mục') }}
                            </x-nav-link>
                             <x-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                                {{ __('Quản Lý Sự Kiện') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                                {{ __('Quản Lý User') }}
                            </x-nav-link>
                            <x-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.index')">
                                {{ __('Quản Lý Mượn/Trả') }}
                            </x-nav-link>
                        @endcan
                    @endauth
                </div>
            </div>

            @auth
                <!-- Settings Dropdown (Đã đăng nhập) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Thông tin cá nhân') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Đăng xuất') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endauth

            @guest
                <!-- Links cho Khách (chưa đăng nhập) -->
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Đăng nhập</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Đăng ký</a>
                    @endif
                </div>
            @endguest

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Trang chủ') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">
                {{ __('Sự kiện') }}
            </x-responsive-nav-link>
            
            {{-- Link chỉ hiển thị cho KHÁCH và USER THƯỜNG (không phải admin) --}}
            @cannot('is-admin')
             <x-responsive-nav-link :href="route('virtual-library.index')" :active="request()->routeIs('virtual-library.index')">
        {{ __('Thư viện ảo') }}
    </x-responsive-nav-link>
    <x-responsive-nav-link :href="route('favorites.index')" :active="request()->routeIs('favorites.index')">
        {{ __('Tủ sách') }}
    </x-responsive-nav-link>
                 <x-responsive-nav-link :href="route('about.index')" :active="request()->routeIs('about.index')">
                    {{ __('Về Chúng Tôi') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('contact.show')" :active="request()->routeIs('contact.show')">
                    {{ __('Liên hệ') }}
                </x-responsive-nav-link>
            @endcannot

            @auth
                <x-responsive-nav-link :href="route('history.my')" :active="request()->routeIs('history.my')">
                    {{ __('Lịch sử mượn') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('gamification.dashboard')" :active="request()->routeIs('gamification.*')">
                    {{ __('Gamification') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('social.feed')" :active="request()->routeIs('social.*')">
                    {{ __('Social') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                    {{ __('Thông báo') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('qr.scanner')" :active="request()->routeIs('qr.*')">
                    {{ __('QR Scanner') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('offline.index')" :active="request()->routeIs('offline.*')">
                    {{ __('Offline') }}
                </x-responsive-nav-link>
                @can('is-admin')
                    {{-- Toàn bộ link của admin nằm ở đây --}}
                    <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Admin Dashboard') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.books.index')" :active="request()->routeIs('admin.books.*')">
                        {{ __('Quản Lý Sách') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.categories.index')" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Quản Lý Danh Mục') }}
                    </x-responsive-nav-link>
                     <x-responsive-nav-link :href="route('admin.events.index')" :active="request()->routeIs('admin.events.*')">
                        {{ __('Quản Lý Sự Kiện') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                        {{ __('Quản Lý User') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.loans.index')" :active="request()->routeIs('admin.loans.index')">
                        {{ __('Quản Lý Mượn/Trả') }}
                    </x-responsive-nav-link>
                @endcan
            @endauth
        </div>

        @auth
            <!-- Responsive Settings Options (Đã đăng nhập) -->
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Thông tin cá nhân') }}
                    </x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Đăng xuất') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth

        @guest
             <!-- Responsive Settings Options (Khách) -->
             <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                 <div class="mt-3 space-y-1">
                     <x-responsive-nav-link :href="route('login')">
                        {{ __('Đăng nhập') }}
                    </x-responsive-nav-link>
                     <x-responsive-nav-link :href="route('register')">
                        {{ __('Đăng ký') }}
                    </x-responsive-nav-link>
                 </div>
             </div>
        @endguest
    </div>
</nav>