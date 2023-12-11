<?php
require_once('../../database/connection.php');
include('../components/header.php');
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

        // Fetch student submission information
        $submissionQuery = "SELECT * FROM AssignmentSubmissions 
                           WHERE AssignmentID = $assignmentID AND StudentID = {$_SESSION['StudentID']}";
        $submissionResult = mysqli_query($conn, $submissionQuery);
        $submissionData = mysqli_fetch_assoc($submissionResult);
    }
}

?>

<body class="overflow-hidden">
    <!-- Navbar -->
    <header class="bg-blue-600 p-4 text-white">
        <nav class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
            <a href="javascript:void(0);" onclick="confirmLogout()"
                class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-500 hover:bg-white mt-4 lg:mt-0">Logout</a>
        </nav>
    </header>
    <div class="h-screen flex flex-row overflow-hidden sc-hide">
        <!-- Sidebar for Materials -->
        <?php include_once('../components/sidebar_students.php') ?>
        <!-- Main Content -->
        <div class="w-9/12 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <!-- Main Content -->
            <main class="container mx-auto mt-4 p-4 bg-white shadow-lg rounded-md">
                <h2 class="text-3xl font-semibold mb-4"><?php echo $assignmentData['Title']; ?></h2>

                <?php if (isset($_GET['assignment_id'])) : ?>
                    <div class="bg-blue-100 p-4 rounded-md mb-4">
                        <h3 class="text-blue-700 font-semibold">Assignment Information:</h3>
                        <p class="mb-2">Assignment Title: <?php echo $assignmentData['Title']; ?></p>
                        <p class="mb-2">Description: <?php echo $assignmentData['Description']; ?></p>
                        <p class="mb-2">Due Date: <?php echo $assignmentData['DueDate']; ?></p>
                        <p class="mb-2">Assigned Date: <?php echo $assignmentData['AssignedDate']; ?></p>
                        <!-- Add more details as needed -->
                    </div>

                    <!-- Display attachments and download links -->
                    <?php if (!empty($attachments)) : ?>
                        <div class="mb-4">
                            <h3 class="text-blue-700 font-semibold">Attachments:</h3>
                            <ul>
                                <?php foreach ($attachments as $attachment) : ?>
                                    <li>
                                        <?php

                                        // example AttachmentFile = ../static/image/attachment/attachment_6576980d8ef4e_1702270989.jpg
                                        $fileExtension = pathinfo($attachment['AttachmentFile'], PATHINFO_EXTENSION);
                                        $allowedImageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                                        $allowedPdfExtensions = ['pdf'];
                                        $isImage = in_array($fileExtension, $allowedImageExtensions);
                                        $isPdf = in_array($fileExtension, $allowedPdfExtensions);
                                        ?>

                                        <?php if ($isImage) : ?>
                                            <img src="<?php echo $attachment['AttachmentFile']; ?>" alt="Image">
                                        <?php elseif ($isPdf) : ?>
                                            <!-- Display PDF viewer using <object> tag -->
                                            <object data="<?php echo $attachment['AttachmentFile']; ?>" type="application/pdf" width="100%" height="600">
                                                <p>Your browser does not support embedded PDF files. You can download the file <a href="<?php echo $attachment['AttachmentFile']; ?>">here</a>.</p>
                                            </object>
                                        <?php else : ?>
                                            <a href="<?php echo $attachment['AttachmentFile']; ?>" download>
                                                <?php echo $attachment['AttachmentFile']; ?>
                                            </a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Display student submission information -->
                    <?php if (!empty($submissionData)) : ?>
                    <!-- Display student submission information -->
                    <div class="mb-4">
                        <h3 class="text-green-700 font-semibold">Your Submission:</h3>
                        <p>Submission Text: <?php echo $submissionData['SubmissionText']; ?></p>

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
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                        <!-- Add more details as needed -->
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

                                <button type="button" onclick="confirmSubmitAssignment()" class="mt-4 bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-700">Submit Assignment</button>
                            </form>
</div>
                    <?php endif; ?>

                <?php else : ?>
                    <p class="text-red-500">Assignment information not available.</p>
                <?php endif; ?>
            </main>
        </div>
    </div>
</body>

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
</html>
