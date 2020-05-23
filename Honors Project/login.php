<?php  @ob_start(); 
    session_start(); 
    include "funcs.php"; ?>
<DOCYTPE! html>
<html>
    <head>
        <title>LPCRMP - Sign In</title>

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
    
    <!-- <body class = "container"> -->
    <body class = "body">
        <div class = "cover">            
            <img src = "graphics/lpclogo1.png" class = "lpcLogo inline right"> &nbsp; &nbsp;
            <h1 class = "titler inline">LPC - RMP</h1>
        </div>
        <div>
            <br>
            <h1> WELCOME ! </h1>
            <h2> To the Las Positas College Rate My Professor </h2>
            <div class = "centrize framer w60">
            <table class = "table">
                <tbody>
                    <tr> 
                        <td>Please Login by Click the LOGIN button</td>
                        <td style = "text-align: center;"> <button class = "btn btn-info" data-toggle = "collapse" data-target = "#login" aria-expanded="false"> LOG IN </button></td>
                    </tr>
                    <tr>
                        <td>Don't have an Account? Sign up with your zonemail address!</td>
                        <td style = "text-align: center;"><button class = "btn btn-info" data-toggle = "collapse" data-target = "#signup" aria-expanded="false"> SIGN UP  </button></td>
                    </tr>
                </tbody>
            </table>
            </div>
        
            <div class = "collapse framer w60" id = "login">
                <h3> Login With your student email</h3>
                <p> Recall you must be a zonemail user to use it! </p>
                <br>
                <form action = "funcs.php" method = "post">
                    <table class = "table">
                        <tbody>
                            <tr>
                                <td>Enter your Zonemail</td>
                                <td><input type = "text" name = "un" class = "btn btn-input" placeholder = "zonemail"></td>
                            </tr>
                            <tr>
                                <td>Enter Your Password</td>
                                <td><input type = "password" class = "btn btn-input" name = "pw"></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type = "hidden" name = "message" value = "logincheck">
                    <button class = "btn btn-success sidePadder5"> LOG IN </button>
                </form>
            </div>

            <div class = "collapse framer w60" id = "signup" >
                <form action = "funcs.php" method = "post">
                    <h4> Fill Out the information below to sign up to LPCRMP </h4>
                    <p> <strong><u>NOTE</u></strong>: Only zonemail users can use the site!</p>
                    <table class = "table">
                        <tbody>
                            <tr>
                                <th>Enter you Zonemail Address</th>
                                <td><input type = "text" id = "zonemail" name = "zonemail" class = "btn btn-input" placeholder="  Zonemail Address"><p id = "emailchecker"></p></td>
                            </tr>
                            <tr>
                                <th>Choose and Verify a Password</th>
                                <td><input type = "text" id = "pw1" name = "pw1" class = "btn btn-input inline" placeholder="  Password">&nbsp;<input type = "text" id = "pw2" name = "pw2" class = "btn btn-input inline" placeholder="  Verify Password"><br><p id = "checker"><p id = "checker"></td>
                            </tr>
                            <tr>
                                <th>Choose a Major</th>
                                <td>
                                <label class="btn checkboxer inline">
                                    <input type="checkbox" name = "annoStatus" value = "UNDCL"> Undeclared 
                                </label>
                                <label class="btn checkboxer inline">
                                    <input type="checkbox" class = "inline"  name = "annoStatus" id = "nameCheck"> Select Major
                                </label>
                                <select class = "btn sideMargger5 btn-input inline" id = "majorSelect" name = "major" style = "visibility: hidden"><?php echo populateMajorDropdown(connDB());?></select>
                                </td>                                
                            </tr>
                        </tbody>
                    </table>
                    <input type = "hidden" name = "message" value = "signup">
                    <button class = "btn btn-success sidePadder5">SIGN UP</button>
                </form>
            </div>


        </div>
    </body>
    <footer class = "footer">
        <p> <strong>Las Positas College | Academic Year 2020</strong></p>
        <p> Special Thanks to the <u>www.thekomanetskys.com</u></p>
        <p> Shahaf Dan Production (All rights reserved)</p>
    </footer>
    <script>
        document.getElementById("nameCheck").onchange = function() {
            if(document.getElementById("majorSelect").style.visibility == "hidden") document.getElementById("majorSelect").style.visibility = "visible";
            else document.getElementById("majorSelect").style.visibility = "hidden";
        };
        var pw1 = document.getElementById("pw1");
        var pw2 = document.getElementById("pw2");
        var zonemail = document.getElementById("zonemail");
        var lastChar = "";
        pw1.onkeyup = function(event){
            if (event.target.value.length == 0)
            {
                document.getElementById("checker").innerHTML = "";
                pw1.style = "background-color: #ECF6F9 !important;";
            } 
            else if(event.target.value.length < 8) 
            {
                document.getElementById("checker").innerHTML = "password has to be 8 characters or more!";
                pw1.style = "background-color: #FF9A9A !important;";
            }
            else 
            {
                pw1.style = "background-color: #BDFF9A !important;";
                document.getElementById("checker").innerHTML = "Passsword Okay!";
            }
        }

        pw2.onkeyup = function(event){
            if (event.target.value.length == 0)
            {
                document.getElementById("checker").innerHTML = "";
                pw2.style = "background-color: #ECF6F9 !important;";
            } 
            else if(event.target.value != pw1.value) 
            {
                document.getElementById("checker").innerHTML = "passwords have to match!";
                pw2.style = "background-color: #FF9A9A !important;";
            }
            else 
            {
                pw2.style = "background-color: #BDFF9A !important;";
                document.getElementById("checker").innerHTML = "Passsword Confirmed!";
            }
        }

        zonemail.onkeyup = function(event){
            if(event.target.value.length < 23)
            {
                document.getElementById("emailchecker").innerHTML = "Enter your full zonemail address";
                zonemail.style = "background-color: #ECF6F9 !important;"; //blue
            }
            else 
            {
                lastChar = zonemail.value.substring(zonemail.value.length -19, zonemail.value.length);
                ///console.log(lastChar);
                if (lastChar == "zonemail.clpccd.edu")
                {
                    zonemail.style = "background-color: #BDFF9A !important;"; //green
                    document.getElementById("emailchecker").innerHTML = "Email Address Confirmed!";
                } 
                else
                {
                    document.getElementById("emailchecker").innerHTML = "Only zonemail users can register";
                    zonemail.style = "background-color: #FF9A9A !important;"; //red
                }
            }
        }
       
        
    
    </script>

</html>