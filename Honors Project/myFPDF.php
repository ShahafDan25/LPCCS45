<?php  @ob_start(); 
    session_start(); 
    include "conndb.php"; 
    
    // ------------------------------------------------ //
    
    require "fpdf_lib/fpdf.php";
    class myFPDFClass extends FPDF //extend all the features from the FPDF class, but more functions
    {

        function Heads()
        {
            $this -> SetFont('Arial', 'B', 20); 
            $this -> Cell(40,12,'My Tracked Classes Report', 'C');
            //$this->Cell( 40, 10, $this->Image("graphics/lpclogo1.png", 120, 5, 35), 0, 0, 'L', false );
            $this -> Ln();
            $this -> Ln();
            return;
        }

        function tableHead() 
        {
            $this -> SetFont('Arial', 'B', 14); 
            $this -> Cell(30, 10, 'Term', 1, 0, 'C'); 
            $this -> Cell(85, 10, 'Classes Taken', 1, 0, 'C');
            $this -> Cell(30, 10, 'Instructor', 1, 0, 'C');
            $this -> Cell(18, 10, 'Grade', 1, 0, 'C');
            $this -> Cell(18, 10, 'Units', 1, 0, 'C');
            $this -> Ln(); 
            $this -> SetFont('Arial', 'B', 12); 
            $this -> Cell(30, 8, '   -   ', 1, 0, 'C');
            $this -> Cell(65, 8, 'Name', 1, 0, 'C');
            $this -> Cell(20, 8, 'Code', 1, 0, 'C');
            $this -> Cell(30, 8, '   -   ', 1, 0, 'C');
            $this -> Cell(18, 8, '   -   ', 1, 0, 'C');
            $this -> Cell(18, 8, '   -   ', 1, 0, 'C');
            $this -> Ln(); 
            return;
        }

        //table body: insert only necessary information from the report page
        function tableBody($c, $u)
        {
            $this -> SetFont('Arial', 'B', 10); 
            $sql = "SELECT c.Units, sc.Grade, sc.Term, sc.Year, p.FirstName, p.LastName, sc.Grade, c.Name, c.Subjects_Code, sc.Courses_Number FROM Courses c JOIN StudCourse sc ON sc.Courses_number = c.Number JOIN Instructors p ON p.ID = sc.Prof_ID WHERE sc.Stud_Zonemail = '".$u."';";
            $s = $c -> prepare($sql);
            $s -> execute();
            $totalUnits = 0;
            $totalIndValue = 0;
            while($r = $s -> fetch(PDO::FETCH_ASSOC))
            {
                //table information
                $this -> Cell(30, 8, $r['Term'].' | '.$r['Year'], 1, 0, 'C');
                $this -> Cell(65, 8, $r['Name'], 1, 0, 'C');
                $this -> Cell(20, 8, $r['Subjects_Code'].$r['Courses_Number'], 1, 0, 'C');
                $this -> Cell(30, 8, $r['LastName'], 1, 0, 'C');
                $this -> Cell(18, 8, $r['Grade'], 1, 0, 'C');
                $this -> Cell(18, 8, $r['Units'], 1, 0, 'C');
                $this -> Ln(); // new line
                ///update the GPA each round
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
            $gpa = round((($totalIndValue/$totalUnits)), 2); //GPA vlue
               
            // ------------------ other information :add later more potentially ---------------------//
            $this -> Ln();
            $this -> SetFont('Arial', 'B', 14); 
            $this -> Cell(30, 10, 'Cumulative GPA: '.$gpa);
            $this -> Ln();
            $this -> Ln();
            return;      
        }
    }
?>