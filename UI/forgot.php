<?php
session_start();

require '../database.php';
$pdo=Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$getResult = array();

if(isset($_POST['Search'])){
  
    $id = $_POST['userID'];

    $getID = $pdo->prepare("SELECT secretQuestion, answer FROM account where idNum = :id");
    $getID->bindValue(':id',$id);
    $getID->execute();
    $idRes = $getID->rowCount();
    $getResult = $getID->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION['res'] = array($getResult);
  

    
    if($idRes == 0){
        echo "<script> alert('Username not found'); </script>";
    }else{
        $_SESSION['id'] = $id;
    }
}


?>


<!DOCTYPE html>
<html>

<head>
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="Forgot.css">
</head>

<body>
    <div class="btncontainer">
        <a class="navtop" href="../logout.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="forgot.html"> Forgot Password </a>
    </div>
    <div class="container">
        <form method="post">
            <table>
            <h1> Forgot Password </h1>
            <hr>
            <table>
                <tr>
                    <td>
                        <input type="text" placeholder="Enter Username:" name = "userID" required>
                        <button type="submit" name = "Search">Search</button>
                    </td>
                </tr>
            </table>
        </form>
   


    <div>
        <div class="addcontainer">
            <table id="myTable" class="addcurriculum">
                <tr>
                    <td>
                        <label for="question" title = "Answer the question so that we can verify your identity">Question:</label>
                    </td>
                </tr>
                

                        <?php foreach($getResult as $id => $data) : ?>
                            <tr>
                                <td>
                                    <input type="text" value="<?php echo $data['secretQuestion'] ?> " readonly>
                                </td>
                            </tr>
                    <?php endforeach; ?>

                <form method = "POST">
                    <tr>
                        <td> <input type="text" placeholder="Answer:" name = "ans" required> </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type = "submit" class="btnchange" name = "submit" value = "submit"></input>
                        </td>
                    </tr>
                </form>


                <?php
                    if(isset($_POST['submit'])){
                        $userAnswer = $_POST['ans'];
                        $try = $pdo->prepare("SELECT pw FROM account where idNum = :id and answer = :ans");
                        $try->bindValue(':id',$_SESSION['id']);
                        $try->bindValue(':ans',$userAnswer);
                        $try->execute();
                        $rowC = $try->rowCount();
                        $getAns = $try->fetchAll(PDO::FETCH_ASSOC);

                        if($rowC == 0){
                            echo "<script> alert('Error'); </script>";
                        }else{
                            foreach($getAns as $id => $data){ ?>
                                <tr>
                                    <td>
                                        <input type="text" value="Your Password is: <?php echo $data['pw'] ?> " readonly>
                                    </td>
                                    <td>
                                        <?php echo '<a href="../index.php" style="color:dodgerblue">Login Now! </a>';  ?>
                                    </td>
                                </tr>
                               
                    <?php        }
                           
                            

                        }
                    }
                ?>



            </table>
        </div>  
</div>
</div>
</body>
</html>
