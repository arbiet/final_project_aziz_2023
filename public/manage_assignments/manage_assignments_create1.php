<?php
session_start();
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
// Include the database connection
require_once('../../database/connection.php');
include_once('../components/header2.php');

// Initialize variables
$subject_id = $material_id = $title = $description = $due_date = $priority_level = '';
$errors = array();
$form_processed = false;

// Fetch subjects
$subject_query = "SELECT * FROM Subjects";
if ($_SESSION['Teacher'] !== null) {
    // If $_SESSION['Teacher'] is not null, add a condition to filter by TeacherID
    $subject_query .= " WHERE TeacherID = '{$_SESSION['Teacher']}'";
}
$result_subject = $conn->query($subject_query);
$subjects = $result_subject->fetch_all(MYSQLI_ASSOC);

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $due_date = mysqli_real_escape_string($conn, $_POST['due_date']);
    $priority_level = mysqli_real_escape_string($conn, $_POST['priority_level']);

    // Check for errors
    if (empty($subject_id)) {
        $errors['subject_id'] = "Subject is required.";
    }
    if (empty($material_id)) {
        $errors['material_id'] = "Material is required.";
    }
    if (empty($title)) {
        $errors['title'] = "Assignment Title is required.";
    }
    // File Attachment Handling
    $attachment_file = $_FILES['attachment_file'];

    // If there are no errors, insert the data into the database
    if (empty($errors)) {
        $query = "INSERT INTO Assignments (SubjectID, MaterialID, Title, Description, DueDate, PriorityLevel)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iisssi", $subject_id, $material_id, $title, $description, $due_date, $priority_level);

        if ($stmt->execute()) {

            $last_assignment_id = $conn->insert_id;

            if ($attachment_file['error'] == UPLOAD_ERR_OK) {
                // Validate file type, size, etc., as needed
                $allowed_extensions = array('pdf', 'doc', 'docx', 'txt', 'png', 'jpg', 'jpeg');
                $file_extension = strtolower(pathinfo($attachment_file['name'], PATHINFO_EXTENSION));
        
                if (!in_array($file_extension, $allowed_extensions)) {
                    $errors['attachment_file'] = "Invalid file type. Allowed types: " . implode(', ', $allowed_extensions);
                }
        
                // Additional validation if needed
        
                // Generate a unique file name to avoid overwriting existing files
                $unique_file_name = uniqid('attachment_') . '_' . time() . '.' . $file_extension;
        
                // Move the uploaded file to a designated directory with the unique file name
                $upload_directory = '../static/image/attachment/'; // Change this to your desired directory
                $file_path = $upload_directory . $unique_file_name;
        
                if (!move_uploaded_file($attachment_file['tmp_name'], $file_path)) {
                    $errors['attachment_file'] = "File upload failed.";
                }
        
                // If there are no errors, insert the file information into the AssignmentAttachments table
                if (empty($errors)) {
                    $attachment_query = "INSERT INTO AssignmentAttachments (AssignmentID, AttachmentFile)
                                        VALUES (?, ?)";
                    $stmt_attachment = $conn->prepare($attachment_query);
                    $stmt_attachment->bind_param("is", $assignment_id, $file_path);
        
                    if (!$stmt_attachment->execute()) {
                        $errors['attachment_file'] = "Failed to save file information.";
                    }
        
                    // Close the statement
                    $stmt_attachment->close();
                }
            } elseif ($attachment_file['error'] != UPLOAD_ERR_NO_FILE) {
                // Handle file upload error, if any
                $errors['attachment_file'] = "File upload error: " . $attachment_file['error'];
            }
            // Assignment creation successful
            // Log the activity for assignment creation
            $activityDescription = "Assignment created: SubjectID: $subject_id, MaterialID: $material_id, Title: $title";
            $currentUserID = $_SESSION['UserID'];
            insertLogActivity($conn, $currentUserID, $activityDescription);
            $form_processed = true;

            // Display success notification and redirect
            echo '<script>
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Assignment created successfully!",
                    }).then(function() {
                        window.location.href = "manage_assignments_list.php";
                    });
                </script>';
        } else {
            // Assignment creation failed
            $errors['db_error'] = "Assignment creation failed.";

            // Display an error notification
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Assignment creation failed.",
                    });
                </script>';
        }
    }
}

