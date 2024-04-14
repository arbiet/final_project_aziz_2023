<?php
// Initialize the session
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
$errors = array();
$assignmentData = array();
$submissionsData = array();

// Retrieve assignment data
if (isset($_GET['id'])) {
    $assignmentID = $_GET['id'];
    // Preserve the class filter value when redirecting back to this page
    $classFilter = isset($_GET['class']) ? $_GET['class'] : 'all';
    $query = "SELECT a.AssignmentID, a.Title, a.Description, a.DueDate, a.PriorityLevel, a.Status, 
                      s.SubjectID, s.SubjectName, m.TitleMaterial
            FROM Assignments a
            LEFT JOIN Subjects s ON a.SubjectID = s.SubjectID
            LEFT JOIN Materials m ON a.MaterialID = m.MaterialID
            WHERE a.AssignmentID = $assignmentID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $assignmentData = $result->fetch_assoc();
        $subjectID = $assignmentData['SubjectID'];  // Store the SubjectID for later use

        // Retrieve submissions associated with the assignment, subject, and class (if provided)
        $submissionsQuery = "
            SELECT 
                s.SubmissionID, 
                s.StudentID, 
                s.SubmissionText, 
                s.SubmissionFile, 
                s.SubmissionDate, 
                s.TeacherFeedback, 
                s.Grade, 
                s.IsLateSubmission,
                u.FullName as StudentName,
                c.ClassName
            FROM AssignmentSubmissions s
            LEFT JOIN Students st ON s.StudentID = st.StudentID
            LEFT JOIN Users u ON st.UserID = u.UserID
            LEFT JOIN Classes c ON st.ClassID = c.ClassID
            WHERE s.AssignmentID = $assignmentID
            AND st.ClassID IN (
                SELECT ClassID FROM ClassSubjects WHERE SubjectID = $subjectID
            )
        " . ($classFilter !== 'all' ? "AND st.ClassID = '$classFilter'" : '');

        $submissionsResult = $conn->query($submissionsQuery);

        if ($submissionsResult->num_rows > 0) {
            while ($submission = $submissionsResult->fetch_assoc()) {
                $submissionsData[] = $submission;
            }
        }
    } else {
        $errors[] = "Assignment not found.";
    }
}

?>

