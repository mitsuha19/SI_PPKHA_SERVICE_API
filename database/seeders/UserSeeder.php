<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
  public function run()
  {
    // Create roles if they don't exist
    $adminRole = Role::firstOrCreate(['name' => 'admin']);
    $alumniRole = Role::firstOrCreate(['name' => 'alumni']);

    // Retrieve Fakultas records
    $fakultasFite = Fakultas::where('name', 'Fakultas Informatika dan Teknik Elektro')->first();
    $fakultasVokasi = Fakultas::where('name', 'Vokasi')->first();

    // Retrieve Prodi records
    // For admin, we use a Prodi that belongs to FITE.
    $prodiS1 = Prodi::where('name', 'S1 Informatika')->first();
    // For alumni user(s), you can use other Prodi as intended.
    $prodiDiv = Prodi::where('name', 'DIV Teknologi Rekayasa Perangkat Lunak')->first();

    // ADMIN USER (assign admin role)
    $admin = User::create([
      'name'        => 'Admin Albukerki',
      'nim'         => 'admin000',
      'prodi_id'    => $prodiS1 ? $prodiS1->id : null,  // Use S1 Informatika, which belongs to FITE
      'tahun_lulus' => 2012,
      'fakultas_id' => $fakultasFite ? $fakultasFite->id : null,
      'password'    => Hash::make('admin000'),
    ]);
    $admin->assignRole($adminRole);

    // ALUMNI USER 1: Alumni Pinkman
    $alumni1 = User::create([
      'name'        => 'Alumni Pinkman',
      'nim'         => 'alumni001',
      'prodi_id'    => $prodiDiv ? $prodiDiv->id : null,
      'tahun_lulus' => 2022,
      'fakultas_id' => $fakultasVokasi->id,
      'password'    => Hash::make('alumni001'),
    ]);
    $alumni1->assignRole($alumniRole);

    // ALUMNI USER 2: Alumni Skyler
    $alumni2 = User::create([
      'name'        => 'Alumni Skyler',
      'nim'         => 'alumni002',
      'prodi_id'    => $prodiS1 ? $prodiS1->id : null,
      'tahun_lulus' => 2022,
      'fakultas_id' => $fakultasFite->id,
      'password'    => Hash::make('alumni002'),
    ]);
    $alumni2->assignRole($alumniRole);
  }
}
