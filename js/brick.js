var canvas = document.getElementById('myCanvas');
var ctx = canvas.getContext('2d');
var interval = 10; //화면재구성 시간간격
var x = canvas.width / 2; //공 위치x좌표
var y = canvas.height / 2; //공 위치y좌표
var dx = 3; //x축이동거리
var dy = -3; //y축이동거리
var ballRadius = 10; //공 지름
var paddleHeight = 10; //막대 높이
var paddleWidth = 100; //막대 길이
var paddleX = (canvas.width - paddleWidth) / 2; //막대 x좌표(왼쪽끝 기준)
var brickRowCount = 4; //벽돌 세로개수
var brickColumnCount = 6; //벽돌 가로개수
var brickWidth = (x + 170) / (brickColumnCount - 1); //벽돌 길이
var brickHeight = 15; //벽돌 높이
var brickPadding = 5; //벽돌간 간격
var brickOffsetTop = 30; //벽돌 위의 공간
var brickOffsetLeft = 15; //벽돌 왼쪽의 공간
var brickDouble = 6; //2중벽돌의 개수
var brickCombo = 2; //콤보벽돌의 개수
var brickBig = 2; //공이커지는벽돌의 개수
var score = 0; //점수
var Life = 1; //게임기회
var rowcolors = ["#FF1C0A", "#FFFD0A", "#00A308", "#0008DB", "#EB0093", "#AABCDD", "#bbcc44", "#777755"]; //벽돌 색 배열
var switchcolor = 0;
var intervalcolor = 50;
var level = 1;
var showText = "";
var showTextTimer = 0;
var showTextInterval = 100;
var rightPressed = false; //오른쪽 버튼 토글
var leftPressed = false; //왼쪽버튼 토글
var isSpace = false; //스페이스 입력 토글
var isMove = false; //게임중 여부
var isEsc = false; //ESC버튼 입력토글

var img = new Image();
img.src = 'img/brick.jpg';
var ptrn; //벽돌패턴
img.onload = function () {
    // 패턴을 생성한다
    ptrn = ctx.createPattern(img, 'repeat');
}

//키보드를 누른경우, 키보드를 뗀 경우, 마우스를 움직인 경우의 이벤트핸들러를 연결
document.addEventListener("keydown", keyDownHandler, false);
document.addEventListener("keyup", keyUpHandler, false);
document.addEventListener("mousemove", mouseMoveHandler, false);

//벽돌의 status : 0-제거된 벽돌 1-일반벽돌 2-2중벽돌 3-주변벽돌도 같이 깨지는 벽돌 4-공의 크기가 커지는 벽돌
var bricks = []; //벽돌배열을 초기화 해준다
for (r = 0; r < brickRowCount; r++) {
    bricks[r] = []; //벽돌배열의 방에 다시 배열을 넣어 2차원배열의 형태로 만들어 준다
    for (c = 0; c < brickColumnCount; c++) {
        bricks[r][c] = { x: 0, y: 0, status: 1 }; //배열의 각 방네 x,y값을 0으로 초기화 하고 상태를 1로해준다.
    }
}
for (i = 0; i < brickDouble; i++) {
    var num = Math.floor(Math.random() * (brickRowCount * brickColumnCount)); //Math.random()을 통해 랜덤하게 값을 정한다.
    var r = Math.floor(num / brickColumnCount);
    var c = num % brickColumnCount;
    if (bricks[r][c].status == 1) {
        bricks[r][c].status = 2; //랜덤하게 선택된 블럭이 1번상태이면 2번 벽돌로 변경해준다.
    } else {
        i--;
    }
}
for (i = 0; i < brickCombo; i++) {
    var num = Math.floor(Math.random() * ((brickRowCount - 2) * (brickColumnCount - 2)));
    var r = Math.floor(num / (brickColumnCount-2)) + 1;
    var c = num % (brickColumnCount-2) + 1;
    if (bricks[r][c].status == 1) {
        bricks[r][c].status = 3; //랜덤하게 선택된 블럭이 1번상태이면 3번 벽돌로 변경해준다.
    } else {
       i--;
    }
}
for (i = 0; i < brickBig; i++) {
    var num = Math.floor(Math.random() * (brickRowCount * brickColumnCount));
    var r = Math.floor(num / brickColumnCount);
    var c = num % brickColumnCount;
    if (bricks[r][c].status == 1) {
        bricks[r][c].status = 4; //랜덤하게 선택된 블럭이 1번상태이면 4번 벽돌로 변경해준다.
    } else {
        i--;
    }
}

