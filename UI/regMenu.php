<?php session_start();

  if(empty($_SESSION['accountIDreg'])):
header('Location:../index.php');
endif;

?>


<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="Student.css">
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
      </div>
    <div class="row">
        <div class="leftcolumn">
            <div class="card">
                <div class="sidenav">
                <a onclick="openPage('menu', this)" id="defaultOpen" > <h2 style="font-size:larger;"> MENU </h2> </a>
                <a href="addcourse.php"> Course </a>
                <a href="changepasswordreg.php"> Change Password </a>
                <a href="../logout.php"> Logout </a>
            </div>
            </div>
        </div>
        <div id="menu" class="tabcontent">
        <div class="rightcolumn">
            <div class="card">
                <img src="bulsu.jpg" width="460" height="345">
            </div>
        </div>
        </div>
    

   
 </body>     
       <script >
    function openPage(pageName, elmnt,) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        document.getElementById(pageName).style.display = "contents";
        elmnt.style.backgroundColor = color;
    }
    document.getElementById("defaultOpen").click();
</script>

</html>