<?php
session_start();

require '../database.php';
$incomplete = false;
$added = false;
$duplicated = false;
$isDeleted = false;
$isUpdated = false;
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_POST['addRoom'])){
    //Check if Fields are empty
    if(empty($_POST['room']) || empty($_POST['bldglist'])){
        $incomplete = true;
        header("refresh:2; url = room.php");
    }else{

        $r = $_POST['room'];
        $bldg = $_POST['bldglist'];
        //CHECK IF THERE ARE ANY Duplicate entry
        $dup = $pdo->prepare("SELECT * FROM classroom  where roomNum = :roomN and  buildingCode = :code");
        $dup->bindValue(':roomN',$r);
        $dup->bindValue(':code',$bldg);
        $dup->execute();
        $dupRes = $dup->rowCount();
        if($dupRes > 0){
            $duplicated = true;
            header("refresh:2; url = room.php");
        }else{
            $st = $pdo->prepare("INSERT INTO classroom (roomNum, buildingCode) values (?,?)");
            $st->execute(array($r, $bldg));
            $added = true;
            header("refresh:2; url = room.php");

        }
    }
}

//DELETE ROOM
if(isset($_POST['delRoom'])){
    $toDelete = $_POST['inpt'];
    $del = $pdo->prepare("DELETE FROM classroom where classroomID = :rID");
    $del->bindValue(':rID',$toDelete);
    $del->execute();
    $isDeleted = true;
    header("refresh:2; url = room.php");
}

//UPDATE ROOM

if(isset($_POST['saveBTN'])){
    //check if dropdown is empty
    if(empty($_POST['bldglist'])){
        $incomplete = true;
    }
    $newRoom = $_POST['newR'];
    $newBld = $_POST['bldglist'];

    //CHECK IF USER INPUT IS DUPLICATE
    $dup = $pdo->prepare("SELECT * FROM classroom where roomNum = :num and buildingCode = :bld");
    $dup->bindValue(':num',$newRoom);
    $dup->bindValue(':bld',$newBld);
    $dup->execute();
    $dupRes = $dup->rowCount();

    if($dupRes > 0){
        $duplicated = true;
        $duplicated = true;
        header("refresh:2; url = room.php");
    }else{

        $updateQuery = $pdo->prepare("UPDATE classroom SET roomNum = :num, buildingCode = :bld WHERE classroomID = :papalitan");
        $updateQuery->bindValue(':num',$newRoom);
        $updateQuery->bindValue(':bld',$newBld);
        $updateQuery->bindValue(':papalitan',$_SESSION['oldRoom']);
        $updateQuery->execute();
        $isUpdated = true;
        header("refresh:2; url = room.php");
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
    <link rel="stylesheet" href="room.css">
</head>
<body>
    <h1> Rooms </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="room.php"> Room </a>
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
                <form method="POST">
                    <tr>
                        <th> 
                            <label for="room"> Room No. </label><br>
                            <input type="text" id="room" name = "room">
                        </th>
                        <th>
                            <label for="bldg"> Building Code</label><br>
                            <select id="bldg" name="bldglist">
                                <option value="" selected disabled ></option>
                                <option value="IT Building">IT Building</option>
                                <option value="Educ Building">Educ Building</option>
                                <option value="HS Building">HS Building</option>
                                <option value="Admin Building">Admin Building</option>
                            </select>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <button type="submit" name = "addRoom">Add Room</button>
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
            <th>Room Number</th>
            <th>Building Code</th>

            <?php 
              $show = $pdo->query("SELECT classroomID, roomNum, buildingCode from classroom");
              while ($row = $show->fetch()) {
                  echo "<tr>
                    <td><input type='radio' name = 'inpt' value =".$row['classroomID']." required></td>
                    <td>".$row['roomNum']."</td>
                    <td>".$row['buildingCode']."</td>
                  </tr>";
              }
           ?>

            <tr>
                <td colspan="3">
                    <button type="submit" name = "delRoom">Delete Room</button>
                    <button type="submit" name = "updateRoom">Update Room</button>
                </td>
            </tr>
            </form>
        

        </table>
    </div>

    <form method = "POST"> 
    <div class="addform-popup" id="myForm">
        <div class="addcontainer">

            <table id="myTable" class="addcurriculum">

                    <th>New Room Number </th>
                    <th>New Building Code</th>

                </tr>
                <tr>
                    <td><input type="text" name = "newR" required></td>
                    <td>
                    <select id="bldg" name="bldglist">
                                <option value="" selected disabled ></option>
                                <option value="IT Building">IT Building</option>
                                <option value="Educ Building">Educ Building</option>
                                <option value="HS Building">HS Building</option>
                                <option value="Admin Building">Admin Building</option>
                            </select>
                    </td>

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
            text: "Room Added",
            icon: "success",
            });
    </script>
    <?php }  ?>

    <?php if($duplicated == true){ ?>
        <script>
            swal({
            title: "Duplicate Input",
            text: "Room Is Already Added",
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
            text: "Room Updated",
            icon: "success",
            });
    </script>
    <?php }  ?>


    <?php if($isDeleted == true){ ?>
        <script>
            swal({
            title: "Successfully Deleted",
            text: "Room Deleted",
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
if(isset($_POST['updateRoom'])){ 
    echo "<script> openForm(); 
    </script>";
    $_SESSION['oldRoom'] = $_POST['inpt'];
}
?>