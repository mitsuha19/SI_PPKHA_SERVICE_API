<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SI-PPKHA</title>
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- Box Icons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Mono:ital,wght@0,100..700;1,100..700&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

</head>

<body style='overflow-y:hidden'>
    <div class="container-fluid-navbar" style="overflow:auto">
        @yield('content')
    </div>

    <script src="{{ asset('assets/js/form-builder.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function updateMainContentMargin() {
                const sidebar = document.querySelector(".sidebar-admin");
                const mainContent = document.querySelector(".main-content");

                if (sidebar && mainContent) {
                    const sidebarWidth = sidebar.offsetWidth; // Ambil lebar sidebar
                    mainContent.style.marginLeft = sidebarWidth + "px"; // Sesuaikan margin-left
                }
            }

            // Panggil fungsi saat halaman dimuat
            updateMainContentMargin();

            // Tambahkan event listener jika sidebar bisa berubah ukuran
            window.addEventListener("resize", updateMainContentMargin);
        });
    </script>
</body>
@include('sweetalert::alert')

</html>