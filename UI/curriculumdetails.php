<?php
    session_start();
     if(empty($_SESSION['accountID'])):
header('Location:../index.php');
endif;

    require '../database.php';
    $tlec1 = 0; $tunit1 = 0; $tlab1 = 0;

    $tlec2 = 0; $tunit2 = 0; $tlab2 = 0;

    $tlec3 = 0; $tunit3 = 0; $tlab3 = 0;

    $tlec4 = 0; $tunit4 = 0; $tlab4 = 0;

    $tlec5 = 0; $tunit5 = 0; $tlab5 = 0;

    $tlec6 = 0; $tunit6 = 0; $tlab6 = 0;

    $tlec7 = 0; $tunit7 = 0; $tlab7 = 0;

    $tlec8 = 0; $tunit8 = 0; $tlab8 = 0;
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST['con'])){
            $pdo=Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $passedYear = $_POST['con'];
            $_SESSION['passedProgCode'];

            //get id of passedYear 
            $getYear =  $pdo->prepare("SELECT syID from schoolyear where schoolYR = ?");
            $getYear->execute(array($passedYear));
            $yearResult = $getYear->fetchAll(PDO::FETCH_ASSOC);

            //get id of progcode
            $getProg =  $pdo->prepare("SELECT acadProgID from academicprog where progCode = ?");
            $getProg ->execute(array($_SESSION['passedProgCode']));
            $progResult = $getProg->fetchAll(PDO::FETCH_ASSOC);
            
            
            //QUERY FOR 1ST YEAR 1ST SEM
            $query1 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 1 and curriculum.levelID = 1");
            $query1->bindValue(':acad', $progResult[0]['acadProgID']);
            $query1->bindValue(':sYear',$yearResult[0]['syID']);
            $query1->execute();
            $result1 = $query1->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 1ST YEAR 2ND SEM
            $query2 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 2 and curriculum.levelID = 1");
            $query2->bindValue(':acad', $progResult[0]['acadProgID']);
            $query2->bindValue(':sYear',$yearResult[0]['syID']);
            $query2->execute();
            $result2 = $query2->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 2ND YEAR 1ST SEM
            $query3 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 1 and curriculum.levelID = 2");
            $query3->bindValue(':acad', $progResult[0]['acadProgID']);
            $query3->bindValue(':sYear',$yearResult[0]['syID']);
            $query3->execute();
            $result3 = $query3->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 2ND YEAR 2ND SEM
            $query4 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 2 and curriculum.levelID = 2");
            $query4->bindValue(':acad', $progResult[0]['acadProgID']);
            $query4->bindValue(':sYear',$yearResult[0]['syID']);
            $query4->execute();
            $result4 = $query4->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 3RD YEAR 1ST SEM
            $query5 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 1 and curriculum.levelID = 3");
            $query5->bindValue(':acad', $progResult[0]['acadProgID']);
            $query5->bindValue(':sYear',$yearResult[0]['syID']);
            $query5->execute();
            $result5 = $query5->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 3RD YEAR 2ND SEM
            $query6 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 2 and curriculum.levelID = 3");
            $query6->bindValue(':acad', $progResult[0]['acadProgID']);
            $query6->bindValue(':sYear',$yearResult[0]['syID']);
            $query6->execute();
            $result6 = $query6->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 4TH YEAR 1ST SEM
            $query7 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 1 and curriculum.levelID = 4");
            $query7->bindValue(':acad', $progResult[0]['acadProgID']);
            $query7->bindValue(':sYear',$yearResult[0]['syID']);
            $query7->execute();
            $result7 = $query7->fetchAll(PDO::FETCH_ASSOC);

            //QUERY FOR 4TH YEAR 2ND SEM
            $query8 = $pdo->prepare("SELECT course.courseCode, course.courseName, course.lec, course.lab, course.totalUnits, complab.isCompLab FROM (course INNER JOIN curriculum on curriculum.courseID = course.courseID) INNER JOIN complab on curriculum.compLabID = complab.compLabID where curriculum.acadProgID = :acad and curriculum.syID = :sYear and curriculum.periodID = 2 and curriculum.levelID = 4");
            $query8->bindValue(':acad', $progResult[0]['acadProgID']);
            $query8->bindValue(':sYear',$yearResult[0]['syID']);
            $query8->execute();
            $result8 = $query8->fetchAll(PDO::FETCH_ASSOC);
           

          
        }

    }


