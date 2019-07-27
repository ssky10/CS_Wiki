<?php
include 'accessDB.php';

$conn = getDB();
$page = $_GET['pageID'];
$text = $_GET['textID'];

//해당문서의 잠김설정 값을 불러오는 쿼리문
$sql = "SELECT cs_wiki_page.ID,locked FROM cs_wiki_page WHERE cs_wiki_page.title='".$_GET['title']."'";
$content = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($content);

//만약 잠겨있는 문서이고 관리자 로그인이 아닌경우
if($row['locked']==1 && $_COOKIE['status']!=1) echo '<script> alert("수정이 잠긴 문서입니다."); location.href = document.referrer;</script>';
else{
    if($_COOKIE['Login']) $writer = $_COOKIE['nickname']; //로그인된 경우 nickname을 기록
    else $writer = $_SERVER['REMOTE_ADDR']; //로그인이 되지 않은 경우 접속ip를 기록
    
    $sql = "UPDATE cs_wiki_page SET article=".$text." WHERE id=".$page;
    mysqli_query($conn,$sql);
    
    $history_sql = "INSERT INTO cs_wiki_history (page_id,text_id,writer,time,exp) VALUES(".$page.", ".$text.", '".$writer."', now(), '이전문서(".$_GET['num'].")로 돌아감')";
    
    $result = mysqli_query($conn,$history_sql);
    header('Location: page.php?title='.$_GET['title']); //문서페이지로 이동
}

?>
