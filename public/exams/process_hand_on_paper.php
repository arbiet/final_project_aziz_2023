<?php
// Include necessary files and start session
require_once('../../database/connection.php');
session_start();

// Check if the request is a GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve user and test information from the session or any other secure source
    $userID = $_SESSION['StudentID'];
    $testID = $_GET['test_id'];

    // Count the total number of questions for the given test
    $totalQuestionsQuery = "SELECT COUNT(*) AS totalQuestions FROM Questions WHERE TestID = $testID";
    $totalQuestionsResult = mysqli_query($conn, $totalQuestionsQuery);
    $totalQuestionsData = mysqli_fetch_assoc($totalQuestionsResult);
    $totalQuestions = $totalQuestionsData['totalQuestions'];

    // Count the total number of unique questions the student has answered
    $answeredQuestionsQuery = "SELECT COUNT(DISTINCT QuestionID) AS answeredQuestions FROM StudentResponses WHERE StudentID = $userID AND TestID = $testID";
    $answeredQuestionsResult = mysqli_query($conn, $answeredQuestionsQuery);
    $answeredQuestionsData = mysqli_fetch_assoc($answeredQuestionsResult);
    $answeredQuestions = $answeredQuestionsData['answeredQuestions'];

    // Check if all questions are answered
    if ($answeredQuestions == $totalQuestions) {
        // Calculate CorrectAnswers, IncorrectAnswers, and Score
        $correctAnswersQuery = "SELECT COUNT(*) AS correctCount FROM StudentResponses WHERE StudentID = $userID AND TestID = $testID AND IsCorrect = 1";
        $correctAnswersResult = mysqli_query($conn, $correctAnswersQuery);
        $correctAnswersData = mysqli_fetch_assoc($correctAnswersResult);
        $correctAnswers = $correctAnswersData['correctCount'];

        $incorrectAnswers = $answeredQuestions - $correctAnswers;
        $score = ($correctAnswers / $totalQuestions) * 100; // Assuming Score is a percentage

        // Check if a record for the user and test already exists in TestResults
        $checkTestResultsQuery = "SELECT * FROM TestResults WHERE StudentID = $userID AND TestID = $testID";
        $checkTestResultsResult = mysqli_query($conn, $checkTestResultsQuery);

        if (mysqli_num_rows($checkTestResultsResult) > 0) {
            // Update the TestResults table
            $updateTestResultsQuery = "UPDATE TestResults SET IsCompleted = 1, CorrectAnswers = ?, IncorrectAnswers = ?, Score = ? WHERE StudentID = ? AND TestID = ?";
            $stmt = mysqli_prepare($conn, $updateTestResultsQuery);
            mysqli_stmt_bind_param($stmt, "iiiii", $correctAnswers, $incorrectAnswers, $score, $userID, $testID);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($stmt) {
                $_SESSION['message'] = ['success', 'Test submitted successfully!'];
            } else {
                $_SESSION['message'] = ['error', 'Error updating TestResults: ' . mysqli_error($conn)];
            }
        } else {
            // Insert a new record into the TestResults table
            $insertTestResultsQuery = "INSERT INTO TestResults (StudentID, TestID, IsCompleted, CorrectAnswers, IncorrectAnswers, Score) VALUES (?, ?, 1, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertTestResultsQuery);
            mysqli_stmt_bind_param($stmt, "iiiii", $userID, $testID, $correctAnswers, $incorrectAnswers, $score);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($stmt) {
                $_SESSION['message'] = ['success', 'Test submitted successfully!'];
            } else {
                $_SESSION['message'] = ['error', 'Error inserting into TestResults: ' . mysqli_error($conn)];
            }
        }
    } else {
        $_SESSION['message'] = ['warning', 'Please answer all questions before submitting the test.'];
    }
} else {
    $_SESSION['message'] = ['error', 'Access Denied'];
}

// Redirect the user to an appropriate page
header("Location: exams_start.php?test_id={$testID}");
exit();
