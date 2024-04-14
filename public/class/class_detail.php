<?php
require_once('../../database/connection.php');
include('../components/header2.php');
session_start();
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: ../systems/login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}
?>
<!-- Navbar -->
 <?php include('../components/navbar3.php'); ?>
<div class="p-8 pt-20 bg-teal-100">
    <div class="container mx-auto bg-white p-8 rounded shadow-lg w-full overflow-y-auto">
        <?php
        if (isset($_GET['class_id'])) {
            $classID = $_GET['class_id'];

            // Fetch class details
            $classQuery = "SELECT c.*, u.FullName as HomeroomTeacherName FROM Classes c
                           LEFT JOIN Users u ON c.HomeroomTeacher = u.UserID
                           WHERE c.ClassID = $classID";
            $classResult = mysqli_query($conn, $classQuery);

            if ($classResult && mysqli_num_rows($classResult) > 0) {
                $classData = mysqli_fetch_assoc($classResult);

                // Display class details
                echo '<h2 class="text-2xl font-semibold mb-4">Class Detail: ' . $classData['ClassName'] . '</h2>';
                echo '<div class="p-4 bg-gray-200 rounded-md mb-4 w-full">';
                echo '<p class="mb-2"><strong>Class Code:</strong> ' . $classData['ClassCode'] . '</p>';
                echo '<p class="mb-2"><strong>Education Level:</strong> ' . $classData['EducationLevel'] . '</p>';
                echo '<p class="mb-2"><strong>Homeroom Teacher:</strong> ' . $classData['HomeroomTeacherName'] . '</p>';
                echo '</div>';

                // Fetch and display enrolled students
                $studentsQuery = "SELECT s.*, u.FullName as StudentFullName FROM Students s
                                  LEFT JOIN Users u ON s.UserID = u.UserID
                                  WHERE s.ClassID = $classID";
                $studentsResult = mysqli_query($conn, $studentsQuery);

                if ($studentsResult && mysqli_num_rows($studentsResult) > 0) {
                    echo '<h3 class="text-xl font-semibold m-4">Enrolled Students:</h3>';
                    echo '<ul class="p-4 w-full">';
                    while ($student = mysqli_fetch_assoc($studentsResult)) {
                        $fullName = isset($student['StudentFullName']) ? $student['StudentFullName'] : 'N/A';
                        $studentNumber = isset($student['StudentNumber']) ? $student['StudentNumber'] : 'N/A';

                        echo '<li class="mb-2">' . $fullName . ' - ' . $studentNumber . '</li>';
                    }
                    echo '</ul>';
                } else {
                    echo '<p class="text-gray-500 mt-4 w-full">No students are enrolled in this class.</p>';
                }
            } else {
                echo '<p class="text-red-500 w-full">Class not found.</p>';
            }
        } else {
            echo '<p class="text-red-500 w-full">Class ID parameter not found.</p>';
        }
        ?>
    </div>
</div>
 <?php include('../components/footer2.php'); ?>