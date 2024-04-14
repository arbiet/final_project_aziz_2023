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
        <div class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
            <div class="p-4 bg-white shadow-lg rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-school text-3xl text-indigo-500 mr-2"></i>
                    <div>
                        <p class="text-sm text-gray-500">Classes</p>
                    </div>
                </div>
                <div class="mt-4">
                    <?php
                    // Check if the user is a homeroom teacher
                    if (isset($_SESSION['HomeroomTeacher']) && $_SESSION['HomeroomTeacher'] !== null) {
                        ?>
                        <p class="text-sm text-gray-500">Total Students per Class</p>
                        <table class="min-w-full border border-gray-300 mt-4">
                            <thead>
                                <tr>
                                    <th class="py-1 px-2 border-b text-sm">Class</th>
                                    <th class="py-1 px-2 border-b text-sm">Student Number</th>
                                    <th class="py-1 px-2 border-b text-sm">Student</th>
                                    <th class="py-1 px-2 border-b text-sm">Log Logins / Month</th>
                                    <?php
                                    // Retrieve distinct subjects for the class
                                    $subjectsQuery = "SELECT DISTINCT sub.SubjectID, sub.SubjectName
                                        FROM Students s
                                        JOIN Classes c ON s.ClassID = c.ClassID
                                        JOIN ClassSubjects cs ON c.ClassID = cs.ClassID
                                        JOIN Subjects sub ON cs.SubjectID = sub.SubjectID
                                        WHERE c.HomeroomTeacher = {$_SESSION['Teacher']}";


                                    $subjectsResult = mysqli_query($conn, $subjectsQuery);

                                    // Display subject columns
                                    while ($subjectRow = mysqli_fetch_assoc($subjectsResult)) {
                                        echo "<th class='py-1 px-2 border-b text-sm'>{$subjectRow['SubjectName']}</th>";
                                    }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $studentsQuery = "SELECT s.UserID, s.StudentID, s.StudentNumber, u.FullName AS StudentName, c.ClassID, c.ClassCode, t.UserID AS TeacherUserID, t.TeacherID, u2.FullName AS TeacherName
                                                    FROM Students s
                                                    JOIN Classes c ON s.ClassID = c.ClassID
                                                    JOIN Teachers t ON c.HomeroomTeacher = t.TeacherID
                                                    JOIN Users u ON s.UserID = u.UserID
                                                    JOIN Users u2 ON t.UserID = u2.UserID
                                                    WHERE c.HomeroomTeacher = {$_SESSION['Teacher']}";

                                $studentsResult = mysqli_query($conn, $studentsQuery);

                                // Check if $studentsResult is empty
                                if (mysqli_num_rows($studentsResult) > 0) {
                                    while ($studentRow = mysqli_fetch_assoc($studentsResult)) {
                                        $classID = $studentRow['ClassID'];
                                        $className = $studentRow['ClassCode'];
                                        $teacherUserID = $studentRow['TeacherUserID'];
                                        $teacherID = $studentRow['TeacherID'];
                                        $teacherName = $studentRow['TeacherName'];
                                        $studentNumber = $studentRow['StudentNumber'];
                                        $studentName = $studentRow['StudentName'];

                                        // Fetch and display total logins per student in this class
                                        $totalLoginsQuery = "SELECT COUNT(DISTINCT la.UserID) AS total_logins
                                                            FROM LogActivity la
                                                            WHERE la.UserID = {$studentRow['UserID']}
                                                            AND la.ActivityDescription = 'User logged in'
                                                            AND MONTH(la.ActivityTimestamp) = MONTH(CURRENT_DATE)";

                                        $totalLoginsResult = mysqli_query($conn, $totalLoginsQuery);
                                        $totalLoginsRow = mysqli_fetch_assoc($totalLoginsResult);
                                        $totalLogins = $totalLoginsRow['total_logins'];

                                        echo "<tr>";
                                        echo "<td class='py-1 px-2 border-b text-sm'>$className</td>";
                                        echo "<td class='py-1 px-2 border-b text-sm'>$studentNumber</td>";
                                        echo "<td class='py-1 px-2 border-b text-sm'>$studentName</td>";
                                        echo "<td class='py-1 px-2 border-b text-sm'>$totalLogins logins</td>";

                                        // Display grades for each subject
                                        $subjectsResult = mysqli_query($conn, $subjectsQuery);
                                        while ($subjectRow = mysqli_fetch_assoc($subjectsResult)) {
                                            $subjectID = $subjectRow['SubjectID'];

                                            // Calculate average grade per subject
                                            $averageGradeQuery = "SELECT AVG(ASub.Grade) AS average_grade
                                                FROM AssignmentSubmissions AS ASub
                                                JOIN Subjects AS S ON S.SubjectID = $subjectID
                                                JOIN ClassSubjects AS CS ON S.SubjectID = CS.SubjectID
                                                WHERE ASub.StudentID = {$studentRow['StudentID']}
                                                AND CS.ClassID = {$studentRow['ClassID']}";

                                            $averageGradeResult = mysqli_query($conn, $averageGradeQuery);
                                            $averageGradeRow = mysqli_fetch_assoc($averageGradeResult);
                                            $averageGrade = $averageGradeRow['average_grade'];
                                            if($averageGrade == NULL) {
                                                $averageGrade = 0;
                                            }

                                            echo "<td class='py-1 px-2 border-b text-sm'>$averageGrade</td>";
                                        }

                                        echo "</tr>";
                                    }
                                } else {
                                    // Display a message if there are no students in this class
                                    echo "<tr><td colspan='5' class='py-2 px-4 text-center'>No students in this class.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    <?php
                    } else {
                        // Display a message if the user is not a homeroom teacher
                        echo "<p class='text-sm text-gray-500'>You are not a homeroom teacher.</p>";
                    }
                    ?>
                </div>
            </div>
            <div class="p-4 bg-white shadow-lg rounded-md">
                <div class="flex items-center">
                    <i class="fas fa-book text-3xl text-indigo-500 mr-2"></i>
                    <div>
                        <p class="text-sm text-gray-500">Subject Result</p>
                    </div>
                </div>
                <div class="mt-4">
                    <label for="subjectFilter" class="text-sm text-gray-500">Filter by Subject:</label>
                    <select id="subjectFilter" name="subjectFilter" class="ml-2 p-2 border border-gray-300">
                        <?php
                        // Fetch subjects based on TeacherID
                        $teacherID = $_SESSION['Teacher'];
                        $subjectsQuery = "SELECT * FROM Subjects WHERE TeacherID = $teacherID";
                        $subjectsResult = mysqli_query($conn, $subjectsQuery);

                        while ($subjectRow = mysqli_fetch_assoc($subjectsResult)) {
                            $subjectID = $subjectRow['SubjectID'];
                            $subjectName = $subjectRow['SubjectName'];

                            echo "<option value='$subjectID'>$subjectName</option>";
                        }
                        ?>
                    </select>

                    <!-- JavaScript to handle subject filter -->
                    <script>
                        document.getElementById('subjectFilter').addEventListener('change', function() {
                            // Reload the page with the selected subject as a parameter
                            window.location.href = '?subject=' + this.value;
                        });

                        // Set the selected option based on the URL parameter
                        const urlParams = new URLSearchParams(window.location.search);
                        const selectedSubject = urlParams.get('subject');
                        if (selectedSubject) {
                            document.getElementById('subjectFilter').value = selectedSubject;
                        }
                    </script>

                    <?php
                    // Check if a subject filter is set
                    if (isset($_GET['subject'])) {
                        $selectedSubjectID = $_GET['subject'];

                        // Fetch Assignment Titles for the selected subject
                        $assignmentTitlesQuery = "SELECT DISTINCT a.Title, a.AssignmentID
                                                    FROM Assignments a
                                                    WHERE a.SubjectID = $selectedSubjectID";
                        $assignmentTitlesResult = mysqli_query($conn, $assignmentTitlesQuery);
                        ?>
                        <label for="assignmentTitleFilter" class="text-sm text-gray-500">Filter by Assignment Title:</label>
                        <select id="assignmentTitleFilter" name="assignmentTitleFilter" class="ml-2 p-2 border border-gray-300">
                            <?php
                            while ($assignmentTitleRow = mysqli_fetch_assoc($assignmentTitlesResult)) {
                                $assignmentTitleID = $assignmentTitleRow['AssignmentID'];
                                $assignmentTitle = $assignmentTitleRow['Title'];

                                echo "<option value='$assignmentTitleID'>$assignmentTitle</option>";
                            }
                            ?>
                        </select>

                        <!-- JavaScript to handle assignment title filter -->
                        <script>
                            document.getElementById('assignmentTitleFilter').addEventListener('change', function() {
                                // Reload the page with the selected assignment title as a parameter
                                window.location.href = `?subject=${selectedSubject}&assignment=${this.value}`;
                            });

                            // Set the selected option based on the URL parameter
                            const selectedAssignmentTitle = urlParams.get('assignment');
                            if (selectedAssignmentTitle) {
                                document.getElementById('assignmentTitleFilter').value = selectedAssignmentTitle;
                            }
                        </script>

                        <?php
                        // Fetch classes based on the selected subject
                        $classesQuery = "SELECT c.ClassID, c.ClassCode
                                            FROM Classes c
                                            JOIN ClassSubjects cs ON c.ClassID = cs.ClassID
                                            WHERE cs.SubjectID = $selectedSubjectID";
                        $classesResult = mysqli_query($conn, $classesQuery);
                        ?>
                        <label for="classFilter" class="text-sm text-gray-500">Filter by Class:</label>
                        <select id="classFilter" name="classFilter" class="ml-2 p-2 border border-gray-300">
                            <?php
                            while ($classRow = mysqli_fetch_assoc($classesResult)) {
                                $classID = $classRow['ClassID'];
                                $classCode = $classRow['ClassCode'];

                                echo "<option value='$classID'>$classCode</option>";
                            }
                            ?>
                        </select>

                        <!-- JavaScript to handle class filter -->
                        <script>
                            document.getElementById('classFilter').addEventListener('change', function() {
                                // Reload the page with the selected class as a parameter
                                window.location.href = `?subject=${selectedSubject}&assignment=${selectedAssignmentTitle}&class=${this.value}`;
                            });

                            // Set the selected option based on the URL parameter
                            const selectedClass = urlParams.get('class');
                            if (selectedClass) {
                                document.getElementById('classFilter').value = selectedClass;
                            }
                        </script>

                        <?php
                        // Check if a class filter is set
                        if (isset($_GET['assignment']) && isset($_GET['class'])) {
                            $selectedAssignmentID = $_GET['assignment'];
                            $selectedClassID = $_GET['class'];

                            // Fetch assignments and submissions for the selected subject, assignment title, and class
                            $assignmentsQuery = "SELECT s.StudentID, u.FullName AS StudentName, a.Title, asub.Grade, asub.TeacherFeedback
                                                    FROM Students s
                                                    LEFT JOIN AssignmentSubmissions asub ON s.StudentID = asub.StudentID AND asub.AssignmentID = $selectedAssignmentID
                                                    LEFT JOIN Assignments a ON asub.AssignmentID = a.AssignmentID AND a.SubjectID = $selectedSubjectID
                                                    JOIN Users u ON s.UserID = u.UserID
                                                    WHERE s.ClassID = $selectedClassID";

                            $assignmentsResult = mysqli_query($conn, $assignmentsQuery);
                            ?>
                            <table class="min-w-full border border-gray-300 mt-4">
                                <thead>
                                    <tr>
                                        <th class="py-1 px-2 border-b">Student</th>
                                        <th class="py-1 px-2 border-b">Grade</th>
                                        <th class="py-1 px-2 border-b">Teacher Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($assignmentRow = mysqli_fetch_assoc($assignmentsResult)) {
                                        $studentName = $assignmentRow['StudentName'];
                                        $assignmentTitle = $assignmentRow['Title'];
                                        $assignmentTeacherFeedback = $assignmentRow['TeacherFeedback'];

                                        // Check if the student has submitted an assignment
                                        if ($assignmentRow['Grade'] === null && $assignmentRow['Title'] !== null) {
                                            // Student has not submitted the assignment
                                            $grade = 'No Grade';
                                        } else {
                                            // Student has submitted the assignment, check if there is a grade
                                            $grade = ($assignmentRow['Grade'] !== null) ? $assignmentRow['Grade'] : 'Not Submitted';
                                        }

                                        echo "<tr>";
                                        echo "<td class='py-1 px-2 border-b'>$studentName</td>";
                                        echo "<td class='py-1 px-2 border-b'>$grade</td>";
                                        echo "<td class='py-1 px-2 border-b'>$assignmentTeacherFeedback</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <?php
                        } else {
                            // Display a message to select an assignment title and class
                            echo "<p class='text-sm text-gray-500 mt-4'>Please select an assignment title and class from the dropdowns above.</p>";
                        }
                    } else {
                        // Display a message to select a subject
                        echo "<p class='text-sm text-gray-500 mt-4'>Please select a subject from the dropdown above.</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
      <!-- End Content -->
</main>
<?php include('../components/footer2.php'); ?>

