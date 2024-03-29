<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "./php_mailer/src/PHPMailer.php";
require "./php_mailer/src/SMTP.php";
require "./php_mailer/src/Exception.php";

$mail = new PHPMailer(true);

$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST= (ADDRESS=(PROTOCOL=TCP)(HOST=LOCALHOST)(PORT=1521)))
        (CONNECT_DATA= (SERVICE_NAME=XE))
    )
";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'c##madang';
$password = 'madang';
try {    
    $conn = new PDO($dsn, $username, $password);
} catch (PDOException $e) {    
    echo("에러 내용: ".$e -> getMessage());
}

session_start();
$id = $_SESSION["id"];
$cno = $_SESSION["CNO"];
$isbn = $_GET["isbn"];

$stmt = $conn->prepare('SELECT TITLE, EMAIL, NAME FROM RESINFO WHERE CNO = :cno AND ISBN = :isbn');
$stmt->bindParam(':cno', $cno);
$stmt->bindParam(':isbn', $isbn);

$stmt->execute();
$row = $stmt -> fetch(PDO::FETCH_ASSOC);
$email = $row["EMAIL"] ?? '';
$name = $row["NAME"] ?? 'CNU Cinema VIP';
$title = $row["TITLE"];

if($email == null){
    echo "email 없음";
}

try {

    // 서버세팅    
    $mail -> SMTPDebug = 0;    // 디버깅 설정
    $mail -> isSMTP();               // SMTP 사용 설정

    $mail -> Host = "smtp.naver.com";                      // email 보낼때 사용할 서버를 지정
    $mail -> SMTPAuth = true;                                // SMTP 인증을 사용함
    $mail -> Username = "pcrs5278@naver.com";  // 메일 계정
    $mail -> Password = "";                   // 메일 비밀번호
    $mail -> SMTPSecure = "ssl";                             // SSL을 사용함
    $mail -> Port = 465;                                        // email 보낼때 사용할 포트를 지정
    $mail -> CharSet = "utf-8";                                // 문자셋 인코딩

    // 보내는 메일
    $mail -> setFrom("pcrs5278@naver.com", "CNU Cinema");

    // 받는 메일
    $mail -> addAddress($row["EMAIL"], $row["NAME"]);

    // 메일 내용
    $mail -> isHTML(true);                                                         // HTML 태그 사용 여부
    $mail -> Subject = "[CNU CINEMA]";                  // 메일 제목
    $mail -> Body = "안녕하세요 $name 님. CNU Cinema입니다.<br />
                    고객님께서 영화 [$title] 을 예매하여 안내메일 보내드립니다.<br />
                    즐거운 관람 되시길 바랍니다<br />
                    감사합니다.";    // 메일 내용
    
    // 메일 전송
    $mail -> send();
    
    if($_SERVER['HTTP_REFERER'] == "http://localhost/DB_OnlineLibrary/process.php?isbn=$isbn&mode=return"){
        echo "<script>location.href='rentallist.php';</script>";
    }else{
        echo "<script>location.href='autoreturn.php';</script>";   
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error : ", $mail -> ErrorInfo;
}
?>