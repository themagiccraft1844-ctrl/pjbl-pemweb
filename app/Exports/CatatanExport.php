<?php

namespace App\Exports;

use App\Models\WishNote;
use Maatwebsite\Excel\Concerns\FromCollection;

class CatatanExport implements FromCollection
{
    public function collection()
    {
        return WishNote::select('id','judul','deskripsi_singkat','privasi','tipe_wadah','created_at')->get();
    }
}
