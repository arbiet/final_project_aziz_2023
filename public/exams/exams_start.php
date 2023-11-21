<?php
require_once('../../database/connection.php');
include('../components/header.php');
session_start();

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

            // Check if 'question_id' is set in the URL
            if (isset($_GET['question_id'])) {
                $questionID = $_GET['question_id'];

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
                            <a href="exams_start.php?test_id=<?php echo $testID; ?>"
                                class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                Start
                            </a>
                        </li>
                        <!-- Question Options -->
                        <?php foreach ($allQuestionsData as $index => $question) : ?>
                            <li>
                                <a href="exams_start.php?test_id=<?php echo $testID; ?>&question_id=<?php echo $question['QuestionID']; ?>"
                                    class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                    <?php echo $index + 1; ?> <!-- Increment the index to start from 1 -->
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <!-- End Option -->
                        <li>
                            <a href="exams_start.php?test_id=<?php echo $testID; ?>&question_id=end"
                                class="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100">
                                End
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Display Question and Answers -->
                <?php if (isset($_GET['question_id'])) : ?>
                    <?php if ($questionData && $answersData) : ?>
                        <div>
                            <h3 class="text-xl font-semibold mb-2">Question: <?php echo $questionData['QuestionText']; ?></h3>
                            <form action="process_answer.php" method="post">
                                <?php foreach ($answersData as $answer) : ?>
                                    <div>
                                        <?php if ($questionData['QuestionType'] === 'true_false' || $questionData['QuestionType'] === 'single_choice') : ?>
                                            <input type="radio" name="answer" value="<?php echo $answer['AnswerID']; ?>">
                                        <?php elseif ($questionData['QuestionType'] === 'multiple_choice') : ?>
                                            <input type="checkbox" name="answers[]" value="<?php echo $answer['AnswerID']; ?>">
                                        <?php endif; ?>
                                        <label><?php echo $answer['AnswerText']; ?></label>
                                    </div>
                                <?php endforeach; ?>
                                <input type="submit" value="Submit Answer">
                            </form>
                        </div>
                    <?php else : ?>
                        <p>Invalid question ID or no data available for the question.</p>
                    <?php endif; ?>
                <?php else : ?>
                    <p>Welcome to the test opening message.</p>
                <?php endif; ?>
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
