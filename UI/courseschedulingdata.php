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
$noAvailRoom = false;
$wrongTimeInput=false;

$pdo=Database::connect(); 
    
  /*  //for Testing only
    echo " laog session crsschedActionCurID: ".$_SESSION['crsSchedulingActionCurID'];
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
/*    echo " laog session crsschedActionCurID2: ".$_SESSION['crsSchedulingActionCurID'];
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
                            $noAvailRoom = true;
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

    } else {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $classroomID = $_GET['classroomID'];
            $crsSchedulingActionCurID3=$_SESSION['crsSchedulingActionCurID'];
            $actionDeptID3=$_SESSION['actionDeptID'];
            $actionSyID3=$_SESSION['actionSyID'];;
            $actionPeriodID3=$_SESSION['actionPeriodID'];
            $actionLevelID3=$_SESSION['actionLevelID'];

            /*echo " laog session crsschedActionCurID3: ".$crsSchedulingActionCurID3;
            echo " laog session deptID3: ".$actionDeptID3;
            echo " laog session actionSyID3: ".$actionSyDI3;
            echo " laog session actionPeriodID3: ".$actionPeriodID3;*/

            $stmt = $pdo->prepare("INSERT INTO coursescheduling (classroomID, dayID, timeStartID, timeEndID, secID, curID, deptID, syID, periodID, levelID)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute(array($classroomID,$_SESSION['dayID'],$_SESSION['timeStartID'],$_SESSION['timeEndID'],$_SESSION['secID'],$crsSchedulingActionCurID3, $actionDeptID3,$actionSyID3,$actionPeriodID3,$actionLevelID3));

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $classroomID = $_GET['classroomID'];
            $stmt = $pdo->prepare("INSERT INTO courseschedulingtemp (classroomID, dayID, timeStartID, timeEndID, secID, curID,  deptID, syID, periodID, levelID)
            VALUES (?,?,?,?,?,?,?,?,?,?)");
  
            $stmt->execute(array($classroomID,$_SESSION['dayID'],$_SESSION['timeStartID'],$_SESSION['timeEndID'],$_SESSION['secID'],$crsSchedulingActionCurID3, $actionDeptID3,$actionSyID3,$actionPeriodID3,$actionLevelID3));
                $isCreated = true;
    }
}

?>

<!doctype html>
<html lang="en">

<head>
      <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
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

     <script src="bootstrap/js/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>
      
    <?php if($wrongTimeInput == true){ ?>
        <script>
            swal({
            title: "Incorrect Time Input",
            text: "Please try again",
            icon: "error",
            });
    </script>
    <?php  $wrongTimeInput=false;}  ?>
    <?php if($isSubmitted  == true){ ?>
        <script>
            swal({
            title: "Preferences Successfully Submitted",
            text: "Proceed to adding a room.",
            icon: "success",
            });
    </script>
     <?php $isSubmitted = false;}  ?>
    <?php if($isCreated  == true){ ?>
        <script>
            swal({
            title: "Successfully Created",
            text: "Your schedule has been created. ",
            icon: "success",
            });
    </script>
    <?php $isCreated = false;}  ?>
    <?php if($isIncomplete == true){ ?>
        <script>
            swal({
            title: "Incomplete Input",
            text: "Please fill up all required fields",
            icon: "warning",
            });
    </script>
    <?php $isIncomplete = false;}  ?>
    <?php if($noAvailRoom  == true){ ?>
        <script>
            swal({
            title: "No Available Room",
            text: "Please try a different day and time",
            icon: "warning",
            });
    </script>
    <?php $noAvailRoom = false;}  ?>

</body>

</html>