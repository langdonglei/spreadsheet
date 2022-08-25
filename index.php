<?php

require_once __DIR__ . '/vendor/autoload.php';

$type = 'Xlsx';
$file1 = 's5.xlsx';
$file2 = 's6.xlsx';

$sheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type)->load($file1);
$sheet2 = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($type)->load($file2);
$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($sheet2, $type);

$count = $sheet1->getActiveSheet()->getHighestRow();
$start = $sheet2->getActiveSheet()->getHighestRow() + 2;

foreach ($sheet1->getActiveSheet()->getRowIterator(1, 1) as $item1) {
    foreach ($item1->getCellIterator() as $cell1) {
        $title1 = $cell1->getValue();
        $match = false;
        foreach ($sheet2->getActiveSheet()->getRowIterator(1, 1) as $item2) {
            foreach ($item2->getCellIterator() as $cell2) {
                $title2 = $cell2->getValue();
                if ($title1 == $title2) {
                    $match = true;
                    $line = $start;
                    for ($i = 2; $i <= $count; $i++) {
                        $line++;
                        $k = $cell2->getColumn() . $line;
                        $v = $sheet1->getActiveSheet()->getCell($cell1->getColumn() . $i)->getValue();
                        $sheet2->getActiveSheet()->setCellValue($k, $v);
                    }
                }
            }
        }
        if (!$match) {
            $new = $sheet2->getActiveSheet()->getHighestColumn();
            $new = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($new) + 1;
            $sheet2->getActiveSheet()->setCellValueByColumnAndRow($new, 1, $title1);

            $pend = $start;
            for ($j = 2; $j <= $count; $j++) {
                $pend++;

                $v = $sheet1->getActiveSheet()->getCell($cell1->getColumn() . $j)->getValue();

                $sheet2->getActiveSheet()->setCellValueByColumnAndRow($new, $pend, $v);
            }
        }
    }
}

$writer->save($file2);




