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
$questionID = '';
$errors = array();
$questionText = '';
$questionType = '';
$answers = array();
$testID = mysqli_real_escape_string($conn, $_GET['test_id']);
$questionID = mysqli_real_escape_string($conn, $_GET['question_id']);
// Process the form submission
if (
    $_SERVER["REQUEST_METHOD"] == "POST"
) {
    // Sanitize and validate the input data
    $questionID = mysqli_real_escape_string($conn, $_POST['question_id']);
    $questionText = mysqli_real_escape_string($conn, $_POST['question_text']);
    $questionType = mysqli_real_escape_string($conn, $_POST['question_type']);

    // Validate the question ID
    if (empty($questionID)) {
        $errors['question_id'] = "Question ID is required.";
    }

    // Validate the question text
    if (empty($questionText)) {
        $errors['question_text'] = "Question text is required.";
    }

    // Validate the question type
    if (empty($questionType)) {
        $errors['question_type'] = "Question type is required.";
    }

    // Validate and process answers based on question type
    $answers = array();
    if ($questionType === 'true_false') {
        // For true/false questions
        if (isset($_POST['true_false_answer'])) {
            if ($_POST['true_false_answer'] === 'True') {
                $is_correctTrue = true;
                $is_correctFalse = false;
            } else {
                $is_correctTrue = false;
                $is_correctFalse = true;
            }
        }

        $answers[] = array(
            'answer_text' => 'True',
            'is_correct' => $is_correctTrue
        );
        $answers[] = array(
            'answer_text' => 'False',
            'is_correct' => $is_correctFalse
        );
    } elseif ($questionType === 'multiple_choice') {
        // For multiple/single choice questions
        for ($i = 1; $i <= 4; $i++) {
            $answerText = mysqli_real_escape_string($conn, $_POST["choice_multiple{$i}"]);
            $isCorrect = false;
            if (isset($_POST["correct_choice_multiple{$i}"]) && $_POST["correct_choice_multiple{$i}"] === 'true') {
                $isCorrect = true;
            }
            if (!empty($answerText)) {
                $answers[] = array(
                    'answer_text' => $answerText,
                    'is_correct' => $isCorrect
                );
            }
        }
    } elseif ($questionType === 'single_choice') {
        // For multiple/single choice questions
        for ($i = 1; $i <= 4; $i++) {
            $isCorrect = false;
            $answerText = mysqli_real_escape_string($conn, $_POST["choice_single{$i}"]);
            if ($_POST["correct_choice_single"] == $i) {
                $isCorrect = true;
            }
            if (!empty($answerText)) {
                $answers[] = array(
                    'answer_text' => $answerText,
                    'is_correct' => $isCorrect
                );
            }
        }
    }

    // Check for errors
    if (empty($errors)) {
        // Update the question in the database
        // Process image upload
        $questionQuery = "SELECT QuestionImage FROM Questions WHERE QuestionID = ?";
        $questionStmt = $conn->prepare($questionQuery);
        $questionStmt->bind_param("i", $questionID);
        $questionStmt->execute();
        $questionResult = $questionStmt->get_result();
        if ($questionRow = $questionResult->fetch_assoc()) {
            $questionImageOld = $questionRow['QuestionImage'];
            $questionImage = $questionRow['QuestionImage'];
        }
        if (!empty($_FILES['question_image']['name'])) {
            $uploadDirectory = '../static/image/tests/';

            // Create the question_id folder if it doesn't exist
            $questionIdFolder = $uploadDirectory . $questionID;
            if (!file_exists($questionIdFolder)) {
                mkdir($questionIdFolder, 0777, true); // 0777 grants full permissions, adjust as needed
            }

            // Generate a unique file name based on time
            $uploadedFileName = uniqid() . '_' . basename($_FILES['question_image']['name']);
            $targetFilePath = $questionIdFolder . '/' . $uploadedFileName;

            if (move_uploaded_file($_FILES['question_image']['tmp_name'], $targetFilePath)) {
                $questionImage = $targetFilePath;
                if (!empty($questionImageOld) && file_exists($questionImageOld)) {
                    unlink($questionImageOld);
                }
                echo ($_FILES['question_image']['name']) . "BERHASIL";
            } else {
                $errors['question_image'] = "Image upload failed.";
                echo ($_FILES['question_image']['name']) . "ERROR";
            }
        } else {
            echo "error woe";
        }

        // Update the SQL query to include the image column
        $updateQuery = "UPDATE Questions SET QuestionText = ?, QuestionType = ?, QuestionImage = ? WHERE QuestionID = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param("sssi", $questionText, $questionType, $questionImage, $questionID);
        
        if ($updateStmt->execute()) {
            // Delete existing answers for the question
            $deleteQuery = "DELETE FROM Answers WHERE QuestionID = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $questionID);
            $deleteStmt->execute();

            // Insert new answers into the database
            foreach ($answers as $answer) {
                print_r($answer);
                $answerText = $answer['answer_text'];
                $isCorrect = $answer['is_correct'];

                // Note: Adjust the SQL query based on your database schema
                $answerQuery = "INSERT INTO Answers (AnswerText, IsCorrect, QuestionID) VALUES (?, ?, ?)";
                $answerStmt = $conn->prepare($answerQuery);
                $answerStmt->bind_param("ssi", $answerText, $isCorrect, $questionID);
                $answerStmt->execute();
            }

            // Question and answers update successful
            // Log the activity for question update
            $activityDescription = "Question updated with ID: $questionID";
            $currentUserID = $_SESSION['UserID'];
            insertLogActivity($conn, $currentUserID, $activityDescription);

            // Display a success notification
            echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Question and answers updated successfully.",
                }).then(function() {
                    window.location.href = "../manage_exams/manage_exams_detail.php?id=' . $testID . '";
                });
            </script>';
            exit();
        } else {
            // Question update failed
            $errors['db_error'] = "Question update failed.";

            // Display an error notification
            echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Question update failed.",
                });
            </script>';
        }
    }
}

