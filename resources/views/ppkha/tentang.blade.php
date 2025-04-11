@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <div class="content-with-background">
        @include('components.bg2')
        <div class="tentang-section">
            <img src="{{ asset('assets/images/itdel.png') }}" alt="Logo IT Del" width="200" height="auto">
            <h2>INSTITUT TEKNOLOGI DEL</h2>
        </div>

        <!-- Nilai-Nilai Del Section (on white background) -->
        <div class="tentang-content-section">
            <h3 class="tentang-section-title">NILAI-NILAI DEL</h3>
            <div class="tentang-nilai-container">
                <img src="{{ asset('assets/images/Roda.png') }}" width="500" height="auto" alt="3M"">
            </div>
        </div>

        <!-- Second Wave Section (Dynamic Stretching) -->
        <div class="tentang-second-wave-container">
            @include('components.bgMid2')
            <div class="tentang-content-on-wave">
                <!-- Visi & Misi Section -->
                <div class="tentang-content-section">
                    <h3 class="tentang-section-title">VISI & MISI</h3>
                    <div class="tentang-visi-misi-container">
                        <div class="visi-card">
                            <p>“Menjadi pusat keunggulan yang berperan dalam pemanfaatan teknologi bagi kemajuan bangsa”</p>
                        </div>
                        <div class="misi-card">
                            <ol>
                                <li>Menyelenggarakan dan mengembangkan proses pendidikan yang unggul, berkeunggulan, dan
                                    bermanfaat bagi masyarakat.</li>
                                <li>Mengembangkan, menciptakan dan meningkatkan ilmu pengetahuan dan teknologi.</li>
                                <li>Meningkatkan peran institut agar mampu menjadi pembaharu kemajuan, keterampilan pilihan
                                    rujukan dan keunggulan dalam berbagai bidang ilmu pengetahuan dan teknologi.</li>
                                <li>Meningkatkan peran nyata kepedulian masyarakat melalui penelitian Tridharma Perguruan
                                    Tinggi.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                <div class="tentang-content-section">
                    <h3 class="tentang-section-title">FAKULTAS & PROGRAM STUDI</h3>
                    <div class="tentang-fakultas-container">
                        <!-- Card 1: Fakultas Informatika Dan Teknik Elektro (FITE) -->
                        <div class="tentang-fakultas-box">
                            <h4>Fakultas Informatika Dan Teknik Elektro (FITE)</h4>
                            <ul>
                                <li>Informatika (S1)</li>
                                <li>Sistem Informasi (S1)</li>
                                <li>Teknik Elektro (S1)</li>
                            </ul>
                        </div>
                        <!-- Card 2: Fakultas Teknologi Industri (FTI) -->
                        <div class="tentang-fakultas-box">
                            <h4>Fakultas Teknologi Industri (FTI)</h4>
                            <ul>
                                <li>Manajemen Rekayasa (S1)</li>
                                <li>Teknik Metalurgi (S1)</li>
                            </ul>
                        </div>
                        <!-- Card 3: Fakultas Vokasi -->
                        <div class="tentang-fakultas-box">
                            <h4>Fakultas Vokasi</h4>
                            <ul>
                                <li>Teknologi Informasi (D3)</li>
                                <li>Teknologi Komputer (D3)</li>
                                <li>Teknologi Rekayasa Perangkat Lunak (D4)</li>
                            </ul>
                        </div>
                        <!-- Card 4: Fakultas Bioteknologi -->
                        <div class="tentang-fakultas-box">
                            <h4>Fakultas Bioteknologi</h4>
                            <ul>
                                <li>Teknik Bioproses (S1)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('components.footer')
@endsection
