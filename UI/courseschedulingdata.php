<?php 
session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

error_reporting(E_ERROR | E_PARSE);


/*echo MyClass::$str;*/
 
require '../database.php';
$isSubmitted = false;
$isCreated = false;
$isIncomplete = false;
$isNoAvailRoom = false;
$wrongTimeInput=false;

$pdo=Database::connect(); 
    
    //for Testing only
/*    echo " laog session crsschedActionCurID: ".$_SESSION['crsSchedulingActionCurID'];
    echo " laog session deptID: ".$_SESSION['actionDeptID'];
    echo " laog session actionSyID: ".$_SESSION['actionSyID'];
    echo " laog session actionPeriodID: ".$_SESSION['actionPeriodID'];*/

if(isset($_GET['secID'])){
    
    //for Testing only
    $crsSchedulingActionCurID=$_GET['crsSchedulingActionCurID'];
    $actionDeptID=$_GET['actionDeptID'];
    $actionSyID=$_GET['actionSyID'];
    $actionPeriodID=$_GET['actionPeriodID'];
    //for Testing only
 /*   echo " laog session crsschedActionCurID2: ".$_SESSION['crsSchedulingActionCurID'];
    echo " laog session deptID2: ".$_SESSION['actionDeptID'];
    echo " laog session actionSyID2: ".$_SESSION['actionSyID'];
    echo " laog session actionPeriodID2: ".$_SESSION['actionPeriodID'];*/
    
    if(empty($_GET['dayID']) || empty($_GET['timeStartID']) || empty($_GET['timeEndID']) || empty($_GET['secID'])){ 
        $isIncomplete = true;
            // echo "Some fields are left unfilled. <br>";
    }else{
            $time=$_GET['timeEndID']-$_GET['timeStartID'];
           if ($time<0) {
              $wrongTimeInput=true;
            } else {

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = $pdo->query("SELECT * from classroom order by roomNum");
                $i=0;
                while ($row = $sql->fetch()) {
                    
                    $room[] = $row['classroomID'];
                }


                $conflictBW=false; $conflictOut=false; 
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query("SELECT * from coursescheduling natural join curriculum order by classroomID");
                    $dayID=$_SESSION['dayID']  = $_GET['dayID'];
                    $timeStartID=$_SESSION['timeStartID'] = $_GET['timeStartID'];
                    $timeEndID=$_SESSION['timeEndID'] = $_GET['timeEndID'];
                    $secID=$_SESSION['secID'] = $_GET['secID'];
                    $time=$timeEndID-$timeStartID;

                    /*header("Location:submit.php?secIDisSet=true&sdayID=".$dayID."&stimeStartID=".$timeStartID."&stimeEndID=".$timeEndID."&ssecID=".$secID);*/
                    
                    //check room conflicts
                    $count=0; $j=0;
                    while ($row = $stmt->fetch()) {   
                            if ($timeStartID >=  $row['timeStartID'] &&  $timeEndID <= $row['timeEndID'] ){
                                    $conflictBW=true;
                           }else{
                            $conflictBW=false;
                           }

                           if (($timeStartID < $row['timeStartID'] && $timeEndID <=$row['timeStartID']) ||
                              ($timeStartID >= $row['timeEndID'] && $timeEndID  > $row['timeEndID'])) {
                               $conflictOut=false;
                           } else {
                              $conflictOut=true; 
                           }

                           

                           if ($_SESSION['actionPeriodID']==$row['periodID'] && $_SESSION['actionSyID']==$row['syID'] && $row['dayID']== $dayID && ($conflictOut==true || $conflictBW==true)) {
                               $roomConflict[]=$row['classroomID'];
                                $count++ ;
                           }else{
                                $roomAvailable[]=$row['classroomID'];
                                        
                            }
                    }

                        //available rooms in an array
                         $i=0; $j=0; 
                        if ($count>0) {
                          while ($i < $count) {
                            $j=0;
                           while ($j < count($room)) {
                             if ($roomConflict[$i] == $room[$j]) {
                               unset($room[$j]); 
                               $roomTrue=array_values($room);
                               $room=array_values($roomTrue); break;  
                             } else {
                                $j++; 
                             } 
                           } /*eo else*/  $i++;
                          } // outer while
                        }/*else{
                          $roomTrue=array_values($room);
                        }*/    
                         if(count($room)==0)
                         {
                            $isNoAvailRoom = true;
                         }

                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query("SELECT * from classroom   order by classroomID");      
                $i=0;
                while ($row = $stmt->fetch()) { 
                  if ($room[$i]==$row['classroomID']) {
                    $classroomIDT[$i] = $row['classroomID'];
                    $roomNumT[$i] = $row['roomNum'];
                    $buildingCodeT[$i] = $row['buildingCode'];
                    $i++;
                  }
                }
                $isSubmitted = true;
            } //end of else statement
    } //end of else statement outer
} // end of if(isset($_GET['secID']))


