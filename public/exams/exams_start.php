<?php
require_once('../../database/connection.php');
include('../components/header.php');
session_start();
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
if (isset($_SESSION['UserID'])) {
    if (isset($_GET['test_id'])) {
        $testID = $_GET['test_id'];

        $testQuery = "SELECT * FROM Tests WHERE TestID = $testID";
        $testResult = mysqli_query($conn, $testQuery);
        $testData = mysqli_fetch_assoc($testResult);

        if ($testData) {
            $duration = $testData['DurationMins'];
            $testDuration = $duration * 60;

            // Fetch all questions related to the test
            $allQuestionsQuery = "SELECT QuestionID, QuestionText FROM Questions WHERE TestID = $testID";
            $allQuestionsResult = mysqli_query($conn, $allQuestionsQuery);
            $allQuestionsData = mysqli_fetch_all($allQuestionsResult, MYSQLI_ASSOC);

            $questionID = null; // Initialize $questionID

            if (isset($_GET['question_id'])) {
                $questionID = $_GET['question_id'];

                // Fetch user's responses for the current question
                $userResponseQuery = "SELECT AnswerID FROM StudentResponses WHERE StudentID = $_SESSION[StudentID] AND TestID = $testID AND QuestionID = $questionID";
                $userResponseResult = mysqli_query($conn, $userResponseQuery);

                // Initialize an array to store user selected answer IDs
                $userSelectedAnswerIDs = [];

                // Fetch all selected answer IDs into the array
                while ($row = mysqli_fetch_assoc($userResponseResult)) {
                    $userSelectedAnswerIDs[] = $row['AnswerID'];
                }

                // Get the total number of user responses
                $totalUserResponses = count($userSelectedAnswerIDs);

                // Display or echo the total number of user responses
                echo "Total User Responses: $totalUserResponses";

                // Initialize $userSelectedAnswerID as an array to avoid undefined variable warning
                $userSelectedAnswerID = [];

                // Fetch user's responses for the current question
                $userResponseQuery = "SELECT AnswerID FROM StudentResponses WHERE StudentID = $_SESSION[StudentID] AND TestID = $testID AND QuestionID = $questionID";
                $userResponseResult = mysqli_query($conn, $userResponseQuery);

                // Fetch all selected answer IDs into the array
                while ($row = mysqli_fetch_assoc($userResponseResult)) {
                    $userSelectedAnswerID[] = $row['AnswerID'];
                }

                // Fetch question information from the database
                $questionQuery = "SELECT * FROM Questions WHERE QuestionID = $questionID AND TestID = $testID";
                $questionResult = mysqli_query($conn, $questionQuery);
                $questionData = mysqli_fetch_assoc($questionResult);

                // Fetch answers related to the question
                $answersQuery = "SELECT * FROM Answers WHERE QuestionID = $questionID";
                $answersResult = mysqli_query($conn, $answersQuery);
                $answersData = mysqli_fetch_all($answersResult, MYSQLI_ASSOC);
            }
        }
    }
}

?>

