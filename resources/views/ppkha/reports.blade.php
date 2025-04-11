@extends('layouts.app')

@section('title', 'Tracer Study Report')

@section('content')
<section class="report-section">
  <div class="container">
      <h2>Tracer Study Report</h2>
      <p>Pilih tahun angkatan untuk melihat hasil tracer study.</p>
      <div class="year-cards">
          <!-- Card Tahun 2024 -->
          <div class="year-card">
              <h3>Angkatan 2022</h3>
              <p>Lihat hasil tracer study untuk angkatan 2022.</p>
              <a href="{{ route('report.2022') }}" class="btn">Lihat Laporan</a>
          </div>
          <!-- Card Tahun 2023 -->
          <div class="year-card">
              <h3>Angkatan 2021</h3>
              <p>Lihat hasil tracer study untuk angkatan 2021.</p>
              <a href="{{ route('report.2021') }}" class="btn">Lihat Laporan</a>
          </div>
          <!-- Card Tahun 2022 -->
          <div class="year-card">
              <h3>Angkatan 2020</h3>
              <p>Lihat hasil tracer study untuk angkatan 2020.</p>
              <a href="{{ url('/report-show') }}" class="btn">Lihat Laporan</a>
          </div>
          <!-- Tambahkan lebih banyak card sesuai kebutuhan -->
      </div>
  </div>
</section>
@endsection