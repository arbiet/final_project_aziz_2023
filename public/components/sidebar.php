<aside class="bg-gray-800 text-white w-64 overflow-y-scroll h-screen flex-shrink-0 sc-hide">
    <ul class="text-gray-400">
        <li class="px-6 py-4 hover:bg-gray-700 cursor-pointer space-x-2 flex items-center">
            <i class="fas fa-tachometer-alt mr-3"></i>
            <?php
            if (isset($_SESSION['RoleID'])) {
                $roleID = $_SESSION['RoleID'];

                if ($roleID == 1) {
                    // Admin
                    echo '<a href="../systems/dashboard_admin.php">Dashboard</a>';
                } elseif ($roleID == 2) {
                    // Teacher
                    echo '<a href="../systems/dashboard_teacher.php">Dashboard</a>';
                } elseif ($roleID == 3) {
                    // Student
                    echo '<a href="../systems/dashboard_student.php">Dashboard</a>';
                }
            }
            ?>
        </li>
        <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
            <i class="fas fa-user mr-3"></i>
            <a href="../profiles/profile.php">Profile</a>
        </li>
        <?php
        if ($_SESSION['RoleID'] === 1) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fas fa-user-cog mr-3"></i>
                <a href="../manage_users/manage_users_list.php">Manage Users</a>
            </li>
            ';
        }
        ?>
        <?php
        if ($_SESSION['RoleID'] === 1) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-door-closed mr-3"></i>
                <a href="../manage_classes/manage_classes_list.php">Manage Classes</a>
            </li>
            ';
        }
        ?>
        <?php
        if ($_SESSION['RoleID'] === 10) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-shield-halved mr-3"></i>
                <a href="../manage_roles/manage_roles_list.php">Manage Roles</a>
            </li>
            ';
        }
        ?>
        <?php
        if ($_SESSION['RoleID'] === 1) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-chalkboard-user mr-3"></i>
                <a href="../manage_teachers/manage_teachers_list.php">Manage Teacher</a>
            </li>
            ';
        }
        ?>
        <?php
            if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2) {
                // Check if the user is a teacher
                $userID = $_SESSION['UserID'];
                $isTeacher = false;

                // Query the Teachers table to check if the user is a teacher
                $query = "SELECT * FROM Teachers WHERE UserID = $userID";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $isTeacher = true;
                    $row = mysqli_fetch_assoc($result);
                    $teacherID = $row['TeacherID'];
                } 

                // Check if the user is a homeroom teacher
                $isHomeroomTeacher = false;

                // Query the Classes table to get the HomeroomTeacher using TeacherID
                $queryHomeroom = "SELECT HomeroomTeacher FROM Classes WHERE HomeroomTeacher = $teacherID";
                $resultHomeroom = mysqli_query($conn, $queryHomeroom);

                if ($resultHomeroom && mysqli_num_rows($resultHomeroom) > 0) {
                    $isHomeroomTeacher = true;

                    // Fetch additional information about the HomeroomTeacher if needed
                    $homeroomRow = mysqli_fetch_assoc($resultHomeroom);
                    $homeroomTeacherID = $homeroomRow['HomeroomTeacher'];

                    // You can use $homeroomTeacherID to retrieve additional information about the HomeroomTeacher
                    // For example, you can query the Users table to get the user details
                    $queryUser = "SELECT * FROM Users WHERE UserID = $homeroomTeacherID";
                    $resultUser = mysqli_query($conn, $queryUser);

                    if ($resultUser && mysqli_num_rows($resultUser) > 0) {
                        $homeroomTeacherInfo = mysqli_fetch_assoc($resultUser);
                        // Now you can use $homeroomTeacherInfo to display information about the HomeroomTeacher
                    }
                }

                // Display "Manage Users" only if the user is a teacher and a homeroom teacher
                if ($isHomeroomTeacher) {
                    echo '
                    <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                        <i class="fa-solid fa-users mr-3"></i>
                        <a href="../manage_students/manage_students_list.php">Manage Students</a>
                    </li>
                    ';
                }
            }
            ?>

        <?php
        if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-book mr-3"></i>
                <a href="../manage_subjects/manage_subjects_list.php">Manage Subjects</a>
            </li>
            ';
        }
        ?>
        <?php
        if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-book mr-3"></i>
                <a href="../manage_materials/manage_materials_list.php">Manage Materials</a>
            </li>
            ';
        }
        ?>
        <?php
        if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-comments mr-3"></i>
                <a href="../manage_exams/manage_exams_list.php">Manage Exams</a>
            </li>
            ';
        }
        ?>
        <?php
        if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2) {
            // Menu "Manage Users" hanya ditampilkan jika peran pengguna adalah "Admin"
            echo '
            <li class="px-6 py-4 hover-bg-gray-700 cursor-pointer space-x-2 flex items-center">
                <i class="fa-solid fa-comments mr-3"></i>
                <a href="../manage_assignments/manage_assignments_list.php">Manage Assignments</a>
            </li>
            ';
        }
        ?>

    </ul>
    <hr class="mt-60 border-transparent">
</aside>
<script>
    // Mendapatkan halaman saat ini
    var currentPage = window.location.href;

    // Mengambil semua tautan dalam daftar
    var links = document.querySelectorAll("aside ul li a");

    // Loop melalui tautan dan periksa jika URL cocok
    links.forEach(function(link) {
        var currentPathParts = currentPage.split("/");
        var linkPathParts = link.href.split("/");
        if (linkPathParts[linkPathParts.length - 2] === currentPathParts[currentPathParts.length - 2]) {
            if (currentPathParts[currentPathParts.length - 2] != "systems") {
                link.parentElement.classList.add("bg-gray-700");
            } else if (currentPathParts[currentPathParts.length - 2] == "systems" && link.href === currentPage) {
                link.parentElement.classList.add("bg-gray-700");
            }
        }
    });
</script>