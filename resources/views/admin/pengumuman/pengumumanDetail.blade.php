@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')

    <div class="main-content d-flex flex-column align-items-center">
        <h1>Pengumuman</h1>

        <div class="background-card" style="margin-bottom:100px">
            <div class="card-information d-flex align-items-center px-3">
                <div class="ps-3 w-100">
                    {{-- Judul Pengumuman dan tombol Edit & Hapus --}}
                    <div class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-center">
                        <h2 class="fst-italic roboto-title mb-0 align-self-center">
                            {{ $pengumuman->judul_pengumuman }}
                        </h2>

                        {{-- Tombol Edit dan Hapus --}}
                        <div class="align-self-start">
                            <a href="{{ route('admin.pengumuman.edit', ['id' => $pengumuman->id]) }}" class="btn" id="btn-edit">
                                <i class='bx bx-pencil'></i> 
                                <span class="d-none d-xl-inline ms-1">Edit</span>
                            </a>
                            <button type="button" id="btn-hapus" class="btn align-items-center" onclick="openDeleteModal({{ $pengumuman->id }}, '{{ $pengumuman->judul_berita }}')">
                                <i class='bx bx-trash fs-5 me-2'></i> Hapus
                            </button>
                        </div>
                    </div>

                    <hr class="my-2 w-100" style="border: 1.5px solid black; opacity: 1;">

                    {{-- Isi Pengumuman --}}
                    <p class="roboto-light mb-1 mt-2" style="font-size: 15px">
                        {!! nl2br(e($pengumuman->deskripsi_pengumuman)) !!}
                    </p>

                    
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

                    {{-- Tombol Kembali --}}
                    <div class="detail">
                        <a href="{{ route('admin.pengumuman.index') }}" class="btn px-3">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: linear-gradient(to bottom, #80C7D9, #446973);">
      <div class="modal-body d-flex flex-column align-items-center justify-content-center gap-4">
        
        <h5 class="modal-title" id="deleteModalLabel"></h5>
        <h2 class="text-center">Apakah anda yakin untuk menghapus <b id="pengumumanTitle"></b>?</h2>
        
        <div class="d-flex gap-3">
          <button type="button" class="btn btn-secondary btn-lg" data-bs-dismiss="modal" 
            style="padding: 15px 40px; font-size: 1.5rem; background: linear-gradient(to bottom, #80C7D9, #446973); border: none;">
            Cancel
          </button>

          <button type="button" class="btn btn-lg text-white" id="confirmDeleteButton" 
            style="padding: 15px 40px; font-size: 1.5rem; background: linear-gradient(to bottom, #80C7D9, #446973); border: none;">
            Yes
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

    <script>
        let selectedId = null;

        function openDeleteModal(id, title) {
            selectedId = id;
            document.getElementById('pengumumanTitle').innerText = title;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            fetch(`/admin/pengumuman/${selectedId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) throw new Error('Gagal menghapus data.');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        window.location.href = '{{ route('admin.pengumuman.index') }}';
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection

