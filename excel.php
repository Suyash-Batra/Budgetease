<?php
require 'vendor/autoload.php';
include("connection.php");
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION['email'])) {
    die("User not logged in.");
}

$email = $_SESSION['email'];

$sql = "SELECT Date, Category, Amount FROM transaction WHERE Email = '$email'";
$result = mysqli_query($con, $sql);

if (!$result) {
    die("Error fetching data: " . mysqli_error($con));
}

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $dates = json_decode($row['Date'], true) ?? [];
    $categories = json_decode($row['Category'], true) ?? [];
    $amounts = json_decode($row['Amount'], true) ?? [];

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'Date');
    $sheet->setCellValue('B1', 'Category');
    $sheet->setCellValue('C1', 'Amount');

    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getColumnDimension('B')->setWidth(30);
    $sheet->getColumnDimension('C')->setWidth(15);

    $rowNumber = 2;
    foreach ($dates as $index => $date) {
        $sheet->setCellValue('A' . $rowNumber, date('Y-m-d', strtotime($date)));
        $sheet->setCellValue('B' . $rowNumber, htmlspecialchars($categories[$index]));
        $sheet->setCellValue('C' . $rowNumber, htmlspecialchars($amounts[$index]));
        $rowNumber++;
    }

    $writer = new Xlsx($spreadsheet);
    $fileName = 'transactions_' . $email . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit;
} else {
    echo "No transactions found.";
}

mysqli_close($con);
?>
