@extends('layouts.appAdmin')

@section('content')
    @include('components.navbarAdmin')
    <div class="main-content d-flex flex-column align-items-center" style="padding-bottom: 5%">
        <h1>Survey Sections</h1>

        <!-- Wrapper for scrollable content -->
        <div class="survey-wrapper d-flex flex-column align-items-center w-100 gap-2"
            style="overflow-y: auto; max-height: calc(100vh - 100px);">
            <div class="d-flex justify-content-end" style="width: 80%">
                <button type="button" class="btn btn-tambah mt-2"
                    onclick="window.location.href='{{ route('admin.surveys.create') }}'">
                    <i class='bx bx-plus-circle'></i>
                    <span class="d-none d-xl-inline">Tambah</span>
                </button>
            </div>

            <form action="{{ route('admin.surveys.update.all') }}" method="POST" id="survey-form">
                @csrf
                <div id="sections-container" class="d-flex flex-column align-items-center w-100 gap-2">
                    @foreach ($sections as $sectionIndex => $section)
                        <div class="outer-section-card" data-id="{{ $section->id }}">
                            <!-- View Mode -->
                            <div class="card-information d-flex align-items-center px-3 view-mode">
                                <div class="ps-3 w-100">
                                    <div
                                        class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-center">
                                        <h2 class="fst-italic roboto-title mb-0 align-self-center section-name"
                                            style="cursor: pointer;">
                                            {{ $section->survey_sections_name }}
                                        </h2>
                                        <div class="align-self-start ms-auto d-flex gap-2">
                                            <a href="javascript:void(0);" class="btn btn-danger btn-delete"
                                                data-id="{{ $section->id }}">
                                                <i class='bx bx-trash'></i>
                                                <span class="d-none d-xl-inline ms-1">Hapus</span>
                                            </a>
                                        </div>
                                    </div>

                                    <hr class="my-2" style="border: 2px solid black; opacity: 1">

                                    <p class="roboto-light mb-1 mt-2 description-text" style="font-size: 15px;">
                                        Questions: {{ $section->surveys->count() }}
                                    </p>

                                    <ul class="question-list"
                                        style="word-wrap: break-word; max-height: 100px; overflow-y: auto;">
                                        @foreach ($section->surveys as $survey)
                                            <li>{{ $survey->question_title }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <!-- Edit Mode -->
                            <div class="edit-mode" style="display: none;">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="survey_sections_name_{{ $section->id }}" class="form-label">Nama
                                                Section</label>
                                            <input type="text" class="form-control"
                                                id="survey_sections_name_{{ $section->id }}"
                                                name="sections[{{ $sectionIndex }}][survey_sections_name]" required
                                                value="{{ $section->survey_sections_name }}">
                                            <input type="hidden" name="sections[{{ $sectionIndex }}][id]"
                                                value="{{ $section->id }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="section-body-container" data-section-index="{{ $sectionIndex }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">Section {{ $sectionIndex + 1 }}</span>
                                        </div>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-link toggle-section">
                                                <i class="fas fa-chevron-up"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body section-body">
                                        <div class="questions-container">
                                            @foreach ($section->surveys as $questionIndex => $question)
                                                <div class="card mb-3 question-card"
                                                    data-question-index="{{ $questionIndex }}">
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
                                                                            {{ $type->type_question_name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label"> </label>
                                                                <div class="form-check mt-2">
                                                                    <input type="hidden"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][is_required]"
                                                                        value="0">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][is_required]"
                                                                        id="is_required_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                                        value="1"
                                                                        @if ($question->is_required) checked @endif>
                                                                    <label class="form-check-label"
                                                                        for="is_required_{{ $sectionIndex }}_{{ $questionIndex }}">Wajib
                                                                        Diisi</label>
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
                                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][0][option_body]">
                                                                                    <option value="0"
                                                                                        @if ($question->surveyOptions && isset($question->surveyOptions[0]) && $question->surveyOptions[0]->option_body == '0') selected @endif>
                                                                                        0</option>
                                                                                    <option value="1"
                                                                                        @if ($question->surveyOptions && isset($question->surveyOptions[0]) && $question->surveyOptions[0]->option_body == '1') selected @endif>
                                                                                        1</option>
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
                                                                                        <option
                                                                                            value="{{ $i }}"
                                                                                            @if (
                                                                                                $question->surveyOptions &&
                                                                                                    isset($question->surveyOptions[1]) &&
                                                                                                    $question->surveyOptions[1]->option_body == (string) $i) selected @endif>
                                                                                            {{ $i }}</option>
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
                                                                @if ($question->surveyOptions)
                                                                    @foreach ($question->surveyOptions as $optionIndex => $option)
                                                                        <div class="row mb-2 option-row"
                                                                            data-option-index="{{ $optionIndex }}">
                                                                            <div class="col-md-10">
                                                                                <input type="text" class="form-control"
                                                                                    name="sections[{{ $sectionIndex }}][questions][{{ $questionIndex }}][survey_options][{{ $optionIndex }}][option_body]"
                                                                                    placeholder="Opsi Jawaban"
                                                                                    value="{{ $option->option_body ?? '' }}"
                                                                                    required>
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
                                                                                    placeholder="Opsi Jawaban"
                                                                                    value="" required>
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
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-secondary add-option"
                                                                        data-section-index="{{ $sectionIndex }}"
                                                                        data-question-index="{{ $questionIndex }}">
                                                                        <i class="fas fa-plus"></i> Tambah Opsi
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <div class="mt-2 text-end">
                                                            <button type="button"
                                                                class="btn btn-sm btn-danger delete-question">
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
                        </div>
                    @endforeach
                </div>

                <!-- Conditionally show the buttons only if there are sections -->
                @if ($sections->isNotEmpty())
                    <div class="mb-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" onclick="location.reload();">Batal</button>
                    </div>
                @endif
            </form>

            @if (isset($output) && $output === '0')
                <div id="no-sections-message" class="text-center">
                    <h1 class="fw-bold fst-italic mt-4">Ops</h1>
                    <h2 class="fw-bold mt-3 text-dark">Tidak ada Section atau Pertanyaan!</h2>
                </div>
            @else
                <div id="no-sections-message" class="text-center" style="display: none;">
                    <h1 class="fw-bold fst-italic mt-4">Ops</h1>
                    <h2 class="fw-bold mt-3 text-dark">Tidak ada Section atau Pertanyaan!</h2>
                </div>
            @endif
        </div> <!-- End of survey-wrapper -->
    </div>
@endsection

<!-- Templates and Scripts -->
<template id="section-template">
    <div class="background-card outer-section-card" data-id="__ID__">
        <!-- View Mode -->
        <div class="card-information d-flex align-items-center px-3 view-mode">
            <div class="ps-3 w-100">
                <div class="d-flex flex-md-row flex-sm-column w-auto justify-content-start align-items-center">
                    <h2 class="fst-italic roboto-title mb-0 align-self-center section-name" style="cursor: pointer;">
                        New Section
                    </h2>
                    <div class="align-self-start ms-auto d-flex gap-2">
                        <a href="javascript:void(0);" class="btn btn-danger btn-delete" data-id="__ID__">
                            <i class='bx bx-trash'></i>
                            <span class="d-none d-xl-inline ms-1">Hapus</span>
                        </a>
                    </div>
                </div>

                <hr class="my-2" style="border: 2px solid black; opacity: 1">

                <p class="roboto-light mb-1 mt-2 description-text" style="font-size: 15px;">
                    Questions: 0
                </p>
            </div>
        </div>

        <!-- Edit Mode -->
        <div class="edit-mode" style="display: none;">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="mb-3">
                        <label for="survey_sections_name___ID__" class="form-label">Nama Section</label>
                        <input type="text" class="form-control" id="survey_sections_name___ID__"
                            name="sections[__SECTION_INDEX__][survey_sections_name]" required value="New Section">
                        <input type="hidden" name="sections[__SECTION_INDEX__][id]" value="__ID__">
                    </div>
                </div>
            </div>

            <div class="section-body-container" data-section-index="__SECTION_INDEX__">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="me-2">Section <span class="section-number">__SECTION_NUMBER__</span></span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-link toggle-section">
                            <i class="fas fa-chevron-up"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body section-body">
                    <div class="questions-container">
                        <!-- Questions will be added here -->
                    </div>
                    <div class="mt-3">
                        <button type="button" class="btn btn-info w-100 add-question"
                            data-section-index="__SECTION_INDEX__">
                            <i class="fas fa-plus"></i> Tambah Pertanyaan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

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
                    <label class="form-label"> </label>
                    <div class="form-check mt-2">
                        <input type="hidden"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][is_required]"
                            value="0">
                        <input class="form-check-input" type="checkbox"
                            name="sections[__SECTION_INDEX__][questions][__QUESTION_INDEX__][is_required]"
                            id="is_required___SECTION_INDEX_____QUESTION_INDEX__" value="1">
                        <label class="form-check-label" for="is_required___SECTION_INDEX_____QUESTION_INDEX__">Wajib
                            Diisi</label>
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

