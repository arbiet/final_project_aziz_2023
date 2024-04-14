<?php
session_start();
require_once('../../database/connection.php');
include_once('../components/header2.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
  }

// Check if the subject ID is provided in the query parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to an error page or an appropriate location
    header('Location: error.php');
    exit();
}

$id = $_GET['id'];

// Initialize success and error messages
$success_message = '';
$error_message = '';

// Perform subject deletion
$query = "DELETE FROM Subjects WHERE SubjectID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    // Activity description
    $activityDescription = "Subject with SubjectID: $id has been deleted.";

    $currentUserID = $_SESSION['UserID'];
    insertLogActivity($conn, $currentUserID, $activityDescription);

    // Subject deletion successful
    $stmt->close();
    $success_message = "Subject has been deleted!";
} else {
    // Subject deletion failed
    $stmt->close();
    $error_message = "Failed to delete the subject.";
}

// Display success or error messages using SweetAlert2
if (!empty($success_message)) {
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '$success_message',
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location.href = 'manage_subjects_list.php'; // Redirect to the subjects list page
    });
    </script>";
} elseif (!empty($error_message)) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '$error_message',
        showConfirmButton: false,
        timer: 1500
    }).then(function() {
        window.location.href = 'manage_subjects_list.php'; // Redirect to the subjects list page
    });
    </script>";
}
?>

<div class="h-screen flex flex-col">
</div>