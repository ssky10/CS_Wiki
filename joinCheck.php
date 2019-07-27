<?php
include 'accessDB.php'; //DB에 접속을 위해서 필요

$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴

header("Content-Type: application/json"); //html헤더의 컨텐트 텍스트를 json으로 설정

$type = $_GET['type']; //검사 타입을 저장(id 혹은 email)

if($type == "id") {
  $sql = "SELECT ID,login_id FROM cs_wiki_users WHERE login_id='".$_GET['data']."'"; //id검색쿼리
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)==1){ //id가 존재할경우
    echo(json_encode(array("code" => 1, "message" => "이미 존재하는 아이디 입니다."))); //json형태로 값과 메시지 전송
  }else{
    echo(json_encode(array("code" => 0, "message" => "사용가능한 아이디 입니다."))); //json형태로 값과 메시지 전송
  }
} else if($type == "mail") {
  $sql = "SELECT ID,email FROM cs_wiki_users WHERE email='".$_GET['data']."'"; //email검색쿼리
  $result = mysqli_query($conn, $sql);
  if(mysqli_num_rows($result)==1){ //email이 존재할경우
    echo(json_encode(array("code" => 1, "message" => "이미 가입된 메일 입니다."))); //json형태로 값과 메시지 전송
  }else{
    echo(json_encode(array("code" => 0, "message" => "사용가능한 이메일 입니다."))); //json형태로 값과 메시지 전송
  }
}
?>
