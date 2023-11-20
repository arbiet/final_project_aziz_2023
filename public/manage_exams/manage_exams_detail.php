<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

// Initialize variables
$testID = '';
$errors = array();
$testData = array();
$questionsData = array();

// Pagination variables
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 5; // You can adjust this based on the number of rows per page

// Initialize search term variable
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Retrieve test data
if (isset($_GET['id'])) {
    $testID = $_GET['id'];
    $query = "SELECT t.TestID, t.TestName, t.TestType, m.TitleMaterial
              FROM Tests t
              INNER JOIN Materials m ON t.MaterialID = m.MaterialID
              WHERE t.TestID = $testID";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $testData = $result->fetch_assoc();

        // Retrieve questions associated with the test
        $questionsQuery = "SELECT q.QuestionID, q.QuestionText, q.QuestionType
                           FROM Questions q
                           WHERE q.TestID = $testID";
        $questionsResult = $conn->query($questionsQuery);

        if ($questionsResult->num_rows > 0) {
            while ($question = $questionsResult->fetch_assoc()) {
                // Retrieve answers associated with each question
                $questionID = $question['QuestionID'];
                $answersQuery = "SELECT a.AnswerID, a.AnswerText, a.IsCorrect
                                 FROM Answers a
                                 WHERE a.QuestionID = $questionID";
                $answersResult = $conn->query($answersQuery);

                $question['Answers'] = array();
                while ($answer = $answersResult->fetch_assoc()) {
                    $question['Answers'][] = $answer;
                }

                $questionsData[] = $question;
            }
        }
    } else {
        $errors[] = "Test not found.";
    }
}

// Pagination
if (isset($_GET['id'])) {
    $testID = $_GET['id'];
    $queryRowCount = "SELECT COUNT(*) as rowCount FROM Tests WHERE TestID = ?";
    $stmtRowCount = $conn->prepare($queryRowCount);
    $stmtRowCount->bind_param("i", $testID); // Assuming TestID is an integer, adjust accordingly
    $stmtRowCount->execute();
    $resultRowCount = $stmtRowCount->get_result();
    $rowCount = $resultRowCount->fetch_assoc()['rowCount'];

    $totalPage = ceil($rowCount / $perPage);
    $startPage = max(1, $page - 2);
    $endPage = min($totalPage, $page + 2);
}
?>



