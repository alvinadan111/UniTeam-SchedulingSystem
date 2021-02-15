<?php session_start();

  if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;
echo "session sctive year".$_SESSION['activeSchoolYear'];
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
  <link rel="stylesheet" href="menu.css">
  <title>Menu | Scheduling System</title>
</head>

<body>

  <div class="header">
    <div class="logocontainer">
      <img src="bulsulogo.png" alt="log" class="logo">
    </div>
    <h1 class="headname"> Bulacan State University (Sarmiento Campus)</h1>

  </div>

  <div class="topname">
    <i class="fas fa-user-circle"></i>
    <h1><?php echo "Hi ".$_SESSION["FName"]."!"; ?></h1>
    <h2> Online Class Scheduling Management System </h2>
  </div>
  <div class="row">
    <div class="leftcolumn">
      <div class="card">
        <div class="sidenav">
          <h1> MENU </h1>
          <a href="menu.php">Home</a>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Entry </button>
          <div class="dropdown-container">
            <a href="academic.php"> Academic Program </a>
            <a href="department.php"> Department </a>
            <a href="section.php"> Section </a>
            <a href="room.php"> Rooms </a>
            <a href="schoolyr.php"> School Year </a>
            <a href="addcourse.php"> Course </a>
          </div>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Curriculum Management </button>
          <div class="dropdown-container">
            <a href="curriculum.php"> Curriculum </a>
            <a href="coursescheduling.php"> Course Scheduling</a>
          </div>
          <a href="facultyloading.php">Faculty Loading </a>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Department Head </button>
          <div class="dropdown-container">
            <a href="addDeptHead.php"> Add Department Head </a>
            <a href="ListOfDeptHead.php"> View Department Head </a>
          </div>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Instructor </button>
          <div class="dropdown-container">
            <a href="addInstructor.php"> Add Instructor </a>
            <a href="ListOfInstructor.php"> View Instructor </a>
          </div>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Registrar</button>
          <div class="dropdown-container">
            <a href="addRegAccount.php"> Add Registrar </a>
            <a href="ListOfRegistrar.php"> View Registrar </a>
          </div>
          <a href="Schedules.php"> Schedules </a>
          <a href="roomoccupied.php"> Rooms </a>
          <a href="changepassword.php"> Change Password </a>
          <a href="../logout.php"> Logout </a>
        </div>
      </div>
    </div>
    <div class="rightcolumn">
      <div class="card">
        <img src="bulsu.jpg" width="460" height="345">
      </div>
    </div>
  </div>
  <script>
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
    var dropdown = document.getElementsByClassName("dropdown-btn");
    var i;

    for (i = 0; i < dropdown.length; i++) {
      dropdown[i].addEventListener("click", function () {
        this.classList.toggle("active");
        var dropdownContent = this.nextElementSibling;
        if (dropdownContent.style.display === "block") {
          dropdownContent.style.display = "none";
        } else {
          dropdownContent.style.display = "block";
        }
      });
    }
  </script>
</body>
</html>