<?php
session_start();
require_once '../pro/connection.php';

// Check if doctor is logged in
if (!isset($_SESSION['doc_email'])) 
{
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied. Please log in as a doctor.');
}

// Verify appointment ID was provided
if (!isset($_GET['appointment_id']) || !is_numeric($_GET['appointment_id'])) {
    header('HTTP/1.1 400 Bad Request');
    exit('Invalid appointment ID.');
}

$appointment_id = (int)$_GET['appointment_id'];

// Get file information from database
$query = "SELECT a.file_path, p.name AS patient_name 
          FROM appointment_records a
          JOIN patient_records p ON a.patient_id = p.patient_id
          WHERE a.appointment_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('HTTP/1.1 404 Not Found');
    exit('Appointment not found.');
}

$appointment = $result->fetch_assoc();

// Verify file exists
$base_dir = '../secure_uploads/patient_docs/';
$file_path = $base_dir . $appointment['file_path'];

if (!file_exists($file_path)) {
    header('HTTP/1.1 404 Not Found');
    exit('File not found on server.');
}

// Get file info
$original_filename = basename($file_path);
$file_size = filesize($file_path);
$file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));

// Validate file extension
$allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
if (!in_array($file_extension, $allowed_extensions)) {
    header('HTTP/1.1 403 Forbidden');
    exit('File type not allowed.');
}

// Generate download filename
$clean_patient_name = preg_replace('/[^a-zA-Z0-9-_]/', '_', $appointment['patient_name']);
$download_name = 'medical_report_' . $clean_patient_name . '_' . date('Y-m-d') . '.' . $file_extension;

// Log the download
file_put_contents('../logs/file_downloads.log', 
    date('Y-m-d H:i:s') . " - Doctor downloaded $original_filename for appointment $appointment_id\n", 
    FILE_APPEND);

// Clear any existing output buffers
while (ob_get_level()) ob_end_clean();

// Set headers - use application/force-download for reliable downloads
header("Content-Description: File Transfer");
header("Content-Type: application/force-download");
header("Content-Disposition: attachment; filename=\"" . basename($download_name) . "\""); 
header("Content-Length: " . $file_size);
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");

// For specific file types, set proper content type
if ($file_extension === 'pdf') {
    header("Content-Type: application/pdf");
} elseif ($file_extension === 'jpg' || $file_extension === 'jpeg') {
    header("Content-Type: image/jpeg");
} elseif ($file_extension === 'png') {
    header("Content-Type: image/png");
} elseif ($file_extension === 'doc') {
    header("Content-Type: application/msword");
} elseif ($file_extension === 'docx') {
    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
}

// Read the file and output it
$chunk_size = 1024 * 1024; // 1MB chunks
$handle = fopen($file_path, 'rb');

if ($handle === false) {
    header('HTTP/1.1 500 Internal Server Error');
    exit('Error reading file.');
}

// Output the file in chunks
while (!feof($handle)) {
    $buffer = fread($handle, $chunk_size);
    echo $buffer;
    ob_flush();
    flush();
    
    // Break if the connection is closed
    if (connection_status() != 0) {
        fclose($handle);
        exit;
    }
}

fclose($handle);
exit;
?>