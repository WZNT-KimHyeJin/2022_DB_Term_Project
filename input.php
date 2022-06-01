<?php
$bookId = $_GET['MID'] ?? ''; 
$mode = $_GET['mode'] ?? '';
$TITLE = '';
$RATING = '';
$MID = '';
$OPEN_DAY='';
$DIRECTOR='';
$LENGTH='';

if ($mode == 'modify') {
    $tns = "(DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE)) )";
    $url = "oci:dbname=" . $tns . ";charset=utf8";
    $username = 'd201902679'; $password = '1003';
    $conn = new PDO($url, $username, $password);
    
    $stmt = $conn -> prepare("SELECT MID, TITLE, RATING,OPEN_DAY,DIRECTOR,LENGTH FROM TP_MOVIE WHERE MID = ? ");
    $stmt -> execute(array($MID));
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $MID = $row['MID'];
        $TITLE = $row['TITLE'];
        $RATING = $row['RATING'];
        $OPEN_DAY = $row['OPEN_DAY'];
        $DIRECTOR = $row['DIRECTOR'];
        $LENGTH = $row['LENGTH'];
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-wEmeIV1mKuiNpC+IOBjI7aAzPcEZeedi5yW5f2yOq55WWLwNGmvvx4Um1vskeMj0“ crossorigin="anonymous">
    <style> a { text-decoration: none; } </style>
    <title>MOVIE_EDIT</title>
</head>
<body>
    <div class="container mb-3">
        <h2 class="display-4"><?= $mode == 'insert' ? '영화 등록' : '영화 정보 수정'?></h2>
        <form class="row g-3 needs-validation" method="post" action="process.php?mode=<?= $mode ?>" novalidate>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" maxlength="5" id="MID" name="MID" placeholder="영화 ID" value="<?= $MID ?>" required>
                <label for="MID" class="form-label">영화 ID</label> 
                <div class="invalid-tooltip">영화 ID를 입력하세요.</div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" maxlength="20" id="TITLE" name="TITLE" placeholder="영화 제목" value="<?= $TITLE ?>" required>
                <label for="TITLE" class="form-label">제목</label> 
                <div class="invalid-tooltip">제목을 입력하세요.</div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" maxlength="3" id="RATING" name="RATING" placeholder="관람 등급" value="<?= $RATING ?>" required>
                <label for="RATING" class="form-label">관람 등급</label> 
                <div class="invalid-tooltip">관람 등급을 입력하세요.</div>
            </div>
            <div class="form-floating mb-3">
                <input type="number" class="form-control" id="LENGTH" name="LENGTH" placeholder="상영 시간" value="<?= $LENGTH ?>" required>
                <label for="LENGTH" class="form-label">상영 시간</label>
                 <div class="invalid-tooltip">상영시간을 입력하세요</div>
            </div>
            <div class="form-floating mb-3">
                <input type="date" class="form-control" id="OPEN_DAY" name="OPEN_DAY" placeholder="개봉 날짜" value="<?= $OPEN_DAY ?>" required>
                <label for="OPEN_DAY">개봉 날짜(YY-MM-DD)</label> 
                <div class="invalid-tooltip">개봉 날짜를 입력하세요.</div>
            </div>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="DIRECTOR" name="DIRECTOR" placeholder="감독" value="<?= $DIRECTOR ?>" required>
                <label for="DIRECTOR">감독</label> 
                <div class="invalid-tooltip">감독 이름을 입력하세요.</div>
            </div>
            <div class="mb-3">
                <input type="hidden" name="bookId" value="<?= $bookId ?>">
                <button class="btn btn-primary" type="submit"><?= $mode == 'insert' ? '등록' : '수정'?></button>
            </div>
        </form>
    </div>
</body>
</html>
