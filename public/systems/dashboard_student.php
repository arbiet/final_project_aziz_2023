<?php
require_once('../../database/connection.php');
include('../components/header.php');
session_start();

if (isset($_SESSION['UserID'])) {
  $userID = $_SESSION['UserID'];
  $query = "SELECT u.*, s.*, c.* 
            FROM Users u
            JOIN Students s ON u.UserID = s.UserID
            JOIN Classes c ON s.ClassID = c.ClassID
            WHERE u.UserID = $userID";
  $result = mysqli_query($conn, $query);

  if ($result && mysqli_num_rows($result) > 0) {
    $studentData = mysqli_fetch_assoc($result);
    $_SESSION['StudentID'] = $studentData['StudentID'];
    // Ambil data kelas dan mata pelajaran siswa
    $classID = $studentData['ClassID'];
    $classQuery = "SELECT cs.ClassID, s.SubjectID, s.SubjectName, s.DifficultyLevel, s.TeachingMethod, s.LearningObjective, t.TeacherID, t.NIP, t.AcademicDegree, u.FullName, COUNT(m.MaterialID) as TotalMaterials
               FROM ClassSubjects cs
               JOIN Subjects s ON cs.SubjectID = s.SubjectID
               LEFT JOIN Teachers t ON s.TeacherID = t.TeacherID
               LEFT JOIN Users u ON t.UserID = u.UserID
               LEFT JOIN Materials m ON s.SubjectID = m.SubjectID
               WHERE cs.ClassID = $classID
               GROUP BY cs.ClassID, s.SubjectID";

    $classResult = mysqli_query($conn, $classQuery);
    $classSubjects = array();

    if ($classResult && mysqli_num_rows($classResult) > 0) {
      while ($row = mysqli_fetch_assoc($classResult)) {
        $classSubjects[] = $row;
      }
    }
  }
}

?>

<body class="overflow-hidden">
  <!-- Main Content Height Menyesuaikan Hasil Kurang dari Header dan Footer -->
  <div class="h-screen flex flex-col overflow-y-scroll flex-shrink-0 sc-hide">
    <!-- Navbar -->
    <header class="bg-blue-600 p-4 text-white">
      <nav class="container mx-auto flex justify-between items-center">
        <h1 class="text-2xl font-bold">Dashboard Siswa</h1>
        <a href="javascript:void(0);" onclick="confirmLogout()" class="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-gray-500 hover:bg-white mt-4 lg:mt-0">Logout</a>
      </nav>
    </header>
    <div class="bg-white p-6 shadow-lg mb-4 relative container mx-auto mt-8 rounded-lg border border-gray-200">
      <a href="#" class="absolute top-0 right-0 mt-4 mr-4 text-blue-500 hover:text-blue-700">
        <i class="fas fa-edit text-xl"></i>
      </a>
      <!-- Badge Identifikasi -->
      <span class="bg-green-400 text-white text-xs px-2 py-1 rounded-full absolute -top-2 -left-2">Active</span>
      <div class="flex items-center">
        <div class="w-20 h-20 bg-gray-300 rounded-full overflow-hidden">
          <img src="../static/image/profile/<?php echo $studentData['ProfilePictureURL']; ?>" alt="Profil Siswa" class="w-full h-full object-cover" />
        </div>
        <div class="ml-4">
          <h3 class="text-2xl font-semibold"><?php echo $studentData['FullName']; ?></h3>
          <p class="text-gray-500">Class : <?php echo $studentData['ClassName']; ?></p>
          <p class="text-gray-500">NISN: <?php echo $studentData['StudentNumber']; ?></p>
          <p class="text-gray-500">Email: <?php echo $studentData['Email']; ?></p>
          <p class="text-gray-500">Kelas: <?php echo $studentData['ClassName']; ?></p>
          <a href="detail_kelas.php?class_id=<?php echo $studentData['ClassID']; ?>" class="text-blue-500 hover:underline">Lihat Kelas</a>

        </div>
      </div>
    </div>
    <!-- Main Content -->
    <main class="container mx-auto mt-4 p-4">
      <h2 class="text-2xl font-semibold mb-4">Daftar Mata Pelajaran</h2>
      <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-4">
        <?php foreach ($classSubjects as $subject) { ?>
          <div class="bg-white rounded p-4 shadow relative shadow:md">
            <!-- Tombol titik tiga (ellipsis) di pojok kanan atas -->
            <div class="absolute top-0 right-0 mt-2 mr-2">
              <button class="text-gray-600 hover:text-gray-700 rounded-full p-2">
                <i class="fas fa-ellipsis-v"></i>
              </button>
            </div>
            <h3 class="text-lg font-semibold"><?php echo $subject['SubjectName']; ?></h3>
            <!-- Informasi guru -->
            <?php if (isset($subject['TeacherID'])) { ?>
              <p class="text-gray-500">NIP: <?php echo $subject['NIP']; ?></p>
              <p class="text-gray-500">Nama Guru: <?php echo $subject['FullName'] . ", " . $subject['AcademicDegree']; ?></p>
            <?php } else { ?>
              <p class="text-gray-500">Guru: Belum ditentukan</p>
            <?php } ?>
            <!-- Informasi tambahan tentang mata pelajaran -->
            <p class="text-gray-500">Kesulitan: <?php echo $subject['DifficultyLevel']; ?></p>
            <p class="text-gray-500">Metode Pengajaran: <?php echo $subject['TeachingMethod']; ?></p>
            <div class="mt-2 flex justify-between items-center">
              <a href="../subjects/subjects_detail.php?subject_id=<?php echo $subject['SubjectID']; ?>&material=start" class="text-blue-500 hover:underline">Lihat Materi</a>
              <span class="text-blue-500 font-semibold"><?php echo $subject['TotalMaterials']; ?> Materi <i class="fas fa-file-alt mr-2"></i></span>
            </div>
            <div class="mt-4 mb-4">
              <label for="progress-<?php echo $subject['SubjectName']; ?>" class="text-sm font-semibold block">Progress:</label>
              <div class="relative pt-1">
                <div class="flex mb-2 items-center justify-between">
                  <div>
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full bg-green-200 text-green-600">
                      50%
                    </span>
                  </div>
                  <div class="text-right">
                    <span class="text-xs font-semibold inline-block text-green-600">
                      100%
                    </span>
                  </div>
                </div>
                <div class="relative w-full bg-gray-200 rounded-full">
                  <div class="absolute left-0 bg-green-600 py-1 rounded-full" style="width: 50%"></div>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </main>

    <!-- Footer -->
    <footer class="fixed bottom-0 w-full p-4 text-gray-600 bg-white">
      <div class="container mx-auto text-center">
        &copy; <?php echo date('Y'); ?> <?php echo $baseTitle; ?> - All Rights Reserved
      </div>
    </footer>
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