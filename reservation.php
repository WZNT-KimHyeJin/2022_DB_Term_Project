<?php
$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';

session_start();
$id = $_SESSION["id"];
$MID = $_GET['MID'] ?? ''; 
$CNT =0;

try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

$stmt = $conn -> prepare("SELECT MV.MID, MV.TITLE, IQ.TNAME, 
TO_CHAR(IQ.SDATETIME,'YY-MM-DD HH:MI') AS MVTIME, IQ.SID, IQ.ES AS EXTRASEAT
FROM (SELECT TH.TNAME,SC.SID, SC.MID,SC.SDATETIME,SUM(TH.SEATS+(NVL(SC.EXT_SEATS,0)*-1)) ES
    FROM  TP_SCHEDULE SC
    INNER JOIN TP_THEATER TH ON TH.TNAME = SC.TNAME
    GROUP BY (SC.SID, SC.MID,TH.SEATS,SC.EXT_SEATS,SC.SDATETIME,TH.TNAME)
    HAVING SC.MID = :MID) IQ
LEFT OUTER JOIN TP_MOVIE MV ON IQ.MID = MV.MID
 ");
$stmt -> execute(array($MID));
$TITLE = '';
$TNAME = '';
$SDATETIME='';
$SCID='';
$EXTRASEAT='';


?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"> <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <style> a {text-decoration: none;} </style>
    <title>MOVIE INFO</title>
</head>
<body>
<div class="bt_logout">
        <h1 class="text-center"><a href="main.php"> CNU Cinema</a></h1>
</div>
    <div class="container">
        <h4 class="display-6">상영 리스트 </h4>
        <table class="table table-bordered text-center">
            
            <tbody>
                <tr>
                    <td>상영</td>
                    <td>영화 제목</td>
                    <td>상영 시간</td>
                    <td>잔여 좌석</td>
                    </tr>
            <?php
                while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
                    ?>
                <tr> 
                    <td><?= $row['TNAME'] ?></td> 
                    <td><?= $row['TITLE'] ?></td> 
                    <td><?= $row['MVTIME'] ?></td> 
                    <td><?= $row['EXTRASEAT'] ?></td> 
                    <td>
                        <form action="reservePage.php?MID=<?= $MID ?>&SCID=<?= $row['SID']?>" method="post" class="row">
                        <button type="submit" class="btn btn-success">예매</button>
                        </form>
                    <td>
                </tr>
            <?php
                }
                ?>

            </tbody>
        </table>
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-
gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>