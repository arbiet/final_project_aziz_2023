<?php
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$assignmentID = '';
$submissionsData = array();

// Retrieve assignment ID and class filter from the URL parameters
if (isset($_GET['id'])) {
    $assignmentID = $_GET['id'];
    // Filter by class if provided
    $classFilter = isset($_GET['class']) ? $_GET['class'] : '';

    // Construct the SQL query to get students based on class
    $classQuery = "SELECT * FROM Students";
    if (!empty($classFilter)) {
        $classQuery .= " WHERE ClassID = $classFilter";
    }

    // Execute the class query
    $classResult = $conn->query($classQuery);

    if ($classResult && $classResult->num_rows > 0) {
        // Fetch students data
        while ($student = $classResult->fetch_assoc()) {
            // Fetch submissions data for each student
            $studentID = $student['StudentID'];
            $submissionQuery = "SELECT * FROM AssignmentSubmissions 
                                WHERE StudentID = $studentID AND AssignmentID = $assignmentID";
            $submissionResult = $conn->query($submissionQuery);
            
            // If submission exists, add it to submissions data
            if ($submissionResult && $submissionResult->num_rows > 0) {
                $submissionData = $submissionResult->fetch_assoc();
                $submissionsData[] = $submissionData;
            } else {
                // If no submission exists, create an entry with default values
                $defaultSubmission = array(
                    'StudentID' => $studentID,
                    'SubmissionID' => 'N/A',
                    'Grade' => 0 // Set grade to 0 if no submission
                );
                $submissionsData[] = $defaultSubmission;
            }
        }
        
        // Check if there are submissions
        if (count($submissionsData) > 0) {
            // Include TCPDF library
            require_once('../../module/tcpdf/tcpdf.php');

            // Initialize PDF object
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('ESAY'); // Set to the creator's name
            $pdf->SetAuthor('Your Author'); // Set to the author's name
            $pdf->SetTitle('Assignment Report - Assignment ID: ' . $assignmentID); // Set to the assignment's name

            // Add a page
            $pdf->AddPage();

            // Set some content to display
            $html = '<h1>Assignment Report</h1>';
            $html .= '<table border="1">';
            $html .= '<tr><th>Student Name</th><th>Submission ID</th><th>Grade</th></tr>';
            // Loop through submissions data to populate the table
            foreach ($submissionsData as $submission) {
                $studentID = $submission['StudentID'];
                // Fetch student name based on UserID
                $studentQuery = "SELECT FullName FROM Users WHERE UserID = $studentID";
                $studentResult = $conn->query($studentQuery);
                if ($studentResult && $studentResult->num_rows > 0) {
                    $studentData = $studentResult->fetch_assoc();
                    $studentName = $studentData['FullName'];
                } else {
                    $studentName = 'N/A'; // Set student name to N/A if not found
                }
                
                $html .= '<tr>';
                $html .= '<td>' . $studentName . '</td>';
                $html .= '<td>' . $submission['SubmissionID'] . '</td>';
                $html .= '<td>' . $submission['Grade'] . '</td>';
                $html .= '</tr>';
            }
            $html .= '</table>';

            // Write the HTML content to the PDF
            $pdf->writeHTML($html, true, false, true, false, '');

            // Close and output PDF
            $pdf->Output('assignment_report.pdf', 'I');
        } else {
            // Show Sweet Alert if there are no submissions
            include_once('../components/header2.php');
            echo "<script>
                    Swal.fire({
                      icon: 'error',
                      title: 'Oops...',
                      text: 'No submissions found for the assignment!',
                      onClose: () => {
                        window.close(); // Close the window
                      }
                    });
                  </script>";
            exit; // Stop further execution
            include_once('../components/footer2.php');
        }
    } else {
        include_once('../components/header2.php');
        // Show Sweet Alert if there are no students found for the class
        echo "<script>
                Swal.fire({
                  icon: 'error',
                  title: 'Oops...',
                  text: 'No students found for the class!',
                  onClose: () => {
                    window.close(); // Close the window
                  }
                });
              </script>";
        exit; // Stop further execution
        include_once('../components/footer2.php');
    }
} else {
    include_once('../components/header2.php');
    // Show Sweet Alert if assignment ID is missing
    echo "<script>
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Assignment ID is missing!',
              onClose: () => {
                window.close(); // Close the window
              }
            });
          </script>";
    exit; // Stop further execution
    include_once('../components/footer2.php');
}


?>
