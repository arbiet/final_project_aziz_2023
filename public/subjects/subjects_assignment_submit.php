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

        // Check if the submission is late based on due date
        $isLateSubmission = isLateSubmission($assignmentID);

        // Insert submission data into the database
        $insertQuery = "INSERT INTO AssignmentSubmissions (StudentID, AssignmentID, SubmissionText, SubmissionFile, SubmissionDate, IsLateSubmission) 
                        VALUES ({$_SESSION['StudentID']}, $assignmentID, '$submissionText', '$submissionFile', NOW(), $isLateSubmission)";

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
    exit(header("Location: subjects_assignment.php?subject_id=".$_POST['subject_id']."&assignment_id=".$_POST['assignment_id']));
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

// Function to check if the submission is late
function isLateSubmission($assignmentID) {
    global $conn;

    // Fetch the due date of the assignment
    $dueDateQuery = "SELECT DueDate FROM Assignments WHERE AssignmentID = $assignmentID";
    $dueDateResult = mysqli_query($conn, $dueDateQuery);

    if ($dueDateResult && mysqli_num_rows($dueDateResult) > 0) {
        $dueDate = mysqli_fetch_assoc($dueDateResult)['DueDate'];
        $currentDate = date('Y-m-d H:i:s');

        // Compare the current date with the due date
        return ($currentDate > $dueDate) ? 1 : 0;
    }

    // Default to not late if due date is not found
    return 0;
}
?>
