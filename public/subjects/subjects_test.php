<?php
session_start();
require_once('../../database/connection.php');
include('../components/header.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
  // Jika tidak, arahkan ke halaman login
  header("Location: ../systems/login.php");
  exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
if (isset($_SESSION['UserID'])) {
  // Check if 'subject_id' is set in the URL
  if (isset($_GET['subject_id'])) {
    $subjectID = $_GET['subject_id'];

    // Fetch subject information from the database
    $query = "SELECT s.*, t.*, u.FullName AS TeacherName
                  FROM Subjects s
                  LEFT JOIN Teachers t ON s.TeacherID = t.TeacherID
                  LEFT JOIN Users u ON t.UserID = u.UserID
                  WHERE s.SubjectID = $subjectID";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      $subjectData = mysqli_fetch_assoc($result);
    }

    // Fetch materials related to the subject
    $materialQuery = "SELECT * FROM Materials WHERE SubjectID = $subjectID ORDER BY Sequence";
    $materialResult = mysqli_query($conn, $materialQuery);
    $materials = [];

    if ($materialResult && mysqli_num_rows($materialResult) > 0) {
      while ($row = mysqli_fetch_assoc($materialResult)) {
        $materials[] = $row;
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
    <!-- Sidebar for Materials -->
    <?php include_once('../components/sidebar_students.php') ?>
    <!-- Main Content -->
    <div class="w-9/12 flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
        <!-- Main Content -->
        <main class="container mx-auto mt-4 p-4 bg-white shadow-lg rounded-md">
            <h2 class="text-3xl font-semibold mb-4"><?php echo $subjectData['SubjectName']; ?></h2>

            <?php if (isset($_GET['test_id'])) : ?>
                <?php
                $testID = $_GET['test_id'];
                $testQuery = "SELECT * FROM Tests WHERE TestID = $testID";
                $testResult = mysqli_query($conn, $testQuery);
                $testData = mysqli_fetch_assoc($testResult);

                if ($testData) :
                ?>
                    <div class="bg-blue-100 p-4 rounded-md mb-4">
                        <h3 class="text-blue-700 font-semibold">Test Information:</h3>
                        <p class="mb-2">Test Name: <?php echo $testData['TestName']; ?></p>
                        <p class="mb-2">Test Type: <?php echo $testData['TestType']; ?></p>
                        <p class="mb-2">Number of Questions: <?php echo $testData['NumQuestions']; ?></p>
                        <p class="mb-2">Duration: <?php echo $testData['DurationMins']; ?> minutes</p>
                    </div>

                    <?php
                    $testResultQuery = "SELECT * FROM TestResults WHERE StudentID = {$_SESSION['StudentID']} AND TestID = $testID";
                    $testResultResult = mysqli_query($conn, $testResultQuery);
                    $testResultData = mysqli_fetch_assoc($testResultResult);

                    if ($testResultData) :
                    ?>
                        <div class="bg-green-100 p-4 rounded-md mb-4">
                            <h3 class="text-green-700 font-semibold">Your Test Results:</h3>
                            <p>Correct Answers: <?php echo $testResultData['CorrectAnswers']; ?></p>
                            <p>Incorrect Answers: <?php echo $testResultData['IncorrectAnswers']; ?></p>
                            <p>Score: <?php echo $testResultData['Score']; ?></p>
                            <p>
                                <a href="subjects_test.php?subject_id=<?php echo $subjectID; ?>&material=<?php echo $material['MaterialID']; ?>&test_id=<?php echo $testID; ?>&test_type=<?php echo $_GET['test_type']; ?>" class="text-blue-500 hover:underline">Re-test</a>
                            </p>
                        </div>
                    <?php else : ?>
                        <div class="bg-blue-100 p-4 rounded-md mb-4 pb-6">
                            <h3 class="text-blue-700 font-semibold mb-4">Take the Test:</h3>
                            <a href="../exams/exams_start.php?test_id=<?php echo $testID; ?>&subject_id=<?php echo $subjectID; ?>" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">Take the Test</a>
                        </div>
                    <?php endif; ?>

                <?php else : ?>
                    <p class="text-red-500">Test information not available.</p>
                <?php endif; ?>

            <?php else : ?>
                <div class="bg-yellow-100 p-4 rounded-md mb-4">
                    <h3 class="text-yellow-700 font-semibold">Start Test:</h3>
                    <a href="../exams/exams_start.php?subject_id=<?php echo $subjectID; ?>&material=<?php echo $material['MaterialID']; ?>&test_id=<?php echo $testID; ?>&test_type=<?php echo $_GET['test_type']; ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-700">Start Test</a>
                </div>
            <?php endif; ?>
        </main>
    </div>
  </div>
</body>
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