// Close the database connection
$result_subject->close();
?>
<!-- Main Content Height Adjusted to Fit Between Header and Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="flex-grow bg-gray-50 flex flex-row shadow-lg">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <main class="bg-gray-50 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <div class="flex items-start justify-start p-6 shadow-lg m-4 flex-1 flex-col">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Create Assignment</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="manage_assignments_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Navigation -->
                    <div class="flex flex-row justify-between items-center w-full pb-2">
                        <div>
                            <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                            <p class="text-gray-600 text-sm">Assignment creation form.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <?php if (!empty($errors)) : ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! There were some errors:</strong>
                            <ul>
                                <?php foreach ($errors as $error) : ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <!-- Assignment Creation Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2" enctype="multipart/form-data">
                        <!-- Subject -->
                        <label for="subject_id" class="block font-semibold text-gray-800 mt-2 mb-2">Subject <span class="text-red-500">*</span></label>
                        <select id="subject_id" name="subject_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" onchange="loadMaterials()">
                            <option value="">Select Subject</option>
                            <?php foreach ($subjects as $subject) : ?>
                                <option value="<?php echo $subject['SubjectID']; ?>"><?php echo $subject['SubjectName']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['subject_id'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['subject_id']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Material -->
                        <label for="material_id" class="block font-semibold text-gray-800 mt-2 mb-2">Material <span class="text-red-500">*</span></label>
                        <select id="material_id" name="material_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Material</option>
                            <!-- Materials will be dynamically loaded here using JavaScript -->
                        </select>
                        <?php if (isset($errors['material_id'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['material_id']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Assignment Title -->
                        <label for="title" class="block font-semibold text-gray-800 mt-2 mb-2">Assignment Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Assignment Title" value="<?php echo $title; ?>">
                        <?php if (isset($errors['title'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['title']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Description -->
                        <label for="description" class="block font-semibold text-gray-800 mt-2 mb-2">Description</label>
                        <textarea id="description" name="description" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Description"><?php echo $description; ?></textarea>

                        <!-- Due Date -->
                        <label for="due_date" class="block font-semibold text-gray-800 mt-2 mb-2">Due Date</label>
                        <input type="date" id="due_date" name="due_date" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" value="<?php echo $due_date; ?>">

                        <!-- Priority Level -->
                        <label for="priority_level" class="block font-semibold text-gray-800 mt-2 mb-2">Priority Level</label>
                        <select id="priority_level" name="priority_level" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Priority Level</option>
                            <option value="1">High Priority</option>
                            <option value="2">Medium Priority</option>
                            <option value="3">Low Priority</option>
                        </select>

                        <!-- File Attachment -->
                        <label for="attachment_file" class="block font-semibold text-gray-800 mt-2 mb-2">File Attachment</label>
                        <input type="file" id="attachment_file" name="attachment_file" class="w-full border-gray-300 px-2 py-2 border text-gray-600">
                        <?php if (isset($errors['attachment_file'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['attachment_file']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Submit Button -->
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Create Assignment</span>
                        </button>
                    </form>
                    <!-- End Assignment Creation Form -->
                </div>
                <!-- End Content -->
            </div>
        </main>
        <!-- End Main Content -->
    </div>
    <!-- End Main Content -->
    <!-- Footer -->
    <?php include('../components/footer.php'); ?>
    <!-- End Footer -->
</div>
<!-- End Main Content -->

<!-- JavaScript to dynamically load materials based on the selected subject -->
<script>
    function loadMaterials() {
        var subjectId = document.getElementById('subject_id').value;
        var materialDropdown = document.getElementById('material_id');

        // Clear existing options
        materialDropdown.innerHTML = '<option value="">Select Material</option>';

        // Fetch materials based on the selected subject using AJAX
        if (subjectId !== '') {
            fetch('get_materials.php?subject_id=' + subjectId)
                .then(response => response.json())
                .then(data => {
                    data.materials.forEach(material => {
                        var option = document.createElement('option');
                        option.value = material.MaterialID;
                        option.textContent = material.TitleMaterial;
                        materialDropdown.appendChild(option);
                    });
                });
        }
    }
</script>

</body>

</html>
