@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')

    <div class="main-content" id="main-content">
        <h1 class="poppins-bold text-black mt-3" style="font-size: 22px">Tambah Pengumuman</h1>

        <div class="box-form">
            <form action="{{ route('admin.pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label for="judul" class="poppins-bold text-black mb-2">Judul Pengumuman:</label>
                <input type="text" class="form-control mb-3" id="judul" name="judul_pengumuman"
                    placeholder="Masukkan judul Pengumuman" required>

                <label for="deskripsi" class="form-label poppins-bold text-black mb-2">Isi Pengumuman:</label>
                <textarea class="form-control mb-3" id="deskripsi" name="deskripsi_pengumuman"
                    placeholder="Masukkan deskripsi Pengumuman" required></textarea>

                <label for="upload" class="form-label poppins-bold text-black mt-2">Tambahkan Lampiran:</label>
                <div class="button-wrap">
                    <label class="buttonUploadFile" for="upload">
                        <i class='bx bx-upload me-1'></i>
                        Choose File
                    </label>
                    <input id="upload" type="file" name="lampiran[]" multiple
                        accept="image/*, .pdf, .doc, .docx, .xls, .xlsx" onchange="previewFiles()">
                </div>

                <div id="preview-container" class="mt-3" style="max-height: 200px; overflow-y: auto;"></div>

                <div class="d-flex justify-content-end align-items-end gap-2 mt-3">
                    <a href="{{ route('admin.pengumuman.index') }}" class="btn btn-batal">Batal</a>
                    <button type="submit" class="btn">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let selectedFiles = [];

        function previewFiles() {
            const previewContainer = document.getElementById("preview-container");
            const inputFiles = document.getElementById("upload").files;

            // Tambahkan file baru ke selectedFiles tanpa menggandakan
            for (let i = 0; i < inputFiles.length; i++) {
                const exists = selectedFiles.some(file => file.name === inputFiles[i].name && file.size === inputFiles[i]
                    .size);
                if (!exists) {
                    selectedFiles.push(inputFiles[i]);
                }
            }

            renderPreview();

            // 🔥 Auto-scroll ke preview-container
            setTimeout(() => {
                previewContainer.scrollTop = previewContainer.scrollHeight;
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: "smooth"
                });
            }, 100);
        }

        function renderPreview() {
            const previewContainer = document.getElementById("preview-container");
            previewContainer.innerHTML = "";

            selectedFiles.forEach((file, index) => {
                const fileWrapper = document.createElement("div");
                fileWrapper.style.display = "flex";
                fileWrapper.style.alignItems = "center";
                fileWrapper.style.marginBottom = "5px";
                fileWrapper.style.padding = "5px";
                fileWrapper.style.border = "1px solid #ddd";
                fileWrapper.style.borderRadius = "5px";
                fileWrapper.style.justifyContent = "space-between";

                const fileElement = document.createElement("p");
                fileElement.textContent = file.name;
                fileElement.style.margin = "0";
                fileWrapper.appendChild(fileElement);

                const deleteButton = document.createElement("button");
                deleteButton.innerHTML = "❌";
                deleteButton.setAttribute("type", "button");
                deleteButton.classList.add("remove-lampiran");
                deleteButton.style.background = "none";
                deleteButton.style.border = "none";
                deleteButton.style.color = "red";
                deleteButton.style.fontSize = "10px";
                deleteButton.style.padding = "2px";
                deleteButton.style.margin = "0";
                deleteButton.style.width = "15px";
                deleteButton.style.height = "15px";
                deleteButton.style.lineHeight = "1";
                deleteButton.style.display = "flex";
                deleteButton.style.alignItems = "center";
                deleteButton.style.justifyContent = "center";
                deleteButton.style.cursor = "pointer";

                deleteButton.onclick = () => removeFile(index);
                fileWrapper.appendChild(deleteButton);

                previewContainer.appendChild(fileWrapper);
            });

            updateFileInput();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderPreview();

            // 🔥 Auto-scroll ke preview-container setelah menghapus file
            setTimeout(() => {
                const previewContainer = document.getElementById("preview-container");
                previewContainer.scrollTop = previewContainer.scrollHeight;
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: "smooth"
                });
            }, 100);
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => dataTransfer.items.add(file));
            document.getElementById("upload").files = dataTransfer.files;
        }
    </script>
@endsection