<style>
    /* Scrollable wrapper */
    .survey-wrapper {
        overflow-y: auto;
        max-height: calc(100vh - 100px);
        width: 100%;
        padding-bottom: 20px;
    }

    .card-information {
        display: flex;
        align-items: center;
        gap: 15px;
        justify-content: space-between;
        /* Align with global card sizes */
        width: 900px;
        /* Match card-lowongan, card-artikel, card-pengumuman */
        min-height: 250px;
        /* Keep consistent height */
        background-color: #7fc7d9;
        /* From global CSS */
        border-radius: 32px;
        /* From global CSS */
        padding: 10px 0px;
        /* From global CSS */
        box-shadow: 0px 6px 6px rgba(0, 0, 0, 0.1);
        /* From global CSS */
        transition: all 0.4s ease;
        /* Smooth transition for size changes */
    }

    .card-information .ps-3 {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .description-text {
        font-size: 15px;
        line-height: 1.6;
        margin-top: 10px;
        margin-bottom: 0;
    }

    .question-list {
        margin-top: 10px;
        padding-left: 20px;
        list-style-type: disc;
        word-wrap: break-word;
        max-height: 150px;
        overflow-y: auto;
    }

    .question-list li {
        margin-bottom: 5px;
    }

    /* Base styles for outer-section-card */
    .outer-section-card {
        padding: 20px;
        margin: 0 auto;
        border-radius: 10px;
        background-color: #e0f7fa;
        position: relative;
        transition: all 0.4s ease;
        width: 940px;
        /* Ensure parent is wide enough for card-information (900px + 20px padding on each side) */
    }

    /* View mode size */
    .outer-section-card .view-mode {
        width: 900px;
        /* Match card-information */
        min-height: 250px;
        /* Same as card-information */
    }

    /* Edit mode size */
    .outer-section-card .edit-mode {
        width: 900px;
        /* Match global card size for consistency */
        min-height: 0;
        /* Remove min-height to allow collapsing */
        transition: all 0.4s ease;
        /* Ensure smooth height transition */
    }

    /* Collapsed state for edit mode */
    .outer-section-card.collapsed .edit-mode {
        min-height: 0;
        /* Ensure it can collapse fully */
        height: auto;
        /* Allow height to adjust based on visible content */
    }

    .edit-mode,
    .view-mode {
        width: 100%;
        /* Ensure full width within the card */
    }

    .main-content {
        padding: 20px 10px 0px 20px;
        min-height: 100vh;
        height: auto !important;
        overflow: hidden;
    }

    /* Ensure toggle animation works */
    .edit-mode {
        display: none;
        transition: all 0.4s ease;
    }

    .view-mode {
        display: flex;
    }

    /* Responsive adjustments */
    @media (max-width: 1000px) {
        .outer-section-card {
            width: 90%;
            /* Adjust parent width */
        }

        .outer-section-card .view-mode {
            width: 100%;
            /* Full width of parent */
            min-height: 200px;
        }

        .outer-section-card .edit-mode {
            width: 100%;
            /* Full width of parent */
            min-height: 0;
            /* Allow collapsing */
        }

        .card-information {
            width: 100%;
            /* Full width of parent */
            min-height: 200px;
        }

        .outer-section-card.collapsed .edit-mode {
            min-height: 0;
        }
    }

    @media (min-width: 1200px) {
        .outer-section-card {
            width: 940px;
            /* Ensure parent matches card width + padding */
        }

        .outer-section-card .view-mode {
            width: 900px;
            min-height: 300px;
        }

        .outer-section-card .edit-mode {
            width: 900px;
            min-height: 0;
            /* Allow collapsing */
        }

        .card-information {
            width: 900px;
            min-height: 300px;
        }

        .outer-section-card.collapsed .edit-mode {
            min-height: 0;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let isToggling = false;

        // Toggle edit mode by clicking section name
        $(document).on('click', '.section-name', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Section name clicked');

            const $sectionCard = $(this).closest('.outer-section-card');
            const $editMode = $sectionCard.find('.edit-mode');
            const $viewMode = $sectionCard.find('.view-mode');

            if ($sectionCard.data('isToggling') || isToggling) {
                console.log('Toggling in progress, skipping');
                return;
            }

            isToggling = true;
            $sectionCard.data('isToggling', true);
            console.log('Toggling started, editMode visible:', $editMode.is(':visible'));

            if ($editMode.is(':visible')) {
                $sectionCard.removeClass('edit-mode-active').addClass('view-mode-active');
                $editMode.stop(true, true).slideUp({
                    duration: 600,
                    complete: function() {
                        console.log('SlideUp completed');
                        $viewMode.show();
                        $sectionCard.data('isToggling', false);
                        isToggling = false;
                    }
                });
            } else {
                $sectionCard.removeClass('view-mode-active').addClass('edit-mode-active');
                $viewMode.hide();
                $editMode.stop(true, true).slideDown({
                    duration: 600,
                    complete: function() {
                        console.log('SlideDown completed');
                        $sectionCard.data('isToggling', false);
                        isToggling = false;
                    }
                });
            }
        });

        // Add new section
        $(document).on('click', '.add-section', function() {
            const newId = nextId++;
            const $sectionsContainer = $('#sections-container');
            const newSectionIndex = sectionIndex++;

            const sectionTemplate = $('#section-template').html()
                .replace(/__ID__/g, newId)
                .replace(/__SECTION_INDEX__/g, newSectionIndex)
                .replace(/__SECTION_NUMBER__/g, newSectionIndex + 1);
            $sectionsContainer.append(sectionTemplate);
        });

        // Delete section
        $(document).on('click', '.btn-delete', function() {
            const sectionId = $(this).closest('.outer-section-card').data('id');
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Section dan pertanyaan yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('admin.surveys.destroy', '__ID__') }}".replace('__ID__',
                            sectionId), {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    title: 'Sukses!',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    title: 'Oops!',
                                    text: data.error || 'Gagal menghapus section.',
                                    icon: 'error',
                                    confirmButtonColor: '#d33'
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                title: 'Oops!',
                                text: 'Terjadi kesalahan: ' + error.message,
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        });
                }
            });
        });

        // Toggle section in edit mode
        $(document).on('click', '.toggle-section', function() {
            const $icon = $(this).find('i');
            const $sectionBody = $(this).closest('.section-body-container').find('.section-body');
            const $sectionCard = $(this).closest('.outer-section-card');

            if ($sectionBody.is(':visible')) {
                $sectionBody.slideUp({
                    duration: 400,
                    complete: function() {
                        $icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
                        $sectionCard.addClass('collapsed'); // Add collapsed class
                    }
                });
            } else {
                $sectionCard.removeClass('collapsed'); // Remove collapsed class before expanding
                $sectionBody.slideDown({
                    duration: 400,
                    complete: function() {
                        $icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
                    }
                });
            }
        });

        // Add question
        $(document).on('click', '.add-question', function() {
            const sectionIndex = $(this).data('section-index');
            const $questionsContainer = $(this).closest('.section-body-container').find(
                '.questions-container');
            const questionIndex = $questionsContainer.find('.question-card').length;

            let questionTemplate = $('#question-template').html()
                .replace(/__SECTION_INDEX__/g, sectionIndex)
                .replace(/__QUESTION_INDEX__/g, questionIndex);
            $questionsContainer.append(questionTemplate);
        });

        // Delete question
        $(document).on('click', '.delete-question', function() {
            if (confirm('Yakin ingin menghapus pertanyaan ini?')) {
                $(this).closest('.question-card').remove();
            }
        });

        // Question type change
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

        // Add option
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

        // Delete option
        $(document).on('click', '.delete-option', function() {
            if ($(this).closest('.options-container').find('.option-row').length > 1) {
                $(this).closest('.option-row').remove();
            } else {
                alert('Pertanyaan harus memiliki minimal satu opsi!');
            }
        });

        // Trigger question type change on load
        $('.question-type').each(function() {
            $(this).trigger('change');
        });

        // Form submission validation
        $('#survey-form').on('submit', function(e) {
            let isValid = true;

            console.log('Validating form data before submission...');
            $('.outer-section-card').each(function(index) {
                const $section = $(this);
                const $sectionNameInput = $section.find(
                    'input[name^="sections"][name$="[survey_sections_name]"]');
                const sectionName = $sectionNameInput.val();
                console.log(`Section ${index}:`, {
                    inputFound: $sectionNameInput.length > 0,
                    sectionName: sectionName,
                    nameAttr: $sectionNameInput.attr('name')
                });

                if ($sectionNameInput.length === 0) {
                    alert('Input untuk nama section tidak ditemukan pada section ' + (index +
                        1) + '!');
                    isValid = false;
                    return false;
                }

                if (!sectionName || sectionName.trim() === '') {
                    alert('Nama section harus diisi pada section ' + (index + 1) + '!');
                    isValid = false;
                    return false;
                }

                const questionCount = $section.find('.section-body-container .question-card')
                    .length;
                if (questionCount === 0) {
                    alert('Setiap section harus memiliki minimal 1 pertanyaan pada section ' + (
                        index + 1) + '!');
                    isValid = false;
                    return false;
                }

                $section.find('.question-type').each(function() {
                    if (!$(this).val()) {
                        alert('Semua pertanyaan harus memiliki tipe yang dipilih pada section ' +
                            (index + 1) + '!');
                        isValid = false;
                        return false;
                    }
                });
            });

            if ($('.outer-section-card').length === 0) {
                alert('Harap tambahkan minimal satu section!');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                console.log('Form submission prevented due to validation errors.');
            } else {
                console.log('Form validation passed, submitting form...');
            }
        });

        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        let sectionIndex = {{ count($sections) }};
        let nextId = {{ $sections->max('id') + 1 ?? 1 }};
    });
</script>
