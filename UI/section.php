<?php
session_start();
require '../database.php';
require 'dropdown.php';
$incomplete = false;
$added = false;
$duplicated = false;
$isDeleted = false;
$isUpdated = false;
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['addSec'])){

    //CHECK IF INPUT FIELDS ARE EMPTY
    if(empty($_POST['acadcodelist']) || empty($_POST['levellist']) || empty($_POST['sec'])){
        $incomplete = true;
        header("refresh:2; url = section.php");
    }else{
        $acad = $_POST['acadcodelist'];
        $lev = $_POST['levellist'];
        $sec = $_POST['sec'];

        //CHECK IF THERE ARE ANY EXISTING SECTION
        $dup = $pdo->prepare("SELECT * FROM section  where acadProgID = :acad and  levelID = :lvl and section = :sec");
        $dup->bindValue(':acad',$acad);
        $dup->bindValue(':lvl',$lev);
        $dup->bindValue(':sec',$sec);
        $dup->execute();
        $dupRes = $dup->rowCount();
        if($dupRes > 0){
            $duplicated = true;
            header("refresh:2; url = section.php");
        }else{
            $st = $pdo->prepare("INSERT INTO section (acadProgID, levelID, section) values (?,?,?)");
            $st->execute(array($acad, $lev, $sec));
            $added = true;
            header("refresh:2; url = section.php");
        }
    }
}

//DELETE SECTION
if(isset($_POST['deleteSec'])){
    $toDelete = $_POST['inpt'];
    $del = $pdo->prepare("DELETE FROM section where secID = :secID");
    $del->bindValue(':secID',$toDelete);
    $del->execute();
    $isDeleted = true;
    header("refresh:2; url = section.php");
}


//UPDATE SECTION

if(isset($_POST['saveBTN'])){
     //CHECK IF DROPDOWN IS EMPTY
     if(empty($_POST['newAcad']) || empty($_POST['newLev'])){
         echo "<script> alert('Empty alert here!'); </script>";

     }

    $newAP = $_POST['newAcad'];
    $newLvl = $_POST['newLev'];
    $newS = $_POST['newSec'];


    //CHECK IF USER INPUT IS DUPLICATE
    $dup = $pdo->prepare("SELECT * FROM section where acadProgID = :newAP and levelID = :newLvl and section = :sec");
    $dup->bindValue(':newAP',$newAP);
    $dup->bindValue(':newLvl',$newLvl);
    $dup->bindValue(':sec',$newS);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        $duplicated = true;
        header("refresh:2; url = section.php");
    }else{
        $updateQuery = $pdo->prepare("UPDATE section SET acadProgID = :newAP, levelID = :newLvl, section = :sec WHERE secID = :papalitan");
        $updateQuery->bindValue(':newAP',$newAP);
        $updateQuery->bindValue(':newLvl',$newLvl);
        $updateQuery->bindValue(':sec',$newS);
        $updateQuery->bindValue(':papalitan',$_SESSION['oldSecID']);
        $updateQuery->execute();
        $isUpdated = true;
        header("refresh:2; url = section.php");

    }
}

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="bootstrap/js/sweetalert.min.js"></script>
    <link rel="stylesheet" href="Section.css">
</head>

<body>
    <h1> Section </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="section.php"> Section </a>
    </div>
    <div class="container">
        <div id="add" class="tabcontent">
            <table>

                <caption>
                    <div id="active" class="navbar">
                        <a class="nav active" onclick="openPage('add', this)" id="defaultOpen"><i
                                class="fas fa-plus-square"></i> Add </a>
                
                        <a class="nav " onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </caption>
                <form method = "POST">
                    <td>
                        <label for="acadcode"> Academic Program </label><br>
                        <select id="acadcode" name="acadcodelist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getProgCode(); ?>
                        </select>
                    </td>
                    <td>
                        <label for="level"> Level </label><br>
                        <select id="level" name="levellist">
                            <option value="" selected disabled > </option>
                            <?php dropdownlist::getLevel(); ?>
                        </select>
                    </td>
                    <td>
                        <label for="section"> Section </label><br>
                        <input type="text" id="sections" name = "sec" required>
                    </td>

                    <tr>
                        <td colspan="3">
                            <button type="submit" name = "addSec">Add Section</button>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>

    <div id="view" class="tabcontent">

        <table>
            <caption>
                <div id="active" class="navbar">
                    <a class="nav " onclick="openPage('add', this)" id="defaultOpen"><i
                            class="fas fa-plus-square"></i> Add </a>
                   
                    <a class="nav active" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                    </a>
                </div>
            </caption>

            <form method = "POST"> 
                <th>Select</th>
                <th>Academic Program </th>
                <th>Level</th>
                <th>Section</th>

                <?php 
                $show = $pdo->query("SELECT academicprog.progName, lvl.levelDesc, secID, section FROM academicprog, lvl, section where section.acadProgID = academicprog.acadProgID and section.levelID = lvl.levelID");
                while ($row = $show->fetch()) {
                    echo "<tr>
                        <td><input type='radio' name = 'inpt' value =".$row['secID']." required></td>
                        <td>".$row['progName']."</td>
                        <td>".$row['levelDesc']."</td>
                        <td>".$row['section']."</td>
                    </tr>";
                }
            ?>
                        <tr>
                    <td colspan="4">
                        <button type="submit" name = "deleteSec">Delete Section</button>
                        <button type="submit" name = "updateSec">Update Section</button>
                    
                    </td>
                </tr>
            </form>

        </table>
    </div>


    <form method = "POST"> 
        <div class="addform-popup" id="myForm">
            <div class="addcontainer">

                <table id="myTable" class="addcurriculum">

                        <th>New Academic Program </th>
                        <th>New Level</th>
                        <th>New Section</th>
                    </tr>
                    <tr>
                        <td>
                            <select id="acadcode" name="newAcad">
                                <option value="" selected disabled > </option>
                                <?php dropdownlist::getProgCode(); ?>
                            </select>
                        </td>
                        <td>
                            <select id="level" name="newLev">
                                <option value="" selected disabled > </option>
                                <?php dropdownlist::getLevel(); ?>
                            </select>
                        </td>
                        <td><input type="text" name = "newSec" required></td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <input type = "submit" class="btnchange" name = "saveBTN" value = "Save Change"></input>
                            <input type = "reset" class="btnchange" name = "cancel" value = "Cancel" onclick = "closeForm()"></input>
                        </td>
                    </tr>
                
                </table>
            
            </div>
        </div>
    </form>





</body>
<?php if($added == true){ ?>
        <script>
            swal({
            title: "Successfully Added",
            text: "Section Added",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($duplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "Section Is Already Added",
            icon: "warning",
            });
    </script>
    <?php }  ?>

    <?php if($incomplete == true){ ?>
        <script>
            swal({
            title: "Incomplete Input",
            text: "Please fill up all required fields",
            icon: "error",
            });
    </script>
    <?php }  ?>


    <?php if($isUpdated == true){ ?>
        <script>
            swal({
            title: "Successfully Updated",
            text: "Section Updated",
            icon: "success",
            });
    </script>
    <?php }  ?>


    <?php if($isDeleted == true){ ?>
        <script>
            swal({
            title: "Successfully Deleted",
            text: "Section Deleted",
            icon: "success",
            });
    </script>
    <?php }  ?>

    

    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }
        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>

<script>
    function openPage(pageName, elmnt,) {
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
if(isset($_POST['updateSec'])){ ?>
    <script> openForm(); </script>
    <?php
    $_SESSION['oldSecID'] = $_POST['inpt'];
}
?>