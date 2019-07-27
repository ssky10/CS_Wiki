<?php
  include 'accessDB.php';

  $conn = getDB(); //DB객체 받아옴
  $id = $_POST['id']; //POST로 받은 문서ID 저장

  /*cs_wiki_text테이블에 text을 통한 INSERT*/
  $text_sql = "INSERT INTO cs_wiki_text (text) VALUES('".str_replace("'","\'","<summary>".$_POST['summ']."</summary>".$_POST['description'])."')";
  if(!mysqli_query($conn,$text_sql))
    echo mysqli_error($conn); //만약 INSERT실패시 에러메시지 화면에 출력
  $text_ID = mysqli_insert_id($conn); //직전삽입한 데이터의 id값 반환 및 저장

  /*만약 id가 -1이면 새로생성된 문서페이지*/
  if($id == -1){
    //cs_wiki_page 테이블의 데이터를 추가한다.
    $page_sql = "INSERT INTO cs_wiki_page (title,article,touched) VALUES('".$_POST['title']."', ".$text_ID.", now())";
    $result = mysqli_query($conn,$page_sql); //쿼리문 실행
    $page_ID = mysqli_insert_id($conn); //새로생성된 페이지의 ID를 반환 및 저장
    $sql = "INSERT INTO cs_wiki_keyword (keyword,pageID) VALUES('".$_POST['title']."', ".$page_ID.")";
    mysqli_query($conn,$sql);
  }
  /*그렇지 않으면 수정된 문서*/
  else{
    $page_sql = "UPDATE cs_wiki_page SET article=".$text_ID." WHERE id=".$_POST['id'];
    $result = mysqli_query($conn,$page_sql); //쿼리문 실행
    $page_ID = $_POST['id']; //POST로 받아온 id를 페이지ID로 저장
  }

  /*cs_wiki_history테이블에 페이지의 수정이력 기록*/
  if($_COOKIE['Login']) $writer = $_COOKIE['nickname']; //로그인된 경우 nickname을 기록
  else $writer = $_SERVER['REMOTE_ADDR']; //로그인이 되지 않은 경우 접속ip를 기록
  $history_sql = "INSERT INTO cs_wiki_history (page_id,text_id,writer,time,exp) VALUES(".$page_ID.", ".$text_ID.", '".$writer."', now(), '".$_POST['explan']."')";
  $result = mysqli_query($conn,$history_sql); //쿼리문 실행
  header('Location: page.php?title='.$_POST['title']); //수정 또는 생성 완료 후 해당 문서 페이지로 이동
?>
