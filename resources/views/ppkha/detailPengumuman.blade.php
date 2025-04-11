@extends('layouts.app')

@section('content')
@include('components.navbar')
<div class="p-3 detail-content">
  <div>
    <h1 class="roboto-light mb-0" style="font-style: italic; color: #0F1035; font-weight: 500; font-size: 45px;">{{ $pengumuman->judul_pengumuman }}</h1>
    <hr>
    <p style = "font-family: 'Roboto Mono', serif ; font-size : 18px; font-weight: 400; color: white" class="mb-1">
    {{ date('d M Y H:i:s', strtotime($pengumuman->updated_at)) }} WIB
    </p>
  </div>
  
  <div class="p-4">
    <p style="font-family: 'Roboto Mono', serif; font-weight: 500; color: white;">     
    {!! nl2br(e($pengumuman->deskripsi_pengumuman)) !!}
   </p>
  </div>

  <h5 style="font-size: 1.2rem; font-weight: bold; margin-bottom: 20px; color: white;">Nama File:</h5>
  <hr>

  @php
    $lampiran = json_decode($pengumuman->lampiran, true) ?? [];
@endphp

@if (!empty($lampiran) && is_array($lampiran) && count($lampiran))
<ul style="font-size: 1rem; color: white; list-style: none; padding: 0;">
@foreach ($lampiran as $file)
            <li style="margin-bottom: 10px;">
                @if (Storage::disk('public')->exists($file))
                    <a href="{{ asset('storage/' . $file) }}" target="_blank"
                        style="color: white;">
                        {{ basename($file) }}
                    </a>
                @else
                    <span style="color: #999;">File tidak tersedia</span>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p style="font-size: 1rem; color: #777;">Tidak ada lampiran tersedia.</p>
@endif


</div>
@include('components.footer')
@endsection