// Fetch the question details for editing
$questionQuery = "SELECT QuestionText, QuestionType, QuestionImage FROM Questions WHERE QuestionID = ?";
$questionStmt = $conn->prepare($questionQuery);
$questionStmt->bind_param("i", $questionID);
$questionStmt->execute();
$questionResult = $questionStmt->get_result();
if ($questionRow = $questionResult->fetch_assoc()) {
    $questionText = $questionRow['QuestionText'];
    $questionType = $questionRow['QuestionType'];
    $questionImage = $questionRow['QuestionImage'];
}

// Close the database connection
?>
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
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Update Question</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="../manage_exams/manage_exams_detail.php?id=<?php echo $testID; ?>" class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
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
                            <p class="text-gray-600 text-sm">Question update form.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <!-- Question Update Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2" enctype="multipart/form-data">
                        <!-- Test ID (Read-only) -->
                        <label for="test_id" class="block font-semibold text-gray-800 mt-2 mb-2">Test ID</label>
                        <input type="text" id="test_id" name="test_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" value="<?php echo htmlspecialchars($testID); ?>" readonly>

                        <!-- Question ID (Read-only) -->
                        <label class="block font-semibold text-gray-800 mt-2 mb-2">Question ID</label>
                        <input type="text" name="question_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Question ID" value="<?php echo htmlspecialchars($questionID); ?>" readonly>

                        <!-- Question Text -->
                        <label for="question_text" class="block font-semibold text-gray-800 mt-2 mb-2">Question Text <span class="text-red-500">*</span></label>
                        <textarea id="question_text" name="question_text" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Question Text"><?php echo $questionText; ?></textarea>
                        <?php if (isset($errors['question_text'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['question_text']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Image Upload -->
                        <label for="question_image" class="block font-semibold text-gray-800 mt-2 mb-2">Question Image</label>
                        <input type="file" name="question_image" accept="image/*">

                        <!-- Display existing question image -->
                        <?php if (!empty($questionImage)) : ?>
                            <img src="<?php echo $questionImage; ?>" alt="Question Image" class="mt-2 mb-2 w-40">
                        <?php endif; ?>

                        <!-- Question Type -->
                        <label for="question_type" class="block font-semibold text-gray-800 mt-2 mb-2">Question Type <span class="text-red-500">*</span></label>
                        <input type="text" id="question_type" name="question_type" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" value="<?php echo htmlspecialchars($questionType); ?>" readonly>
                        <?php if (isset($errors['question_type'])) : ?>
                            <p class="text-red-500 text-sm">
                                <?php echo $errors['question_type']; ?>
                            </p>
                        <?php endif; ?>

                        <!-- Answers Section -->
                        <div id="answersSection">
                            <!-- Display existing answers for editing -->
                            <label class="block font-semibold text-gray-800 mt-2 mb-2">Answers <span class="text-red-500">*</span></label>

                            <?php
                            // Fetch and display existing answers for editing
                            $existingAnswersQuery = "SELECT AnswerText, IsCorrect FROM Answers WHERE QuestionID = ?";
                            $existingAnswersStmt = $conn->prepare($existingAnswersQuery);
                            $existingAnswersStmt->bind_param("i", $questionID);
                            $existingAnswersStmt->execute();
                            $existingAnswersResult = $existingAnswersStmt->get_result();

                            // Display existing answers based on question type
                            if ($questionType === 'true_false') {
                                while ($existingAnswerRow = $existingAnswersResult->fetch_assoc()) {
                                    $answerText = $existingAnswerRow['AnswerText'];
                                    $isCorrect = $existingAnswerRow['IsCorrect'];

                                    // Display existing true/false answers for editing
                            ?>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <label class="block font-semibold text-gray-800"><?php echo $answerText; ?></label>
                                        <input type="radio" name="true_false_answer" value="<?php echo $answerText; ?>" <?php echo ($isCorrect) ? 'checked' : ''; ?>>
                                    </div>
                                <?php }
                            } elseif ($questionType === 'single_choice') {
                                $i = 1;
                                while ($existingAnswerRow = $existingAnswersResult->fetch_assoc()) {
                                    $answerText = $existingAnswerRow['AnswerText'];
                                    $isCorrect = $existingAnswerRow['IsCorrect'];

                                    // Display existing single choice answers for editing
                                ?>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <label class="block font-semibold text-gray-800">Choice <?php echo $i; ?></label>
                                        <input type="text" name="choice_single<?php echo $i; ?>" class="w-1/2 rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Choice <?php echo $i; ?>" value="<?php echo $answerText; ?>">
                                        <input type="radio" name="correct_choice_single" value="<?php echo $i; ?>" <?php echo ($isCorrect) ? 'checked' : ''; ?>>
                                        <label class="block font-semibold text-gray-800">Correct</label>
                                    </div>
                                <?php $i++;
                                }
                            } elseif ($questionType === 'multiple_choice') {
                                $i = 1;
                                while ($existingAnswerRow = $existingAnswersResult->fetch_assoc()) {
                                    $answerText = $existingAnswerRow['AnswerText'];
                                    $isCorrect = $existingAnswerRow['IsCorrect'];

                                    // Display existing multiple choice answers for editing
                                ?>
                                    <div class="flex items-center mt-2 space-x-2">
                                        <label class="block font-semibold text-gray-800">Choice <?php echo $i; ?></label>
                                        <input type="text" name="choice_multiple<?php echo $i; ?>" class="w-1/2 rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Choice <?php echo $i; ?>" value="<?php echo $answerText; ?>">
                                        <input type="checkbox" name="correct_choice_multiple<?php echo $i; ?>" value="true" <?php echo ($isCorrect) ? 'checked' : ''; ?>>
                                        <label class="block font-semibold text-gray-800">Correct</label>
                                    </div>
                            <?php $i++;
                                }
                            }
                            ?>

                        </div>
                        <!-- End Answers Section -->

                        <!-- Submit Button -->
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Update Question</span>
                        </button>

                    </form>
                    <!-- End Question Update Form -->

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
<script>
    // JavaScript code to handle dynamic changes based on question type
    document.addEventListener('DOMContentLoaded', function() {
        const questionTypeSelect = document.getElementById('question_type');
        const answersSection = document.getElementById('answersSection');

        // Show/hide answers section based on question type
        questionTypeSelect.addEventListener('change', function() {
            const selectedType = questionTypeSelect.value;
            trueFalseAnswers.classList.add('hidden');
            singleChoiceAnswers.classList.add('hidden');
            multipleChoiceAnswers.classList.add('hidden');

            if (selectedType === 'true_false') {
                trueFalseAnswers.classList.remove('hidden');
            } else if (selectedType === 'single_choice') {
                singleChoiceAnswers.classList.remove('hidden');
            } else if (selectedType === 'multiple_choice') {
                multipleChoiceAnswers.classList.remove('hidden');
            }

            answersSection.classList.remove('hidden');
        });

        // Additional JavaScript code as needed for form validation, etc.
    });
</script>
</body>

</html>