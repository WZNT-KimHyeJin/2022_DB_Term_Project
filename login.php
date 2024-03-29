<?php
$tns = "
(DESCRIPTION=
     (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=KimHyejin)(PORT=1521)))
     (CONNECT_DATA= (SERVICE_NAME=XE))   
 )
";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd201902679';
$password = '1003';

$id = $_POST['id'] ?? '';
$pw = $_POST['pw'] ?? '';
$ADMIN_ID = '9999';
$ADMIN_PW = '9999';

try {    
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {    
    echo("에러 내용: ".$e -> getMessage());
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
    <link rel=”stylesheet” href=”http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css“>
    <title>CNU Cinema</title>
</head>
<body>
    <div class="jumbotron">
        <h1 class="text-center">CNU Cinema</h1>
    </div>

    <div class="container">
        <?php
        
$stmt = $conn -> prepare("SELECT CID, PASSWORD, NAME FROM TP_CUSTOMER WHERE CID = :id");
$stmt -> execute(array((int)$id));

while ($row = $stmt -> fetch(PDO::FETCH_ASSOC)) {
    if($row['PASSWORD'] != $pw){
        ?>
        <script>alert('password가 일치하지 않습니다.');</script>
        <?php
    }else{
        // 로그인 성공 시 id를 session에 저장하고 main페이지로 이동
        if($id==$ADMIN_ID){
            ?>
            <script>alert('관리자로 접속하였습니다.'); location.href='admin.php'</script>
            <?php
        }
        session_start();
        $_SESSION['id'] = $id;
        ?>
        <script>alert('반갑습니다. <?=$row['NAME']?>님'); location.href='main.php'</script>
        <?php
    }
}
?>
        <!--form을 통해 입력받은 id와 pw를 post방식으로 현재 php에 전송 -->
        <form method="post">
            <div class="col-12">
                <label for="id" class="col-4 text-center">ID</label>
                <input type="text" id="id" class="col-6" name="id" maxlength="5" value="<?=$id?>">
            </div>
            <div class="col-12">
                <label for="pw" class="col-4 text-center">PASSWORD</label>
                <input type="password" class="col-6" id="pw" name="pw" maxlength="20" value="<?=$pw?>">
            </div>
            <div class="login">
                <button type="submit" class="btn btn-light">login</button>
            </div>
        </form>
    </div>
</body>
</html>