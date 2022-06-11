<?php
$tns = "(DESCRIPTION=
(ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
(CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';
$conn = new PDO($dsn, $username, $password);

session_start();
$id = $_SESSION["id"];
$MID = $_GET['mvid'];
$mode = $_GET['mode'];

try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}




$stmt = $conn -> prepare("SELECT MV.MID, MV. TITLE, RATING,OPEN_DAY,DIRECTOR,LENGTH,CNT,SUMM,IQ.CNT_NUM
FROM TP_MOVIE MV
LEFT JOIN (SELECT SC.MID, COUNT(STATUS)AS CNT_NUM
FROM TP_SCHEDULE SC
INNER JOIN TP_TICKETING TI ON TI.SID = SC.SID
GROUP BY SC.MID, STATUS
HAVING TI.STATUS ='R')IQ
ON MV.MID = IQ.MID
where MV.MID = ? ");

$stmt -> execute(array($MID));
$TITLE = '';
$RATING = '';
$MID = '';
$OPEN_DAY='';
$DIRECTOR='';
$LENGTH='';
$CNT='';
$RESERVE_CNT='';
$SUMM='';

if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    $MID = $row['MID'];
    $TITLE = $row['TITLE'];
    $RATING = $row['RATING'];
    $OPEN_DAY = $row['OPEN_DAY'];
    $DIRECTOR = $row['DIRECTOR'];
    $LENGTH = $row['LENGTH'];
    $CNT = $row['CNT'];
    $RESERVE_CNT = $row['CNT_NUM']?? '0';
    $SUMM = $row['SUMM'];
    
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
        <h2 class="display-6">상세 화면</h2>
        <table class="table table-bordered text-center">
            <tbody>
                <tr> 
                    <th>영화 ID</th> 
                    <th>영화 제목</th> 
                    <th>관람 연령</th> 
                    <th>상영시간</th> 
                </tr>
                <tr> 
                    <td><?= $MID ?></td> 
                    <td><?= $TITLE ?></td> 
                    <td><?= $RATING ?></td> 
                    <td><?= $LENGTH ?>분</td> 
                </tr>
                <tr> 
                    <th>개봉날짜</th> 
                    <th>감독</th> 
                    <th>현예매수</th> 
                    <?= $mode == 'be_shown' ? '': '<th>누적 관객수</th>' ?>
                </tr>
                <tr> 
                    <td><?= $OPEN_DAY ?></td> 
                    <td><?= $DIRECTOR ?></td> 
                    <td><?= $RESERVE_CNT ?></td> 
                    <?= $mode == 'be_shown' ? '':  '<td>'.$CNT.'</td>' ?>
                </tr>
                <tr> 
                    <th colspan="4">줄거리</th> 
                </tr>
                <tr>
                    <td colspan="4"><?= $SUMM ?></td> 
                </tr>
               


            </tbody>
        </table>
        <?php
        }
        ?>
        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="search_mv.php" class="btn btn-success">목록</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">예매</button>

        </div>
    </div>
    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="deleteConfirmModal" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmModalLabel"><?= $TITLE ?> / <?= $RATING ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"> 영화를 예매하시겠습니까? </div>
                    <div class="modal-footer">
                        <form action="reservation.php?MID=<?= $MID ?>" method="post" class="row">
                            <input type="hidden" name="MID" value="<?= $MID ?>">
                            <button type="submit" class="btn btn-danger">예매</button>
                        </form>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button>
                    </div>
            </div>
        </div>
    </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-
gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</html>