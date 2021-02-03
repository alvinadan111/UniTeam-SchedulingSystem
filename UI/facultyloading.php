<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

//error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();
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
    <link rel="stylesheet" href="Facultyloading.css">
</head>

<body>
    <h1> Faculty Loading </h1> 
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="facultyloading.php"> Faculty Loading </a>
    </div>
  <form method="get">
    <table class="top">
        <tr>
            <th colspan="4" class="border"> Search Instructor</th>
           <?php if ($_GET['scheduleGenerated']==true) { ?>
                 <!-- Success Alert -->
                    <div id="myAlert" class="alert alert-success alert-dismissible fade show">
                        <strong>Success!</strong> Schedule has been generated.
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
       <?php  } ?>
       <?php if ($_GET['addCalendarConflict']==true) { ?>
                 <!-- Error Alert -->
              <div id="myAlert" class="alert alert-danger alert-dismissible fade show">
                <strong>Error!</strong> &nbsp Cannot add to calendar for there's a conflict to the schedule of this professor!
                <button type="button"  class="close" data-dismiss="alert">&times;</button>
              </div>  
       <?php  } ?>
       
        </tr>
        <tr>
            <td class="border">
                <label for="dept"> Department </label>
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
                <label for="level"> Level </label>
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
                <label for="instructor"> Instructor </label>
                <select id="instructor" name="instructorlist" required >
                <option value=" " selected disabled></option>
                    <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT * FROM account where accessLevel='prof'");
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
  



                    <div class="row">
                          <div class="column">
                            <table>

     <?php if(isset($_GET['searchBtn'])) {
        if(empty($_GET['deptlist']) || empty($_GET['levellist']) || empty($_GET['instructorlist'])){ ?>
                         
                                    </div>
                                    <!-- Warning Alert -->
                                    <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
                                    <strong>Warning!</strong> &nbsp Please make sure all fields are filled.
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    </div>

         <?php  }else{ 
                                    // $pdo=Database::connect();
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $deptlist = $_GET['deptlist'];
                                    $levellist = $_GET['levellist'];
                                    $_SESSION['instructorID'] = $_GET['instructorlist'];

                                        $stmt=$pdo->prepare("select * from curriculum c natural join coursescheduling natural join timestart natural join timeend
                                            where (c.deptID = ?) and (c.levelID = ?) 
                                            order by curID ");
                                        $stmt->execute(array($deptlist, $levellist));
                                        $result= $stmt->rowCount();
                                         if($result==0){ ?>
                                            <!-- Warning Alert -->
                                            <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
                                            <strong>Warning!</strong> &nbsp No result found.
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            </div>
                                <?php   } //end of if result 
                      else { ?>
                                
                                    <!-- <td colspan="3" class="border">
                                        <label for="searchcode">Enter The Course Code To Search:</label>
                                        <input type="search" id="searchcode" name="searchcode">
                                    </td> -->
                                 <thead>
                                    <tr class="border">
                                        <th class="border" colspan="3"> Courses to Load </th>
                                    </tr>
                                    <tr>
                                        <th class="head">Course</th>
                                        <th> Schedule </th>
                                        <th> </th>
                                    </tr>
                                </thead>
                       
                        <?php    while ($row = $stmt->fetch()) 
                                {
                                    $crsSchedID=$row['crsSchedID'];
                                     $crsName=$row['crsName'];
                                     $timeStart=$row['timeStart'];
                                     $timeEnd=$row['timeEnd'];

                                        $i=0;
                                         if ($result>0 && $i!=1) {
                                            echo "laman mga row[]: crsName:".$crsName." timeStart ".
                                            $timeStart." timeEnd: ".$timeEnd."  instructorlist " .$_SESSION['instructorID']." crsSchedID".$crsSchedID;
                                         } //end of if result>0 ?> 
                                      <tbody>
                                        <tr>
                                            <td><?php echo $crsName;?></td>
                                        <td><?php echo $timeStart." - ".$timeEnd;?></td>
                                        <td class="btncenter"> <a href=<?php echo "submit.php?addCalendarcrsSchedID=".$crsSchedID;?>><button> Add to Calendar </button></a> </td>
                                        </tr>
                                      </tbody>
            <?php /*$i++; */    } //end of while
                        }  //end of else
                    } /*<!-- end of else outer -->*/ 
                } ?> <!-- end of if isset -->
                                </table>
                              </div>



    <?php if(($_GET['facultyLoadingIsLoaded']==true)||($_GET['facultyLoadingIsUnloaded']==true)||($_GET['scheduleGenerated']==true)) { 
        echo "Printing sched to the calendar "; ?>
        <div class="column">
            <div id="Cal" class="tabcontent">
                <table class="table2">
                    <tr>
                        <td colspan="7" class="border">
                            <button class="tablink" onclick="openPage('Tab', this,)">Tabular View</button>
                            <button class="tablink01" onclick="openPage('Cal', this,)" id="defaultOpen">Calendar
                                View</button>
                            <h3> Faculty Loading </h3>

                            Total No. of Units Loaded:
                        </td>
<?php
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $addCalendarcrsSchedIDFromSubmit = $_SESSION['addCalendarcrsSchedID'];

    if (($_GET['facultyLoadingIsLoaded']==true)) {
        $stmt=$pdo->prepare("select * from facultyloading natural join  classroom natural join day natural join timestart natural join timeend natural join section natural join course where accountID=?");

        $stmt->execute(array( $addCalendarcrsSchedIDFromSubmit));
    
    } else if (($_GET['facultyLoadingIsUnloaded']==true)||($_GET['scheduleGenerated']==true)) {
         $stmt=$pdo->prepare("select * from facultyloading  natural join classroom natural join day natural join timestart natural join timeend natural join section natural join course ");
    }
        

        
        while ($row = $stmt->fetch()){
            $totalUnits=$row['totalUnits'];
            $roomNum=$row['roomNum'];
            $dayName=$row['dayName'];
            $timeStart=$row['timeStart'];
            $timeEnd=$row['timeEnd'];

            $totalUnits+=$totalUnits;
?> 
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
                    <tr>
                        <td> 7am </td>
                        <td> <?php $day="Monday"; $timeS="7 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        
                        <td> <?php $day="Tuesday"; $timeS="7 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="7 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="7 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="7 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="7 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 8am </td>
                        <td><?php $day="Monday"; $timeS="8 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="8 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="8 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="8 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="8 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="8 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 9am </td>
                        <td> <?php $day="Monday"; $timeS="9 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?></td>
                        <td> <?php $day="Tuesday"; $timeS="9 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?></td>
                        <td><?php $day="Wednesday"; $timeS="9 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="9 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td> <?php $day="Friday"; $timeS="9 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?></td>
                        <td><?php $day="Saturday"; $timeS="9 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 10am </td>
                        <td><?php $day="Monday"; $timeS="10 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="10 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="10 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="10 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="10 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="10 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 11am </td>
                        <td><?php $day="Monday"; $timeS="11 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="11 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td> <?php $day="Wednesday"; $timeS="11 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?></td>
                        <td><?php $day="Thursday"; $timeS="11 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="11 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="11 am"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 12nn </td>
                        <td><?php $day="Monday"; $timeS="12 nn"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="12 nn"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="12 nn"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="12 nn"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="12 nn"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="12 nn"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 1pm </td>
                        <td><?php $day="Monday"; $timeS="1 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="1 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="1 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="1 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="1 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="1 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 2pm </td>
                        <td><?php $day="Monday"; $timeS="2 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="2 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="2 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="2 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="2 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="2 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 3pm </td>
                        <td><?php $day="Monday"; $timeS="3 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="3 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="3 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="3 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td> <?php $day="Friday"; $timeS="3 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?></td>
                        <td><?php $day="Saturday"; $timeS="3 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                      
                    </tr>
                    <tr>
                        <td> 4pm </td>
                        <td><?php $day="Monday"; $timeS="4 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="4 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="4 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="4 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="4 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="4 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 5pm </td>
                        <td><?php $day="Monday"; $timeS="5 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="5 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="5 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="5 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="5 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="5 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 6pm </td>
                        <td><?php $day="Monday"; $timeS="6 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="6 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="6 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="6 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="6 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="6 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 7pm </td>
                        <td><?php $day="Monday"; $timeS="7 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="7 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="7 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="7 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="7 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="7 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                    <tr>
                        <td> 8pm </td>
                        <td><?php $day="Monday"; $timeS="8 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Tuesday"; $timeS="8 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Wednesday"; $timeS="8 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Thursday"; $timeS="8 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Friday"; $timeS="8 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                        <td><?php $day="Saturday"; $timeS="8 pm"; if ($day==$dayName && $timeS==$timeStart) {
                            echo $code." ".$dayName." ".$roomNum." ".$timeStart." - ".$timeEnd; } ?> </td>
                    </tr>
                </table>
            </div>
<?php } //end of while row

 ?>


            <div id="Tab" class="tabcontent">
            <form method="get">
                <table>
                    <tr>
                        <td colspan="6" class="border">
                            <button class="tablink01" onclick="openPage('Tab', this,)" id="defaultOpen">Tabular
                                View</button>
                            <button class="tablink" onclick="openPage('Cal', this,)">Calendar View</button>
                            <h3> Faculty Loading </h3>
                            Total No. of Units Loaded: <?php echo $totalUnits; ?>
                        </td>
                    </tr>




                    <tr>
                    <thead>
                        <th> Action </th>
                        <th> Code </th>
                        <th> Description </th>
                        <th> Units </th>
                        <th> Section </th>
                        <th> Schedule </th>
                    </thead>
                    </tr>

<?php
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $addCalendarcrsSchedIDFromSubmit = $_SESSION['addCalendarcrsSchedID'];

     
        $stmt=$pdo->prepare("select * from facultyloading  natural join classroom natural join day natural join timestart natural join timeend natural join section natural join course where accountID=?");

        $stmt->execute(array($_SESSION['instructorID']));

        if (($_GET['facultyLoadingIsLoaded']==true)) {
        $stmt=$pdo->prepare("select * from facultyloading  natural join classroom natural join day natural join timestart natural join timeend natural join section natural join course where accountID=?");

        $stmt->execute(array( $addCalendarcrsSchedIDFromSubmit));
    
    }


         else if (($_GET['facultyLoadingIsUnloaded']==true)||($_GET['scheduleGenerated']==true)) {
         $stmt=$pdo->prepare("select * from facultyloading  natural join classroom natural join day natural join timestart natural join timeend natural join section natural join course ");
    }
    
        while ($row = $stmt->fetch()){
            $fUnloadCrsSchedID=$row['crsSchedID'];
            $code=$row['courseCode'];
            $desc=$row['courseName'];
            $totalUnits=$row['totalUnits'];
            $section=$row['section'];
            $roomNum=$row['roomNum'];
            $dayName=$row['dayName'];
            $timeStart=$row['timeStart'];
            $timeEnd=$row['timeEnd'];
?> 
   

                 <tbody>
                    <tr>
                        <td class="btncenter"> <a href=<?php echo "submit.php?fUnloadCrsSchedID=". $fUnloadCrsSchedID;?>><button class="btnaction"  onclick="openForm()"><i
                                    class="fas fa-edit"></i> </button></a>

                                       
                        </td>
                        <td><?php  echo $code; ?>  </td>
                        <td><?php  echo $desc; ?> </td>
                        <td><?php  echo $totalUnits; ?> </td>
                        <td><?php  echo $section; ?> </td>
                        <td><?php  echo $roomNum." ".$dayName." ".$timeStart." - ".$timeEnd; ?> </td>
                    </tr>
                 </tbody>
<?php } //end of while row
?>
                    <tr>
                        <td class="btncenter" colspan="6">
                           <a href=<?php echo "submit.php?generateSched=true";?>><button class="btn">  Generate Schedule </button></a> 
                        </td>
                    </tr>   
                </table>  
              </form>    
            </div>
        </div>
    </div>  

<?php } /*end of if(isset($_GET['searchBtn']) && isset($_GET['searchBtn']==true))*/

 ?>


<?php Database::disconnect(); ?>

    <div class="unloadform-popup" id="myForm">
        <div class="unloadcontainer">
            <p style="color: white;"> By clicking OK button will unload the subject from the instructor. Do you wish to
                continue? </p>
            <button class="btnunload"> OK </button>


          

            <button class="btnunload" onclick="closeForm()"> Cancel </button>
        </div>
    </div>
    <script>
        function openPage(pageName, elmnt,) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
            }
            document.getElementById(pageName).style.display = "block";
            elmnt.style.backgroundColor = color;
        }
        document.getElementById("defaultOpen").click();
    </script>
    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }
        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>

    
    <!-- bootstrap JS-->
    <script src="bootstrap/js/bootstrap.min.js"></script>

     <script type="text/javascript">
     $(document).ready(function()
     {
        setTimeout(function (){
            $('#myAlert').hide('fade');
        }, 3500); 
     });



</body>

</html>