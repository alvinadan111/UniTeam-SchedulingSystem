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
     <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap CSS -->
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="addAccount.css">
    <title>Add Registrar's Account</title>
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
    
    if(empty($_POST['departmentlist']) || empty($_POST['questions'])){
 
        $isIncomplete = true;
         
 
    }else{

        $dept = $_POST['departmentlist'];
        $secretQ = $_POST['questions'];
        $ans = $_POST['Answer'];

        $q = $pdo->prepare("SELECT * FROM account where idNum = ? ");
        $q->execute(array($ID));
        $result = $q->rowCount();

        if($result > 0){
           $isDuplicated = true;
          
        }else{

            $stmt = $pdo->prepare("INSERT INTO account (FName, MName, LName, idNum, dept,  pw, accessLevel, secretQuestion, answer)
            VALUES (?,?,?,?,?,?,'reg',?,?)");
            $stmt->execute(array($FN,$MN,$LN,$ID,$dept,$pw,$secretQ,$ans));
            $isRegistered = true;
            
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
                    <?php   if($isIncomplete == true){ ?>
               <!-- Warning Alert -->
                <div id="myAlertUF" class="alert alert-warning alert-dismissible fade show">
                <strong>Warning!</strong> &nbsp Some fields are left unfilled.
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
        <?php } ?>
        <?php if($isDuplicated == true){ ?>
            <!-- Error Alert -->
              <div id="myAlertC" class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> &nbsp Duplicate Username.
                <button type="button"  class="close" data-dismiss="alert">&times;</button>
              </div>
    <?php }   ?>
    <?php if($isRegistered == true){ ?>
                <!-- Success Alert -->
                <div id="myAlert" class="alert alert-success alert-dismissible fade show">
                    <strong>Success!</strong> Successfully added.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
    <?php }   ?>

                </tr>
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
                        <label for="question" title = "If you forget your password, We'll ask for your secret answer to verify your identity">Secret Question:</label>
                        <select name="questions">
                            <option value=" " selected disabled></option>
                            <option value="What is your childhood nickname?">What is your childhood nickname?</option>
                            <option value="What is the name of the first school you attended?">What is the name of the first school you attended?</option>
                            <option value="What is your first pet's name?">What is your first pet's name?</option>
                        </select>
                    </td>
                </tr>
                <tr><td colspan="3"><input type="text" placeholder="Answer" name="Answer" required></td></tr>


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
        }, 3000); 

     });
    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertC').hide('fade');
        }, 3000); 

     });       

    </script>



  </body>
</html>