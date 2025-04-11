@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')
    <div class="main-content d-flex flex-column align-items-center">
        <h1>Tracer Study</h1>

        <div class="d-flex flex-row justify-content-center gap-2 w-100 mb-3">
            <form class="w-50" action="/admin/tracer-study">
                <input type="text" id="search" name="search" placeholder="Cari Form" value="{{ request('search') }}">
            </form>
            <div class="search-logo d-flex justify-content-center align-items-center">
                <i class='bx bx-search-alt-2'></i>
            </div>
        </div>

        <div class="d-flex flex-column align-items-center w-100 gap-2">
            <div class="d-flex justify-content-end" style="width: 80%">
                <button type="button" class="btn btn-tambah mt-2" onclick="window.location.href='/admin/tracer-study/create'">
                    <i class='bx bx-plus-circle'></i>
                    <span class="d-none d-xl-inline">Tambah</span>
                </button>
            </div>


            @foreach ($forms as $tracerStudy)
                <div class="background-card">
                    <div class="card-information d-flex align-items-center px-3">
                        <img src="{{ asset('assets/images/image.png') }}" alt="Tracer Study Image">
                        <div class="ps-3 w-100">
                            <div class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-center">
                                <h2 class="fst-italic roboto-title mb-0 align-self-center">{{ $tracerStudy->judul_form }}
                                </h2>
                                <div class="align-self-start">
                                    <div class="ms-auto d-flex gap-2">
                                        <a href="{{ route('admin.forms.edit', $tracerStudy->id) }}"
                                            class="btn btn-sm btn-info">
                                            <i class='bx bx-pencil'></i>
                                            <span class="d-none d-xl-inline ms-1">Edit</span>
                                        </a>

                                        <a href="javascript:void(0);" class="btn btn-danger btn-delete"
                                            data-id="{{ $tracerStudy->id }}">
                                            <i class='bx bx-trash'></i>
                                            <span class="d-none d-xl-inline ms-1">Hapus</span>
                                        </a>
                                    </div>

                                </div>
                            </div>

                            <hr class="my-2" style="border: 2px solid black; opacity: 1">

                            <p class="roboto-light mb-1 mt-2 description-text" style="font-size: 15px;">
                                {{ $tracerStudy->deskripsi_form }}
                            </p>

                            <div class="detail align-self-end">
                                <a href="/admin/tracer-study/detail/{{ $tracerStudy->id }}">
                                    Selengkapnya..
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

            @if ($forms->isEmpty())
                <div class="text-center">
                    <h1 class="fw-bold fst-italic mt-4">Ops</h1>
                    <h2 class="fw-bold mt-3 text-dark">Tidak ada Form!</h2>

                    {{-- <a href="/admin/tracer-study/create" class="tambah-btn">
                        <span class="icon-box"><i class="bx bx-plus"></i></span> <span
                            class="fw-bold fst-italic">Tambah</span>
                    </a> --}}
                </div>
            @else
                <div class="pagination">
                    <a href="{{ $forms->previousPageUrl() }}" style="background-color: transparent">
                        &laquo;
                    </a>
                    @foreach ($forms->getUrlRange(1, $forms->lastPage()) as $page => $url)
                        <a href="{{ $url }}" class="{{ $page == $forms->currentPage() ? 'active' : '' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                    <a href="{{ $forms->nextPageUrl() }}" style="background-color: transparent">
                        &raquo;
                    </a>
                </div>
            @endif
        </div>

        <style>
            .card-artikel {
                display: flex;
                align-items: center;
                gap: 15px;
                justify-content: space-between;
            }

            .card-artikel .ps-3 {
                display: flex;
                flex-direction: column;
                flex-grow: 1;
            }

            .description-text {
                font-size: 15px;
                line-height: 1.6;
                margin-top: 10px;
                margin-bottom: 0;
                max-height: 60px;
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function() {
                        let formId = this.getAttribute('data-id');

                        Swal.fire({
                            title: "Apakah Anda yakin?",
                            text: "Form yang dihapus tidak dapat dikembalikan!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Ya, hapus!",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch(`/admin/tracer-study/delete/${formId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content'),
                                            'Content-Type': 'application/json'
                                        }
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.message) {
                                            Swal.fire('Sukses!', data.message, 'success')
                                                .then(() => {
                                                    location
                                                        .reload();
                                                });
                                        } else {
                                            Swal.fire('Oops!', data.error, 'error');
                                        }
                                    })
                                    .catch(error => {
                                        console.error("Error: ", error);
                                        Swal.fire('Oops!',
                                            'Terjadi kesalahan saat menghapus.', 'error'
                                        );
                                    });
                            }
                        });
                    });
                });
            });
        </script>
    @endsection
