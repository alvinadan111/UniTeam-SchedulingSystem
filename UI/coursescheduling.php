<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();

$noResult = false;
$isIncomplete = false;


?>

<!DOCTYPE html>
<html>
<head>
     <title>Course Scheduling</title>
     <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     
     <script src="https://kit.fontawesome.com/a076d05399.js"></script>
     <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
     <script src="bootstrap/js/sweetalert.min.js"></script>
     <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="courseScheduling.css">
</head>
<body>
         <form method="post" >
         <h1 class="textcolor"> Course Scheduling </h1>
            <div class="btncontainer">
                <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
                <a class="navtop" href="coursescheduling.php"> Course Scheduling </a>
            </div>
        <table> 

            <tr>
                <td colspan="3">
                    <p> Academic Programs </p>
                    
            </tr>
            <tr>
                <td>
                    <label for="acadprog"> *Academic Program </label>
                    <select id="acadprog" name="acadlist"  required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT acadProgID, progCode FROM academicprog");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['acadProgID']; ?>"> <?php echo $row['progCode'];  ?> </option>
                                    
                            <?php }?>     
                    </select>
                     
                </td>
                <td>
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
                <td class="line">
                    <label for="period"> *Period </label>
                    <select id="period" name="periodlist"  required>
                        <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT periodID, periodDesc FROM period");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['periodID']; ?>"> <?php echo $row['periodDesc'];  ?> </option>
                                    
                            <?php }?> 
                    </select>
                    <input type="submit" name="btnSearch" value="Search"> </td>
                </td>
            </tr>
        </table>
         </form>
        <br>
<?php if(isset($_POST['btnSearch']))
{ 
       if(empty($_POST['periodlist']) || empty($_POST['levellist']) || empty($_POST['acadlist'])){ 
 
           $isIncomplete = true;

 }else{ ?>

   <?php
    $pdo=Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $acadlists = $_POST['acadlist'];
    $levellists = $_POST['levellist'];
    $periodlists = $_POST['periodlist'];

     
        $stmt=$pdo->prepare("select c.levelID, c.syID, c.deptID, c.curID, c.syID, c.periodID, course.courseCode, course.courseName from curriculum c 
            left outer join course ON c.courseID=course.courseID 
            left outer join academicprog ON c.acadProgID=academicprog.acadProgID  
            left outer join lvl ON c.levelID=lvl.levelID
            left outer join period ON c.periodID=period.periodID
            where (c.periodID = ?) and (c.levelID = ?) and (c.acadProgID = ?)
            order by curID ");
        $stmt->execute(array($acadlists, $levellists,  $periodlists));
        $result= $stmt->rowCount();
         if($result==0){ 
            $noResult = true;
            
   } else{ ?>
                <table class="table">
              <thead>
                <tr>
                <th>Course Code</th>
                <th>Description</th>
                <th>Schedule</th>
                <th>Action </th>                      
                </tr>
              </thead>

    <?php 
                while ($row = $stmt->fetch()) 
                      {
                        /*$_SESSION['deptID']=*/$deptID=$row['deptID'];
                        /*$_SESSION['curID']=*/$curID=$row['curID'];
                        /*$_SESSION['syID']=*/$syID=$row['syID'];
                        $_SESSION['periodID']=$periodID=$row['periodID'];
                        $levelID=$row['levelID'];
                        $courseCode=$row['courseCode'];
                        $courseName=$row['courseName'];


    ?>                  <tbody>
                            <tr>
                            <td><?php echo $courseCode;?></td>
                            <td><?php echo $courseName;?></td>
                            <td></td>
                        

                            <td style="text-align: center;"> <a class="btn"  href=<?php echo "submit.php?crsSchedulingActionCurID=".$curID."&actionDeptID=".$deptID."&actionSyID=".$syID."&actionPeriodID=".$periodID."&actionLevelID=".$levelID;?>  ><i class="fas fa-edit"></i></a> </td> 
                            </tr>
                        </tbody>
                       

                   
    <?php       } /*eo while*/ 
        } //end of inner else 
  }  //end of else 
} /*if(isset($_POST['btnSearch']))*/
 Database::disconnect(); ?>                       
</table> 
    <script src="bootstrap/js/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>

       <?php if($noResult == true){ ?>
        <script>
            swal({
            title: "No Result Found",
            text: "Add a record now",
            icon: "warning",
            });
    </script>
    <?php }  ?>

    <?php if($isIncomplete == true){ ?>
        <script>
            swal({
            title: "Incomplete Input",
            text: "Please fill out all required fields",
            icon: "warning",
            });
    </script>
    <?php }  ?>
</body>
</html>
