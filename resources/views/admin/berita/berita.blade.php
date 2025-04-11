@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')

<div class="main-content d-flex flex-column align-items-center">
  <h1>Berita</h1>

  {{-- Form Pencarian --}}
  <div class="d-flex flex-row justify-content-center gap-2 w-100 mb-3">
    <form class="w-50 d-flex" action="{{ route('admin.berita.berita') }}" method="GET">
        <input type="text" id="berita" name="search" class="form-control" placeholder="Cari Berita..." value="{{ request('search') }}">
        <button type="submit" class="search-logo d-flex justify-content-center align-items-center">
            <i class='bx bx-search-alt-2'></i>
        </button>
    </form>
</div>

  
  {{-- Wrapper Berita --}}
  <div class="content-wrapper d-flex flex-column align-items-center w-100 gap-2" style="flex-grow: 1;">
    <div class="d-flex justify-content-end" style="width: 80%">
      <a href="{{ route('admin.berita.add') }}" class="btn btn-tambah mt-2">
        <i class='bx bx-plus-circle'></i> Tambah
      </a>
    </div>

    {{-- Daftar Berita --}}
    <div class="news-container w-100 d-flex flex-column align-items-center">
      @foreach ($berita as $item)
      <div class="background-card">
        <div class="card-information d-flex align-items-center px-3">
          {{-- Ambil gambar pertama jika tersedia --}}
          @php
              $gambarArray = $item->gambar ?? []; // Laravel otomatis mengubah JSON ke array
          @endphp

          @if (!empty($gambarArray) && isset($gambarArray[0]))
          <img style="width: 100px" src="{{ asset('storage/' . $gambarArray[0]) }}" alt="Gambar Berita">
          @else
          <img style="width: 100px" src="{{ asset('assets/images/image.png') }}" alt="Default Gambar">
          @endif

          <div class="ps-3 w-100">
            {{-- Judul Berita --}}
            <div class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-end">
              <h2 class="fst-italic roboto-title mb-0 align-self-center">
                {{ $item->judul_berita }}
              </h2>
              <div class="align-self-start">
              <div class="ms-auto d-flex gap-2">
                  <button type="button" id="btn-edit" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center" onclick="window.location.href='{{ route('admin.berita.beritaEdit', $item->id) }}'">
                      <i class='bx bx-pencil fs-5 me-2'></i> Edit
                  </button>
                  
                  <button type="button" id="btn-hapus" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center" onclick="openDeleteModal({{ $item->id }}, '{{ $item->judul_berita }}')">
                      <i class='bx bx-trash fs-5 me-2'></i> Hapus
                  </button>                      
              </div>


              </div>
            </div>

            <hr class="my-2 w-100" style="border: 3px solid black; opacity:1">

            {{-- Deskripsi Berita --}}
            <p class="roboto-light mb-1 mt-2" style="font-size: 15px">
              {{ Str::limit($item->deskripsi_berita, 200, '...') }}
            </p>

            {{-- Link ke Detail Berita --}}
            <div class="detail">
              <a href="{{ route('admin.berita.beritaDetail', ['id' => $item->id]) }}">Selengkapnya..</a>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    {{-- Pagination --}}
    <div class="pagination">  
    {{ $berita->appends(request()->query())->links() }}
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
