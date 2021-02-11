<?php
session_start();

 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

error_reporting(E_ERROR | E_PARSE); 

require '../database.php';
require 'dropdown.php';
$duplicated = false;
$added = false;
$isDeleted = false;
$isUpdated = false;
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['add'])){
    $getPC = $_POST['PC'];
    $getPN = $_POST['PN'];

    //CHECK IF USER INPUT IS DUPLICATED
    
    $dup = $pdo->prepare("SELECT * FROM academicprog where progCode = :progC or progName = :progN");
    $dup->bindValue(':progC',$getPC);
    $dup->bindValue(':progN',$getPN);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        header("refresh:2; url = academic.php");
    }else{
        $st = $pdo->prepare("INSERT INTO academicprog (progCode, progName) values (?,?)");
        $st->execute(array($getPC, $getPN));
        $added = true;
        header("refresh:2; url = academic.php");
    }

}

//DELETE Academeic program 
if(isset($_POST['delProg'])){

    $radioSelect = $_POST['inpt'];
    $del = $pdo->prepare("DELETE FROM academicprog where progCode = :PC");
    $del->bindValue(':PC',$radioSelect);
    $del->execute();
    $isDeleted = true;
}

//UPDATE Academic Program


if(isset($_POST['saveBTN'])){
    $newPC = $_POST['newPC'];
    $newPN = $_POST['newPN'];

    //CHECK IF USER INPUT IS DUPLICATE
    $dup = $pdo->prepare("SELECT * FROM academicprog where progCode = :progC or progName = :progN");
    $dup->bindValue(':progC',$newPC);
    $dup->bindValue(':progN',$newPN);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        /*echo "<script> alert('obob Duplicate Entry'); </script>";*/
        header("refresh:2; url = academic.php");
    }else{

        $updateQuery = $pdo->prepare("UPDATE academicprog SET progCode = :nPC, progName = :nPN WHERE progCode = :papalitan");
        $updateQuery->bindValue(':nPC',$newPC);
        $updateQuery->bindValue(':nPN',$newPN);
        $updateQuery->bindValue(':papalitan',$_SESSION['val']);
        $updateQuery->execute();
        $isUpdated = true;
        header("refresh:2; url = academic.php");

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
    <link rel="stylesheet" href="academic.css">
</head>

<body>


    <h1> Academic Program </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="academic.php"> Academic Program </a>
    </div>
    <div class="container">
        <div id="add" class="tabcontent">

            <table>
                <caption >
                    <div id="active" class="navbar">
                        <a class="nav active" onclick="openPage('add', this)" id="defaultOpen"><i
                                class="fas fa-plus-square"></i> Add</a>
                        <a class="nav" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </caption>
                <tr>
                    <form method = "POST">
                        <td>
                            <label for="progcode"> Program Code </label><br>
                            <input type="text" id="programcode" name = "PC" required>
                        </td>
                        <td>
                            <label for="progname"> Program Name </label><br>
                            <input type="text" id="programname" name = "PN" required>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" name = "add">Add Program</button>
                        </td>
                    </tr>
                </form>
            </table>
        </div>
    </div>
   
    </div>
    
    <div id="view" class="tabcontent">

        <table>
            <caption >
                <div id="active" class="navbar">
                    <a class="nav" onclick="openPage('add', this)" id="defaultOpen"><i
                            class="fas fa-plus-square"></i> Add </a>
                    <a class="nav active" onclick="openPage('view', this)"><i class="fas fa-eye"></i> View
                    </a>
                </div>
            </caption>
            <form method = "POST">
                <th> Select </th>
                <th> Program Code </th>
                <th> Program Name </th>
           <?php 
              $show = $pdo->query("SELECT progCode, progName FROM academicprog");
              while ($row = $show->fetch()) {
                  echo "<tr>
                    <td><input type='radio' name = 'inpt' value =".$row['progCode']." required></td>
                    <td>".$row['progCode']."</td>
                    <td>".$row['progName']."</td>
                  </tr>";
              }   
           ?>
           <tr>
                <td colspan="2">
                    <button type="submit" name = "delProg">Delete Academic Program</button>
                    <button type="submit" name = "updateProg" id = "up">Update Academic Program </button>
                  
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

                    <th>New Program Code</th>
                    <th>New Program Name</th>
                </tr>
                <tr>
                    <td><input type="text" name = "newPC" required></td>
                    <td><input type="text" name = "newPN" required></td>
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
   
    <script src="bootstrap/js/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <?php if($added == true){ ?>
        <script>
            swal({
            title: "Successfully Added",
            text: "Academic Program Added",
            icon: "success",
            });
    </script>
    <?php }  ?>
    
    <?php if($duplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "Academic Program Is Already Added",
            icon: "warning",
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


<?php if($isDeleted == true){ ?>
        <script>
            swal({
            title: "Successfully Deleted",
            text: "Academic Program Deleted",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($isUpdated == true){ ?>
        <script>
            swal({
            title: "Successfully Updated",
            text: "Academic Program Updated",
            icon: "success",
            });
    </script>
    <?php }  ?>

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
if(isset($_POST['updateProg'])){
    echo "<script> openForm(); 
    </script>";
    
    $_SESSION['val'] = $_POST['inpt']; 
}
?>