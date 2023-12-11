<?php
require_once('../../database/connection.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the required fields are set in the POST data
    if (isset($_POST['submissionID'], $_POST['grade'], $_POST['feedback'])) {
        $submissionID = $_POST['submissionID'];
        $grade = mysqli_real_escape_string($conn, $_POST['grade']);
        $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);

        // Update submission data in the database
        $updateQuery = "UPDATE AssignmentSubmissions 
                        SET Grade = '$grade', TeacherFeedback = '$feedback' 
                        WHERE SubmissionID = $submissionID";

        if (mysqli_query($conn, $updateQuery)) {
            // Successful update
            $response = ['success' => true];
        } else {
            // Failed update
            $response = ['success' => false];
        }
    } else {
        // Invalid or missing data
        $response = ['success' => false];
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
} else {
    // Invalid request method
    http_response_code(405);
    exit('Method Not Allowed');
}
?>