<body class="overflow-hidden">
    <!-- Navbar -->
    <header class="bg-blue-600 p-4 text-white">
        <nav class="container mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
            <a href="javascript:void(0);" onclick="confirmLogout()" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-500 hover:bg-white mt-4 lg:mt-0">Logout</a>
        </nav>
    </header>
    <div class="h-screen flex flex-row overflow-hidden sc-hide">
        <!-- Main Content -->
        <div class="w-9/12 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <!-- Main Content -->
            <main class="container mx-auto mt-4 p-4 bg-white shadow-lg rounded-md">
                <div class="flex justify-between">
                    <h2 class="text-3xl font-semibold mb-4">Test: <?php echo $testData['TestName']; ?></h2>
                    <div id="timer" class="text-black"></div>
                </div>
                <!-- Navigation for Questions -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold mb-2">Navigation</h3>
                    <ul class="flex space-x-2">
                        <!-- Start Option -->
                        <li>
                            <a href="exams_start.php?test_id=<?php echo $testID; ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                Start
                            </a>
                        </li>
                        <!-- Question Options -->
                        <?php foreach ($allQuestionsData as $index => $question) : ?>
                            <?php
                            $questionID = $question['QuestionID'];

                            // Check if the current question has a response in the database
                            $responseQuery = "SELECT COUNT(*) as count FROM StudentResponses WHERE StudentID = $_SESSION[StudentID] AND TestID = $testID AND QuestionID = $questionID";
                            $responseResult = mysqli_query($conn, $responseQuery);
                            $responseCount = mysqli_fetch_assoc($responseResult)['count'];

                            // Apply a class to highlight the answered question
                            $highlightClass = ($responseCount > 0) ? 'bg-green-500 text-white' : '';

                            ?>
                            <li>
                                <a href="exams_start.php?test_id=<?php echo $testID; ?>&question_id=<?php echo $questionID; ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 <?php echo $highlightClass; ?>">
                                    <?php echo $index + 1; ?> <!-- Increment the index to start from 1 -->
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <!-- End Option -->
                        <li>
                            <a href="exams_start.php?test_id=<?php echo $testID; ?>&question_id=<?php echo end($allQuestionsData)['QuestionID']; ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                End
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Display Question and Answers -->
                <?php if (isset($_GET['question_id'])) : ?>
                    <?php if ($questionData && $answersData) : ?>
                        <div>
                            <?php
                            $currentQuestionID = $_GET['question_id'];

                            // Find the index of the current question in the navigation
                            $currentQuestionIndex = array_search($currentQuestionID, array_column($allQuestionsData, 'QuestionID'));

                            // Display the question number and text
                            if ($currentQuestionIndex !== false) {
                                $questionNumber = $currentQuestionIndex + 1;
                            ?>
                                <h3 class="text-xl font-semibold mb-2">
                                    Question <?php echo $questionNumber; ?> of <?php echo count($allQuestionsData); ?>:
                                    <?php echo $questionData['QuestionText']; ?>
                                </h3>
                            <?php } else { ?>
                                <p>Invalid question ID or no data available for the question.</p>
                            <?php } ?>

                            <?php // Display answers 
                            ?>
                            <form id="answerForm" action="process_answer.php?test_id=<?php echo $_GET['test_id'] ?>&question_id=<?php echo $_GET['question_id'] ?>" method="post" class="mt-4">
                                <?php foreach ($answersData as $answer) : ?>
                                    <?php
                                    // Check if the current answer is selected by the user
                                    $isChecked = in_array($answer['AnswerID'], $userSelectedAnswerID);


                                    $inputType = ($questionData['QuestionType'] === 'true_false' || $questionData['QuestionType'] === 'single_choice') ? 'radio' : 'checkbox';

                                    // Generate unique input names based on question type
                                    $inputName = ($questionData['QuestionType'] === 'multiple_choice') ? 'answers[]' : 'answer';

                                    ?>
                                    <div class="flex items-center mb-2 p-3 border border-gray-300 cursor-pointer rounded-md transition duration-300 ease-in-out transform hover:scale-95 <?php echo $isChecked ? 'bg-green-200' : ''; ?>" onclick="selectAnswer('<?php echo $answer['AnswerID']; ?>')">
                                        <input type="<?php echo $inputType; ?>" name="<?php echo $inputName; ?>" id="answer_<?php echo $answer['AnswerID']; ?>" value="<?php echo $answer['AnswerID']; ?>" class="mr-2 text-<?php echo $isChecked ? 'white' : ($inputType === 'radio' ? 'blue-500' : 'green-500'); ?> focus:ring-2 focus:ring-<?php echo $isChecked ? 'white' : ($inputType === 'radio' ? 'blue-500' : 'green-500'); ?>" <?php echo $isChecked ? 'checked' : ''; ?>>
                                        <label for="answer_<?php echo $answer['AnswerID']; ?>" class="text-sm text-gray-700"><?php echo $answer['AnswerText']; ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <button type="submit" class="hidden">Submit Answer</button>

                                <script>
                                    function selectAnswer(answerId) {
                                        var answerInput = document.getElementById('answer_' + answerId);

                                        if (answerInput) {
                                            if (answerInput.type === 'checkbox') {
                                                // Toggle the checkbox for multiple-choice questions
                                                answerInput.checked = !answerInput.checked;
                                            } else {
                                                // For radio buttons or true/false questions, just check the selected option
                                                answerInput.checked = true;
                                            }
                                        }
                                    }
                                </script>
                            </form>
                        </div>
                    <?php else : ?>
                        <p>Invalid question ID or no data available for the question.</p>
                    <?php endif; ?>
                <?php else : ?>
                    <div class="bg-gray-200 p-4 rounded-md">
                        <p class="text-xl font-bold mb-4">Welcome to the <?php echo $testData['TestName']; ?> test! <i class="fas fa-check-circle text-green-500"></i></p>
                        <p>This test consists of <?php echo $testData['NumQuestions']; ?> questions, covering material from the subject <?php echo $testData['SubjectID']; ?>.</p>
                        <p>The duration of the test is <?php echo $testData['DurationMins']; ?> minutes. <i class="far fa-clock text-blue-500"></i></p>
                        <p>Good luck! <i class="fas fa-thumbs-up text-yellow-500"></i></p>
                    </div>
                <?php endif; ?>

                <!-- Add Next and Previous buttons -->
                <div class="mt-4 flex justify-between">
                    <?php
                    // Determine the previous and next question IDs
                    $prevQuestionID = null;
                    $nextQuestionID = null;
                    $IIquestionID = isset($_GET['question_id']) ? $_GET['question_id'] : null;

                    $currentQuestionIndex = array_search($IIquestionID, array_column($allQuestionsData, 'QuestionID'));

                    if ($currentQuestionIndex !== false) {
                        $prevQuestionID = ($currentQuestionIndex > 0) ? $allQuestionsData[$currentQuestionIndex - 1]['QuestionID'] : null;

                        // Determine the next question ID
                        $nextQuestionID = null;
                        $nextQuestionIndex = $currentQuestionIndex + 1;

                        // Check if the next index is within array bounds
                        if ($nextQuestionIndex < count($allQuestionsData)) {
                            $nextQuestionID = $allQuestionsData[$nextQuestionIndex]['QuestionID'];
                        }
                    }

                    // Check if the current question is the last one
                    $isLastQuestion = ($IIquestionID == end($allQuestionsData)['QuestionID']);
                    ?>
                    <a href="exams_start.php?test_id=<?php echo $testID; ?>&question_id=<?php echo $prevQuestionID; ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 <?php echo ($prevQuestionID === null) ? 'invisible' : ''; ?>">
                        Previous
                    </a>

                    <!-- New Submit Answer Button -->
                    <button onclick="submitAnswer()" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                        Submit Answer
                    </button>

                    <?php if (!$isLastQuestion) : ?>
                        <?php
                        $nextQuestionIndex = $currentQuestionIndex + 1;
                        $nextQuestionID = $allQuestionsData[$nextQuestionIndex]['QuestionID'];
                        ?>
                        <a href="exams_start.php?test_id=<?php echo $testID; ?>&question_id=<?php echo $nextQuestionID; ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                            Next
                        </a>
                    <?php else : ?>
                        <a href="process_hand_on_paper.php?test_id=<?php echo $testID; ?>" class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100 <?php echo ($isLastQuestion) ? '' : 'invisible'; ?>">
                            Hand on Paper
                        </a>
                    <?php endif; ?>

                </div>
            </main>
        </div>
    </div>
