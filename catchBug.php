<?php
  include './page_form.php'; //전체페이지틀 호출을 위해 포함
  
	html_header('게임:버그잡기'); //html의 head부분을 그려주는 함수 호출
?>
<!--body부분이 로드되면 createMap()이 실행됩니다-->
<body id="target" onload="createMap()">
<link rel="stylesheet" href="css/catchBug.css">
<script src="js/catchBug.js"></script>

<?php html_navbar($_COOKIE['Login'],$_COOKIE['status']); /*상단네비게이션바와를 그려주는 함수 호출*/?>
    <div class="container">
      <div class="innercontainer">
        <div id="info">
          <div id="info_outer">
            <div id="info_inner">
              <div id="info_centered">
                <h4 id="info_title">버그잡기</h4>
                <p id="info_text">랜덤하게 나타나는 버그를 마우스로 잡으세요.<br/><br/>
                   잡을 때 정상적인 실행을 잡지 않도록 주의하세요!</p><br/>
                   <button class="popbtn" onclick="startGame()">start!</button>
              </div>
            </div>
          </div>
        </div>
        <div id="map">
        </div>
      </div>
    </div>
		<?php if(!$_COOKIE['Login']) login_modal(); /*로그인 상태가 아니라면 login모달창을 그려줌*/?>
  </body>
</html>
