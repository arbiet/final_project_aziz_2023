<?php
session_start();

// Include the database connection
require_once('../../database/connection.php');
include_once('../components/header.php');


// Initialize variables
$subject_id = $title_material = $type = $content = $link = $sequence = '';
$errors = array();
$form_processed = false;

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $title_material = mysqli_real_escape_string($conn, $_POST['title_material']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $content = '';
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Check for errors
    if (empty($subject_id)) {
        $errors['subject_id'] = "Subject is required.";
    }
    if (empty($title_material)) {
        $errors['title_material'] = "Title Material is required.";
    }
    // Query to get the maximum sequence for the given subject_id
    $maxSequenceQuery = "SELECT MAX(Sequence) AS MaxSequence FROM Materials WHERE SubjectID = ?";
    $stmt = $conn->prepare($maxSequenceQuery);
    $stmt->bind_param("s", $subject_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $maxSequence = $row['MaxSequence'];

    // Increment the sequence by 1
    $sequence = $maxSequence + 1;

    /// Check the selected material option
    $material_option = $_POST['material_option'];

    // Initialize $content and $link
    $link = '';

    if ($material_option === "upload_file") {
        // Check if a file was uploaded
        if (!empty($_FILES['file_upload']['name'])) {
            // Get the file name from the uploaded file
            $original_file_name = $_FILES['file_upload']['name'];

            // Generate the new file name based on $subject_id and $title_material
            $new_file_name = $subject_id . '_' . str_replace(' ', '', $title_material) . '.php';

            $file_tmp = $_FILES['file_upload']['tmp_name'];
            $file_destination = '../materials_data/' . $new_file_name;

            // Create the content here if needed
            // For example, you can read the contents of the uploaded file and store it in the $content variable
            $content = file_get_contents($file_tmp);

            // If you need to manipulate the content, you can do it here

            // If everything is ready, you can insert the data into the database
            // If there are no errors, insert the data into the database
            if (empty($errors)) {
                // First, move the file to the destination
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // File upload successful, update the $link variable with the file path
                    $link = '../materials_data/' . $new_file_name;

                    $query = "INSERT INTO Materials (SubjectID, TitleMaterial, Type, Content, Link, Sequence)
                  VALUES (?, ?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param(
                        "ssssss",
                        $subject_id,
                        $title_material,
                        $type,
                        $content,  // Use content as the value to be inserted into the database
                        $link,
                        $sequence
                    );

                    if ($stmt->execute()) {
                        // Material creation successful
                        // Log the activity for material creation
                        $activityDescription = "Material created: $title_material";
                        $currentUserID = $_SESSION['UserID'];
                        insertLogActivity($conn, $currentUserID, $activityDescription);
                        $form_processed = true;
                    } else {
                        // Material creation failed
                        $errors['db_error'] = "Material creation failed.";

                        // Display an error notification
                        echo '<script>
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Material creation failed.",
                    });
                </script>';
                    }
                }
            } else {
                $errors['file_upload'] = "Please select a file to upload.";
            }
        }
    } elseif ($material_option === "create_new") {
        // Create a new PHP file based on subject_id + title_material
        $file_name = '../materials_data/' . $subject_id . '_' . str_replace(' ', '', $title_material) . '.php';

        // Save content to the new PHP file
        if (!empty($content)) {
            if (file_put_contents($file_name, $content)) {
                $link = $file_name;
            } else {
                $errors['file_creation'] = "File creation failed. Check directory permissions and file name validity.";
            }
        } else {
            $errors['file_creation'] = "Content is empty. Cannot create an empty file.";
        }

        // If there are no errors, insert the data into the database
        if (empty($errors)) {
            $query = "INSERT INTO Materials (SubjectID, TitleMaterial, Type, Content, Link, Sequence)
                  VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param(
                "ssssss",
                $subject_id,
                $title_material,
                $type,
                $content,  // Use content as the value to be inserted into the database
                $link,
                $sequence
            );

            if ($stmt->execute()) {
                // Material creation successful
                // Log the activity for material creation
                $activityDescription = "Material created: $title_material";
                $currentUserID = $_SESSION['UserID'];
                insertLogActivity($conn, $currentUserID, $activityDescription);
                // Display a success notification
                $form_processed = true;
            } else {
                // Material creation failed
                $errors['db_error'] = "Material creation failed.";

                // Display an error notification
                echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Material creation failed.",
                });
            </script>';
            }
        }
    }
    // Check if a file was uploaded
    if (!empty($_FILES['content_source_files']['name'][0]) && $form_processed) {
        $file_count = count($_FILES['content_source_files']['name']);
        $uploaded_files = array();

        for ($i = 0; $i < $file_count; $i++) {
            $file_name = $_FILES['content_source_files']['name'][$i];
            $file_tmp = $_FILES['content_source_files']['tmp_name'][$i];

            // Generate the folder name based on subject_id and title_material
            $folder_name = $subject_id . '_' . str_replace(' ', '', $title_material);

            // Create the folder if it doesn't exist
            if (!file_exists('../materials_data/' . $folder_name)) {
                mkdir('../materials_data/' . $folder_name, 0777, true);
            }

            $file_destination = '../materials_data/' . $folder_name . '/' . $file_name;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($file_tmp, $file_destination)) {
                $uploaded_files[] = $file_destination;
            } else {
                $errors['file_upload'] = "File upload failed. Check directory permissions and file name validity.";
            }
        }

        // Save the array of uploaded file paths in the $link variable
        $link = implode(',', $uploaded_files);
    } else {
        $errors['file_upload'] = "Please select one or more files to upload.";
    }
    if ($form_processed) {
        echo '<script>
        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Material created successfully."
        }).then(function() {
            window.location.href = "manage_materials_create.php";
        });
    </script>';
    }
}

