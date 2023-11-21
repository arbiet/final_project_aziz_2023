<?php
require_once('../../database/connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['answer']) || isset($_POST['answers'])) {
        // Retrieve user and question information from the session or any other secure source
        $userID = $_SESSION['StudentID'];
        $testID = $_GET['test_id'];
        $questionID = $_GET['question_id'];

        // Check if the user has already answered the question
        $existingResponseQuery = "SELECT * FROM StudentResponses 
                                  WHERE StudentID = $userID 
                                  AND TestID = $testID 
                                  AND QuestionID = $questionID";

        $existingResponseResult = mysqli_query($conn, $existingResponseQuery);

        if ($existingResponseResult) {
            $existingResponseData = mysqli_fetch_all($existingResponseResult, MYSQLI_ASSOC);

            // Delete existing responses for the question
            foreach ($existingResponseData as $existingResponse) {
                $responseID = $existingResponse['ResponseID'];
                $deleteResponseQuery = "DELETE FROM StudentResponses WHERE ResponseID = $responseID";
                $deleteResponseResult = mysqli_query($conn, $deleteResponseQuery);

                if (!$deleteResponseResult) {
                    $_SESSION['message'] = ['error', 'Error deleting existing responses: ' . mysqli_error($conn)];
                    exit();
                }
            }
        } else {
            $_SESSION['message'] = ['error', 'Error checking existing response: ' . mysqli_error($conn)];
            exit();
        }

        // Insert new responses
        if (isset($_POST['answer'])) {
            // Assuming single choice or true/false questions
            $answerID = $_POST['answer'];

            // Ensure a valid AnswerID is provided
            if ($answerID > 0) {
                // Check if the selected answer is correct
                $isCorrect = checkAnswerCorrectness($conn, $answerID);

                $insertResponseQuery = "INSERT INTO StudentResponses (StudentID, TestID, QuestionID, AnswerID, IsCorrect) 
                   VALUES ($userID, $testID, $questionID, $answerID, $isCorrect)";
                $questionType = 'single choice';

                $insertResponseResult = mysqli_query($conn, $insertResponseQuery);

                if (!$insertResponseResult) {
                    $_SESSION['message'] = ['error', 'Error inserting answer: ' . mysqli_error($conn)];
                    exit();
                }
            } else {
                $_SESSION['message'] = ['warning', 'Invalid answer selected!'];
                echo "Invalid answer selected!";
                exit();
            }
        } elseif (isset($_POST['answers'])) {
            // Assuming multiple-choice questions
            $answers = $_POST['answers'];
            // Ensure at least one valid AnswerID is provided
            if (!empty($answers)) {
                $values = [];
                foreach ($answers as $answerID) {
                    // Validate each answer ID
                    if ($answerID > 0) {
                        // Check if the selected answer is correct
                        $isCorrect = checkAnswerCorrectness($conn, $answerID);

                        $values[] = "($userID, $testID, $questionID, $answerID, $isCorrect)";
                    }
                }
                if (!empty($values)) {
                    $placeholders = implode(',', $values);
                    $insertResponseQuery = "INSERT INTO StudentResponses (StudentID, TestID, QuestionID, AnswerID, IsCorrect) 
                           VALUES $placeholders";

                    $insertResponseResult = mysqli_query($conn, $insertResponseQuery);

                    if (!$insertResponseResult) {
                        $_SESSION['message'] = ['error', 'Error inserting answer: ' . mysqli_error($conn)];
                        exit();
                    }

                    $questionType = 'multiple choice';
                } else {
                    $_SESSION['message'] = ['warning', 'No answer selected!'];
                    echo "No answer selected!";
                    exit();
                }
            } else {
                $_SESSION['message'] = ['warning', 'No answer selected!'];
                echo "No answer selected!";
                exit();
            }
        } else {
            // Handle the case where neither 'answer' nor 'answers' is set
            $_SESSION['message'] = ['warning', 'No answer selected!'];
            echo "No answer selected!";
            exit();
        }

        // The $insertResponseResult query should be placed outside the loop
        if ($insertResponseResult) {
            $responseCount = mysqli_affected_rows($conn);
            $_SESSION['message'] = ['success', "Answer saved successfully! Responses saved: $responseCount (Question Type: $questionType)"];
        } else {
            $_SESSION['message'] = ['error', 'Error saving answer: ' . mysqli_error($conn)];
        }
    } else {
        $_SESSION['message'] = ['warning', 'No answer selected!'];
    }
} else {
    // Redirect to the exams_start.php page if accessed directly without a form submission
    $_SESSION['message'] = ['error', 'Access Denied'];
}

header("Location: exams_start.php?test_id={$_GET['test_id']}&question_id={$_GET['question_id']}");
exit();

// Function to check if the selected answer is correct
function checkAnswerCorrectness($conn, $answerID)
{
    $questionID = $_GET['question_id'];
    $correctAnswerQuery = "SELECT IsCorrect FROM Answers WHERE QuestionID = $questionID AND AnswerID = $answerID";
    $correctAnswerResult = mysqli_query($conn, $correctAnswerQuery);

    if ($correctAnswerResult) {
        $correctAnswerData = mysqli_fetch_assoc($correctAnswerResult);
        return $correctAnswerData['IsCorrect'];
    }

    return 0; // Default to incorrect if there is an error
}
