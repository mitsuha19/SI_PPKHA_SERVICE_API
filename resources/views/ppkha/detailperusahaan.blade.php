@extends('layouts.app')

@section('content')
@include('components.navbar')

<div class="p-3 detail-content">
    <!-- Berita Section -->
    <div class="horizontal-card2 mt-4">
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
    </div>

    <div class="horizontal-card3 mt-4">
        <div class="horizontal-card-body3">
            <div class="text-container">
                <div class="horizontal-card-text-section3">
                    <h5 class="horizontal-card-title3">TENTANG KAMI</h5>
                    <p class="horizontal-card-text2">
                        {{ $perusahaan->deskripsiPerusahaan }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="horizontal-card3 mt-4">
        <h2 class="title">Lowongan</h2>
        @foreach ($lowongan as $job)
            <div style="border-bottom: 1px solid #000; padding: 10px;">
                <div style="display: flex; align-items: start; gap: 20px; padding: 10px;">
                <img style="height: 92px; width: auto;" src="{{ asset($perusahaan->logo ? 'storage/' . $perusahaan->logo : 'assets/images/default-logo.png') }}">
                    <div class="job-info" style="display: grid; gap: 10px;">
                        <h3>{{ $job->judulLowongan }}</h3>
                        <ul style="margin: 0; padding-left: 20px;">
                            <li>{{ $job->deskripsiLowongan }}</li>
                        </ul>
                    </div>

                    <a href="{{ route('ppkha.lowonganPekerjaanDetail', ['id' => $job->id]) }}" class="detail d-flex flex-row gap-1 align-items-center" style="margin-left: auto; text-decoration: none; color: black; font-weight: bold;">
                        Detail 
                        <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
                <div class="job-tags" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <span style="background: #f3f3f3; padding: 5px 10px; border-radius: 5px;">{{ strtoupper($perusahaan->lokasiPerusahaan) }}</span>
                    <span style="background: #f3f3f3; padding: 5px 10px; border-radius: 5px;">{{ $job->jenisLowongan }}</span>
                    <span style="background: #f3f3f3; padding: 5px 10px; border-radius: 5px;">{{ $job->tipeLowongan }}</span>
                </div>
            </div>
        @endforeach
    </div>
</div>

@include('components.footer')
@endsection
