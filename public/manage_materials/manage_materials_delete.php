<?php
session_start();
require_once('../../database/connection.php');
include_once('../components/header.php');

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

// Check if the question ID is provided in the query parameter
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to an error page or an appropriate location
    header('Location: error.php');
    exit();
}

$id = $_GET['id'];

// Initialize success and error messages
$success_message = '';
$error_message = '';

// Delete associated answers
$queryDeleteAnswers = "DELETE FROM Answers WHERE QuestionID = ?";
$stmtDeleteAnswers = $conn->prepare($queryDeleteAnswers);
$stmtDeleteAnswers->bind_param('i', $id);

// Delete associated student responses
$queryDeleteStudentResponses = "DELETE FROM StudentResponses WHERE QuestionID = ?";
$stmtDeleteStudentResponses = $conn->prepare($queryDeleteStudentResponses);
$stmtDeleteStudentResponses->bind_param('i', $id);

// Delete the question
$queryDeleteQuestion = "DELETE FROM Questions WHERE QuestionID = ?";
$stmtDeleteQuestion = $conn->prepare($queryDeleteQuestion);
$stmtDeleteQuestion->bind_param('i', $id);

// Perform deletion operations
try {
    $conn->autocommit(false); // Start a transaction

    // Delete answers
    $stmtDeleteAnswers->execute();

    // Delete student responses
    $stmtDeleteStudentResponses->execute();

    // Delete the question
    $stmtDeleteQuestion->execute();

    $conn->commit(); // Commit the transaction

    // Activity description
    $activityDescription = "Question with QuestionID: $id has been deleted.";

    $currentUserID = $_SESSION['UserID'];
    insertLogActivity($conn, $currentUserID, $activityDescription);

    // Deletion successful
    $success_message = "Question and associated records have been deleted!";
} catch (Exception $e) {
    // If an error occurs, rollback the transaction
    $conn->rollback();

    // Deletion failed
    $error_message = "Failed to delete the question and associated records: " . $e->getMessage();
} finally {
    // Close all prepared statements
    $stmtDeleteAnswers->close();
    $stmtDeleteStudentResponses->close();
    $stmtDeleteQuestion->close();

    $conn->autocommit(true); // Turn autocommit back on
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
        window.location.href = '../manage_exams/manage_exams_detail.php?id={$_GET['test_id']}'; // Redirect to the exam detail page
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
        window.location.href = '../manage_exams/manage_exams_detail.php?id={$_GET['test_id']}'; // Redirect to the exam detail page
    });
    </script>";
}
?>

<div class="h-screen flex flex-col">
</div>