</body>
<script>
    var testDuration = <?php echo $testDuration; ?>;
    var timeRemaining = testDuration;

    function updateTimer() {
        var minutes = Math.floor(timeRemaining / 60);
        var seconds = timeRemaining % 60;

        var formattedTime = padZero(Math.max(minutes, 0)) + ":" + padZero(Math.max(seconds, 0));

        document.getElementById("timer").innerText = "Time Remaining: " + formattedTime;

        if (timeRemaining <= 0) {
            clearInterval(timerInterval); // Stop the timer when time is up
            //   alert("Time's up! Submitting the test.");
            // You may want to redirect to a submission page or trigger a form submission here
        }

        timeRemaining--;
    }

    function padZero(number) {
        return (number < 10 ? "0" : "") + number;
    }

    var timerInterval = setInterval(updateTimer, 1000);
</script>
<script>
    function submitAnswer() {
        // Trigger the form submission
        document.getElementById("answerForm").submit();
    }

    function highlightAnswer(answerID) {
        // Add a visual highlight when hovering over an answer
        document.getElementById("answer_" + answerID).style.backgroundColor = "#e6e6e6";
    }

    function removeHighlightAnswer(answerID) {
        // Remove the visual highlight when the mouse leaves an answer
        document.getElementById("answer_" + answerID).style.backgroundColor = "";
    }
</script>

<script>
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
<script>
    <?php
    // Display SweetAlert based on session message
    if (isset($_SESSION['message'])) {
        list($type, $message) = $_SESSION['message'];
        unset($_SESSION['message']);
    ?>
        Swal.fire({
            icon: '<?php echo $type; ?>',
            title: '<?php echo ucfirst($type); ?>!',
            text: '<?php echo $message; ?>',
            showConfirmButton: false,
            timer: 1500
        });
    <?php
    }
    ?>
</script>


</html>