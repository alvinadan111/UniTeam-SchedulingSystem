<?php session_start();
require 'database.php';


    if(!empty($_POST))
    {
       $idNum=$_POST['idNum'];
        $pw=$_POST['pw'];

        //get data of user
         $pdo=Database::connect();

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM account WHERE idNum = ?  AND pw = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($idNum, $pw ));
        $account = $q->fetch(PDO::FETCH_ASSOC);
 
        if($account['idNum']!="")
        {
          
          @$_SESSION['FName']=$account['FName'];
          @$_SESSION['pw']=$account['pw'];
          @$_SESSION['idNum']=$account['idNum'];
          @$_SESSION['accessLevel']=$account['accessLevel'];
          @$_SESSION['signupDeptID']=$account['dept'];

          if ($_SESSION['accessLevel']=="admin") {
            @$_SESSION['accountID']=$account['accountID']; 
            header("Location: UI/menu.php");
          } else if ($_SESSION['accessLevel']=="student") {
            @$_SESSION['accountIDstudent']=$account['accountID']; 
            header("Location: UI/studentmenu.php");
          }else if ($_SESSION['accessLevel']=="prof") {
            @$_SESSION['accountIDprof']=$account['accountID']; 
            header("Location: UI/profmenu.php");
          }else if ($_SESSION['accessLevel']=="reg") {
            @$_SESSION['accountIDreg']=$account['accountID']; 
            header("Location: UI/regMenu.php");
          }
           
        } else
        {
            header("Location: index.php?signin=mismatch");  
        } 
         Database::disconnect();   
    }        
?>