<?
  include 'accessDB.php'; //DB의 접속을 위해서 불러옴

  $conn = getDB(); //DB객체 받아옴

  extract($_REQUEST); //배열형태의 _REQUEST변수를 인덱스명으로 쓸 수 있도록 해줌

  $query= "select * from cs_wiki_img where title='$title'" ; //저장된 DB중 제목이 일치하는 DB를 불러오는 쿼리문
  $result=mysqli_query($conn, $query); //쿼리문실행
  $row=mysqli_fetch_assoc($result); //실행결과중 첫번째열 배열형태로 반환
  Header("Content-type:  image/".$row["extension"]); //해당문서의 컨텐츠 타입을 이미지/저장된 확장자로 설정
  //컨텐츠가 브라우저에서 바로 처리되도록 하고, 파일의 이름을 title.확장자 형태로 설정
  Header("Content-Disposition: inline; filename=".$row["title"].".".$row["extension"]);
  echo $row["data"]; //이진 바이너리데이터 출력
 ?>