// Close the database connection
?>
<!-- Main Content Height Adjusted to Fit Between Header and Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="flex-grow bg-gray-50 flex flex-row shadow-md">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->

        <!-- Main Content -->
        <main class="bg-gray-50 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <div class="flex items-start justify-start p-6 shadow-md m-4 flex-1 flex-col">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Create Material</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="manage_materials_list.php" class="bg-gray-800 hover-bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
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
                            <p class="text-gray-600 text-sm">Material creation form.</p>
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
                    <!-- Material Creation Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2" enctype="multipart/form-data">
                        <!-- Subject ID -->
                        <label for="subject_id" class="block font-semibold text-gray-800 mt-2 mb-2">Subject <span class="text-red-500">*</span></label>
                        <select id="subject_id" name="subject_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Subject</option>
                            <!-- Add options dynamically based on your subjects -->
                            <?php
                            $query = "SELECT SubjectID, SubjectName FROM Subjects";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($_GET['subject_id'] == $row['SubjectID']) ? 'selected' : '';
                                echo '<option value="' . $row['SubjectID'] . '" ' . $selected . '>' . $row['SubjectName'] . '</option>';
                            }
                            ?>
                        </select>
                        <?php if (isset($errors['subject_id'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['subject_id']; ?>
                            </p>
                        <?php endif; ?>
                        <!-- Title Material -->
                        <label for="title_material" class="block font-semibold text-gray-800 mt-2 mb-2">Title Material <span class="text-red-500">*</span></label>
                        <input type="text" id="title_material" name="title_material" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Title Material" value="<?php echo $title_material; ?>">
                        <?php if (isset($errors['title_material'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['title_material']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Material Option -->
                        <label for="material_option" class="block font-semibold text-gray-800 mt-2 mb-2">Material Option <span class="text-red-500">*</span></label>
                        <select id="material_option" name="material_option" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="create_new">Create New</option>
                            <option value="upload_file">Upload File</option>
                        </select>

                        <!-- Create New Content (Initially Hidden) -->
                        <div id="create-new-content" style="display: none">
                            <!-- Content -->
                            <label for="content" class="block font-semibold text-gray-800 mt-2 mb-2">Content</label>
                            <textarea id="content" name="content" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" rows="10"></textarea>
                        </div>

                        <!-- Upload File (Initially Hidden) -->
                        <div id="upload-file" style="display: none">
                            <!-- Upload File Input -->
                            <label for="content" class="block font-semibold text-gray-800 mt-2 mb-2">Upload File</label>
                            <input type="file" id="file_upload" name="file_upload" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                        </div>

                        <div id="upload-file">
                            <!-- Upload File Input -->
                            <label for="content_source_files" class="block font-semibold text-gray-800 mt-2 mb-2">Upload Data Source</label>
                            <input type="file" id="content_source_files" name="content_source_files[]" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" multiple>
                        </div>

                        <!-- Type -->
                        <label for="type" class="block font-semibold text-gray-800 mt-2 mb-2">Type</label>
                        <input type="text" id="type" name="type" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Type" value="<?php echo $type; ?>">

                        <!-- Submit Button -->
                        <button type="submit" class="bg-green-500 hover-bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Create Material</span>
                        </button>
                    </form>
                    <!-- End Material Creation Form -->
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
</body>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil elemen-elemen yang diperlukan
        var materialOption = document.getElementById("material_option");
        var createNewContent = document.getElementById("create-new-content");
        var uploadFile = document.getElementById("upload-file");
        var findFile = document.getElementById("find-file");

        // Fungsi untuk menampilkan atau menyembunyikan elemen berdasarkan pilihan
        function toggleMaterialOption() {
            var selectedOption = materialOption.value;

            if (selectedOption === "create_new") {
                createNewContent.style.display = "block";
                uploadFile.style.display = "none";
            } else if (selectedOption === "upload_file") {
                createNewContent.style.display = "none";
                uploadFile.style.display = "block";
            }
        }

        // Panggil fungsi saat halaman dimuat dan ketika pilihan berubah
        toggleMaterialOption();
        materialOption.addEventListener("change", toggleMaterialOption);
    });
</script>


</html>