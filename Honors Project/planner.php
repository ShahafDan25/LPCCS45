<?php  @ob_start(); 
    session_start(); 
    include "funcs.php"; ?>
<?php $user = $_SESSION['user'];?>
<DOCYTPE! html>
    <html>
        <head>
            <title>LPCRMP Planner</title>

            <!-- <link rel="shortcut icon" href="./The Market_files/lpcsgLogo.jpg"> -->

             <!-- Bootstrap for CSS -->
             <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
            
            <!-- LINK TO OUR JS CODE -->
            <script src = "lpcrmp.js"></script> 

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
            
            <button class = "sm2 sp2 btn btn-info inline pull-left" onclick = "location.replace('admin.php');"> ADMIN </button>
            <button class = "sm2 sp2 btn btn-info inline pull-left" onclick = "location.replace('login.php');"> LOGIN PAGE </button>
            <h1 class = "titler inline">LPC - RMP</h1>
        </div>
        <!-- <body class = "container"> -->
        <body class = "body">
            <div>
                <h2> WELCOME TO YOUR ACADEMIC PLANNER </h2>
                <div class = "framer centrize wider w85">
                    <h3>Here are your currently tracked and planned courses: </h3>
                    <!-- PHP WILL PRINT A TABLE -->
                    <?php popStudCourses(connDB(), $user); ?>
                    <!-- PHP WILL FINISH PRINTING THE TABLE -->
                    <form action = "funcs.php" method = "post">
                        <input type = "hidden" name= "message" value = "pdfReport">
                        <button class = "btn btn-info sidePadder5">GENERATE PDF SUMMARY</button>
                    </form>
                    <hr class = "sep">
                    <h3> Add a Course to your plan! </h3>
                    <form action = "funcs.php" method = "post">
                        <table class = "table optionTable">
                            <thead>
                                <th> Category </th>
                                <th> Select From: </th> 
                                <th> Category </th>
                                <th> Select From: </th> 
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Academic Term</td>
                                    <td><select class = "btn btn-input inline" name = "term" required><?php echo popTerms(); ?></select>&nbsp;<select class = "btn btn-input inline" name = "year" required><?php echo popYears(); ?></select> </td>
                                    <td>Instructor Teaching</td>
                                    <td><select class = "btn btn-input inline" name = "prof" required><?php echo populateProfDropdown(connDB()); ?></select></td>
                                </tr>
                                <tr>
                                    <td> Grade Recieved </td>
                                    <td><select class = "btn btn-input inline" name = "grade" required><?php echo popGrades(); ?></select></td>
                                    <td>Course Taken</td>
                                    <td><select class = "btn btn-input inline" name = "course" required><?php echo populateAllClasses(connDB()); ?></select></td>
                                </tr>
                            </tbody>
                        </table>
                        <input type = "hidden"  name = "message" value = "insertStudCourse">
                        <button class = "btn btn-success sidePadder5 inline"> SUBMIT </button>
                    </form>
                    <hr class = "sep">
                    <h3> Cumulative GPA: <?php echo calcGPA(connDB(), $user); ?></h3>
                    <?php $data = popGpaGraph(connDB(), $user); ?> 
                    <div class = "graphicalMorris">
                        <div id = "gpaGraph"><!-- GRAPH VIA JS --></div>
                    </div>
                </div>
            </div>
        </body>
        <footer class = "footer">
            <p> <strong>Las Positas College | Academic Year 2020</strong></p>
            <p> Special Thanks to the <u>www.thekomanetskys.com</u></p>
            <p> Shahaf Dan Production (All rights reserved)</p>
        </footer>
        <script>
        //average score graph (linear) 
            Morris.Line({
                element : 'gpaGraph', 
                data:[<?php echo $data ?>], 
                xkey: ['SEMESTER'],
                ykeys: ['GPA'],
                ymin: '2.0',
                ymax: '4.0',
                labels:['GPA'],
                hideHover:'auto',
                stacked:true
            });
    </script>
    </html>