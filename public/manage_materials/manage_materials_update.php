<?php
session_start();

// Include the database connection
require_once('../../database/connection.php');
include_once('../components/header.php');

// Initialize variables
$material_id = $subject_id = $title_material = $type = $content = $link = $sequence = '';
$errors = array();
$form_processed = false;

// Fetch material details for the given MaterialID
if (isset($_GET['id'])) {
    $material_id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "SELECT * FROM Materials WHERE MaterialID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $subject_id = $row['SubjectID'];
        $title_material = $row['TitleMaterial'];
        $type = $row['Type'];
        $content = $row['Content'];
        $link = $row['Link'];
        $sequence = $row['Sequence'];
    } else {
        // Handle invalid MaterialID
        echo "Invalid MaterialID.";
        exit();
    }
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $title_material = mysqli_real_escape_string($conn, $_POST['title_material']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    // Check for errors
    if (empty($subject_id)) {
        $errors['subject_id'] = "Subject is required.";
    }
    if (empty($title_material)) {
        $errors['title_material'] = "Title Material is required.";
    }

    // If there are no errors, update the data in the database
    if (empty($errors)) {
        $query = "UPDATE Materials SET SubjectID = ?, TitleMaterial = ?, Type = ?, Content = ? WHERE MaterialID = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $subject_id, $title_material, $type, $content, $material_id);

        if ($stmt->execute()) {
            // Material update successful
            // Log the activity for material update
            $activityDescription = "Material updated: $title_material";
            $currentUserID = $_SESSION['UserID'];
            insertLogActivity($conn, $currentUserID, $activityDescription);
            $form_processed = true;
        } else {
            // Material update failed
            $errors['db_error'] = "Material update failed.";

            // Display an error notification
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Material update failed.",
                });
            </script>';
        }
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
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Update Material</h1>
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
                    <!-- Material Update Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2">
                        <!-- Material ID (Hidden Input) -->
                        <input type="hidden" name="material_id" value="<?php echo $material_id; ?>">

                        <!-- Subject ID -->
                        <label for="subject_id" class="block font-semibold text-gray-800 mt-2 mb-2">Subject <span class="text-red-500">*</span></label>
                        <select id="subject_id" name="subject_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Subject</option>
                            <!-- Add options dynamically based on your subjects -->
                            <?php
                            $query = "SELECT SubjectID, SubjectName FROM Subjects";
                            $result = $conn->query($query);
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($subject_id == $row['SubjectID']) ? 'selected' : '';
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

                        <!-- Content -->
                        <label for="content" class="block font-semibold text-gray-800 mt-2 mb-2">Content</label>
                        <textarea id="content" name="content" class="ckeditor w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" rows="10"><?php echo $content; ?></textarea>

                        <!-- Type -->
                        <label for="type" class="block font-semibold text-gray-800 mt-2 mb-2">Type</label>
                        <input type="text" id="type" name="type" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Type" value="<?php echo $type; ?>">

                        <!-- Submit Button -->
                        <button type="submit" class="bg-blue-500 hover-bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Update Material</span>
                        </button>
                    </form>
                    <!-- End Material Update Form -->
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
</html>
