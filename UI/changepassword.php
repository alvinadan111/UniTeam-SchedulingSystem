<?php
session_start();
require '../database.php';

$isChanged = false;
$isFailed = false;

$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = $pdo->prepare("SELECT pw from account where accountID = :accID");
$sql->bindValue(':accID',$_SESSION['accountID']);
$sql->execute();
$result = $sql->fetchAll(PDO::FETCH_ASSOC);
$accPW = $result[0]['pw'];

if(isset($_POST['change'])){
    $old = $_POST['old'];
    $new = $_POST['new'];
    $con = $_POST['con'];
   

    if($old != $accPW || $new != $con){ 
        $isFailed = true;
    }
    if($old == $accPW && $new == $con){
        $pwUpdate = $pdo->prepare("UPDATE account set pw = :acPW where accountID = :acID");
        $pwUpdate->bindValue(':acPW',$con);
        $pwUpdate->bindValue(':acID',$_SESSION['accountID']);
        $pwUpdate->execute(); 
        $isChanged = true;
        header("refresh:2; url = menu.php");

    }

}
   
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="bootstrap/js/sweetalert.min.js"></script>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="Changepassword.css">

        <title>Change Password</title>
</head>
<body>
    <h1> Change Password </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="changepassword.php"> Change Password </a>
    </div>
    <form class="container" method = "POST">
        <table>
            <tr>
                <th> Change Password </th>
            </tr>
            <tr>
                <td> 
              
                    <input type="text" placeholder="Old Password " name = "old" required>
                </td>
            </tr>
            <tr>
                <td>
              
                    <input type="text" placeholder="New Password " name = "new" required>
                </td>
            </tr>
            <tr>
                <td>
        
                    <input type="text" placeholder="Confirm New Password" name = "con" required>
                </td>
            </tr>
            <tr>
                <td>
                    <input type="submit" name = "change" value="Save">
                </td>
                
            </tr>
        </table>
    </form>
    <script src="bootstrap/js/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>

    <?php if($isChanged == true){ ?>
        <script>
            swal({
            title: "Password Successfully Changed",
            text: "Password changed",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($isFailed == true){ ?>
        <script>
            swal({
            title: "Password Not Match",
            text: "Failed to Change",
            icon: "error",
            });
    </script>
    <?php }  ?>


</body>

</html>