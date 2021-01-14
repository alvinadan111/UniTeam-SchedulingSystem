<?php
  session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="UI/login.css">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap CSS
        ============================================ -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  
</head>
<body>            
    <div class="container" >
        <form action="login.php" class="container2"  method="post">
            <h1> LOGIN </h1>
            <hr>
            <div class="logocontainer">
                <img src="UI/bulsulogo.png" alt="log" class="logo">
            </div>
            <input type="text"  placeholder="Enter ID Number" name="idNum" required>
            <input type="password" placeholder="Enter Password" name="pw" required>

            <?php if (isset($_GET['signIn'])) { if ($_GET['signIn']=="mismatch") { ?>
              <!-- Error Alert -->
              <div class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> &nbsp Username and password didn't match!
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>       
            <?php } } ?>

            <button type="submit" value="loginSubmit" class="loginbtn" name="login" default>Login</button>

           <p> <br>
            <a href="#" style="color:dodgerblue">Create New Account </a>
            <br><br>
           </p>
        </form>
    </div>
    <!-- bootstrap JS
        ============================================ -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>