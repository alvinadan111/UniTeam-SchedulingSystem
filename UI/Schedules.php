<?php   
session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;
error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();

$record=false;
$isIncomplete=false;
 ?>


<!DOCTYPE html>
<html>
    <head>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
         <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  
        <link rel="stylesheet" href="schedules.css">
    </head>
    <body>
        <h1> View Schedules </h1>
        <div class="btncontainer">
            <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="Schedules.php"> Schedules </a>
        </div>
         <form>
        <table>
            <td>
                <label for="dept"> *Department </label>
                <select id="dept" name="departmentlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from department");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['deptID']; 
                                    $deptCode=$row['deptCode'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$deptCode";  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td>
                <label for="cur"> *Curriculum Year </label>
                <select id="cur" name="curlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from schoolyear");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['syID']; 
                                    $schoolYR=$row['schoolYR'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$schoolYR";  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td>
                <label for="per"> *Period </label>
                <select id="period" name="periodlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from period");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['periodID']; 
                                    $periodDesc=$row['periodDesc'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$periodDesc";  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td>
                <label for="lev"> Level </label>
                <select id="level" name="levellist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from lvl");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['levelID']; 
                                    $levelDesc=$row['levelDesc'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$levelDesc";  ?> </option>
                                    
                            <?php }?>     
                    </select>            </td>
            <td>
                <label for="sec"> Section </label>
                <select id="sec" name="seclist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from section");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['secID']; 
                                    $section=$row['section'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$section";  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td>
                <label for="ins"> Instructor </label>
                <select id="instructor" name="instructorlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("select * from account where accessLevel='prof'");
                                while ($row = $stmt->fetch()) {
                                    $id=$row['accountID']; 
                                    $prof=$row['FName']." - ".$row['LName'];
?>

                                <option value="<?php echo $id; ?>"> <?php echo "$prof";  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td> <button> Search </button></td>
        </table>
       </form>
        <br>
        <table>
            <tr>
                <td> Department Code </td>
                <td> Subject Title </td>
                <td> UNIT <br> Lec Lab Credit </td>
                <td> Schedule/Room </td>
                <td> Instructor </td>
            </tr>
            <?php  if ( !empty($_GET['departmentlist']) ||  !empty($_GET['curlist'])||  !empty($_GET['periodlist'])||  !empty($_GET['levellist']) || !empty($_GET['seclist'])||  !empty($_GET['instructorlist']) ) {

                /*echo "naglaog sa if hanip";*/
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                /*echo "naglaog sa if";*/
                $departmentID =$_GET['departmentlist'];
                $syID =$_GET['curlist'];
                $periodID =$_GET['periodlist'];
                $levelID =$_GET['levellist'];
                $secID =$_GET['seclist'];
                $instructorID =$_GET['instructorlist'];



                if(empty($_GET['periodlist']) || empty($_GET['curlist']) || empty($_GET['departmentlist']) ){/* echo "required fields unfilled"; */  $isIncomplete=true;?>

                    <!-- Warning Alert -->
                    <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp Please make sure all required fields are filled.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>

        <?php  }



                if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& !empty($_GET['levellist']) && !empty($_GET['seclist'])&& !empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=? and f.levelID=? and f.secID=? and f.accountID=?  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$levelID,$secID,$instructorID));
                $result= $q->rowCount();
                }

                if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& !empty($_GET['levellist']) && !empty($_GET['seclist'])&& empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=? and f.levelID=? and f.secID=?   order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$levelID,$secID));
                $result= $q->rowCount();
                }
                
                if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& !empty($_GET['levellist']) && empty($_GET['seclist'])&& !empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=? and f.levelID=? and f.accountID=?  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$levelID,$instructorID));
                $result= $q->rowCount();
                }

                if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& empty($_GET['levellist']) && !empty($_GET['seclist'])&& !empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=?  and f.secID=? and f.accountID=?  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$secID,$instructorID));
                $result= $q->rowCount();
                }

               if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& empty($_GET['levellist']) && empty($_GET['seclist'])&& !empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=?  and f.accountID=?  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$instructorID));
                $result= $q->rowCount();
                }

               if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& !empty($_GET['levellist']) && empty($_GET['seclist'])&& empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=? and f.levelID=?  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$levelID));
                $result= $q->rowCount();
                }

                if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& empty($_GET['levellist']) && !empty($_GET['seclist'])&& empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=? and f.secID=?  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID,$secID));
                $result= $q->rowCount();
                }
               if (!empty($_GET['departmentlist']) && !empty($_GET['curlist'])&& !empty($_GET['periodlist'])&& empty($_GET['levellist']) && empty($_GET['seclist'])&& empty($_GET['instructorlist'])) {
                        $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f where f.deptID=? and f.syID=? and f.periodID=?   order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute(array($departmentID,$syID,$periodID));
                $result= $q->rowCount();
                }

                    
                 if($result==0 && $isIncomplete==false){ ?>
                    <!-- Warning Alert -->
                    <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp No records found.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php } else if ($result>0) {

                          while ($row = $q->fetch()){
                                    $deptCode=$row['deptCode'];
                                    $dayName=$row['dayName'];
                                    $crsName=$row['crsName'];
                                    $lec=$row['lec'];
                                    $lab=$row['lab'];
                                    $units=$row['units'];
                                    $sched=$row['timeStart']." - ".$row['timeEnd']." ".$row['section']." - ".$row['roomNum']." ".$dayName;
                                    $instructor=$row['FName']." ".$row['LName'];
                                    
            
         ?>    
                               <tr>
                                <td><?php  echo $deptCode; ?> </td>
                                <td><?php  echo $crsName; ?> </td>
                                <td><?php  echo "$lec  $lab  $units"; ?> </td>
                                <td><?php  echo $sched; ?> </td>
                                <td><?php  echo $instructor; ?> </td>
                               </tr>
        <?php
                         }
                     }
            }




             else {
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
               /* echo "naglaog sa else";*/
                $stmt=("select *, 
                    (select deptCode from department where f.deptID=department.deptID) as deptCode, 
                    (select dayName from day where f.dayID=day.dayID) as dayName, 
                    (select crsName from curriculum where f.curID=curriculum.curID) as crsName,
                    (select lec from curriculum where f.curID=curriculum.curID) as lec,
                    (select lab from curriculum where f.curID=curriculum.curID) as lab,
                    (select units from curriculum where f.curID=curriculum.curID) as units,
                    (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
                    (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
                    (select section from section where f.secID=section.secID) as section, 
                    (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
                    (select FName from account where f.accountID=account.accountID) as FName, 
                    (select LName from account where f.accountID=account.accountID) as LName
                     from facultyloading f  order by deptID asc, dayID asc, timeStartID asc");

                $q = $pdo->prepare($stmt);
                
                $q->execute();
                $result= $q->rowCount();

                 if($result==0){ ?>
                    <!-- Warning Alert -->
                    <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp No records yet.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php } else {
            
                          while ($row2 = $q->fetch()){
                                    $deptCode=$row2['deptCode'];
                                    $dayName=$row2['dayName'];
                                    $crsName=$row2['crsName'];
                                    $lec=$row2['lec'];
                                    $lab=$row2['lab'];
                                    $units=$row2['units'];
                                    $sched=$row2['timeStart']." - ".$row2['timeEnd']." ".$row2['section']." ".$row2['roomNum']." ".$dayName;
                                    $instructor=$row2['FName']." - ".$row2['LName'];
                                    
            
         ?>    
                               <tr>
                                <td><?php  echo $deptCode; ?> </td>
                                <td><?php  echo $crsName; ?> </td>
                                <td><?php  echo "$lec  $lab  $units"; ?> </td>
                                <td><?php  echo $sched; ?> </td>
                                <td><?php  echo $instructor; ?> </td>
                               </tr>
        <?php
                         }
                    }
             }

 Database::disconnect(); 
?>
        </table>


        <!-- bootstrap JS-->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
     $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlert').hide('fade');
        }, 3500); 

     });
        
    </script>
    </body>
</html>