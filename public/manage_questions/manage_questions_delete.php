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

// Fetch the question details to get the image file path and folder
$questionQuery = "SELECT QuestionImage FROM Questions WHERE QuestionID = ?";
$stmtQuestion = $conn->prepare($questionQuery);
$stmtQuestion->bind_param('i', $id);
$stmtQuestion->execute();
$resultQuestion = $stmtQuestion->get_result();

if ($rowQuestion = $resultQuestion->fetch_assoc()) {
    $questionImage = $rowQuestion['QuestionImage'];

    // Check if the question has an associated image
    if (!empty($questionImage) && file_exists($questionImage)) {
        // Delete the image file
        unlink($questionImage);

        // Get the folder path and attempt to delete it
        $folderPath = dirname($questionImage);
        if (is_dir($folderPath)) {
            rmdir($folderPath);
        }
    }
}

$stmtQuestion->close();

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
