<?php
require_once('../../database/connection.php');
session_start();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the required fields are set in the POST data
    if (isset($_POST['subject_id'], $_POST['assignment_id'], $_POST['submissionText'])) {
        $subjectID = $_POST['subject_id'];
        $assignmentID = $_POST['assignment_id'];
        $submissionText = mysqli_real_escape_string($conn, $_POST['submissionText']);

        // Check if a file was uploaded
        $submissionFile = null;
        if (isset($_FILES['submissionFile'])) {
            $tempFile = $_FILES['submissionFile']['tmp_name'];

            // Generate a unique name for the file
            $uniqueFileName = generateUniqueFileName($_FILES['submissionFile']['name']);
            $fileDestination = "../static/image/submission/" . $uniqueFileName;

            // Move the uploaded file to the destination directory
            move_uploaded_file($tempFile, $fileDestination);
            $submissionFile = $fileDestination;
        }

        // Insert submission data into the database
        $insertQuery = "INSERT INTO AssignmentSubmissions (StudentID, AssignmentID, SubmissionText, SubmissionFile, SubmissionDate) 
                        VALUES ({$_SESSION['StudentID']}, $assignmentID, '$submissionText', '$submissionFile', NOW())";

        if (mysqli_query($conn, $insertQuery)) {
            // Successful submission
            $response = [
                'success' => true
            ];
        } else {
            // Failed submission
            $response = [
                'success' => false
            ];
        }
    } else {
        // Invalid or missing data
        $response = [
            'success' => false
        ];
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

// Function to generate a unique file name
function generateUniqueFileName($originalFileName) {
    $timestamp = time();
    $randomString = bin2hex(random_bytes(5)); // Adjust the length as needed
    $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
    $uniqueFileName = "{$timestamp}_{$randomString}.{$fileExtension}";

    return $uniqueFileName;
}
?>