//마우스 이동 핸들러
function mouseMoveHandler(e) {
    var relativeX = e.clientX - canvas.offsetLeft;
    if (relativeX - paddleWidth / 2 > 0 && relativeX < canvas.width - paddleWidth / 2) {
        paddleX = relativeX - paddleWidth / 2;
    }
}

//키보드를 누를 때 핸들러
function keyDownHandler(e) {
    if (e.keyCode == 39) {
        rightPressed = true;
    } else if (e.keyCode == 37) {
        leftPressed = true;
    }
    else if (e.keyCode == 32) {
        isSpace = true;
    }
    else if (e.keyCode == 27) {
        isEsc = true;
    }
}

//키보드를 뗄 때 핸들러
function keyUpHandler(e) {
    if (e.keyCode == 39) {
        rightPressed = false;
    } else if (e.keyCode == 37) {
        leftPressed = false;
    }
    else if (e.keyCode == 32) {
        isSpace = false;
    }
    else if (e.keyCode == 27) {
        isEsc = false;
    }
}

//캔버스 화면에 점수를 그려준다
function drawScore() {
    ctx.font = "16px Arial";
    ctx.fillStyle = "#000000";
    ctx.textBaseline = "bottom";
    ctx.textAlign = "left";
    ctx.fillText("점수: " + score, 8, 20);
}

//속도가 빨라지거나 hidden item을 깬 경우 화면에 글자 표시
function drawText() {
    ctx.font = "50px Arial";
    ctx.fillStyle = "#000000";
    ctx.textBaseline = "middle";
    ctx.textAlign = "center";
    ctx.fillText(showText, canvas.width / 2, canvas.height / 2);
    showTextTimer++;
    showTextTimer = showTextTimer % showTextInterval;
}

function collisionDetection() {
    var total = 0;
    for (r = 0; r < brickRowCount; r++) {
        for (c = 0; c < brickColumnCount; c++) {
            var b = bricks[r][c];
            //일반벽돌을 깬 경우
            if (b.status == 1) {
                if (x > b.x && x < b.x + brickWidth && y > b.y && y < b.y + brickHeight) {
                    dy = -dy;
                    b.status = 0;
                    score += 100;
                }
            }
            //2중벽돌을 깬 경우
            else if (b.status == 2) {
                if (x > b.x && x < b.x + brickWidth && y > b.y && y < b.y + brickHeight) {
                    dy = -dy;
                    b.status = 1;
                    score += 100;
                }
            }
            //폭탄벽돌을 깬 경우
            else if (b.status == 3) {
                if (x > b.x && x < b.x + brickWidth && y > b.y && y < b.y + brickHeight) {
                    b.status = 0;
                    bricks[r - 1][c - 1].status = 0;
                    bricks[r - 1][c].status = 0;
                    bricks[r - 1][c + 1].status = 0;
                    bricks[r][c - 1].status = 0;
                    bricks[r][c + 1].status = 0;
                    bricks[r + 1][c - 1].status = 0;
                    bricks[r + 1][c].status = 0;
                    bricks[r + 1][c + 1].status = 0;
                    showText = "BOOM!";
                    drawText();
                    dy = -dy;
                    paddleWidth -= 5;
                    score += 1000;
                }
            }
            //sizeup 벽돌을 깬 경우
            else if (b.status == 4) {
                if (x > b.x && x < b.x + brickWidth && y > b.y && y < b.y + brickHeight) {
                    b.status = 0;
                    ballRadius += 2;
                    dy = -dy;
                    paddleWidth -= 10;
                    score += 500;
                    showText = "Size UP!";
                    drawText();
                }
            }
            else
                total++;
        }
    }
    //벽돌이 모두 제거된 경우
    if (total == brickRowCount * brickColumnCount) {
        alert("성공!!");
        document.location.reload(); //화면새로고침
    }
}

