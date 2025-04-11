@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content d-flex flex-column align-items-center">
  <h1>Berita</h1>

  <div class="background-card" style="margin-bottom:100px">
    <div class="card-information d-flex align-items-center px-3">
      <div class="ps-3 w-100">
        {{-- Judul Berita dan button Edit dan Delete --}}
        <div class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-center">
          <h2 class="fst-italic roboto-title mb-0 align-self-center">
            {{ $berita->judul_berita }}
          </h2>
  
          {{-- Button --}}
          <div class="align-self-start">
            <a href="{{ route('admin.berita.beritaEdit', ['id' => $berita->id]) }}" class="btn" id="btn-edit">
              <i class='bx bx-pencil'></i> 
              <span class="d-none d-xl-inline ms-1">Edit</span>
            </a>
            <button type="button" id="btn-hapus" class="btn align-items-center" onclick="openDeleteModal({{ $berita->id }}, '{{ $berita->judul_berita }}')">
                      <i class='bx bx-trash fs-5 me-2'></i> Hapus
                  </button>
          </div>
        </div>

        <hr class="my-2" style="border: 1.5px solid black; opacity : 1;">

        {{-- Carousel untuk Gambar --}}
        <div class="w-100 d-flex justify-content-center m-2">
          <div id="beritaCarousel" style="width: 80%" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
              @php
                $gambarArray = is_string($berita->gambar) ? json_decode($berita->gambar, true) : $berita->gambar;
                $gambarArray = is_array($gambarArray) ? $gambarArray : [];
              @endphp
              @foreach ($gambarArray as $index => $gambar)
                <button type="button" data-bs-target="#beritaCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}" aria-label="Slide {{ $index + 1 }}"></button>
              @endforeach
            </div>
            <div class="carousel-inner">
              @foreach ($gambarArray as $index => $gambar)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                  <img style="width: 100%" src="{{ asset('storage/' . $gambar) }}" class="d-block w-100">
                </div>
              @endforeach
              @if (empty($gambarArray))
                <div class="carousel-item active">
                  <img style="width: 100%" src="{{ asset('assets/images/image.png') }}" class="d-block w-100">
                </div>
              @endif
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#beritaCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#beritaCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
        </div>
        

        {{-- Deskripsi --}}
        <p class="roboto-light mb-1 mt-2" style="font-size: 15px">
          {{ $berita->deskripsi_berita }}
        </p>

        {{-- Link ke berita --}}
        <div class="detail">
          <a href="{{ route('admin.berita.berita') }}" class="btn px-3">Kembali</a>
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
        <h2 class="text-center">Apakah anda yakin untuk menghapus <b id="beritaTitle"></b>?</h2>
        
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
      document.getElementById('beritaTitle').innerText = title;
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
  }

  document.getElementById('confirmDeleteButton').addEventListener('click', function() {
      fetch(`/admin/berita/${selectedId}`, {
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
                  window.location.href = '{{ route('admin.berita.berita') }}';
              } else {
                  console.error(data.message);
              }
          })
          .catch(error => console.error('Error:', error));
  });
</script>
@endsection
