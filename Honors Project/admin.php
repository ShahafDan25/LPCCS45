<?php  @ob_start(); ?>
<?php session_start(); ?>
<?php include "funcs.php"; ?>
<DOCYTPE! html>
<html>
    <head>
        <title>LPCRMP - Admin</title>

        <!-- <link rel="shortcut icon" href="./The Market_files/lpcsgLogo.jpg"> -->

            <!-- Bootstrap for CSS -->
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        
        <!-- CSS HARDCODE FILE LINK -->
        <link rel="stylesheet" type="text/css" href="lpcrmp.css"> 

        <!-- Bootstrap for JavaScript -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <!-- MORRIS.JS (for graphing utilities from PHP data) LINKS -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    </head>
    <div class = "cover">
        <button class = "sm2 sp2 btn btn-info inline pull-left" onclick = "location.replace('index.php');"> HOME PAGE </button>
        <button class = "sm2 sp2 btn btn-info inline pull-left" onclick = "location.replace('login.php');"> LOGIN PAGE </button>
        <h1 class = "titler inline">LPC - RMP</h1>
    </div>
    <body class = "body">
        <br><br>
        <h2> ADMIN PAGE </h2><br>
        <h4> ACTIVITIES ONLY THE ADMIN CAN DO</h4>
        <div class = "btnOptions">
            <table class = "table optionTable">
                <thead>
                    <th> OPTION </th>
                    <th> CLICK BUTTON </th>
                </thead>
                <tbody>
                    <tr>
                        <td>Insert a new class to the database</td> 
                        <td><button class = "btn btn-warning" data-toggle = "collapse" data-target = "#newClass" aria-expanded="false"> Click Here  </button></td>
                    </tr>
                    <tr>
                        <td>Register a new Instructor to the School</td> 
                        <td><button class = "btn btn-warning" data-toggle = "collapse" data-target = "#newProf" aria-expanded="false"> Click Here </button></td>
                    </tr>
                    <tr>
                        <td>Add an existing class to an academic term</td> 
                        <td><button class = "btn btn-warning" data-toggle = "collapse" data-target = "#existClass" aria-expanded="false"> Click Here  </button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <br><hr class = "sep">
        <div class = "collapse" id = "newClass">
            <h3>INSERT A NEW CLASS TO THE DATABASE</h3>
            <br>
            <form action = "funcs.php" method = "post" class = "commentForm">
            <table class = "table">
                    <tbody> 
                        <tr>
                            <td>Select a Subject</td>
                            <td><select class = "btn btn-input" name = "subject"><?php echo populateMajorDropdown(connDB());?></select></td>
                        </tr>
                        <tr>
                            <td>Enter Course's Number </td>
                            <td><input type = "text" name = "courseNumber" placeholder="  Course Number" class = "btn btn-input sm2"></td>
                        </tr>
                        <tr>
                            <td> Enter Course's Name </td>
                            <td><input type = "text" name = "courseName" placeholder = "  Class Name" class = "btn btn-input sm2"></td>
                        </tr>
                        <tr>
                            <td> Enter Course's Units </td>
                            <td><input type = "number" name = "units" style = "width: 30% !important;" placeholder = "Units" class = "btn btn-input sm2" min = "0" max = "8" step = "0.5"></td>
                        </tr>
                    </tbody>
            </table>
            <br>
                <input type = "hidden" name = "message" value = "insertNewCourse"><br>
                <button class = "btn btn-success sidePadder5">SUBMIT</button>
            </form>
        </div>
        <div class = "collapse" id = "newProf">
            <h3>INSERT A NEW INSTRUCTOR TO THE DATABASE</h3>
            <br>
            <form action = "funcs.php" method = "post" class = "commentForm">
                <input type = "text" placeholder= "First Name" name = "firstName" class = "btn btn-input">
                <input tpye = "text" placeholder= "Last Name" name = "lastName" class = "btn btn-input"><br><br>
                <p class = "inline">Select Their <strong>Department</strong>:</p>
                <select class = "btn btn-input inline" name = "dept" required>
                    <option>SELECT A SUBJECT</option>
                    <!-- PHP CODE TO POPULATE -->
                    <?php
                        echo populateMajorDropdown(connDB());
                    ?>
                </select> <br><br>
                <input type = "hidden" name = "message" value = "insertNewProf">
                <button class = "btn btn-success sidePadder5">SUBMIT</button>
            </form>
        </div>
        <div class = "collapse" id = "existClass">
            <h3> ADD AN EXISTING CLASS TO AN ACADEMIC TERM </h3><br>
            <form action = "funcs.php" method = "post" class = "commentForm">
                <table class = "table">
                    <tbody>
                        <tr>
                            <th> Choose Subject </th>
                            <td><select class = "btn btn-input inline" name= "subject" required><?php echo populateAllSubjects(connDB()); ?></select> </td>
                        </tr>
                        <tr>
                            <th> Choose Class </th>
                            <td><select class = "btn btn-input inline" name= "number" required><?php echo populateAllClasses(connDB()); ?></select></td>
                        </tr>
                        <tr>
                            <th> Choose Instructor </th>
                            <td><select class = "btn btn-input inline" name = "prof" required><?php echo populateProfDropdown(connDB()); ?></select></td>
                        </tr>
                        <tr>
                            <th> Choose Academic Term </th>
                            <td><select class = "btn btn-input inline" name = "term" required><?php echo popTerms(); ?></select></td>
                        </tr>
                        <tr>
                            <th> Choose Year </th>
                            <td><select class = "btn btn-input inline" name = "year" required><?php echo popYears(); ?></select></td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <input type = "hidden" value = "addExists" name = "message">
                <button class = "btn btn-success sidePadder5"> SUBMIT </button>
            </form> 
        </div>  
    </body>
    <footer class = "footer">
        <p> <strong>Las Positas College | Academic Year 2020</strong></p>
        <p> Special Thanks to the <u>www.thekomanetskys.com</u></p>
        <p> Shahaf Dan Production (All rights reserved)</p>
    </footer>
</html>