<?php include_once('../components/header.php'); ?>
<!-- Main Content Height Adjusts to Fit Between Header and Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="flex-grow bg-gray-50 flex flex-row shadow-md">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->
        <main class="bg-gray-50 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <div class="flex items-start justify-start p-6 shadow-md m-4 flex-1 flex-col">
                <!-- Header Content -->
                <div class="flex flex-row justify-between items-center w-full border-b-2 border-gray-600 mb-2 pb-2">
                    <h1 class="text-3xl text-gray-800 font-semibold w-full">Test Details</h1>
                    <div class="flex flex-row justify-end items-center">
                        <a href="../manage_exams/manage_exams_list.php" class="bg-gray-800 hover-bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
                <!-- End Header Content -->
                <!-- Content -->
                <div class="flex flex-col w-full">
                    <!-- Navigation -->
                    <div class="flex flex-row justify-between items-center w-full mb-2 pb-2">
                        <div>
                            <h2 class="text-lg text-gray-800 font-semibold">Welcome back, <?php echo $_SESSION['FullName']; ?>!</h2>
                            <p class="text-gray-600 text-sm">Test information.</p>
                        </div>
                    </div>
                    <!-- End Navigation -->
                    <!-- Test Details -->
                    <?php if (!empty($testData)) : ?>
                        <div class="bg-white shadow-md p-4 rounded-md">
                            <h3 class="text-lg font-semibold text-gray-800">Test Information</h3>
                            <p><strong>Test Name:</strong> <?php echo $testData['TestName']; ?></p>
                            <p><strong>Test Type:</strong> <?php echo $testData['TestType']; ?></p>
                            <p><strong>Material Title:</strong> <?php echo $testData['TitleMaterial']; ?></p>
                        </div>
                        <!-- Questions and Answers Section -->
                        <div class="mt-4 bg-white shadow-md p-4 rounded-md">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-800">Questions for this Test</h3>
                                <!-- Add Question button on the right -->
                                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" onclick="addQuestion()">
                                    <i class="fas fa-plus"></i> Add Question
                                </button>
                            </div>
                            <?php if (!empty($questionsData)) : ?>
                                <?php foreach ($questionsData as $question) : ?>
                                    <div class="border-b border-gray-200 py-4 relative">
                                        <!-- Question Type in the top-right corner -->
                                        <p class="text-gray-500 absolute top-0 right-0 mt-2 mr-2"><?php echo $question['QuestionType']; ?></p>
                                        <p class="text-lg font-semibold text-gray-800"><?php echo $question['QuestionText']; ?></p>
                                        <?php if (!empty($question['Answers'])) : ?>
                                            <div class="flex flex-wrap items-center">
                                                <?php foreach ($question['Answers'] as $answer) : ?>
                                                    <div class="flex items-center mr-4">
                                                        <?php if ($answer['IsCorrect']) : ?>
                                                            <span class="m-2 py-1 px-2 text-white rounded-md bg-green-500">
                                                                <?php echo $answer['AnswerText']; ?>
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="mr-2 text-gray-500"><?php echo $answer['AnswerText']; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php else : ?>
                                            <p class="text-gray-500">No answers available.</p>
                                        <?php endif; ?>
                                        <div class="mt-2">
                                            <a href="#" class="text-blue-500 hover:underline mr-4" onclick="editQuestion(<?php echo $question['QuestionID']; ?>)">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="#" class="text-red-500 hover:underline" onclick="confirmDeleteQuestion(<?php echo $question['QuestionID']; ?>)">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                <?php
                                // Pagination
                                ?>
                                <div class="flex flex-row justify-between items-center w-full mt-4">
                                    <div class="flex flex-row justify-start items-center">
                                        <span class="text-gray-600">Total <?php echo $rowCount; ?> rows</span>
                                    </div>
                                    <div class="flex flex-row justify-end items-center space-x-2">
                                        <?php
                                        $prevPage = $page > 1 ? $page - 1 : 1;
                                        $nextPage = $page < $totalPage ? $page + 1 : $totalPage;
                                        ?>
                                        <a href="?id=<?php echo $testData['TestID'] ?>&page=1&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                                            <i class="fas fa-angle-double-left"></i>
                                        </a>
                                        <a href="?id=<?php echo $testData['TestID'] ?>&page=<?php echo $prevPage; ?>&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                        <!-- Page number -->
                                        <?php
                                        for ($i = $startPage; $i <= $endPage; $i++) {
                                            if ($i == $page) {
                                                $aidi = $testData['TestID'];
                                                echo "<a href='?id=$aidi&page=$i&search=$searchTerm' class='bg-blue-500 hover-bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center'>$i</a>";
                                            } else {
                                                echo "<a href='?id=$aidi&page=$i&search=$searchTerm' class='bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center'>$i</a>";
                                            }
                                        }
                                        ?>
                                        <a href="?id=<?php echo $testData['TestID'] ?>&page=<?php echo $nextPage; ?>&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                        <a href="?id=<?php echo $testData['TestID'] ?>&page=<?php echo $totalPage; ?>&search=<?php echo $searchTerm; ?>" class="bg-gray-200 hover-bg-gray-300 text-gray-600 font-bold py-2 px-4 rounded inline-flex items-center">
                                            <i class="fas fa-angle-double-right"></i>
                                        </a>
                                    </div>
                                    <div class="flex flex-row justify-end items-center ml-2">
                                        <span class="text-gray-600">Page <?php echo $page; ?> of <?php echo $totalPage; ?></span>
                                    </div>
                                </div>

                            <?php else : ?>
                                <div class="bg-white shadow-md p-4 rounded-md mt-4">
                                    <p>No questions available for this test.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php else : ?>
                        <div class="bg-white shadow-md p-4 rounded-md">
                            <p>No test data available.</p>
                        </div>
                    <?php endif; ?>
                    <!-- End Test Details -->
                </div>
                <!-- End Content -->
            </div>
        </main>
    </div>

    <!-- End Main Content -->
    <!-- Footer -->
    <?php include('../components/footer.php'); ?>
    <!-- End Footer -->
</div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function addQuestion() {
        // Redirect to the page for adding a question with the test ID
        window.location.href = `../manage_questions/manage_questions_create.php?test_id=<?php echo $testID; ?>`;
    }

    function editQuestion(questionID) {
        // Redirect to the page for editing a question with the question ID
        window.location.href = `../manage_questions/manage_questions_update.php?test_id=<?php echo $testID; ?>&question_id=${questionID}`;
    }

    function confirmDeleteQuestion(questionID) {
        // Display a SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // If the user clicks "Yes," redirect to the page for deleting a question with the question ID
                window.location.href = `../manage_questions/manage_questions_delete.php?test_id=<?php echo $testID; ?>&question_id=${questionID}`;
            }
        });
    }
</script>

</html>