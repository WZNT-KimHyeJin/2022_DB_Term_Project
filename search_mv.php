<?php
$tns = "(DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';

$searchWord = $_GET['searchWord'] ?? '';
$searchDate = $_GET['searchDate'] ?? '';
$mode =$_GET['mode'] ?? 'search';

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
</br>

    <div class ='col'>
        <a href="search_mv.php?&mode=on_show" class="btn btn-danger">상영중</a>
        <a href="search_mv.php?&mode=be_shown" class="btn btn-warning">상영예정</a>
    </div>

</br>
</br>
    <h2 class="text-center">MOVIE SCHEDULE</h2>
    <table class="table table-bordered text-center">
        <thead> 
            <tr>
                <th>영화 ID</th>
                <th>영화 제목</th>
                <th>개봉 날짜</th>
                <th>연령</th> 
            </tr>
        </thead>

        <tbody>

            <?php
            if($mode=='on_show'){
                $searchDate = isset($_POST["searchDate"]) ? $searchDate : '@';
                $searchWord = isset($_POST["searchWord"]) ? $searchWord : '@';

                $stmt = $conn -> prepare("SELECT MID, TITLE, TO_CHAR(OPEN_DAY,'YY-MM-DD'), RATING 
                FROM TP_MOVIE WHERE not LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                and not '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') LIKE :searchDate
                and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') >= TO_CHAR(SYSDATE-10,'YYYY-MM-DD')
                and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') <= TO_CHAR(SYSDATE,'YYYY-MM-DD')
                ORDER BY OPEN_DAY 
                ");
                $stmt -> execute(array($searchWord,$searchDate));
            }else if($mode =='be_shown' ){
                $searchDate = isset($_POST["searchDate"]) ? $searchDate : '@';
                $searchWord = isset($_POST["searchWord"]) ? $searchWord : '@';

                $stmt = $conn -> prepare("SELECT MID, TITLE, TO_CHAR(OPEN_DAY,'YY-MM-DD'), RATING 
                FROM TP_MOVIE WHERE not LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                and not '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') LIKE :searchDate
                and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') > TO_CHAR(SYSDATE,'YYYY-MM-DD')
                ORDER BY OPEN_DAY 
                ");
                $stmt -> execute(array($searchWord,$searchDate));
            }
            else if($mode == 'search'){

                if($searchDate=='' && $searchWord==''){
                    $searchDate = isset($_POST["searchDate"]) ? $searchDate : '@';
                    $searchWord = isset($_POST["searchWord"]) ? $searchWord : '@';

                    $stmt = $conn -> prepare("SELECT MID, TITLE, TO_CHAR(OPEN_DAY,'YY-MM-DD'), RATING 
                    FROM TP_MOVIE WHERE not LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                    and not '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') LIKE :searchDate
                    and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') >= TO_CHAR(SYSDATE-10,'YYYY-MM-DD')
                    ORDER BY OPEN_DAY 
                    ");
                    $stmt -> execute(array($searchWord,$searchDate));

                }
                else if($searchDate==''){
                    $searchDate = isset($_POST["searchDate"]) ? $searchDate : '@';


                    $stmt = $conn -> prepare("SELECT MID, TITLE, TO_CHAR(OPEN_DAY,'YY-MM-DD'), RATING 
                    FROM TP_MOVIE WHERE LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                    and not '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') LIKE :searchDate
                    and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') >= TO_CHAR(SYSDATE-10,'YYYY-MM-DD')
                    ORDER BY OPEN_DAY 
                    ");
                    $stmt -> execute(array($searchWord,$searchDate));
    
                }
                else if($searchWord==''){
                    $searchWord = isset($_POST["searchWord"]) ? $searchWord : '@';

                    $stmt = $conn -> prepare("SELECT MID, TITLE, TO_CHAR(OPEN_DAY,'YY-MM-DD'), RATING 
                    FROM TP_MOVIE WHERE not LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                    and '20'||TO_CHAR(OPEN_DAY+10,'YY-MM-DD') >=:searchDate
                    and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') <= :searchDate
                    ORDER BY OPEN_DAY 
                    ");
                    $stmt -> execute(array($searchWord,$searchDate));
    
                }
                else{
    
                    $stmt = $conn -> prepare("SELECT TP_MOVIE.MID, TITLE, TO_CHAR(OPEN_DAY,'YY-MM-DD'), RATING 
                    FROM TP_MOVIE WHERE
                    LOWER(TITLE) LIKE '%'|| :searchWord || '%' 
                    and '20'||TO_CHAR(OPEN_DAY+10,'YY-MM-DD') >=:searchDate
                    and '20'||TO_CHAR(OPEN_DAY,'YY-MM-DD') <= :searchDate
                    ORDER BY OPEN_DAY 
                    ");
                    $stmt -> execute(array($searchWord,$searchDate));
                }
            }
            while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
            ?>
            <tr>
                <td><?= $row['MID'] ?></td>
                <td><a href="movie_info.php?mvid=<?= $row['MID']?>&mode=<?=$mode?>"><?= $row['TITLE']?></a></td>
                <td><?= $row['TO_CHAR(OPEN_DAY,\'YY-MM-DD\')'] ?></td>
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