//화면에 벽돌을 그려준다
function drawBricks() {
    for (r = 0; r < brickRowCount; r++) {
        for (c = 0; c < brickColumnCount; c++) {
            if (bricks[r][c].status == 1) { //상태가 1인 벽돌
                var brickX = (c * (brickWidth + brickPadding)) + brickOffsetLeft;
                var brickY = (r * (brickHeight + brickPadding)) + brickOffsetTop;
                bricks[r][c].x = brickX;
                bricks[r][c].y = brickY;
                
                //최상단에서 설정한 벽돌사진 패턴을 이용한 사각형으로 그린다.
                ctx.beginPath();
                ctx.fillStyle = ptrn;
                ctx.fillRect(brickX, brickY, brickWidth, brickHeight);
                ctx.fill();
                ctx.closePath();
            }
            else if (bricks[r][c].status == 2) { //상태가 2인벽돌
                var brickX = (c * (brickWidth + brickPadding)) + brickOffsetLeft;
                var brickY = (r * (brickHeight + brickPadding)) + brickOffsetTop;
                bricks[r][c].x = brickX;
                bricks[r][c].y = brickY;

                //회색으로 색을 채운 벽돌을 사각형으로 그린다
                ctx.beginPath();
                ctx.rect(brickX, brickY, brickWidth, brickHeight);
                ctx.fillStyle = 'gray';
                ctx.fill();
                ctx.closePath();
            }
            else if (bricks[r][c].status == 3 || bricks[r][c].status == 4) { //상태가 3이거나 4인 벽돌
                var brickX = (c * (brickWidth + brickPadding)) + brickOffsetLeft;
                var brickY = (r * (brickHeight + brickPadding)) + brickOffsetTop;
                bricks[r][c].x = brickX;
                bricks[r][c].y = brickY;

                //rowcolors의 배열 값을 계속해서 반복하여 나타내준다(여러가지 색으로 계속변함) 
                ctx.beginPath();
                ctx.rect(brickX, brickY, brickWidth, brickHeight);
                ctx.fillStyle = rowcolors[Math.floor(switchcolor / intervalcolor)];
                switchcolor++;
                switchcolor = switchcolor % (intervalcolor * 8);
                ctx.fill();
                //벽돌의 가운데 Hidden item이라는 글자를 써준다.
                ctx.font = "12px Arial";
                ctx.fillStyle = "#000000";
                ctx.textBaseline = "middle";
                ctx.textAlign = "center";
                ctx.fillText("Hidden item", brickX + brickWidth / 2, brickY + brickHeight / 2);

                ctx.closePath();
            }
        }
    }
}

//공을 그림
function drawBall() {
    ctx.beginPath();
    var ballgra = ctx.createRadialGradient(x+ballRadius/4,y-ballRadius/4,0.5,x,y,ballRadius);
    ballgra.addColorStop(0,'white');
    ballgra.addColorStop(1,'black');
    ctx.arc(x, y, ballRadius, 0, Math.PI * 2);
    ctx.fillStyle = ballgra;
    ctx.fill();
    ctx.closePath();
}

