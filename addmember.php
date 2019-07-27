<?php
include 'accessDB.php';

$conn = getDB(); //DB객체 받아옴

/*사용자 정보 추가(비밀번호는 php5.5 이후 지원되는 password_hash함수 이용 단방향 암호화 저장)*/
$sql = "INSERT INTO cs_wiki_users (login_id,password,nickname,email,registered,status) VALUES('".$_POST['id']."', '".password_hash($_POST['password'],PASSWORD_DEFAULT)."', '".$_POST['id']."', '".$_POST['email']."', now(), 2)";
$result = mysqli_query($conn,$sql); //쿼리문 실행

 /*회원가입 완료 후 script*/
echo '<script>';
if($result == 1){
  echo 'alert("회원가입이 정상적으로 완료되었습니다.");'; //정상적인 회원가입 완료시
}else{
  echo 'alert("회원가입에 실패하였습니다.");'; //정상적인 회원가입 실패시
}
echo 'location.href = "index.php";'; //회원가입 완료 후 메인페이지로 이동
echo '</script>';
?>
