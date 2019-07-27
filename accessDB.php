<?php
function getDB(){
  $hostname = "localhost"; //데이터베이스 주소
  $user = "root"; //사용자명
  $password = "qlalfqjsgh"; //암호

  $conn = mysqli_connect($hostname,$user,$password); //데이터베이스 접속
  mysqli_select_db($conn,"cs_wiki"); //cs_wiki테이블 접근
  mysqli_query($conn, 'set names utf8'); //문자열포맷 utf-8로 변경
  return $conn; //데이터베이스 객체 반환
}
?>
