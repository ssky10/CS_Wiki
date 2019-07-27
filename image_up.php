<?
include 'accessDB.php'; //DB에 접속을 위해서 필요

$conn = getDB(); //DB객체 받아옴

extract($_REQUEST); //_REQUEST 배열에 있던 값들을 각 배열 인덱스 이름으로 바로 접근하도록 해준다.

$filename = $_FILES[image][tmp_name]; //웹서버에 임시로 저장된 파일의 이름 반환
$extnsion = $_FILES[image][name];
$tmp_arr = explode(".",$extnsion);
$extnsion = strtolower($tmp_arr[sizeof($tmp_arr)-1]);
$handle = fopen($filename,"rb"); //임시파일의 이름을 통해 파일객체를 열어서 반환
//파일객체를 파일객체의 길이만큼을 읽어들여 ' 또는 "의 앞에 \를 붙여서 반환(파일을 이진바이너리형태로 변환)
$imageblob = addslashes(fread($handle, filesize($filename)));
fclose($handle); //열었던 파일객체 닫기


ini_set("memory_limit" , -1); //메모리 오류 방지를 위해 해당 페이지에서의 php메모리 제한을 해제
$query = "INSERT INTO cs_wiki_img (data,title,extension) VALUES ('$imageblob', '$title', '$extnsion')" ; //img저장 DB에 제목과 이진데이터 형태 쿼리문
$result = mysqli_query($conn,$query); //쿼리문 실행
//정상 저장시 출력
if($result == 1){?>
  <h4>업로드 완료</h4>
  <p>[사진 <?php echo $title;?>|가로=200px |세로=300px ]</p>
  <p>위의 코드를 해당위치에 붙여 넣으세요.</p>
<?php
}
//저장 실패시 출력
else{?>
  <h4>업로드 실패</h4>
  <p>다시 시도해 주세요.</p>
<?php
}
?>
