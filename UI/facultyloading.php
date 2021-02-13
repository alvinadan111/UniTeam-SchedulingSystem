<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();


$incomplete=false;
$noResult=false;

?>


<!DOCTYPE html>

<html>

<head>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> 
    <link rel="stylesheet" href="facultyloading.css">
    <title>Faculty Loading</title>
</head>

<body>
    <h1> Faculty Loading </h1> 
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="facultyloading.php"> Faculty Loading </a>
    </div>
     

    
  <form method="get"  >
    <table class="top">
        <tr>
            <th colspan="4" class="border"> Load Instructor</th>
            <?php if ( isset($_GET['genSchedBtn'])) { ?>
                 <!-- Success Alert -->
                    <div id="myAlertS" class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Schedule has been generated.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
       <?php  } 
              if ($_GET['addCalendarConflict']==true) { ?>
                 <!-- Error Alert -->
              <div id="myAlertC" class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> &nbsp Cannot add to calendar, doing so will result to a conflicting schedule for this professor!
                <button type="button"  class="close" data-dismiss="alert">&times;</button>
              </div>  
       <?php  } ?>
             
        </tr>
        <tr>
            <td class="border">
                <label for="dept"> *Department </label>
                <select id="dept" name="deptlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT * FROM department");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['deptID']; ?>"> <?php echo $row['deptCode'];  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td class="border">
                <label for="level"> *Level </label>
                <select id="level" name="levellist"  required>
                     <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT levelID, levelDesc FROM lvl");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['levelID']; ?>"> <?php echo $row['levelDesc'];  ?> </option>
                                    
                            <?php }?>   
                    </select>
            </td>
            <td class="border">
                <label for="instructor"> *Instructor </label>
                <select id="instructor" name="instructorlist" required >
                <option value=" " selected disabled></option>
                    <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT * FROM account where accessLevel='prof'  order by FName asc");
                                //$q->execute(array($accessLevel ));
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['accountID']; ?>"> <?php echo $row['FName']." - ".$row['LName'];  ?> </option>
                                    
                            <?php }?>   
                    </select>
            </td>
            <td class="border">
                <button class="btn" name="searchBtn"> Search </button></i> </button>
            </td>
        </tr>
    </table>
  </form>
  

<br>

                  
                            <table>

<?php if(isset($_GET['searchBtn']) ||  $_GET['facultyLoadingIsUnloaded']==true  || $_GET['facultyLoadingIsLoaded']==true || $_GET['addCalendarConflict']==true) 
{         
         if ( $_GET['facultyLoadingIsUnloaded']==true  || $_GET['facultyLoadingIsLoaded']==true || $_GET['addCalendarConflict']==true) {
             $_GET['deptlist']=$_SESSION['deptlist'];
             $_GET['levellist']=$_SESSION['levellist'] ;
             $_GET['instructorlist']=$_SESSION['instructorID'];
         }
        if(empty($_GET['deptlist'])  || empty($_GET['instructorlist']) || empty($_GET['levellist']) ){ 
          $incomplete=true; ?>
           <!-- Warning Alert -->
            <div id="myAlertI" class="alert alert-warning alert-dismissible fade show">
            <strong>Warning!</strong> &nbsp Please make sure all required fields are filled.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
<?php 
         } 


                if (!empty($_GET['deptlist']) && !empty($_GET['levellist']) && !empty($_GET['instructorlist'])) { 
                    // $pdo=Database::connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $_SESSION['deptlist'] = $deptlist = $_GET['deptlist'];
                    $_SESSION['levellist'] = $levellist = $_GET['levellist'];
                    $_SESSION['instructorID'] = $_GET['instructorlist'];

                   /* echo "deptlist: ".$deptlist." - "."levellist: ".$levellist." - "."session_instructorID: ".$_SESSION['instructorID'] ." - ";
*/
                    $stmt=$pdo->prepare("select * from curriculum c natural join courseschedulingtemp natural join timestart natural join timeend  natural join day natural join classroom where (c.deptID = ?) and (c.levelID = ?)  order by curID ");
                    $stmt->execute(array($deptlist, $levellist));
                    $result= $stmt->rowCount();
                        if($result==0){ 
                        $noResult=true; ?>
                        <!-- Warning Alert -->
                      <div id="myAlertN" class="alert alert-warning alert-dismissible fade show">
                      <strong>Warning!</strong> &nbsp No courses to load.
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      </div>
<?php                } //end of if result 
                } /*<!-- if (!empty($_GET['deptlist']) && !empty($_GET['levellist']) ... -->*/             

                else if (!empty($_GET['deptlist']) && empty($_GET['levellist']) && !empty($_GET['instructorlist'])) { 
                    // $pdo=Database::connect();
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $_SESSION['deptlist'] = $deptlist = $_GET['deptlist'];
                    $_SESSION['instructorID'] = $_GET['instructorlist'];

                    /*echo "deptlist: ".$deptlist." - "."session_instructorID: ".$_SESSION['instructorID'] ." - ";*/

                    $stmt=$pdo->prepare("select * from curriculum c natural join courseschedulingtemp natural join timestart natural join timeend  natural join day natural join classroom where (c.deptID = ?)  order by curID ");
                    $stmt->execute(array($deptlist));
                    $result= $stmt->rowCount();
                        if($result==0){ 
                        $noResult=true; ?>
                        <!-- Warning Alert -->
                      <div id="myAlertN" class="alert alert-warning alert-dismissible fade show">
                      <strong>Warning!</strong> &nbsp No courses to load.
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      </div>

<?php                } //end of if result 
                } /*<!-- else if (!empty($_GET['deptlist']) && empty($_GET['levellist']) ... -->*/             
?>
                     <thead>
                        <tr class="border">
                            <th colspan="3" style="text-align: left"> Courses to Load </th>
                        </tr>
                        <tr>
                            <th class="head">Course</th>
                            <th> Schedule </th>
                            <th> </th>
                        </tr>
                     </thead>
                       
<?php               while ($row = $stmt->fetch()) {
                      $crsSchedID3=$row['crsSchedID'];
                      $crsName3=$row['crsName'];
                      $timeStart3=$row['timeStart'];
                      $timeEnd3=$row['timeEnd'];
                      $dayName3=$row['dayName'];
                      $roomNum3=$row['roomNum'];
?> 
                       <tbody>
                        <tr>
                            <td><?php echo $crsName3;?></td>
                            <td><?php echo "Room ".$roomNum3." - ".$dayName3."<br>".$timeStart3." - ".$timeEnd3;?></td>
                            <td class="btncenter"> <a href=<?php echo "submit.php?addCalendarcrsSchedID=".$crsSchedID3;?>><button class="btn2"> Add to Calendar </button></a> </td>
                        </tr>
                        </tbody>
 <?php /*$i++; */  } //end of while
              // }  //end of else
      /*  } <!-- end of else outer -->*/ 
 } /* if(isset($_GET['searchBtn'])) */ 
 ?> 
                    </table>
                </div>

