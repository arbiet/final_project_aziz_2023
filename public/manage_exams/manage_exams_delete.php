<?php
session_start();
require_once('../../database/connection.php');
include_once('../components/header.php');

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

// Check if the test ID is provided in the query parameter
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

// Delete associated student responses
$deleteResponsesQuery = "DELETE FROM StudentResponses WHERE QuestionID IN (SELECT QuestionID FROM Questions WHERE TestID = ?)";
$stmtDeleteResponses = $conn->prepare($deleteResponsesQuery);
$stmtDeleteResponses->bind_param('i', $id);

if ($stmtDeleteResponses->execute()) {
    // Delete associated answers
    $deleteAnswersQuery = "DELETE FROM Answers WHERE QuestionID IN (SELECT QuestionID FROM Questions WHERE TestID = ?)";
    $stmtDeleteAnswers = $conn->prepare($deleteAnswersQuery);
    $stmtDeleteAnswers->bind_param('i', $id);

    if ($stmtDeleteAnswers->execute()) {
        // Delete associated questions
        $deleteQuestionsQuery = "DELETE FROM Questions WHERE TestID = ?";
        $stmtDeleteQuestions = $conn->prepare($deleteQuestionsQuery);
        $stmtDeleteQuestions->bind_param('i', $id);

        if ($stmtDeleteQuestions->execute()) {
            // Delete the test
            $deleteTestQuery = "DELETE FROM Tests WHERE TestID = ?";
            $stmtDeleteTest = $conn->prepare($deleteTestQuery);
            $stmtDeleteTest->bind_param('i', $id);

            if ($stmtDeleteTest->execute()) {
                // Activity description
                $activityDescription = "Test with TestID: $id has been deleted.";

                $currentUserID = $_SESSION['UserID'];
                insertLogActivity($conn, $currentUserID, $activityDescription);

                // Test deletion successful
                $success_message = "Test has been deleted!";
            } else {
                // Test deletion failed
                $error_message = "Failed to delete the test.";
            }

            $stmtDeleteTest->close();
        } else {
            // Question deletion failed
            $error_message = "Failed to delete associated questions.";
        }

        $stmtDeleteQuestions->close();
    } else {
        // Answer deletion failed
        $error_message = "Failed to delete associated answers.";
    }

    $stmtDeleteAnswers->close();
} else {
    // Student response deletion failed
    $error_message = "Failed to delete student responses.";
}

// Enable foreign key checks again
$conn->query('SET foreign_key_checks = 1');

$stmtDeleteResponses->close();

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
        window.location.href = 'manage_exams_list.php'; // Redirect to the exams list page
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
        window.location.href = 'manage_exams_list.php'; // Redirect to the exams list page
    });
    </script>";
}
?>

<div class="h-screen flex flex-col">
    <!-- You can add content specific to this page if needed -->
</div>