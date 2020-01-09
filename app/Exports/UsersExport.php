<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromArray, WithHeadings
{
    use Exportable;

    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function array(): array
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'E-mail',
            'Contract Start Date',
            'Contract End Date',
            'Type',
            'Verified'
        ];
    }
}
