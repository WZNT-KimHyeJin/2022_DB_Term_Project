<?php
$tns = "(DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';

$mode =$_GET['mode'] ?? 'search';
$ADMIN_ID='9999';

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
    <title>MOVIE SCHEDULE</title>
</head>

<body>
<div class="bt_logout">
        <h1 class="text-center"><a href="admin.php"> CNU Cinema ADMIN</a></h1>
</div>
<div class="container">
<br>
    <div class ='col'>
        <a href="admin.php?&mode=join" class="btn btn-danger">JOIN</a>
        <a href="admin.php?&mode=rollup" class="btn btn-warning">GROUP_ROLLUP</a>
        <a href="admin.php?&mode=window" class="btn btn-success">WINDOW</a>
    </div>

</br>
</br>
    <h2 class="text-center">RESULT</h2>
    <table class="table table-bordered text-center">
        
        <tbody>

            <?php
            if($mode=='join'){
                ?>
                <tr>
                    <td colspan ="5">
                    tp_ticketing, tp_customer, tp_schedule 테이블을 조인하여 사용자 ID와 사용자가 예약한영화 id, 
                    <br>
                    해당 영화의 상영 날짜, 영화관 이름, 예매한 좌석을 조회하는 SQL문을 작성하고 그
                    결과를 출력하시오. 
                    <br>
                    사용자 이름 순으로 정렬한다.
                    </td>
                </tr>
                <tr>
                    <th>사용자 이름</th>
                    <th>영화 ID</th>
                    <th>상영 날짜</th>
                    <th>상영관</th> 
                    <th>좌석번호</th> 
                </tr>
                <?php

                $stmt = $conn -> prepare("SELECT CT.NAME, SH.MID, TO_CHAR(SH.SDATETIME,'YY-MM-DD HH:MI') MVTIME, SH.TNAME, TK.SEATS
                FROM TP_TICKETING TK, TP_CUSTOMER CT, TP_SCHEDULE SH
                WHERE TK.SID = SH.SID
                AND TK.CID = CT.CID
                ORDER BY CT.NAME
                ");
                $stmt -> execute();

                while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?= $row['NAME'] ?></td>
                    <td><?= $row['MID']?></td>
                    <td><?= $row['MVTIME'] ?></td>
                    <td><?= $row['TNAME'] ?></td>
                    <td><?= $row['SEATS'] ?></td>

                </tr>
            <?php
                }
            }else if($mode =='rollup' ){
                ?>
                <tr>
                    <td colspan ="5">
                    상영관 이름과 사용자 이름을 기준으로 예매한 영화의 개수를<br>
                    <strong>ROLLUP</strong>을 사용하여 확인하여라.
                    </td>
                </tr>
                <tr>
                    <th>상영관</th>
                    <th>사용자 이름</th>
                    <th>개수</th>
                </tr>
                <?php

                $stmt = $conn -> prepare("SELECT nvl(sh.tname,'ALL TEATER') T_NAME ,nvl(ct.name,'ALL USERS') C_NAME, count(*) T_CNT
                from tp_ticketing tk,tp_customer ct, tp_schedule sh
                where tk.cid = ct.cid
                and tk.sid = sh.sid
                group by rollup (sh.tname, ct.name)
                ");
                $stmt -> execute();

                while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?= $row['T_NAME'] ?></td>
                    <td><?= $row['C_NAME']?></td>
                    <td><?= $row['T_CNT'] ?></td>

                </tr>
            <?php
                }

            }
            else if($mode == 'window'){
                ?>
                <tr>
                    <td colspan ="5">
                    tp_ticketing, tp_customer, tp_schedule, tp_movie 테이블을 사용하여 
                    <br>사용자가 예매한 영화의 영화관 이름, 영화 제목, 사용자 이름, 사용자 이름 별로 구분하여 
                    <br>사용자가 예매한 <strong>가장 최근 상영 날짜</strong>를 반환하여라.
                    </td>
                </tr>
                <tr>
                    <th>상영관</th>
                    <th>영화 제목</th>
                    <th>사용자 이름</th>
                    <th>예매한 최근 상영 날짜</th>
                </tr>
                <?php

                $stmt = $conn -> prepare("select IQ.tname T_NAME, mv.title M_TITLE, ct.name USER_NAME, 
                to_char( (max(IQ.sdatetime) over (partition by ct.name)),'YY-MM-DD HH:MI') RECENT
                from
                (select tname, sh.sdatetime, mid, cid
                from tp_schedule sh
                left join tp_ticketing tk on tk.sid = sh.sid
                group by(sh.tname,sh.sdatetime,mid,cid) ) IQ , 
                tp_customer ct, tp_movie mv
                where IQ.cid = ct.cid
                and mv.mid = IQ.mid
                ");
                $stmt -> execute();

                while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
                <tr>
                    <td><?= $row['T_NAME'] ?></td>
                    <td><?= $row['M_TITLE']?></td>
                    <td><?= $row['USER_NAME'] ?></td>
                    <td><?= $row['RECENT'] ?></td>

                </tr>
            <?php
                }

               
            }
            ?>
            
            
        </tbody>
    </table>

</div>
</body>
</html>
