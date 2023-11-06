<?php
session_start();

// Include the database connection
require_once('../../database/connection.php');
$addedHead = '<link rel="stylesheet" type="text/css" href="../../content-tools/content-tools.min.css">';
include_once('../components/header.php');


// Initialize variables
$subject_id = $title_material = $type = $content = $link = $sequence = '';
$errors = array();

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $title_material = mysqli_real_escape_string($conn, $_POST['title_material']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $link = mysqli_real_escape_string($conn, $_POST['link']);
    $sequence = mysqli_real_escape_string($conn, $_POST['sequence']);

    // Check for errors
    if (empty($subject_id)) {
        $errors['subject_id'] = "Subject is required.";
    }
    if (empty($title_material)) {
        $errors['title_material'] = "Title Material is required.";
    }
    // Add validation rules for other fields as needed...

    // If there are no errors, insert the data into the database
    if (empty($errors)) {
        $query = "INSERT INTO Material (SubjectID, TitleMaterial, Type, Content, Link, Sequence)
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssss", $subject_id, $title_material, $type, $content, $link, $sequence);

        if ($stmt->execute()) {
            // Material creation successful
            // Log the activity for material creation
            $activityDescription = "Material created: $title_material";
            $currentUserID = $_SESSION['UserID'];
            insertLogActivity($conn, $currentUserID, $activityDescription);
            // Display a success notification
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Material created successfully.",
                }).then(function() {
                    window.location.href = "manage_materials_list.php";
                });
            </script>';
            exit();
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

// Close the database connection
?>
<script src="https://cdn.tiny.cloud/1/rflwrnd3qul9mawoopjuu3u3lt8zftw2p2idqo7rq6au3r92/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
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
                    <!-- Material Creation Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2">
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

                        <!-- Material Option -->
                        <label for="material_option" class="block font-semibold text-gray-800 mt-2 mb-2">Material Option <span class="text-red-500">*</span></label>
                        <select id="material_option" name="material_option" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="create_new">Create New</option>
                            <option value="upload_file">Upload File</option>
                            <option value="find_file">Find File</option>
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

                        <!-- Find File (Initially Hidden) -->
                        <div id="find-file" style="display: none">
                            <!-- File List (You can list existing files or add your logic) -->
                            <label for="content" class="block font-semibold text-gray-800 mt-2 mb-2">Find File</label>
                            <select id="existing_material" name="existing_material" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                                <option value="">Select Existing Material</option>
                                <!-- Add options dynamically based on existing materials -->
                                <?php
                                // Your logic to fetch and display existing materials
                                ?>
                            </select>
                        </div>

                        <!-- Title Material -->
                        <label for="title_material" class="block font-semibold text-gray-800 mt-2 mb-2">Title Material <span class="text-red-500">*</span></label>
                        <input type="text" id="title_material" name="title_material" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Title Material" value="<?php echo $title_material; ?>">
                        <?php if (isset($errors['title_material'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['title_material']; ?>
                            </p>
                        <?php endif; ?>
                        <!-- Type -->
                        <label for="type" class="block font-semibold text-gray-800 mt-2 mb-2">Type</label>
                        <input type="text" id="type" name="type" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Type" value="<?php echo $type; ?>">

                        <!-- Link -->
                        <label for="link" class="block font-semibold text-gray-800 mt-2 mb-2">Link</label>
                        <input type="text" id="link" name="link" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Link" value="<?php echo $link; ?>">

                        <!-- Sequence -->
                        <label for="sequence" class="block font-semibold text-gray-800 mt-2 mb-2">Sequence</label>
                        <input type="text" id="sequence" name="sequence" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Sequence" value="<?php echo $sequence; ?>">

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
                findFile.style.display = "none";
            } else if (selectedOption === "upload_file") {
                createNewContent.style.display = "none";
                uploadFile.style.display = "block";
                findFile.style.display = "none";
            } else if (selectedOption === "find_file") {
                createNewContent.style.display = "none";
                uploadFile.style.display = "none";
                findFile.style.display = "block";
            }
        }

        // Panggil fungsi saat halaman dimuat dan ketika pilihan berubah
        toggleMaterialOption();
        materialOption.addEventListener("change", toggleMaterialOption);
    });
</script>


</html>