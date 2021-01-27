<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;


require '../database.php';
$isSubmitted = false;
$isCreated = false;
$isIncomplete = false;

if(!empty($_POST['dayID'])&& !empty($_POST['timeStartID']) && !empty($_POST['timeEndID']) && !empty($_POST['secID'])){
        $isSubmitted = true;
    }

if(isset($_POST['saveSubmitBtn']))
{
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $curID = $_POST['curID'];
    $dayID = $_POST['dayID'];
    $timeStartID = $_POST['timeStartID'];
    $timeEndID = $_POST['timeEndID'];
    $secID = $_POST['secID'];
    $classroomID = $_POST['classroomID'];


    if(empty($_POST['dayID']) || empty($_POST['timeStartID']) || empty($_POST['timeEndID'])|| empty($_POST['secID'])|| empty($_POST['classroomID'])){
 
        $isIncomplete = true;
    }else{

            $stmt = $pdo->prepare("INSERT INTO coursescheduling (curID, dayID, timeStartID, timeEndID, secID, classroomID)
            VALUES (?,?,?,?,?,?,?,?)");
            $stmt->execute(array($curID,$dayID,$timeStartID,$timeEndID,$secID,$classroomID));
            $isCreated = true;
            // header("refresh:3; url = index.php");
    }

}

?>

<!DOCTYPE html>
<html>

<head>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="courseSchedulingdata.css">
</head>

<body>
    <div class="container">
        <div class="btncontainer">
            <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="coursescheduling.php"> Course Scheduling <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="courseschedulingdata.php"> Schedule </a>
        </div>
        <h1 class="textcolor"> Course Scheduling </h1>

<form method="post" > <!--action="#courseSchedulingdata.php-->
        <table class="table1">
            <tr>
                <th colspan="7"> Schedule </th>
               
            </tr>
            <tr>
                <td class="noborder">
                    <label for="day"> Day </label>
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
                <td class="noborder">
                <label for="rooms">Available Rooms</label>
            <div class="hs">
                <select id="rooms" name="classroomID"  style="align-items: center;" required>
                     <option value=" " selected disabled></option>
                         <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT * FROM coursescheduling c
                                    left outer join classroom ON c.classroomID=classroom.classroomID");
                               // $row2 = $stmt->fetch();
                            while ($row = $stmt->fetch()) { 
                                    $count=0; $count2=0;

                                if ((row['timeStartID']>= $_POST['timeStartID'] &&  row['timeEndID']<= $_POST['timeEndID'])&&
                                    (($_POST['timeStartID']&&$_POST['timeEndID']<=row['timeStartID']) ||
                                    ($_POST['timeStartID']&&$_POST['timeEndID']<=row['timeEndID']))) {
                                    $conflictTime=false;
                                } else {
                                   $conflictTime=true; 
                                }

                                if (row['dayID']== $_POST['dayID'] && $conflictTime==true) {
                                    $conflictTime=true; 
                                   /* $roomConflict[$count]=row['classroomID'];
                                    $count++ */
                                }else{
                                  /*  $roomAvailable[$count2]=row['classroomID'];
                                    $count2++; */
                        ?>
                                <option value="<?php echo $row['classroomID']; ?>"> <?php echo $row['roomNum']." - ".$row['buildingCode'];  ?> </option>
                                                         
                        <?php  } }?>   
                    
                </select>
                </td>
                <td class="noborder" style="border-right: 1px solid black">
                <input type="submit" name="saveSubmitBtn"  value="Save & Submit"> </td>

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
    </div>
     </form>



    <?php  Database::disconnect(); ?>   

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>


    <?php if($isSubmitted == true){ ?>
        <script>
            swal({
            title: "Successfully Submitted",
            text: "Proceed to adding a room available",
            icon: "success",
            });
    </script>
    <?php }  ?>


     <?php if($isCreated == true){ ?>
        <script>
            swal({
            title: "Successfully Created a Schedule",
            text: "",
            icon: "success",
            });
    </script>
    <?php }  ?>
    <?php if($isIncomplete == true){ ?>
        <script>
            swal({
            title: "Incomplete input",
            text: "Please fill out all required fields",
            icon: "warning",
            });
    </script>
    <?php }  ?>

     
</body>

</html>