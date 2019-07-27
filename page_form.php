<?php
//전체 화면에 대한 템플릿을 불러올 수 있는 함수들

//html시작태그를 포함한 헤더부분을 출력하는 함수 매개변수의 값을 title명에 이용한다.
function html_header($title){
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <!--부트스트랩을 위한 viewport-->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="shortcut icon" href="favicon.ico"/>
  <title><?php echo $title; ?> :::: 모두의 CS-WIKI</title>
  <!--부트스트랩을 위한 CSS 및 JavaScript, jQuery-->
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/latest/css/bootstrap.min.css" rel="stylesheet">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/latest/js/bootstrap.min.js"></script>
  <!--부트스트랩의 팝오버기능 사용을 위해 popover활성화-->
	<script type="text/javascript">
		$(document).ready(function () {
			$('[data-toggle="popover"]').popover();
		});
  </script>
  <!--사이트 특성에 맞추어 따로 제작한 CSS-->
	<link rel="stylesheet" href="css/style.css">
</head>
<?php
}

//네비게이션바 출력(문서관련페이지를 제외한 페이지용)
function html_navbar($login,$status){
?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">토글 네비게이션</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">CS-WIKI</a>
    </div>
    <div class="collapse navbar-collapse">
      <!--메뉴버튼부분-->
      <ul class="nav navbar-nav">
        <li><a href="index.php">홈</a></li>
        <!--게임메뉴 드롭다운-->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">게임<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="catchBug.php">버그잡기</a></li>
            <li><a href="brick.php">벽돌깨기</a></li>
          </ul>
        </li>
      </ul>
      <!--검색창-->
      <form class="navbar-form navbar-right" action="search.php" method="GET">
        <input type="text" class="form-control" name="keyword" placeholder="Search...">
      </form>

      <!-- 로그인 또는 정보변경 버튼 -->
      <?php if(!$login) //로그인 유무에 따라 버튼형태 및 컨텐츠 변경
              echo '<button type="button" class="btn btn-success navbar-btn navbar-right" data-toggle="modal" data-target="#loginform">로그인</button>';
            else{
              echo '<div class="btn-group navbar-btn  navbar-right">';
              echo '<button type="button" class="btn btn-success" onclick="'."location.href = 'join.php'".'" >정보변경</button>';
              echo '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
              echo '<span class="caret"></span>';
              echo '</button>';
              echo '<ul class="dropdown-menu" role="menu">';
              echo '<li><a href="logout.php">로그아웃</a></li>';
              echo '</ul>	</div>';
            }
      ?>
    </div>
  </div>
</div>
<?php
}

//네비게이션바 출력(문서관련페이지용)
function html_navbar_article($title,$id,$page,$login,$status){
?>
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">토글 네비게이션</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">CS-WIKI</a>
    </div>
    <div class="collapse navbar-collapse">
      <!--메뉴버튼부분-->
      <ul class="nav navbar-nav">
        <li><a href="index.php">홈</a></li>
        <!--문서관련 버튼부분-->
        <li <?php if($page=="page") echo 'class="active"'?>><a href="page.php?title=<?php echo $title; ?>">문서</a></li>
        <li <?php if($page=="write") echo 'class="active"'?>><a href="write.php?title=<?php echo $title; ?>">편집</a></li>
        <li <?php if($page=="history") echo 'class="active"'?>><a href="history.php?title=<?php echo $title;?>&ID=<?php echo $id; ?>">편집이력</a></li>
        <!--게임메뉴 드롭다운-->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">게임<span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="catchBug.php">버그잡기</a></li>
            <li><a href="brick.php">벽돌깨기</a></li>
          </ul>
        </li>
      </ul>
      <!--검색창-->
      <form class="navbar-form navbar-right" action="search.php" method="GET">
        <input type="text" class="form-control" name="keyword" placeholder="Search...">
      </form>

      <!-- 로그인 또는 정보변경 버튼 -->
      <?php if(!$login) //로그인 유무에 따라 버튼형태 및 컨텐츠 변경
              echo '<button type="button" class="btn btn-success navbar-btn navbar-right" data-toggle="modal" data-target="#loginform">로그인</button>';
            else{
              echo '<div class="btn-group navbar-btn navbar-right">';
              echo '<button type="button" class="btn btn-success" onclick="'."location.href = 'join.php'".'" >정보변경</button>';
              echo '<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
              echo '<span class="caret"></span>';
              echo '</button>';
              echo '<ul class="dropdown-menu" role="menu">';
              if($status==1&&$id!=""){
                echo '<li><a href="pagelock.php?title='.$title.'">문서잠금/해제</a></li>';
                echo '<li class="divider"></li>';
              }
              echo '<li><a href="logout.php">로그아웃</a></li>';
              echo '</ul>	</div>';
            }
      ?>
    </div>
  </div>
</div>
<?php
}

//로그인 모달창 부분 출력
function login_modal(){
?>
<!--로그인 모달-->
<div class="modal fade" id="loginform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!--모달 해더부분-->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Login</h4>
      </div>
      <!--모달 바디부분-->
      <div class="modal-body">
        <!--로그인값 전송을 위한 form부분 시작-->
        <form role="form" action="login.php" method="POST">
          <div class="form-group">
            <label for="usrname">
              <span class="glyphicon glyphicon-user"></span> 아이디</label>
            <input type="text" class="form-control" name="ID" id="usrname" placeholder="Enter email">
          </div>
          <div class="form-group">
            <label for="psw">
              <span class="glyphicon glyphicon-eye-open"></span> 비밀번호</label>
            <input type="password" class="form-control" name="password" id="psw" placeholder="Enter password">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox" value="select" name="remember" checked>일주일간 로그인 기억하기</label>
          </div>
          <button type="submit" class="btn btn-success btn-block">
            <span class="glyphicon glyphicon-off"></span> 로그인</button>
        </form>
        <!--로그인값 전송을 위한 form부분 끝-->
      </div>
      <!--모달 풋터부분-->
      <div class="modal-footer">
        <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
          <span class="glyphicon glyphicon-remove"></span> Cancel</button>
        <p>가입하셨나요?
          <a href="join.php">회원가입</a>
        </p>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
