<?php
include 'accessDB.php'; //DB에 접속을 위해서 필요

$conn = getDB(); //DB객체 받아옴

$lock = 1; //lock선언 및 1로 초기화

if(!empty($_GET['title'])){

  if($_COOKIE['status']!=1){
    header('Location: page.php?title='.$_GET['title']); //권한이 없는 경우 문서페이지로 단순 이동
  }

  //해당문서의 잠김설정 값을 불러오는 쿼리문
  $sql = "SELECT cs_wiki_page.ID,locked FROM cs_wiki_page WHERE cs_wiki_page.title='".$_GET['title']."'";
  $content = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($content);
  //만약 잠겨있는 문서라면 lock을 0으로 설정
  if($row['locked']==1) $lock = 0;
  //잠김상태 업데이트 쿼리문
  $sql = "UPDATE cs_wiki_page SET locked = ".$lock." WHERE ID= ".$row['ID'];
  $content = mysqli_query($conn,$sql);
  header('Location: page.php?title='.$_GET['title']); //수정 후 해당 문서 페이지로 이동
}
?>
