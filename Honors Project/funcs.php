<?php
    @ob_start(); 
    session_start();
    include "myFPDF.php";
    
    // ----------------------------------- //
    // ----------- POSTS ----------------- //
    // ----------------------------------- //
    if($_POST['message'] == "insertProfessor")
    {
        $fn = $_POST['firstname'];
        $ln = $_POST['lastname'];
        if(empty($_POST['taken'])) $taken = true;
        else $taken = false;
        insertProfessor(connDB(), $fn, $ln, $taken);
        //return to page - CHANGE TO INDEX.PHP LATER
        echo '<script>location.replace("index.php");</script>';
    }
   
    if($_POST['message'] == "commentary")
    {
        $a = "";
        if($_POST['annoStatus'] == "anno") $a = "Anonymous";
        else $a = $_POST['commenterName'];
        $class = $_POST['courseTaken'];
        $term = $_POST['termTaken'];
        $year = $_POST['yearTaken'];
        $grade = $_POST['grade'];
        $comment = $_POST['comment'];
        $rating = $_POST['ratings'];
        date_default_timezone_set("America/Los_Angeles"); /// set time zone
        $datetimestamp = date ("Y-m-d H:i:s"); //current time in that time zone
        comment(connDB(), $grade, $comment, $a, $term, $year, $datetimestamp, $class, $rating);
        echo '<script>location.replace("index.php");</script>';
    }

    if($_POST['message'] == "feedAboutProf")
    {
        $p = $_POST['profSelected'];
        updateProfFeed(connDB(), $p);
        echo '<script>location.replace("comment.php");</script>';
        $_SESSION['commentProf'] = $_POST['profSelected'];

    }

    if($_POST['message'] == "readAboutProf")
    {
        updateProfRead(connDB(), $_POST['profSelected']);
        echo '<script>location.replace("read.php");</script>';
    }
   
    if($_POST['message'] == 'insertNewProf')
    {
        insertNewProf(connDB(), $_POST['firstName'], $_POST['lastName'], $_POST['dept']); //insert to db
        echo '<script>location.replace("admin.php")</script>';; //go back to admin page
    }

    if($_POST['message'] == 'insertNewCourse')
    {
        newCourse(connDB(), $_POST['courseName'], $_POST['courseNumber'], $_POST['subject'], $_POST['units']);
        echo '<script>alert("New Course Inserted Succesfully!"); location.replace("admin.php");</script>';
    }

    if($_POST['message'] == "chooseSubject")
    {
        updateChosenSubject(connDb(), $_POST['subject']);
        echo '<script>location.replace("admin.php");</script>';
    }

    if($_POST['message'] == "addExists")
    {
        courseLog(connDb(), $_POST['subject'], $_POST['number'], $_POST['prof'], $_POST['term'], $_POST['year']);
        echo '<script>location.replace("admin.php");</script>';
    }

    if($_POST['message'] == "logincheck")
    {
        if(checkcredentials(connDB(), $_POST['un'], $_POST['pw'])) 
        {
            echo '<script>location.replace("index.php");</script>';
            $_SESSION['user'] = $_POST['un'];
        }   
        else 
        {
            echo '<script>alert("Error, Wrong Credentials"); location.replace("login.php");</script>';
        }
    }

    if($_POST['message'] == "signup")
    {
        if(userSignUp(connDB(), $_POST['zonemail'], $_POST['pw2'], $_POST['major']))
        {
            echo '<script>alert("Sign Up Was Successful! Go Ahead and Log In Please");location.replace("login.php");</script>';
        }
        else
        {
            echo '<script>location.replace("login.php");</script>';
        }
    }

    if($_POST['message'] == "planner")
    {
        if (checkZonemail(connDB(), $_POST['zonemail'])) echo '<script>location.replace("planner.php");</script>';
        else echo '<script>alert ("Your zonemail was not found in the system. Try again"); location.replace("index.php");</script>';
    }

    if($_POST['message'] == "insertStudCourse")
    {
        $user = $_SESSION['user'];
        $prof = $_POST['prof'];
        $term = $_POST['term'];
        $year = $_POST['year'];
        $grade = $_POST['grade'];
        $course = $_POST['course'];
        var_dump($user);
        insertStudCourse(connDB(), $user, $prof, $course, $term, $year, $grade);
        echo '<script>location.replace("planner.php");</script>';
    }

    if($_POST['message'] == "pdfReport")
    {
        // var_dump("user: ".$_SESSION['user']);
        $user = $_SESSION['user'];
        pdf_report(connDB(), $user);
        echo '<script>location.replace("planner.php");</script>';
    }
    // ----------------------------------- //
    // ---------- FUNCTIONS -------------- //
    // ----------------------------------- //
    function pdf_report($c, $user) //generate pdf report
    {
        //--------------- report code ---------------------//
        $pdf = new myFPDFClass; 
        $pdf -> AddPage();
        $pdf -> Heads();
        $pdf -> tableHead();
        $pdf -> tableBody(connDB(), $user);

        date_default_timezone_set("America/Los_Angeles"); /// set time zone
        $todayte = date ("Y-m-d");

        $reportFile = fopen('rmpclasses'.$todayte.'.pdf', 'w+');
        fclose($reportFile);
        $pdf -> Output('rmpclasses'.$todayte.'.pdf', 'F');
        echo '<script>alert("YOUR PDF IS GENERATED AS: rmpclasses'.$todayte.'.pdf  ");</script>';
        return;
    }

    function insertStudCourse($c, $u, $p, $l, $t, $y, $g)
    {
        $sql = "INSERT INTO StudCourse VALUES (".$l.", (SELECT Subjects_Code FROM Instructors WHERE ID = ".$p."), '".$u."', '".$t."', ".$y.", '".$g."', ".$p.", (SELECT Subjects_Code FROM Instructors WHERE ID = ".$p."));";
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c -> exec($sql);
        return;
    }
    
    function calcGPA($c, $user)
    {
        $sql = "SELECT c.Units, sc.Grade FROM Courses c JOIN StudCourse sc ON sc.Courses_number = c.Number WHERE sc.Stud_Zonemail = '".$user."';";
        $s = $c -> prepare($sql);
        $s -> execute();
        $totalUnits = 0;
        $totalIndValue = 0;
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $totalUnits += $r['Units'];
            $value = 0;
            if($r['Grade'] == 'A') $value = 4;
            elseif ($r['Grade'] == 'B') $value = 3;
            elseif($r['Grade'] == 'C') $value = 2;
            elseif ($r['Grade'] == 'D') $value = 1;
            elseif ($r['Grade'] == 'W') $totalUnits -= $r['Units'];
            else $value = 0;
            $totalIndValue += ($r['Units'] * $value);
        }
        return round((($totalIndValue/$totalUnits)), 2);//essentially the floor function
    }
    
    function checkZonemail($c, $z)
    {
        $sql = "SELECT COUNT(*) FROM Credentials WHERE zonemail = '".$z."';";
        if(($c -> query($sql) -> fetchColumn()) == 0) return false;
        else return true;
    }

    function comment($c, $g, $text, $a, $t, $y, $dt, $course, $rating)
    {
        //verify the comment does not exist!
        $profID = $_SESSION['commentProf'];
        $sql_checkExists = "SELECT Year FROM Comments WHERE Term = '".$t."' AND Year = ".$y." AND Courses_Number = ".$course." AND Courses_Subjects_Code = (SELECT Subjects_Code FROM Instructors WHERE ID = ".$profID.") AND Instructors_ID = ".$profID.";";
        $s = $c -> prepare($sql_checkExists);
        $s -> execute();
        if(!$s -> fetch(PDO::FETCH_ASSOC)) 
        {
            echo '<script>alert("You already commented on this course, about this professor, in this term and year, before.");</script>';
            return;
        }


        $sql2 = "SELECT MAX(ID)+1 FROM Comments;";
        $s = $c -> prepare($sql2);
        $s -> execute();
        $max = $s -> fetchColumn();

        $sql = "INSERT INTO Comments (ID, Grade, TEXT, Name, Term, Year, DateTimeStamp, Courses_Number, Courses_Subjects_Code, Instructors_ID, Rating) VALUES (".$max.",'".$g."', '".$text."', '".$a."', '".$t."', ".$y.", '".$dt."', ".$course.", (SELECT Subjects_Code FROM Instructors WHERE ID = ".$profID."), ".$profID.", ".$rating.");";

        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c -> exec($sql);
        echo '<script>alert("Comment Stored Succesfully!");</script>';

        return;
    }

    function updateProfRead($c, $p)
    {   
        $sql = "UPDATE Instructors SET Reader = 0;";
        $sql .= "UPDATE Instructors SET Reader = 1 WHERE ID = ".$p.";";
        $c -> prepare($sql) -> execute();
        return;
    }

    function checkcredentials($c, $m, $p)
    {
        //verify password is correct for the given email address
        $sql = "SELECT password FROM Credentials WHERE zonemail = '".$m."';";
        $s = $c -> prepare($sql);
        $s -> execute();
        $r = $s -> fetch(PDO::FETCH_ASSOC);
        if($r['password'] == md5($p)) return true;
        else return false;        
    }
    
    function userSignUp($c, $m, $p, $major)
    {
        //NOTE: c = connection, m = zonemail, p = password
        //structure: 
        //if not zonemail: alert, locationReplace return false
        //elseif: verify no such entry in the db
        //else:  insert, return true
        if (substr($m, (strlen($m) - 19), 19) != "zonemail.clpccd.edu") echo '<script>alert("Only zonemails can use this site!"); location.replace("login.php");</script>';
        else
        {
            // $sql = "SELECT COUNT(*) FROM Credentials WHERE zonemail = '".$m."';";
            if(checkZonemail($c, $m)) echo '<script>alert("this email address is already registered");</script>';
            else
            {
                $sql = "INSERT INTO Credentials VALUES ('".$m."', md5('".$p."'), '".$major."');";
                $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $c->exec($sql);
                return true;
            }
        }
        
        return false;
        
    }

    function courseLog($c, $subject, $number, $prof, $term, $year)
    {
        $sql = "INSERT INTO ProfCourse VALUES (".$number.", '".$subject."', ".$prof.", '".$term."', ".$year.")";
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c->exec($sql);
        return;
    }
    
    function newCourse($c, $name, $number, $subject, $units)
    {
        //first: check existence:
        $sql = "SELECT COUNT(*) FROM Courses WHERE Number = ".$number." AND Subjects_Code = '".$subject."');";
        $s = $c -> prepare($sql);
        $s -> execute();
        if(!$s -> fetch(PDO::FETCH_ASSOC)) 
        {
            echo '<script>alert("Course Already Exists in the DataBase!");location.replace("admin.php");</script>';
            return;
        }
        
        $sql = "INSERT INTO Courses VALUES (".$number.", '".$subject."', '".$name."', ".$units.")";
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c->exec($sql);
        return;
    }
    
    function insertNewProf($c, $f, $l, $d)
    {
        $sql = "INSERT INTO Instructors (FirstName, LastName, Subjects_Code) VALUES('".$f."', '".$l."', '".$d."');";
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c->exec($sql);
        return;
    }
    
    function updateProfFeed($c, $p)
    {
        $sql = "UPDATE Instructors SET Comment = 1 WHERE ID = ".$p;
        $s = $c -> prepare($sql);
        $s -> execute();
        //update everyone elses
        $sql2 = "UPDATE Instructors SET Comment = 0 WHERE NOT ID = ".$p;
        $s2 = $c -> prepare($sql2);
        $s2 -> execute();
        return;
    }

    function insertProfessor($c, $f, $l, $t)
    {
        $sql = "INSERT INTO Instructors (FirstName, LastName) VALUES ('".$f."', '".$l."');";
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $c->exec($sql);
        //add echo script to go to comment page
    }




    /// ----------------- POPULATORS ---------------------///
    function populateCoursesForProf($c)
    {
        $sql = "SELECT Subjects_Code, Number, Name FROM Courses WHERE Subjects_Code = (SELECT Subjects_Code FROM Instructors WHERE Comment = 1);";
        $s = $c -> prepare($sql);
        $s -> execute();
        $data = "";
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= '<option value = '.$r["Number"].'>'.$r['Subjects_Code'].$r['Number'].' [ '.$r["Name"].' ] </option>';
        }
        return $data;
    }
    function populateAllSubjects($c)
    {
        $data = "";
        $sql = "SELECT * FROM Subjects";
        $s = $c ->prepare($sql);
        $s -> execute();
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= "<option value = ".$r['Code'].">".$r['Name']." [ ".$r['Code']." ]  </option>";
        }
        return $data;    
    }

    function populateAllClasses($c)
    {
        $data = "";
        $sql = "SELECT * FROM Courses";
        $s = $c ->prepare($sql);
        $s -> execute();
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= "<option value = ".$r['Number'].">".$r['Name']." [ ".$r['Subjects_Code']." ".$r['Number']." ]  </option>";
        }
        return $data;    
    }

    function populateYearDropdown($c)
    {
        $data = "";
        $sql = "SELECT DISTINCT Year FROM ProfCourse";
        $s = $c -> prepare($sql);
        $s -> execute();
        while($row = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= "<option>".$row['Year']."</option>";
        }
        return;
    }
    function populateProfDropdown($c)
    {
        $data = "";

        $sql = "SELECT FirstName, LastName, ID FROM Instructors";
        $s = $c -> prepare($sql);
        $s -> execute();
        while($row = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= "<option value = ".$row['ID'].">".$row['FirstName']."  ".$row['LastName']."</option>";
        }
        return $data;
    }

    function populateMajorDropdown($c)
    {
        $data = "";

        $sql = "SELECT Code, Name FROM Subjects";
        $s = $c -> prepare($sql);
        $s -> execute();
        while($row = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= "<option value = '".$row['Code']."'>".$row['Name']."  ( ".$row['Code']." ) </option>";
        }
        return $data;
    }

    function popYears()
    {
        $options = "";
        for($x = 0; $x <= 11; $x++)
        {
            $options .= '<option value = '.($x + 2010).'>  - '.($x+2010).'  -  </option>';
        }
        return $options;
    }

    function popTerms()
    {
        $options = '<option value = "summer">SUMMER</option>';
        $options .= '<option value = "fall">FALL</option>';
        $options .= '<option value = "spring">SPRING</option>';
        return $options;
    }

    function popGrades()
    {
        $options = '<option value = "A">A</option>';
        $options .= '<option value = "B">B</option>';
        $options .= '<option value = "C">C</option>';
        $options .= '<option value = "C">D</option>';
        $options .= '<option value = "C">F</option>';
        $options .= '<option value = "C">W</option>';
        return $options;
    }
    function popRatings()
    {
        for($x = 1; $x <= 10; $x ++)
        {
            $options .= '<option value = "'.$x.'"> '.$x.' </option>';
        }
        return $options;
    }

    function populateChosenSubjectNumber($c)
    {
        $sql = "SELECT Number, Name FROM Courses;";
        $s = $c -> prepare($sql);
        $s -> execute();
        $data = "";
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $data .= '<option value = '.$r["Number"].'>'.$r['Number'].' [ '.$r["Name"].' ] </option>';
        }
        return $data;
    }

    function populateCommentTable($c)
    {
        $colors = array("FF7E7E", "#FCA0A0", "#FCCEA0", "FCE0A0", "EFFF87", "#FCFCA0", "#CEFCA0", "#A0FCA0", "#A0FCCE", "#A0C3FC");
        //based on index of rating, we will choose a color for the front end user interface: that took me a while to firgure out but looks really cool to my opinion
        $sql = "SELECT Name, TEXT, DateTimeStamp, Rating FROM Comments WHERE Instructors_ID = (SELECT ID FROM Instructors WHERE Reader = 1) LIMIT 12;";
        $s = $c -> prepare($sql);
        $s -> execute();
        $foundData = false;
        $table = "";
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {

            if(!$foundData)
            {
                $table .= "<table class = 'table'>
                <thead>
                    <th> Feedbacker: </th>
                    <th> Rated: </th>
                    <th> Comment: </th>
                    <th> Date: </th>
                </thead>
                <tbody>";
            }
            if($r['Name'] == "") $table .= '<tr ><td> Anonymous </td>';
            else $table .= '<tr><td>'.$r['Name'].'</td>';
            $table .= '<td><div style = "background-color: '.$colors[($r['Rating']-1)].' !important; border-radius:8px; border: 2px solid black; padding: 20% !important; text-align:center;">'.$r['Rating'].'</div></td>';
            $table .= '<td>'.$r['TEXT'].'</td>';
            $table .= '<td>'.substr($r['DateTimeStamp'],5,2).'.'.substr($r['DateTimeStamp'],8,2).'.'.substr($r['DateTimeStamp'],0,4).'</td>';
            $table .= '</tr>';
            $foundData = true;
        }
        if ($table == "") 
        {
            echo '<h5>No feedback was recorded for this instructor yet</h5><br>';
        }
        else
        {   
            $table .= '</tbody></table>';
            echo $table;
        }
        return;
    }

    function popStudCourses($c, $user)
    {
        $sql = "SELECT sc.Courses_Number, sc.Subjects_Code, sc.Term, sc.Year, sc.Grade, c.Units, c.Name FROM StudCourse sc JOIN Courses c ON (c.Number = sc.Courses_Number AND c.Subjects_Code = sc.Subjects_Code) WHERE Stud_Zonemail = '".$user."' ORDER BY sc.Year, sc.Term ;";
        $s = $c ->prepare($sql);
        $s -> execute();
        $foundData = false;
        $table = "";
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            if(!$foundData)
            {
                $table .= "<table class = 'table optionTable'>
                <thead>
                    <th> Term / Year </th>
                    <th> Course </th> 
                    <th> Grade </th>
                    <th> Units </th>
                </thead>
                <tbody>";
            }
            $table .= "<tr><td>".$r['Term']. " / ".$r['Year']."</td>";
            $table .= "<td>".$r['Name']." [ ".$r['Subjects_Code']." - ".$r['Courses_Number']." ]</td>";
            $table .= "<td>".$r['Grade']."</td>";
            $table .= "<td>".$r['Units']."</td>";
            $table .= "</tr>";
            $foundData = true;
        }
        if ($table == "") echo "<h5>No classes are on your record yet!</h5><br>";
        else $table .= "</tbody></table>"; echo $table;
        return;
    }

    function popProfScoreGraph($c, $prof)
    {
        $sql = "SELECT DateTimeStamp, Rating FROM Comments WHERE Instructors_ID = ".$prof.";";
        $s = $c ->prepare($sql);
        $s -> execute();
        $counter = 0; $total = 0; $average = 0; //initializations
        $r = $s -> fetch(PDO::FETCH_ASSOC);
        $counter++;
        $total += $r['Rating'];
        $average = round(($total / $counter), 2); //to 2 decimal places!
        $data = "{TIME:'".$r['DateTimeStamp']."', SCORE:'".$average."'}";
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            $counter++;
            $total += $r['Rating'];
            $average = round(($total / $counter), 2); //to 2 decimal places!
            $data .= ", {TIME:'".$r['DateTimeStamp']."', SCORE:'".$average."'}";
        }
        return $data;
    }

    function popGpaGraph($c, $user)
    {
        //idea: two inner sql statements (embedded)
        //first for year and term
        //second (inner one) for gpa calculations per each term/year
        $sql = "SELECT DISTINCT Term, Year FROM StudCourse WHERE Stud_Zonemail = '".$user."' ORDER BY Year;";
        $s = $c -> prepare($sql);
        $s -> execute();
        $totalUnits = 0; //initiatlize variables to calculate the GPA per term
        $totalIndValue = 0;
        $concurrent = 0;
        while($r = $s -> fetch(PDO::FETCH_ASSOC))
        {
            //next: embed the inner sql statement to calc gpa per semester;
            $sql2 = "SELECT sc.Grade, c.Units FROM Courses c JOIN StudCourse sc ON c.Number = sc.Courses_Number WHERE sc.Stud_Zonemail = '".$user."' AND sc.Term  = '".$r['Term']."' AND sc.Year = '".$r['Year']."';";
            $s2 = $c -> prepare($sql2);
            $s2 -> execute();
            $sem = 0;
            if($r['Term'] == "spring") $sem = 0.1;
            elseif($r['Term'] == "summer") $sem = 0.4;
            elseif($r['Term'] == "fall") $sem = 0.6;
            $counter = 0;
            while($r2 = $s2 -> fetch(PDO::FETCH_ASSOC))
            {
                $counter++;
                echo $counter;
                $totalUnits += $r2['Units'];
                $value = 0;
                if($r2['Grade'] == 'A') $value = 4;
                elseif ($r2['Grade'] == 'B') $value = 3;
                elseif($r2['Grade'] == 'C') $value = 2;
                elseif ($r2['Grade'] == 'D') $value = 1;
                elseif ($r2['Grade'] == 'W') $totalUnits -= $r2['Units'];
                else $value = 0;
                $totalIndValue += ($r2['Units'] * $value);
            }
            echo " | ";
            $termGPA = round(($totalIndValue/$totalUnits), 2);
            $data .= "{SEMESTER:'".($r['Year']+$sem)."', GPA:'".$termGPA."'}, ";
        }
        //next: remove the last comma for snytax corrections
        $data = substr($data, 0, strlen($data) - 2);//cut the last two characters (remove a space and a comma)
        return $data;
    }
?>