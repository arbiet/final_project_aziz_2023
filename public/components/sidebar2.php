    <!--sidenav -->
    <div class="fixed left-0 top-0 w-64 h-full bg-[#f8f4f3] p-4 z-50 sidebar-menu transition-transform">
        <a href="#" class="flex items-end border-b border-b-gray-800 pb-4">
            <img src="../static/image/icon.png" alt="Icon" class="w-8 h-8 mr-2">
            <h2 class="font-bold text-2xl">E<span class="bg-[#f84525] text-white px-2 rounded-md">SAY</span></h2>
            <span class="text-xs text-gray-500 ml-1">.beta</span>
        </a>
        <ul class="mt-4">
            <span class="text-gray-400 font-bold">Admin</span>
            <li class="mb-1 group">
            <?php
            if (isset($_SESSION['RoleID'])) {
                $roleID = $_SESSION['RoleID'];
                $dashboardLink = '';
                
                switch ($roleID) {
                    case 1: // Admin
                        $dashboardLink = '../systems/dashboard_admin.php';
                        break;
                    case 2: // Teacher
                        $dashboardLink = '../systems/dashboard_teacher.php';
                        break;
                    case 3: // Student
                        $dashboardLink = '../systems/dashboard_student.php';
                        break;
                    default:
                        // handle other roles if needed
                        break;
                }
                
                if (!empty($dashboardLink)) {
                    echo '<a href="' . $dashboardLink . '" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">';
                    echo '<i class="ri-home-2-line mr-3 text-lg"></i>';
                    echo '<span class="text-sm">Dashboard</span>';
                    echo '</a>';
                }
            }
            ?>
            </li>
            <li class="mb-1 group">
                <a href="../profiles/profile.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class="fas fa-user mr-3"></i>                
                    <span class="text-sm">Profile</span>
                </a>
            </li>
            <span class="text-gray-400 font-bold">Manage</span>
            <?php if ($_SESSION['RoleID'] === 1): ?>
            <li class="mb-1 group">
                <a href="../manage_users/manage_users_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class="fas fa-user-cog mr-3"></i>                
                    <span class="text-sm">Users</span>
                </a>
            </li>
            <li class="mb-1 group">
                <a href="../manage_classes/manage_classes_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class="fa-solid fa-door-closed mr-3"></i>                
                    <span class="text-sm">Classes</span>
                </a>
            </li>
            <li class="mb-1 group">
                <a href="../manage_teachers/manage_teachers_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class="fa-solid fa-chalkboard-user mr-3"></i>                
                    <span class="text-sm">Teacher</span>
                </a>
            </li>
            <?php endif; ?>
            <?php
            if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2) {
                // Check if the user is a teacher
                $userID = $_SESSION['UserID'];
                $isTeacher = false;

                // Query the Teachers table to check if the user is a teacher
                $query = "SELECT * FROM Teachers WHERE UserID = $userID";
                $result = mysqli_query($conn, $query);
                $teacherID = NULL;
                if ($result && mysqli_num_rows($result) > 0) {
                    $isTeacher = true;
                    $row = mysqli_fetch_assoc($result);
                    $teacherID = $row['TeacherID'];
                } 

                // Check if the user is a homeroom teacher
                $isHomeroomTeacher = false;

                if ($teacherID != NULL) {
                    $_SESSION['Teacher'] = $teacherID;
                    // Query the Classes table to get the HomeroomTeacher using TeacherID
                    $queryHomeroom = "SELECT HomeroomTeacher, ClassID FROM Classes WHERE HomeroomTeacher = $teacherID";
                    $resultHomeroom = mysqli_query($conn, $queryHomeroom);

                    if ($resultHomeroom && mysqli_num_rows($resultHomeroom) > 0) {
                        $isHomeroomTeacher = true;

                        // Fetch additional information about the HomeroomTeacher if needed
                        $homeroomRow = mysqli_fetch_assoc($resultHomeroom);
                        $homeroomTeacherID = $homeroomRow['HomeroomTeacher'];
                        $KelaseDewe = $homeroomRow['ClassID'];
                        $_SESSION['HomeroomTeacher'] = $homeroomTeacherID;

                        // You can use $homeroomTeacherID to retrieve additional information about the HomeroomTeacher
                        // For example, you can query the Users table to get the user details
                        $queryUser = "SELECT * FROM Users WHERE UserID = $homeroomTeacherID";
                        $resultUser = mysqli_query($conn, $queryUser);

                        if ($resultUser && mysqli_num_rows($resultUser) > 0) {
                            $homeroomTeacherInfo = mysqli_fetch_assoc($resultUser);
                            // Now you can use $homeroomTeacherInfo to display information about the HomeroomTeacher
                        }
                    }
                }

                // Display "Manage Students" only if the user is a teacher and a homeroom teacher
                if ($isHomeroomTeacher OR $_SESSION['RoleID'] === 1) {
                    echo '
                    <li class="mb-1 group">
                        <a href="../manage_students/manage_students_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                            <i class="fa-solid fa-users mr-3"></i>                
                            <span class="text-sm">Students</span>
                        </a>
                    </li>
                    ';
                }
            }
            ?>
            <span class="text-gray-400 font-bold">Learning</span>
            <?php if ($_SESSION['RoleID'] === 1 or $_SESSION['RoleID'] === 2): ?>
                <li class="mb-1 group">
                    <a href="../manage_subjects/manage_subjects_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                        <i class="fa-solid fa-book mr-3"></i>
                        <span class="text-sm">Subjects</span>
                    </a>
                </li>
                <li class="mb-1 group">
                    <a href="../manage_materials/manage_materials_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                        <i class="fa-solid fa-book mr-3"></i>
                        <span class="text-sm">Materials</span>
                    </a>
                </li>
                <!-- Uncomment the block below if needed -->
                <!--
                <li class="mb-1 group">
                    <a href="../manage_exams/manage_exams_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                        <i class="fa-solid fa-comments mr-3"></i>
                        <span class="text-sm">Manage Exams</span>
                    </a>
                </li>
                -->
                <li class="mb-1 group">
                    <a href="../manage_assignments/manage_assignments_list.php" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                        <i class="fa-solid fa-comments mr-3"></i>
                        <span class="text-sm">Assignments</span>
                    </a>
                </li>
            <?php endif; ?>
            <span class="text-gray-400 font-bold">Personal</span>
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class='bx bx-bell mr-3 text-lg' ></i>                
                    <span class="text-sm">Notifications</span>
                    <span class=" md:block px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-red-600 bg-red-200 rounded-full">5</span>
                </a>
            </li>
            <li class="mb-1 group">
                <a href="" class="flex font-semibold items-center py-2 px-4 text-gray-900 hover:bg-gray-950 hover:text-gray-100 rounded-md group-[.active]:bg-gray-800 group-[.active]:text-white group-[.selected]:bg-gray-950 group-[.selected]:text-gray-100">
                    <i class='bx bx-envelope mr-3 text-lg' ></i>                
                    <span class="text-sm">Messages</span>
                    <span class=" md:block px-2 py-0.5 ml-auto text-xs font-medium tracking-wide text-green-600 bg-green-200 rounded-full">2 New</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay"></div>
    <!-- end sidenav -->
    