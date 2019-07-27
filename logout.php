<?php
//세션에 저장된 값들을 변경시키고 만료시간을 과거로 바꿈
setcookie('Login',false,time()-2300,'/','.cs-wiki.ml',1);
setcookie('ID',"",time()-2300,'/','cs-wiki.ml',1);
setcookie('nickname',"",time()-2300,'/','.cs-wiki.ml',1);
setcookie('status',"",time()-2300,'/','.cs-wiki.ml',1);

//로그아웃 완료창 출력 및 이전페이지로 이동
echo '<script> alert("로그아웃 되었습니다."); location.href = document.referrer;</script>';
?>
