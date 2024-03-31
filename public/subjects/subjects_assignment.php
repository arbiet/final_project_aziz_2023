<?php
require_once('../../database/connection.php');
include('../components/header2.php');
session_start();

if (isset($_SESSION['UserID'])) {
    // Check if 'subject_id' and 'assignment_id' are set in the URL
    if (isset($_GET['subject_id']) && isset($_GET['assignment_id'])) {
        $subjectID = $_GET['subject_id'];
        $assignmentID = $_GET['assignment_id'];

        // Fetch assignment information from the database
        $assignmentQuery = "SELECT a.*, m.TitleMaterial
                            FROM Assignments a
                            LEFT JOIN Materials m ON a.MaterialID = m.MaterialID
                            WHERE a.AssignmentID = $assignmentID";

        $assignmentResult = mysqli_query($conn, $assignmentQuery);

        if ($assignmentResult && mysqli_num_rows($assignmentResult) > 0) {
            $assignmentData = mysqli_fetch_assoc($assignmentResult);
        }

        // Fetch materials related to the subject
        $materialQuery = "SELECT * FROM Materials WHERE SubjectID = $subjectID ORDER BY Sequence";
        $materialResult = mysqli_query($conn, $materialQuery);
        $materials = [];

        if ($materialResult && mysqli_num_rows($materialResult) > 0) {
            while ($row = mysqli_fetch_assoc($materialResult)) {
                $materials[] = $row;
            }
        }

        // Fetch attachments related to the assignment
        $attachmentQuery = "SELECT * FROM AssignmentAttachments WHERE AssignmentID = $assignmentID";
        $attachmentResult = mysqli_query($conn, $attachmentQuery);
        $attachments = [];

        if ($attachmentResult && mysqli_num_rows($attachmentResult) > 0) {
            while ($row = mysqli_fetch_assoc($attachmentResult)) {
                $attachments[] = $row;
            }
        }
        // print_r($attachments);

        // Fetch student submission information
        $submissionQuery = "SELECT * FROM AssignmentSubmissions 
                           WHERE AssignmentID = $assignmentID AND StudentID = {$_SESSION['StudentID']}";
        $submissionResult = mysqli_query($conn, $submissionQuery);
        $submissionData = mysqli_fetch_assoc($submissionResult);
    }
}

