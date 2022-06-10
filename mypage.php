<?php
$tns = "(DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';
$conn = new PDO($dsn, $username, $password);

session_start();
$id = $_SESSION["id"];
$firstDate = $_GET['firstDate'] ?? 'null';
$lastDate = $_GET['lastDate'] ?? 'null';
$mode =$_GET['mode'] ?? 'All';

try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0"
    crossorigin="anonymous">
    <style> a { text-decoration: none; } </style>
    <title>MY SCHEDULE</title>
</head>

<body>
<div class="bt_logout">
        <h1 class="text-center"><a href="main.php"> CNU Cinema</a></h1>
</div>
<div class="container">

    <form class="col">
        
        <div>
            <input type="date" id="firstDate" name="firstDate" value="<?=$firstDate ?>">
              -  
            <input type="date" id="lastDate" name="lastDate" value="<?=$lastDate ?>">
        </div>
        
        <div class="col-auto text-end">
            <button type="submit" class="btn btn-primary mb-3">전체 내역</button>
            <!-- <button type="submit" class="btn btn-primary mb-3">예매 내역</button>
            <button type="submit" class="btn btn-primary mb-3">취소 내역</button>
            <button type="submit" class="btn btn-primary mb-3">검색</button> -->

        </div>
    </form>
</br>

    <div class ='col'>
        <a href="mypage.php?&mode=All&firstDate=<?=$firstDate?>&lastDate=<?=$lastDate?>" class="btn btn-success">전체 내역</a>
        <a href="mypage.php?&mode=Reserved&firstDate=<?=$firstDate?>&lastDate=<?=$lastDate?>" class="btn btn-warning">예매 내역</a>
        <a href="mypage.php?&mode=Cancled&firstDate=<?=$firstDate?>&lastDate=<?=$lastDate?>" class="btn btn-danger">취소 내역</a>
        <a href="mypage.php?&mode=Watched&firstDate=<?=$firstDate?>&lastDate=<?=$lastDate?>" class="btn btn-danger">관람 내역</a>
    </div>

</br>
</br>
    <h2 class="text-center">My LIST</h2>
    <br>
    <table class="table table-bordered text-center">
        <thead> 
            <tr>
                <th>상영관</th>
                <th>영화 제목</th>
                <th>예매 날짜</th>
                <th>좌석 번호</th> 
                <th>상태</th>
                <?= $mode == 'Reserved' ? 
                '<th> 취소 </th>'
                 : ''?>
            </tr>
        </thead>

        <tbody>

            <?php
            if($firstDate=='null' |$firstDate=='' ) $firstDate='2000-01-01';
            if($lastDate=='null'|$lastDate=='') $lastDate=date("Y-m-d");
            if($mode=='All'){

                $stmt = $conn -> prepare("SELECT SC.TNAME, MV.TITLE, TO_CHAR(TI.RC_DATE,'YY-MM-DD') RCDATE, TI.SEATS, TI.STATUS
                FROM TP_TICKETING TI
                LEFT OUTER JOIN TP_SCHEDULE SC ON SC.SID = TI.SID
                INNER JOIN TP_MOVIE MV ON SC.MID = MV.MID
                WHERE 
                TI.CID=:id
                and  '20'||TO_CHAR(TI.RC_DATE-1,'YY-MM-DD') >= :firstDate
                and  '20'||TO_CHAR(TI.RC_DATE,'YY-MM-DD') <= :lastDate
                ORDER BY TI.RC_DATE
                ");
                $stmt -> execute(array($id,$firstDate,$lastDate));

            }else if($mode =='Reserved' ){

                $stmt = $conn -> prepare("SELECT SC.TNAME, MV.TITLE, TO_CHAR(TI.RC_DATE,'YY-MM-DD') RCDATE, TI.SEATS, TI.STATUS,TI.SID
                FROM TP_TICKETING TI
                LEFT OUTER JOIN TP_SCHEDULE SC ON SC.SID = TI.SID
                INNER JOIN TP_MOVIE MV ON SC.MID = MV.MID
                WHERE 
                TI.CID=:id
                and TI.STATUS ='R'
                and  '20'||TO_CHAR(TI.RC_DATE-1,'YY-MM-DD') >= :firstDate
                and  '20'||TO_CHAR(TI.RC_DATE,'YY-MM-DD') <= :lastDate
                ORDER BY TI.RC_DATE
                ");
                $stmt -> execute(array($id,$firstDate,$lastDate));
            
            }else if($mode =='Cancled' ){

                $stmt = $conn -> prepare("SELECT SC.TNAME, MV.TITLE, TO_CHAR(TI.RC_DATE,'YY-MM-DD') RCDATE, TI.SEATS, TI.STATUS
                FROM TP_TICKETING TI
                LEFT OUTER JOIN TP_SCHEDULE SC ON SC.SID = TI.SID
                INNER JOIN TP_MOVIE MV ON SC.MID = MV.MID
                WHERE 
                TI.CID=:id
                and TI.STATUS ='C'
                and  '20'||TO_CHAR(TI.RC_DATE-1,'YY-MM-DD') >= :firstDate
                and  '20'||TO_CHAR(TI.RC_DATE,'YY-MM-DD') <= :lastDate
                ORDER BY TI.RC_DATE
                ");
                $stmt -> execute(array($id,$firstDate,$lastDate));
            
            }else if($mode =='Watched' ){
                $firstDate = isset($_GET["firstDate"]) ? $firstDate : '2000-01-01';
                $lastDate = isset($_GET["lastDate"]) ? $lastDate : date("Y-m-d");

                $stmt = $conn -> prepare("SELECT SC.TNAME, MV.TITLE, TO_CHAR(TI.RC_DATE,'YY-MM-DD') RCDATE, TI.SEATS, TI.STATUS
                FROM TP_TICKETING TI
                LEFT OUTER JOIN TP_SCHEDULE SC ON SC.SID = TI.SID
                INNER JOIN TP_MOVIE MV ON SC.MID = MV.MID
                WHERE 
                TI.CID=:id
                and TI.STATUS ='W'
                and  '20'||TO_CHAR(TI.RC_DATE-1,'YY-MM-DD') >= :firstDate
                and  '20'||TO_CHAR(TI.RC_DATE,'YY-MM-DD') <= :lastDate
                ORDER BY TI.RC_DATE
                ");
                $stmt -> execute(array($id,$firstDate,$lastDate));
            
            }
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= $row['TNAME'] ?></td>
                <td><?= $row['TITLE']?></a></td>
                <td><?= $row['RCDATE'] ?></td>
                <td><?= $row['SEATS'] ?></td>
                <td><?= $row['STATUS'] ?></td>
                <?= $mode == 'Reserved' ? 
                '<td> 
                    <form action="process.php?" method="post" class="row">
                    <button type="submit" class="btn btn-success">cacle</button>
                    </form>
                 </td>'
                 : ''?>

            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

</div>
</body>
</html>
