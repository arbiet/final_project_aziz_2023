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

// Check if the assignment ID is provided in the query parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to an error page or an appropriate location
    header('Location: error.php');
    exit();
}

$id = $_GET['id'];

// Initialize success and error messages
$success_message = '';
$error_message = '';

// Disable foreign key checks to allow deletion in the correct order
$conn->query('SET foreign_key_checks = 0');

// Delete associated assignment submissions
$deleteSubmissionsQuery = "DELETE FROM AssignmentSubmissions WHERE AssignmentID = $id";

if ($conn->query($deleteSubmissionsQuery)) {
    // Delete associated attachment file (if any)
    $selectAttachmentQuery = "SELECT AttachmentFile FROM AssignmentAttachments WHERE AssignmentID = $id";
    $stmtSelectAttachment = $conn->query($selectAttachmentQuery);

    if ($attachmentFile = $stmtSelectAttachment->fetch_assoc()) {
        // Delete the attachment file from the server
        if (!empty($attachmentFile['AttachmentFile']) && file_exists($attachmentFile['AttachmentFile'])) {
            unlink($attachmentFile['AttachmentFile']);
        }

        // Delete the assignment attachment record
        $deleteAttachmentQuery = "DELETE FROM AssignmentAttachments WHERE AssignmentID = $id";
        if (!$conn->query($deleteAttachmentQuery)) {
            // Attachment deletion failed
            $error_message = "Failed to delete associated attachment.";
        }
    }

    // Delete the assignment
    $deleteAssignmentQuery = "DELETE FROM Assignments WHERE AssignmentID = $id";
    if ($conn->query($deleteAssignmentQuery)) {
        // Activity description
        $activityDescription = "Assignment with AssignmentID: $id has been deleted.";

        $currentUserID = $_SESSION['UserID'];
        insertLogActivity($conn, $currentUserID, $activityDescription);

        // Assignment deletion successful
        $success_message = "Assignment has been deleted!";
    } else {
        // Assignment deletion failed
        $error_message = "Failed to delete the assignment.";
    }
} else {
    // Assignment submission deletion failed
    $error_message = "Failed to delete assignment submissions.";
}

// Enable foreign key checks again
$conn->query('SET foreign_key_checks = 1');

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
        window.location.href = 'manage_assignments_list.php'; // Redirect to the assignments list page
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
        window.location.href = 'manage_assignments_list.php'; // Redirect to the assignments list page
    });
    </script>";
}
?>

<div class="h-screen flex flex-col">
    <!-- You can add content specific to this page if needed -->
</div>
