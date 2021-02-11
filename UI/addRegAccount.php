<?php
session_start();


  if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;



require '../database.php';
$isRegistered = false;
$isDuplicated = false;
$isIncomplete = false;


?>

<!doctype html>
<html lang="en">
  <head>
     <title>Add Registrar's Account</title>
     <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="addAccount.css">
   
  </head>
  <body>
  <h1 class="h1"> Add Registrar's Account </h1> 
       <?php  
    if(isset($_POST['add'])){

    $pdo=Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $FN = $_POST['First_Name'];
    $MN = $_POST['Middle_Name'];
    $LN = $_POST['Last_Name'];
    $ID = $_POST['ID_Number'];
    $pw = $_POST['pas'];
    
    if(empty($_POST['departmentlist'])){
 
        $isIncomplete = true;
        if($isIncomplete == true){ ?>
           <!-- Warning Alert -->
            <div id="myAlertUF" class="alert alert-warning alert-dismissible fade show">
            <strong>Warning!</strong> &nbsp Some fields are left unfilled.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
    <?php }  
 
    }else{

        $dept = $_POST['departmentlist'];

        $q = $pdo->prepare("SELECT * FROM account where idNum = ? ");
        $q->execute(array($ID));
        $result = $q->rowCount();

        if($result > 0){
           $isDuplicated = true;
          if($isDuplicated == true){ ?>
        <!-- Error Alert -->
              <div id="myAlert" class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> &nbsp Duplicate Username.
                <button type="button"  class="close" data-dismiss="alert">&times;</button>
              </div>
    <?php }  
        }else{

            $stmt = $pdo->prepare("INSERT INTO account (FName, MName, LName, idNum, dept,  pw, accessLevel)
            VALUES (?,?,?,?,?,?,'reg')");
            $stmt->execute(array($FN,$MN,$LN,$ID,$dept,$pw));
            $isRegistered = true;
            if($isRegistered == true){ ?>
         <!-- Success Alert -->
                <div id="myAlert" class="alert alert-success alert-dismissible fade show">
                    <strong>Success!</strong> Ssuccessfully added.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
    <?php }  
            header("refresh:2; url = menu.php");
        }
    }
}

?>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="addRegAccount.php"> Add Registrar's Account </a>
    </div>
    <div class="container">
   
        <form class="container2" method = "POST">

            <table>

                <tr>
                    <td><input type="text" placeholder="First Name" name="First_Name" required></td>
                    <td><input type="text" placeholder="Middle Name" name="Middle_Name"></td>
                    <td><input type="text" placeholder="Last Name" name="Last Name" required></td>
                </tr>
                <tr>
                    <td colspan="3"><input type="text" placeholder="Username" name="ID_Number" required></td>
                </tr>
                <tr>
                    <td colspan="3"><input type="password" placeholder="Password" name="pas" required></td>
                </tr>
                <tr>
                    <td colspan="3"> 
                        <label for="department"> Department </label>
                        <select id="department" name="departmentlist">

                            <option value=" " selected disabled></option>
                                <?php
                                $pdo=Database::connect();
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT deptID, deptName FROM department");
                                while ($row = $stmt->fetch()) { ?>

                            <option value="<?php echo $row['deptID']; ?>"> <?php echo $row['deptName'];  ?> </option>
                                    
                            <?php }?>
                        </select>
                    </td>

                </tr>
                <tr>
                    <td colspan="3">
                        <button type="submit" name = "add" class="addbtn">Add Account</button>
                    </td>
                </tr>
            </table>
        </form>
    </div>


  <?php  Database::disconnect(); ?>  

        <!-- bootstrap JS-->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
     $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlert').hide('fade');
        }, 3000); 
     });

    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertUF').hide('fade');
        }, 3500); 

     });
    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertC').hide('fade');
        }, 3000); 

     });       





  </body>
</html>