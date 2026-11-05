<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        $validated = Validator::make(
            ['email' => $email, 'password' => $password],
            ['email' => ['required', 'email', 'max:255'], 'password' => ['required', Password::min(12)->mixedCase()->numbers()->symbols()]],
        )->validate();

        if (User::where('email', $validated['email'])->exists()) {
            $this->command?->warn('Email tersebut sudah terdaftar; akun yang ada tidak diubah.');

            return;
        }

        User::create([
            'name' => 'Administrator',
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'no_whatsapp' => '',
            'alamat' => '-',
        ])->forceFill(['role' => 'admin'])->save();

        $this->command?->info('Akun admin berhasil dibuat.');
    }
}
