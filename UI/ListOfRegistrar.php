<?php  session_start();

  if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

$noResult=false;
 ?>




<!DOCTYPE html>
<html>
    <head>
        
         <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <link rel="stylesheet" href="Listofinstructor.css">
        <title>List of Registrar </title>
    </head>
    <body>
            <h1 class="textcolor" colspan="3"> View Registrar </h1>
            <div class="btncontainer">
                <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
                <a class="navtop" href="ListOfRegistrar.php"> View Registrar  </a>
            </div>
            <table>
                <tr>
                    <th colspan="5"> List of Instructors </th>
                </tr>
                <tr>
                    <th> ID  Number</th>
                    <th> Name </th>
                    <th> Department </th>
                </tr>
               <?php
                require '../database.php';
                $pdo=Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = $pdo->prepare('SELECT account.idNum, account.FName, account.MName, account.LName, department.deptName FROM account, department where accessLevel = "reg" and department.deptID = account.dept  order by account.FName');
                $sql->execute();
                $result2= $sql->rowCount();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);
                
                 if($result2==0){ 
                    $noResult=true;
                }

                foreach($result as $id => $data) : ?>
                    <tr>
                        <td><?php echo $data['idNum'] ?></td>
                        <td><?php echo $data['FName']." ". $data['MName']." ".$data['LName']?></td>
                        <td><?php echo $data['deptName'] ?></td>

                    </tr>
                <?php endforeach; ?>

            </table>
            <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
            <script src="bootstrap/js/sweetalert.min.js"></script>
      
    <?php if($noResult == true){ ?>
        <script>
            swal({
            title: "No records yet.",
            text: "",
            icon: "warning",
            });
    </script>
    <?php }  ?>
    </body>
</html>