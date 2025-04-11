@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <div class="content-with-background">
        @include('components.bg')
        
        <!-- Top Search Bar Section (New, positioned at the top of content) -->
        <div class="top-search-bar-container">
    <div class="top-search-bar d-flex align-items-center">
        <form class="d-flex w-100" action="{{ route('ppkha.artikel') }}" method="GET">
            <input type="text" id="artikel" name="search" class="form-control me-2" placeholder="Cari Artikel..." value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class='bx bx-search bx-sm'></i>
            </button>
        </form>
    </div>
</div>
        <div class="pengumuman-section d-flex flex-column align-items-center gap-4">
            <div class="pengumuman-grid" style="display: flex; flex-wrap: wrap;">
                <!-- Static Cards -->

                @foreach ($artikel as $item)
                <div class="background-card-artikel">
                    <div class="card" style="width: 18rem;">
                         @php
                            $gambarArray = $item->gambar ?? []; // Laravel otomatis mengubah JSON ke array
                        @endphp

                        @if (!empty($gambarArray) && isset($gambarArray[0]))
                            <img class="card-img-top" src="{{ asset('storage/' . $gambarArray[0]) }}" alt="Gambar Artikel">
                        @else
                            <img class="card-img-top" src="{{ asset('assets/images/image.png') }}" alt="Default Gambar">
                        @endif

                        <div class="card-detail">
                            <h5 class="card-title">
                                {{ $item->judul_artikel }}
                            </h5>
                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('ppkha.detailArtikel', ['id' => $item->id]) }}">Selengkapnya..</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach


            </div>
            <div class="">
            {{ $artikel->appends(request()->query())->links() }}
        </div>
        </div>

    </div>
    

    @include('components.footer')
@endsection