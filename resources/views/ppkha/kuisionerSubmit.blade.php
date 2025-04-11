@extends('layouts.app')

@section('content')
    @include('components.navbar')

    <style>
        .center-horizontal {
            display: flex;
            justify-content: center;
            margin-top: 0;
            padding: 0 20px;
        }

        .card-user-survey {
            text-align: center;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px 25px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
        }

        .card-user-survey h1 {
            font-size: 28px;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 20px;
        }

        .card-user-survey p {
            font-size: 16px;
            font-family: 'Poppins', sans-serif;
            color: #555;
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .signature {
            margin-top: 32px;
            font-size: 15px;
            color: #444;
        }

        .userSurveyButton {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            font-family: 'Poppins', sans-serif;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.2s ease;
        }

        .userSurveyButton:hover {
            background-color: #0056b3;
        }
    </style>

    <div class="detail-content center-horizontal">
        <div class="card-user-survey">
            <h1>🎉 Terima Kasih!</h1>

            <p>
                Jawaban Anda telah berhasil disimpan. Kami sangat menghargai partisipasi Anda dalam survei ini.
                Jika terdapat pertanyaan terkait kuesioner ini, Anda dapat menghubungi:
                <br><strong>PPKHA - Ritcan Hutahaean (WhatsApp): 082183009322</strong>
                <br>
                Kontribusi rekan-rekan alumni dalam pengisian tracer study ini sangat berguna untuk peningkatan kualitas
                institusi kita di masa yang akan datang. Terima kasih atas waktu dan effort yang diberikan.
            </p>


            <p class="signature">
                Salam MarTuhan, Marroha, Marbisuk<br>
                <strong>Tim Tracer Study IT Del</strong>
            </p>

            <a href="{{ url('/') }}" class="userSurveyButton">Kembali ke Beranda</a>
        </div>
    </div>

    @include('components.footer')
@endsection
