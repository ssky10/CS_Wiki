<?php
	include 'accessDB.php'; //DB에 접속을 위해서 필요

	$conn = getDB(); //accessDB.php에 있는 getDB()를 통해 DB객체 불러옴
	//id가 일치하는 데이터를 불러와서 저장
	$result = mysqli_query($conn,"SELECT password,ID,nickname,status FROM cs_wiki_users WHERE login_id = '".$_POST['ID']."'");
	$row = mysqli_fetch_assoc($result); //저장된 결과 데이터 배열 형태로 변환
	echo '<script>';
	if($row == false){
		echo 'alert("ID가 존재하지 않습니다.");'; //데이터 결과값이 없는경우
	}else{
		$password = $row['password'];
		if ( !password_verify($_POST['password'] , $password) ) {
			echo 'alert("비밀번호가 일치하지 않습니다.");'; //비밀번호가 일치하지 않는 경우
		}else{
			//7일간 기억하기에 체크시 time을 현재시각+7일, 아니면 0(세션)
			if($_POST['remember']=='select') $time = time()+(60*60*12*7);
			else $time = 0;
			//로그인 정보에 관한 쿠기를 저장
			setcookie('Login',true,$time,'/','.cs-wiki.ml',1);
			setcookie('ID',$row['login_id'],$time,'/','cs-wiki.ml',1);
			setcookie('nickname',$row['nickname'],$time,'/','.cs-wiki.ml',1);
			setcookie('status',$row['status'],$time,'/','.cs-wiki.ml',1);
			//로그인 완료창 화면에 띄움
			echo "alert('로그인 되었습니다.');\n";
		}
	}
	echo 'location.href = document.referrer;'; //이전페이지로 돌아감
	echo '</script>';
?>
