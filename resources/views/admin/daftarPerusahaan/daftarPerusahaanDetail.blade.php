@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content d-flex flex-column align-items-start gap-3">
  <div class="card-perusahaan d-flex flex-row align-items-center gap-5">
    <img style="height: 92px; width: auto;" src="{{ asset($perusahaan->logo ? 'storage/' . $perusahaan->logo : 'assets/images/default-logo.png') }}">
    <div class="montserrat-medium mb-0">
      <h2>{{ $perusahaan->namaPerusahaan }}</h2>
      <p>{{ $perusahaan->lokasiPerusahaan }}</p>
      <div class="d-flex flex-row" style="gap: 100px;">
        <p><span style="color: #656565;">Industri</span><br>{{ $perusahaan->industriPerusahaan }}</p>
        <p><span style="color: #656565;">Website</span><br><a href="{{ $perusahaan->websitePerusahaan }}" target="_blank">{{ $perusahaan->websitePerusahaan }}</a></p>
      </div>
    </div>
  </div>

  <div class="card-perusahaan-about d-flex flex-row align-items-center gap-5">
    <div class="montserrat-medium mb-0">
      <h2 class="mb-0">Tentang Kami</h2>
      <hr style="opacity:1; margin: 10px 0px">
      <p>
         {!! nl2br(e($perusahaan->deskripsiPerusahaan)) !!}
      </p>
    </div>
  </div>
  
  <div class="card-perusahaan-about d-flex flex-row align-items-center gap-5">
    <div class="montserrat-medium mb-0 w-100">
      <h2 class="mb-0">Lowongan</h2>
      <hr style="opacity:1; margin: 10px 0px">
      @forelse ($lowongan as $job)
        <div class="mb-2">
          <div class="d-flex flex-row mb-3">
            <img style="width: 100px;" src="{{ asset($perusahaan->logo ? 'storage/' . $perusahaan->logo : 'assets/images/default-logo.png') }}">
            <div class="d-flex flex-column">
              <h3 class="mb-0 ps-3">{{ $job->judulLowongan }}</h3>
              <ul class="roboto-light text-black mb-1" style="font-size: 15px">
                
                  {!! nl2br(e(Str::limit($job->deskripsiLowongan, 50, '...'))) !!}
                
              </ul>
            </div>
          </div>
          <div class="d-flex flex-row gap-2 flex-wrap">
            <div class="pills">{{ strtoupper($job->perusahaan->lokasiPerusahaan) }}</div>
            <div class="pills">{{ $job->jenisLowongan }}</div>
            <div class="pills">{{ $job->tipeLowongan }}</div>
          </div>
          <hr style="opacity:1; margin: 10px 0px">
        </div>
      @empty
        <p>Tidak ada lowongan tersedia saat ini.</p>
      @endforelse
    </div>
  </div>

  <div class="align-self-end justify-self-end" style="margin-bottom: 100px">
    <a href="{{ route('admin.daftarPerusahaan.daftarPerusahaan') }}" class="btn btn-primary">Kembali</a>
  </div>
</div>
@endsection
