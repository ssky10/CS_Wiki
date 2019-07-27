<?php
include './page_form.php'; //전체화면 구성을 위해서 필요

if($_COOKIE['Login'])
	html_header('정보수정'); //로그인한 사용자가 접근할 경우 타이틀을 정보수정으로
else
	html_header('회원가입'); //로그인안한 사용자가 접근할 경우 타이틀을 회원가입으로
?>
<body>
<script>
var idck = <?php if(!$_COOKIE['Login']) echo 'false'; else echo 'true'; /*로그인을 한 사용자는 id변경 불필요*/?>;
var emailck = 'false';

//입력결과를 제출하기전 먼저 전체폼의 유효성검사 실행
function check() {
  if($('#inputPassword').val()==""){
    alert("비밀번호를 입력해주세요.");
    return false;
  }
  else if($('#inputPassword2').val()==""){
    alert("비밀번호확인을 입력해주세요.");
    return false;
  }
  else if(!idck) {
    alert("아이디 중복확인을 해주세요.");
    return false;
  }
  else if(!emailck) {
    alert("이메일 중복확인을 해주세요.");
    return false;
  }else if($('#inputPassword2').val()!=$('#inputPassword').val()){
                alert("비밀번호가 일치하지 않습니다.");
                return false;
        }
  else return true;
}
</script>
<?php html_navbar($_COOKIE['Login'],$_COOKIE['status']); /*상단네비게이션바와를 그려주는 함수 호출*/?>
	<div class="container">
		<div class="innercontainer">
			<form class="form-horizontal" action="addmember.php" onsubmit="return check();" method="POST" >
				<?php if(!$_COOKIE['Login']){ /*로그인을 한 사용자는 id변경 불필요*/ ?>
				<div class="form-group" id="formId">
					<label for="inputId" class="col-sm-2 control-label">아이디</label>
					<div class="col-sm-10">
						<div class="input-group">
						<input type="text" name="id" pattern="^[a-zA-Z0-9]*$" minlength="5" class="form-control" id="inputId" placeholder="영문대소문자,숫자 5자이상">
							<span class="input-group-btn">
								<button type="button" class="btn btn-warning" id="idcheck" onclick="idCheck();">중복확인</button>
							</span>
						</div>
					</div>
				</div>				
				<?php }?>
				<div class="form-group"id="formMail">
					<label for="inputMail" class="col-sm-2 control-label">이메일</label>
					<div class="col-sm-10">
						<div class="input-group">
							<input type="email" class="form-control" id="inputMail" name="email"  placeholder="이메일주소 입력">
							<span class="input-group-btn">
								<button type="button" class="btn btn-warning" id="mailcheck" onclick="mailCheck();">중복확인</button>
							</span>
						</div>
					</div>
				</div>
				<div class="form-group" id="formPassword">
					<label for="inputPassword" class="col-sm-2 control-label">비밀번호<?php if($_COOKIE['Login']) echo ' 변경';?></label>
					<div class="col-sm-10">
						<input type="password" name="password" pattern="^[a-zA-Z]+[a-zA-Z0-9]*[!@#$%^*()]+[a-zA-Z0-9!@#$%^*()]*$" minlength="7" class="form-control" id="inputPassword" placeholder="영문으로 시작하는 영문,숫자,!@#$%^*()포함 7자리">
					</div>
				</div>
          <div class="form-group" id="formPassword2">
            <label for="inputPassword2" class="col-sm-2 control-label">비밀번호 확인</label>
            <div class="col-sm-10">
              <input type="password" pattern="^[a-zA-Z]+[a-zA-Z0-9]*[!@#$%^*()]+[a-zA-Z0-9!@#$%^*()]*$" minlength="7" class="form-control" id="inputPassword2" placeholder="영문으로 시작하는 영문,숫자,!@#$%^*()포함 7자리">
            </div>
          </div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
							<button type="submit" class="btn btn-default">확인</button>
						</div>
					</div>
					<input type="hidden" value="<?php echo $_COOKIE["ID"]; ?>" name="reviseID">
				</form>
			</div>
		</div>
<?php if(!$_COOKIE['Login']){ //로그인을 한 사용자는 id중복확인과 로그인 모달이 필요없음
	 login_modal(); ?>
<script>
function idCheck(){
	var check = /[^A-Za-z0-9]/;
	var str = $('#inputId').val();
	if(str.length < 5){
		alert("최소 길이는 5자 입니다.");
	}
	else if(check.test(str)){
		alert("영문자와 숫자조합만 가능합니다.");
	}else{
		console.log(AjaxCall("id",str));
	}
}
<?php } ?>
function mailCheck(){
	var check = /^[0-9a-zA-Z.!#$%&'*+/=?^_`{|}~-]+@[0-9a-zA-Z-]+(?:\.[a-zA-Z0-9-]+)*$/;
	var str = $('#inputMail').val();
	if(!check.test(str)){
		alert("이메일 양식이 맞지않습니다.");
	}else{
		emailck = AjaxCall("mail",str);
	}
}
//Ajax를 이용하여 id 또는 mail중복확인
function AjaxCall(type,data) {
	 	$.ajax({
			type: "GET",
		 	url : "joinCheck.php?type=" + type + "&data=" + data,
		 	dataType:"json",
		 	success : function(data, status, xhr) {
				alert(data['message']);
				if(data['code']==0) type=="id"? idck=true:emailck=true;
				else type=="id"? idck=false:emailck=false;
			 },
		 	error: function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR.responseText);
				type=="id"? idck=false:emailck=false;
			}
		});
}
</script>

</body>

</html>
