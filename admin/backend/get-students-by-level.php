<?php
    if(isset($_POST)){
        require('db_conn.php');
        if($conn){
            $level = $_POST["level"];
            $session = $_POST["session"];
            $queryMatno = mysqli_query($conn,"SELECT DISTINCT matno FROM  course_registration WHERE level =".$level." AND session = ".$session."")or die(mysqli_error($conn));
            if(mysqli_num_rows($queryMatno)>0){
                $dataArray = array();
                while($matno = mysqli_fetch_assoc($queryMatno)){
                    $verifyOthername = mysqli_query($conn, "SELECT COUNT(student_id) as total_othernames FROM student_othernames") or die(mysqli_error($conn));
                    $num_names = mysqli_fetch_assoc($verifyOthername);
                    if($num_names['total_othernames']>0){
                        $getStudentData = mysqli_query($conn,"SELECT student.matno, student.surname, student.firstname, (SELECT student_othernames.othername FROM student_othernames WHERE student_othernames.student_id=student.id) AS othername   FROM student WHERE matno ='".$matno['matno']."'")or die(mysqli_error($conn));
                        if(mysqli_num_rows($getStudentData)>0){
                            while($rows = mysqli_fetch_assoc($getStudentData)){
                                $dataArray[] = $rows;
                            }
                        }
                    }else{
                        $getStudentData = mysqli_query($conn,"SELECT matno, surname, firstname FROM student WHERE matno ='".$matno['matno']."'")or die(mysqli_error($conn));
                        if(mysqli_num_rows($getStudentData)>0){
                            while($rows = mysqli_fetch_assoc($getStudentData)){
                                $dataArray[] = $rows;
                            }
                        }
                    }
                }
                echo json_encode($dataArray);
            }else{
                echo "ERROR! No student for that level";
            }
        }
    }

?>