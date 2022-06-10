<?php
$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';

try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}

session_start();
$id = $_SESSION["id"];
$MID = $_GET['MID']; 
echo($MID);
$CNT =0;

$stmt = $conn -> prepare("SELECT MV.MID, MV.TITLE, TH.TNAME, TO_CHAR(SC.SDATETIME,'YY-MM-DD HH:MI') AS MVTIME, TH.SEATS FROM TP_SCHEDULE SC
LEFT OUTER JOIN TP_MOVIE MV ON SC.MID = MV.MID
INNER JOIN TP_THEATER TH ON TH.TNAME = SC.TNAME
WHERE MV.MID = ?
and '20'||TO_CHAR(SDATETIME+1,'YY-MM-DD HH:MI')>=TO_CHAR(SYSDATE,'YYYY-MM-DD HH:MI')
ORDER BY TH.TNAME 
 ");


if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    $MID = $row['MID'];
    $TITLE = $row['TITLE'];
    $TNAME = $row['TNAME'];
    $SDATETIME = $row['MVTIME'];
    $SEATS = $row['SEATS'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0" crossorigin="anonymous">
    <style> a {text-decoration: none;} </style>
    <title>MOVIE LIST</title>
</head>

<body>
<?php
}
?>
<div class="bt_logout">
        <h1 class="text-center"><a href="main.php"> CNU Cinema</a></h1>
</div>


    <h2>이게 왜 안돼?</h2>
</body>
</html>