<?php include_once('../components/header2.php'); ?>
<?php include('../components/sidebar2.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-200 min-h-screen transition-all main">
    <?php include('../components/navbar2.php'); ?>
    <!-- Content -->
      <div class="p-4">
        <!-- Main Content -->
        <div class="flex items-start justify-start p-6 shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Assignment Details</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="../manage_assignments/manage_assignments_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Navigation -->
                    <div class="flex flex-row justify-between items-center w-full mb-2 pb-2">
                        <div>
                            <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                            <p class="text-gray-600 text-sm">Assignment information.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <!-- Assignment Details -->
                    <?php if (!empty($assignmentData)) : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-gray-800">Assignment Information</h3>
                            <p><strong>Title:</strong> <?php echo $assignmentData['Title']; ?></p>
                            <p><strong>Description:</strong> <?php echo $assignmentData['Description']; ?></p>
                            <p><strong>Due Date:</strong> <?php echo $assignmentData['DueDate']; ?></p>
                            <p><strong>Priority Level:</strong> <?php echo $assignmentData['PriorityLevel']; ?></p>
                            <p><strong>Status:</strong> <?php echo $assignmentData['Status']; ?></p>
                            <p><strong>Subject:</strong> <?php echo $assignmentData['SubjectName']; ?></p>
                            <p><strong>Material:</strong> <?php echo $assignmentData['TitleMaterial']; ?></p>
                        </div>
                        <!-- Submissions Section -->
                        <div class="mt-4 bg-white shadow-lg p-4 rounded-md">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">Submissions for this Assignment</h3>
                                    <!-- Filter by Class Button and Form -->
                                    <div class="flex items-center space-x-2 mb-4">
                                        <span class="text-gray-600">Filter by Class:</span>
                                        <form method="get" action="" id="classFilterForm">
                                            <select name="class" onchange="submitForm()">
                                                <option value="all">All Classes</option>
                                                <!-- Add options dynamically based on available classes related to the subject -->
                                                <?php
                                                // Retrieve and display available classes related to the subject
                                                $classesQuery = "SELECT c.* FROM Classes c
                                                                JOIN ClassSubjects cs ON c.ClassID = cs.ClassID
                                                                WHERE cs.SubjectID = $subjectID";
                                                $classesResult = $conn->query($classesQuery);
                                                while ($class = $classesResult->fetch_assoc()) {
                                                    $selected = $classFilter == $class['ClassID'] ? 'selected' : '';
                                                    echo "<option value='{$class['ClassID']}' $selected>{$class['ClassName']}</option>";
                                                }
                                                ?>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <button onclick="printReport()" class="bg-gray-500 text-white px-4 py-2 rounded">
                                    <i class="fas fa-print"></i> Print Report
                                </button>
                            </div>
                        <?php if (!empty($submissionsData)) : ?>
                            <div class="grid grid-cols-1 gap-4">
                                <?php foreach ($submissionsData as $submission) : ?>
                                    <div class="border rounded-md p-4 mb-4 <?php echo empty($submission['Grade']) ? 'bg-yellow-100' : 'bg-white'; ?>">
                                        <div class="flex items-center mb-2">
                                            <span class="font-semibold mr-2">Submission ID:</span>
                                            <span><?php echo $submission['SubmissionID']; ?></span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <span class="font-semibold mr-2">Student Name:</span>
                                            <span><?php echo $submission['StudentName']; ?></span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <span class="font-semibold mr-2">Class:</span>
                                            <span><?php echo $submission['ClassName']; ?></span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <span class="font-semibold mr-2">Submission Text:</span>
                                            <span><?php echo $submission['SubmissionText']; ?></span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <span class="font-semibold mr-2">Submission File:</span>
                                            <span><?php echo $submission['SubmissionFile']; ?></span>
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <span class="font-semibold mr-2">Grade:</span>
                                            <span id="grade_<?php echo $submission['SubmissionID']; ?>"><?php echo $submission['Grade']; ?></span>
                                        </div>
                                        <div class="flex items-center">
                                            <button class="bg-blue-500 text-white px-2 py-1 rounded mr-2" onclick="provideFeedback(<?php echo $submission['SubmissionID']; ?>)">
                                                <i class="fas fa-comment"></i> Feedback
                                            </button>
                                            <?php if ($submission['Grade'] !== NULL) : ?>
                                                <button class="bg-red-500 text-white px-2 py-1 rounded mr-2" onclick="provideDelete(<?php echo $submission['SubmissionID']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete Grade
                                                </button>
                                            <?php endif; ?>
                                            <button class="bg-blue-500 text-white px-2 py-1 rounded mr-2" onclick="viewSubmission(<?php echo $submission['SubmissionID']; ?>, '<?php echo $submission['SubmissionFile']; ?>')">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="bg-white shadow-lg p-4 rounded-md mt-4">
                                <p>No submissions available for this assignment.</p>
                            </div>
                        <?php endif; ?>


                        </div>
                    <?php else : ?>
                        <div class="bg-white shadow-lg p-4 rounded-md">
                            <p>No assignment data available.</p>
                        </div>
                    <?php endif; ?>
                    <!-- End Assignment Details -->
                </div>
                <!-- End Content -->
            </div>
    </div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function printReport() {
        // Dapatkan nilai filter kelas yang dipilih
        var selectedClass = '<?php echo isset($_GET["class"]) ? $_GET["class"] : ""; ?>';
        // Kirim permintaan cetak ke skrip PHP yang akan membuat PDF dengan nilai filter kelas yang dipilih
        window.open('print_report.php?id=<?php echo $assignmentID; ?>&class=' + selectedClass, '_blank');
    }
</script>




<script>
    function viewSubmission(submissionID, submissionFile) {
        // Cek apakah file adalah PDF atau bukan
        var extension = submissionFile.split('.').pop().toLowerCase();
        if (extension === 'pdf') {
            // Jika file PDF, buka dengan SweetAlert
            Swal.fire({
                title: 'View Submission',
                html: 'This submission is in PDF format. Do you want to view it?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, View',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tambahkan logika untuk menampilkan file PDF di sini
                    // Misalnya, jika ingin membuka file di jendela baru:
                    window.open(submissionFile, '_blank');
                }
            });
        } else {
            // Jika bukan file PDF, langsung unduh dengan SweetAlert
            Swal.fire({
                title: 'Download Submission',
                html: 'This submission is not in PDF format. Do you want to download it?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Download',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tambahkan logika untuk mengunduh file di sini
                    // Misalnya, jika ingin mengarahkan pengguna ke file untuk diunduh:
                    window.location.href = submissionFile;
                }
            });
        }
    }

    function provideDelete(submissionID) {
    Swal.fire({
        title: 'Are you sure?',
        text: 'You won\'t be able to revert this!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform an AJAX request to delete the grade
            $.ajax({
                type: 'GET',
                url: `../manage_assignments/manage_assignments_delete_grade.php?submission_id=${submissionID}`,
                dataType: 'json', // Specify JSON dataType
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Deletion Successful',
                            text: 'The grade has been deleted successfully.',
                            onClose: () => {
                                // Reload the page after successful deletion
                                location.reload();
                            }
                        });
                    } else {
                        // Show error message from server
                        Swal.fire({
                            icon: 'error',
                            title: 'Deletion Failed',
                            text: response.error || 'There was an error deleting the grade.',
                        });
                    }
                },
                error: function(xhr, status, error) {
                    location.reload();
                }
            });
        }
    });
}

    function provideFeedback(submissionID) {
        const gradeElement = document.getElementById(`grade_${submissionID}`);
        const currentGrade = gradeElement ? gradeElement.innerText : '';

        Swal.fire({
            title: 'Provide Feedback',
            html: `
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="updatedGrade">Grade:</label>
                    <input type="number" id="updatedGrade" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" value="${currentGrade}" min="0" max="100" placeholder="Enter grade (0-100)" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="feedback">Feedback:</label>
                    <textarea id="feedback" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your feedback here..."></textarea>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Submit',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const updatedGrade = document.getElementById('updatedGrade').value;
                const feedback = Swal.getPopup().querySelector('#feedback').value;
                return { updatedGrade, feedback };
            },
            inputValidator: (value) => {
                const updatedGrade = document.getElementById('updatedGrade').value;
                if (!updatedGrade) {
                    return 'Grade cannot be empty!';
                }
                if (isNaN(updatedGrade) || updatedGrade < 0 || updatedGrade > 100) {
                    return 'Grade must be a number between 0 and 100!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const { updatedGrade, feedback } = result.value;

                // Perform an AJAX request to update the database with the grade and feedback
                $.ajax({
                    type: 'POST',
                    url: 'update_submmision.php',
                    data: {
                        submissionID: submissionID,
                        grade: updatedGrade,
                        feedback: feedback
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Feedback Submitted',
                                text: 'Grade and feedback submitted successfully!',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // You may want to update the UI here to reflect the changes, e.g., updating the feedback column
                            if (gradeElement) {
                                gradeElement.innerText = updatedGrade;
                            }

                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Submission Failed',
                                text: 'There was an error submitting your feedback.',
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Failed',
                            text: 'There was an error submitting your feedback.',
                        });
                    }
                });
            }
        });
    }

    function addSubmission() {
        // Redirect to the page for adding a submission with the assignment ID
        window.location.href = `../manage_submissions/manage_submissions_create.php?assignment_id=<?php echo $assignmentID; ?>`;
    }

    function editSubmission(submissionID) {
        // Redirect to the page for editing a submission with the submission ID
        window.location.href = `../manage_submissions/manage_submissions_update.php?assignment_id=<?php echo $assignmentID; ?>&submission_id=${submissionID}`;
    }

    function confirmDeleteSubmission(submissionID) {
        // Display a SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks "Yes," redirect to the page for deleting a submission with the submission ID
                window.location.href = `../manage_submissions/manage_submissions_delete.php?assignment_id=<?php echo $assignmentID; ?>&submission_id=${submissionID}`;
            }
        });
    }
    
</script>
<script>
    function submitForm() {
        // Get the selected class value
        var selectedClass = document.getElementsByName('class')[0].value;

        // Get the current URL
        var currentUrl = window.location.href;

        // Check if the URL already contains "class" parameter
        var hasClassParam = currentUrl.includes('class=');

        // If the URL already contains "class" parameter, replace its value
        if (hasClassParam) {
            var regex = new RegExp('class=\\d+');
            currentUrl = currentUrl.replace(regex, 'class=' + selectedClass);
        } else {
            // If the URL does not contain "class" parameter, add it
            currentUrl += (currentUrl.includes('?') ? '&' : '?') + 'class=' + selectedClass;
        }

        // Redirect to the updated URL
        window.location.href = currentUrl;
    }
</script>
</main>
<?php include('../components/footer2.php'); ?>