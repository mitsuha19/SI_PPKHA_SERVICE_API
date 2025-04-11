@extends('layouts.app')

@section('content')
@include('components.navbar')
<div class="detail-content">
    <div>
        <h1 class="roboto-light mb-0" style="font-style: italic; color: #0F1035; font-weight: 500; font-size: 45px;">
            {{ $artikel->judul_artikel }}
        </h1>
        <hr>
        <p style="font-family: 'Roboto Mono', serif; font-size: 18px; font-weight: 400; color: white" class="mb-1">
            {{ date('d M Y H:i:s', strtotime($artikel->updated_at)) }} WIB
        </p>
    </div>
    <div class="max-width d-flex justify-content-center">
        @php
            $gambarArray = is_string($artikel->gambar) ? json_decode($artikel->gambar, true) : $artikel->gambar;
            $gambarArray = is_array($gambarArray) ? $gambarArray : [];
        @endphp
        @if (!empty($gambarArray) && isset($gambarArray[0]))
            <img src="{{ asset('storage/' . $gambarArray[0]) }}">
        @else
            <img src="{{ asset('assets/images/image.png') }}">
        @endif
    </div>
    <div class="p-4">
        <p style="font-family: 'Roboto Mono', serif; font-weight: 500; color: white;">
            {{ $artikel->deskripsi_artikel }}
            <br> <br>
          Sumber : <br>
          <a class="text-white" href="{{ $artikel->sumber_artikel }}"> {{ $artikel->sumber_artikel }}</a>
        </p>
    </div>
    
    <div class="pengumuman-section">
        <h2 class="section-title">Rekomendasi Artikel</h2>
        <div class="pengumuman-grid">
            @foreach ($artikelRekomendasi as $item)
            <div class="card" style="width: 18rem;">
                @php
                    $gambarArray = $item->gambar ?? [];
                @endphp
                @if (!empty($gambarArray) && isset($gambarArray[0]))
                    <img src="{{ asset('storage/' . $gambarArray[0]) }}" alt="Gambar Artikel">
                @else
                    <img src="{{ asset('assets/images/image.png') }}" alt="Default Gambar">
                @endif
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $item->judul_artikel }}
                    </h5>
                    <a href="{{ route('ppkha.detailArtikel', ['id' => $item->id]) }}">Selengkapnya..</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@include('components.footer')
@endsection
