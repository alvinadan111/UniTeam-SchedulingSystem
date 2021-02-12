<?php   
session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;
error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();


 ?>

<!DOCTYPE html>
<html>

<head>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="roomoccupied.css">
</head>

<body>
    
    <h1> Room Occupied </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="roomoccupied.php"> Room Occupied </a>
    </div>
    <table>
        <form method="get">
        <tr class="left">
            <td colspan="5">
                <label for="rooms"> Rooms </label>
                <select id="rooms" name="roomlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from classroom");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['classroomID']; 
                                    $roomNum=$row['roomNum'];
                                    $buildingCode=$row['buildingCode'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$roomNum - $buildingCode";  ?> </option>
                                    
                            <?php }?>     
                    </select>
                
                <input type="submit" value="Search">
               
            </td>
        </tr>
         </form>
        <tr>
            <th colspan="5"> Search Results</th>
        </tr>
        <tr>
            <th> # </th>
            <th> Day </th>
            <th> Schedule </th>
            <th>  Room </th>
            <th> Building </th>
        </tr>

        <?php if (!empty($_GET['roomlist'])) {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $classroomID =$_GET['roomlist'];

                $stmt=("select *, (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select buildingCode from classroom where f.classroomID=classroom.classroomID) as buildingCode from facultyloading f where classroomID=? order by classroomID, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                $q->execute(array($classroomID));
                 $num=1;
                  while ($row2 = $q->fetch()){
                            $dayName=$row2['dayName'];
                            $sched=$row2['crsName']."<br>".$row2['timeStart']." - ".$row2['timeEnd'];
                            $buildingCode=$row2['buildingCode'];
                            $roomNum=$row2['roomNum'];
    
 ?>    
                       <tr>
                        <td><?php  echo $num; ?> </td>
                        <td><?php  echo $dayName; ?> </td>
                        <td><?php  echo $sched; ?> </td>
                        <td><?php  echo $roomNum; ?> </td>
                        <td><?php  echo $buildingCode; ?> </td>
                       </tr>
<?php                   $num++;
                 }
             } else {

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt=("select *, (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select buildingCode from classroom where f.classroomID=classroom.classroomID) as buildingCode from facultyloading f  order by classroomID, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                $q->execute(array($classroomID));

                 $num=1;
                  while ($row2 = $q->fetch()){
                            $dayName=$row2['dayName'];
                            $sched=$row2['crsName']."<br>".$row2['timeStart']." - ".$row2['timeEnd'];
                            $buildingCode=$row2['buildingCode'];
                            $roomNum=$row2['roomNum'];
 ?>    
                        <tr>
                        <td><?php  echo $num; ?> </td>
                        <td><?php  echo $dayName; ?> </td>
                        <td><?php  echo $sched; ?> </td>
                        <td><?php  echo $roomNum; ?> </td>
                        <td><?php  echo $buildingCode; ?> </td>
                        </tr> 
<?php                   $num++;
                 }
             }
             Database::disconnect();
        ?>
        
    </table>
</body>

</html>