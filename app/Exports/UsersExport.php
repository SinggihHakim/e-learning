<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class UsersExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithTitle
{
    protected $role;

    public function __construct($role = null)
    {
        $this->role = $role;
    }

    public function query()
    {
        $query = User::query();
        if ($this->role) {
            $query->where('role', $this->role);
        }
        return $query->latest();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->nis,
            $user->name,
            $user->gender,
            $user->birth_place,
            $user->birth_date,
            $user->address,
            $user->religion,
            $user->phone,
            $user->father_name,
            $user->mother_name,
            $user->parent_address,
            $user->father_job,
            $user->mother_job,
            $user->email,
            ucfirst($user->role),
            $user->created_at->format('d M Y H:i'),
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIS',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Alamat',
            'Agama',
            'No. HP',
            'Nama Ayah',
            'Nama Ibu',
            'Alamat Orang Tua',
            'Pekerjaan Ayah',
            'Pekerjaan Ibu',
            'Email',
            'Role',
            'Registered At',
        ];
    }

    public function title(): string
    {
        return 'Users Export';
    }
}
