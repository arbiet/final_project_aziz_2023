<?php
session_start();
// Include the connection file
require_once('../../database/connection.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
// Initialize variables
$assignmentID = '';
$submissionsData = array();

// Retrieve assignment ID and class filter from the URL parameters
if (isset($_GET['id'])) {
    $assignmentID = $_GET['id'];
    // Filter by class if provided
    $classFilter = isset($_GET['class']) ? $_GET['class'] : '';

    // Construct the SQL query to get all students in the class with their class information
    $classQuery = "SELECT Students.StudentID, Users.FullName, Classes.ClassCode
                   FROM Students
                   INNER JOIN Users ON Students.UserID = Users.UserID
                   INNER JOIN Classes ON Students.ClassID = Classes.ClassID";
    if (!empty($classFilter)) {
        $classQuery .= " WHERE Students.ClassID = $classFilter";
    }

    // Execute the class query
    $classResult = $conn->query($classQuery);

    if ($classResult && $classResult->num_rows > 0) {
        // Fetch students data
        while ($student = $classResult->fetch_assoc()) {
            // Fetch submission data for each student
            $studentID = $student['StudentID'];
            $submissionQuery = "SELECT SubmissionID, Grade, SubmissionDate
                                FROM AssignmentSubmissions 
                                WHERE StudentID = $studentID AND AssignmentID = $assignmentID";
            $submissionResult = $conn->query($submissionQuery);
            
            // If submission exists, add it to submissions data
            if ($submissionResult && $submissionResult->num_rows > 0) {
                $submissionData = $submissionResult->fetch_assoc();
                $submissionData['ClassCode'] = $student['ClassCode']; // Add student class to submission data
                $submissionData['FullName'] = $student['FullName']; // Add student full name to submission data
                $submissionsData[] = $submissionData;
            } else {
                // If no submission exists, create an entry with default values
                $defaultSubmission = array(
                    'ClassCode' => $student['ClassCode'],
                    'FullName' => $student['FullName'],
                    'SubmissionID' => 'N/A',
                    'Grade' => 0,
                    'SubmissionDate' => 'N/A' // Set submission date to N/A if no submission
                );
                $submissionsData[] = $defaultSubmission;
            }
        }
        
        // Check if there are submissions
if (count($submissionsData) > 0) {
    require_once('../../module/tcpdf/tcpdf.php');
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->SetCreator('ESAY');
    $pdf->SetAuthor('Your Author');
    $pdf->SetTitle('Assignment Report - Assignment ID: ' . $assignmentID);

    // Initialize an array to store distinct class codes
    $classCodes = [];

    // Extract distinct class codes from submissions data
    foreach ($submissionsData as $submission) {
        $classCodes[] = $submission['ClassCode'];
    }
    $classCodes = array_unique($classCodes);

    // Loop through distinct class codes
    foreach ($classCodes as $classCode) {
        $pdf->AddPage();

        // Set content for each page
        $html = '<h1 style="font-size: 24px; margin-bottom: 16px;">Assignment Report</h1>';
        // Retrieve assignment detail based on AssignmentID
            $assignmentDetailQuery = "SELECT * FROM Assignments WHERE AssignmentID = $assignmentID";
            $assignmentDetailResult = $conn->query($assignmentDetailQuery);

            if ($assignmentDetailResult && $assignmentDetailResult->num_rows > 0) {
                $assignmentDetail = $assignmentDetailResult->fetch_assoc();

                // Include assignment detail in the PDF content
                $html .= '<table style="border-collapse: collapse; border: 1px solid #ddd; margin-top: 10px;">';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Report Type:</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">Submission Status Report</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Printed at:</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . date('Y-m-d H:i:s') . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Title:</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $assignmentDetail['Title'] . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Description:</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $assignmentDetail['Description'] . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Class :</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' .  $classCode . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Due Date:</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $assignmentDetail['DueDate'] . '</td>';
                $html .= '</tr>';
                $html .= '<tr>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;"><strong>Assigned Date:</strong></td>';
                $html .= '<td style="border: 1px solid #ddd; padding: 8px;">' . $assignmentDetail['AssignedDate'] . '</td>';
                $html .= '</tr>';
                $html .= '</table>';
            } else {
                $html .= '<p>No assignment detail found.</p>';
            }
        // Filter submissions data for the current class code
        $submissionsOfClass = array_filter($submissionsData, function($submission) use ($classCode) {
            return $submission['ClassCode'] === $classCode;
        });
        $html .= '<h2 style="font-size: 16px; margin-bottom: 16px;">Student Grade</h2>';
        // Populate table with submissions data for the current class
        $html .= '<table style="border-collapse: collapse; border: 1px solid #ddd;">';
        $html .= '<thead style="background-color: #f2f2f2;"><tr>';
        $html .= '<th style="border: 1px solid #ddd; padding: 8px; width: 5%;">#</th>'; // Set width manually
        $html .= '<th style="border: 1px solid #ddd; padding: 8px; width: 43%;">Student Name</th>'; // Set width manually
        $html .= '<th style="border: 1px solid #ddd; padding: 8px; width: 12%;">Class</th>'; // Set width manually
        $html .= '<th style="border: 1px solid #ddd; padding: 8px; width: 15%;">Grade</th>'; // Set width manually
        $html .= '<th style="border: 1px solid #ddd; padding: 8px; width: 25%;">Submission Date</th>'; // Set width manually
        $html .= '</tr></thead><tbody>';
        // Loop through submissions data to populate the table
        $no = 1;
        foreach ($submissionsOfClass as $submission) {
          $html .= '<tr>';
          $html .= '<td style="border: 1px solid #ddd; padding: 8px; width: 5%;">' . $no++ . '</td>';
          $html .= '<td style="border: 1px solid #ddd; padding: 8px; width: 43%;">' . $submission['FullName'] . '</td>';
          $html .= '<td style="border: 1px solid #ddd; padding: 8px; width: 12%;">' . $submission['ClassCode'] . '</td>';
          $html .= '<td style="border: 1px solid #ddd; padding: 8px; width: 15%;">' . $submission['Grade'] . '</td>';
          $html .= '<td style="border: 1px solid #ddd; padding: 8px; width: 25%;">' . $submission['SubmissionDate'] . '</td>';
          $html .= '</tr>';
        }
        $html .= '</tbody></table>';

        // Write HTML content to PDF
        $pdf->writeHTML($html, true, false, true, false, '');
    }

    // Output PDF
    $pdf->Output('assignment_report.pdf', 'I');
} else {
            // Show Sweet Alert if there are no submissions
            include_once('../components/header2.php');
            echo "<script>
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'No submissions found for the assignment!',
                      confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'manage_assignments_detail.php?id=$assignmentID&class=$classFilter';
                        }
                    });
                  </script>";
            include_once('../components/footer2.php');
        }
    } else {
        // Show Sweet Alert if there are no students found for the class
        include_once('../components/header2.php');
        echo "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'No students found for the class!',
                  confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manage_assignments_detail.php?id=$assignmentID&class=$classFilter';
                    }
                });
              </script>";
        include_once('../components/footer2.php');
    }
} else {
    // Show Sweet Alert if assignment ID is missing
    include_once('../components/header2.php');
    echo "<script>
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Assignment ID is missing!',
              confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'manage_assignments_detail.php';
                }
            });
          </script>";
    include_once('../components/footer2.php');
}
?>
