<?php
	include './articleset.php'; //검색결과에서 마크업언어를 제거하고 요약부분만 출력하도록 하기위해 필요
	include './page_form.php'; //전체화면 구성을 위해서 필요
	include './accessDB.php'; //DB에 접속을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴
	//keyword테이블에 검색어와 일치하는 데이터가 있는지 확인하는 쿼리문
	$sql = "SELECT cs_wiki_page.title,cs_wiki_page.ID,keyword FROM cs_wiki_page LEFT JOIN cs_wiki_keyword ON cs_wiki_page.ID = cs_wiki_keyword.pageID WHERE keyword ='".$_GET['keyword']."'";
	$content = mysqli_query($conn,$sql);
	if(mysqli_num_rows($content)==1){ //검색결과가 1개가 존재하면
		$row = mysqli_fetch_assoc($content);
		header('Location: page.php?title='.$row['title']); //해당문서로 바로 이동
	}

	html_header('검색 : '.$_GET['keyword']); //html의 head부분을 그려주는 함수 호출
?>
<body>
	<?php html_navbar($_COOKIE['Login'],$_COOKIE['status']); /*상단네비게이션바와를 그려주는 함수 호출*/ ?>
	<div class="container">
	  <div class="innercontainer">
			<h1 style="margin-top:10px; margin-bottom:20px;"><?php echo $_GET['keyword']; ?>의 검색결과</h1>
			<!--문서를 추가하기위한 링크부분-->
  		<h4>찾으시려는 문서가 없어요. <a href="./write.php?title=<?php echo $_GET['keyword'];?>">추가</a>하실래요? </h4>
			<?php
			//text의 n-gram 전문검색을 이용하여 해당 검색어가 문서내용중에 존재하는지 검색
			$sql = "SELECT cs_wiki_page.ID,title,text FROM cs_wiki_page LEFT JOIN cs_wiki_text ON cs_wiki_page.article = cs_wiki_text.ID WHERE MATCH(text) AGAINST('".$_GET['keyword']."' IN BOOLEAN MODE)";
			$content = mysqli_query($conn,$sql);
			//검색결과가 존재할경우 테이블 형태로 검색결과를 보여준다.
			if(mysqli_num_rows($content)!=0){
  			echo '<div class="panel panel-default"><div class="panel-heading">검색어가 포함된 문서입니다.</div>';
  			echo '<table class="table table-hover">';
       	while($row = mysqli_fetch_assoc($content)){
  				echo '<tr><td>'.'<a href="page.php?title='.$row['title'].'">'.str_replace($_GET['keyword'],"<strong>".$_GET['keyword']."</strong>",$row['title']).'</a><br/>';
   				if(mb_strlen($row['text'], "utf-8")<250){
     				echo str_replace($_GET['keyword'],"<strong>".$_GET['keyword']."</strong>",htmlspecialchars(removeTag(divText($row['text'])[0]))).'</td></tr>';
   				}else{
     				echo str_replace($_GET['keyword'],"<strong>".$_GET['keyword']."</strong>",mb_substr(htmlspecialchars(removeTag(divText($row['text'])[0])),0,250,"utf-8")).'</td></tr>';
   				}
  			}
  			echo '</table></div>';
			}
			?>
		</div>
	</div>
	<?php if(!$_COOKIE['Login']) login_modal(); /*로그인 상태가 아니라면 login모달창을 그려줌*/ ?>
</body>
</html>
