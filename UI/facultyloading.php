<?php session_start();
 if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

//error_reporting(E_ERROR | E_PARSE);
require '../database.php';
$pdo=Database::connect();
?>


<!DOCTYPE html>

<html>

<head>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="facultyloading.css">
</head>

<body>
    <h1> Faculty Loading </h1> 
    <div class="btncontainer">
        <a class="navtop" href="menu.html"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="facultyloading.html"> Faculty Loading </a>
    </div>
  <form method="get">
    <table class="top">
        <tr>
            <th colspan="4" class="border"> Search Instructor</th>
        </tr>
        <tr>
            <td class="border">
                <label for="dept"> Department </label>
                <select id="dept" name="deptlist" required>
                   <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT * FROM department");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['deptID']; ?>"> <?php echo $row['deptCode'];  ?> </option>
                                    
                            <?php }?>     
                    </select>
            </td>
            <td class="border">
                <label for="level"> Level </label>
                <select id="level" name="levellist"  required>
                     <option value=" " selected disabled></option>
                            <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT levelID, levelDesc FROM lvl");
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['levelID']; ?>"> <?php echo $row['levelDesc'];  ?> </option>
                                    
                            <?php }?>   
                    </select>
            </td>
            <td class="border">
                <label for="instructor"> Instructor </label>
                <select id="instructor" name="instructorlist" required >
                <option value=" " selected disabled></option>
                    <?php
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $stmt = $pdo->query("SELECT * FROM account where accessLevel='prof'");
                                //$q->execute(array($accessLevel ));
                                while ($row = $stmt->fetch()) { ?>

                                <option value="<?php echo $row['accountID']; ?>"> <?php echo $row['FName']." - ".$row['LName'];  ?> </option>
                                    
                            <?php }?>   
                    </select>
            </td>
            <td class="border">
                <button class="btn" name="searchBtn"> Search </button></i> </button>
            </td>
        </tr>
    </table>
  </form>
  <?php if(isset($_GET['searchBtn'])) {
    if(empty($_GET['deptlist']) || empty($_GET['levellist']) || empty($_GET['instructorlist'])){ ?>
 
            </div>
            <!-- Warning Alert -->
            <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
            <strong>Warning!</strong> &nbsp Please make sure all fields are filled.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>

<?php  }else{ 
            // $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $deptlist = $_GET['deptlist'];
            $levellist = $_GET['levellist'];
            $instructorlist = $_GET['instructorlist'];

                $stmt=$pdo->prepare("select * from curriculum c natural join coursescheduling natural join course natural join timestart natural join timeend
                    where (c.deptID = ?) and (c.levelID = ?) 
                    order by curID ");
                $stmt->execute(array($deptlist, $levellist));
                $result= $stmt->rowCount();
                 if($result==0){ ?>
                    <!-- Warning Alert -->
                    <div id="myAlert" class="alert alert-warning alert-dismissible fade show">
                    <strong>Warning!</strong> &nbsp No result found.
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
        <?php   } //end of if result  ?>



                <div class="row">
                      <div class="column">
                        <table>
                            <tr class="border">
                                <th class="border" colspan="2"> Courses to Load </th>
                            </tr>
                            <!-- <td colspan="3" class="border">
                                <label for="searchcode">Enter The Course Code To Search:</label>
                                <input type="search" id="searchcode" name="searchcode">
                            </td> -->
                         <thead>
                            <tr>
                                <th class="head">Course</th>
                                <th> Schedule </th>
                                <th> </th>
                            </tr>
                            <tr>
                                <td> </td>
                                <td> </td>
                                <td class="btncenter"> <button> Add to Calendar </button> </td>
                            </tr>
                        </thead>
        <?php    while ($row = $stmt->fetch()) 
              {
                 $crsName=$row['crsName'];
                 $timeStart=$row['timeStart'];
                 $timeEnd=$row['timeEnd'];

                     if ($result>0) {
                        echo "laman mga row[]: crsName:".$crsName." timeStart ".
                        $timeStart." timeEnd: ".$timeEnd;
                     } //end of if result>0 ?> 
                            <tbody>
                                <tr>
                                    <td><?php echo $crsName;?></td>
                                <td><?php echo $timeStart." - ".$timeEnd;?></td>
                                <td></td>
                                </tr>
                            </tbody>
                        </table>
                      </div>


<?php          } //end of while
        } /*<!-- end of else -->*/ 
}  ?> <!-- end of if isset -->

        <div class="column">
            <div id="Cal" class="tabcontent">
                <table class="table2">
                    <tr>
                        <td colspan="7" class="border">
                            <button class="tablink" onclick="openPage('Tab', this,)">Tabular View</button>
                            <button class="tablink01" onclick="openPage('Cal', this,)" id="defaultOpen">Calendar
                                View</button>
                            <h3> Faculty Loading </h3>

                            Total No. of Units Loaded:
                        </td>
                    </tr>
                    <tr>
                        <th> Time </th>
                        <th> Monday </th>
                        <th> Tuesday </th>
                        <th> Wednesday </th>
                        <th> Thursday </th>
                        <th> Friday </th>
                        <th> Saturday </th>
                    </tr>
                    <tr>
                        <td> 7am </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 8am </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 9am </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 10am </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 11am </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 12nn </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 1pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 2pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 3pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                      
                    </tr>
                    <tr>
                        <td> 4pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 5pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 6pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 7pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td> 8pm </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                </table>
            </div>
            <div id="Tab" class="tabcontent">
                <table>
                    <tr>
                        <td colspan="6" class="border">
                            <button class="tablink01" onclick="openPage('Tab', this,)" id="defaultOpen">Tabular
                                View</button>
                            <button class="tablink" onclick="openPage('Cal', this,)">Calendar View</button>
                            <h3> Faculty Loading </h3>

                            Total No. of Units Loaded:
                        </td>
                    </tr>
                    <tr>
                        <th> Action </th>
                        <th> Code </th>
                        <th> Description </th>
                        <th> Units </th>
                        <th> Section </th>
                        <th> Schedule </th>
                    </tr>
                    <tr>
                        <td class="btncenter"> <button class="btnaction" onclick="openForm()"><i
                                    class="fas fa-edit"></i></button>
                        </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>
                    <tr>
                        <td class="btncenter" colspan="6">
                            <button class="btn"> Generate Schedule </button></i> </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

<?php Database::disconnect(); ?>

    <div class="unloadform-popup" id="myForm">
        <div class="unloadcontainer">
            <p style="color: white;"> By clicking OK button will unload the subject from the instructor. Do you wish to
                continue? </p>
            <button class="btnunload"> Ok </button>
            <button class="btnunload" onclick="closeForm()"> Cancel </button>
        </div>
    </div>
    <script>
        function openPage(pageName, elmnt,) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
            }
            document.getElementById(pageName).style.display = "block";
            elmnt.style.backgroundColor = color;
        }
        document.getElementById("defaultOpen").click();
    </script>
    <script>
        function openForm() {
            document.getElementById("myForm").style.display = "block";
        }
        function closeForm() {
            document.getElementById("myForm").style.display = "none";
        }
    </script>

</body>

</html>