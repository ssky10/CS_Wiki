<?php
	include './page_form.php'; //전체페이지틀 호출을 위해 포함

	html_header('게임:벽돌깨기'); //html의 head부분을 그려주는 함수 호출
?>
<body>
	<?php html_navbar($_COOKIE['Login'],$_COOKIE['status']); /*상단네비게이션바와를 그려주는 함수 호출*/ ?>
	<div class="container">
		<div class="innercontainer">
			<!--화면 캔버스 부분-->
    		<canvas id="myCanvas" width="650" height="300"></canvas>

    		<br />
    		<p style="text-align:center">
				일시정지 : <kbd>ESC</kbd><br/>
        		시작 : <kbd>&nbsp;space bar  </kbd><br />
        		이동 : 마우스 / <kbd>←</kbd>+<kbd>→</kbd>
    		</p>
		</div>
	</div>
	<?php if(!$_COOKIE['Login']) login_modal(); /*로그인 상태가 아니라면 login모달창을 그려줌*/ ?>
	<!--벽돌깨기 JavaScript 부분-->
	<script src="js/brick.js"></script>
</body>
</html>
