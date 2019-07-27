<?php
	include './page_form.php'; //전체화면 구성을 위해서 필요
	include './accessDB.php'; //DB에 접속을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴
	
	if(!empty($_GET['ID'])){
		$sql = "SELECT ID,page_id,text_id,writer,time,exp FROM cs_wiki_history  WHERE page_id=".$_GET['ID']." ORDER BY ID DESC";
		$content = mysqli_query($conn,$sql);
    $num = mysqli_num_rows($content);
	}


	html_header("문서이력 : ".$_GET['title']); //html의 head부분을 그려주는 함수 호출
?>
<body>
	<?php html_navbar_article($_GET["title"],$_GET["ID"],"history",$_COOKIE['Login'],$_COOKIE['status']); /*상단네비게이션바와를 그려주는 함수 호출*/?>
	<div class="container">
	  <div class="innercontainer">
			<!--제목-->
			<h1 id="firstHeading" class="firstHeading"><?php echo htmlspecialchars($_GET["title"]); ?> 문서 이력</h1><hr/>
			<?php
			//테이블의 형태로 문서의 수정이력을 표시
			if($num!=0){
				echo '<table class="table table-hover">';
				while($row = mysqli_fetch_assoc($content)){
					echo '<tr><td>'.$num.' : '.$row['exp'].' / '.$row['writer'].' / '.$row['time'].' ( <a href="reback.php?num='.$num.'&title='.$_GET['title'].'&pageID='.$_GET['ID'].'&textID='.$row['text_id'].'">변경</a> | <a href="preview.php?textID='.$row['text_id'].'" target="_blank">보기</a> )';
					echo '</tr></td>';
					$num = $num-1;
				}
				echo '</table></div>';
			}
			?>
<?php if(!$_COOKIE['Login']) login_modal(); /*로그인 상태가 아니라면 login모달창을 그려줌*/?>
</body>
</html>
