<?php

session_start();
$id = $_SESSION["id"];
$tns = "
    (DESCRIPTION=
    (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
    (CONNECT_DATA= (SERVICE_NAME=XE))
    )";
$dsn = "oci:dbname=".$tns.";charset=utf8";
try {
    $conn = new PDO($dsn, "d201902679", "1003");
} catch (PDOException $e) {
    echo("에러 내용: ".$e->getMessage());
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> 
    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <title>CNU Cinema</title>
</head>

<body>
    <div class="bt_logout">
        <h1 class="text-center"><a href="main.php"> CNU Cinema</a></h1>
        <a class="btn btn-sm btn-outline-dark float-right" href="login.php">LOGOUT</a>
    </div>

    <div class="info">
        <?php
                $stmt = $conn -> prepare("SELECT NAME, EMAIL
                                            FROM TP_CUSTOMER
                                            WHERE CID = :id");
                $stmt -> execute(array($id));
                $row = $stmt -> fetch(PDO::FETCH_ASSOC);
        ?>
        
        <span><?=$row["NAME"]?> 님</span>
        <span>ID : <?=$id?></span>
        <span>email : <?=$row["EMAIL"]?></span>

    </div>

    <!--크게 3가지의 경우로 나누어 각각 페이지 연결-->
    <div class="container">
        <div class="row">
            <div class="btn btn-outline-dark btn-lg mvp">
                <a href="search_mv.php">영화 검색</a>
            </div>
            <div class="text">
                - 상영중 혹은 상영 예정중인 영화 검색 가능<br>
                - 영화 예매 가능
            </div>
        </div>

        <div class="row">
            <div class="btn btn-outline-dark btn-lg mvp">
                <a href="mypage.php">마이 페이지</a>
            </div>
            <div class="text">
                - 예매 영화 확인 및 취소 가능<br>
                - 과거 예매한 영화 내역 확인
            </div>
        </div>


    </div>
</body>

</html>