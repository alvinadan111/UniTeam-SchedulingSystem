<?php
session_start();
require '../database.php';
require  'dropdown.php';
$added = false;
$duplicated = false;
$isDeleted = false;
$isUpdated = false;
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['addDept'])){

    $getDC = $_POST['DC'];
    $getDN = $_POST['DN'];
    
    //CHECK THERE ARE ALREADY EXISTING DEPARTMENT
    
    $dup = $pdo->prepare("SELECT * FROM department where deptCode = :deptC or deptName = :deptN");
    $dup->bindValue(':deptC',$getDC);
    $dup->bindValue(':deptN',$getDN);
    $dup->execute();
    $dupRes = $dup->rowCount();
    if($dupRes > 0){
        $duplicated = true;
        
        header("refresh:2; url = department.php");
    }else{
        $st = $pdo->prepare("INSERT INTO department (deptCode, deptName) values (?,?)");
        $st->execute(array($getDC, $getDN));
        $added = true;
    
        header("refresh:2; url = department.php");
    } 
}

//DELETE DEPARTMENT
if(isset($_POST['delDept'])){
   
    $toDelete = $_POST['inpt'];
    $del = $pdo->prepare("DELETE FROM department where deptCode = :DC");
    $del->bindValue(':DC',$toDelete);
    $del->execute();
    $isDeleted = true;
    header("refresh:2; url = department.php");
}

//UPDATE DEPARTMENT

if(isset($_POST['saveBTN'])){
    $newDC = $_POST['newDC'];
    $newDN = $_POST['newDN'];

    //CHECK IF USER INPUT IS DUPLICATE
    $dup = $pdo->prepare("SELECT * FROM department where deptCode = :newDC or deptName = :newDN");
    $dup->bindValue(':newDC',$newDC);
    $dup->bindValue(':newDN',$newDN);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        // echo "<script> alert('obob Duplicate Entry'); </script>";
        header("refresh:2; url = department.php");
    }else{
        $updateQuery = $pdo->prepare("UPDATE department SET deptCode = :nDC, deptName = :nDN WHERE deptCode = :papalitan");
        $updateQuery->bindValue(':nDC',$newDC);
        $updateQuery->bindValue(':nDN',$newDN);
        $updateQuery->bindValue(':papalitan',$_SESSION['oldDC']);
        $updateQuery->execute();
        $isUpdated = true;
        header("refresh:2; url = department.php");
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
    <link rel="stylesheet" href="department.css">
</head>

<body>
    <h1> Department </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="department.php"> Department </a>
    </div>
    <div class="container">
        <div id="add" class="tabcontent">
            <table>
                <caption>
                    <div id="active" class="navbar">
                        <a class="nav active" onclick="openPage('add', this)" id="defaultOpen"><i
                                class="fas fa-plus-square"></i> Add</a>
                        <a class="nav" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </caption>
                <form method = "POST">
                    <td>
                        <label for="deptcode"> Department Code </label><br>
                        <input type="text" id="deptcode" name = "DC" required>
                    </td>
                    <td>
                        <label for="deptname"> Department Name </label><br>
                        <input type="text" id="deptname" name = "DN" required>
                    </td>
                    <tr>
                        <td colspan="2">
                            <button type="submit" name = "addDept">Add Department</button>
                        </td>
                </form>
                    </tr>
            </table>
        </div>
    </div>
    
    <div id="view" class="tabcontent">
        <table>
            <caption>
                <div id="active" class="navbar">
                    <a class="nav " onclick="openPage('add', this)" id="defaultOpen"><i class="fas fa-plus-square"></i>
                        Add</a>
                  
                    <a class="nav active" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                    </a>
                </div>
            </caption>
            <form method = "POST">
            <th> Select </th>
            <th> Department Code </th>
            <th> Department Name </th>
            <?php 
              $show = $pdo->query("SELECT deptCode, deptName FROM department");
              while ($row = $show->fetch()) {
                  echo "<tr>
                    <td><input type='radio' name = 'inpt' value =".$row['deptCode']." required></td>
                    <td>".$row['deptCode']."</td>
                    <td>".$row['deptName']."</td>
                  </tr>";
              }
           ?>
           <tr>
                    <td colspan="3">
                        <button type="submit" name = "delDept">Delete Department</button>
                        <button type="submit" name = "updateDept">Update Department</button>
            
                    </td>
                </tr>
        </form>
           
        </table>
    </div>

    <form method = "POST"> 
    <div class="addform-popup" id="myForm">
        <div class="addcontainer">

            <table id="myTable" class="addcurriculum">
                <tr>

                    <th>New Department Code</th>
                    <th>New Department Name</th>
                </tr>
                <tr>
                    <td><input type="text" name = "newDC" required></td>
                    <td><input type="text" name = "newDN" required></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type = "submit" class="btnchange" name = "saveBTN" value = "Save Change"></input>
                        <input type = "reset" class="btnchange" name = "cancel" value = "Cancel" onclick = "closeForm()"></input>
                    </td>
                </tr>
               
            </table>
         
        </div>
    </div>


    <?php if($added == true){ ?>
        <script>
            swal({
            title: "Successfully Added",
            text: "Department Added",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($duplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "Department Is Already Registered",
            icon: "warning",
            });
    </script>
    <?php }  ?>


    <?php if($isUpdated == true){ ?>
        <script>
            swal({
            title: "Successfully Updated",
            text: "Department Updated",
            icon: "success",
            });
    </script>
    <?php }  ?>


    <?php if($isDeleted == true){ ?>
        <script>
            swal({
            title: "Successfully Deleted",
            text: "Department Deleted",
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


</body>
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
if(isset($_POST['updateDept'])){ ?>
    <script> openForm(); </script> <?php
    $_SESSION['oldDC'] = $_POST['inpt'];

}
?>