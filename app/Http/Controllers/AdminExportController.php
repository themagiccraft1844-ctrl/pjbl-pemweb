<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\WishNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AdminExportController extends Controller
{
    /**
     * Export Data Catatan ke CSV (Bisa dibuka di Excel)
     */
    public function exportExcel()
    {
        $fileName = 'wishnotes_data_' . date('Y-m-d_H-i-s') . '.xlsx';
        $notes = WishNote::with('user')->latest()->get();

        $headers = [
            "Content-type"        => "text/xlsx",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Judul', 'Deskripsi', 'Pemilik', 'Tipe', 'Privasi', 'Jumlah Pesan', 'Tanggal Dibuat'];

        $callback = function() use($notes, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($notes as $note) {
                $row['ID']  = $note->id;
                $row['Judul']    = $note->judul;
                $row['Deskripsi']    = $note->deskripsi_singkat;
                $row['Pemilik']  = $note->user->name ?? 'Anonim';
                $row['Tipe']  = $note->tipe_wadah;
                $row['Privasi']  = $note->privasi;
                $row['Jumlah Pesan']  = $note->messages->count();
                $row['Tanggal Dibuat']  = $note->created_at->format('Y-m-d H:i');

                fputcsv($file, array($row['ID'], $row['Judul'], $row['Deskripsi'], $row['Pemilik'], $row['Tipe'], $row['Privasi'], $row['Jumlah Pesan'], $row['Tanggal Dibuat']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export Data Laporan ke PDF (Versi Print View Sederhana)
     * Catatan: Untuk PDF asli butuh library 'dompdf', ini solusi HTML Print-ready.
     */
    public function exportPdf()
    {
        $notes = WishNote::with('user')->latest()->get();
        $totalUsers = User::count();
        $totalNotes = WishNote::count();

        // Mengembalikan view khusus yang siap dicetak (Ctrl+P otomatis atau simpan sebagai PDF)
        return view('admin.export.pdf_view', compact('notes', 'totalUsers', 'totalNotes'));
    }
}