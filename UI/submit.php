<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

//error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();

if (!empty($_GET['addCalendarcrsSchedID'])) {
	$_SESSION['addCalendarcrsSchedID'] = $_GET['addCalendarcrsSchedID'];
    $accountID =  $_SESSION['instructorID'];

    echo "session_addCalendarcrsSchedID ".$_SESSION['addCalendarcrsSchedID']." - " ;

		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = ("SELECT * from coursescheduling where crsSchedID=?");
        	$q = $pdo->prepare($sql);
            $q->execute(array($_SESSION['addCalendarcrsSchedID']));
            $row = $q->fetch(PDO::FETCH_ASSOC);

            //$crsSchedID = $row['crsSchedID'];
            $classroomID = $row['classroomID'];
            $dayID = $row['dayID'];
            $timeStartID = $row['timeStartID'];
            $timeEndID = $row['timeEndID'];
            $secID = $row['secID'];
            $curID = $row['curID'];


		$conflictBW=false; $conflictOut=false; 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = ("SELECT * from facultyloading where accountID=?");
        	$q = $pdo->prepare($sql);
            $q->execute(array($accountID));
            $result = $q->rowCount();

            //check room and day conflicts
            $count=0;  $conflictCount=0; 
            while ($row2 = $q->fetch()) { 
                
                    if ($timeStartID >=  $row2['timeStartID'] &&  $timeEndID <= $row2['timeEndID'] ){
                            $conflictBW=true;
                            echo "conflictBW true  ";
                   }else{
                    $conflictBW=false;
                            echo "conflictBW false  ";
                   }

                   if (($timeStartID < $row2['timeStartID'] && $timeEndID <=$row2['timeStartID']) ||
                      ($timeStartID >= $row2['timeEndID'] && $timeEndID  > $row2['timeEndID'])) {
                       $conflictOut=false;
                     echo "conflictOut false ";
                   } else {
                      $conflictOut=true; 
                       echo "conflictOut true ";
                   }

                   if ($row2['dayID']== $dayID && ($conflictOut==true || $conflictBW==true)) {
                       echo "theres a conflicted room " ;
                       $conflictCount++;

                       /*echo "Facultyloadingconflict is true - ";
                        $_GET['deptlist']=$_SESSION['deptlist'];
             			$_GET['levellist']=$_SESSION['levellist'] ;
             			$_GET['instructorlist']=;*/
                        header("Location: facultyloading.php?addCalendarConflict=true&deptlist=".$_SESSION['deptlist']."&levellist=".$_SESSION['levellist']."&instructorlist=".$_SESSION['instructorID']); 
                   }else{

                         echo "No conflict count: ".$count ;
                         $count++; 
                          
                         }                    
         }

          if ($conflictCount==0 ){
     		echo "inserted  to facultyloading and deleted from coursescheduling";
     		$stmt = $pdo->prepare("INSERT INTO facultyloading (crsSchedID,classroomID,dayID,timeStartID,timeEndID,secID,curID, accountID)
            VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute(array($_SESSION['addCalendarcrsSchedID'],$classroomID,$dayID,$timeStartID,$timeEndID,$secID,$curID,$accountID));

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM courseschedulingtemp WHERE crsSchedID = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($_SESSION['addCalendarcrsSchedID']));

            echo "Facultyloading is loaded true -  session_addCalendarcrsSchedID = ".$_SESSION['addCalendarcrsSchedID']." - " ;
            header("Location:facultyloading.php?facultyLoadingIsLoaded=true");
          }
} /*eo if ($_GET)*/

   		
     

     if (!empty($_GET['fUnloadCrsSchedID2'])) {
     	echo "deleted fUnloadCrsSchedID";

     	$crsSchedID2=$_GET['fUnloadCrsSchedID2'] ;

     	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = ("SELECT * from facultyloading where crsSchedID=?");
        	$q = $pdo->prepare($sql);
            $q->execute(array($crsSchedID2));
            $row = $q->fetch(PDO::FETCH_ASSOC);

     	
     	$classroomID2 = $row['classroomID'];
        $dayID2 = $row['dayID'];
        $timeStartID2 = $row['timeStartID'];
        $timeEndID2 = $row['timeEndID'];
        $secID2 = $row['secID'];
        $curID2 = $row['curID'];

     	$stmt = $pdo->prepare("INSERT INTO courseschedulingtemp (crsSchedID,classroomID,dayID,timeStartID,timeEndID,secID,curID)
            VALUES (?,?,?,?,?,?,?)");
            $stmt->execute(array($crsSchedID2,$classroomID2,$dayID2,$timeStartID2,$timeEndID2,$secID2,$curID2));

     	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM facultyloading WHERE crsSchedID = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($crsSchedID2));

            header("Location:facultyloading.php?facultyLoadingIsUnloaded=true");
     }


   /*  if ($_GET['generateSched']==true ) {
     	echo "schedule generated";

              header("Location:facultyloading.php?scheduleGenerated=true");
     } */ /*eo if ($_GET['generateSched']==true )*/  



Database::disconnect();  



?>