if(isset($_GET['saveSubmitBtn'])){


    if(empty($_GET['classroomID'])){
        $isIncomplete = true;
?>        <!-- Warning Alert -->
        <!-- <div id="myAlertI" class="alert alert-warning alert-dismissible fade show">
        <strong>Warning!</strong> &nbsp Some fields are left unfilled.
        <button type="button" class="close" data-dismiss="alert">&times;</button> -->
        </div>
<?php     } else {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $classroomID = $_GET['classroomID'];
            $crsSchedulingActionCurID3=$_SESSION['crsSchedulingActionCurID'];
            $actionDeptID3=$_SESSION['actionDeptID'];
            $actionSyID3=$_SESSION['actionSyID'];;
            $actionPeriodID3=$_SESSION['actionPeriodID'];
            $actionLevelID3=$_SESSION['actionLevelID'];
/*
            echo " laog session crsschedActionCurID3: ".$crsSchedulingActionCurID3;
            echo " laog session deptID3: ".$actionDeptID3;
            echo " laog session actionSyID3: ".$actionSyDI3;
            echo " laog session actionPeriodID3: ".$actionPeriodID3;*/

            $stmt = $pdo->prepare("INSERT INTO coursescheduling (classroomID, dayID, timeStartID, timeEndID, secID, curID, deptID, syID, periodID, levelID)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
            $isCreated = true;
            $stmt->execute(array($classroomID,$_SESSION['dayID'],$_SESSION['timeStartID'],$_SESSION['timeEndID'],$_SESSION['secID'],$crsSchedulingActionCurID3, $actionDeptID3,$actionSyID3,$actionPeriodID3,$actionLevelID3));

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $classroomID = $_GET['classroomID'];
            $stmt = $pdo->prepare("INSERT INTO courseschedulingtemp (classroomID, dayID, timeStartID, timeEndID, secID, curID,  deptID, syID, periodID, levelID)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
            $isCreated = true;
            $stmt->execute(array($classroomID,$_SESSION['dayID'],$_SESSION['timeStartID'],$_SESSION['timeEndID'],$_SESSION['secID'],$crsSchedulingActionCurID3, $actionDeptID3,$actionSyID3,$actionPeriodID3,$actionLevelID3));
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
     <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap CSS -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="CourseSchedulingdata.css">
</head>

<body>
  
        <div class="btncontainer">
            <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="coursescheduling.php"> Course Scheduling <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="coursechedulingdata.php"> Schedule </a>
        </div>
        <h1 class="textcolor"> Course Scheduling </h1>

<form   method="get">
        <table class="table1">
            <tr>
                <th colspan="7"> Schedule</th>
                <?php if ($isSubmitted==true) { $isSubmitted=false; ?>
                    <!-- Success Alert -->
                    <div id="myAlert" class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Your preferences have been submitted. Please proceed to adding a room.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php   } 
                if ($isIncomplete==true) { $isIncomplete=false; ?>
                    <!-- Warning Alert -->
                    <div id="myAlertUF" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp Some fields are left unfilled.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php   } 
             if ($isCreated==true) { $isCreated=false; ?>
                     <!-- Success Alert -->
                    <div id="myAlertC" class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Your schedule has been created.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
             <?php } 
                if ($isNoAvailRoom ==true) { $isNoAvailRoom=false;  ?>
                    <!-- Warning Alert -->
                    <div id="myAlertUF" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp No available room for your preferences.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php   } 
                if ($wrongTimeInput ==true) { $wrongTimeInput=false;  ?>
                    <!-- Error Alert -->
                      <div id="myAlert" class="alert alert-danger alert-dismissible fade show">
                        <strong>Error!</strong> &nbsp You have entered an incorrect time!
                        <button type="button"  class="close" data-dismiss="alert">&times;</button>
                      </div>
            <?php   } 
            ?>
               
            </tr>
            <tr>
                <td class="noborder">
                    <label  for="day"> Day </label>
                    <select id="day" name="dayID" required>
                        <option value=" " selected disabled></option>
                            <?php
                                $pdo=Database::connect();
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT dayID, dayName FROM day");
                                while ($row = $stmt->fetch()) { 
                            ?>
                                <option value="<?php echo $row['dayID']; ?>"> <?php echo $row['dayName'];  ?> </option>
                                    
                            <?php }?>    
                    </select>
                   
                </td>
                <td class="noborder">
                    <label for="timestart"> Time Start </label>
                    <select id="timestart" name="timeStartID" required>
                        <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT timeStartID, timeStart FROM timestart");
                                while ($row = $stmt->fetch()) { 
                            ?>
                                <option value="<?php echo $row['timeStartID']; ?>"> <?php echo $row['timeStart'];  ?> </option>
                                    
                            <?php }?>   
                    </select>
                </td>
                <td class="noborder">
                    <label for="timeend"> Time End </label>
                    <select id="timeend" name="timeEndID" required>
                        <option value=" " selected disabled></option>
                         <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT timeEndID, timeEnd FROM timeend");
                                while ($row = $stmt->fetch()) { 
                            ?>
                                <option value="<?php echo $row['timeEndID']; ?>"> <?php echo $row['timeEnd'];  ?> </option>
                                    
                            <?php }?>   
                    </select>
                </td>
                <td class="noborder">
                    <label for="section"> Section </label>
                    <select id="section" name="secID" onchange='this.form.submit()'> 
                        <option value="" selected disabled ></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT secID, section FROM section");
                                while ($row = $stmt->fetch()) { 
                            ?>
                                <option value="<?php echo $row['secID']; ?> "> <?php echo $row['section'];  ?> </option>
                                    
                            <?php }?> 
                    </select>
                </td>
                <td style="border-left:none">
                <label for="rooms">Available Rooms</label>
                <div class="hs">
                <select id="rooms" name="classroomID"  style="align-items: center;" required>
                    <option value=" " selected disabled></option>
                         <?php
                            //display available rooms in <select> tag
                                $i=0;
                                while ($i < count($classroomIDT)) {

                        ?>
                                 <option value="<?php echo $classroomIDT[$i]; ?>"> <?php echo  $roomNumT[$i]." - ". $buildingCodeT[$i];  ?> </option>
                                                         
                        <?php  $i++; }?>   
                    
                </select>
                </td>
                <td class="noborder" style="border-right: 1px solid black">
                <input type="Submit" name="saveSubmitBtn" value="Save & Submit"> </td>
            </tr>
            </table>

     </form>

    <?php  Database::disconnect(); ?>   
    <!-- bootstrap JS-->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript">
     $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlert').hide('fade');
        }, 8000); 
     });

    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertI').hide('fade');
        }, 3500); 

     });
    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertC').hide('fade');
        }, 3500); 

     });       
    </script>
</body>

</html>