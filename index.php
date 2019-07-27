<?php
	include './page_form.php'; //전체화면 구성을 위해서 필요
	include 'accessDB.php'; //DB에 접속을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴

	//최근 수정된 문서 목록 10개를 불러오는 쿼리문
	$sql = "SELECT A.ID,title,page_id,writer,time,exp FROM (SELECT T.ID, T.page_id,writer,time,exp FROM ( SELECT MAX(ID) AS ID,page_id FROM cs_wiki_history GROUP BY page_id ) T LEFT JOIN cs_wiki_history ON T.ID = cs_wiki_history.ID ) A LEFT JOIN cs_wiki_page ON A.page_id = cs_wiki_page.ID ORDER BY TIME DESC";
	$content = mysqli_query($conn,$sql);
	//최근 생성된 문서 목록 10개를 불러오는 쿼리문
	$sql = "SELECT * FROM `cs_wiki_page` ORDER BY touched DESC LIMIT 10";
	$content2 = mysqli_query($conn,$sql);

	//각 쿼리문의 실행결과 데이터 열의 개수를 반환
	$num = mysqli_num_rows($content);
	$num2 = mysqli_num_rows($content2);

	html_header("홈"); //html의 head부분을 그려주는 함수 호출
?>
<body>
<?php html_navbar($_COOKIE['Login'],$_COOKIE['status']);  /*상단네비게이션바와를 그려주는 함수 호출*/?>
<!--네비게이션바 아래에 점보트론으로 화면을 채워준다-->
<div class="jumbotron" id="jumbo_main">
	<h1>환영합니다!</h1>
	<p><b>CS-WIKI는 다함께 만들어가는 사전입니다.</b><br/>
	경상대 컴퓨터 과학과와 관련이 있는, 알아두면 도움이 되는 모든 지식을 다함께 만들어요!</p>
	<p><a class="btn btn-primary btn-lg" href="page.php?title=CS-WIKI" role="button">더 알아보기</a></p>
</div>
<!--점보트론 아래의 컨텐츠 부분-->
<div class="container">
	<div class="innercontainer">
		<div class="row">
			<!--부트스트랩 그리드시스템에 의해 가로폭이 줄어들경우 12/12차지-->
			<!--그 외에 일반 화면사이즈에서는 6/12차지-->
			<div class="col-xs-12 col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h2>최근 생성된 문서</h2></div>
						<?php
						//개수가 0개일 경우 실행안함
            if($num2!=0){
              echo '<table class="table table-hover">';
              while($row = mysqli_fetch_assoc($content2)){
                echo '<tr><td>'.$row['title'].'/'.$row['touched'].' (<a href="page.php?title='.$row['title'].'">보기</a>)';
                echo '</td></tr>';
              }
            	echo '</table>';
            }?>
					</div>
				</div>
				<!--부트스트랩 그리드시스템에 의해 가로폭이 줄어들경우 12/12차지-->
				<!--그 외에 일반 화면사이즈에서는 6/12차지-->
				<div class="col-xs-12 col-md-6">
          <div class="panel panel-default">
          	<div class="panel-heading"><h2>최근 수정된 문서</h2></div>
						<?php
						//개수가 0개일 경우 실행안함
						if($num!=0){
					  	echo '<table class="table table-hover">';
					      while($row = mysqli_fetch_assoc($content)){
					    		echo '<tr><td>'.$row['title'].'/'.$row['exp'].' / '.$row['writer'].' / '.$row['time'].' (<a href="page.php?title='.$row['title'].'">보기</a>)';
					    		echo '</tr></td>';
					  		}
					 		echo '</table>';
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php if(!$_COOKIE['Login']) login_modal(); /*로그인 상태가 아니라면 login모달창을 그려줌*/ ?>
</body>
</html>
