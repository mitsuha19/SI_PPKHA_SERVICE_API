<!-- Include SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom Swal for Logout -->
<link rel="stylesheet" href="{{ asset('assets/css/logout.css') }}">

<nav class="shadow-md">
    <div class="container">
        <!-- Logo dan Judul -->
        <div class="d-flex justify-content-start">
            <a class="text-white text-decoration-none">
                <img src="{{ asset('assets/images/itdel.png') }}" alt="Logo" width="40" height="auto"
                    style="margin-right: 10px;">
                <div class="d-flex flex-column" style="line-height: 0.9; gap: 0px;">
                    <span class="fs-4 fw-bold poppins-bold text-start">CAIS</span>
                    <p class="m-0 roboto-light text-start" style="margin-top: -4px; width: 125px;">Career Alumni
                        Information System</p>
                </div>
            </a>
        </div>

        <div class="d-flex justify-content-end align-items-end">
            <!-- Menu Navigasi -->
            <ul class="text-white" style="list-style: none; padding: 0; display: flex; gap: 20px;">
                @php
                $menus = [
                '/' => 'Beranda',
                '/berita' => 'Berita',
                '/pengumuman' => 'Pengumuman',
                '/artikel' => 'Artikel',
                '/daftar_perusahaan' => 'Daftar Perusahaan',
                '/lowongan_pekerjaan' => 'Lowongan Pekerjaan',
                '/tracer_study' => 'Tracer Study',
                '/user-survey' => 'User Survey',
                '/tentang' => 'Tentang',
                ];
                @endphp

                @foreach ($menus as $route => $name)
                @php
                $isActive = Request::is(trim($route, '/')) || ($route == '/' && Request::is('/'));
                @endphp

                <li style="position: relative; text-align: center;">
                    <a href="{{ $route }}" class="{{ $isActive ? 'fw-bold' : '' }}"
                        style="text-decoration: none; color: white; font-weight: {{ $isActive ? 'bold' : 'normal' }}; display: inline-block;">
                        {{ $name }}
                    </a>

                    @if ($isActive)
                    <div
                        style="position: absolute; left: 50%; transform: translateX(-50%); bottom: -13px; width: 100%;">
                        <svg width="100%" height="4" viewBox="0 0 100 4" preserveAspectRatio="none">
                            <ellipse cx="50" cy="2" rx="50" ry="1.5" fill="#3B3B3B" />
                            <ellipse cx="50" cy="2" rx="45" ry="1" fill="#1A1A1A" />
                        </svg>
                    </div>
                    @endif
                </li>
                @endforeach

                @guest
                <li class="me-3"><a href="{{ route('login') }}" class="text-white">Login</a></li>
                @endguest

                @auth
                <li>
                    <!-- Public Logout Form with Custom SweetAlert2 Confirmation -->
                    <form id="public-logout-form" action="{{ route('logout') }}" method="POST"
                        class="d-flex align-items-center justify-content-center text-decoration-none"
                        style="margin-left: auto; margin-right: auto; background: none; border: none; padding: 0;">
                        @csrf
                        <button type="button" class="btn btn-transparent d-flex align-items-center text-decoration-none"
                            style="background: none; border: none; padding: 0;" onclick="showLogoutConfirmation(event)">
                            <span class="fw-bold" style="font-size: 14px; margin-right: 5px; color: #FFFFFF;">
                                Hi, {{ Auth::user()->name }}
                            </span>
                            <img src="{{ asset('assets/images/logout-04.png') }}" alt="Logout Icon"
                                style="margin-left: 5px;">
                        </button>
                    </form>
                </li>
                @endauth

                <script>
                    function showLogoutConfirmation(event) {
                        event.preventDefault(); // Prevent default form submission
                        Swal.fire({
                            title: "Are you sure you want to leave?",
                            showCancelButton: true,
                            confirmButtonText: "Yes",
                            cancelButtonText: "Cancel",
                            background: "linear-gradient(to bottom, #a2d9e0, #468c98)", // Teal gradient background
                            backdrop: true, // Default backdrop or blurred
                            width: '500px', // Larger width for popup
                            customClass: {
                                popup: 'swal-popup',
                                title: 'swal-title',
                                confirmButton: 'swal-confirm',
                                cancelButton: 'swal-cancel'
                            },
                            buttonsStyling: false // Disable default button styling to apply custom CSS
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: "Logged out!",
                                    text: "You have successfully logged out.",
                                    icon: "success",
                                    background: "linear-gradient(to bottom, #a2d9e0, #468c98)", // Match background
                                    confirmButtonColor: "#4aa3a3", // Teal confirm button for success message
                                    width: '500px', // Maintain width for success message
                                    customClass: {
                                        popup: 'swal-popup',
                                        title: 'swal-title',
                                        confirmButton: 'swal-confirm'
                                    },
                                    buttonsStyling: false
                                }).then(() => {
                                    document.getElementById('public-logout-form').submit(); // Submit form
                                });
                            }
                        });
                    }
                </script>
            </ul>
        </div>
    </div>
</nav>