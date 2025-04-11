@extends('layouts.app')

@section('content')
    @include('components.navbar')
    <div class="content-with-background d-flex flex-column align-items-center">
        @include('components.bg') <!-- Renders the background waves -->
        
        <!-- Top Search Bar Section (New, positioned at the top of content) -->
        <div class="top-search-bar-container">
    <div class="top-search-bar d-flex align-items-center">
        <form class="d-flex w-100" action="{{ route('ppkha.pengumuman') }}" method="GET">
            <input type="text" id="pengumuman" name="search" class="form-control me-2" placeholder="Cari Pengumuman..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-search bx-sm'></i>
            </button>
        </form>
    </div>
</div>


        <!-- Pengumuman Section -->
        @foreach($pengumuman as $item)
            <div class="background-card">
                <div class="card-information d-flex align-items-center px-3">
                    @php
                        $gambarArray = $item->gambar ?? [];
                    @endphp

                    @if (!empty($gambarArray) && isset($gambarArray[0]))
                        <img 
                        src="{{ asset('storage/' . $gambarArray[0]) }}" alt="Gambar Pengumuman">
                    @else
                        <img
                        src="{{ asset('assets/images/image.png') }}" alt="Default Gambar">
                    @endif

                    <div class="ps-3 w-100">
                        <div class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-end">
                            <h5 class="horizontal-card-title fw-bold">
                                {{ $item->judul_pengumuman }}
                            </h5>
                        </div>
                        <hr class="my-2 w-100" style="border: 2px solid black; opacity:1">
                        <p class="roboto-light mb-1 mt-2" style="font-size: 15px">
                            {{ Str::limit($item->deskripsi_pengumuman, 200, '...') }}
                        </p>
                        <div class="detail">
                            <a href="{{ route('ppkha.pengumumanDetail', ['id' => $item->id]) }}">Selengkapnya..</a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="pagination">
            {{ $pengumuman->appends(request()->query())->links() }}
        </div>
    </div>

    @include('components.footer')
@endsection