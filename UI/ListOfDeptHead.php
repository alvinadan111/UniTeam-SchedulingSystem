<?php session_start();

  if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

$noResult=false;
 ?>

<!DOCTYPE html>
<html>
    <head>
        <title>List of Department Head </title>
         <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="Listofinstructors.css">
    </head>
    <body>
            <h1 class="textcolor" colspan="3"> View Department Head  </h1>
            <div class="btncontainer">
                <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
                <a class="navtop" href="ListOfDeptHead.php"> View Department Head </a>
            </div>
            <table>
                <tr>
                    <th colspan="5"> List of Department Heads </th>
                </tr>
                <tr>
                    <th> ID  Number</th>
                    <th> Name </th>
                    <th> Department </th>
                    <th> Specialization </th>
                    <th> Rank </th>
                </tr>
                <?php
                require '../database.php';
                $pdo=Database::connect();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = $pdo->prepare('SELECT account.idNum, account.FName, account.MName, account.LName, department.deptName, specialization.specName, rnk.rankName FROM account, department, specialization, rnk where accessLevel = "admin" and department.deptID = account.dept and specialization.specializationID = account.specializationID and rnk.rankID = account.rankID order by account.FName asc');
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach($result as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['idNum'] ?></td>
                    <td><?php echo $data['FName']." ". $data['MName']." ".$data['LName']?></td>
                    <td><?php echo $data['deptName'] ?></td>
                    <td><?php echo $data['specName'] ?></td>
                    <td><?php echo $data['rankName'] ?></td>
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