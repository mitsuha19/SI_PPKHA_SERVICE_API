@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content d-flex flex-column align-items-start gap-3">
    
  <div class="message-lowongan montserrat-medium align-items-center">
    <i class='bx bx-md bx-message-error'></i>
    <p class="mb-0">Kamu dapat melamar lowongan ini pada {{ date('d M Y', strtotime($lowongan->batasMulai)) }} - {{ date('d M Y', strtotime($lowongan->batasAkhir)) }}</p>
  </div>

  <div class="card-lowongan d-flex flex-row align-items-center gap-5">
  <img style="height: 92px; width: auto;" 
     src="{{ isset($lowongan->perusahaan) && $lowongan->perusahaan->logo ? asset('storage/' . $lowongan->perusahaan->logo) : asset('public\assets\images\image.png') }}" 
     alt="Logo Perusahaan">

    <div class="montserrat-medium mb-0">
    <h2>{{ $lowongan->perusahaan->namaPerusahaan ?? 'Tidak ada perusahaan' }}</h2>
      <p>{{ $lowongan->perusahaan->lokasiPerusahaan ?? 'Lokasi tidak ada' }}</p>
      <div class="d-flex flex-row" style="gap: 100px;">
        <p><span style="color: #656565;">Lokasi</span><br>{{ $lowongan->perusahaan->lokasiPerusahaan ?? 'Lokasi Tidak ada'}}</p>
        <p><span style="color: #656565;">Departemen</span><br>{{ $lowongan->jenisLowongan }}</p>
        <p><span style="color: #656565;">Jenis-Pekerjaan</span><br>{{ $lowongan->tipeLowongan }}</p>
      </div>
    </div>
  </div>

  <div class="card-lowongan-about d-flex flex-row align-items-center gap-5">
    <div class="montserrat-medium mb-0 w-100">
      <h2 class="mb-0">Deskripsi Lowongan</h2>
      <hr style="opacity:1; margin: 10px 0px">
      <p class="roboto-light text-black mb-1" style="font-size: 15px">
        {!! nl2br(e($lowongan->deskripsiLowongan)) !!}
      </p>
    </div>
</div>


  <div class="card-lowongan-about d-flex flex-row align-items-center gap-5">
    <div class="montserrat-medium mb-0 w-100">
      <h2 class="mb-0">Kualifikasi</h2>
      <hr style="opacity:1; margin: 10px 0px">
      <p class="align-items-center">{!! nl2br(e($lowongan->kualifikasi ?: 'Belum ada kualifikasi dari lowongan pekerjaan ini')) !!}
      </p>
    </div>
  </div>

  <div class="card-lowongan-about d-flex flex-row align-items-center gap-5">
    <div class="montserrat-medium mb-0 w-100">
      <h2 class="mb-0">Benefit</h2>
      <hr style="opacity:1; margin: 10px 0px">
      <p class="align-items-center">{!! nl2br(e($lowongan->benefit ?: 'Belum ada benefit dari lowongan pekerjaan ini')) !!}
      </p>
    </div>
  </div>

  <div class="card-lowongan-about d-flex flex-row align-items-center gap-5">
    <div class="montserrat-medium mb-0 w-100">
        <h2 class="mb-0">Keahlian</h2>
        <hr style="opacity:1; margin: 10px 0px">
        <div class="d-flex gap-2 flex-wrap">
            @php
                $keahlian = !empty($lowongan->keahlian) ? explode(',', $lowongan->keahlian) : [];
            @endphp

            @if(count($keahlian) > 0)
                @foreach($keahlian as $skill)
                    <div class="pills">{{ trim($skill) }}</div>
                @endforeach
            @else
                <p>Belum ada keahlian yang dicantumkan.</p>
            @endif
        </div>
    </div>
</div>


  <div class="align-self-end justify-self-end" style="margin-bottom: 100px">
    <a href="{{ route('admin.lowonganPekerjaan.lowonganPekerjaan') }}" class="btn btn-primary">Kembali</a>
  </div>
</div>
@endsection
