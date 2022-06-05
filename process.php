<?php

session_start();
$id = $_SESSION["id"];
$MID = $_GET['MID'];
$SDATETIME = $_GET['SDATETIME'];
$TNAME = $_GET['TNAME'];
$CNT=0;
$tns = "
    (DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
    )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$conn = new PDO($dsn, "d201902679", "1003");

switch($_GET['mode']){
    case 'insert':
        $stmt = $conn -> prepare("INSERT INTO TP_MOVIE(MID, TITLE, OPEN_DAY, DIRECTOR, RATING, LENGTH) 
                VALUES ((SELECT NVL(MAX(MID), 0) + 1 FROM TP_MOVIE), :TITLE, :OPEN_DAY, :DIRECTOR,:RATING,:LENGTH)");

        $stmt->bindParam(':MID',$MID); 
        $stmt->bindParam(':TITLE',$TITLE); 
        $stmt->bindParam(':OPEN_DAY',$OPEN_DAY);
        $stmt->bindParam(':RATING',$RATING);

        $MID = $_POST['MID'];
        $TITLE = $_POST['TITLE']; 
        $OPEN_DAY = $_POST['OPEN_DAY'];
        $RATING = $_POST['RATING']; 

        $stmt->execute();
        header("Location: serach_mv.php");
        break;
    case 'delete':
        $stmt = $conn->prepare('DELETE FROM TP_MOVIE WHERE MID = :MID');
        $stmt->bindParam(':MID', $MID); 
        $MID = $_POST['MID'];
        $stmt->execute();
        header("Location: serach_mv.php");
        break;
    case 'modify':
        $stmt = $conn->prepare("UPDATE TP_MOVIE SET MID=:MID, TITLE=:TITLE, OPEN_DAY=:OPEN_DAY, DIRECTOR=:DIRECTOR, RATING=:RATING,LENGTH=:LENGTH
        WHERE MID = :MID");
        $stmt->bindParam(':MID',$MID); 
        $stmt->bindParam(':TITLE',$TITLE); 
        $stmt->bindParam(':OPEN_DAY',$OPEN_DAY);
        $stmt->bindParam(':RATING',$RATING);
        $stmt->bindParam(':DIRECTOR',$DIRECTOR);
        $stmt->bindParam(':LENGTH',$LENGTH);


        $MID = $_POST['MID'];
        $TITLE = $_POST['TITLE']; 
        $OPEN_DAY = $_POST['OPEN_DAY'];
        $RATING = $_POST['RATING']; 
        $RATING = $DIRECTOR['DIRECTOR']; 
        $RATING = $LENGTH['LENGTH']; 


        $stmt->execute();
        header("Location: search_mv.php?MID=$MID");
        break;
    case 'reserve' :
        $seat_list=$_POST["seat"];
        
        foreach($seat_list as $seat_num){
            $CNT++;
        }
        if($CNT<10 && $CNT > 0){
            foreach($seat_list as $seat_num){

                
                $stmt = $conn -> prepare("INSERT INTO TP_TICKECTING(ID, RC_DATE, SEATS, DIRECTOR, RATING, LENGTH) 
                VALUES ((SELECT NVL(MAX(MID), 0) + 1 FROM TP_MOVIE), :TITLE, :OPEN_DAY, :DIRECTOR,:RATING,:LENGTH)");

                $stmt->bindParam(':MID',$MID); 
                $stmt->bindParam(':TITLE',$TITLE); 
                $stmt->bindParam(':OPEN_DAY',$OPEN_DAY);
                $stmt->bindParam(':RATING',$RATING);
                
                $MID = $_POST['MID'];
                $TITLE = $_POST['TITLE']; 
                $OPEN_DAY = $_POST['OPEN_DAY'];
                $RATING = $_POST['RATING']; 
                
                $stmt->execute();
                header("Location: serach_mv.php");
                break;
            }
        }

        header("Location: reservePage.php?MID=$MID&SDATETIME=$SDATETIME&TNAME=$TNAME&CNT=$CNT&seat_list=$seat_list");
        
        break;
    }
    ?>