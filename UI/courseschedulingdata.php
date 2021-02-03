<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

// error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$isSubmitted = false;
$isCreated = false;
$isIncomplete = false;

$pdo=Database::connect(); 


if(isset($_GET['secID'])){
    if(empty($_GET['dayID']) || empty($_GET['timeStartID']) || empty($_GET['timeEndID']) || empty($_GET['secID'])){ 
        $isIncomplete = true;
            echo "Some fields are left out unfilled. <br>";
    /*    ?>   <!-- Warning Alert -->
            <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
            <strong>Warning!</strong> &nbsp Some fields are left unfilled.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php */
    }else{

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $pdo->query("SELECT * from classroom order by roomNum");
    $i=0;
    while ($row = $sql->fetch()) {
        
        $room[] = $row['classroomID'];
        echo "all rooms: ".$room[$i]."<br>";
        $i++;
    }


  $conflictBW=false; $conflictOut=false; 
  //$roomConflict[0]-0; $roomAvailable[0]=0;
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT * from coursescheduling natural join curriculum order by classroomID");
            $dayID=$_SESSION['dayID']  = $_GET['dayID'];
            $timeStartID=$_SESSION['timeStartID'] = $_GET['timeStartID'];
            $timeEndID=$_SESSION['timeEndID'] = $_GET['timeEndID'];
            $secID=$_SESSION['secID'] = $_GET['secID'];
            

            //check room conflicts
            $count=0; $j=0;
            while ($row = $stmt->fetch()) { 
                

                    if ($timeStartID >=  $row['timeStartID'] &&  $timeEndID <= $row['timeEndID'] ){
                            $conflictBW=true;
                            echo "conflictBW true  ";
                   }else{
                    $conflictBW=false;
                            echo "conflictBW false  ";
                   }

                   if (($timeStartID < $row['timeStartID'] && $timeEndID <=$row['timeStartID']) ||
                      ($timeStartID >= $row['timeEndID'] && $timeEndID  > $row['timeEndID'])) {
                       $conflictOut=false;
                     echo "conflictOut false ";
                   } else {
                      $conflictOut=true; 
                       echo "conflictOut true ";
                   }

                   if ($_SESSION['periodID']==$row['periodID'] && $_SESSION['syID']==$row['syID'] && $row['dayID']== $dayID && ($conflictOut==true || $conflictBW==true)) {
                       echo "same day true  ";
                       $roomConflict[]=$row['classroomID'];
                       echo "conflicted room: ".$roomConflict[$count]."  "; $count++ ;
                   }else{
                        $roomAvailable[]=$row['classroomID'];
                         echo "Available room: ".$roomAvailable[$j]."  ";
                         $j++; 
                                
                    }
                    echo "<br>";
                     echo "laman session csdata: syId:".$_SESSION['syID']." periodid ".
            $_SESSION['periodID']." curid: ".$_SESSION['curID'];
        }

echo "<br><br>";

//available rooms in an array
 $i=0; $j=0; 
if ($count>0) {
  while ($i < $count) {
    $j=0;
   while ($j < count($room)) {
     if ($roomConflict[$i] == $room[$j]) {
       unset($room[$j]); 
       $roomTrue=array_values($room);
        $j++;  break;  
     } else {
      $roomTrue=array_values($room);
      // $room[$j]=$room[$j];
       echo "<br> set room the same: ".$room[$j]." at index ".$j;
     }
     
     $j++;  
   }
   $i++;
 }
}else{
  $roomTrue=array_values($room);
}
 
echo "<br>";

echo "all rooms: ".count($room);

  echo "<br><br>";

$i=0;
  while ($i < count($roomTrue)) {
        echo "all true rooms as in: ".$roomTrue[$i]."<br>";
        $i++;
  }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT * from classroom   order by classroomID");
            
        $i=0;
        while ($row = $stmt->fetch()) { 
          if ($roomTrue[$i]==$row['classroomID']) {
            $classroomIDT[$i] = $row['classroomID'];
            $roomNumT[$i] = $row['roomNum'];
            $buildingCodeT[$i] = $row['buildingCode'];
            $i++;
          }
          
        }

  $i=0;
  while ($i < count($classroomIDT)) {
        echo "classroomID: ".$classroomIDT[$i]."<br>";
        echo "roomNumT: ".$roomNumT[$i]."<br>";
        echo "buildingCodeT: ".$buildingCodeT[$i]."<br>";
        $i++;
        echo "<br>";
  }
  $isSubmitted = true;
            echo "Successfully submitted. Proceed to adding a room <br>";
   /* ?>    <!-- Success Alert -->
    <div id="myAlert" class="alert alert-success alert-dismissible fade show">
        <strong>Success!</strong> Your preferences have been submitted. Please proceed to adding a room.
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php  */
 } //end of else statement
} // end of if(isset($_GET['secID']))


