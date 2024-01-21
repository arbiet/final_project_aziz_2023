<?php
require_once('../../database/connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['assignment_id'], $_GET['submission_id'])) {
    $assignmentID = $_GET['assignment_id'];
    $submissionID = $_GET['submission_id'];

    // Fetch submission data to get the file path
    $submissionQuery = "SELECT SubmissionFile FROM AssignmentSubmissions WHERE SubmissionID = $submissionID";
    $submissionResult = $conn->query($submissionQuery);

    if ($submissionResult->num_rows > 0) {
        $submissionData = $submissionResult->fetch_assoc();

        // Delete the submission file if it exists
        if (!empty($submissionData['SubmissionFile']) && file_exists($submissionData['SubmissionFile'])) {
            unlink($submissionData['SubmissionFile']);
        }

        // Perform update to set TeacherFeedback and Grade to NULL
        $updateQuery = "UPDATE AssignmentSubmissions SET TeacherFeedback = NULL, Grade = NULL WHERE SubmissionID = $submissionID";

        if (mysqli_query($conn, $updateQuery)) {
            // Respond with success message as JSON
            echo json_encode(['success' => true]);
            exit;
        } else {
            // Respond with error message as JSON
            echo json_encode(['success' => false, 'error' => 'Error updating submission data.']);
        }
    } else {
        // Submission data not found
        echo json_encode(['success' => false, 'error' => 'Submission data not found.']);
    }
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Bad Request']);
    exit;
}
?>
