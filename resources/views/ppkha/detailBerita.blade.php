@extends('layouts.app')

@section('content')
@include('components.navbar')
<div class="detail-content">
  <div>
    <h1 class="roboto-light mb-0" style="font-style: italic; color: #0F1035; font-weight: 500; font-size: 45px;">
      {{ $berita->judul_berita }}
    </h1>
    <hr>
    <p style = "font-family: 'Roboto Mono', serif ; font-size : 18px; font-weight: 400; color: white" class="mb-1">
      {{ date('d M Y H:i:s', strtotime($berita->updated_at)) }} WIB
    </p>
  </div>
  
  {{-- Carousel untuk Gambar --}}
        <div class="w-100 d-flex justify-content-center m-2">
          <div id="beritaCarousel" style="width: 40%" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              @php
                $gambarArray = is_string($berita->gambar) ? json_decode($berita->gambar, true) : $berita->gambar;
                $gambarArray = is_array($gambarArray) ? $gambarArray : [];
              @endphp
              @foreach ($gambarArray as $index => $gambar)
                <button type="button" data-bs-target="#beritaCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
              @endforeach
            </div>
            <div class="carousel-inner">
              @foreach ($gambarArray as $index => $gambar)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                  <img style="width: 100%" src="{{ asset('storage/' . $gambar) }}" class="d-block w-100">
                </div>
              @endforeach
              @if (empty($gambarArray))
                <div class="carousel-item active">
                  <img style="width: 100%" src="{{ asset('assets/images/image.png') }}" class="d-block w-100">
                </div>
              @endif
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#beritaCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#beritaCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
  
  <div class="p-4">
    <p style="font-family: 'Roboto Mono', serif; font-weight: 500; color: white;">     
      {{ $berita->deskripsi_berita }}
    </p>
  </div>
</div>
@include('components.footer')
@endsection
