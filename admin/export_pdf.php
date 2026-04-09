<?php
// Start output buffering
ob_start();

require_once '../config/function.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Get date range from request
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// Get sales report data
$sales_report = get_sales_report($start_date, $end_date);
$total_revenue = get_total_revenue($start_date, $end_date);

// Create new PDF document
$pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('JangkrikNet Store');
$pdf->SetAuthor('JangkrikNet Store');
$pdf->SetTitle('Laporan Penjualan');

// Remove default header/footer
$pdf->setPrintHeader(true);
$pdf->setPrintFooter(true);

// Set default header data
$pdf->SetHeaderData('', 0, 'Laporan Penjualan', 'Periode: ' . date('d/m/Y', strtotime($start_date)) . ' - ' . date('d/m/Y', strtotime($end_date)));

// Set header and footer fonts
$pdf->setHeaderFont(Array('helvetica', '', 12));
$pdf->setFooterFont(Array('helvetica', '', 8));

// Set margins
$pdf->SetMargins(15, 15, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(10);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage();

// Set font
$pdf->SetFont('helvetica', '', 10);

// Summary information
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Ringkasan', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 7, 'Total Pendapatan: Rp ' . number_format($total_revenue['data']['total_revenue'], 0, ',', '.'), 0, 1, 'L');
$pdf->Cell(0, 7, 'Total Transaksi: ' . count($sales_report['data']), 0, 1, 'L');
$pdf->Ln(5);

// Table header
$pdf->SetFont('helvetica', 'B', 10);
$header = array('No', 'Tanggal', 'Kode Invoice', 'Pelanggan', 'Total', 'Status', 'Metode');
$w = array(10, 30, 30, 40, 30, 25, 25);

// Colors, line width and bold font
$pdf->SetFillColor(200, 200, 200);
$pdf->SetTextColor(0);
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.1);

// Header
for($i = 0; $i < count($header); $i++) {
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
}
$pdf->Ln();

// Color and font restoration
$pdf->SetFillColor(255, 255, 255);
$pdf->SetTextColor(0);
$pdf->SetFont('helvetica', '', 9);

// Data
$no = 1;
foreach($sales_report['data'] as $transaction) {
    $pdf->Cell($w[0], 6, $no++, 'LR', 0, 'C');
    $pdf->Cell($w[1], 6, date('d/m/Y H:i', strtotime($transaction['tanggal'])), 'LR', 0, 'C');
    $pdf->Cell($w[2], 6, $transaction['kode_invoice'], 'LR', 0, 'C');
    $pdf->Cell($w[3], 6, $transaction['nama_user'], 'LR', 0, 'L');
    $pdf->Cell($w[4], 6, 'Rp ' . number_format($transaction['total_harga'], 0, ',', '.'), 'LR', 0, 'R');
    $pdf->Cell($w[5], 6, $transaction['status'], 'LR', 0, 'C');
    $pdf->Cell($w[6], 6, ucfirst($transaction['metode_pembayaran']), 'LR', 0, 'C');
    $pdf->Ln();
}

// Closing line
$pdf->Cell(array_sum($w), 0, '', 'T');

// Clear any previous output
ob_end_clean();

// Output PDF
$pdf->Output('Laporan_Penjualan_' . date('Y-m-d') . '.pdf', 'D'); 