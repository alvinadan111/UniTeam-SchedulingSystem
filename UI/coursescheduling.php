<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

//error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();
/*$isSubmitted = false;
$isCreated = false;
$isIncomplete = false;*/





?>

<!DOCTYPE html>
<html>
<head>
     <title>Course Scheduling</title>
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
     
     <script src="https://kit.fontawesome.com/a076d05399.js"></script>
     <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>  

    <link rel="stylesheet" href="CourseScheduling.css">
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
                    <label for="acadprog"> Academic Program </label>
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
                <td class="line">
                    <label for="period"> Period </label>
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
       if(empty($_POST['periodlist']) || empty($_POST['levellist']) || empty($_POST['acadlist'])){ ?>
 
            </div>
            <!-- Warning Alert -->
            <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
            <strong>Warning!</strong> &nbsp Please make sure all fields are filled.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>

<?php  }else{ ?>

   <?php
    $pdo=Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $acadlists = $_POST['acadlist'];
    $levellists = $_POST['levellist'];
    $periodlists = $_POST['periodlist'];

     
        $stmt=$pdo->prepare("select c.curID, c.syID, c.periodID, course.courseCode, course.courseName from curriculum c 
            left outer join course ON c.courseID=course.courseID 
            left outer join academicprog ON c.acadProgID=academicprog.acadProgID  
            left outer join lvl ON c.levelID=lvl.levelID
            left outer join period ON c.periodID=period.periodID
            where (c.periodID = ?) and (c.levelID = ?) and (c.acadProgID = ?)
            order by curID ");
        $stmt->execute(array($acadlists, $levellists,  $periodlists));
        $result= $stmt->rowCount();
         if($result==0){ ?>
 
            </div>
            <!-- Warning Alert -->
            <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
            <strong>Warning!</strong> &nbsp No result from the chosen academic program.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
    <?php } ?>
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
            $_SESSION['curID']=$row['curID'];
            $_SESSION['syID']=$row['syID'];
            $_SESSION['periodID']=$row['periodID'];
            $courseCode=$row['courseCode'];
            $courseName=$row['courseName'];

            /*if ($result>0) {
    echo "laman session: syId:".$_SESSION['syID']." periodid ".
            $_SESSION['periodID']." curid: ".$_SESSION['curID'];
}*/

    ?>       <tbody>
                <tr>
                <td><?php echo $courseCode;?></td>
                <td><?php echo $courseName;?></td>
                <td></td>
            

                <td style="text-align: center;"> <a href="<?php echo 'courseschedulingdata.php'; ?>"  class="btn"><i
                            class="fas fa-edit"></i></a> </td> 
               <!--  <td style="text-align: center;"> <a href="<?php //echo 'courseschedulingdata.php?curID='.$_SESSION['curID']; ?>"  class="btn"><i
                            class="fas fa-edit"></i></a> </td> --> 
                </tr>
            </tbody>
           

       
 <?php } } } Database::disconnect(); ?>                       
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
