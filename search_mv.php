<?php
$tns = "(DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';
$searchWord = $_GET['searchWord'] ?? '';
$searchDate = $_GET['searchDate'] ?? '';

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
        <h1 class="text-center"><a href="main.php"> CNU Cinema</a></h1>
</div>
<div class="container">

    <form class="row">
        <div class="col-10">
            <label for="searchWord" class="visually-hidden">Search Word</label>
            <input type="text" class="form-control" id="searchWord" name="searchWord"
            placeholder="검색어 입력" value="<?= $searchWord ?>">
        </div>
        <div>
            <p><input type="date" id="searchDate" name="searchDate" value="<?=$searchDate ?>"></p>
        </div>
        <div class="col-auto text-end">
            <button type="submit" class="btn btn-primary mb-3">검색</button>

        </div>
    </form>
    <h2 class="text-center">MOVIE SCHEDULE</h2>
    <table class="table table-bordered text-center">
        <thead> 
            <tr>
                <th>영화 ID</th>
                <th>영화 제목</th>
                <th>상영 일자</th>
                <th>연령</th> 
            </tr>
        </thead>

        <tbody>

            <?php
            // 이거 나중에 수정 해야함
            if($searchDate==''){
                $searchDate = isset($_POST["searchWord"]) ? $searchDate : '@';
                $stmt = $conn -> prepare("SELECT TP_MOVIE.MID, TITLE, TO_CHAR(SDATETIME,'YY-MM-DD'), RATING 
                FROM TP_MOVIE,TP_SCHEDULE WHERE TP_MOVIE.MID=TP_SCHEDULE.MID 
                and LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                and '20'||TO_CHAR(SDATETIME,'YY-MM-DD') <= TO_CHAR(SYSDATE,'YYYY-MM-DD')
                and not '20'||TO_CHAR(SDATETIME,'YY-MM-DD') LIKE :searchDate
                ORDER BY SDATETIME 
                ");
                $stmt -> execute(array($searchWord,$searchDate));


            }
            else if($searchWord==''){
                $searchWord = isset($_POST["searchWord"]) ? $searchWord : '@';
               
                $stmt = $conn -> prepare("SELECT TP_MOVIE.MID, TITLE, TO_CHAR(SDATETIME,'YY-MM-DD'), RATING 
                FROM TP_MOVIE,TP_SCHEDULE WHERE TP_MOVIE.MID=TP_SCHEDULE.MID 
                and not LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                and '20'||TO_CHAR(SDATETIME,'YY-MM-DD') LIKE :searchDate
                ORDER BY SDATETIME 
                ");
                $stmt -> execute(array($searchWord, $searchDate));

            }
            else{

                $stmt = $conn -> prepare("SELECT TP_MOVIE.MID, TITLE, TO_CHAR(SDATETIME,'YY-MM-DD'), RATING 
            FROM TP_MOVIE,TP_SCHEDULE WHERE TP_MOVIE.MID=TP_SCHEDULE.MID 
            and LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
            and '20'||TO_CHAR(SDATETIME,'YY-MM-DD') LIKE :searchDate
            ORDER BY SDATETIME 
            ");
            $stmt -> execute(array($searchWord,$searchDate));

            }
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= $row['MID'] ?></td>
                <td><a href="movie_info.php?mvid=<?= $row['MID'] ?>"><?= $row['TITLE']?></a></td>
                <td><?= $row['TO_CHAR(SDATETIME,\'YY-MM-DD\')'] ?></td>
                <td><?= $row['RATING'] ?></td>

            </tr>
            <?php
            }
            ?>
        </tbody>
    </table>

</div>
</body>
</html>
