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
if (!isset($_GET['question_id']) || !is_numeric($_GET['question_id'])) {
    // Redirect to an error page or an appropriate location
    header('Location: error.php');
    exit();
}

$id = $_GET['question_id'];

if (!isset($_GET['test_id']) || !is_numeric($_GET['test_id'])) {
    // Redirect to an error page or an appropriate location
    header('Location: error.php');
    exit();
}

$test_id = $_GET['test_id'];

// Initialize success and error messages
$success_message = '';
$error_message = '';

// Delete associated answers
$deleteAnswersQuery = "DELETE FROM Answers WHERE QuestionID = ?";
$stmtDeleteAnswers = $conn->prepare($deleteAnswersQuery);
$stmtDeleteAnswers->bind_param('i', $id);

if ($stmtDeleteAnswers->execute()) {
    // Delete associated student responses
    $deleteResponsesQuery = "DELETE FROM StudentResponses WHERE QuestionID = ?";
    $stmtDeleteResponses = $conn->prepare($deleteResponsesQuery);
    $stmtDeleteResponses->bind_param('i', $id);

    if ($stmtDeleteResponses->execute()) {
        // Delete the question
        $deleteQuestionQuery = "DELETE FROM Questions WHERE QuestionID = ?";
        $stmtDeleteQuestion = $conn->prepare($deleteQuestionQuery);
        $stmtDeleteQuestion->bind_param('i', $id);

        if ($stmtDeleteQuestion->execute()) {
            // Activity description
            $activityDescription = "Question with QuestionID: $id has been deleted.";

            $currentUserID = $_SESSION['UserID'];
            insertLogActivity($conn, $currentUserID, $activityDescription);

            // Question deletion successful
            $success_message = "Question has been deleted!";
        } else {
            // Question deletion failed
            $error_message = "Failed to delete the question.";
        }

        $stmtDeleteQuestion->close();
    } else {
        // Student response deletion failed
        $error_message = "Failed to delete student responses.";
    }

    $stmtDeleteResponses->close();
} else {
    // Answer deletion failed
    $error_message = "Failed to delete associated answers.";
}

$stmtDeleteAnswers->close();

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
        window.location.href = '../manage_exams/manage_exams_detail.php?id=$test_id'; // Redirect to the questions list page
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
        window.location.href = '../manage_exams/manage_exams_detail.php?id=$test_id'; // Redirect to the questions list page
    });
    </script>";
}
?>

<div class="h-screen flex flex-col">
    <!-- You can add content specific to this page if needed -->
</div>