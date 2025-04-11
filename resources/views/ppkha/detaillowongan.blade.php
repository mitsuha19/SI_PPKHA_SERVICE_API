@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <div class="p-3 detail-content">
        <!-- Berita Section -->
        <div class="message-lowongan montserrat-medium align-items-center">
            <i class='bx bx-md bx-message-error'></i>
            <p class="mb-0">Kamu dapat melamar lowongan ini pada {{ date('d M Y', strtotime($lowongan->batasMulai)) }} - {{ date('d M Y', strtotime($lowongan->batasAkhir)) }}</p>
        </div>

        <div class="horizontal-card2 mt-4">
            <div class="horizontal-card-body2">
                <!-- First Container: Image -->
                <div class="image-container">
                <img style="height: 92px; width: auto;" 
     src="{{ isset($lowongan->perusahaan) && $lowongan->perusahaan->logo ? asset('storage/' . $lowongan->perusahaan->logo) : asset('public\assets\images\image.png') }}" 
     alt="Logo Perusahaan"></div>

                <!-- Second Container: Text -->
                <div class="text-container">
                    <div class="horizontal-card-text-section2">
                        <h5 class="montserrat-medium mb-0" style="font-size: 36px;">{{ $lowongan->judulLowongan }}</h5>
                        <p class="montserrat-medium" style="font-size: 15px;">
                        @if ($lowongan->perusahaan)
        <a href="{{ route('ppkha.daftarPerusahaanDetail', ['id' => $lowongan->perusahaan->id]) }}" 
           class="text-decoration-none text-dark">
            {{ $lowongan->perusahaan->namaPerusahaan }}
        </a>
    @else
        Perusahaan tidak tersedia
    @endif<br>
                        <div class="text-row montserrat-medium" style="width: fit-content">
                            <div class="info-item">
                                <span class="text-label">Lokasi</span>
                                <span class="text-value">{{ $lowongan->perusahaan->lokasiPerusahaan ?? 'Lokasi tidak ada' }}</span>
                            </div>
                            <div class="info-item">
                                <span class="text-label">Departemen</span>
                                <span class="text-value">{{ $lowongan->jenisLowongan }}</span>
                            </div>
                            <div class="info-item">
                                <span class="text-label">Jenis Pekerjaan</span>
                                <span class="text-value">{{ $lowongan->tipeLowongan }}</span>
                            </div>
                        </div>
                        </p>
                    </div>
                </div>

                <!-- Third Container: Right Section -->
                <div class="right-section">
                    <button class="lamar-btn">Lamar</button>
                    <div class="share-section">
                        <button onclick="copyLink()" class="btn btn-primary">
                            Bagikan
                        </button>
                    </div>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" target="_blank">
                            <img src="{{ asset('assets/images/facebook-logo.png') }}" alt="Facebook">
                        </a>
                        <a href="https://www.instagram.com/direct/new/?text={{ urlencode('Cek lowongan ini: ' . request()->fullUrl()) }}" target="_blank">
                            <img src="{{ asset('assets/images/instagram.png') }}" alt="Instagram DM">
                        </a>
                       
                        <a id="whatsappShare" onclick="shareToWhatsAppStory()" target="_blank">
                            <img src="{{ asset('assets/images/Whatsapp-logo.png') }}" alt="WhatsApp">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="horizontal-card3 mt-4">
            <h5 class="montserrat-medium text-black mb-0" style="font-size: 28px;">Deskripsi Lowongan</h5>
            <hr class="mt-1">
            <p class="mb-0 montserrat-medium" style="font-size: 12px">
                {!! nl2br(e(Str::limit($lowongan->deskripsiLowongan, 100, '...'))) !!}
            </p>
        </div>

        <div class="horizontal-card3 mt-4">
            <h5 class="montserrat-medium text-black mb-0" style="font-size: 28px;">Kualifikasi</h5>
            <hr class="mt-1">
            <p class="mb-0 montserrat-medium" style="font-size: 12px">
                {!! nl2br(e($lowongan->kualifikasi ?: 'Belum ada kualifikasi dari lowongan pekerjaan ini')) !!}
            </p>
        </div>

        <div class="horizontal-card3 mt-4">
            <h5 class="montserrat-medium text-black mb-0" style="font-size: 28px;">Benefit</h5>
            <hr class="mt-1">
            <p class="mb-0 montserrat-medium" style="font-size: 12px">
                {!! nl2br(e($lowongan->benefit ?: 'Belum ada benefit dari lowongan pekerjaan ini')) !!}
            </p>
        </div>

        <div class="horizontal-card3">
            <div class="horizontal-card-text-section3">
                <h5 class="montserrat-medium text-black mb-0" style="font-size: 28px;">Keahlian</h5>
                <hr class="mt-1">
                <div class="skills-container gap-3">
                    @php
                        $keahlian = !empty($lowongan->keahlian) ? explode(',', $lowongan->keahlian) : [];
                    @endphp

                    @if(count($keahlian) > 0)
                        @foreach($keahlian as $skill)
                            <span class="skill-badge">{{ trim($skill) }}</span>
                        @endforeach
                    @else
                        <p>Belum ada keahlian yang dicantumkan.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyLink() {
            var link = window.location.href;
            var tempInput = document.createElement("input");
            tempInput.value = link;
            document.body.appendChild(tempInput);
            tempInput.select();
            tempInput.setSelectionRange(0, 99999);
            document.execCommand("copy");
            document.body.removeChild(tempInput);
            alert("Link telah disalin!");

            var whatsappLink = "https://wa.me/?text=" + encodeURIComponent("Cek lowongan ini: " + link);
            document.getElementById("whatsappShare").href = whatsappLink;
        }

        function shareToWhatsAppStory() {
            var text = "Cek lowongan ini: " + window.location.href;
            var whatsappUrl = "https://api.whatsapp.com/send?text=" + encodeURIComponent(text);
            window.open(whatsappUrl, '_blank');
        }
    </script>

    @include('components.footer')
@endsection

