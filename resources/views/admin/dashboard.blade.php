@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')

<div class="main-content d-flex flex-column align-items-center">
    <h1>Tracer Study Dashboard</h1>

    <!-- Display the numeric stats -->
    <div class="d-flex flex-row justify-content-center gap-2 w-100 mt-3 mb-3">
        <div class="col text-center">
            <h3>Total Alumni</h3>
            <h4>{{ $totalAlumni }}</h4>
        </div>
        <div class="col text-center">
            <h3>Belum Mengisi</h3>
            <h4>{{ $belumMengisi }}</h4>
        </div>
        <div class="col text-center">
            <h3>Sudah Mengisi</h3>
            <h4>{{ $sudahMengisi }}</h4>
        </div>
    </div>

    <!-- CSV -->
    <div class="row mb-4 content-wrapper align-items-center w-50 flex-row">
        <div class="col text-center">
            <h4>Hasil Tracer Study</h4>
            <a href="{{ route('admin.unduh.csv', ['formId' => 1]) }}" class="btn btn-tambah mt-2 mb-0 pb-0">
                Unduh CSV Tracer Study
            </a>
        </div>
        <div class="col text-center">
            <h4>Hasil User Survey</h4>
            <a href="{{ route('admin.unduh.survey.csv', ['surveySectionId' => 1]) }}" class="btn btn-tambah mt-2 mb-0 pb-0">
                Unduh CSV User Survey
            </a>
        </div>
    </div>

    <section class="section dashboard">
        <div class="row" style="margin-bottom: 100px;">
            <!-- Pie chart -->
            <div class="col-md-6 mb-3">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h5 class="card-title">Perbandingan Pengisian Kuesioner</h5>
                        <canvas id="chartPengisianKuesioner"></canvas>
                    </div>
                </div>
            </div>
            <!-- Bar graph -->
            <div class="col-md-6 mb-3">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h5 class="card-title">Jumlah Mahasiswa Tiap Kategori</h5>
                        <canvas id="chartJumlahMahasiswa"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data for Chart 1: Jumlah Mahasiswa Tiap Kategori
            const jumlahMahasiswaData = @json($jumlah_mahasiswa_tiap_kategori);

            const configJumlahMahasiswa = {
                type: 'bar',
                data: {
                    labels: jumlahMahasiswaData.labels,
                    datasets: [{
                        label: 'Total Responden',
                        data: jumlahMahasiswaData.data,
                        backgroundColor: ['#6C5B7B', '#355C7D', '#C06C84', '#F8B195', '#F67280'],
                        borderColor: '#fff',
                        borderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const total = jumlahMahasiswaData.data.reduce((sum, value) => sum + value, 0);
                                    const value = tooltipItem.raw;
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${jumlahMahasiswaData.labels[tooltipItem.dataIndex]}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            };
            new Chart(document.getElementById('chartJumlahMahasiswa'), configJumlahMahasiswa);

            // Data for Chart 2: Perbandingan Pengisian Kuesioner
            const pengisianKuesionerData = @json($perbandingan_pengisian_questioner);

            const configPengisianKuesioner = {
                type: 'pie',
                data: {
                    labels: pengisianKuesionerData.labels,
                    datasets: [{
                        label: 'Total Responden',
                        data: pengisianKuesionerData.data,
                        backgroundColor: ['#556270', '#4ECDC4', '#C44D58', '#FF6B6B', '#2A363B'],
                        borderColor: '#333',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    const total = pengisianKuesionerData.data.reduce((sum, value) => sum + value, 0);
                                    const value = tooltipItem.raw;
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${pengisianKuesionerData.labels[tooltipItem.dataIndex]}: ${value} (${percentage}%)`;
                                },
                            },
                        },
                    },
                },
            };

            new Chart(document.getElementById('chartPengisianKuesioner'), configPengisianKuesioner);
        });
    </script>
    @endsection