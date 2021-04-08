<?php
session_start();

class Index extends Controller
{

    public function __construct()
    {
        $this->turnOver = $this->model('Turnover');
        $this->brandCategory = $this->model('BrandCategory');
    }

    public function index()
    {


        //List All Brand Categories
        $brandCategories = $this->brandCategory->getAll();


        //Initialize Variables
        $startDate = "";
        $endDate = "";
        $isExcludedVat = false;
        $brandCategoryId = "";


        //Capture User Inputs
        $validationErrors = [];



        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            //Capture User Inputs
            $startDate = $_POST['start_date_input'];
            $endDate = $_POST['end_date_input'];
            $brandCategoryId = $_POST['brand_category_input'];

            /* Start Date,End Date Validation */
            $isValidStartDateInput = $this->isValidateDate($startDate);
            $isValidEndDateInput = $this->isValidateDate($endDate);


            if (!empty($startDate) && !empty($endDate)) {
                /* 1. Check Dates are in correct format */
                if (!$isValidStartDateInput || !$isValidEndDateInput) {
                    array_push($validationErrors, "Invalid Date Formats");
                }


                /* 2. End Date should always greater than from date */
                if (!($endDate >= $startDate)) {
                    array_push($validationErrors, "End Date should be  greater than from date");
                }
            }


            //Brand Category Validation
            $brandCategoryId = filter_var($brandCategoryId, FILTER_SANITIZE_STRING);


            //Exclude VAT Input
            if (isset($_POST['exclude_vat_input'])) {
                $isExcludedVat = $_POST['exclude_vat_input'];
                $isExcludedVat = filter_var($isExcludedVat, FILTER_VALIDATE_BOOLEAN);
            } else {
                $isExcludedVat = false;
            }


            //If there is any valiation errors , redirect to Main Page
            if (!empty($validationErrors)) {
                $_SESSION ['errors'] = $validationErrors;
                header("Location: " . SITE_URL . "/index.php");
                die();
            } else {
                unset ($_SESSION["errors"]); //If there is no errors, then unset "error" session
            }


        }

        //Turn Over
        $turnOver = $this->turnOver->getTurnOvers($startDate, $endDate, $brandCategoryId);


        //Get Unique Brand Items
        $brandsItemArr = $this->getUniqueBrandItems($turnOver);


        //Export Result To CSV
        $downloadLink = $this->getCSVDownloadLink($turnOver, $brandsItemArr, $isExcludedVat);


        //Pass Data to View
        $data = [
            "downLoadLink" => (!empty($downloadLink) ? $downloadLink : ""),
            "brandCategories" => $brandCategories,
            "turnOver" => $turnOver,
            "brandItems" => $brandsItemArr
        ];


        $this->view('pages/index', $data);
    }

    public function isValidateDate($date)
    {
        $date = DateTime::createFromFormat("Y-m-d", $date);
        return $date && $date->format("Y-m-d");
    }

    public function getUniqueBrandItems($turnOver)
    {
        //Brand Items

        $brandsItemArr = [];
        foreach ($turnOver as $to) {

            $brandId = $to->brand_id;
            $brandName = $to->name;
            $brandDescription = $to->description;
            $products = $to->products;
            $createdDate = $to->created;

            $brandItems = [
                'brandId' => $brandId,
                'brandName' => $brandName,
                'brandDescription' => $brandDescription,
                'products' => $products,
                'createdDate' => $createdDate
            ];

            $brandItems = (object)$brandItems;

            array_push($brandsItemArr, $brandItems);

        }


        $brandsItemArr = array_unique($brandsItemArr, 0);

        return $brandsItemArr;

    }

    public function getCSVDownloadLink($turnOver, $brandItems, $isExcludedTax = false)
    {
        //Generate Excel Sheet
        $currentDate = date('Y-m-d');
        $fileName = 'turn_over_report_'. $currentDate;
        $filePath = PUB_URL . '/reports/' . $fileName;
        //Save File
        $excelSheet = new Excelsheet;
        $excelSheet->save($turnOver, $brandItems, $filePath, $isExcludedTax);

        //Download Link
        $downloadLink = SITE_URL . "/public/reports/$fileName.csv";


        return $downloadLink;


    }


}