?>
<?php include('../components/sidebar_students.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-teal-100 min-h-screen transition-all main">
    <?php include('../components/navbar4.php'); ?>
    <!-- Content -->
    <div class="p-6">
        <div class="flex items-start justify-start shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
            <!-- Main Content -->
            <!-- Main Content -->
            <main class="container mx-auto mt-4 p-4 bg-white">
                <h2 class="text-3xl font-semibold mb-4"><?php echo $assignmentData['Title']; ?></h2>
                <?php if (isset($_GET['assignment_id'])) : ?>
                    <div class="bg-blue-100 p-4 rounded-md mb-4">
                        <h3 class="text-blue-700 font-semibold">Assignment Information:</h3>
                        <table class="table-auto">
                            <tr>
                                <td class="py-1"><p class="text-gray-500">Assignment Title</p></td>
                                <td class="py-1"><p class="text-gray-500">:</p></td>
                                <td class="py-1"><p class=""><?php echo $assignmentData['Title']; ?></p></td>
                            </tr>
                            <tr>
                                <td class="py-1"><p class="text-gray-500">Description</p></td>
                                <td class="py-1"><p class="text-gray-500">:</p></td>
                                <td class="py-1"><p class=""><?php echo $assignmentData['Description']; ?></p></td>
                            </tr>
                            <tr>
                                <td class="py-1"><p class="text-gray-500">Due Date</p></td>
                                <td class="py-1"><p class="text-gray-500">:</p></td>
                                <td class="py-1"><p class=""><?php echo $assignmentData['DueDate']; ?></p></td>
                            </tr>
                            <tr>
                                <td class="py-1"><p class="text-gray-500">Assigned Date</p></td>
                                <td class="py-1"><p class="text-gray-500">:</p></td>
                                <td class="py-1"><p class=""><?php echo $assignmentData['AssignedDate']; ?></p></td>
                            </tr>
                            </table>

                        <!-- Add more details as needed -->
                    </div>

                    <!-- Display attachments and download links -->
                    <div class="bg-green-100 p-4 rounded-md mb-4">
                        <h3 class="text-green-700 font-semibold mb-4">Attachments:</h3>
                        <?php if (!empty($attachments)) : ?>
                            <ul>
                                <?php foreach ($attachments as $attachment) : ?>
                                    <li>
                                        <?php
                                        $fileExtension = pathinfo($attachment['AttachmentFile'], PATHINFO_EXTENSION);
                                        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                        $allowedPdfExtensions = ['pdf'];
                                        $allowedOfficeExtensions = ['xlsx', 'csv', 'docx', 'doc', 'xls', 'ppt', 'pptx'];
                                        $isImage = in_array($fileExtension, $allowedImageExtensions);
                                        $isPdf = in_array($fileExtension, $allowedPdfExtensions);
                                        $isOfficeDocument = in_array($fileExtension, $allowedOfficeExtensions);
                                        ?>

                                        <?php if ($isImage) : ?>
                                            <img src="<?php echo $attachment['AttachmentFile']; ?>" alt="Image">
                                        <?php elseif ($isPdf) : ?>
                                            <!-- Display PDF viewer using <object> tag -->
                                            <object data="<?php echo $attachment['AttachmentFile']; ?>" type="application/pdf" width="100%" height="600">
                                                <p>Your browser does not support embedded PDF files. You can download the file <a href="<?php echo $attachment['AttachmentFile']; ?>">here</a>.</p>
                                            </object>
                                        <?php elseif ($isOfficeDocument) : ?>
                                            <!-- Display download link for office documents -->
                                            <a href="<?php echo $attachment['AttachmentFile']; ?>" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700" download>
                                                Download <?php echo strtoupper($fileExtension); ?> File
                                            </a>
                                        <?php else : ?>
                                            <!-- Display a generic download link for other file types -->
                                            <a href="<?php echo $attachment['AttachmentFile']; ?>" class="mt-4 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700" download>
                                                Download File
                                            </a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>

                    <!-- Display student submission information -->
                    <?php if (!empty($submissionData)) : ?>
                    <!-- Display student submission information -->
                    <div class="mb-4">
                        <h3 class="text-green-700 font-semibold">Your Submission:</h3>

                        <?php if (!empty($submissionData['SubmissionFile'])) : ?>
                            <p>Submitted File:
                                <?php
                                $fileExtension = pathinfo($submissionData['SubmissionFile'], PATHINFO_EXTENSION);
                                $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                $allowedPdfExtensions = ['pdf'];
                                $isImage = in_array($fileExtension, $allowedImageExtensions);
                                $isPdf = in_array($fileExtension, $allowedPdfExtensions);
                                ?>
                                

                                <?php if ($isImage) : ?>
                                    <img src="<?php echo $submissionData['SubmissionFile']; ?>" alt="Image">
                                <?php elseif ($isPdf) : ?>
                                    <!-- Display PDF viewer using <object> tag -->
                                    <object data="<?php echo $submissionData['SubmissionFile']; ?>" type="application/pdf" width="100%" height="600">
                                        <p>Your browser does not support embedded PDF files. You can download the file <a href="<?php echo $submissionData['SubmissionFile']; ?>">here</a>.</p>
                                    </object>
                                <?php else : ?>
                                    <a href="<?php echo $submissionData['SubmissionFile']; ?>" download>
                                        <?php echo $submissionData['SubmissionFile']; ?>
                                    </a>
                                    <br>
                                    <a href="<?php echo $submissionData['SubmissionFile']; ?>" class="mt-4 bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-700" download>
                                        Download
                                    </a>
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <?php if (!isset($submissionData['TeacherFeedback'])) : ?>
                        <!-- Add more details as needed -->
                        <!-- Add an "Edit Submission" form -->
                        <form id="editSubmissionForm" action="../subjects/edit_submission.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="subject_id" value="<?php echo $subjectID; ?>">
                            <input type="hidden" name="assignment_id" value="<?php echo $assignmentID; ?>">

                            <!-- Edit Submission Text -->
                            <label for="editSubmissionText" class="block text-gray-700 text-sm font-bold mb-2">Edit Submission Text:</label>
                            <textarea name="editSubmissionText" id="editSubmissionText" rows="4" cols="50" class="w-full p-2 border border-gray-300 rounded-md"><?php echo $submissionData['SubmissionText']; ?></textarea>

                            <!-- Edit File Submission -->
                            <label for="editSubmissionFile" class="block text-gray-700 text-sm font-bold mb-2">Edit Submission File:</label>
                            <input type="file" name="editSubmissionFile" id="editSubmissionFile" class="w-full bg-white p-2 border border-gray-300 rounded-md">

                            <button type="button" onclick="confirmEditSubmission()" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Edit Submission</button>
                        </form>
                        <?php else : ?>
                            <!-- If assignment is evaluated, show teacher feedback, grade, and late submission information -->
                            <p>Teacher Feedback: <?php echo $submissionData['TeacherFeedback']; ?></p>
                            <p>Grade: <?php echo $submissionData['Grade']; ?></p>
                            <p>Is Late Submission: <?php echo $submissionData['IsLateSubmission'] ? 'Yes' : 'No'; ?></p>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                        <div class="bg-yellow-100 p-4 rounded-md">
                            <h3 class="text-yellow-700 font-semibold mb-4">Submit Assignment:</h3>
                            <!-- Add an ID to the form for easy selection in JavaScript -->
                            <form id="submitAssignmentForm" action="../subjects/subjects_assignment_submit.php" method="POST" enctype="multipart/form-data">

                                <input type="hidden" name="subject_id" value="<?php echo $subjectID; ?>">
                                <input type="hidden" name="assignment_id" value="<?php echo $assignmentID; ?>">

                                <!-- Text Submission -->
                                <label for="submissionText" class="block text-gray-700 text-sm font-bold mb-2">Submission Text:</label>
                                <textarea name="submissionText" id="submissionText" rows="4" cols="50" class="w-full p-2 border border-gray-300 rounded-md"></textarea>

                                <!-- File Submission -->
                                <label for="submissionFile" class="block  text-gray-700 text-sm font-bold mb-2">Submission File:</label>
                                <input type="file" name="submissionFile" id="submissionFile" class="w-full bg-white p-2 border border-gray-300 rounded-md">

                                <button type="submit" onclick="confirmSubmitAssignment()" class="bg-red-500 text-white mt-2 px-4 py-2 rounded-md hover:bg-red-700">Submit Assignment</button>
                            </form>
                        </div>
                <?php endif; ?>
                <?php else : ?>
                    <p class="text-red-500">Assignment information not available.</p>
                <?php endif; ?>
            </main>
        </div>
    </div>
</main>
<script>
    function confirmSubmitAssignment() {
    Swal.fire({
        title: 'Apakah Anda yakin ingin mengumpulkan tugas?',
        text: 'Setelah dikumpulkan, Anda tidak dapat mengedit atau menghapus tugas.',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Kumpulkan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Use FormData to serialize the form data
            const formData = new FormData(document.getElementById('submitAssignmentForm'));

            // Use Fetch API to send the form data asynchronously
            fetch(document.getElementById('submitAssignmentForm').getAttribute('action'), {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Handle the response data
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Submission Successful',
                        text: 'Your assignment has been submitted successfully!',
                    }).then(() => {
                        // Reload the page
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Failed',
                        text: 'There was an error submitting your assignment.',
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Failed',
                    text: 'There was an error submitting your assignment.',
                });
            });
        }
    });
}
function confirmEditSubmission() {
        Swal.fire({
            title: 'Apakah Anda yakin ingin mengedit pengumpulan tugas?',
            text: 'Setelah diedit, tugas Anda akan diperbarui.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Edit!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Use FormData to serialize the form data
                const formData = new FormData(document.getElementById('editSubmissionForm'));

                // Use Fetch API to send the form data asynchronously
                fetch(document.getElementById('editSubmissionForm').getAttribute('action'), {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response data
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Edit Successful',
                            text: 'Your submission has been edited successfully!',
                        }).then(() => {
                            // Reload the page or update the UI as needed
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Edit Failed',
                            text: 'There was an error editing your submission.',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Edit Failed',
                        text: 'There was an error editing your submission.',
                    });
                });
            }
        });
    }


    function confirmLogout() {
        Swal.fire({
            title: 'Apakah Anda yakin ingin logout?',
            text: 'Anda akan keluar dari sesi ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect to the logout page or trigger your logout logic here
                window.location.href = '../systems/logout.php';
            }
        });
    }
</script>
<?php include('../components/footer2.php'); ?>
