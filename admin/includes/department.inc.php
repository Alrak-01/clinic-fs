<?php
    $department = new Department();
    $department->tableName = "tbl_department";

        if (isset($_POST['submit'])){
              $department = trim(stripslashes($_POST['department']));
              $description = trim(stripslashes($_POST['description']));


            $pattern = '/^[a-zA-Z0-9.,()"\' ]+$/';
//              Define a regular expression pattern to match alphanumeric characters, comma, period, double quotes, and parentheses


            if (empty($department) || empty($description)){
                  header("location:../add-department.php?e=emn");
//                  INPUT FIELD IS EMPTY
              }
              elseif (!ctype_alnum($department)){
                  header("location:../add-department.php?e=alh");
//                  INPUT CONTAINS OTHER CHARACTERS ASIDE ALPHANUMERIC CHARACTERS
              }
            elseif (!preg_match($pattern, $description)){
                header("location:../add-department.php?e=nm");
//                INPUT CHANRACTERS SHOULD MATCH ALPHANUMERIC, COMMA, FULL STOP, DOUBLE QOUTES, AND BRACKET
            }
              else{
                  $result = $department->createDepartment($department, $description);
                  if ($result === 0){
                      header("location:../add-department.php?e=db");
//                      DATABASE ERROR OCCURRED
                  }
                  elseif ($result === 1){
                      header("location:../display-department.php?m=s");
//                      DEPARTMENT CREATED SUCCESSFULLY
                  }
              }
        }

//        UPDATE DEPARTMENT BEGINS

        if (isset($_POST['update'])){
            $department = stripslashes($_POST['department']);
            $description = stripslashes($_POST['description']);

            if (empty($department) || empty($description)){
                header("location:../update-department.php?e=emn");
//                  INPUT FIELD IS EMPTY
            }
            elseif (!ctype_alnum($department) || !ctype_alnum($description)){
                header("location:../update-department.php?e=alh");
//                  INPUT CONTAINS OTHER CHARACTERS ASIDE ALPHANUMERIC CHARACTERS
            }
            else{
                $id = $_GET['id'];
                $result = $department->updateDepartment($department, $description, $id);
                if ($result === 0){
                    header("location:../update-department.php?e=db");
//                    DATABASE ERROR OCCURRED
                }
                elseif ($result === 1){
                    header("location:../display-department.php?m=s");
//                    DEPARTMENT UPDATED SUCCESSFULLY
                }
            }
        }

//        DELETE DEPARTMENT BEGINS
        if (isset($_GET['del']) && isset($_GET['id'])){
                $id = $_GET['id'];
                $result = $department->deleteDepartment($id);
                if ($result === 0){
                    header("location:../display-department.php?e=db");
//                    DATABASE ERROR OCCURRED
                }
                elseif ($result === 1){
                    header("location:../display-department.php?m=s");
//                    DEPARTMENT DELETED SUCCESSFULLY
                }
        }