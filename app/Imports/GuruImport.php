<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class GuruImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    WithChunkReading,
    WithBatchInserts
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            $emailExists = User::where('email', $row['email'])->exists();
            $nipExists = User::where('nip', $row['nip'])->exists();

            if ($emailExists || $nipExists) {
                Log::warning('Data guru dilewati saat import (Email/NIP sudah ada)', [
                    'nama' => $row['nama_lengkap'],
                    'nip' => $row['nip'],
                    'email' => $row['email']
                ]);
                continue;
            }

            $password = !empty($row['password']) ? $row['password'] : 'guru123';

            User::create([
                'name' => $row['nama_lengkap'],
                'nip' => $row['nip'],
                'email' => $row['email'],
                'phone' => $row['no_hp'],
                'address' => $row['alamat'],
                'password' => Hash::make($password),
                'role' => 'guru',
                'email_verified_at' => now(),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama_lengkap' => 'required|string|max:255',
            'nip' => 'required|string|max:20|unique:users,nip',
            'email' => 'required|email|max:255|unique:users,email',
            'no_hp' => 'nullable|numeric|digits_between:9,15',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama_lengkap.required' => 'Kolom "Nama Lengkap" wajib diisi.',
            'nip.required' => 'Kolom "NIP" wajib diisi.',
            'nip.unique' => 'NIP ini sudah terdaftar di sistem.',
            'email.required' => 'Kolom "Email" wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }
}
