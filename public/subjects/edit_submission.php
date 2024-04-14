<?php
session_start();
require_once('../../database/connection.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that the required fields are set in the POST data
    if (isset($_POST['subject_id'], $_POST['assignment_id'], $_POST['editSubmissionText'])) {
        $subjectID = $_POST['subject_id'];
        $assignmentID = $_POST['assignment_id'];
        $editSubmissionText = mysqli_real_escape_string($conn, $_POST['editSubmissionText']);

        // Check if a file was uploaded
        $editSubmissionFile = null;
        if (isset($_FILES['editSubmissionFile'])) {
            $tempFile = $_FILES['editSubmissionFile']['tmp_name'];

            // Generate a unique name for the file
            $uniqueFileName = generateUniqueFileName($_FILES['editSubmissionFile']['name']);
            $fileDestination = "../static/image/submission/" . $uniqueFileName;

            // Move the uploaded file to the destination directory
            move_uploaded_file($tempFile, $fileDestination);
            $editSubmissionFile = $fileDestination;
        }

        // Update the existing submission data in the database
        $updateQuery = "UPDATE AssignmentSubmissions 
                        SET SubmissionText = '$editSubmissionText', SubmissionFile = '$editSubmissionFile', SubmissionDate = NOW()
                        WHERE AssignmentID = $assignmentID AND StudentID = {$_SESSION['StudentID']}";

        if (mysqli_query($conn, $updateQuery)) {
            // Successful update
            $response = [
                'success' => true
            ];
        } else {
            // Failed update
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
