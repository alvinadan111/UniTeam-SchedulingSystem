<?php
session_start();
require 'database.php';
$isRegistered = false;
$isDuplicated = false;
$isIncomplete = false;

if(isset($_POST['sign'])){

    $pdo=Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $FN = $_POST['First_Name'];
    $MN = $_POST['Middle_Name'];
    $LN = $_POST['Last_Name'];
    $ID = $_POST['ID_Number'];
    $pass = $_POST['password']; 
    $mail = $_POST['emailaddress'];
    
    if(empty($_POST['spec']) || empty($_POST['ranks']) || empty($_POST['departments'])){
 
        $isIncomplete = true;
 
    }else{
        $SP = $_POST['spec'];
        $r = $_POST['ranks'];
        $dept = $_POST['departments'];

        $q = $pdo->prepare("SELECT * FROM account where idNum = ? ");
        $q->execute(array($ID));
        $result = $q->rowCount();

        if($result > 0){
           $isDuplicated = true;
        }else{

            $stmt = $pdo->prepare("INSERT INTO account (FName,MName, LName, idNum, dept, email, rankID, specializationID, pw)
            VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->execute(array($FN,$MN,$LN,$ID,$dept,$mail,$r,$SP,$pass));
            $isRegistered = true;
            header("refresh:3; url = index.php");
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
    <link rel="stylesheet" href="UI/signup.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
  
  <div class="container">
        <form class="container2" method = "POST">

            <table>
                <h1> Sign Up </h1>
                <tr>
                    <td><input type="text" placeholder="First Name" name="First_Name" required></td>
                    <td><input type="text" placeholder="Middle Name" name="Middle_Name"></td>
                    <td><input type="text" placeholder="Last Name" name="Last_Name" required></td>
                </tr>
                <tr>
                    <td colspan="3"><input type="text" placeholder="ID Number" name="ID_Number" required></td>
                </tr>
                <tr>
                    <td colspan="3"><input type="password" placeholder="New Password" name="password" required></td>
                </tr>
                <tr>
                    <td colspan="3"> <input type="email" placeholder="EmailAddress" name="emailaddress" required> </td>
                   
                </tr>
                <tr>
                    <td colspan="3">
                        <label for="specialization"> Specialization </label>
                        <select name="spec" id="specialization" required>
                            <option value=" " selected disabled></option>
                            <?php
                                $pdo=Database::connect();
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT specializationID, specName FROM specialization");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['specializationID']; ?>"> <?php echo $row['specName'];  ?> </option>
                                    
                            <?php }?>
                        </select>
                        <label for="rank"> Rank </label>
                        <select name="ranks" id="rank" required>
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
                        <select name="departments" id="department" required>
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
                        <input type="submit" class="signupbtn" name = "sign" value = "Sign Up"></input>
                    </td>
                </tr>
            </table>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>

    <?php if($isDuplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate ID Number",
            text: "ID Number already registered",
            icon: "error",
            });
    </script>
    <?php }  ?>
    <?php if($isRegistered == true){ ?>
        <script>
            swal({
            title: "Successfully Registered",
            text: "Proceed to Login",
            icon: "success",
            });
    </script>
    <?php }  ?>
    <?php if($isIncomplete == true){ ?>
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