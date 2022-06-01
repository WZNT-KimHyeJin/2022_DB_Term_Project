<?php
$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE)) )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679'; $password = '1003';
$MID = $_GET['mvid'];
try {
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo("에러 내용: ".$e -> getMessage());
}
$stmt = $conn -> prepare("SELECT MID, TITLE, RATING FROM TP_MOVIE WHERE MID = ? ");
$stmt -> execute(array($MID));
$TITLE = '';
$RATING = '';
$MID = '';

if ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    $MID = $row['MID'];
    $TITLE = $row['TITLE'];
    $RATING = $row['RATING'];
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
    <div class="container">
        <h2 class="display-6">상세 화면</h2>
        <table class="table table-bordered text-center">
            <tbody>
                <tr> <td>영화 ID</td> <td><?= $MID ?></td> </tr>
                <tr> <td>영화 제목</td> <td><?= $TITLE ?></td> </tr>
                <tr> <td>관람 등급</td> <td><?= $RATING ?></td> </tr>
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
                        <form action="reservation.php?mode=reserve" method="post" class="row">
                        <input type="hidden" name="mvID" value="<?= $MID ?>">
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