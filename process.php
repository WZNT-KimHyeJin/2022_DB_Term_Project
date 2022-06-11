<?php

session_start();
$id = $_SESSION["id"];


$tns = "
    (DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
    )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$conn = new PDO($dsn, "d201902679", "1003");

switch($_GET['mode']){
    
    case 'reserve' :
        $SDATETIME = '20'.$_POST['SDATETIME']; 
        $RSTATUS='R';
        $CID=$id;
        $SCID = $_POST['SCID']; 
        $seat_list=$_POST["seat"];
        
        $CNT=0;
        
        foreach($seat_list as $seat_num){
            $CNT++;
        }
        if($CNT<10 && $CNT > 0){
            foreach($seat_list as $SEAT_NUM){

                $stmt = $conn -> prepare("INSERT INTO TP_TICKETING(RC_DATE, SEATS, STATUS, CID, SID) 
                VALUES ( SYSDATE, :SEAT_NUM, :RSTATUS,:CID,:SCID)");

                $stmt->bindParam(':SEAT_NUM',$SEAT_NUM); 
                $stmt->bindParam(':RSTATUS',$RSTATUS); 
                $stmt->bindParam(':CID',$CID); 
                $stmt->bindParam(':SCID',$SCID); 

                $stmt->execute();
                
            }
            // header("Location: mailer.php");
            ?>
        
            <script>alert('예매가 완료되었습니다.');</script>
            <?php
            header("Location: search_mv.php");
            break;
        }
        break;
    case 'cancle':
        $SEAT_NUM = $_GET['seat_num'];
        $SCH_ID = $_GET['sche_id'];

        $stmt = $conn->prepare("UPDATE TP_TICKETING SET STATUS ='C'
        WHERE CID = :id
        and SEATS = :seatnum
        and SID = :sche_id");

        $stmt->bindParam(':seatnum',$SEAT_NUM); 
        $stmt->bindParam(':sche_id',$SCH_ID);
        $stmt->bindParam(':id',$id);


        $stmt->execute();
        
        header("Location: mypage.php?&mode=Cancled");

        
        break;
    case 'Watched':
        
        
        $stmt = $conn->prepare("UPDATE ( SELECT TI.STATUS AS ST
            FROM TP_SCHEDULE SC, TP_TICKETING TI
            WHERE SC.SID = TI.SID
            and SC.SDATETIME < SYSDATE 
            And TI.CID=:id
            and TI.STATUS='R'
            )
            SET ST='W'
        ");

        $stmt->bindParam(':id',$id);
       
        $stmt->execute(); 

        header("Location: mypage.php?&mode=Watched");
        break;

    
        
}
?>