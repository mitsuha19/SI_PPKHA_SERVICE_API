document.addEventListener("DOMContentLoaded", function () {
    const formSections = document.getElementById("form-sections");

    // Fungsi untuk membuat opsi jawaban dengan dropdown tujuan section

    function createOption() {
        return `
            <div class="option flex items-center gap-3 w-full">
                <input type="radio" disabled class="shrink-0">
                <div contenteditable="true" class="border-b flex-grow focus:outline-none text-gray-700 p-1" data-placeholder="Opsi Baru"></div>
                <select class="form-select text-sm bg-white p-1 rounded border select-section">
                    ${getSectionOptions()}
                </select>
                <button class="text-red-500 text-lg remove-option">&times;</button>
            </div>
        `;
    }

    // Fungsi untuk mengambil daftar section sebagai dropdown tujuan
    function getSectionOptions() {
        const sections = document.querySelectorAll(".section-container");
        let options = '<option value="">Pilih Section Tujuan</option>'; // Default kosong
        sections.forEach((section, index) => {
            options += `<option value="section-${index + 1}">Bagian ${
                index + 1
            }</option>`;
        });
        return options;
    }

    function updateAllSectionDropdowns() {
        document.querySelectorAll(".select-section").forEach((dropdown) => {
            dropdown.innerHTML = getSectionOptions();
        });
    }

    // Event Listener untuk Hapus Section
    formSections.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-section")) {
            e.target.closest(".section-container").remove();
            updateAllSectionDropdowns();
        }
    });

    function createQuestion() {
        const question = document.createElement("div");
        question.classList.add(
            "question-container",
            "p-6",
            "rounded-lg",
            "shadow-sm",
            "relative",
            "w-full",
            "max-w-2xl",
            "bg-gray-100"
        );

        question.innerHTML = `
            <!-- Tombol Hapus Pertanyaan -->
            <button class="absolute top-2 right-2 text-red-500 text-lg remove-question">&times;</button>
    
            <!-- Baris Pertanyaan & Dropdown -->
            <div class="d-flex justify-between items-center mb-3 gap-5">
                <input type="text" class="form-control font-semibold border-b flex-grow focus:outline-none"
                    placeholder="Pertanyaan">
    
                <select class="form-select text-sm bg-white p-2 rounded border question-type w-2/3 select-arrow-right equal-radius">
                    <option value="short-answer">Jawaban Singkat</option>
                    <option value="paragraph">Paragraf</option>
                    <option value="multiple-choice">Pilihan Ganda</option>
                    <option value="checkbox">Kotak Centang</option>
                    <option value="dropdown">Drop-down</option>
                    <option value="linear-scale">Skala-Linier</option>
                    <option value="location">Lokasi</option>
                </select>
            </div>
    
            <!-- Container Jawaban -->
            <div class="answers space-y-2 mt-3"></div>

          
        `;

        // Menambahkan event listener ke dropdown
        const questionType = question.querySelector(".question-type");
        const answersContainer = question.querySelector(".answers");

        function updateAnswers() {
            const selectedType = questionType.value;
            if (
                selectedType === "multiple-choice" ||
                selectedType === "checkbox" ||
                selectedType === "dropdown"
            ) {
                answersContainer.innerHTML = `
                    ${createOption()} 
                    <button class="text-blue-500 text-sm mt-2 add-option">Tambah Opsi</button>
                `;
            } else if (selectedType === "short-answer") {
                answersContainer.innerHTML = `
                    <input type="text" class="borderless-input"
                        placeholder="Teks Jawaban Singkat" disabled>
                `;
            } else if (selectedType === "paragraph") {
                answersContainer.innerHTML = `
                    <textarea class="borderless-textarea"
                        placeholder="Teks Jawaban Panjang" disabled></textarea>
                `;
            }
        }

        // Set jawaban default pertama kali
        updateAnswers();

        // Event listener saat dropdown berubah
        questionType.addEventListener("change", updateAnswers);

        return question;
    }

    function createSection() {
        const section = document.createElement("div");
        section.classList.add(
            "section-container",
            "bg-white",
            "p-6",
            "rounded-lg",
            "shadow-md",
            "relative",
            "w-full",
            "max-w-2xl"
        );
        section.innerHTML = `
            <!-- Tombol Hapus Section -->
            <button class="absolute top-2 right-2 text-red-500 text-lg remove-section">&times;</button>
    
            <div class="d-flex flex-row pe-4 section-container-main">
                <div class="mb-4 d-flex flex-column me-3" style="width: 95%">
                    <label for="judul" class="text-black">Tambahkan Judul Form</label>
                    <input type="text"
                        class="mb-3 bg-white text-lg font-semibold border-b w-100 focus:outline-none"
                        id="judul" placeholder="Masukkan Judul Formulir">
                    <label for="deskripsi" class="text-black">Tambahkan Deskripsi</label>
                    <input type="text"
                        class="mb-3 bg-white text-lg font-semibold border-b w-100 focus:outline-none"
                        id="deskripsi" placeholder="Masukkan Deskripsi Formulir">
                </div>
    
                <!-- Floating Button -->
                <div class="floating-menu gap-3 py-3">
                    <button class="floating-btn add-question" title="Tambah Pertanyaan">
                        <i class="bx bx-plus bx-sm"></i>
                    </button>
                    <button class="floating-btn add-section" title="Tambah Section">
                        <i class="bx bx-list-plus bx-sm"></i>
                    </button>
                </div>
            </div>
    
            <!-- Container Pertanyaan yang Kosong -->
            <div class="questions space-y-4"></div>
        `;

        formSections.appendChild(section);
    }

    // Event Listener untuk Tambah Section (Hanya SATU)
    formSections.addEventListener("click", function (e) {
        if (e.target.closest(".add-section")) {
            createSection();
            updateAllSectionDropdowns();
        }
    });

    // Event Listener untuk Tambah Pertanyaan
    formSections.addEventListener("click", function (e) {
        if (e.target.closest(".add-question")) {
            const section = e.target.closest(".section-container");
            const questionsContainer = section.querySelector(".questions");
            questionsContainer.appendChild(createQuestion());
        }
    });

    // Event Listener untuk Hapus Pertanyaan
    formSections.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-question")) {
            e.target.closest(".question-container").remove();
        }
    });

    // Event Listener untuk Hapus Section
    formSections.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-section")) {
            e.target.closest(".section-container").remove();
        }
    });

    // Event Listener untuk Tambah Opsi Jawaban
    formSections.addEventListener("click", function (e) {
        if (e.target.classList.contains("add-option")) {
            const answersContainer = e.target.parentElement;
            const newOption = document.createElement("div");
            newOption.classList.add(
                "flex",
                "items-center",
                "space-x-2",
                "option"
            );
            newOption.innerHTML = createOption();
            answersContainer.insertBefore(newOption, e.target);
        }
    });

    // Event Listener untuk Hapus Opsi Jawaban
    formSections.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-option")) {
            const optionToRemove = e.target.closest(".option");
            if (optionToRemove) {
                optionToRemove.remove();
            }
        }
    });
});

