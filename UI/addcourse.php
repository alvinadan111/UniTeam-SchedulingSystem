<?php
session_start();
// session_destroy();
// exit;
require '../database.php';
$added = false;
$duplicated = false;
$deleted = false;
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//ADD COURSE
if(isset($_POST['addCourse'])){
   
    //check for duplicate course
    $cc = $_POST['CC'];
    $cn = $_POST['CN'];
    $lec = $_POST['LEC'];
    $lab = $_POST['LAB'];
    $unit = $_POST['UNIT'];

    $dup = $pdo->prepare("SELECT * FROM course where courseCode = :CC or courseName = :CN");
    $dup->bindValue(':CC',$cc);
    $dup->bindValue(':CN',$cn);
    $dup->execute();
    $dupRes = $dup->rowCount();
    if($dupRes > 0){
        $duplicated = true;
        header("refresh:1; url = addcourse.php");
    }else{
        $st = $pdo->prepare("INSERT INTO course (courseCode, courseName, lec, lab, totalUnits) VALUES (?,?,?,?,?)");
        $st->execute(array($cc, $cn, $lec, $lab, $unit));
        $added = true;

        header("refresh:1; url = addcourse.php");

    }
}

//DELETE COURSE
if(isset($_POST['delCourse'])){

    $radioSelect = $_POST['inpt'];
    $del = $pdo->prepare("DELETE FROM course where courseID = :CC");
    $del->bindValue(':CC',$radioSelect);
    $del->execute();

    $newTBL = $pdo->prepare("CREATE table temp(
        courseID int(5) unsigned primary key not null auto_increment,
        courseCode varchar(10) not null, 
        courseName varchar(50) not null,
        lec float(3,2) unsigned not null,
        lab float(3,2) unsigned not null,
        totalUnits float(3,2) unsigned not null
    );");
    $newTBL->execute();

    //insert to new Table 
    $retieve = $pdo->prepare("SELECT courseCode, courseName, lec, lab, totalUnits from course");
    $retieve->execute();
    $getResult = $retieve->fetchAll(PDO::FETCH_ASSOC);


    //insert  to new table 
    for($i = 0; $i < count($getResult); $i++){
        $insertToTemp = $pdo->prepare("INSERT INTO temp (courseCode, courseName, lec, lab, totalUnits) VALUES (?,?,?,?,?)");
        $insertToTemp->execute(array($getResult[$i]['courseCode'], $getResult[$i]['courseName'], $getResult[$i]['lec'], $getResult[$i]['lab'],$getResult[$i]['totalUnits'],));
    }
    //delete old table
    $deleteOld = $pdo->prepare("DROP TABLE course;");
    $deleteOld->execute();
    //rename new table
    $renameNew = $pdo->prepare("ALTER TABLE temp RENAME TO course");
    $renameNew->execute();
    $deleted = true;
    header("refresh:1; url = addcourse.php");
}



//UPDATE Course

if(isset($_POST['saveBTN'])){
    $newCC = $_POST['newCC'];
    $newCN = $_POST['newCN'];
    $newLEC = $_POST['newLEC'];
    $newLAB = $_POST['newLAB'];
    $newUNIT = $_POST['newUNIT'];

    //CHECK IF USER INPUT IS DUPLICATE
    $dup = $pdo->prepare("SELECT * FROM course where courseCode = :courseC or courseName = :courseN");
    $dup->bindValue(':courseC',$newCC);
    $dup->bindValue(':courseN',$newCN);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        echo "<script> alert('obob Duplicate Entry'); </script>";
        header("refresh:2; url = academic.php");
    }else{

        $updateQuery = $pdo->prepare("UPDATE course SET courseCode = :cc, courseName = :cn, lec = :lc, lab = :lb, totalUnits = :tot WHERE courseID = :papalitan");
        $updateQuery->bindValue(':cc',$newCC);
        $updateQuery->bindValue(':cn',$newCN);
        $updateQuery->bindValue(':lc',$newLEC);
        $updateQuery->bindValue(':lb',$newLAB);
        $updateQuery->bindValue(':tot',$newUNIT);
        $updateQuery->bindValue(':papalitan',$_SESSION['courseID']);
        $updateQuery->execute();
        echo "<script> alert('Napalitan na!'); </script>";

        header("refresh:1; url = addcourse.php");


    }

}




?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="bootstrap/js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="Addcourse.css">
</head>

