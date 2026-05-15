<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class RekamMedisExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function map($item): array
    {
        static $no = 1;
        return [
            $no++,
            $item->pendaftaran->nama_pasien ?? '-',
            $item->pendaftaran->poli ?? '-',
            $item->keluhan ?? '-',
            $item->diagnosis,
            $item->tindakan,
            str_replace(["\n", "\r"], ", ", $item->resep),
            $item->created_at->format('d-m-Y H:i')
        ];
    }

    public function headings(): array
    {
        return ['No', 'Nama Pasien', 'Poli', 'Keluhan', 'Diagnosis', 'Tindakan', 'Resep', 'Waktu'];
    }

    public function styles(Worksheet $sheet)
    {
        // KOP SURAT
        $sheet->setCellValue('A1', 'POLKES 05.09.15 JOMBANG');
        $sheet->setCellValue('A2', 'Jl. KH. Wahid Hasyim No.28 B');
        $sheet->setCellValue('A3', 'Jombang, Jawa Timur');
        $sheet->setCellValue('A4', 'Telp / WA: 0877-7723-5386 | Email: jombangposkes@gmail.com');
        $sheet->setCellValue('A6', 'LAPORAN REKAM MEDIS PASIEN');
        
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');
        $sheet->mergeCells('A4:H4');
        $sheet->mergeCells('A6:H6');

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A6')->getAlignment()->setHorizontal('center');
        
        // Header Tabel
        $sheet->getStyle('A9:H9')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
        $sheet->getStyle('A9:H9')->getFill()->setFillType('solid')->getStartColor()->setRGB('0F172A');

        return [];
    }
}