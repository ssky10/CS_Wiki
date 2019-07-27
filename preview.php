<?php
	include './accessDB.php'; //DB에 접속을 위해서 필요
	include "./articleset.php"; //wiki마크업언어를 html로 변환하기 위해서 필요
	include './page_form.php'; //전체화면 구성을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴

	if(!empty($_GET['textID'])){
		$sql = "SELECT ID,text FROM cs_wiki_text WHERE ID=".$_GET['textID'];
		$content = mysqli_query($conn,$sql);
		$row = mysqli_fetch_assoc($content);
	}
	html_header("미리보기"); //html의 head부분을 그려주는 함수 호출
?>
<body>
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	        <span class="sr-only">토글 네비게이션</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">CS-WIKI</a>
	    </div>
	    <div class="collapse navbar-collapse">
	      <ul class="nav navbar-nav">
	        <li class="active"><a href="#">홈</a></li>
	      </ul>
	      <form class="navbar-form navbar-right" action="search.php" method="GET">
					<input type="text" class="form-control" name="keyword" placeholder="Search...">
	      </form>
	    </div>
	  </div>
	</div>
	<div class="container">
	  <div class="starter-template">
	<h1 id="firstHeading" class="firstHeading">미리보기</h1>
	<hr/>
	<?php echo Transform_text(htmlspecialchars(nl2br($row["text"]))); /*마크업언어로 작성된 텍스트를 html코드로 변환출력*/ ?>
</body>
</html>
