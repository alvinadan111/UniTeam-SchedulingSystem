<?php
session_start();

 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

require '../database.php';
$duplicated = false;
$added = false;
$isDeleted = false;
$isUpdated = false;
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['addSY'])){
   
    $yr = $_POST['SY'];
    //CHECK IF INPUT IS DUPLICATED
    $dup = $pdo->prepare("SELECT * FROM schoolyear where schoolYR = :yr");
    $dup->bindValue(':yr',$yr);
    $dup->execute();
    $dupRes = $dup->rowCount();
    if($dupRes > 0){
        $duplicated = true;
        header("refresh:2; url = schoolyr.php");
    }else{
        $st = $pdo->prepare("INSERT INTO schoolyear (schoolYR) VALUES (?)");
        $st->execute(array($yr));
        $added = true;
        header("refresh:2; url = schoolyr.php");

    }
}
//DELETE SCHOOL YEAR
if(isset($_POST['deleteSY'])){
    $toDelete = $_POST['inpt'];
    $del = $pdo->prepare("DELETE FROM schoolyear where syID = :SYID");
    $del->bindValue(':SYID',$toDelete);
    $del->execute();
    $isDeleted = true;
    header("refresh:2; url = schoolyr.php");
}

//UPDATE SCHOOL YEAR

if(isset($_POST['saveBTN'])){
    $newSY = $_POST['newSY'];

    //CHECK IF USER INPUT IS DUPLICATE
    $dup = $pdo->prepare("SELECT * FROM schoolyear where schoolYR = :sy");
    $dup->bindValue(':sy',$newSY);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        $duplicated = true;
        header("refresh:2; url = schoolyr.php");
    }else{
        $updateQuery = $pdo->prepare("UPDATE schoolyear SET schoolYR = :sy WHERE syID = :papalitan");
        $updateQuery->bindValue(':sy',$newSY);
        $updateQuery->bindValue(':papalitan',$_SESSION['oldSy']);
        $updateQuery->execute();
        $isUpdated = true;
        header("refresh:2; url = schoolyr.php");
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
    <link rel="stylesheet" href="schoolyr.css">
</head>

<body>
    <h1> School Year </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="schoolyr.php"> School Year </a>
    </div>
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
            <tr>
                <th>
                    <label for="schlyr"> School Year </label><br>
                    <input type="text" id="schlyr" name = "SY" required>
                </th>

                <th>
                    <label for="schlyr"> Set Active School Year </label><br>
                    <input type="text" id="schlyrActive" name = "schlyrActive" required>
                </th>
            </tr>
            <tr>
                <td>
                    <button type="submit" name = "addSY">Add School Year</button>
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
                            class="fas fa-plus-square"></i> Add </a>
                    <a class="nav active" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                    </a>
                </div>
            </caption>
            <form method = "POST">
            <th> Select</th>  
            <th> School Year </th>  
            <?php 
              $show = $pdo->query("SELECT syID, schoolYR FROM schoolyear");
              while ($row = $show->fetch()) {
                  echo "<tr>
                    <td><input type='radio' name = 'inpt' value =".$row['syID']." required></td>
                    <td>".$row['schoolYR']."</td>
                  </tr>";
              }
           ?>
            <tr>
                <td colspan="2">
                    <button type="submit" name = "deleteSY">Delete School Year</button>
                    <button type="submit" name = "updateSY">Update School Year</button>
                </td>
            </tr>
            </form>
          
        </table>
    </div>

    <form method = "POST"> 
    <div class="addform-popup" id="myForm">
        <div class="addcontainer">

            <table id="myTable" class="addcurriculum">

                    <th>New School Year</th>

                </tr>
                <tr>
                    <td><input type="text" name = "newSY" required></td>
                </tr>
                <tr>
                    <td>
                        <input type = "submit" class="btnchange" name = "saveBTN" value = "Save Change"></input>
                        <input type = "reset" class="btnchange" name = "cancel" value = "Cancel" onclick = "closeForm()"></input>
                    </td>
                </tr>
               
            </table>
         
        </div>
    </div>
        
</body>

<?php if($added == true){ ?>
        <script>
            swal({
            title: "Successfully Added",
            text: "School Year Added",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($duplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "School Year Is Already Added",
            icon: "warning",
            });
    </script>
    <?php }  ?>

    <?php if($isDeleted == true){ ?>
        <script>
            swal({
            title: "Successfully Deleted",
            text: "School Year Deleted",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($isUpdated == true){ ?>
        <script>
            swal({
            title: "Successfully Updated",
            text: "School Year Updated",
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
if(isset($_POST['updateSY'])){ ?>
    <script> openForm(); </script> <?php
    $_SESSION['oldSy'] = $_POST['inpt'];
}
?>