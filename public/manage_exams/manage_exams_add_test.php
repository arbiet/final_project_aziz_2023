<?php
// Include the necessary files and start the session
session_start();
require_once('../../database/connection.php');
include_once('../components/header.php');

// Initialize variables
$materialID = '';
$errors = array();
$materialData = array();

// Retrieve material data
if (isset($_GET['material_id'])) {
    $materialID = $_GET['material_id'];
    $query = "SELECT * FROM Materials WHERE MaterialID = $materialID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $materialData = $result->fetch_assoc();
    } else {
        $errors[] = "Material not found.";
    }
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission (inserting questions and answers into the database)
    // ...

    // Redirect to the test details page or display an error message
    // ...
}
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
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Add Test for <?php echo $materialData['TitleMaterial']; ?></h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="manage_exams_list.php" class="bg-gray-800 hover-bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Test Creation Form -->
                    <form action="" method="POST" class="flex flex-col w-full space-x-2" id="testCreationForm">
                        <!-- Material ID (Hidden Input) -->
                        <input type="hidden" name="material_id" value="<?php echo $materialID; ?>">

                        <!-- Test Name -->
                        <label for="test_name" class="block font-semibold text-gray-800 mt-2 mb-2">Test Name <span class="text-red-500">*</span></label>
                        <input type="text" id="test_name" name="test_name" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" placeholder="Test Name" required>

                        <!-- Number of Questions -->
                        <label for="number_of_questions" class="block font-semibold text-gray-800 mt-2 mb-2">Number of Questions</label>
                        <input type="number" id="number_of_questions" name="number_of_questions" class="w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600" value="1" min="1" max="10" onchange="updateQuestionFields()" required>

                        <!-- Question and Answer Fields Container -->
                        <div id="questionFieldsContainer">
                            <!-- JavaScript will add question fields here -->
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="bg-green-500 hover-bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center mt-4 text-center">
                            <i class="fas fa-check mr-2"></i>
                            <span>Create Test</span>
                        </button>
                    </form>
                    <!-- End Test Creation Form -->
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
    function updateQuestionFields() {
        var numberOfQuestions = parseInt(document.getElementById("number_of_questions").value);
        var container = document.getElementById("questionFieldsContainer");
        container.innerHTML = "";

        for (var i = 1; i <= numberOfQuestions; i++) {
            var questionNumber = i;
            var questionField = document.createElement("div");
            questionField.className = "mt-4 question-container"; // Add a class for easy identification

            // Question Text
            var questionLabel = document.createElement("label");
            questionLabel.innerHTML = "Question " + questionNumber + "<span class='text-red-500'>*</span>";
            questionLabel.className = "block font-semibold text-gray-800 mt-2 mb-2";
            questionField.appendChild(questionLabel);

            var questionInput = document.createElement("input");
            questionInput.type = "text";
            questionInput.name = "question_" + questionNumber;
            questionInput.className = "w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600";
            questionInput.placeholder = "Question Text";
            questionInput.required = true;
            questionField.appendChild(questionInput);

            // Question Type
            var questionTypeLabel = document.createElement("label");
            questionTypeLabel.innerHTML = "Question Type";
            questionTypeLabel.className = "block font-semibold text-gray-800 mt-2 mb-2";
            questionField.appendChild(questionTypeLabel);

            var questionTypeSelect = document.createElement("select");
            questionTypeSelect.name = "question_type_" + questionNumber;
            questionTypeSelect.className = "w-full rounded-md border-gray-300 px-2 py-2 border text-gray-600";
            questionTypeSelect.setAttribute("onchange", "updateAnswerFields(" + questionNumber + ")");
            questionField.appendChild(questionTypeSelect);

            // Add options for question type
            var questionTypeOptions = ["true_false", "multiple_choice"];
            for (var j = 0; j < questionTypeOptions.length; j++) {
                var option = document.createElement("option");
                option.value = questionTypeOptions[j];
                option.text = questionTypeOptions[j].charAt(0).toUpperCase() + questionTypeOptions[j].slice(1).replace("_", " ");
                questionTypeSelect.appendChild(option);
            }

            container.appendChild(questionField);

            // Initial call to populate answer fields based on the initial question type
            updateAnswerFields(questionNumber);
        }
    }

    function updateAnswerFields(questionNumber) {
        var container = document.getElementById("questionFieldsContainer");
        var questionContainer = document.querySelector(".question-container:last-child");
        var questionTypeSelect = questionContainer.querySelector("select[name='question_type_" + questionNumber + "']");

        if (questionTypeSelect) {
            var questionType = questionTypeSelect.value;
            var answerFieldPrefix = "answer_" + questionNumber + "_";

            // Remove existing answer fields for the given question number
            var elementsToRemove = Array.from(questionContainer.children).filter(element => element.id && element.id.startsWith(answerFieldPrefix));
            elementsToRemove.forEach(element => element.remove());

            // Add answer fields dynamically based on the selected question type
            var numberOfAnswerFields = (questionType === "true_false") ? 2 : 4;

            for (var i = 1; i <= numberOfAnswerFields; i++) {
                var answerField = document.createElement("div");
                answerField.className = "mt-2";

                // Answer Text
                var answerLabel = document.createElement("label");
                answerLabel.innerHTML = "Answer " + i + "<span class='text-red-500'>*</span>";
                answerLabel.className = "block font-semibold text-gray-800 mt-2 mb-2";
                answerField.appendChild(answerLabel);

                var answerInput = document.createElement("input");
                answerInput.type = "text";
                answerInput.name = "answer_" + questionType + "_" + questionNumber + "_" + i;
                answerInput.className = "w-96 rounded-md border-gray-300 px-2 py-2 border text-gray-600";
                answerInput.placeholder = "Answer Text";
                answerInput.required = true;
                answerField.appendChild(answerInput);

                questionContainer.appendChild(answerField);

                // Checkbox for correct answer
                var correctAnswerLabel = document.createElement("label");
                correctAnswerLabel.innerHTML = "Correct Answer";
                correctAnswerLabel.className = "block font-semibold text-gray-800 mt-2 mb-2 w-32"; // Menambahkan lebar label menggunakan w-32

                var correctAnswerCheckbox = document.createElement("input");
                correctAnswerCheckbox.type = "checkbox";
                correctAnswerCheckbox.name = "correct_answer_" + questionType + "_" + questionNumber + "_" + i;
                correctAnswerCheckbox.value = i;
                correctAnswerCheckbox.className = "ml-2 h-4 w-4"; // Menambahkan ukuran checkbox menggunakan h-4 dan w-4

                // Using Tailwind CSS classes for styling
                correctAnswerLabel.classList.add("flex", "items-center");
                correctAnswerCheckbox.classList.add("ml-2");

                // Adding Tailwind classes to the container div
                answerField.classList.add("flex", "items-center", "justify-between", "space-x-2");

                answerField.appendChild(correctAnswerLabel);
                answerField.appendChild(correctAnswerCheckbox);
            }
        }
    }

    // Initial call to populate fields based on the initial value
    updateQuestionFields();
</script>



</body>

</html>