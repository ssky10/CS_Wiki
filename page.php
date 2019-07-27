<?php
	include "./articleset.php"; //wiki마크업언어를 html로 변환하기 위해서 필요
	include './page_form.php'; //전체화면 구성을 위해서 필요
	include './accessDB.php'; //DB에 접속을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴
	
	if(!empty($_GET['title'])){
		//cs_wiki_page에 저장된 정보+cs_wiki_page.article에 저장된값과
		//cs_wiki_text.ID값이 일치하도록 DB를 결합 후 title이 일치하는 DB값 추출 
		$sql = "SELECT cs_wiki_page.ID,title,text,locked FROM cs_wiki_page LEFT JOIN cs_wiki_text ON cs_wiki_page.article = cs_wiki_text.ID WHERE cs_wiki_page.title='".$_GET['title']."'";
		$content = mysqli_query($conn,$sql); //쿼리문 실행
		$row2 = mysqli_fetch_assoc($content); //쿼리 결과를 배열형태로 반환
	}

	html_header("문서 : ".$_GET['title']); //html의 head부분을 그려주는 함수 호출
?>
<body>
	<?php html_navbar_article($row2["title"],$row2["ID"],"page",$_COOKIE['Login'],$_COOKIE['status']); /*상단네비게이션바와를 그려주는 함수 호출*/ ?>
	<div class="container">
	  <div class="innercontainer">
			<!--제목-->
	    <div class="page-header">
				<h1 id="firstHeading" class="firstHeading"><?php
					echo htmlspecialchars($row2["title"]);		
    			if($row2["locked"]==1) //수정이 잠긴 문서의 경우 출력
      			echo ' <small style="font-size:10px;"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>수정이 잠긴문서입니다.</small>';
  			?></h1>
	    </div>
			<!--컨텐츠-->
		<div><?php echo Transform_text(htmlspecialchars(nl2br($row2["text"]))); /*마크업언어로 작성된 텍스트를 html코드로 변환출력*/ ?></div>
	</div>
	<?php if(!$_COOKIE['Login']) login_modal(); /*로그인 상태가 아니라면 login모달창을 그려줌*/ ?>
</body>
</html>
