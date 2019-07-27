<?php
	include './page_form.php'; //전체화면 구성을 위해서 필요
	include './articleset.php'; //문서text를 개요부분과 내용부분으로 분리하기 위해 필요
	include './accessDB.php'; //DB에 접속을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴
	$title = $_GET['title'];; //title선언 및 초기화
	$content_text = ""; //content_text선언 및 초기화

	if(!empty($_GET['title'])){
		//넘어온 title와 일치하는 page검색하는 쿼리문
		$sql = "SELECT cs_wiki_page.ID,title,text,locked FROM cs_wiki_page LEFT JOIN cs_wiki_text ON cs_wiki_page.article = cs_wiki_text.ID WHERE cs_wiki_page.title='".$_GET['title']."'";
		$content = mysqli_query($conn,$sql);
		if(mysqli_num_rows($content)==0){ //검색결과가 없으면
			$text_arr = ["",""]; //개요부분과 내용부분 빈문자열로 초기화
			$is_new = true; //새로운 문서표시
		}else{
			$row2 = mysqli_fetch_assoc($content);
			if($row2['locked']==1 && $_COOKIE['status']!=1) echo '<script> alert("수정이 잠긴 문서입니다."); location.href = document.referrer;</script>';
			$text_arr = divText($row2['text']); //저장된 문서를 개요부분과 내용부분으로 나눈 문자열 반환
			$is_new = false; //새로운 문서가 아님을 표시
		}
	}else{
		header("location: index.php"); //title값이 주어지지 않은 상태로 넘어온 경우 메인 인덱스로 전환
	}

	html_header("문서편집 : ".$title); //html의 head부분을 그려주는 함수 호출
?>
<body>
	<?php
	if(!$_COOKIE['Login']) login_modal(); /*상단네비게이션바와를 그려주는 함수 호출*/

	if($is_new) html_navbar($_COOKIE['Login'],$_COOKIE['status']); //문서생성이면 편집이력등의 버튼이 없는 네비게이션바 출력
	else html_navbar_article($title,$row2["ID"],"write",$_COOKIE['Login'],$_COOKIE['status']); //문서편집이면 문서전용버튼이 있는 네비게이션바 출력
	?>
	<div class="container">
		<div class="innercontainer">
			<!--제목-->
			<h1 id="firstHeading" class="firstHeading">'<?php echo htmlspecialchars($title);?>' 문서 편집하기</h1> <hr/>
			<!--문서전송을 위한 폼-->
			<form action="process.php" method="post">
				<p>
					<div class="form-group">
						<!--에디터 버튼 그룹 시작-->
						<div class="btn-group" data-toggle="buttons">
							<button type="button" class="btn btn-info" onclick="innerInForm('==제목==\n')">제목</button>
							<button type="button" class="btn btn-info" onclick="innerInForm('===소제목===\n')">소제목</button>
							<button type="button" class="btn btn-info" onclick="innerInForm("+"'''텍스트'''"+")">진하게</button>
							<button type="button" class="btn btn-info" onclick="innerInForm('{{|제목=내용\n|제목=내용|}}\n')">요약표</button>
							<button type="button" class="btn btn-info" onclick="innerInForm('[[링크문서명|텍스트]]\n')">내부링크</button>
							<button type="button" class="btn btn-info" onclick="innerInForm('[$링크주소|텍스트$]')">외부링크</button>
							<button type="button" class="btn btn-info" onclick="innerInForm('<ref>각주내용</ref>')">각주</button>
							<button type="button" class="btn btn-info" onclick="innerInForm('==각주==\n{{각주}}')">각주 출력</button>
							<button type="button" class="btn btn-primary" onclick='window.open("image_up.html", "window", "width=500,height=290");'>이미지</button>
						</div>
						<!--에디터 버튼 그룹 끝-->
					</div>
					<div class="form-group">
						<!--문서 개요 작성란-->
						<textarea name="summ" class="form-control" rows="2" style="resize:none;" placeholder="문서 개요 작성란..."><?php echo htmlspecialchars($text_arr[0]);?></textarea>
					</div>
					<div class="form-group">
						<!--문서 내용 작성란-->
						<textarea name="description" id="editor" class="form-control" rows="25" cols="80" placeholder="문서내용 작성란..." style="resize:none;"><?php echo htmlspecialchars($text_arr[1]);?></textarea>
					</div>
				</p>
				<p>
					<input type="text" placeholder="편집이력 요약설명..." name="explan" class="form-control" />
				</p>
				<input type="hidden" value="<?php
					if(!$is_new){
						echo $row2["ID"]; //새문서가 아닐 경우 문서의 고유 ID값 출력
					}else{
						echo '-1'; //새문서일 경우 -1 출력
					} ?>" name="id">
				<input type="hidden" value="<?php echo $_GET["title"]; ?>" name="title">
				<input type="submit" class="btn btn-success">
			</form>
		</div>
	</div>
	<!--에디터버튼에서의 작동을 위한 JavaScript-->
	<script src="js/editer.js"></script>
</body>
</html>
