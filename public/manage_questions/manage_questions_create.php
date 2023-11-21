  <?php
  session_start();

  // Include the database connection
  require_once('../../database/connection.php');
  include_once('../components/header.php');

  // Initialize variables
  $testID = '';
  $errors = array();
  $questionText = '';
  $questionType = '';
  $answers = array();

  // Process the form submission
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate the input data
    $testID = mysqli_real_escape_string($conn, $_POST['test_id']);
    $questionText = mysqli_real_escape_string($conn, $_POST['question_text']);
    $questionType = mysqli_real_escape_string($conn, $_POST['question_type']);

    // Validate the test ID
    if (empty($testID)) {
      $errors['test_id'] = "Test ID is required.";
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
      $answers[] = array(
        'answer_text' => 'True',
        'is_correct' => isset($_POST['true_false_answer']) && $_POST['true_false_answer'] === 'true'
      );
      $answers[] = array(
        'answer_text' => 'False',
        'is_correct' => isset($_POST['true_false_answer']) && $_POST['true_false_answer'] === 'false'
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
    // Insert the question into the database
    // Note: Adjust the SQL query based on your database schema
    $query = "INSERT INTO Questions (QuestionText, QuestionType, TestID) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $questionText, $questionType, $testID);

    if ($stmt->execute()) {
      // Get the questionID after inserting the question
      $questionID = $stmt->insert_id;

      // Move the image upload section here, after obtaining questionID
      // Process image upload
      $questionImage = null;
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
        } else {
          $errors['question_image'] = "Image upload failed.";
          // Display an error notification
          echo '<script>
                  Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Image upload failed.",
                  });
                </script>';
        }
      }

      // Update the SQL query to include the image column
      $updateQuery = "UPDATE Questions SET QuestionImage = ? WHERE QuestionID = ?";
      $updateStmt = $conn->prepare($updateQuery);
      $updateStmt->bind_param("si", $questionImage, $questionID);
      

      if ($updateStmt->execute()) {

        // Insert answers into the database
        foreach ($answers as $answer) {
          $answerText = $answer['answer_text'];
          $isCorrect = $answer['is_correct'];

          // Note: Adjust the SQL query based on your database schema
          $answerQuery = "INSERT INTO Answers (AnswerText, IsCorrect, QuestionID) VALUES (?, ?, ?)";
          $answerStmt = $conn->prepare($answerQuery);
          $answerStmt->bind_param("ssi", $answerText, $isCorrect, $questionID);
          $answerStmt->execute();
        }

        // Question and answers creation successful
        // Log the activity for question creation
        $activityDescription = "Question created for Test ID: $testID";
        $currentUserID = $_SESSION['UserID'];
        insertLogActivity($conn, $currentUserID, $activityDescription);

        // Display a success notification
        echo '<script>
                  Swal.fire({
                      icon: "success",
                      title: "Success",
                      text: "Question and answers created successfully.",
                  }).then(function() {
                      window.location.href = "../manage_exams/manage_exams_detail.php?id=' . $testID . '";
                  });
              </script>';
        exit();
      }
    } else {
        // Question creation failed
        $errors['db_error'] = "Question creation failed.";

        // Display an error notification
        echo '<script>
                  Swal.fire({
                      icon: "error",
                      title: "Error",
                      text: "Question creation failed.",
                  });
              </script>';
      }
    }
    
  }
  // If it's a GET request, fetch the test_id from the URL
  $testID = mysqli_real_escape_string($conn, $_GET['test_id']);
  // Fetch the Test Name based on the Test ID
  $testName = '';
  if (!empty($testID)) {
    $testQuery = "SELECT TestName FROM Tests WHERE TestID = ?";
    $testStmt = $conn->prepare($testQuery);
    $testStmt->bind_param("i", $testID);
    $testStmt->execute();
    $testResult = $testStmt->get_result();
    if ($testRow = $testResult->fetch_assoc()) {
      $testName = $testRow['TestName'];
    }
  }


  // Close the database connection
  ?>
  <!-- HTML and JavaScript code for manage_questions_create.php -->
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
            <h1 class="text-3xl text-gray-800 font-semibold w-full">Create Question</h1>
            <div class="flex flex-row justify-end items-center">
              <a href="../manage_exams/manage_exams_detail.php?id=<?php echo $testID; ?>" class="bg-gray-800 hover-bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
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
                <p class="text-gray-600 text-sm">Question creation form.</p>
              </div>
            </div>
            <!-- End Navigation -->
            <!-- Question Creation Form -->
            <form action="" method="POST" class="flex flex-col w-full space-x-2" enctype="multipart/form-data">
              
              <!-- Test Name (Read-only) -->
              <label class="block font-semibold text-gray-800 mt-2 mb-2">Test Name</label>
              <input type="text" name="test_name" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Test Name" value="<?php echo htmlspecialchars($testName); ?>" readonly>

              <!-- Test ID (Read-only) -->
              <label class="block font-semibold text-gray-800 mt-2 mb-2">Test ID</label>
              <input type="text" name="test_id" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Test ID" value="<?php echo htmlspecialchars($testID); ?>" readonly>

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

              <!-- Question Type -->
              <label for="question_type" class="block font-semibold text-gray-800 mt-2 mb-2">Question Type <span class="text-red-500">*</span></label>
              <select id="question_type" name="question_type" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600">
                <option value="" disabled selected>Select Question Type</option>
                <option value="true_false">True/False</option>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="single_choice">Single Choice</option>
              </select>
              <?php if (isset($errors['question_type'])) : ?>
                <p class="text-red-500 text-sm">
                  <?php echo $errors['question_type']; ?>
                </p>
              <?php endif; ?>

              <!-- Answers Section -->
              <div id="answersSection" class="hidden">
                <label class="block font-semibold text-gray-800 mt-2 mb-2">Answers <span class="text-red-500">*</span></label>
                <div id="trueFalseAnswers" class="hidden">
                  <div class="flex items-center mt-2 space-x-2">
                    <label class="block font-semibold text-gray-800">True</label>
                    <input type="radio" name="true_false_answer" value="true">
                  </div>
                  <div class="flex items-center mt-2 space-x-2">
                    <label class="block font-semibold text-gray-800">False</label>
                    <input type="radio" name="true_false_answer" value="false">
                  </div>
                </div>
                <!-- Updated Single Choice Answers Section -->
                <div id="singleChoiceAnswers" class="hidden">
                  <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <div class="flex items-center mt-2 space-x-2">
                      <label class="block font-semibold text-gray-800">Choice <?php echo $i; ?></label>
                      <input type="text" name="choice_single<?php echo $i; ?>" class="w-1/2 rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Choice <?php echo $i; ?>">
                      <input type="radio" name="correct_choice_single" value="<?php echo $i; ?>">
                      <label class="block font-semibold text-gray-800">Correct</label>
                    </div>
                  <?php endfor; ?>
                </div>
                <!-- End Updated Single Choice Answers Section -->

                <!-- Multiple Choice Answers Section -->
                <div id="multipleChoiceAnswers" class="hidden">
                  <?php for ($i = 1; $i <= 4; $i++) : ?>
                    <div class="flex items-center mt-2 space-x-2">
                      <label class="block font-semibold text-gray-800">Choice <?php echo $i; ?></label>
                      <input type="text" name="choice_multiple<?php echo $i; ?>" class="w-1/2 rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Choice <?php echo $i; ?>">
                      <input type="checkbox" name="correct_choice_multiple<?php echo $i; ?>" value="true">
                      <label class="block font-semibold text-gray-800">Correct</label>
                    </div>
                  <?php endfor; ?>
                </div>
                <!-- End Multiple Choice Answers Section -->

              </div>
              <!-- End Answers Section -->

              <!-- Submit Button -->
              <button type="submit" class="bg-green-500 hover-bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                <i class="fas fa-check mr-2"></i>
                <span>Create Question</span>
              </button>
            </form>
            <!-- End Question Creation Form -->
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
      const trueFalseAnswers = document.getElementById('trueFalseAnswers');
      const singleChoiceAnswers = document.getElementById('singleChoiceAnswers');
      const multipleChoiceAnswers = document.getElementById('multipleChoiceAnswers');

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
    });
  </script>
  </body>

  </html>