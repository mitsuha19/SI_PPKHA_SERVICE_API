@extends('layouts.appAdmin')

@section('content')
@include('components.navbarAdmin')
<div class="main-content d-flex flex-column align-items-center">
<h1>Lowongan Pekerjaan</h1>

{{-- Form Pencarian --}}
  <div class="d-flex flex-row justify-content-center gap-2 w-100 mb-3">
    <form class="w-50 d-flex" action="{{ route('admin.lowonganPekerjaan.lowonganPekerjaan') }}" method="GET">
        <input type="text" id="lowongan" name="search" class="form-control" placeholder="Cari Lowongan Pekerjaan..." value="{{ request('search') }}">
        <button type="submit" class="search-logo d-flex justify-content-center align-items-center">
            <i class='bx bx-search-alt-2'></i>
        </button>
    </form>
</div>

<div class="d-flex flex-column align-items-center w-100 gap-2">
  <div class="d-flex justify-content-end" style="width: 80%">
    <a href="{{ route('admin.lowonganPekerjaan.add') }}" class="btn btn-tambah mt-2">
      <i class='bx bx-plus-circle'></i> Tambah
    </a>
  </div>

    @foreach($lowongan as $l)
        <div class="background-card">
        <div class="card-information d-flex align-items-center px-3">
            @if ($l->perusahaan && $l->perusahaan->logo)
                <img style="width: 100px" src="{{ asset('storage/' . $l->perusahaan->logo) }}" alt="Logo Perusahaan">
            @endif

                <div class="ps-3 w-100">
                    <div class="d-flex flex-row w-auto justify-content-start align-items-center">
                        <h2 class="fst-italic roboto-title mb-0 align-self-center">
                            {{ $l->judulLowongan }}
                        </h2>

                       
                        <div class="align-self-start">
                                    <div class="ms-auto d-flex gap-2">
                            <button type="button" id="btn-edit" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center" onclick="window.location.href='{{ route('admin.lowonganPekerjaan.edit', $l->id) }}'">
                                <i class='bx bx-pencil fs-5 me-2'></i> Edit
                            </button>
                            
                            <button type="button" id="btn-hapus" class="btn btn-primary px-4 py-2 fw-bold d-flex align-items-center" onclick="openDeleteModal({{ $l->id }}, '{{ $l->judulLowongan }}')">
                                <i class='bx bx-trash fs-5 me-2'></i> Hapus
                            </button>                      
                        </div>
                        </div>
                    
                    </div>

                    <hr class="my-2" style="border: 2px solid black; opacity: 1">

                    <p class="mb-0 montserrat-light">{{ $l->perusahaan->namaPerusahaan ?? 'Perusahaan tidak tersedia'}}</p>

                    <ul class="roboto-light text-black mb-1 mt-2" style="font-size: 15px">
                        {!! nl2br(e(Str::limit($l->deskripsiLowongan, 50, '...'))) !!}
                    </ul>

                    <div class="d-flex flex-row gap-2">
                        <div class="pills">{{ $l->perusahaan->lokasiPerusahaan ?? 'Lokasi  tidak ada' }}</div>
                        <div class="pills">{{ $l->jenisLowongan }}</div>
                        <div class="pills">Full-Time</div>
                    </div>

                    <div class="detail">
                    <a href="{{ route('admin.lowonganPekerjaan.lowonganPekerjaanDetail', ['id' => $l->id]) }}">
                        Selengkapnya..
                    </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="pagination">
    {{ $lowongan->appends(request()->query())->links() }}
    </div>

   
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background: linear-gradient(to bottom, #80C7D9, #446973);">
      <div class="modal-body d-flex flex-column align-items-center justify-content-center gap-4">
        
        <h5 class="modal-title" id="deleteModalLabel"></h5>
        <h2 class="text-center">Apakah anda yakin untuk menghapus <b id="judulLowongan"></b>?</h2>
        
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
      document.getElementById('judulLowongan').innerText = title;
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
  }

  document.getElementById('confirmDeleteButton').addEventListener('click', function() {
      fetch(`/admin/lowongan-pekerjaan/${selectedId}`, {
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
                  window.location.href = '{{ route('admin.lowonganPekerjaan.lowonganPekerjaan') }}';
              } else {
                  console.error(data.message);
              }
          })
          .catch(error => console.error('Error:', error));
  });
</script>
@endsection
