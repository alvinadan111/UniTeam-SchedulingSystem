<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="listofinstructor.css">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>
    <body>
            <h1 class="textcolor" colspan="3"> View Instructor </h1>
            <div class="btncontainer">
                <a class="navtop" href="menu.html"> Home <i class="fas fa-chevron-right"></i> </a>
                <a class="navtop" href="listofinstructor.html"> View Instructor </a>
            </div>
            <table>
                <tr>
                    <th colspan="5"> List of Instructors </th>
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
                $sql = $pdo->prepare('SELECT account.idNum, account.FName, account.MName, account.LName, department.deptName, specialization.specName, rnk.rankName FROM account, department, specialization, rnk where accessLevel = "prof" and department.deptID = account.dept and specialization.specializationID = account.specializationID and rnk.rankID = account.rankID');
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
    </body>
</html>