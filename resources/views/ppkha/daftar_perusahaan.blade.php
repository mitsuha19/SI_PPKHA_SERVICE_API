@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <div class="content-with-background">
        @include('components.bg') <!-- Renders the background waves -->
        
        <!-- Top Search Bar Section (New, positioned at the top of content) -->
        <div class="top-search-bar-container">
            <div class="top-search-bar">
                <input type="text" class="top-search-input" placeholder="Cari Nama Perusahaan" />
                <button class="top-search-button">
                    <i class='bx bx-search bx-sm'></i>
                </button>
            </div>
        </div>
        
        {{-- Daftar Perusahaan Section --}}
        <div class="d-flex flex-column align-items-center gap-4">

            @foreach($perusahaan as $p)
            <div class="background-card">
                <div class="card-information d-flex align-items-center px-3">
                    <img src="{{ $p->logo ? asset('storage/' . $p->logo) : asset('assets/images/default-logo.png') }}">
                    <div class="ps-3 w-100">
                        <div class="horizontal-card-text-section card-detail-perusahaan">

                              <div class="d-flex flex-row w-auto justify-content-between align-items-center w-100">
                                <h5 class="horizontal-card-title fw-bold mb-0">
                                    {{ $p->namaPerusahaan }}
                                </h5>
                                <div class="d-flex flex-row justify-content-center align-items-center gap-1 detail">
                                    <a href="{{ route('ppkha.daftarPerusahaanDetail', ['id' => $p->id]) }}">Detail </a>
                                    <i class='bx bx-sm bx-right-arrow-alt'></i>       
                                </div>
                              </div>
                             
                            <hr class="my-1" style="border: 2px solid black; opacity: 1">
  
                            <div class="d-flex flex-row align-items-center mb-2" style="gap: 5px">
                              <p class="mb-0 montserrat-light">{{ $p->lokasiPerusahaan }}</p>
                              <div class="circle"></div>
                              <p class="mb-0 montserrat-medium">{{ $p->industriPerusahaan }}</p>
                            </div>
                            <p class="horizontal-card-text">
                                {{ $p->deskripsiPerusahaan }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="load-more-container">
            <button class="load-more-btn">Muat Lebih Banyak</button>
        </div>
    </div>

    @include('components.footer')
@endsection