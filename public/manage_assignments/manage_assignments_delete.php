<?php
session_start();
require_once('../../database/connection.php');
include_once('../components/header.php');

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
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
$deleteSubmissionsQuery = "DELETE FROM AssignmentSubmissions WHERE AssignmentID = ?";
$stmtDeleteSubmissions = $conn->prepare($deleteSubmissionsQuery);
$stmtDeleteSubmissions->bind_param('i', $id);

if ($stmtDeleteSubmissions->execute()) {
    // Delete associated attachment file (if any)
    $selectAttachmentQuery = "SELECT AttachmentFile FROM AssignmentAttachments WHERE AssignmentID = ?";
    $stmtSelectAttachment = $conn->prepare($selectAttachmentQuery);
    $stmtSelectAttachment->bind_param('i', $id);
    $stmtSelectAttachment->execute();
    $stmtSelectAttachment->bind_result($attachmentFile);

    if ($stmtSelectAttachment->fetch()) {
        // Delete the attachment file from the server
        if (!empty($attachmentFile) && file_exists($attachmentFile)) {
            unlink($attachmentFile);
        }

        // Delete the assignment attachment record
        $deleteAttachmentQuery = "DELETE FROM AssignmentAttachments WHERE AssignmentID = ?";
        $stmtDeleteAttachment = $conn->prepare($deleteAttachmentQuery);
        $stmtDeleteAttachment->bind_param('i', $id);

        if (!$stmtDeleteAttachment->execute()) {
            // Attachment deletion failed
            $error_message = "Failed to delete associated attachment.";
        }

        $stmtDeleteAttachment->close();
    }

    $stmtSelectAttachment->close();

    // Delete the assignment
    $deleteAssignmentQuery = "DELETE FROM Assignments WHERE AssignmentID = ?";
    $stmtDeleteAssignment = $conn->prepare($deleteAssignmentQuery);
    $stmtDeleteAssignment->bind_param('i', $id);

    if ($stmtDeleteAssignment->execute()) {
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

    $stmtDeleteAssignment->close();
} else {
    // Assignment submission deletion failed
    $error_message = "Failed to delete assignment submissions.";
}

// Enable foreign key checks again
$conn->query('SET foreign_key_checks = 1');

$stmtDeleteSubmissions->close();

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
