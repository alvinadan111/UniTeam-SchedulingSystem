<?php session_start();
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://kit.fontawesome.com/a076d05399.js"></script>
  <link rel="stylesheet" href="menu.css">
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
          <a href="menu.html">Home</a>
          <a href="facultyloading.html">Faculty Loading </a>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Entry </button>
          <div class="dropdown-container">
            <a href="academic.html"> Academic Program </a>
            <a href="department.html"> Department </a>
            <a href="section.html"> Section </a>
            <a href="room.html"> Rooms </a>
            <a href="schoolyr.html"> School Year </a>
            <a href="addcourse.html"> Course </a>
          </div>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Curriculum Management </button>
          <div class="dropdown-container">
            <a href="curriculum.html"> Curriculum </a>
            <a href="coursescheduling.php"> Course Scheduling</a>
          </div>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Department Head </button>
          <div class="dropdown-container">
            <a href="add Account.html"> Add Department Head </a>
            <a href="listofinstructor.html"> View Department Head </a>
          </div>
          <button class="dropdown-btn"><i class="fa fa-caret-down"></i> Instructor </button>
          <div class="dropdown-container">
            <a href="add Account.html"> Add Instructor </a>
            <a href="listofinstructor.html"> View Instructor </a>
          </div>
          <a href="roomoccupied.html"> Rooms </a>
          <a href="changepassword.html"> Change Password </a>
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