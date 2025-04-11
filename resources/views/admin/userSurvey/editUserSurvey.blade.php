@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')
    <div class="main-content" style="padding-bottom: 5%">
        <div class="row mb-4">
            <div class="col">
                <br />
                <h1>Edit User Survey</h1>
            </div>
            <div class="col-auto">
                <br />
                <a href="{{ route('admin.surveys.survey') }}" class="btn btn-secondary">Kembali</a>
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

        <form action="{{ route('admin.surveys.update', $surveySection->id) }}" method="POST" id="formUserSurvey">
            @csrf
            @method('PUT')
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="survey_sections_name" class="form-label">Nama Section</label>
                        <input type="text" class="form-control" id="survey_sections_name" name="survey_sections_name"
                            required value="{{ $surveySection->survey_sections_name }}">
                    </div>
                </div>
            </div>

            <div id="sections-container">
                @php $sectionIndex = 0; @endphp
                <div class="card mb-4 section-card" data-section-index="{{ $sectionIndex }}">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <span class="me-2">Section <span class="section-number">{{ $sectionIndex + 1 }}</span></span>
                            <input type="text" class="form-control form-control-sm"
                                name="sections[{{ $sectionIndex }}][section_name]" placeholder="Nama Section" required
                                value="{{ $surveySection->survey_sections_name }}" style="max-width: 300px;" readonly>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-link toggle-section">
                                <i class="fas fa-chevron-up"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body section-body">
                        <div class="questions-container">
                            @foreach ($surveySection->surveys as $questionIndex => $question)
                                <div class="card mb-3 question-card" data-question-index="{{ $questionIndex }}">
                                    <div class="card-body">
                                        <input type="hidden"
                                            name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][id]"
                                            value="{{ $question->id }}">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Judul Pertanyaan</label>
                                                <input type="text" class="form-control"
                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][question_title]"
                                                    placeholder="Tulis judul pertanyaan di sini"
                                                    value="{{ $question->question_title }}" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Isi Pertanyaan</label>
                                                <input type="text" class="form-control"
                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][question_body]"
                                                    placeholder="Tulis isi pertanyaan di sini"
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
                                                <label class="form-label">&nbsp;</label>
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
                                                {{-- Linear Scale --}}
                                                <div class="row mb-3 scale-options">
                                                    <div class="col-md-6">
                                                        <label class="form-label">Skala Mulai</label>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-4">
                                                                <select class="form-control scale-start"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][0][option_body]">
                                                                    <option value="0"
                                                                        @if ($question->surveyOptions && isset($question->surveyOptions[0]) && $question->surveyOptions[0]->option_body == '0') selected @endif>0
                                                                    </option>
                                                                    <option value="1"
                                                                        @if ($question->surveyOptions && isset($question->surveyOptions[0]) && $question->surveyOptions[0]->option_body == '1') selected @endif>1
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][0][label_angka]"
                                                                    placeholder="Label (opsional)"
                                                                    value="{{ $question->surveyOptions && isset($question->surveyOptions[0]) ? $question->surveyOptions[0]->label_angka : '' }}">
                                                                <input type="hidden"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][0][id]"
                                                                    value="{{ $question->surveyOptions && isset($question->surveyOptions[0]) ? $question->surveyOptions[0]->id : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label">Skala Akhir</label>
                                                        <div class="row align-items-center">
                                                            <div class="col-md-4">
                                                                <select class="form-control scale-end"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][1][option_body]">
                                                                    @for ($i = 2; $i <= 10; $i++)
                                                                        <option value="{{ $i }}"
                                                                            @if (
                                                                                $question->surveyOptions &&
                                                                                    isset($question->surveyOptions[1]) &&
                                                                                    $question->surveyOptions[1]->option_body == (string) $i) selected @endif>
                                                                            {{ $i }}
                                                                        </option>
                                                                    @endfor
                                                                </select>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][1][label_angka]"
                                                                    placeholder="Label (opsional)"
                                                                    value="{{ $question->surveyOptions && isset($question->surveyOptions[1]) ? $question->surveyOptions[1]->label_angka : '' }}">
                                                                <input type="hidden"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][1][id]"
                                                                    value="{{ $question->surveyOptions && isset($question->surveyOptions[1]) ? $question->surveyOptions[1]->id : '' }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif (in_array($question->type_question_id, [3, 4, 5]))
                                                {{-- Multiple Choice, Checkbox, Dropdown --}}
                                                @if ($question->surveyOptions)
                                                    @foreach ($question->surveyOptions as $optionIndex => $option)
                                                        <div class="row mb-2 option-row"
                                                            data-option-index="{{ $optionIndex }}">
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][{{ $optionIndex }}][option_body]"
                                                                    placeholder="Opsi Jawaban"
                                                                    value="{{ $option->option_body ?? '' }}" required>
                                                                <input type="hidden"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][{{ $optionIndex }}][id]"
                                                                    value="{{ $option->id ?? '' }}">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger delete-option">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    @for ($optionIndex = 0; $optionIndex < 2; $optionIndex++)
                                                        <div class="row mb-2 option-row"
                                                            data-option-index="{{ $optionIndex }}">
                                                            <div class="col-md-10">
                                                                <input type="text" class="form-control"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][{{ $optionIndex }}][option_body]"
                                                                    placeholder="Opsi Jawaban" value="" required>
                                                                <input type="hidden"
                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][{{ $optionIndex }}][id]"
                                                                    value="">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <button type="button"
                                                                    class="btn btn-sm btn-danger delete-option">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                @endif
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
            </div>

            <div class="mb-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <template id="question-template">
        <div class="card mb-3 question-card" data-question-index="__QUESTION_INDEX__">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Judul Pertanyaan</label>
                        <input type="text" class="form-control"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][question_title]"
                            placeholder="Tulis judul pertanyaan di sini" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Isi Pertanyaan</label>
                        <input type="text" class="form-control"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][question_body]"
                            placeholder="Tulis isi pertanyaan di sini" required>
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
                        <label class="form-label">&nbsp;</label>
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
                    name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][survey_options][__OPTION_INDEX__][option_body]"
                    placeholder="Opsi Jawaban" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-danger delete-option">
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
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][survey_options][0][option_body]">
                            <option value="0">0</option>
                            <option value="1">1</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][survey_options][0][label_angka]"
                            placeholder="Label (opsional)">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label">Skala Akhir</label>
                <div class="row align-items-center">
                    <div class="col-md-4">
                        <select class="form-control scale-end"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][survey_options][1][option_body]">
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
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][survey_options][1][label_angka]"
                            placeholder="Label (opsional)">
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let sectionIndex = 0;
            let questionIndex = {{ count($surveySection->surveys) }};

            $(document).on('click', '.toggle-section', function() {
                const $icon = $(this).find('i');
                const $sectionBody = $(this).closest('.section-card').find('.section-body');
                $sectionBody.slideToggle();
                $icon.toggleClass('fa-chevron-up fa-chevron-down');
            });

            $(document).on('click', '.add-question', function() {
                const sectionIndex = $(this).data('section-index');
                const $questionsContainer = $(this).closest('.section-card').find('.questions-container');
                const questionIndex = $questionsContainer.find('.question-card').length;

                let questionTemplate = $('#question-template').html()
                    .replace(/__SECTION_INDEX__/g, sectionIndex)
                    .replace(/__QUESTION_INDEX__/g, questionIndex);
                $questionsContainer.append(questionTemplate);
            });

            $(document).on('click', '.delete-question', function() {
                if (confirm('Yakin ingin menghapus pertanyaan ini?')) {
                    $(this).closest('.question-card').remove();
                }
            });

            $(document).on('change', '.question-type', function() {
                const typeId = parseInt($(this).val());
                const sectionIndex = $(this).data('section-index');
                const questionIndex = $(this).data('question-index');
                const $optionsContainer = $(this).closest('.question-card').find('.options-container');

                const existingOptions = $optionsContainer.find('.option-row').length > 0 ?
                    $optionsContainer.find('.option-row').map(function() {
                        return {
                            option_body: $(this).find('input[type="text"]').val(),
                            id: $(this).find('input[type="hidden"]').val()
                        };
                    }).get() : [];

                $optionsContainer.empty();

                if ([3, 4, 5, 6].includes(typeId)) {
                    $optionsContainer.show();
                    if (typeId === 6) {
                        const scaleTemplate = $('#option-scale-template').html()
                            .replace(/__SECTION_INDEX__/g, sectionIndex)
                            .replace(/__QUESTION_INDEX__/g, questionIndex);
                        $optionsContainer.append(scaleTemplate);
                    } else {
                        if (existingOptions.length > 0) {
                            existingOptions.forEach((option, index) => {
                                const optionTemplate = $('#option-template').html()
                                    .replace(/__SECTION_INDEX__/g, sectionIndex)
                                    .replace(/__QUESTION_INDEX__/g, questionIndex)
                                    .replace(/__OPTION_INDEX__/g, index);
                                $optionsContainer.append(optionTemplate);
                                $optionsContainer.find(
                                    `.option-row:eq(${index}) input[type="text"]`).val(option
                                    .option_body);
                                $optionsContainer.find(
                                    `.option-row:eq(${index}) input[type="hidden"]`).val(option
                                    .id);
                            });
                        } else {
                            for (let i = 0; i < 2; i++) {
                                const optionTemplate = $('#option-template').html()
                                    .replace(/__SECTION_INDEX__/g, sectionIndex)
                                    .replace(/__QUESTION_INDEX__/g, questionIndex)
                                    .replace(/__OPTION_INDEX__/g, i);
                                $optionsContainer.append(optionTemplate);
                            }
                        }
                        $optionsContainer.append(`
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-secondary add-option"
                                    data-section-index="${sectionIndex}"
                                    data-question-index="${questionIndex}">
                                    <i class="fas fa-plus"></i> Tambah Opsi
                                </button>
                            </div>
                        `);
                    }
                } else {
                    $optionsContainer.hide();
                }
            });

            $(document).on('click', '.add-option', function() {
                const sectionIndex = $(this).data('section-index');
                const questionIndex = $(this).data('question-index');
                const $optionsContainer = $(this).closest('.options-container');
                const optionIndex = $optionsContainer.find('.option-row').length;

                const optionTemplate = $('#option-template').html()
                    .replace(/__SECTION_INDEX__/g, sectionIndex)
                    .replace(/__QUESTION_INDEX__/g, questionIndex)
                    .replace(/__OPTION_INDEX__/g, optionIndex);
                $optionsContainer.find('.mb-3').before(optionTemplate);
            });

            $(document).on('click', '.delete-option', function() {
                if ($(this).closest('.options-container').find('.option-row').length > 1) {
                    $(this).closest('.option-row').remove();
                } else {
                    alert('Pertanyaan harus memiliki minimal satu opsi!');
                }
            });

            $('.question-type').each(function() {
                $(this).trigger('change');
            });

            $('#formUserSurvey').on('submit', function(e) {
                let isValid = true;

                if (!$('#survey_sections_name').val()) {
                    alert('Nama section harus diisi!');
                    isValid = false;
                }

                $('.section-card').each(function() {
                    const questionCount = $(this).find('.question-card').length;
                    if (questionCount === 0) {
                        alert('Section harus memiliki minimal 1 pertanyaan!');
                        isValid = false;
                        return false;
                    }
                });

                $('.question-type').each(function() {
                    if (!$(this).val()) {
                        alert('Semua pertanyaan harus memiliki tipe yang dipilih!');
                        isValid = false;
                        return false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                }
            });
        });
    </script>

    <style>
        html,
        body {
            height: 100%;
            overflow: hidden;
        }

        .container {
            height: 100vh;
            overflow: auto;
        }
    </style>
@endsection
