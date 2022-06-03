<?php
session_start();
// $id = $_SESSION["id"];
// $MID = $_GET['MID'];
// $SDATETIME = $_GET['SDATETIME'];
// $TNAME = $_GET['TNAME'];
$id = '1';
$MID = '10394';
$SDATETIME = '22-05-03 11:00';
$TNAME = 'ahtohallan';


$tns = "
    (DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
    )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$conn = new PDO($dsn, "d201902679", "1003");

$stmt = $conn -> prepare("SELECT MV.MID, MV.TITLE, TH.TNAME, TO_CHAR(SC.SDATETIME,'YY-MM-DD HH:MI') AS MVTIME, TH.SEATS FROM TP_SCHEDULE SC
LEFT OUTER JOIN TP_MOVIE MV ON SC.MID = MV.MID
INNER JOIN TP_THEATER TH ON TH.TNAME = SC.TNAME
WHERE MV.MID = ?
ORDER BY TH.TNAME 
 ");
 $stmt -> execute(array($MID));
 $TITLE = '';
 $TNAME = '';
 $MID = '';
 $SDATETIME='';
 $SEATS='';
 
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
    <from action="reservPage.php" method="post">        
    <tbody>
        <tr>
            <td colspan='5'>SCREEN</td>
                </tr>
                <tr> 
                    <td>A1<input type="checkbox" value="A1"></td> 
                    <td>A2<input type="checkbox" value="A2"></td> 
                    <td>A3<input type="checkbox" value="A3"></td> 
                    <td>A4<input type="checkbox" value="A4"></td> 
                    <td>A5<input type="checkbox" value="A5"></td> 
                </tr>
                <tr> 
                    <td>B1<input type="checkbox" value="B1"></td> 
                    <td>B2<input type="checkbox" value="B2"></td> 
                    <td>B3<input type="checkbox" value="B3"></td> 
                    <td>B4<input type="checkbox" value="B4"></td> 
                    <td>B5<input type="checkbox" value="B5"></td> 
                </tr>
                <tr> 
                    <td>C1<input type="checkbox" value="C"></td> 
                    <td>C2<input type="checkbox" value="C2"></td> 
                    <td>C3<input type="checkbox" value="C3"></td> 
                    <td>C4<input type="checkbox" value="C4"></td> 
                    <td>C5<input type="checkbox" value="C5"></td> 
                </tr>
                
            </tbody>
    </table>
</div>
<div class="modal-footer">
    <form action="reservation.php?MID=<?= $MID ?>" method="post" class="row">
        <input type="hidden" name="MID" value="<?= $MID ?>">
        <button type="submit" class="btn btn-danger">예매</button>
    </form>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
</div>
<?php
    }
    ?>
</body>
</html>