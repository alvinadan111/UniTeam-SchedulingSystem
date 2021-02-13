<?php
session_start();

  if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;  


require '../database.php';
$added1 = false;
$duplicate1 = false;
$incomplete1 = false;

if(isset($_POST['add'])){

    $pdo=Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $FN = $_POST['First_Name'];
    $MN = $_POST['Middle_Name'];
    $LN = $_POST['Last_Name'];
    $ID = $_POST['ID_Number'];
    $pw = $_POST['pas'];
    
    if(empty($_POST['specializationlist']) || empty($_POST['ranklist']) || empty($_POST['departmentlist']) || empty($_POST['questions'])){
        $incomplete1 = true;
 
    }else{
        $SP = $_POST['specializationlist'];
        $r = $_POST['ranklist'];
        $dept = $_POST['departmentlist'];
        $secretQ = $_POST['questions'];
        $ans = $_POST['Answer'];

        $q = $pdo->prepare("SELECT * FROM account where idNum = ? ");
        $q->execute(array($ID));
        $result = $q->rowCount();

        if($result > 0){
            $duplicate1 = true;
        }else{

            $stmt = $pdo->prepare("INSERT INTO account (FName, MName, LName, idNum, dept, rankID, specializationID, pw, accessLevel, secretQuestion, answer)
            VALUES (?,?,?,?,?,?,?,?,'prof',?,?)");
            $stmt->execute(array($FN,$MN,$LN,$ID,$dept,$r,$SP,$pw,$secretQ,$ans));
            $added1 = true;
            header("refresh:2; url = menu.php");
       
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head> 
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="addAccount.css">
    <title>Add Instructor</title>
  </head>
  <body>
  <h1 class="h1"> Add Instructor's Account</h1> 
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="addInstructor.php"> Add Account </a>
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
                    <td colspan="3"><input type="text" placeholder="ID Number" name="ID_Number" required></td>
                </tr>
                <tr>
                    <td colspan="3"><input type="password" placeholder="Password" name="pas" required></td>
                </tr>
            
                <tr>
                    <td colspan="3">
                        <label for="specialization"> Specialization </label>
                        <select id="specialization" name="specializationlist">
                            <option value=" " selected disabled></option>
                            <?php
                                $pdo=Database::connect();
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT specializationID, specName FROM specialization");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['specializationID']; ?>"> <?php echo $row['specName'];  ?> </option>
                                    
                            <?php }?>
                        </select>
                        <label for="Rank"> Rank </label>
                        <select id="rank" name="ranklist">
                            <option value=" " selected disabled></option>
                            <?php
                                $pdo=Database::connect();
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT rankID, rankName FROM rnk");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['rankID']; ?>"> <?php echo $row['rankName'];  ?> </option>
                                    
                            <?php }?>
                        </select>
                    </td>
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

    <script src="bootstrap/js/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>
      
    <?php if($duplicate1 == true){ ?>
        <script>
            swal({
            title: "Duplicate Username",
            text: "Username already registered",
            icon: "error",
            });
    </script>
    <?php }  ?>
    <?php if($added1 == true){ ?>
        <script>
            swal({
            title: "Successfully Registered",
            text: "Proceed to Login",
            icon: "success",
            });
    </script>
    <?php }  ?>
    <?php if($incomplete1 == true){ ?>
        <script>
            swal({
            title: "Incomplete input",
            text: "Please fill up all required fields",
            icon: "warning",
            });
    </script>
    <?php }  ?>

  </body>
</html>