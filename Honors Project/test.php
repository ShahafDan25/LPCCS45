<?php include "connDB.php"; ?>
<?php
    $prof = 5;
    $c = connDB();
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
    echo $data;


?>