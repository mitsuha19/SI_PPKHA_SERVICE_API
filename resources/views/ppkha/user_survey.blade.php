@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <div class="detail-content">
        <div class="pengumuman-section">
            <h2 class="section-title">USER SURVEY</h2>
        </div>

        <div class="user-survey">
            <div class="user-survey-content">
                @if (isset($message))
                    <!-- Condition 1: No surveys available -->
                    <div class="alert alert-warning text-center mt-3">
                        {{ $message }}
                    </div>
                @else
                    <!-- Condition 2: Surveys available -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('survey.submit') }}" method="POST" id="surveyForm">
                        @csrf
                        @foreach ($surveySections as $sectionIndex => $section)
                            <div class="card-user-survey">
                                <h1 class="montserrat-medium text-black" style="font-size: 22px; font-style: italic;">
                                    {{ $sectionIndex + 1 }}. {{ $section->survey_sections_name }}
                                </h1>
                                @foreach ($section->surveys as $questionIndex => $question)
                                    @php
                                        $isRequired = $question->is_required;
                                    @endphp
                                    @switch($question->type_question_id)
                                        @case(1)
                                            <!-- Text Input -->
                                            <div class="box-form">
                                                <label for="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    class="montserrat-medium text-black mb-2">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]"
                                                    placeholder="Masukkan {{ $question->question_title }}"
                                                    @if ($isRequired) required @endif>
                                            </div>
                                        @break

                                        @case(2)
                                            <!-- Textarea -->
                                            <div class="box-form">
                                                <label for="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    class="montserrat-medium text-black mb-2">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <textarea class="form-control" id="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]" rows="3"
                                                    placeholder="Masukkan {{ $question->question_title }}" @if ($isRequired) required @endif></textarea>
                                            </div>
                                        @break

                                        @case(3)
                                            <input type="hidden" name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]"
                                                value="">
                                            <!-- Radio Buttons -->
                                            <div class="radioButtons">
                                                <h3 class="montserrat-medium text-black mb-2" style="font-size: 16px;">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </h3>
                                                @foreach ($question->surveyOptions as $optionIndex => $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]"
                                                            id="option_{{ $sectionIndex }}_{{ $questionIndex }}_{{ $optionIndex }}"
                                                            value="{{ $option->option_body }}"
                                                            @if ($isRequired) required @endif>
                                                        <label class="form-check-label" style="font-style: italic;"
                                                            for="option_{{ $sectionIndex }}_{{ $questionIndex }}_{{ $optionIndex }}">{{ $option->option_body }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @break

                                        @case(4)
                                            <!-- Checkboxes -->
                                            <div class="radioButtons">
                                                <h3 class="montserrat-medium text-black mb-2" style="font-size: 16px;">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </h3>
                                                @foreach ($question->surveyOptions as $optionIndex => $option)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            name="answers[{{ $sectionIndex }}][{{ $questionIndex }}][]"
                                                            id="option_{{ $sectionIndex }}_{{ $questionIndex }}_{{ $optionIndex }}"
                                                            value="{{ $option->option_body }}">
                                                        <label class="form-check-label" style="font-style: italic;"
                                                            for="option_{{ $sectionIndex }}_{{ $questionIndex }}_{{ $optionIndex }}">{{ $option->option_body }}</label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @break

                                        @case(5)
                                            <!-- Dropdown -->
                                            <div class="box-form">
                                                <label for="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    class="montserrat-medium text-black mb-2">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <select class="form-control survey-dropdown"
                                                    name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]"
                                                    id="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    @if ($isRequired) required @endif>
                                                    <option value="" disabled selected>Pilih Opsi</option>
                                                    @foreach ($question->surveyOptions as $optionIndex => $option)
                                                        <option value="{{ $option->option_body }}">{{ $option->option_body }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @break

                                        @case(6)
                                            <!-- Linear Scale -->
                                            <div class="box-form">
                                                <label for="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    class="montserrat-medium text-black mb-2">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <div class="linear-scale">
                                                    @php
                                                        $options = $question->surveyOptions
                                                            ->pluck('option_body')
                                                            ->map(function ($value) {
                                                                return is_numeric($value) ? (int) $value : 0;
                                                            })
                                                            ->sort()
                                                            ->values();
                                                        $start = $options->isNotEmpty() ? $options->first() : 0;
                                                        $end = $options->isNotEmpty() ? $options->last() : 5;
                                                        $end = max($start, $end);
                                                        if ($end < $start) {
                                                            $temp = $start;
                                                            $start = $end;
                                                            $end = $temp;
                                                        }
                                                    @endphp
                                                    @for ($i = $start; $i <= $end; $i++)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]"
                                                                id="option_{{ $sectionIndex }}_{{ $questionIndex }}_{{ $i }}"
                                                                value="{{ $i }}"
                                                                @if ($isRequired) required @endif>
                                                            <label class="form-check-label" style="font-style: italic;"
                                                                for="option_{{ $sectionIndex }}_{{ $questionIndex }}_{{ $i }}">{{ $i }}</label>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        @break

                                        @default
                                            <div class="box-form">
                                                <label for="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    class="montserrat-medium text-black mb-2">
                                                    {{ $question->question_title }}
                                                    @if ($isRequired)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control"
                                                    id="question_{{ $sectionIndex }}_{{ $questionIndex }}"
                                                    name="answers[{{ $sectionIndex }}][{{ $questionIndex }}]"
                                                    placeholder="Masukkan {{ $question->question_title }}"
                                                    @if ($isRequired) required @endif>
                                            </div>
                                    @endswitch
                                @endforeach
                            </div>
                        @endforeach
                        <button type="submit" class="userSurveyButton" style="justify-content: center" id="submitButton">
                            <span class="button-text">Kirim</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('surveyForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent traditional form submission

            const submitButton = document.getElementById('submitButton');
            const buttonText = submitButton.querySelector('.button-text');
            const spinner = submitButton.querySelector('.spinner-border');

            // Show loading state
            buttonText.classList.add('d-none');
            spinner.classList.remove('d-none');
            submitButton.disabled = true;

            const formData = new FormData(this);

            fetch("{{ route('survey.submit') }}", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert(data.message); // Simple alert, can be replaced with a better UI
                        window.location.reload(); // Reload or redirect as needed
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim survei.');
                })
                .finally(() => {
                    // Reset button state
                    buttonText.classList.remove('d-none');
                    spinner.classList.add('d-none');
                    submitButton.disabled = false;
                });
        });
    </script>
@endsection
