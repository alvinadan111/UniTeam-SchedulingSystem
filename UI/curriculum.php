<?php
session_start();
require '../database.php';
require 'dropdown.php';

$isDuplicated = false;
$isIncomplete = false;
$isRegistered = false;

if(isset($_POST['saveBTN'])){
  
    $pdo=Database::connect();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if(empty($_POST['yearlist']) || empty($_POST['periodlist']) || empty($_POST['levellist']) || empty($_POST['progcodelist']) || empty($_POST['codelist']) || empty($_POST['deptcodelist']) || empty($_POST['complalist']) || empty($_POST['courseN']) || empty($_POST['lecture']) || empty($_POST['labo']) || empty($_POST['uni']) ){
       
        $isIncomplete = true;
        echo "<script> alert('Pumasok sa incomplete'); </script>";

        }else{
            
            $YR = $_POST['yearlist'];
            $PER = $_POST['periodlist'];
            $lev = $_POST['levellist'];
            $prog = $_POST['progcodelist'];
            $crs = $_POST['codelist'];
            $dep = $_POST['deptcodelist'];
            $com = $_POST['complalist'];
            $CN = $_POST['courseN'];
            $lecc = $_POST['lecture'];
            $labb = $_POST['labo'];
            $un = $_POST['uni'];
            


          
            //check for duplicate entry
            $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dup = $pdo->prepare("SELECT * from curriculum where syID = :SY and periodID = :pID and levelID = :lvlID and acadProgID = :apID  and courseID = :cID and deptID = :dID");
            $dup->bindValue(':SY',$YR);
            $dup->bindValue(':pID',$PER );
            $dup->bindValue(':lvlID',$lev );
            $dup->bindValue(':apID',$prog);
            $dup->bindValue(':cID',$crs);
            $dup->bindValue(':dID',$dep);
            $dup->execute();
            $dupRes = $dup->rowCount();
        
            if($dupRes > 0){
                
                $isDuplicated = true;
                echo "<script> alert('Pumasok sa duplicate'); </script>";
            }else{
                $stmt = $pdo->prepare("INSERT INTO curriculum (syID, periodID, levelID, acadProgID, courseID, crsName, deptID, lec, lab, units, compLabID, totalUnits)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    
                $stmt->execute(array($YR, $PER, $lev, $prog, $crs, $CN, $dep, $lecc, $labb, $un, $com, $un));
                
                $isRegistered = true;
                echo "<script> alert('Pumasok sa create'); </script>";
                header("refresh:2; url = curriculum.php");

            }
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
    <link rel="stylesheet" href="curriculum.css">

    <title>Curriculum</title>
    <style>
        .data {
            padding: 0;
            margin: 0;
        }
        input[type='text'] {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            text-align: center;
        }
    </style>
  </head>
  <body>
       
        <table>
        <h1> Curriculum </h1>
        <div class="btncontainer">
            <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="curriculum.php"> Curriculum Management </a>
        </div>
        <tr>
            <th colspan="2"> Academic Programs </th>
            <td class="center"><button class="btn" onclick="openForm()"> <i class="fas fa-plus"></i> New
                    Curriculum</button> </td>
        </tr>
        <form method = "post" action = "viewcurriculum.php" class="forms">
        <tr>
            <th> Program Code </th>
            <th> Program Name</th>
            <td class="center"> View </td>
        </tr>
        <tr>
            <?php dropdownlist::showAcadProgs(); ?>
        </tr>

    </table>
    </form>
    
    <form method = "POST" action = ""> 
    <div class="addform-popup" id="myForm">
        <div class="addcontainer">

            <table id="myTable" class="addcurriculum">
                <tr>
                    <h1> Add Curriculum </h1>
                </tr>
                <tr>

                    <th> Curriculum Year </th>
                    <th> Period </th>
                    <th> Level </th>
                    <th> Program Code </th>
                    <th> Course Code </th>
                    <th> Course Name </th>
                    <th> Department Code </th>
                    <th> Lcc </th>
                    <th> Lab </th>
                    <th> Unit </th>
                    <th> Complab </th>
                </tr>
                <!--first row-->
                <tr>
                    <td>
                        <select id="year" name="yearlist">
                            <option value="" selected disabled ></option>
                            <?php dropdownlist::getYear(); ?>
                        </select>
                    </td>
                    <td>
                        <select id="period" name="periodlist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getPeriod(); ?>
                    </td>
                    <td>
                        <select id="level" name="levellist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getLevel(); ?>
                        </select>
                    </td>
                    <td>
                        <select id="progcode" name="progcodelist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getProgCode(); ?>
                        </select>
                    </td>
                    <td>
                        <select id="coursecode" name="codelist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getCourse(); ?>
                        </select>
                    </td>
                    <td> 
                        <input class="courseDetails" type="text" value="" name="courseN">
                    </td>
                    <td>
                        <select id="deptcode" name="deptcodelist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getDeptCode(); ?>
                        </select>
                    </td>
                    <td > 
                        <input class="courseDetails" type="text" value="" name="lecture"> 
                    </td>
                    <td> 
                        <input class="courseDetails" type="text" value="" name="labo">
                    </td>
                    <td>
                        <input class="courseDetails" type="text" value="" name="uni">
                    </td>
                    <td>
                        <select id="complab" name="complalist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::bridge(); ?>
                            <?php dropdownlist::getCompLab(); ?>
                        </select>
                    </td>
                </tr>
               
            </table>
            <input type = "submit" class="btnchange" name = "saveBTN" value = "Save Change"></input>
            <input type = "reset" class="btnchange" name = "cancel" value = "Cancel" onclick = "closeForm()"></input>
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


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <script src="bootstrap/js/sweetalert.min.js"></script>
    <?php if($isRegistered == true){ ?>
        <script>
            swal({
            title: "Successfully Added",
            text: "curriculum added",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($isIncomplete == true){ ?>
        <script>
            swal({
            title: "Incomplete input",
            text: "Please fill up all required fields",
            icon: "warning",
            });
    </script>
    <?php }  ?>

    <?php if($isDuplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "Field's already registered",
            icon: "warning",
            });
    </script>
    <?php }  ?>
    

  </body>
</html>