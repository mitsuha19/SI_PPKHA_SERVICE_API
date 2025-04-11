@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <!-- Main Content with Background -->
    <div class="content-with-background">
        @include('components.bg')

        <!-- Hero Container (Beranda Section) -->
        <div class="hero-container">
            <div class="grid-container">
                <!-- Left: Text Section -->
                <div class="text-section">
                    <h1 class="poppins-semibold mb-0 text-black" style="font-size: 48px">Selamat Datang,</h1>
                    <div class="d-flex align-items-center gap-3 mb-0">
                        <p class="poppins-semibold text-black mb-0" style="font-size: 32px">Di</p>
                        <h2 class="poppins-semibold highlight mb-0">CAIS</h2>
                    </div>
                    <p class="subheading poppins-semibold">Career Alumni Information System</p>
                    <p class="description roboto-title m-0 w-100" style="font-size: 15px">
                        {{ old('deskripsi_beranda', $beranda->deskripsi_beranda) }}
                    </p>
                </div>

                <!-- Right: Image Section -->
                <div class="image-section">
                    <img src="{{ asset('assets/images/tugu_Del.png') }}" alt="Monument">
                </div>
            </div>
        </div>

        <!-- Berita Section -->
        <div class="berita-section">
            <h2 class="section-title">BERITA</h2>
            <div class="berita-grid">
            

                <!-- Static Cards -->
                @foreach ($berita as $item)
                <div class="bg-card">
                    <div class="card" style="width: 18rem;">
                    {{-- Ambil gambar pertama jika tersedia --}}
                    @php
                            $gambarArray = $item->gambar ?? []; // Laravel otomatis mengubah JSON ke array
                        @endphp

                        @if (!empty($gambarArray) && isset($gambarArray[0]))
                            <img src="{{ asset('storage/' . $gambarArray[0]) }}" class="card-img-top" alt="Berita">
                        @else
                            <img src="{{ asset('assets/images/image.png') }}" class="card-img-top" alt="artikel">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title text-start roboto-title">{{ $item->judul_berita }}</h5>
                            <p class="roboto-light text-white" style="font-size: 14px; text-align: justify;"> 
                                {{ Str::limit($item->deskripsi_berita, 200, '...') }}
                            </p>
                            <div class="d-flex justify-content-end ">
                            <a href="{{ route('ppkha.detailBerita', ['id' => $item->id]) }}">Selengkapnya..</a>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                

                

            </div>
        </div>

        <!-- Pengumuman Section -->

        <div class="pengumuman-section">
            <h2 class="section-title">PENGUMUMAN</h2>
            <div class="pengumuman-grid">
                

                <!-- Static Cards -->
                @foreach ($pengumuman as $item)
                <div class="bg-card">
                    <div class="card" style="width: 18rem;">
                        <img src="{{ asset('assets/images/image.png') }}" class="card-img-top" alt="Pengumuman 1">
                        <div class="card-detail">
                            <h5 class="card-title text-center roboto-title mb-3">{{ $item->judul_pengumuman }}</h5>
                            <div class="d-flex justify-content-end">
                            <a href="{{ route('ppkha.pengumumanDetail', ['id' => $item->id]) }}">Selengkapnya..</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>
        </div>

        <!-- Artikel Section -->
        <div class="pengumuman-section">
            <h2 class="section-title">ARTIKEL</h2>
            <div class="pengumuman-grid">
                <!-- Static Cards -->
                @foreach ($artikel as $item)
                <div class="card" style="width: 18rem;">
                        @php
                            $gambarArray = $item->gambar ?? []; // Laravel otomatis mengubah JSON ke array
                        @endphp

                        @if (!empty($gambarArray) && isset($gambarArray[0]))
                            <img src="{{ asset('storage/' . $gambarArray[0]) }}" class="card-img-top" alt="Artikel">
                        @else
                            <img src="{{ asset('assets/images/image.png') }}" class="card-img-top" alt="artikel">
                        @endif

                    <div class="card-body">
                        <h5 class="card-title">{{ $item->judul_artikel }}</h5>
                        <div class="d-flex justify-content-end">
                        <a href="{{ route('ppkha.detailArtikel', ['id' => $item->id]) }}">Selengkapnya..</a>
                    </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>

    @include('components.footer')
@endsection
