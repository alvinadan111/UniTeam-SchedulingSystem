<?php

 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

$var = 0;

class dropdownlist{
    public static function getYear(){
       
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT syID, schoolYR FROM schoolyear where status='active'");
        while ($row = $stmt->fetch()) {
            echo '<option value = "'.$row['syID'].'">'.$row['schoolYR'].'</option>';
        }
    }
    public static function getPeriod(){
       
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT periodID, periodDesc FROM period");
        while ($row = $stmt->fetch()) {
            echo '<option value = "'.$row['periodID'].'">'.$row['periodDesc'].'</option>';
        }
    }
    public static function getLevel(){
       
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT levelID, levelDesc FROM lvl");
        while ($row = $stmt->fetch()) {
            echo '<option value = "'.$row['levelID'].'">'.$row['levelDesc'].'</option>';
        }
    }
    public static function getProgCode(){
       
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT acadProgID, progCode FROM academicprog");
        while ($row = $stmt->fetch()) {
            echo '<option value = "'.$row['acadProgID'].'">'.$row['progCode'].'</option>';

        }
    }
    public static function getDeptCode(){

        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT deptID, deptCode FROM department");
        while ($row = $stmt->fetch()) {
            echo '<option value = "'.$row['deptID'].'">'.$row['deptCode'].'</option>';
        }
    }
    public static function getCourse(){
        
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT courseID, courseCode, courseName, lec, lab, totalUnits FROM course");
    
        while ($row = $stmt->fetch()) {
            if($GLOBALS['var']  < 2) {
                echo '<option value ="'.$row['courseID'].'" id = "drop">'.$row['courseCode'].'</option>';
               
            }   
        }

      

    }

    public static function bridge() {
        
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT courseID, courseCode, courseName, lec, lab, totalUnits FROM course");
    
        $arr = array();
        
        while ($row = $stmt->fetch()) {
            
                $arr = array_merge($arr, array($row['courseName'], $row['lec'], $row['lab'], $row['totalUnits']));   
        }

            ?>
            <script>
                let codelist = document.querySelector('#coursecode');
                codelist.addEventListener('change', show);
    
                let y = document.querySelectorAll('.courseDetails');
                y.forEach((yy, i) => {
                    if(i == 0) {
                        yy.style.width = "110px";
                    }else{
                        yy.style.width = "30px";
                    }
                    yy.setAttribute('readonly', 'true');
                });

                function show(e) {
                    let count = parseInt(codelist.value) - 1;
                  
                  
                    let arr = "<?php echo implode(", ", $arr) ?>".split(", ");

                    for(let i = count * 4, j = 0; i < ((count + 1) * 4); i++, j++) {
                        y[j].value = arr[i];
                    }
                }
            </script>
            <?php
    }

    public static function getCompLab(){
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT compLabID, isCompLab FROM complab");
        while ($row = $stmt->fetch()) {
            echo '<option value = "'.$row['compLabID'].'">'.$row['isCompLab'].'</option>';
        }
    }
    public static function showAcadProgs(){
        $pdo=Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->query("SELECT DISTINCT academicprog.progCode, academicprog.progName FROM curriculum, academicprog
        where academicprog.acadProgID = curriculum.acadProgID");
        $stmt->execute();
        
        $arrayProgCode = array();
       
        for ($i = 0; $row = $stmt->fetch(); $i++) { 
            echo "<tr><td class='data'><input type='text'  value='". $row['progCode']."' readonly></input></td><td>".$row['progName']." </td>";
            echo "<td style='text-align: center;'><input type = 'submit' class='btnchange eye' name = 'saveBTN' value = 'View' id='$i'></input> </td></tr>";
            
            $arrayProgCode = array_merge($arrayProgCode, array($row['progCode']));
        } 
        echo "<input id='idContainer' type='text' name='test' value='' style='display: none'></input>";
        ?>

        <script>
            let forms = document.querySelector('.forms');
            forms.addEventListener('submit', atSubmitClick);
            let idContainer = document.querySelector('#idContainer');

            let btnChange = document.querySelectorAll('.btnchange');
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
     <?php   

     
    }

}
?>

