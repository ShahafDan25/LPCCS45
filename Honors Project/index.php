<?php  @ob_start(); 
    session_start(); 
    include "funcs.php"; ?>
<DOCYTPE! html>
    <html>
        <head>
            <title>LPCRMP - HOME</title>

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
            
            <div class = "lowerBody">
            <h2><strong><u> Rate My Professor </u></strong></h2>
                <h4> Las Positas College </h4> 
                <ul class = "objectives">
                    <li> Leave a feedback about an instructor! </li>
                    <li> Learn about your professors at LPC! </li>
                    <li> Design and establish an academic path at LPC! </li>
                </ul>
                <br><br>
                    <div class = "btnOptions">
                        <table class = "table optionTable">
                            <thead>
                                <th> OPTION </th>
                                <th> DESCRIPTION </th>
                                <th> CLICK BUTTON </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "text-align: center;"><strong>COMMENT</strong></td> 
                                    <td> Leave some feedback about your expeirnce with one of your instructors! </td>
                                    <td><button class = "btn btn-warning" data-toggle = "collapse" data-target = "#leaveFeedback"> Click Here! </button></td>
                                </tr>
                                <tr>
                                    <td style = "text-align: center;"><strong>READ</strong></td> 
                                    <td> Read the feedbacks other students has to share about a specific instructor! </td>
                                    <td><button class = "btn btn-warning" data-toggle = "collapse" data-target = "#readFeedback" aria-expanded="false"> Click Here! </button></td>
                                </tr>
                                <tr>
                                    <td style = "text-align: center;"><strong>PLAN </strong></td> 
                                    <td> Create an organized cademic planner for your academic path at Las Positas College</td>
                                    <td><button class = "btn btn-warning" onclick = "location.replace('planner.php');"> Click Here! </button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br> <hr class = "sep">
                    <div id = "leaveFeedback" class = "collapse centrize">
                        <div class = "framer w60">
                            <h4> Choose a Professor </h4>
                            <p> Choose a professor from the below dropdown, or else insert his information </p>
                            <hr class = "sep">
                            <form action = "funcs.php" method = "post">
                                <select class = "btn browser-default inline sideMargger5 increaseHeight btn-input" name = "profSelected">
                                    <option>SELECT A PROFESSOR</option>                 
                                    <?php echo populateProfDropdown(connDB()); ?>
                                </select>
                                <input type = "hidden" name = "message" value = "feedAboutProf">
                                <button class = "btn btn-success sidePadder5 sideMargger5 inline">SUBMIT </button>
                            </form>
                        </div> 
                    </div>
                    <div id = "readFeedback" class = "collapse centrize">
                        <div class = "framer w60">
                            <h4> Choose a Professor </h4>
                            <p> Choose a professor to see the comments previous students had about them </p>
                            <hr class = "sep">
                            <form action = "funcs.php" method = "post">
                                <select class = "btn browser-default inline sideMargger5 increaseHeight btn-input" name = "profSelected">
                                    <option>SELECT A PROFESSOR</option>
                                    <?php echo populateProfDropdown(connDB());?>
                                </select>
                                &nbsp;
                                <input type = "hidden" name = "message" value = "readAboutProf">
                                <button class = "btn btn-success sidePadder5 sideMargger5 inline">SUBMIT </button>
                            </form>
                        </div>
                    </div>
                <br>
            </div>
        </body>
        <footer class = "footer">
            <p> <strong>Las Positas College | Academic Year 2020</strong></p>
            <p> Special Thanks to the <u>www.thekomanetskys.com</u></p>
            <p> Shahaf Dan Production (All rights reserved)</p>
        </footer>
    </html>