if(isset($_GET['saveSubmitBtn']))
{
    echo "save&submit button clicked <br>";
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    /*$curID = $_GET['curID'];
    $dayID = $_GET['dayID'];
    $timeStartID = $_GET['timeStartID'];
    $timeEndID = $_GET['timeEndID'];
    $secID = $_GET['secID'];*/
    $classroomID = $_GET['classroomID'];


   /* if(empty($_GET['dayID']) || empty($_GET['timeStartID']) || empty($_GET['timeEndID'])|| empty($_GET['secID'])|| empty($_GET['classroomID'])){
         echo "empty any from gET: <br>";
        $isIncomplete = true;*/
    // }else{

            $stmt = $pdo->prepare("INSERT INTO coursescheduling (classroomID, dayID, timeStartID, timeEndID, secID, curID)
            VALUES (?,?,?,?,?,?)");
            $stmt->execute(array($classroomID,$_SESSION['dayID'],$_SESSION['timeStartID'],$_SESSION['timeEndID'],$_SESSION['secID'],$_SESSION['curID']));
            // header("refresh:3; url = index.php");

            /*$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE coursescheduling SET curID = ?, dayID=?, timeStartID=?, timeEndID=?, secID=?, classroomID=? WHERE accountnum = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($fname,$lname,$bday,$accountnum));*/

            $isCreated = true;
            echo "successfully inserted: <br>";

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

<form method="get">
        <table class="table1">
            <tr>
                <th colspan="7"> Schedule</th>
                <?php if ($isSubmitted==true) { ?>
                    <!-- Success Alert -->
                    <div id="myAlert" class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Your preferences have been submitted. Please proceed to adding a room.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php   } 
                if ($isIncomplete==true) { ?>
                    <!-- Warning Alert -->
                    <div id="myAlertUF" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp Some fields are left unfilled.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
            <?php   } 
             if ($isCreated==true) { ?>
                     <!-- Success Alert -->
                    <div id="myAlertC" class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Your schedule has been created.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
             <?php } ?>
               
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
                                // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $i=0;
                                while ($i < count($classroomIDT)) {
                                  
                            //     $stmt = $pdo->prepare("SELECT * from classroom where classroomID=? ");
                            // $stmt->execute(array($roomTrue[$i]));
                            // $rowR= $stmt->fetch();
                            // while ($rowR = $stmt->fetch()){
                            // echo "test".$rowR['roomNum']." - ".$rowR['buildingCode']."<br>";

                        ?>
                                 <option value="<?php echo $classroomIDT[$i]; ?>"> <?php echo  $roomNumT[$i]." - ". $buildingCodeT[$i];  ?> </option>
                                                         
                        <?php  $i++; }?>   
                    
                </select>
                </td>
                <td class="noborder" style="border-right: 1px solid black">
                <input type="Submit" name="saveSubmitBtn" value="Save & Submit"> </td>
            </tr>
            </table>
            <table>
      
                <tr>
                <td> Time </td>
                <td> Monday </td>
                <td> Tuesday </td>
                <td> Wednesday </td>
                <td> Thursday </td>
                <td> Friday </td>
                <td> Saturday </td>
                </tr>
                <tr>
                <td> 7am </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
             </tr>
                <tr>
                <td> 8am </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
             </tr>
                <tr>
                <td> 9am </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                </tr>
                <tr>
                <td> 10am </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                </tr>
                <tr>
                <td> 11am </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                </tr>
                <tr>
                <td> 12nn </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                    </tr>
                <tr>
                <td> 1pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 2pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 3pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 4pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 5pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 6pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 7pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
            </tr>
            <tr>
                <td> 8pm </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
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
        }, 4000); 
     });

    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertUF').hide('fade');
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