//아래쪽에 공을 튀겨내는 막대를 그림
function drawPaddle() {
    ctx.beginPath();
    ctx.lineWidth = paddleHeight;
    ctx.lineCap = 'round';
    ctx.moveTo(paddleX, canvas.height - paddleHeight/2);
    ctx.lineTo(paddleX + paddleWidth, canvas.height - paddleHeight / 2);
    ctx.strokeStyle = "#000000";
    ctx.stroke();
    ctx.closePath();
}

//게임을 시작하기전 초기설정
function set() {
    x = paddleX + paddleWidth / 2;
    y = canvas.height - 20;
}

//화면에 각종요소를 그리는 함수를 호출하고 각종이벤트를 관리하는 함수
//일종의 메인 함수
function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height); //캔버스 전체 초기화
    if (!isMove) set(); //게임중(공이움직이는 중)이 아니면 공의 좌표를 막대의 가운데로
    drawBricks(); //벽돌을 화면에 그림
    drawBall(); //공의 위치를 그림
    drawPaddle(); //막대를 그림
    drawScore(); //점수현황을 그려줌
    collisionDetection(); //공의 위치가 벽과 닿았는지 판단 및 제거

    if (showTextTimer != 0) drawText();
    if (isSpace) isMove = true; //스페이스 입력시 게임중으로 상황변경
    if (isEsc) {
        alert("일시정지");
        isEsc = false;
    }
    if (isMove) { //게임중이라면 공의 좌표를 dx,dy만큼 변경
        x += dx;
        y += dy;
    }

    //점수가 올라가면 속도가 올라가도록 함
    if (level == 3 && score > 15000) {
        dx < 0 ? dx = -6 : dx = 6;
        dy < 0 ? dy = -6 : dy = 6;
        level++;
        showText = "Speed UP!";
        drawText();
    } else if (level == 2 && score > 10000) {
        dx < 0 ? dx = -5 : dx = 5;
        dy < 0 ? dy = -5 : dy = 5;
        level++;
        showText = "Speed UP!";
        drawText();
    } else if (level==1 && score > 5000) {
        dx < 0 ? dx = -4 : dx = 4;
        dy < 0 ? dy = -4 : dy = 4;
        level++;
        showText = "Speed UP!";
        drawText();
    }

    if (x > canvas.width - ballRadius - 1 || x < ballRadius + 1) {
        dx = -dx; //공이 오른쪽 또는 왼쪽 벽에 닿은경우
    }
    if (y < ballRadius + 1) {
        dy = -dy; //공이 위쪽 벽에 닿았을경우
    }
    else if (y > canvas.height - ballRadius / 2) { //공이 아래쪽에 닿고
        if (x > paddleX - ballRadius / 2 && x < paddleX + paddleWidth + ballRadius / 2) { //막대에 닿은경우
            if (x < paddleX + paddleWidth / 3) { // 막대의 왼쪽의 1/3 구간
                if (dx > 0) dx = -dx;
                dy = -dy;
            }
            else if (x > paddleX + paddleWidth - paddleWidth / 2) { //막대의 오른쪽의 1/2 구간
                if (dx < 0) dx = -dx;
                dy = -dy;
            }
            else { //나머지 구간
                dy = -dy;
            }
        }
        else { //막대에 닿지 않은경우
            Life--; //남은기회 1감소
            if (!Life) { //남은기회 0일경우
                alert("Game Over!");
                document.location.reload();
            }
            else {
                set(); //초기화
                dx = 5;
                dy = -5;
                ballRadius = 10; //공 지름 초기화
                paddleWidth = 100; //막대 길이 초기화
                isMove = false; //게임대기상태 전환
            }
        }
    }
    //방향키를 눌렀을때 막대의 위치를 이동
    if (rightPressed && paddleX < canvas.width - paddleWidth) {
        paddleX += 10;
    } else if (leftPressed && paddleX > 0) {
        paddleX -= 10;
    }
    requestAnimationFrame(draw); //draw함수를 계속해서 호출시켜준다
}

draw(); //draw함수 호출
