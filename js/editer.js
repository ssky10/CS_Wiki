var agent = navigator.userAgent.toLowerCase(); //접속웹브라우저의 정보를 받아서 모주 소문자로 바꾸어준다.
var ie = false; //ie여부 변수를 false로 초기화 시켜준다.

if ( (navigator.appName == 'Netscape' && navigator.userAgent.search('Trident') != -1) || (agent.indexOf("msie") != -1) ) {
  ie = true; //만약 접속 웹브라우저가 ie인경우 ie를 true로 설정
}

$(function(){
  var editor = document.getElementById('editor');
  if(editor.isContentEditable){
    editor.focus(); //에티터 창에 포커스를 둔다
  }
});

function innerInForm(format) {
  if(ie){
    alert("클립보드에 복사되었습니다.");
    window.clipboardData.setData("Text", format); //ie로 접속한 경우 클립보드로 복사
  }
  else
    document.execCommand('insertText',false,format); //현재 커서 위치에 format문자열 추가
}
