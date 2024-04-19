<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');
// Periksa apakah sesi telah dimulai dengan mengecek salah satu variabel sesi
if (!isset($_SESSION['UserID'])) {
    // Jika tidak, arahkan ke halaman login
    header("Location: login.php");
    exit(); // Pastikan tidak ada kode eksekusi setelah ini
}

// Function to get total users count
function getTotalUsers($conn) {
    $query = "SELECT COUNT(*) as total_users FROM Users";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_users'];
}

// Function to get total students count
function getTotalStudents($conn) {
    $query = "SELECT COUNT(*) as total_students FROM Students";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_students'];
}

// Function to get total teachers count
function getTotalTeachers($conn) {
    $query = "SELECT COUNT(*) as total_teachers FROM Teachers";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_teachers'];
}

// Function to get total classes count
function getTotalClasses($conn) {
    $query = "SELECT COUNT(*) as total_classes FROM Classes";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_classes'];
}

?>
<?php include('../components/header2.php'); ?>
<?php include('../components/sidebar2.php'); ?>
<main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-200 min-h-screen transition-all main">
    <?php include('../components/navbar2.php'); ?>
    <!-- Content -->
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-md border border-gray-100 p-6 shadow-lg shadow-black/5">
                <div class="flex justify-between">
                        <div>
                            <i class="fas fa-users text-3xl text-green-500 mr-2"></i>
                            <div class="flex items-center mb-1">
                                <div class="text-2xl font-semibold"><?php echo getTotalUsers($conn); ?></div>
                            </div>
                            <div class="text-sm font-medium text-gray-400">Total Users</div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-md border border-gray-100 p-6 shadow-lg shadow-black/5">
                    <div class="flex justify-between">
                        <div>
                            <i class="fas fa-user-graduate text-3xl text-indigo-500 mr-2"></i>
                            <div class="flex items-center mb-1">
                                <div class="text-2xl font-semibold"><?php echo getTotalStudents($conn); ?></div>
                            </div>
                            <div class="text-sm font-medium text-gray-400">Total Students</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
                <div class="p-6 relative flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50 w-full shadow-lg rounded">
                    <div class="rounded-t mb-0 px-0 border-0">
                        <div class="flex flex-wrap items-center px-4 py-2">
                            <div class="relative w-full max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-base text-gray-900">Total Classes</h3>
                            </div>
                        </div>
                        <div class="block w-full overflow-x-auto">
                            <div class="flex items-center p-4">
                                <i class="fas fa-school text-3xl text-indigo-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Total Classes</p>
                                    <p class="text-lg font-semibold"><?php echo getTotalClasses($conn); ?></p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Total Students per Class</p>
                                <table class="min-w-full bg-transparent border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Class</th>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Total Students</th>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Students Login</th>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Log Logins</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $classQuery = "SELECT ClassID, ClassName, ClassCode FROM Classes";
                                        $classResult = mysqli_query($conn, $classQuery);

                                        while ($classRow = mysqli_fetch_assoc($classResult)) {
                                            $classID = $classRow['ClassID'];
                                            $className = $classRow['ClassCode'];

                                            $totalStudentsQuery = "SELECT COUNT(*) as total_students FROM Students WHERE ClassID = $classID";
                                            $totalStudentsResult = mysqli_query($conn, $totalStudentsQuery);
                                            $totalStudentsRow = mysqli_fetch_assoc($totalStudentsResult);
                                            $totalStudents = $totalStudentsRow['total_students'];

                                            $totalLoginsQuery = "SELECT COUNT(DISTINCT LogActivity.UserID) as total_students, COUNT(*) as total_logins FROM LogActivity 
                                                                INNER JOIN Students ON LogActivity.UserID = Students.UserID
                                                                WHERE Students.ClassID = $classID 
                                                                AND LogActivity.ActivityDescription = 'User logged in'
                                                                AND MONTH(LogActivity.ActivityTimestamp) = MONTH(CURRENT_DATE())";
                                            $totalLoginsResult = mysqli_query($conn, $totalLoginsQuery);
                                            $totalLoginsRow = mysqli_fetch_assoc($totalLoginsResult);
                                            $totalLogins = $totalLoginsRow['total_logins'];
                                            $totalLogStudents = $totalLoginsRow['total_students'];

                                            $totalLoginsPerStudent = ($totalLogStudents > 0) ? round($totalLogins / $totalLogStudents, 2) : 0;

                                            echo "<tr>";
                                            echo "<td class='px-4 py-1 border-b'>$className</td>";
                                            echo "<td class='px-4 py-1 border-b'>$totalStudents student</td>";
                                            echo "<td class='px-4 py-1 border-b'>$totalLogStudents student</td>";
                                            echo "<td class='px-4 py-1 border-b'>$totalLoginsPerStudent log/$totalLogStudents student</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
                <div class="p-6 relative flex flex-col min-w-0 mb-4 lg:mb-0 break-words bg-gray-50  w-full shadow-lg rounded">
                    <div class="rounded-t mb-0 px-0 border-0">
                        <div class="flex flex-wrap items-center px-4 py-2">
                            <div class="relative w-full max-w-full flex-grow flex-1">
                                <h3 class="font-semibold text-base text-gray-900 ">Total Teachers</h3>
                            </div>
                        </div>
                        <div class="block w-full overflow-x-auto">
                            <div class="flex items-center p-4">
                                <i class="fas fa-chalkboard-teacher text-3xl text-blue-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Total Teachers</p>
                                    <p class="text-lg font-semibold"><?php echo getTotalTeachers($conn); ?></p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500">Subjects Taught and Classes</p>
                                <table class="min-w-full bg-transparent border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Teacher Name</th>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Subject Taught</th>
                                            <th class="px-4 bg-gray-100 text-gray-500 align-middle border border-solid border-gray-200 py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">Classes with Same Subjects</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $teacherQuery = "SELECT t.TeacherID, t.NIP, s.SubjectName, Fullname, GROUP_CONCAT(DISTINCT c.ClassCode SEPARATOR ', ') as ClassNames
                                                        FROM Teachers t
                                                        INNER JOIN Subjects s ON t.TeacherID = s.TeacherID
                                                        LEFT JOIN ClassSubjects cs ON s.SubjectID = cs.SubjectID
                                                        LEFT JOIN Classes c ON cs.ClassID = c.ClassID
                                                        LEFT JOIN Users u ON t.UserID = u.UserID
                                                        GROUP BY t.TeacherID, s.SubjectID";
                                        $teacherResult = mysqli_query($conn, $teacherQuery);

                                        while ($teacherRow = mysqli_fetch_assoc($teacherResult)) {
                                            $teacherNIP = $teacherRow['NIP'];
                                            $fullname = $teacherRow['Fullname'];
                                            $subjectName = $teacherRow['SubjectName'];
                                            $classNames = $teacherRow['ClassNames'];

                                            echo "<tr>";
                                            echo "<td class='px-4 py-1 border-b'>$fullname (NIP : $teacherNIP)</td>";
                                            echo "<td class='px-4 py-1 border-b'>$subjectName</td>";
                                            echo "<td class='px-4 py-1 border-b'>$classNames</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <!-- End Content -->
</main>
<?php include('../components/footer2.php'); ?>

