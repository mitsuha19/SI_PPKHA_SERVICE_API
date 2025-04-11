<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\Fakultas;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Retrieve Fakultas records first
        $vokasi = Fakultas::where('name', 'Vokasi')->first();
        $fite = Fakultas::where('name', 'Fakultas Informatika dan Teknik Elektro')->first();
        $fti = Fakultas::where('name', 'Fakultas Teknologi Industri')->first();
        $bio = Fakultas::where('name', 'Fakultas Bioteknologi')->first();

        Prodi::create([
            'name' => 'S1 Informatika',
            'fakultas_id' => $fite ? $fite->id : null,
        ]);

        Prodi::create([
            'name' => 'S1 Sistem Informasi',
            'fakultas_id' => $fite ? $fite->id : null,
        ]);

        Prodi::create([
            'name' => 'S1 Teknik Elektro',
            'fakultas_id' => $fite ? $fite->id : null,
        ]);

        Prodi::create([
            'name' => 'S1 Teknik Bioproses',
            'fakultas_id' => $bio ? $bio->id : null,
        ]);

        Prodi::create([
            'name' => 'S1 Manajemen Rekayasa',
            'fakultas_id' => $fti ? $fti->id : null,
        ]);

        Prodi::create([
            'name' => 'S1 Teknik Metalurgi',
            'fakultas_id' => $fti ? $fti->id : null,
        ]);

        Prodi::create([
            'name' => 'DIII Teknologi Komputer',
            'fakultas_id' => $vokasi ? $vokasi->id : null,
        ]);

        Prodi::create([
            'name' => 'DIII Teknologi Informasi',
            'fakultas_id' => $vokasi ? $vokasi->id : null,
        ]);

        Prodi::create([
            'name' => 'DIV Teknologi Rekayasa Perangkat Lunak',
            'fakultas_id' => $vokasi ? $vokasi->id : null,
        ]);
    }
}
