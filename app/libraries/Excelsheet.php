<?php
require LIBPATH . '/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;


class Excelsheet
{


    public function save($queryResult, $brandItems, $fileName, $isExcludedTax = false)
    {


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        /* Excel sheet table headings */
        $sheet->setCellValue('A1', 'Brand Name');
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Price (USD)');
        (($isExcludedTax) ? $sheet->setCellValue('D1', 'Price (Excluded 21% VAT)') : "");
        /* Excel sheet table headings */


        /* Initialization */
        $totalTurnOver = 0; //Total Amount of Turn Over
        $totalTurnOverWithoutTax = 0;  //Total Amount of Turn over without Tax
        $totalDataCount = count($queryResult); // Total Rows of Data
        $lastRowDataNo = $totalDataCount + 5; // Last index of Row
        $excelCordinateNo = 2; // Excel Sheet Cordination Starting Number


        /* Set Query Result Content to the Excel sheet */
        foreach ($queryResult as $qr) {

            $brandName = $qr->name;
            $turnOverDate = $qr->date;
            $turnOverAmount = $qr->turnover;
            $totalTurnOver = $totalTurnOver + $turnOverAmount;


            $sheet->setCellValue('A' . $excelCordinateNo, $brandName);
            $sheet->setCellValue('B' . $excelCordinateNo, $turnOverDate);
            $sheet->setCellValue('C' . $excelCordinateNo, $turnOverAmount);


            /* Price without VAT*/
            if ($isExcludedTax) {
                $priceExcludedTax = $this->getPrice(21, $turnOverAmount);
                $totalTurnOverWithoutTax = $totalTurnOverWithoutTax + $priceExcludedTax;
                $sheet->setCellValue('D' . $excelCordinateNo, $priceExcludedTax);
            }
            /* Price without VAT*/

            //Next Column
            $excelCordinateNo++;
        }


        /*Set Total Values To Excel sheet*/
        $sheet->setCellValue('E' . $lastRowDataNo, "TOTAL TURNOVER");
        $sheet->setCellValue('F' . $lastRowDataNo, $totalTurnOver);


        /*Total turnover (Excluded Tax)*/
        if ($isExcludedTax) {

            $lastRowDataNo++; //New Line

            $sheet->setCellValue('E' . $lastRowDataNo, "TOTAL TURNOVER (21% Excluded Tax)");
            $sheet->setCellValue('F' . $lastRowDataNo, $totalTurnOverWithoutTax);

            $lastRowDataNo++;
            $totalTaxAmount = ($totalTurnOver - $totalTurnOverWithoutTax);

            $sheet->setCellValue('E' . $lastRowDataNo, "TOTAL TAX AMOUNT");
            $sheet->setCellValue('F' . $lastRowDataNo, $totalTaxAmount);

        }

        /*Set Brand Items Details to Excel Sheet*/
        foreach ($brandItems as $bi) {
            $lastRowDataNo++; //New Row Line

            $brandName = $bi->brandName;
            $brandDescription = $bi->brandDescription;
            $products = $bi->products;
            $createdDate = $bi->createdDate;

            $sheet->setCellValue('A' . $lastRowDataNo, "BRAND NAME");
            $sheet->setCellValue('B' . $lastRowDataNo, $brandName);

            $lastRowDataNo++; //New Row Line

            $sheet->setCellValue('A' . $lastRowDataNo, "BRAND DESCRIPTION");
            $sheet->setCellValue('B' . $lastRowDataNo, (!empty($brandDescription) ? $brandDescription : "Empty"));

            $lastRowDataNo++; //New Row Line


            $sheet->setCellValue('A' . $lastRowDataNo, "PRODUCTS (QTY)");
            $sheet->setCellValue('B' . $lastRowDataNo, $products);

            $lastRowDataNo++; //New Row Line


            $sheet->setCellValue('A' . $lastRowDataNo, "CREATED DATE");
            $sheet->setCellValue('B' . $lastRowDataNo, $createdDate);

            $lastRowDataNo += 1; //Divider Line

        }


        /* Finalize and Save it */
        $writer = new Csv($spreadsheet);
        $writer->save($fileName . '.csv');
    }


    public function getPrice($vat_percentage, $turn_over_amount)
    {

        $vat_percentage = 21;
        $price_excluded_tax = (100 / (100 + $vat_percentage)) * $turn_over_amount;
        $price_excluded_tax = round($price_excluded_tax, 0);

        return $price_excluded_tax;

    }
}