<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once('../../database/connection.php');
include_once('../components/header.php');

// Check if the user is logged in
if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

// Check if the submission ID is provided in the query parameter
if (!isset($_GET['submission_id']) || !is_numeric($_GET['submission_id'])) {
    // Respond with JSON indicating error
    echo json_encode(array("success" => false, "error" => "Invalid submission ID provided."));
    exit();
}

$submissionID = $_GET['submission_id'];

// Initialize success and error messages
$success_message = '';
$error_message = '';

// Update the grade of the submission to NULL
$updateGradeQuery = "UPDATE AssignmentSubmissions SET Grade = NULL WHERE SubmissionID = $submissionID";

if ($conn->query($updateGradeQuery)) {
    $success_message = "Grade has been deleted successfully!";
} else {
    $error_message = "Failed to delete the grade: " . $conn->error;
}

// Display success or error messages using JSON
if (!empty($success_message)) {
    // Respond with JSON indicating success
    echo json_encode(array("success" => true, "message" => $success_message));
} elseif (!empty($error_message)) {
    // Respond with JSON indicating error
    echo json_encode(array("success" => false, "error" => $error_message));
}

?>
