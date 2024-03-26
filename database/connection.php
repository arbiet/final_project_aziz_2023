<?php
$host = 'localhost'; // Ganti dengan host MySQL Anda
$username = 'root'; // Ganti dengan username MySQL Anda
$password = ''; // Ganti dengan password MySQL Anda
$database = 'final_project_aziz_2023'; // Ganti dengan nama database Anda

// Membuat koneksi
$conn = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Base URL Configuration
$baseUrl = "http://localhost/final_project_aziz_2023/";

// base Title and description
$baseTitle = "ESAY.beta";
$baseDescription = "Sekolah Menengah Kejuruan Berbasis Teknologi dan Industri di Kota Kediri dengan 9 Kompetensi Keahlian. Beralamat di Jl. Veteran No. 9 Mojoroto Kota Kediri";

// Base Logo
$baseLogoUrl = "http://localhost/final_project_aziz_2023/static/logo.png";

function insertLogActivity($conn, $userID, $activityDescription)
{
    $query = "INSERT INTO LogActivity (UserID, ActivityDescription, ActivityTimestamp) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('is', $userID, $activityDescription);
    $stmt->execute();
    $stmt->close();
}

function generateRandomUserID()
{
    $timestamp = time(); // Get the current timestamp
    $randomPart = str_pad(mt_rand(1, 9999999999), 10, '0', STR_PAD_LEFT);

    $user_id = $timestamp . $randomPart;

    return substr($user_id, 0, 10); // Take the first 10 digits
}

// $hostname = "localhost";
// $db_username = "ikiz5613_admin_aziz";
// $db_password = "yongalah";
// $db_name = "ikiz5613_final_project_aziz_2023";

// // Membuat koneksi
// $conn = new mysqli($hostname, $db_username, $db_password, $db_name);

// // Memeriksa koneksi
// if ($conn->connect_error) {
//     die("Koneksi gagal: " . $conn->connect_error);
// }

// // Base URL Configuration
// $baseUrl = "https://asadulaziz.arbiet.my.id/";

// // Base Logo
// $baseLogoUrl = "https://asadulaziz.arbiet.my.id/static/logo.png";
