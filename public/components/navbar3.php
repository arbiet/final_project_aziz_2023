<header class="bg-emerald-600 text-white fixed w-full z-30">
      <nav class="container mx-auto flex justify-between items-center">
        <a href="#" class="flex items-end">
            <img src="../static/image/icon.png" alt="Icon" class="w-8 h-8 mr-2">
            <h2 class="font-bold text-2xl">E<span class="bg-[#f84525] text-white px-2 rounded-md">SAY</span></h2>
            <span class="text-xs text-white ml-1">.beta</span>
        </a>
        <div class="flex row">
          <?php
            // Data object untuk menyimpan informasi tutorial untuk setiap jenis pengguna
            $user_tutorials = array(
                "user_type1" => array(
                    array("name" => "Tutorial 1 for User Type 1", "description" => "Description of Tutorial 1", "file" => "tutorial1_user_type1.pdf", "image" => "https://placehold.co/32x32"),
                    array("name" => "Tutorial 2 for User Type 1", "description" => "Description of Tutorial 2", "file" => "tutorial2_user_type1.pdf", "image" => "https://placehold.co/32x32")
                )
            );
        ?>

        <div class="dropdown">
            <button type="button" class="dropdown-toggle text-white m-4 w-8 h-8 rounded flex items-center justify-center hover:text-white">
                <i class="fa-regular fa-circle-question text-xl text-white mr-2"></i>
            </button>
            <div id="dropdown-menu-1" class="dropdown-menu absolute right-0 shadow-lg shadow-black/5 z-30 hidden max-w-xs w-full bg-white rounded-md border border-gray-100">
                <div class="flex items-center px-4 pt-4 border-b border-b-gray-100 notification-tab">
                    <?php foreach ($user_tutorials as $key => $tutorials): ?>
                        <button type="button" data-tab="notification" data-tab-page="<?php echo $key; ?>" class="text-gray-400 font-medium text-[13px] hover:text-gray-600 border-b-2 border-b-transparent mr-4 pb-1"><?php echo ucfirst($key); ?></button>
                    <?php endforeach; ?>
                </div>
                <?php foreach ($user_tutorials as $key => $tutorials): ?>
                    <div class="my-2">
                        <ul class="max-h-64 overflow-y-auto" data-tab-for="notification" data-page="<?php echo $key; ?>">
                            <?php foreach ($tutorials as $tutorial): ?>
                                <li>
                                    <a href="<?php echo $tutorial['file']; ?>" target="_blank" class="py-2 px-4 flex items-center hover:bg-gray-50 group">
                                        <div class="ml-2 flex items-center">
                                            <img src="<?php echo $tutorial['image']; ?>" alt="" class="w-8 h-8 mr-2 rounded block object-cover align-middle">
                                            <div class="flex flex-col">
                                                <div class="text-[13px] text-gray-600 font-medium truncate group-hover:text-blue-500"><?php echo $tutorial['name']; ?></div>
                                                <div class="text-[11px] text-gray-400"><?php echo $tutorial['description']; ?></div>
                                            </div>
                                            <!-- Icon for download -->
                                            <a href="<?php echo $tutorial['file']; ?>" target="_blank" download class="ml-2 flex items-center">
                                                <i class="fas fa-download ml-2 text-gray-400 hover:text-gray-600"></i>
                                            </a>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button id="fullscreen-button" class="py-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" class="hover:bg-emerald-700 rounded-full" viewBox="0 0 24 24" style="fill: white;transform: ;msFilter:;"><path d="M5 5h5V3H3v7h2zm5 14H5v-5H3v7h7zm11-5h-2v5h-5v2h7zm-2-4h2V3h-7v2h5z"></path></svg>
        </button>

        <div class="dropdown ml-3">
            <button type="button" class="dropdown-toggle flex items-center" aria-haspopup="true" aria-expanded="false">
                <div class="h-8 w-8 relative bg-white rounded-full">
                <img class="w-8 h-8 rounded-full" src="<?php echo '../static/image/profile/'.$_SESSION['ProfilePictureURL']; ?>" alt="Logo"/>
                </div>
                <div class="p-2 md:block text-left">
                    <h2 class="text-sm font-semibold text-white"><?php echo $_SESSION['FullName']; ?></h2>
                    <p class="text-xs text-white">
                        <?php
                        if ($_SESSION['RoleID'] === 1) {
                            echo " (Admin)";
                        } elseif ($_SESSION['RoleID'] === 2) {
                            echo " (Teacher)";
                        } elseif ($_SESSION['RoleID'] === 3) {
                            echo " (Student)";
                        }
                        ?>
                    </p>
                </div>
            </button>
            <ul id="dropdown-menu-2" class="dropdown-menu shadow-lg absolute shadow-black/5 z-30 hidden py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-[140px]">
                <li>
                    <a href="<?php echo $baseUrl; ?>public/systems/dashboard_student.php" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#f84525] hover:bg-gray-50">Dashboard</a>
                </li>
                <li>
                    <a href="<?php echo $baseUrl; ?>public/profiles/profile_student.php" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#f84525] hover:bg-gray-50">Profile</a>
                </li>
                 <li>
                    <a href="<?php echo $baseUrl; ?>public/class/class_detail.php?class_id=<?php echo $_SESSION['ClassID']; ?>" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#f84525] hover:bg-gray-50">My Class</a>
                </li>
                <li>
                    <?php
                    if (isset($_SESSION['UserID'])) {
                        // Jika pengguna sudah login, tampilkan tombol Logout
                        echo '<a href="javascript:void(0);" onclick="confirmLogout()" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#f84525] hover:bg-gray-50">Logout</a>';
                    } else {
                        // Jika pengguna belum login, tampilkan tombol Login
                        echo '<a href="login.php" class="flex items-center text-[13px] py-1.5 px-4 text-gray-600 hover:text-[#f84525] hover:bg-gray-50">Login</a>';
                    }
                    ?>
                </li>
            </ul>
        </div>

        <script>
          const fullscreenButton = document.getElementById('fullscreen-button');
                
            fullscreenButton.addEventListener('click', toggleFullscreen);
                
            function toggleFullscreen() {
                if (document.fullscreenElement) {
                    // If already in fullscreen, exit fullscreen
                    document.exitFullscreen();
                } else {
                    // If not in fullscreen, request fullscreen
                    document.documentElement.requestFullscreen();
                }
            }
            // Tambahkan script berikut untuk mengatur perilaku dropdown
            document.addEventListener("DOMContentLoaded", function() {
                const dropdownToggle1 = document.querySelector('.dropdown-toggle');
                const dropdownMenu1 = document.querySelector('#dropdown-menu-1');
                const dropdownToggle2 = document.querySelectorAll('.dropdown-toggle')[1];
                const dropdownMenu2 = document.querySelector('#dropdown-menu-2');

                dropdownToggle1.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                    this.setAttribute('aria-expanded', !expanded);
                    dropdownMenu1.classList.toggle('hidden');
                });

                dropdownToggle2.addEventListener('click', function() {
                    const expanded = this.getAttribute('aria-expanded') === 'true' || false;
                    this.setAttribute('aria-expanded', !expanded);
                    dropdownMenu2.classList.toggle('hidden');
                });

                // Tutup dropdown saat klik di luar area dropdown
                document.addEventListener('click', function(event) {
                    if (!dropdownToggle1.contains(event.target)) {
                        dropdownMenu1.classList.add('hidden');
                        dropdownToggle1.setAttribute('aria-expanded', false);
                    }
                    if (!dropdownToggle2.contains(event.target)) {
                        dropdownMenu2.classList.add('hidden');
                        dropdownToggle2.setAttribute('aria-expanded', false);
                    }
                });
            });
        </script>
        </div>
      </nav>
    </header>