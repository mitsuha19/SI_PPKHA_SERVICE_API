@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')
    <div class="main-content" style="padding-bottom: 5%">
        <div class="row mb-4">
            <div class="col">
                <br />
                <h1>Edit Tracer Study</h1>
            </div>
            <div class="col-auto">
                <br />
                <a href="/admin/tracer-study" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.forms.update', $form->id) }}" method="POST" id="formTracer">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="judul_form" class="form-label">Judul Form</label>
                        <input type="text" class="form-control" id="judul_form" name="judul_form" required
                            value="{{ $form->judul_form }}">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_form" class="form-label">Deskripsi Form</label>
                        <textarea class="form-control" id="deskripsi_form" name="deskripsi_form" rows="3">{{ $form->deskripsi_form }}</textarea>
                    </div>
                </div>
            </div>

            <input type="hidden" name="deleted_options" id="deleted_options" value="">

            <div id="sections-container">
                @foreach ($form->sections as $sectionIndex => $section)
                    <input type="hidden" name="sections[{{ $sectionIndex }}][id]" value="{{ $section->id }}">
                    <div class="card mb-4 section-card" data-section-index="{{ $sectionIndex }}">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-sm"
                                    name="sections[{{ $sectionIndex }}][section_name]" placeholder="Nama Section" required
                                    value="{{ $section->section_name }}" style="max-width: 300px;">
                            </div>
                            <div>
                                <button type="button" class="btn btn-sm btn-link toggle-section">
                                    <i class="fas fa-chevron-up"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger delete-section">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body section-body">
                            <div class="questions-container">
                                @foreach ($section->questions as $questionIndex => $question)
                                    <input type="hidden"
                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][id]"
                                        value="{{ $question->id }}">
                                    <div class="card mb-3 question-card" data-question-index="{{ $questionIndex }}">
                                        <div class="card-body">
                                            <div class="row mb-3">
                                                <div class="col">
                                                    <label class="form-label">Pertanyaan</label>
                                                    <input type="text" class="form-control"
                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][question_body]"
                                                        placeholder="Tulis pertanyaan di sini"
                                                        value="{{ $question->question_body }}" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-8">
                                                    <label class="form-label">Tipe Pertanyaan</label>
                                                    <select class="form-select question-type"
                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][type_question_id]"
                                                        data-section-index="{{ $sectionIndex }}"
                                                        data-question-index="{{ $questionIndex }}" required>
                                                        <option value="">Pilih Tipe Pertanyaan</option>
                                                        @foreach ($typeQuestions as $type)
                                                            <option value="{{ $type->id }}"
                                                                @if ($question->type_question_id == $type->id) selected @endif>
                                                                {{ $type->type_question_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label"> </label>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][is_required]"
                                                            id="is_required_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                            value="1" @if ($question->is_required) checked @endif>
                                                        <label class="form-check-label"
                                                            for="is_required_{{ $sectionIndex }}_{{ $questionIndex }}">
                                                            Wajib Diisi
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="options-container"
                                                @if (!in_array($question->type_question_id, [3, 4, 5, 6])) style="display: none;" @endif>
                                                @if ($question->type_question_id == 6)
                                                    <div class="row mb-3 scale-options">
                                                        <div class="col-md-6">
                                                            <label class="form-label">Skala Mulai</label>
                                                            <div class="row align-items-center">
                                                                <div class="col-md-4">
                                                                    <select class="form-control scale-start"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][0][option_body]">
                                                                        <option value="0"
                                                                            @if ($question->options[0]->option_body == '0') selected @endif>
                                                                            0</option>
                                                                        <option value="1"
                                                                            @if ($question->options[0]->option_body == '1') selected @endif>
                                                                            1</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][0][label_angka]"
                                                                        placeholder="Label (opsional)"
                                                                        value="{{ $question->options[0]->label_angka ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][0][id]"
                                                                        value="{{ $question->options[0]->id }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">Skala Akhir</label>
                                                            <div class="row align-items-center">
                                                                <div class="col-md-4">
                                                                    <select class="form-control scale-end"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][1][option_body]">
                                                                        @for ($i = 2; $i <= 10; $i++)
                                                                            <option value="{{ $i }}"
                                                                                @if ($question->options[1]->option_body == (string) $i) selected @endif>
                                                                                {{ $i }}</option>
                                                                        @endfor
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <input type="text" class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][1][label_angka]"
                                                                        placeholder="Label (opsional)"
                                                                        value="{{ $question->options[1]->label_angka ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][1][id]"
                                                                        value="{{ $question->options[1]->id }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif (in_array($question->type_question_id, [3, 4, 5]))
                                                    @foreach ($question->options as $optionIndex => $option)
                                                        <div class="row mb-2 option-row"
                                                            data-option-index="{{ $optionIndex }}">
                                                            <input type="hidden"
                                                                name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][{{ $optionIndex }}][id]"
                                                                value="{{ $option->id }}">
                                                            @if ($question->type_question_id == 3)
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][{{ $optionIndex }}][option_body]"
                                                                        placeholder="Opsi Jawaban"
                                                                        value="{{ $option->option_body }}" required>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <select class="form-select next-section-select"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][{{ $optionIndex }}][next_section_id]">
                                                                        <option value="">Pilih Section Selanjutnya
                                                                        </option>
                                                                        <option value="999"
                                                                            @if ($submitSection && $option->next_section_id == $submitSection->id) selected @endif>
                                                                            Kirim Formulir</option>
                                                                        @foreach ($form->sections as $nextIndex => $nextSection)
                                                                            @if ($nextIndex != $sectionIndex && $nextSection->section_name != 'Kirim Formulir')
                                                                                <option value="{{ $nextIndex }}"
                                                                                    @if ($option->next_section_id == $nextSection->id) selected @endif>
                                                                                    {{ $nextSection->section_name }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            @else
                                                                <div class="col-md-10">
                                                                    <input type="text" class="form-control"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][options][{{ $optionIndex }}][option_body]"
                                                                        placeholder="Opsi Jawaban"
                                                                        value="{{ $option->option_body }}" required>
                                                                </div>
                                                            @endif
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger delete-option"
                                                                    data-option-id="{{ $option->id }}">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="mb-3">
                                                        <button type="button" class="btn btn-sm btn-secondary add-option"
                                                            data-section-index="{{ $sectionIndex }}"
                                                            data-question-index="{{ $questionIndex }}">
                                                            <i class="fas fa-plus"></i> Tambah Opsi
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="mt-2 text-end">
                                                <button type="button" class="btn btn-sm btn-danger delete-question">
                                                    <i class="fas fa-trash"></i> Hapus Pertanyaan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-3">
                                <button type="button" class="btn btn-info w-100 add-question"
                                    data-section-index="{{ $sectionIndex }}">
                                    <i class="fas fa-plus"></i> Tambah Pertanyaan
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <button type="button" class="btn btn-success w-100" id="add-section">
                    <i class="fas fa-plus"></i> Tambah Section Baru
                </button>
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <template id="section-template">
        <div class="card mb-4 section-card" data-section-index="__SECTION_INDEX__">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <input type="text" class="form-control form-control-sm"
                        name="sections[__SECTION_INDEX__][section_name]" placeholder="Nama Section" required
                        style="max-width: 300px;">
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-link toggle-section">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger delete-section">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body section-body">
                <div class="questions-container"></div>
                <div class="mt-3">
                    <button type="button" class="btn btn-info w-100 add-question"
                        data-section-index="__SECTION_INDEX__">
                        <i class="fas fa-plus"></i> Tambah Pertanyaan
                    </button>
                </div>
            </div>
        </div>
    </template>

    <template id="question-template">
        <div class="card mb-3 question-card" data-question-index="__QUESTION_INDEX__">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <label class="form-label">Pertanyaan</label>
                        <input type="text" class="form-control"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][question_body]"
                            placeholder="Tulis pertanyaan di sini" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-8">
                        <label class="form-label">Tipe Pertanyaan</label>
                        <select class="form-select question-type"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][type_question_id]"
                            data-section-index="__SECTION_INDEX__" data-question-index="__QUESTION_INDEX__" required>
                            <option value="">Pilih Tipe Pertanyaan</option>
                            @foreach ($typeQuestions as $type)
                                <option value="{{ $type->id }}">{{ $type->type_question_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label"> </label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox"
                                name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][is_required]"
                                id="is_required___SECTION_INDEX_____QUESTION_INDEX__" value="1">
                            <label class="form-check-label" for="is_required___SECTION_INDEX_____QUESTION_INDEX__">
                                Wajib Diisi
                            </label>
                        </div>
                    </div>
                </div>
                <div class="options-container" style="display: none;">
                    <div class="mb-3">
                        <button type="button" class="btn btn-sm btn-secondary add-option"
                            data-section-index="__SECTION_INDEX__" data-question-index="__QUESTION_INDEX__">
                            <i class="fas fa-plus"></i> Tambah Opsi
                        </button>
                    </div>
                </div>
                <div class="mt-2 text-end">
                    <button type="button" class="btn btn-sm btn-danger delete-question">
                        <i class="fas fa-trash"></i> Hapus Pertanyaan
                    </button>
                </div>
            </div>
        </div>
    </template>

    <template id="option-template">
        <div class="row mb-2 option-row" data-option-index="__OPTION_INDEX__">
            <div class="col-md-10">
                <input type="text" class="form-control"
                    name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][__OPTION_INDEX__][option_body]"
                    placeholder="Opsi Jawaban" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger delete-option" data-option-id="">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </template>

    <!-- Template untuk opsi multiple choice -->
    <template id="option-mc-template">
        <div class="row mb-2 option-row" data-option-index="__OPTION_INDEX__">
            <div class="col-md-6">
                <input type="text" class="form-control"
                    name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][__OPTION_INDEX__][option_body]"
                    placeholder="Opsi Jawaban" required>
            </div>
            <div class="col-md-4">
                <select class="form-select next-section-select"
                    name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][__OPTION_INDEX__][next_section_id]">
                    <option value="">Pilih Section Selanjutnya</option>
                    <option value="999">Kirim Formulir</option>
                    <!-- Opsi lain akan ditambahkan oleh JavaScript -->
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger delete-option" data-option-id="">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </template>

    <template id="option-scale-template">
        <div class="row mb-3 scale-options">
            <div class="col-md-6">
                <label class="form-label">Skala Mulai</label>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <select class="form-control scale-start"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][0][option_body]">
                            <option value="0">0</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][0][label_angka]"
                            placeholder="Label (opsional)">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Skala Akhir</label>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <select class="form-control scale-end"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][1][option_body]">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5" selected>5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][options][1][label_angka]"
                            placeholder="Label (opsional)">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let sectionIndex = {{ count($form->sections) }};
            let deletedOptions = [];

            // Toggle Section
            $(document).on('click', '.toggle-section', function() {
                const $icon = $(this).find('i');
                const $sectionBody = $(this).closest('.section-card').find('.section-body');
                $sectionBody.slideToggle();
                $icon.toggleClass('fa-chevron-up fa-chevron-down');
            });

            // Add Section
            $('#add-section').click(function() {
                const sectionTemplate = $('#section-template').html()
                    .replace(/__SECTION_INDEX__/g, sectionIndex);
                $('#sections-container').append(sectionTemplate);
                updateNextSectionDropdowns();
                sectionIndex++;
            });

            // Delete Section
            $(document).on('click', '.delete-section', function() {
                if (confirm('Yakin ingin menghapus section ini?')) {
                    $(this).closest('.section-card').remove();
                    updateNextSectionDropdowns();
                }
            });

            // Add Question
            $(document).on('click', '.add-question', function() {
                const sectionIndex = $(this).data('section-index');
                const $questionsContainer = $(this).closest('.section-body').find('.questions-container');
                const questionIndex = $questionsContainer.find('.question-card').length;
                const questionTemplate = $('#question-template').html()
                    .replace(/__SECTION_INDEX__/g, sectionIndex)
                    .replace(/__QUESTION_INDEX__/g, questionIndex);
                $questionsContainer.append(questionTemplate);
            });

            // Delete Question
            $(document).on('click', '.delete-question', function() {
                if (confirm('Yakin ingin menghapus pertanyaan ini?')) {
                    $(this).closest('.question-card').remove();
                }
            });

            // Question Type Change
            $(document).on('change', '.question-type', function() {
                const typeId = parseInt($(this).val());
                const sectionIndex = $(this).data('section-index');
                const questionIndex = $(this).data('question-index');
                const $optionsContainer = $(this).closest('.question-card').find('.options-container');

                $optionsContainer.empty();

                if ([3, 4, 5, 6].includes(typeId)) {
                    $optionsContainer.show();
                    if (typeId === 6) {
                        const scaleTemplate = $('#option-scale-template').html()
                            .replace(/__SECTION_INDEX__/g, sectionIndex)
                            .replace(/__QUESTION_INDEX__/g, questionIndex);
                        $optionsContainer.append(scaleTemplate);
                    } else {
                        const buttonHtml = `
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-secondary add-option"
                                    data-section-index="${sectionIndex}" data-question-index="${questionIndex}">
                                    <i class="fas fa-plus"></i> Tambah Opsi
                                </button>
                            </div>`;
                        $optionsContainer.append(buttonHtml);
                        if (typeId === 3 || typeId === 4 || typeId === 5) {
                            addOption(sectionIndex, questionIndex, 0, typeId === 3);
                        }
                    }
                } else {
                    $optionsContainer.hide();
                }
            });

            // Add Option
            $(document).on('click', '.add-option', function() {
                const sectionIndex = $(this).data('section-index');
                const questionIndex = $(this).data('question-index');
                const $optionsContainer = $(this).closest('.options-container');
                const typeId = parseInt($(this).closest('.question-card').find('.question-type').val());
                const optionIndex = $optionsContainer.find('.option-row').length;

                if (typeId === 6) return; // Skala tidak bisa tambah opsi
                addOption(sectionIndex, questionIndex, optionIndex, typeId === 3);
            });

            function addOption(sectionIndex, questionIndex, optionIndex, isMultipleChoice) {
                const $optionsContainer = $(
                    `.section-card[data-section-index="${sectionIndex}"] .question-card[data-question-index="${questionIndex}"] .options-container`
                );
                const template = isMultipleChoice ? '#option-mc-template' : '#option-template';
                const optionHtml = $(template).html()
                    .replace(/__SECTION_INDEX__/g, sectionIndex)
                    .replace(/__QUESTION_INDEX__/g, questionIndex)
                    .replace(/__OPTION_INDEX__/g, optionIndex);
                $optionsContainer.find('.mb-3').before(optionHtml);

                if (isMultipleChoice) {
                    updateSingleNextSectionDropdown($optionsContainer.find('.next-section-select').last(),
                        sectionIndex);
                }
            }

            // Delete Option
            $(document).on('click', '.delete-option', function() {
                const $optionRow = $(this).closest('.option-row');
                const optionId = $(this).data('option-id');

                if ($optionRow.siblings('.option-row').length >= 1) {
                    if (optionId) { // Jika opsi sudah ada di database
                        deletedOptions.push(optionId);
                        $('#deleted_options').val(deletedOptions.join(','));
                    }
                    $optionRow.remove();
                } else {
                    alert('Minimal harus ada 1 opsi!');
                }
            });

            // Update All Next Section Dropdowns
            function updateNextSectionDropdowns() {
                $('.next-section-select').each(function() {
                    const $select = $(this);
                    const sectionIndex = $select.closest('.section-card').data('section-index');
                    updateSingleNextSectionDropdown($select, sectionIndex);
                });
            }

            function updateSingleNextSectionDropdown($select, currentSectionIndex) {
                const currentValue = $select.val();
                $select.empty();
                $select.append('<option value="">Pilih Section Selanjutnya</option>');
                $select.append('<option value="999">Kirim Formulir</option>');

                $('.section-card').each(function() {
                    const sectionIdx = $(this).data('section-index');
                    const sectionName = $(this).find('input[name$="[section_name]"]').val();
                    if (sectionIdx !== currentSectionIndex && sectionName && sectionName !==
                        'Kirim Formulir') {
                        $select.append(`<option value="${sectionIdx}">${sectionName}</option>`);
                    }
                });

                if (currentValue && $select.find(`option[value="${currentValue}"]`).length) {
                    $select.val(currentValue);
                }
            }
            // Form Submission Validation
            $('#formTracer').on('submit', function(e) {
                let isValid = true;
                if ($('.section-card').length === 0) {
                    alert('Form harus memiliki minimal 1 section!');
                    isValid = false;
                }

                $('.section-card').each(function() {
                    const sectionName = $(this).find('input[name$="[section_name]"]').val();
                    const questionCount = $(this).find('.question-card').length;
                    if (!sectionName) {
                        alert('Setiap section harus memiliki nama!');
                        isValid = false;
                        return false;
                    }
                    if (questionCount === 0) {
                        alert(`Section "${sectionName}" harus memiliki minimal 1 pertanyaan!`);
                        isValid = false;
                        return false;
                    }
                });

                $('.question-type').each(function() {
                    if (!$(this).val()) {
                        alert('Semua pertanyaan harus memiliki tipe!');
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });

            // Initialize Dropdowns
            updateNextSectionDropdowns();

            // Update Dropdowns on Section Name Change
            $(document).on('input', 'input[name$="[section_name]"]', function() {
                updateNextSectionDropdowns();
            });
        });
    </script>

    <style>
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 100px);
            /* Adjust based on navbar height */
        }
    </style>
@endsection