document.getElementById("save-form").addEventListener("click", function () {
    let formTitle = document.getElementById("judul")?.value || "";
    let formDescription = document.getElementById("deskripsi")?.value || "";
    let questions = [];

    document
        .querySelectorAll(".question-container")
        .forEach((questionEl, qIndex) => {
            let questionText =
                questionEl.querySelector("input.form-control")?.value || "";
            let questionType =
                questionEl.querySelector("select.question-type")?.value || "";
            let options = [];

            let optionElements = questionEl.querySelectorAll(".option");

            console.log(`Total opsi ditemukan: ${optionElements.length}`); // 🔍 Debugging

            // Hindari duplikasi dengan membuat Set
            let seenOptions = new Set();

            optionElements.forEach((optionEl, oIndex) => {
                let optionText =
                    optionEl
                        .querySelector("div[contenteditable='true']")
                        ?.innerText.trim() || "";
                let nextSection =
                    optionEl.querySelector("select.select-section")?.value ||
                    null;

                if (
                    typeof nextSection === "string" &&
                    nextSection.startsWith("section-")
                ) {
                    nextSection = parseInt(
                        nextSection.replace("section-", ""),
                        10
                    );
                } else {
                    nextSection = null;
                }

                // Cegah duplikasi dengan memeriksa apakah sudah ada
                let optionKey = `${optionText}-${nextSection}`;
                if (!seenOptions.has(optionKey)) {
                    seenOptions.add(optionKey);

                    options.push({
                        text: optionText,
                        next_section: nextSection,
                    });
                }
            });

            questions.push({
                text: questionText,
                type: questionType,
                options: options,
            });
        });

    let formData = {
        title: formTitle,
        description: formDescription,
        questions: questions,
    };

    console.log("Data yang akan dikirim:", formData); // Debugging sebelum dikirim ke backend

    fetch("/forms", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
            Accept: "application/json",
        },
        body: JSON.stringify(formData),
    })
        .then((response) => response.json())
        .then((data) => alert("Form berhasil disimpan!"))
        .catch((error) => console.error("Error:", error));
});

document.addEventListener("click", function () {
    let mainContent = document.querySelector(".main-content");
    mainContent.style.height = "auto"; // Biarkan tinggi menyesuaikan isi
    mainContent.scrollTop = mainContent.scrollHeight; // Scroll otomatis ke bawah saat ada elemen baru
});
