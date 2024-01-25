<?php
// Initialize the session
session_start();
// Include the connection file
require_once('../../database/connection.php');

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

<?php include('../components/header.php'); ?>
<!-- Main Content Height Menyesuaikan Hasil Kurang dari Header dan Footer -->
<div class="h-screen flex flex-col">
    <!-- Top Navbar -->
    <?php include('../components/navbar.php'); ?>
    <!-- End Top Navbar -->
    <!-- Main Content -->
    <div class="bg-gray-50 flex flex-row shadow-md">
        <!-- Sidebar -->
        <?php include('../components/sidebar.php'); ?>
        <!-- End Sidebar -->
        <!-- Main Content -->
        <main class="flex flex-col flex-1 overflow-y-scroll h-screen flex-shrink-0 sc-hide pb-40">
            <div class="flex items-start justify-start p-6 shadow-md m-4 flex-col">
                <h1 class="text-3xl text-gray-800 font-semibold border-b border-gray-200 w-full">Dashboard</h1>
                <h2 class="text-xl text-gray-800 font-semibold">
                    Welcome back, <?php echo $_SESSION['FullName']; ?>!
                    <?php
                    if ($_SESSION['RoleID'] === 1) {
                        echo " (Admin)";
                    } elseif ($_SESSION['RoleID'] === 2) {
                        echo " (Student)";
                    } elseif ($_SESSION['RoleID'] === 3) {
                        echo " (Teacher)";
                    }
                    ?>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-4 mt-6 w-full">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6 w-full">
                        <!-- Total Users -->
                        <div class="p-4 bg-white shadow-md rounded-md">
                            <div class="flex items-center">
                                <i class="fas fa-users text-3xl text-gray-600 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Total Users</p>
                                    <p class="text-lg font-semibold"><?php echo getTotalUsers($conn); ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- Total Students -->
                        <div class="p-4 bg-white shadow-md rounded-md">
                            <div class="flex items-center">
                                <i class="fas fa-user-graduate text-3xl text-green-500 mr-2"></i>
                                <div>
                                    <p class="text-sm text-gray-500">Total Students</p>
                                    <p class="text-lg font-semibold"><?php echo getTotalStudents($conn); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-white shadow-md rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-school text-3xl text-indigo-500 mr-2"></i>
                            <div>
                                <p class="text-sm text-gray-500">Total Classes</p>
                                <p class="text-lg font-semibold"><?php echo getTotalClasses($conn); ?></p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Total Students per Class</p>
                            <table class="min-w-full border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="text-sm py-1 px-2 border-b">Class</th>
                                        <th class="text-sm py-1 px-2 border-b">Teacher</th>
                                        <th class="text-sm py-1 px-2 border-b">Total Students</th>
                                        <th class="text-sm py-1 px-2 border-b">Students Login</th>
                                        <th class="text-sm py-1 px-2 border-b">Log Logins</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch and display total students and logins per class
                                    $classQuery = "SELECT c.ClassID, c.ClassName, c.EducationLevel, c.HomeroomTeacher, c.Curriculum, c.AcademicYear, c.ClassCode, u.FullName AS TeacherName
                                        FROM Classes c
                                        JOIN Teachers t ON c.HomeroomTeacher = t.TeacherID
                                        JOIN Users u ON t.UserID = u.UserID";
                                    $classResult = mysqli_query($conn, $classQuery);

                                    while ($classRow = mysqli_fetch_assoc($classResult)) {
                                        $classID = $classRow['ClassID'];
                                        $className = $classRow['ClassCode'];
                                        $TeacherName = $classRow['TeacherName'];

                                        // Get total students
                                        $totalStudentsQuery = "SELECT COUNT(*) as total_students FROM Students WHERE ClassID = $classID";
                                        $totalStudentsResult = mysqli_query($conn, $totalStudentsQuery);
                                        $totalStudentsRow = mysqli_fetch_assoc($totalStudentsResult);
                                        $totalStudents = $totalStudentsRow['total_students'];

                                        // Get total logins this month for students in this class
                                        $totalLoginsQuery = "SELECT COUNT(DISTINCT LogActivity.UserID) as total_students, COUNT(*) as total_logins FROM LogActivity 
                                                            INNER JOIN Students ON LogActivity.UserID = Students.UserID
                                                            WHERE Students.ClassID = $classID 
                                                            AND LogActivity.ActivityDescription = 'User logged in'
                                                            AND MONTH(LogActivity.ActivityTimestamp) = MONTH(CURRENT_DATE())";
                                        $totalLoginsResult = mysqli_query($conn, $totalLoginsQuery);
                                        $totalLoginsRow = mysqli_fetch_assoc($totalLoginsResult);
                                        $totalLogins = $totalLoginsRow['total_logins'];
                                        $totalLogStudents = $totalLoginsRow['total_students'];

                                        // Calculate total logins per student
                                        $totalLoginsPerStudent = ($totalLogins > 0) ? round($totalLogins / $totalLogStudents, 2) : 0;

                                        echo "<tr>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$className</td>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$TeacherName</td>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$totalStudents student</td>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$totalLogStudents student</td>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$totalLoginsPerStudent log/$totalLogStudents student</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Total Teachers -->
                    <div class="p-4 bg-white shadow-md rounded-md">
                        <div class="flex items-center">
                            <i class="fas fa-chalkboard-teacher text-3xl text-blue-500 mr-2"></i>
                            <div>
                                <p class="text-sm text-gray-500">Total Teachers</p>
                                <p class="text-lg font-semibold"><?php echo getTotalTeachers($conn); ?></p>
                            </div>
                        </div>
                        <!-- Subjects Taught and Classes -->
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Subjects Taught and Classes</p>
                            <table class="min-w-full border border-gray-300">
                                <thead>
                                    <tr>
                                        <th class="text-sm py-1 px-2 border-b">Teacher Name</th>
                                        <th class="text-sm py-1 px-2 border-b">Subject Taught</th>
                                        <th class="text-sm py-1 px-2 border-b">Classes with Same Subjects</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch and display subjects taught by each teacher and corresponding classes
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
                                        echo "<td class='text-sm py-1 px-2 border-b'>$fullname (NIP : $teacherNIP)</td>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$subjectName</td>";
                                        echo "<td class='text-sm py-1 px-2 border-b'>$classNames</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Inside the main content section -->
        </main>
        <!-- End Main Content -->
    </div>
    <!-- End Main Content -->
    <!-- Footer -->
    <?php include('../components/footer.php'); ?>
    <!-- End Footer -->
</div>
<!-- End Main Content -->
</body>

</html>