?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="Curriculumdetails.css">
    <title>Curriculum</title>
  </head>
  <body>

  <h1> Curriculum Details </h1>
    <div class="btncontainer">
        <a class="navtop" href="menu.php"> Home <i class="fas fa-chevron-right"></i> </a>
        <a class="navtop" href="curriculum.php"> Curriculum Management <i class="fas fa-chevron-right"></i></a>
        <a class="navtop" href="viewcurriculum.php"> View Curriculum <i class="fas fa-chevron-right"></i></a>
        <a class="navtop" href="curriculumdetails.php"> Curriculum Details </a>
    </div>
    <!-- TABLE FOR 1ST YEAR 1ST SEM -->
    <table>
            <tr>
                <td colspan="1"> Result </td>
                <td colspan="2"> <?php echo  $passedYear?> </td>
                <td colspan="3"> <?php echo  $_SESSION['passedProgCode']?> </td>
            </tr>
            <tr>
                <td colspan="2"> 1st Year </td>
                <td colspan="4"> 1st Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result1 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec1 += floatval($data['lec']); $tlab1 += floatval($data['lab']); $tunit1 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec1).'.0' ?> </td>
                <td> <?php echo floatval($tlab1).'.0' ?> </td>
                <td> <?php echo floatval($tunit1).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>

         <!-- TABLE FOR 1ST YEAR 2ND SEM -->
    <table>
            <tr>
                <td colspan="2"> 1st Year </td>
                <td colspan="4"> 2nd Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result2 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec2 += floatval($data['lec']); $tlab2 += floatval($data['lab']); $tunit2 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec2).'.0' ?> </td>
                <td> <?php echo floatval($tlab2).'.0' ?> </td>
                <td> <?php echo floatval($tunit2).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>

        <!-- TABLE FOR 2ND YEAR 1ST SEM -->
        <br>
    <table>
            <tr>
                <td colspan="2"> 2nd Year </td>
                <td colspan="4"> 1st Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result3 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec3 += floatval($data['lec']); $tlab3 += floatval($data['lab']); $tunit3 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec3).'.0' ?> </td>
                <td> <?php echo floatval($tlab3).'.0' ?> </td>
                <td> <?php echo floatval($tunit3).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>

         <!-- TABLE FOR 2ND YEAR 2ND SEM -->
    <table>
            <tr>
                <td colspan="2"> 2nd Year </td>
                <td colspan="4"> 2ND Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result4 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec4 += floatval($data['lec']); $tlab4 += floatval($data['lab']); $tunit4 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec4).'.0' ?> </td>
                <td> <?php echo floatval($tlab4).'.0' ?> </td>
                <td> <?php echo floatval($tunit4).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>
        <br>
         <!-- TABLE FOR 3RD YEAR 1ST SEM -->
    <table>
            <tr>
                <td colspan="2"> 3rd Year </td>
                <td colspan="4"> 1st Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result5 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec5 += floatval($data['lec']); $tlab5 += floatval($data['lab']); $tunit5 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec5).'.0' ?> </td>
                <td> <?php echo floatval($tlab5).'.0' ?> </td>
                <td> <?php echo floatval($tunit5).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>

        <!-- TABLE FOR 3RD YEAR 2ND SEM -->
    <table>
            <tr>
                <td colspan="2"> 3rd Year </td>
                <td colspan="4"> 2nd Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result6 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec6 += floatval($data['lec']); $tlab6 += floatval($data['lab']); $tunit6 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec6).'.0' ?> </td>
                <td> <?php echo floatval($tlab6).'.0' ?> </td>
                <td> <?php echo floatval($tunit6).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>
        <br>
         <!-- TABLE FOR 4TH YEAR 1ST SEM -->
    <table>
            <tr>
                <td colspan="2"> 4th Year </td>
                <td colspan="4"> 1st Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result7 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec7 += floatval($data['lec']); $tlab7 += floatval($data['lab']); $tunit7 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec7).'.0' ?> </td>
                <td> <?php echo floatval($tlab7).'.0' ?> </td>
                <td> <?php echo floatval($tunit7).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>

         <!-- TABLE FOR 4TH YEAR 2ND SEM -->
    <table>
            <tr>
                <td colspan="2"> 4th Year </td>
                <td colspan="4"> 2nd Semester </td>
            </tr>
            <tr>
                <th> Course Code </th>
                <th> Course Description </th>
                <th> Lec </th>
                <th> Lab </th>
                <th> Units </th>
                <th> Complab </th>
            </tr>
            <?php foreach($result8 as $id => $data) : ?>
                <tr>
                    <td><?php echo $data['courseCode'] ?></td>
                    <td><?php echo $data['courseName'] ?></td>
                    <td><?php echo $data['lec'] ?></td>
                    <td><?php echo $data['lab'] ?></td>
                    <td><?php echo $data['totalUnits'] ?></td>
                    <td><?php echo $data['isCompLab'] ?></td>
                    <?php $tlec8 += floatval($data['lec']); $tlab8 += floatval($data['lab']); $tunit8 += floatval($data['totalUnits']); ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td class="total" colspan="2"> Total: </td>
                <td> <?php echo floatval($tlec8).'.0' ?> </td>
                <td> <?php echo floatval($tlab8).'.0' ?> </td>
                <td> <?php echo floatval($tunit8).'.0' ?> </td>
                <td> </td>
            </tr>
        </table>
  
        

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="bootstrap/js/sweetalert.min.js"></script>

  </body>
</html>