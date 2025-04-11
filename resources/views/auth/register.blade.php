<!-- resources/views/auth/register.blade.php -->
@extends('layouts.auth')

@section('content')
    <div class="form-container">
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <h2>Register</h2>

            <div class="form-group">
                <i class="icon fa fa-user"></i>
                <input type="text" name="nim" id="nim" placeholder="Enter your NIM" value="{{ old('nim') }}" required>
                @error('nim') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <i class="icon fa fa-user-circle"></i>
                <input type="text" name="name" id="name" placeholder="Enter Your Name" value="{{ old('name') }}" required>
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <!-- <div class="form-group">
                    <i class="icon fa fa-graduation-cap"></i>
                    <input type="text" name="prodi" id="prodi" placeholder="Enter Your Program Study" value="{{ old('prodi') }}"
                        required>
                    @error('prodi') <span class="error">{{ $message }}</span> @enderror
                </div> -->

            <div class="form-group">
                <i class="icon fa fa-graduation-cap"></i>
                <select name="prodi" id="prodi" required>
                    <option value="" disabled selected>Select Your Program Study</option>
                    <option value="DIV Teknologi Rekayasa Perangkat Lunak">DIV Teknologi Rekayasa Perangkat Lunak</option>
                    <option value="DIII Teknologi Informasi">DIII Teknologi Informasi</option>
                    <option value="DIII Teknologi Komputer">DIII Teknologi Komputer</option>
                    <option value="S1 Informatika">S1 Informatika</option>
                    <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                    <option value="S1 Teknik Elektro">S1 Teknik Elektro</option>
                    <option value="S1 Teknik Bioproses">S1 Teknik Bioproses</option>
                    <option value="S1 Sistem Informasi">S1 Sistem Informasi</option>
                    <option value="S1 Manajemen Rekayasa">S1 Manajemen Rekayasa</option>
                    <option value="S1 Teknik Metalurgi">S1 Teknik Metalurgi</option>
                    <!-- Tambahkan pilihan lainnya sesuai kebutuhan -->
                </select>
                @error('prodi') <span class="error">{{ $message }}</span> @enderror
            </div>


            <div class="form-group">
                <i class="icon fa fa-building-o"></i>
                <select name="fakultas" id="fakultas" required>
                    <option value="" disabled selected>Select Your Faculty</option>
                    <option value="Vokasi">Vokasi</option>
                    <option value="Fakultas Informatika dan Teknik Elektro">Fakultas Informatika dan Teknik Elektro</option>
                    <option value="Fakultas Bioteknologi">Fakultas Bioteknologi</option>
                    <option value="Fakultas Teknologi Industri">Fakultas Teknologi Industri</option>
                    <!-- Tambahkan pilihan lainnya sesuai kebutuhan -->
                </select>
                @error('prodi') <span class="error">{{ $message }}</span> @enderror
            </div>

            <!-- <div class="form-group">

                    <input type="text" name="fakultas" id="fakultas" placeholder="Enter Your Faculty" required>
                    @error('fakultas') <span class="error">{{ $message }}</span> @enderror
                </div> -->

            <div class="form-group">
                <i class="icon fa fa-calendar"></i>
                <input type="number" name="tahun_lulus" id="tahun_lulus" placeholder="Enter Your Graduate Year"
                    value="{{ old('tahun_lulus') }}" required>
                @error('tahun_lulus') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <i class="icon fa fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Create new Password" required>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <i class="icon fa fa-lock"></i>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    placeholder="Confirmation of your password" required>
            </div>

            <button type="submit" class="btn">Register</button>


        </form>
    </div>
@endsection