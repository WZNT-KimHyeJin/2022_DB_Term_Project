<?php
session_start();
$id = $_SESSION["id"];
$MID = $_GET['MID'];
$SDATETIME = $_GET['SDATETIME'];
$TNAME = $_GET['TNAME'];
echo($MID);
echo($SDATETIME);
echo($TNAME);


?>