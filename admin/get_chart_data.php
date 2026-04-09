<?php
require_once '../config/function.php';

// Data statis untuk chart
$chart_data = [
    'purchases' => [
        'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        'data' => [5, 8, 6, 9, 7, 10, 8]
    ],
    'sales' => [
        'labels' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
        'data' => [3, 5, 4, 6, 5, 7, 6]
    ]
];

// Kirim response
header('Content-Type: application/json');
echo json_encode($chart_data);
exit;