var space; //전체 맵 공간
var bugs; //맵내의 12개의 공간의 상태
var score; //점수
var timer = new Array(); //버그가 반복하여 나타나는 타이머 배열
var nowBugArr = new Array(); //현재 0~2번버그의 위치
var timeOver; //게임시간 측정
var bugColor = ['','url(../img/bug1.jpg)','url(../img/bug2.jpg)','url(../img/bug3.jpg)']; //버그의 이미지
var level; //게임레벨

//기본 세팅 함수
function settings(){
  space = document.getElementsByName("target"); //게임 맵을 연결
  space.forEach(function(v){
    v.style.backgroundImage = ""; //맵의 각 공간의 배경이미지를 초기화
  })
  bugs = [0,0,0,0,0,0,0,0,0,0,0,0]; //맵의 위치의 각 상태배열 초기화
  nowBugArr = [0,0,0]; //각 번째 버그배열 초기화
  score = 0; //점수초기화
  level = 1; //레벨초기화
  bugSet(0,2000); //0번버그를 2초간격으로 재생성
  timeOver = setTimeout(gameEnd,62000); //게임시간 1분 + 대기시간 2초
  document.getElementById('scoreNum').innerText = score; //화면에 점수출력
}

//맵을 생성
function createMap(){
  var map = document.getElementById("map");
  var width_map = $(".innercontainer").width(); //부모요소의 가로 길이 측정

  width_map = width_map; 
  height_map = (width_map/16)*9;
  $("#map").width(width_map); //전체맵의 가로길이설정
  $("#map").height(height_map); //전체맵의 높이설정
  
  //전체map의 하위에 12개의 각각의 클릭공간을 만들어서 설정한다.
  for(var i=0;i<12;i++){
    var newDiv = document.createElement('div');
    var attrName = document.createAttribute("name");
    var attrOnclick = document.createAttribute("onclick");
    attrName.value = "target";
    attrOnclick.value = "clickTarget("+i+")";
    newDiv.setAttributeNode(attrName);
    newDiv.setAttributeNode(attrOnclick);
    //newDiv.style.width = width_map/4;
    //newDiv.style.height = (height_map-height_map/14)/3;
    map.appendChild(newDiv);
  }

  //전체map의 남는 부분에 점수를 출력하기 위한 공간 생성
  var newDiv = document.createElement('div');
  var newStrong = document.createElement('strong');
  var attridDiv = document.createAttribute("id");
  var attridStr = document.createAttribute("id");
  attridDiv.value = "score";
  newDiv.setAttributeNode(attridDiv);
  attridStr.value = "scoreNum";
  newStrong.setAttributeNode(attridStr);
  newDiv.appendChild(newStrong);
  map.appendChild(newDiv);

  //위에서 생성한 공간들의 가로세로길이를 각각 설정해준다.
  $("[name=target]").width(width_map/4);
  $("[name=target]").height((height_map-height_map/20)/3);
  $("#score").width(width_map/4);
  $("#score").height(height_map/20);
  $("#info").width(width_map);
  $("#info").height(height_map);
}

//setInterval함수를 이용하여 각 버그를 설정해준다.
function bugSet(num,time){
  timer[num] = setInterval(setTarget,time,num);
}

//각각에 target을 클릭했을 때 호출되는 함수
function clickTarget(id){
  switch (bugs[id]) {//버그의 종류에 따라 점수 증/차감
    case 1: score += 30; break;
    case 2: score -= 20; break;
    case 3: score += 70; break;
    default: return; //만약 아무것도 아닌공간을 클릭한경우 함수종료
  }
  setTarget(bugs[id]-1); //버그위치 재설정
  document.getElementById('scoreNum').innerText = score; //스코어 새로고침
  //점수가 특정구간이 지나면 에러 종류가 더 많아짐
  if(level==1&&score>60){
    bugSet(1,2000); level++;
  }else if(level==2&&score>60){
    bugSet(2,500); level++;
  }
}

//랜덤으로 버그의 위치를 선정하고 전정된 위치의 배경을 바꾸어준다
function setTarget(num){
  var temp = Math.floor(Math.random()*12);
  space[nowBugArr[num]].style.backgroundImage = "";
  while(bugs[temp] != 0){
    temp = Math.floor(Math.random()*12);
  }
  bugs[temp] = num+1;
  bugs[nowBugArr[num]]=0;
  nowBugArr[num] = temp;
  space[temp].style.backgroundImage = bugColor[num+1];
}

//게임종료시 버그가 타이머를 모두 종료시키고 화면에 점수를 출력한다
function gameEnd(){
  for(var i=0;i<timer.length;i++){
    clearInterval(timer[i]);
  }
  changeInfo("Game Over!","당신의 점수는 "+score+"점 입니다.");
}

//초기화면의 제목과 내용,화면 표시여부 설정
function changeInfo(title,text="",nonedis=false){
  if(nonedis) document.getElementById('info').style.display = "none";
  else document.getElementById('info').style.display = "";
  document.getElementById('info_title').innerText = title;
  document.getElementById('info_text').innerText = text;
}

//게임을 시작하기전 초기화 함수,초기화면 제거 등의 역할
function starttimer(time){
  changeInfo(time) //시작페이지의 text를 남은시간으로 바꿈
  if(time==2) settings();
  if(time==0) changeInfo("","",true);
  else{
    time--;
    setTimeout(starttimer,1000,time);
  }
}

//게임시작 함수
function startGame(){
  starttimer(3); //스타트 타이머 실행
}