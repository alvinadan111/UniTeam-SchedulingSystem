<?php
    session_start();
    require '../database.php';
  
        if(isset($_POST['test'])){

            $_SESSION['passedProgCode'] = $_POST['test'];
        
    
            $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $ProgCodeTry = $_POST['test'];
    
            $first = $pdo->prepare("SELECT acadProgID  FROM academicprog where progCode = :progCode");
            $first->bindValue(':progCode', $ProgCodeTry);
            $first->execute();
            $acadID = $first->fetchAll(PDO::FETCH_ASSOC)[0];
            
          
            $second =  $pdo->prepare("SELECT DISTINCT syID  FROM curriculum where acadProgID = :acadID ORDER BY syID");
            $second->bindValue(':acadID', $acadID['acadProgID']);
            $second->execute();
            $syCur = $second->fetchAll(PDO::FETCH_ASSOC);

           
            
            $third =  $pdo->prepare("SELECT * FROM schoolyear");
            $third->execute();
            $yearResult = $third->fetchAll(PDO::FETCH_ASSOC);
            
            
    
         }
            

        

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="viewcurriculum.css">
    <title>Curriculum</title>
  </head>
  <body>

  <form method = "post" action = "curriculumdetails.php" class = "f">
      <table>
        <h1> View Curriculum </h1>

        <div class="btncontainer">

            <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
            <a class="navtop" href="curriculum.php"> Curriculum Management <i class="fas fa-chevron-right"></i></a>
            <a class="navtop" href="viewcurriculum.php"> View Curriculum </a>
        </div>
        <table>
            <tr>
                <td colspan="2"> Result </td>
            </tr>
            <tr>
                <th> Curriculum Year </th>
                <th> Action </th>
            </tr>
                <?php
                foreach($yearResult as $key => $val) {
                    if(isset($syCur[$key])) {
                        echo "<tr>";
                        foreach($yearResult as $key2 => $val) {
                            if($syCur[$key]['syID'] == $val['syID']) {
                                echo "<td class='icon' style='padding: 0; margin: 0;'> 
                                        <input type='text' style = 'border: none; outline: none;'  value='". $val['schoolYR']."' readonly></input>
                                    </td>";
                                echo "<td style='text-align: center;'><input type = 'submit'  class = 'sub' name = 'view' value = 'View'></input> </td>";
                               
                            }
                        }
                        echo "</tr>";
                    }
                }
                echo "<input id='content' type='text' name='con' value='' style='display: none'></input>";
                
                ?>
        </table>
        </form>
        

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>

    <script>
            let forms = document.querySelector('.f');
            forms.addEventListener('submit', atSubmitClick);
            let idContainer = document.querySelector('#content');

            let btnChange = document.querySelectorAll('.sub');
            btnChange.forEach(btn => {
                btn.addEventListener('click', atBtnClick);
            });

            function atBtnClick(e) {
                idContainer.value = this.parentElement.parentElement.children[0].children[0].value;
                
            }
            function atSubmitClick(e){
                // DON'T DELETE!!!
            }
        
        </script> 

  </body>
</html>