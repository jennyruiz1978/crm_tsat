<?php

require_once RUTA_APP . '/../public/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExportImportExcel
{

    public static function exportarFichajesExcel($cabeceras, $filas, $nombreArchivo)
    {
        if (empty($filas)) {
            echo "<script>alert('No hay datos que exportar'); window.history.back();</script>";
            exit;
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $columna = 'A';
        foreach ($cabeceras as $cabecera) {
            $sheet->setCellValue($columna . '1', $cabecera);
            $sheet->getStyle($columna . '1')->getFont()->setBold(true);
            $columna++;
        }

        $fila = 2;
        foreach ($filas as $row) {
            $columna = 'A';
            foreach ($row as $valor) {
                $sheet->setCellValue($columna . $fila, $valor);
                $columna++;
            }
            $fila++;
        }

        foreach (range('A', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fullPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $nombreArchivo;
        $writer->save($fullPath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: max-age=0');

        readfile($fullPath);
        unlink($fullPath);
        exit;
    }
}