<?php

if(isset($_GET['instructorlist']) || $_GET['facultyLoadingIsUnloaded']==true || $_GET['facultyLoadingIsLoaded']==true ||($_GET['addCalendarConflict']) ==true )
{

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $instructorID2 =$_SESSION['instructorID'];

   /* echo "naglaog sa kondisyon sql para sa  calndar view ";*/

    $stmt=("select *, (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, 
        (select dayName from day where f.dayID=day.dayID) as dayName, 
        (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, 
        (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, 
        (select section from section where f.secID=section.secID) as section, 
        (select crsName from curriculum where f.curID=curriculum.curID) as crsName,  
        (select totalUnits from curriculum where f.curID=curriculum.curID)   as totalUnits from facultyloading f where accountID=?");

            //$stmt->execute(array( $addCalendarcrsSchedIDFromSubmit));
    $q = $pdo->prepare($stmt);
    $q->execute(array($instructorID2));
    
       
?> 
       

      <!--  <div id="Tab" class="tabcontent"> -->
          <br>
      <div class="row">
          <div class="column">
                <table>
                   
<?php              /* echo "Printing sched to the tabular view "; */

?>
                    <tr>
                        <td colspan="6" style="text-align: left">
                            <h4 style="float:right"> Tabular View </h4>
                            <h3> Faculty Loading </h3>
                            <!-- <h5> Total No. of Units Loaded: <?php echo $_SESSION['totalUnits']; ?></h5> -->
                            *Clicking edit icon will unload the schedule from this instructor.
                            
                        </td>
                    </tr>

                    <tr>
                        <th> Action </th>
                        <!-- <th> Code </th> -->
                        <th> Subjects </th>
                        <th> Units </th>
                        <th> Section </th>
                        <th> Schedule </th>
                    </tr>



<?php 
            while ($row2 = $q->fetch()){
                            /*echo "fUnloadCrsSchedID2 ".$row2['crsSchedID'];*/
                            $fUnloadCrsSchedID2=$row2['crsSchedID'];
                            /*$code2=$row2['courseCode'];*/
                            $desc2=$row2['crsName'];
                            $totalUnits2=$row2['totalUnits'];
                            $section2=$row2['section'];
                            $roomNum2=$row2['roomNum'];
                            $dayName2=$row2['dayName'];
                            $timeStart2=$row2['timeStart'];
                            $timeEnd2=$row2['timeEnd'];
    
 ?>    


                 <tbody>
                    <tr>
                        <td class="btncenter"> <a href=<?php echo "submit.php?fUnloadCrsSchedID2=".$fUnloadCrsSchedID2;?>><button name="editBtn"  ><i class="fas fa-edit"></i> </button></a>
                    
           
                        </td>
                        <!-- <td><?php  echo $code2; ?>  </td> -->
                        <td><?php  echo $desc2; ?> </td>
                        <td><?php  echo $totalUnits2; ?> </td>
                        <td><?php  echo $section2; ?> </td>
                        <td><?php  echo "Rm ".$roomNum2."<br>".$dayName2."<br>".$timeStart2." - ".$timeEnd2; ?> </td>


                    </tr>
                 </tbody>
<?php       
            } //end of while row
?>                  <form>
                    <tr>
                        <td class="btncenter" colspan="6">
                           <button class="btn" name="genSchedBtn">  Generate Schedule </button>
                        </td>
                    </tr> 
                    </form>

<?php
/*}*///if(isset($_GET['instructorlist']))
?>               </table>     
           </div>
         
         <?php  

if(isset($_GET['instructorlist']) || $_GET['facultyLoadingIsUnloaded']==true  || $_GET['facultyLoadingIsLoaded']==true || ($_GET['addCalendarConflict']) ==true)
{
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $instructorID = $_SESSION['instructorID'];

   /* echo "faculty loading is true MEANS ISSET GET_INSTRUCTOR LIST";*/

    $stmt=("select *, (select roomNUm from classroom where f.classroomID=classroom.classroomID) as roomNum, (select dayName from day where f.dayID=day.dayID) as dayName, (select timeStart from timestart where f.timeStartID=timestart.timeStartID) as timeStart, (select timeEnd from timeend where f.timeEndID=timeend.timeEndID) as timeEnd, (select section from section where f.secID=section.secID) as section, (select crsName from curriculum where f.curID=curriculum.curID) as crsName,  (select totalUnits from curriculum where f.curID=curriculum.curID)   as totalUnits from facultyloading f where accountID=?");

            //$stmt->execute(array( $addCalendarcrsSchedIDFromSubmit));
    $q = $pdo->prepare($stmt);
    $q->execute(array($instructorID));



     $count=0;   $_SESSION['totalUnits']=0; 
    while ($row = $q->fetch()){

       /* echo "naglaog sa while para sa calendar view <br>";*/
                
        $sched[]=$row['crsName']."<br>Rm ".$row['roomNum']."<br> ".$row['timeStart']." - ".$row['timeEnd'];
        $crsName[]=$row['crsName'];
        $dayName[]=$row['dayName'];
        $roomNum[]=$row['roomNum'];
        $timeStart[]=$row['timeStart'];
        $timeEnd[]=$row['timeEnd'];
        $rspan[]=$row['timeEndID'] - $row['timeStartID'];

        $_SESSION['totalUnits']=$_SESSION['totalUnits']+$row['totalUnits'];
      

        $count++;
    }
     //including variables instantiation
     include('../includes/varInstantiation.php'); 

     $i=0; 
     while ($i < $count) {
        /*echo "naglaog sa whhile kang includes/xyIntercepts ";*/
       //including x&y intercept tester
       include('../includes/xyIntercepts.php');
       $i++; 
    }//eo while ($i < $count)



 ?>


<?php            /*   echo "Printing sched to the calendar "; */

?>                  
               
               
                    <div class="column">
                <table class="table2">
                    <tr>
                    <td colspan="7" style="text-align: left">
                        <h4 style="float:right"> Calendar View </h4>
                        <h3> Faculty Loading </h3>

                        Total No. of Units Loaded: <?php echo $_SESSION['totalUnits']; ?>
                    </td>
                    </tr>

                    <tr>
                        <th> Time </th>
                        <th> Monday </th>
                        <th> Tuesday </th>
                        <th> Wednesday </th>
                        <th> Thursday </th>
                        <th> Friday </th>
                        <th> Saturday </th>
                    </tr>
<?php
        
                    //including calendar view rows result
                    include('../includes/calViewRows.php'); 
                    /*echo " code sunod sa includes/calViewRows "; */

}//if(isset($_GET['instructorlist']))
?> 
                </table>
           </div> 
           </div>

<?php
}//if(isset($_GET['instructorlist']))
?>  

<?php Database::disconnect(); ?>

    
    <!-- bootstrap JS-->
    <script src="bootstrap/js/bootstrap.min.js"></script>

     <script type="text/javascript">
     $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertN').hide('fade');
        }, 3000); 
     });

     $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertI').hide('fade');
        }, 3000); 

     });
    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertS').hide('fade');
        }, 3000); 

     });
    $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlertC').hide('fade');
        }, 4000); 

     });
     </script>


</body>

</html>