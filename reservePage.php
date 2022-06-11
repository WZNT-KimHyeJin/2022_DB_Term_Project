<?php
session_start();
$id = $_SESSION["id"];
$MID = $_GET['MID'];
$SCID = $_GET['SCID'];
$CNT = $_GET['CNT'] ?? 0;
$seat_list =$_GET['seat_list'] ?? '';

$tns = "
    (DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
    )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$conn = new PDO($dsn, "d201902679", "1003");

$stmt = $conn -> prepare("
SELECT MV.MID, MV.TITLE, TH.TNAME, TO_CHAR(SC.SDATETIME,'YY-MM-DD HH:MI') AS MVTIME, TH.SEATS, SC.SID
FROM TP_SCHEDULE SC
LEFT OUTER JOIN TP_MOVIE MV ON SC.MID = MV.MID
INNER JOIN TP_THEATER TH ON TH.TNAME = SC.TNAME
WHERE SC.SID = :SCID
ORDER BY TH.TNAME 
 ");
 $stmt->bindParam(':SCID',$SCID); 
 $stmt -> execute(array($SCID));
 $TITLE = '';
 $TNAME = '';
 $MID = '';
 $SDATETIME='';
 $SEATS='';
 $SCID =''; 
 
 if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
     $MID = $row['MID'];
     $TITLE = $row['TITLE'];
     $TNAME = $row['TNAME'];
     $SDATETIME = $row['MVTIME'];
     $SEATS = $row['SEATS'];
     $SCID = $row['SID']; 


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <style> a {text-decoration: none;} </style>
    <title>RESERVATION</title>
</head>
<body>

<div class="bt_logout">
        <h1 class="text-center"><a href="main.php"> CNU Cinema</a></h1>
</div>

<div class="container">
    <div>
        <h1 class="display-8">영화 예매 : <?= $TITLE ?></h1>
        <h1 class="display-8">관람일자 : <?= $SDATETIME ?></h1>
        
    </div>

    <table class="table table-bordered text-center">
    <tbody>
        <tr>
            <td colspan='5'>SCREEN</td>
        </tr>
        <form action="process.php??MID=<?= $MID ?>&mode=reserve" method="post">
                <tr> 
                    <td>A1<input type="checkbox" name = "seat[]" value=1></td> 
                    <td>A2<input type="checkbox" name = "seat[]"value=2></td> 
                    <td>A3<input type="checkbox" name = "seat[]" value=3></td> 
                    <td>A4<input type="checkbox" name = "seat[]" value=4></td> 
                    <td>A5<input type="checkbox" name = "seat[]" value=5></td> 
                </tr>
                <tr> 
                    <td>B1<input type="checkbox" name = "seat[]" value=6></td> 
                    <td>B2<input type="checkbox" name = "seat[]" value=7></td> 
                    <td>B3<input type="checkbox" name = "seat[]" value=8></td> 
                    <td>B4<input type="checkbox" name = "seat[]" value=9></td> 
                    <td>B5<input type="checkbox" name = "seat[]" value=10></td> 
                </tr>
                <tr> 
                    <td>C1<input type="checkbox" name = "seat[]" value=11></td> 
                    <td>C2<input type="checkbox" name = "seat[]" value=12></td> 
                    <td>C3<input type="checkbox" name = "seat[]" value=13></td> 
                    <td>C4<input type="checkbox" name = "seat[]" value=14></td> 
                    <td>C5<input type="checkbox" name = "seat[]" value=15></td> 
                </tr>
                
            </tbody>
    </table>
</div>
<?php
    if($CNT == 0){
        ?>
            <script>alert('좌석을 선택 해 주세요');</script>
        <?php
    }else if($CNT>10){
        ?>
            <script>alert('선택 가능 좌석은 최대 10석 입니다. (<?=$CNT?>/10)');</script>
        <?php
    }
    
        ?>


<div class="modal-footer" >
        <input type="hidden" name="MID" value="<?= $MID ?>">
        <input type="hidden" name="SDATETIME" value="<?= $SDATETIME ?>">
        <input type="hidden" name="TNAME" value="<?= $TNAME ?>">
        <input type="hidden" name="SCID" value="<?= $SCID ?>">

        <button type="submit" class="btn btn-danger">예매</button>
    </form>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
    </div>
<?php
    }
    ?>

</body>
</html>