<body>
    <h1> Course </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="coursescheduling.php"> Course </a>
    </div>
    <div id="add" class="tabcontent">
        <table>
            <caption>
                <div id="active" class="navbar">
                    <a class="nav active" onclick="javascript:openPage('add', this)" id="defaultOpen"><i
                            class="fas fa-plus-square"></i> Add</a>
                    <a class="nav" onclick="javascript:openPage('view', this)"><i class="fas fa-eye"></i> View
                    </a>
                </div>
            </caption>
            <form method = "POST">
            <tr>
                <th> Course Code </th>
                <th> Course Name </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
               
            </tr>
            <tr>
            
                <td> <input type="text" placeholder="Course Code " name="CC" required> </td>
                <td> <input type="text" placeholder="Course Name " name="CN" required> </td>
                <td> <input type="number" placeholder="Lecture " name="LEC" required> </td>
                <td> <input type="number" placeholder="Laboratory " name="LAB" required> </td>
                <td> <input type="number" placeholder="Units " name="UNIT" required> </td>
             
            </tr>
            <tr>
                <td class="border" colspan="7">
                    <button type="submit" name = "addCourse">Add Program</button>
                </td>
            </tr>
            </form>

        </table>
    </div>

    <div id="view" class="tabcontent">
        <table>
        <caption>
            <div id="active" class="navbar">
                <a class="nav " onclick="openPage('add', this)" id="defaultOpen"><i
                        class="fas fa-plus-square"></i> Add</a>
                <a class="nav active" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                </a>
            </div>
        </caption>
        <form method = "POST">
        <tr>
            <th> Select </th>
            <th> Course Code </th>
            <th> Course Name </th>
            <th> Lec </th>
            <th> Lab </th>
            <th> Units </th>
    
        </tr>
        <?php 
              $show = $pdo->query("SELECT courseID, courseCode, courseName, lec, lab, totalUnits FROM course");
              while ($row = $show->fetch()) {
                  echo "<tr>
                    <td><input type='radio' name = 'inpt' value =".$row['courseID']." required></td>
                    <td>".$row['courseCode']."</td>
                    <td>".$row['courseName']."</td>
                    <td>".$row['lec']."</td>
                    <td>".$row['lab']."</td>
                    <td>".$row['totalUnits']."</td>
                  </tr>";
              }   
           ?>

                <tr>
                    <td colspan="6">
                        <button type="submit" name = "delCourse">Delete Course</button>
                        <button type="submit" name = "updateCourse" >Update Course </button>
                  
                </td>
            </tr>
            </form>
        </table>
    </div>




    <form method = "POST"> 
    <div class="addform-popup" id="myForm">
        <div class="addcontainer">

            <table id="myTable" class="addcurriculum">
               
                    <th>New Course Code</th>
                    <th>New Course Name</th>
                    <th>New Lec</th>
                    <th>New Lab</th>
                    <th>New Unit</th>
                </tr>
                <tr>
                    <td> <input type="text" placeholder="Course Code " name="newCC" required> </td>
                    <td> <input type="text" placeholder="Course Name " name="newCN" required> </td>
                    <td> <input type="number" placeholder="Lecture " name="newLEC" required> </td>
                    <td> <input type="number" placeholder="Laboratory " name="newLAB" required> </td>
                    <td> <input type="number" placeholder="Units " name="newUNIT" required> </td>
                </tr>
                <tr>
                    <td colspan = "5">
                        <input type = "submit" class="btnchange" name = "saveBTN" value = "Save Change"></input>
                        <input type = "reset" class="btnchange" name = "cancel" value = "Cancel" onclick = "closeForm()"></input>
                    </td>
                </tr>
               
            </table>
         
        </div>
    </div>
     </form>



    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
    
        }
        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>


    <?php if($added == true){ ?>
        <script>
            swal({
            title: "Successfully Added",
            text: "Course Added",
            icon: "success",
            });
    </script>
    <?php }  ?>
    
    <?php if($duplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "Course Is Already Added",
            icon: "warning",
            });
    </script>
    <?php }  ?>

    <?php if($deleted == true){ ?>
        <script>
            swal({
            title: "Successfully Deleted",
            text: "Course Deleted",
            icon: "success",
            });
    </script>
    <?php }  ?>

    


</body>
<script>
    function openPage(pageName, elmnt) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        document.getElementById(pageName).style.display = "block";
        elmnt.style.backgroundColor = color;
    }
    document.getElementById("defaultOpen").click();
</script>
</html>


<?php

if(isset($_POST['updateCourse'])){
    echo "<script> openForm(); 
    </script>";
    
    $_SESSION['courseID'] = $_POST['inpt']; 
}
?>