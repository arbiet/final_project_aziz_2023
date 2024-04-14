<?php
session_start();

// Include the database connection
require_once('../../database/connection.php');
include_once('../components/header2.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
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
        // Check if a video file was uploaded
        if (!empty($_FILES['video_upload']['name'])) {
            $video_tmp = $_FILES['video_upload']['tmp_name'];
            $video_name = $_FILES['video_upload']['name'];
            $video_destination = '../materials_data/'.$title_material.'/'.'video/' . $video_name;

            // Create the directory if it does not exist
            $video_directory = dirname($video_destination);
            if (!file_exists($video_directory)) {
                mkdir($video_directory, 0777, true);
            }

            // Move the uploaded video to the destination folder
            if (move_uploaded_file($video_tmp, $video_destination)) {
                $video_link = 'materials_data/'.$title_material.'/'.'video/' . $video_name;
                
                // Update the video link in the database
                $query = "UPDATE Materials SET SubjectID = ?, TitleMaterial = ?, Type = ?, Content = ?, Video = ? WHERE MaterialID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssss", $subject_id, $title_material, $type, $content, $video_link, $material_id);

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
            } else {
                $errors['video_upload'] = "Video upload failed. Check directory permissions and file name validity.";
            }
        } else {
            // If no video uploaded, update other fields except video link
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
}

// Close the database connection
?>
<?php include('../components/sidebar2.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-200 min-h-screen transition-all main">
    <?php include('../components/navbar2.php'); ?>
    <!-- Content -->
      <div class="p-4">
        <!-- Main Content -->
        <div class="flex items-start justify-start p-6 shadow-lg m-4 bg-white flex-1 flex-col rounded-md">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Update Material</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="manage_materials_list.php" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Material Update Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2" enctype="multipart/form-data">
                        <!-- Material ID (Hidden Input) -->
                        <input type="hidden" name="material_id" value="<?php echo $material_id; ?>">

                        <!-- Subject ID -->
                        <label for="subject_id" class="block font-semibold text-gray-800 mt-2 mb-2 <?php echo ($teacherID !== null) ? 'hidden' : '';?>">Subject <span class="text-red-500">*</span></label>
                        <select id="subject_id" name="subject_id" class="<?php echo ($teacherID !== null) ? 'hidden' : '';?> w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                            <option value="">Select Subject</option>
                            <!-- Add options dynamically based on your subjects -->
                            <?php
                            $query = "SELECT SubjectID, SubjectName FROM Subjects";
                            if ($teacherID !== null) {
                                // If $teacherID is not null, add a condition to filter by TeacherID
                                $query .= " WHERE TeacherID = '$teacherID'";
                            }
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

                        <!-- Video Upload -->
                        <label for="video_upload" class="block font-semibold text-gray-800 mt-2 mb-2">Upload New Video</label>
                        <input type="file" id="video_upload" name="video_upload" accept="video/*" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                        <?php if (isset($errors['video_upload'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['video_upload']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Submit Button -->
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Update Material</span>
                        </button>
                    </form>
                    <!-- End Material Update Form -->
                </div>
                <!-- End Content -->
            </div>
        <!-- End Main Content -->
    </div>
    <!-- End Main Content -->
</main>
<?php include('../components/footer2.php'); ?>
</html>
