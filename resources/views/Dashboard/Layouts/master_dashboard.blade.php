<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-id" content="{{ auth()->id() }}">
    <title>Apartment One</title>
    <link rel="icon" href="{{ asset('assets/images/apartment-one-favicon.png') }}" type="favicon.png" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('assets/style-folder/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.6.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
    {{-- <style>
        .properties-dropdown .properties-dropdown-box{
            display: none ;
        }
    </style> --}}



    <style>
        .modal-content {
            border-radius: 12px;
        }

        .modal-header {
            border-bottom: none;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    @php
        $averagePropertyWeight = config('appdata.averagePropertyWeight');
    @endphp
    <section class="dashboard-main">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-2">
                    <div class="left-panel">
                        @php
                            $user = Auth::user();
                            $dashboardUrl = env('APP_URL');
                        @endphp




                        <div class="header-logo">
                            <a href="{{ $dashboardUrl }}"><x-logo /></a>
                        </div>
                        <div class="left-panel-menu">
                            <div class="panel-box">
                                <div class="user-login-box">
                                    <div class="user-img">
                                        @php
                                            $profiledUrl = null;
                                            if (Auth::user()) {
                                                $profiledUrl = route('profile.edit');
                                            }
                                        @endphp
                                        <a href="{{ $profiledUrl }}">
                                            <img src="{{ asset('assets/' . ($user->profile_img ?? 'default.png')) }}"
                                                onerror="this.src='{{ asset('assets/new-images/defaultprofile.png') }}'"
                                                alt="Profile Image">
                                        </a>
                                    </div>
                                    <div class="user-detail">
                                        <p>Hello,</p>
                                        <p class="fw-bold">{{ Auth::user()->first_name ?? '' }}</p>
                                    </div>

                                </div>
                                @if (Auth::user())
                                    <ul class="scroll-ul">





                                        <li><a href="{{ route('payment') }}" class="payment-active">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="white" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4z" />
                                                    <path
                                                        d="M0 7h16v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V7zm2 2.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2zm4 0a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H6z" />
                                                </svg>
                                                Payments</a></li>


                                        <li><a href="{{ route('listing') }}" class="listing-active">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z" />
                                                </svg>
                                                Listings</a></li>



                                        <li><a href="{{ route('listingCharges') }}" class="profile-active">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="white" viewBox="0 0 16 16">
                                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4z" />
                                                    <path
                                                        d="M0 7h16v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V7zm2 2.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2zm4 0a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H6z" />
                                                </svg>
                                                Listing Charge</a></li>


                                        <li><a href="{{ route('announcements.index') }}" class="announcement-active">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="68" height="68"
                                                    fill="white" viewBox="0 0 24 24" stroke="white" stroke-width="2">
                                                    <path d="M3 11V9a1 1 0 0 1 1-1h3l4-3v12l-4-3H4a1 1 0 0 1-1-1z">
                                                    </path>
                                                    <path d="M14 9a3 3 0 0 1 0 6"></path>
                                                </svg>


                                                Announcement Notifications</a></li>



                                                <li><a href="{{ route('admin.events.index') }}" class="event-active">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="white" viewBox="0 0 16 16" stroke="white" stroke-width="2">
                                                <path d="M3 11V9a1 1 0 0 1 1-1h3l4-3v12l-4-3H4a1 1 0 0 1-1-1z">
                                                </path>
                                                <path d="M14 9a3 3 0 0 1 0 6"></path>
                                            </svg>


                                            Events</a></li>

                                    <li>
                                    </ul>
                                @endif


                            </div>
                            <div class="panel-box">

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                @if (Auth::user())
                                    <a href="{{ route('profile.edit') }}" class="profile-active"><svg width="20"
                                            height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.82055 1.34503C7.61914 1.37311 5.51579 2.26009 3.95903 3.81685C2.40227 5.37361 1.51529 7.47695 1.48721 9.67837C1.4975 10.9608 1.80365 12.2235 2.38181 13.3683C2.95997 14.5131 3.79454 15.5089 4.82055 16.2784V16.345H4.90388C6.31487 17.4263 8.04292 18.0122 9.82055 18.0122C11.5982 18.0122 13.3262 17.4263 14.7372 16.345H14.8205V16.2784C15.8466 15.5089 16.6811 14.5131 17.2593 13.3683C17.8374 12.2235 18.1436 10.9608 18.1539 9.67837C18.1258 7.47695 17.2388 5.37361 15.6821 3.81685C14.1253 2.26009 12.022 1.37311 9.82055 1.34503ZM6.54555 15.4534C6.6672 14.8962 6.97567 14.3974 7.41978 14.0397C7.86389 13.682 8.41695 13.4869 8.98721 13.4867H10.6539C11.2241 13.4869 11.7772 13.682 12.2213 14.0397C12.6654 14.3974 12.9739 14.8962 13.0955 15.4534C12.1029 16.0374 10.9722 16.3453 9.82055 16.3453C8.66888 16.3453 7.53816 16.0374 6.54555 15.4534ZM14.4955 14.3784C14.1786 13.6208 13.6448 12.9738 12.9613 12.5187C12.2778 12.0635 11.4751 11.8204 10.6539 11.82H8.98721C8.16603 11.8204 7.36327 12.0635 6.67978 12.5187C5.99628 12.9738 5.46252 13.6208 5.14555 14.3784C4.5211 13.7642 4.0238 13.033 3.68206 12.2265C3.34032 11.4201 3.16084 10.5542 3.15388 9.67837C3.17549 7.91697 3.88481 6.23381 5.1304 4.98822C6.37599 3.74263 8.05915 3.03331 9.82055 3.0117C11.5819 3.03331 13.2651 3.74263 14.5107 4.98822C15.7563 6.23381 16.4656 7.91697 16.4872 9.67837C16.4803 10.5542 16.3008 11.4201 15.959 12.2265C15.6173 13.033 15.12 13.7642 14.4955 14.3784Z"
                                                fill="#0077B6" />
                                            <path
                                                d="M9.82055 4.67837C9.38 4.66811 8.94195 4.74732 8.53289 4.9112C8.12383 5.07508 7.75227 5.32024 7.44067 5.63183C7.12907 5.94343 6.88392 6.315 6.72004 6.72405C6.55615 7.13311 6.47695 7.57116 6.48721 8.01171C6.47695 8.45225 6.55615 8.8903 6.72004 9.29936C6.88392 9.70842 7.12907 10.08 7.44067 10.3916C7.75227 10.7032 8.12383 10.9483 8.53289 11.1122C8.94195 11.2761 9.38 11.3553 9.82055 11.345C10.2611 11.3553 10.6991 11.2761 11.1082 11.1122C11.5173 10.9483 11.8888 10.7032 12.2004 10.3916C12.512 10.08 12.7572 9.70842 12.9211 9.29936C13.0849 8.8903 13.1641 8.45225 13.1539 8.01171C13.1641 7.57116 13.0849 7.13311 12.9211 6.72405C12.7572 6.315 12.512 5.94343 12.2004 5.63183C11.8888 5.32024 11.5173 5.07508 11.1082 4.9112C10.6991 4.74732 10.2611 4.66811 9.82055 4.67837ZM9.82055 9.67837C9.59884 9.68909 9.37734 9.65332 9.17028 9.57335C8.96321 9.49339 8.77516 9.371 8.61821 9.21404C8.46126 9.05709 8.33886 8.86904 8.2589 8.66198C8.17893 8.45491 8.14316 8.23342 8.15388 8.01171C8.14316 7.79 8.17893 7.5685 8.2589 7.36144C8.33886 7.15437 8.46126 6.96633 8.61821 6.80937C8.77516 6.65242 8.96321 6.53003 9.17028 6.45006C9.37734 6.3701 9.59884 6.33432 9.82055 6.34504C10.0423 6.33432 10.2638 6.3701 10.4708 6.45006C10.6779 6.53003 10.8659 6.65242 11.0229 6.80937C11.1798 6.96633 11.3022 7.15437 11.3822 7.36144C11.4622 7.5685 11.4979 7.79 11.4872 8.01171C11.4979 8.23342 11.4622 8.45491 11.3822 8.66198C11.3022 8.86904 11.1798 9.05709 11.0229 9.21404C10.8659 9.371 10.6779 9.49339 10.4708 9.57335C10.2638 9.65332 10.0423 9.68909 9.82055 9.67837Z"
                                                fill="#0077B6" />
                                        </svg>
                                        Profile</a>
                                @endif


                                <a href="javascript:void(0);" onclick="confirmLogout()"
                                    class="t-btn t-btn-blue t-btn-svg">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M1.66666 10L5.83332 13.3334V10.8334H13.3333V9.16669H5.83332V6.66669L1.66666 10Z"
                                            fill="white" />
                                        <path
                                            d="M10.8342 2.49917C9.84876 2.49644 8.87262 2.68927 7.96228 3.06648C7.05194 3.44369 6.22549 3.99779 5.53082 4.69667L6.70916 5.87501C7.81082 4.77334 9.27582 4.16584 10.8342 4.16584C12.3925 4.16584 13.8575 4.77334 14.9592 5.87501C16.0608 6.97667 16.6683 8.44167 16.6683 10C16.6683 11.5583 16.0608 13.0233 14.9592 14.125C13.8575 15.2267 12.3925 15.8342 10.8342 15.8342C9.27582 15.8342 7.81082 15.2267 6.70916 14.125L5.53082 15.3033C6.94666 16.72 8.82999 17.5008 10.8342 17.5008C12.8383 17.5008 14.7217 16.72 16.1375 15.3033C17.5542 13.8875 18.335 12.0042 18.335 10C18.335 7.99584 17.5542 6.11251 16.1375 4.69667C15.4428 3.99779 14.6164 3.44369 13.706 3.06648C12.7957 2.68927 11.8196 2.49644 10.8342 2.49917Z"
                                            fill="white" />
                                    </svg>Logout
                                </a>

                            </div>
                        </div>


                    </div>
                </div>

                {{-- Start Mobile View --}}

                <div class="col-lg-10">
                    <div class="main-dashboard-header">

                        <div class="hamburger-menu">
                            <input id="menu__toggle" type="checkbox" />
                            <label class="menu__btn" for="menu__toggle">
                                <span></span>
                            </label>



                            @if (Auth::user())
                                <ul class="menu__box">
                                    <li>
                                        <div class="header-logo">
                                            <a href="{{ route('dashboard') }}"><img
                                                    src="/assets/new-images/apartmentone-logo.png" alt=""></a>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="user-login-box">
                                            <div class="user-img">
                                                <a href="{{ route('profile.edit') }}">
                                                    <img src="{{ asset('assets/' . ($user->img ?? 'default.png')) }}"
                                                        onerror="this.src='{{ asset('assets/new-images/defaultprofile.png') }}'"
                                                        alt="Profile Image">
                                                </a>
                                            </div>
                                            <div class="user-detail">
                                                <p>Hello,</p>
                                                <h5>{{ Auth::user()->first_name ?? '' }}</h5>
                                            </div>

                                        </div>
                                    </li>


                                    <li><a href="{{ route('payment') }}" class="payment-active">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="white" viewBox="0 0 16 16">
                                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4z" />
                                                <path
                                                    d="M0 7h16v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V7zm2 2.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2zm4 0a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H6z" />
                                            </svg>
                                            Payments</a></li>


                                    <li><a href="{{ route('listing') }}" class="listing-active">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M4 4h6v6H4V4zm10 0h6v6h-6V4zM4 14h6v6H4v-6zm10 0h6v6h-6v-6z" />
                                            </svg>
                                            Listings</a></li>
                                    {{-- <li>
                                        <a href="{{ env('APP_HOME') }}" class=""><svg width="30"
                                                height="31" viewBox="0 0 30 31" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M25 4.25H5C3.62125 4.25 2.5 5.37125 2.5 6.75V20.5C2.5 21.8787 3.62125 23 5 23H13.75V25.5H10V28H20V25.5H16.25V23H25C26.3787 23 27.5 21.8787 27.5 20.5V6.75C27.5 5.37125 26.3787 4.25 25 4.25ZM5 18V6.75H25L25.0025 18H5Z"
                                                    fill="#999999" />
                                            </svg>
                                            Go To Website</a>
                                    </li> --}}



                                    <li><a href="{{ route('listingCharges') }}" class="profile-active">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="white" viewBox="0 0 16 16">
                                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v1H0V4z" />
                                                <path
                                                    d="M0 7h16v5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V7zm2 2.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2zm4 0a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1H6z" />
                                            </svg>
                                            Listing Charge</a></li>

                                    <li><a href="{{ route('announcements.index') }}" class="announcement-active">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="white" viewBox="0 0 16 16" stroke="white" stroke-width="2">
                                                <path d="M3 11V9a1 1 0 0 1 1-1h3l4-3v12l-4-3H4a1 1 0 0 1-1-1z">
                                                </path>
                                                <path d="M14 9a3 3 0 0 1 0 6"></path>
                                            </svg>


                                            Announcement Notifications</a></li>
                                    <li><a href="{{ route('admin.events.index') }}" class="event-active">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="white" viewBox="0 0 16 16" stroke="white" stroke-width="2">
                                                <path d="M3 11V9a1 1 0 0 1 1-1h3l4-3v12l-4-3H4a1 1 0 0 1-1-1z">
                                                </path>
                                                <path d="M14 9a3 3 0 0 1 0 6"></path>
                                            </svg>


                                            Events</a></li>

                                    <li>
                                        <a href="javascript:void(0);" onclick="confirmLogout()"
                                            class="t-btn t-btn-blue t-btn-svg">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path d="M16 13V11H7V8L2 12L7 16V13H16Z" fill="white" />
                                                <path
                                                    d="M20 3H11C9.897 3 9 3.897 9 5V9H11V5H20V19H11V15H9V19C9 20.103 9.897 21 11 21H20C21.103 21 22 20.103 22 19V5C22 3.897 21.103 3 20 3Z"
                                                    fill="white" />
                                            </svg>
                                            Logout
                                        </a>
                                    </li>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </ul>
                            @endif


                        </div>

                        <div class="page-detail-box">
                            <p>Dashboard</p>
                            <h5>@yield('heading')</h5>
                        </div>

                        <div class="right-header-links">
                            <ul>




                                {{-- <li><a href="{{ env('APP_HOME') }}" class="t-btn dashboard-web-link"><svg
                                            width="30" height="31" viewBox="0 0 30 31" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M25 4.25H5C3.62125 4.25 2.5 5.37125 2.5 6.75V20.5C2.5 21.8787 3.62125 23 5 23H13.75V25.5H10V28H20V25.5H16.25V23H25C26.3787 23 27.5 21.8787 27.5 20.5V6.75C27.5 5.37125 26.3787 4.25 25 4.25ZM5 18V6.75H25L25.0025 18H5Z"
                                                fill="#999999" />
                                        </svg>
                                        Go To Website</a></li> --}}






                            </ul>
                        </div>
                    </div>
                    @yield('content')

                </div>
            </div>
        </div>

    </section>






    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/custom-js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    {{-- stripe --}}
    <script src="https://js.stripe.com/v3/"></script>
    <!-- Load Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>


    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.0.8/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.0/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const header = document.querySelector(".main-dashboard-header");
            const stickyClass = "sticky";

            window.addEventListener("scroll", function() {
                if (window.scrollY > 0) {
                    header.classList.add(stickyClass);
                } else {
                    header.classList.remove(stickyClass);
                }
            });
        });



        function confirmLogout() {
            Swal.fire({
                title: "Are you sure?",
                text: "Do you really want to log out?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Logout"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById("logout-form").submit();
                }
            });
        }
    </script>
    @yield('scripts')
</body>

</html>
