<!-- Include SweetAlert2 CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom Swal for Logout -->
<link rel="stylesheet" href="{{ asset('assets/css/logout.css') }}">

<div class="combined-header-sidebar sticky-top">
    <div class="container-fluid m-0 p-0 header-admin">
        <header class="d-flex justify-content-start py-3 ps-4">
            <a class="text-white text-decoration-none">
                <div class="d-flex flex-row">
                    <img src="{{ asset('assets/images/itdel.png') }}" alt="Logo" width="40" height="auto"
                        style="margin-right: 10px;">
                    <div class="d-flex flex-column" style="line-height: 0.9; gap: 0px;">
                        <span class="fs-4 fw-bold poppins-bold text-center fst-italic">Admin</span>
                        <p class="m-0 roboto-light text-center" style="margin-top: -4px; width: 100px; font-size:10px;">
                            Career Alumni Information System</p>
                    </div>
                </div>
            </a>
            <form id="admin-logout-form" action="{{ route('logout') }}" method="POST"
                class="d-flex align-items-center text-decoration-none"
                style="margin-left:auto; margin-right: 1%; background: none; border: none; padding: 0;">
                @csrf
                <button type="submit" class="btn btn-transparent d-flex align-items-center text-decoration-none"
                    style="background: none; border: none; padding: 0;" onclick="return showLogoutConfirmation(event);">
                    <span class="fw-bold" style="font-size: 14px; margin-right: 5px; color: #000000;">
                        Hi, {{ Auth::user()->name }}
                    </span>
                    <img src="{{ asset('assets/images/logout-04.png') }}" alt="Logout Icon" style="margin-left: 5px;">
                </button>
            </form>
        </header>
    </div>

    <div class="sidebar-admin">
        <ul class="nav flex-column mb-auto px-3" style="padding-top: 16px">
            <li class="nav-item">
                <a href="/admin" class="nav-link {{ Request::is('admin') ? 'active' : '' }}" aria-current="page">
                    <i class='bx bx-bar-chart-square'></i>
                    <span class="d-none d-xl-inline ms-1">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="/admin/beranda" class="nav-link {{ Request::is('admin/beranda*') ? 'active' : '' }}">
                    <i class='bx bx-home-alt'></i>
                    <span class="d-none d-xl-inline ms-1">Home</span>
                </a>
            </li>

            <li>
                <a href="/admin/berita" class="nav-link {{ Request::is('admin/berita*') ? 'active' : '' }}">
                    <i class='bx bx-info-circle'></i>
                    <span class="d-none d-xl-inline ms-1">Berita</span>
                </a>
            </li>

            <li>
                <a href="/admin/pengumuman" class="nav-link {{ Request::is('admin/pengumuman*') ? 'active' : '' }}">
                    <i class='bx bx-bell'></i>
                    <span class="d-none d-xl-inline ms-1">Pengumuman</span>
                </a>
            </li>

            <li>
                <a href="/admin/artikel" class="nav-link {{ Request::is('admin/artikel*') ? 'active' : '' }}">
                    <i class='bx bx-book'></i>
                    <span class="d-none d-xl-inline ms-1">Artikel</span>
                </a>
            </li>

            <li>
                <a href="/admin/daftar-perusahaan"
                    class="nav-link {{ Request::is('admin/daftar-perusahaan*') ? 'active' : '' }}">
                    <i class='bx bx-home-circle'></i>
                    <span class="d-none d-xl-inline ms-1">Daftar Perusahaan</span>
                </a>
            </li>

            <li>
                <a href="/admin/lowongan-pekerjaan"
                    class="nav-link {{ Request::is('admin/lowongan-pekerjaan*') ? 'active' : '' }}">
                    <i class='bx bx-briefcase-alt'></i>
                    <span class="d-none d-xl-inline ms-1">Lowongan Pekerjaan</span>
                </a>
            </li>

            <li>
                <a href="/admin/tracer-study" class="nav-link {{ Request::is('admin/tracer-study*') ? 'active' : '' }}">
                    <i class='bx bx-group'></i>
                    <span class="d-none d-xl-inline ms-1">Tracer Study</span>
                </a>
            </li>

            <li>
                <a href="/admin/user-survey" class="nav-link {{ Request::is('admin/user-survey*') ? 'active' : '' }}">
                    <i class='bx bx-notepad'></i>
                    <span class="d-none d-xl-inline ms-1">User Survey</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    function showLogoutConfirmation(event) {
        event.preventDefault(); // Prevent default form submission
        Swal.fire({
            title: "Are you sure you want to logout?",
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
                    document.getElementById('admin-logout-form').submit(); // Submit form
                });
            }
        });
    }
</script>