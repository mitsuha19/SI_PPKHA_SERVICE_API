@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <div class="content-with-background">
        <div class="first-section">
            <div class="welcome-grid">
                <div class="welcome-text">
                    <h1>Selamat Datang di</h1>
                    <h2>Tracer Study <span class="welcome-text h2 span"> Institut Teknologi Del </span> </h2>
                    <p>Bantu kami meningkatkan kualitas pendidikan dengan berpartisipasi dalam Tracer Study
                        <span>"Pioneering the Path to Success"</span>
                    </p>
                    <a href="{{ route('kuesioner.show') }}" class="start-button">Mulai Sekarang</a>
                </div>
                <div class="welcome-image">
                    <img src="{{ asset('assets/images/first-section.png') }}" alt="Graduates Celebrating">
                </div>
            </div>
        </div>


        <div class="second-section">
            @include('components.bgMid')
            <div class="tracer-study-info">
                <h3>Apa itu Tracer Study?</h3>
                <p>Tracer Study di Institut Teknologi Del berperan penting dalam mempererat hubungan antara institusi dan
                    alumni. Survei ini memberikan wawasan tentang perjalanan karir alumni serta harapan mereka terhadap
                    pengembangan pendidikan. Selain meningkatkan kualitas pendidikan, Tracer Study juga memperkuat jaringan
                    alumni yang saling mendukung dalam karir dan kontribusi terhadap kemajuan kampus, serta membuka peluang
                    bagi alumni untuk berpartisipasi dalam pengembangan almamater.</p>
            </div>
        </div>

        <div class="third-section">
            <h3 class="section-title">Manfaat dan Tujuan</h3>
            <div class="benefit-grid">
                <div class="manfaat-cards">
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <img src="{{ asset('assets/images/akreditasi.png') }}" alt="Accreditation Icon">
                        </div>
                        <div class="benefit-text">
                            <h4>Akreditasi Perguruan Tinggi</h4>
                            <p>Pengisian Tracer Study digunakan sebagai dasar menghitung capaian Indikator Kinerja Utama
                                yang memperngaruhi pemeringkatan perguruan tinggi.</p>
                        </div>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <img src="{{ asset('assets/images/save.png') }}" alt="Evaluation Icon">
                        </div>
                        <div class="benefit-text">
                            <h4>Evaluasi Relevansi Kurikulum dan Dunia Kerja</h4>
                            <p>Data Tracer Study digunakan sebagai bahan evaluasi kurikulum pada setiap program studi agar
                                sesuai kebutuhan dunia kerja secara.</p>
                        </div>
                    </div>
                    <div class="benefit-card">
                        <div class="benefit-icon">
                            <img src="{{ asset('assets/images/link.png') }}" alt="Network Icon">
                        </div>
                        <div class="benefit-text">
                            <h4>Membangun Jejaring Alumni</h4>
                            <p>Data Tracer Study dapat digunakan untuk mengembangkan persebaran alumni dalam rangka
                                membangun jejaring komunitas alumni di Indonesia atau dunia.</p>
                        </div>
                    </div>
                </div>
                <div class="manfaat-picture">
                    <img src="{{ asset('assets/images/second_section.png') }}" alt="Graduates Celebrating">
                </div>
            </div>

            <h3 class="section-title">Metode dan Konsep Tracer Study</h3>
            <div class="method-section">
                <p class="method-description">Sistem pelacakan alumni di Institut Teknologi Del disebut Tracer Study. Sistem
                    ini dilakukan oleh PPKHA IT Del dan bertujuan untuk memperoleh informasi dari alumni untuk meningkatkan
                    kualitas pendidikan dan menjaga hubungan yang baik antara kampus dan lulusan.</p>
                <div class="method-grid">
                    <div class="method-card">
                        <div class="method-icon">
                            <img src="{{ asset('assets/images/icon2.png') }}" alt="Data Icon">
                        </div>
                        <h4>Data Tracer Study dapat digunakan untuk membantu persebaran alumni dalam rangka membangun
                            jejaring komunitas alumni di Indonesia atau dunia.</h4>
                    </div>
                    <div class="method-card">
                        <div class="method-icon">
                            <img src="{{ asset('assets/images/icon1.png') }}" alt="Management Icon">
                        </div>
                        <h4>Pengelola prodi menghubungi lulusan untuk memberikan jawaban atas pertanyaan tracer study.</h4>
                    </div>
                    <div class="method-card">
                        <div class="method-icon">
                            <img src="{{ asset('assets/images/icon3.png') }}" alt="Community Icon">
                        </div>
                        <h4>Himpunan mahasiswa mengirim broadcast message melalui media populer seperti LINE, Instagram, dan
                            WhatsApp.</h4>
                    </div>
                </div>
            </div>

            <h1 class="footer-title">Ayo Berkontribusi dalam Tracer Study</h1>
        </div>

        @include('components.bgBtm') <!-- Renders the background waves -->
    </div>

    @include('components.footer')
@endsection
