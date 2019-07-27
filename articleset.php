<?php
////////////////////////////////////////////////////////////////////////////////////
/*php를 이용하여 CS-wiki마크업 언어로 작성된 문서를 html코드로 변환 혹은 마크업요소 제거*/
////////////////////////////////////////////////////////////////////////////////////
// Transform_text($text) : 매개변수로 받아온 값을 이용하여 위키문법에서 제외시킬 nowiki//
//                         태그를 분리하여 마크업요소를 html로 변경해 주는 함수로 넘김 //
/////////////////////////////////////////////////////////////////////////////////////
// repalce_sametoken($str,$token,$start,$end,$title=false)                         //
// : ==제목== 등과 같이 앞과 뒤가 같은 코드를 token단위로 분리하여 앞에는 start, 뒤에는 //
//   end를 붙여주고 만약 title속성이 true라면 목차생성을 위해 제목으로 입력된 값을 ol   //
//   태그를 이용해 따로 추가로 저장하여 원문과 목차문자열을 배열으로 반환한다.           //
/////////////////////////////////////////////////////////////////////////////////////
// repalce_link($str,$first_token,$mid_token,$last_token,$html)                    //
// : 앞과 뒤, 그리고 구분자가 있는 [[링크주소|노출명]]형태의 링크마크업을 html로 변환    //
/////////////////////////////////////////////////////////////////////////////////////
// repalce_img($str,$stok,$start) : [사진 사진명|속성=값] 형태의 이미지 마크업을 변환  //
/////////////////////////////////////////////////////////////////////////////////////
// repalce_ref($str) : <ref>내용</ref>형태의 각주를 부트스트랩 팝오버형태로 바꾸고     //
//                     각주의 내용을 따로 저장하여 배열형태로 반환                    //
/////////////////////////////////////////////////////////////////////////////////////
// replace_html($str) : 위에서 설명되어진 함수들을 호출하고 그 외에 간단하게 replace를 //
//                      이용하여 변횐되어지는 코드 및 표를 변환하는 작업을 수행        //
/////////////////////////////////////////////////////////////////////////////////////
// divText($text) : 검색시 문서의 개요부분만 출력하도록 하기위해 요약 부분 분리 및 반환 //
/////////////////////////////////////////////////////////////////////////////////////
// removeTag($text) : 검색시 마크업요소를 제거한 결과를 출력하기 위해 사용             //
/////////////////////////////////////////////////////////////////////////////////////

	/*마크업언어를 html로 변환하기 위해 호출 하는 함수
	(매개변수 전체 문자열, 반환값 변환된 문자열)*/
	function Transform_text($text){
		$arr_temp = explode("&lt;nowiki&gt;",$text); //전제 문자열을 <nowiki>를 기준으로 나눔
		$strtemp = $arr_temp[0]; //첫번째 nowiki시작태그 이전부분을 임시저장 문자열에 저장
		$nowiki_arr = array(); //nowiki태그 내부값 배열로 따로 저장

		for($i = 1;$i < count($arr_temp);$i++){
			 //다음 nowiki시작태그 이전의 값에서 닫는 /nowiki값을 찾아 나눔
			$divide = explode("&lt;/nowiki&gt;",$arr_temp[$i]);
			//nowiki가 들어가야할 자리에 <nowiki-순서>형태로 저장
			$strtemp = $strtemp."<nowiki-".$i.">".$divide[1];
			//nowiki 내용 배열에 저장
			$nowiki_arr[$i] = $divide[0];
		}

		$text = replace_html($strtemp); //nowiki내용이 제외된 문자열 html코드로 변환

		for($i = 1;$i < count($arr_temp);$i++){
			//html로 변횐되어진 문서에서 <nowiki-순서>부분에 해당하는 값을 교체
			$text = str_replace("<nowiki-".$i.">",str_replace("&lt;br /&gt;","<br />",$nowiki_arr[$i]),$text);
		}

		return $text; //변환되어진 전체 문자열 반환
	}

	/*앞뒤가 똑같은 마크업언어 변환(전체문자열,마크업토큰,시작html,끝html,큰제목여부)*/
	function repalce_sametoken($str,$token,$start,$end,$title=false){
		$arr_temp = explode($token,$str); //토큰을 기준으로 문자열 잘라서 배열 저장
		$strtemp = $arr_temp[0]; //첫번째 토큰이전의 값을 strtemp에 저장
		$index = ""; //인덱스를 저장할 변수 선언 및 초기화
		if(!$title){ //타이틀이 아닐경우 단순히 앞뒤 구분하여 태그 붙이기
			for($i = 1;$i < count($arr_temp);$i++){
				if($i%2==0)
					$strtemp = $strtemp.$end.$arr_temp[$i];
				else
					$strtemp = $strtemp.$start.$arr_temp[$i];
			}
		}else{ //타이틀일 경우 앞뒤 구분하여 태그 붙이고 인덱스리스트형태로 저장
			$index = '<div id@@@"index"><h2>목차</h2><ol>';
			for($i = 1;$i < count($arr_temp);$i++){
				if($i%2==0)
					$strtemp = $strtemp.$end.$arr_temp[$i];
				else{
					$strtemp = $strtemp.str_replace("##",($i+1)/2,$start).$arr_temp[$i];
					$index = $index.'<li><a href@@@"#section'.(($i+1)/2).'">'.$arr_temp[$i].'</a></li>';
				}
			}
			$index = $index."</ol></div>";
			if(count($arr_temp)<7) $index = ""; //타이틀의 개수가 3개 미만이면 인덱스 붙이지 않음
			$array_str = array($strtemp,$index); //변환된 문자열과 인덱스를 배열형태로 array_str에 저장
			$strtemp = $array_str; //strtemp에 array_str값 저장
		}
		return $strtemp; //strtemp 값 반환
	}

	/*앞뒤가 다르고 구분자가 있는 마크업언어 변환(전체문자열,시작토큰,구분자,종료토큰,교체html)*/
	function repalce_link($str,$first_token,$mid_token,$last_token,$html){
		$arr_temp = explode($first_token,$str);
		$strtemp = $arr_temp[0];
		for($i = 1;$i < count($arr_temp);$i++){
			$arr_temp2 = explode($last_token,$arr_temp[$i]);
			if(count($arr_temp2)!=0){
				$arr_temp3 = explode("$mid_token",$arr_temp2[0]);
				if($arr_temp3[1]!="") //구분자가 있는 경우
					$strtemp = $strtemp.str_replace("~~~",$arr_temp3[1],str_replace("###",$arr_temp3[0],$html)).$arr_temp2[1];
				else //구분자가 없는 경우
					$strtemp = $strtemp.str_replace("~~~",$arr_temp3[0],str_replace("###",$arr_temp3[0],$html)).$arr_temp2[1];
			}else{
				$strtemp = $strtemp.$arr_temp[$i]; //구문이 잘못된 경우
			}
		}
		return $strtemp; //변환된 문자열 반환
	}

	/*이미지 마크업언어 변환(전체문자열,시작토큰,시작html)*/
	function repalce_img($str,$stok,$start){
		$arr_temp = explode($stok,$str);
		$strtemp = $arr_temp[0];
		for($i = 1;$i < count($arr_temp);$i++){
			$arr_temp2 = explode("]",$arr_temp[$i]);
			$arr_temp2[0] = $start.$arr_temp2[0];
			$arr_temp2[0] = str_replace("|가로=","' width@@@'",$arr_temp2[0]);
			$arr_temp2[0] = str_replace("|세로=","' height@@@'",$arr_temp2[0]);
			$arr_temp2[0] = str_replace("|썸네일","' class@@@'img-thumbnail",$arr_temp2[0]);
			$strtemp = $strtemp.$arr_temp2[0]."'/>";
			unset($arr_temp2[0]);
			$strtemp = $strtemp.implode("]",$arr_temp2);
		}
		return $strtemp;
	}

	/*각주 마크업언어 변환(전체문자열)*/
	function repalce_ref($str){
		$arr_temp = explode("&lt;ref&gt;",$str);
		$strtemp = $arr_temp[0];
		$ref = "";
		for($i = 1;$i < count($arr_temp);$i++){
			$arr_temp2 = explode("&lt;/ref&gt;",$arr_temp[$i]);
			if(count($arr_temp2)==2){
				$strtemp = $strtemp.'<sup id@@@"ref_con_'.$i.'"><a tabindex@@@"0" role@@@"button" data-toggle@@@"popover" data-container@@@"body" data-placement@@@"top" data-trigger@@@"focus" data-html@@@"true" title@@@"각주" data-content@@@"'.$arr_temp2[0].'">['.$i."]</a></sup>".$arr_temp2[1];
				$ref = $ref."<a id='ref_".$i."' href@@@'#ref_con_".$i."'>[".$i."] ".$arr_temp2[0]."<br/>";
			}
			else{
				$strtemp = $strtemp.$arr_temp[$i];
			}
		}
		return array($strtemp,$ref); //변환된 문자열과 각주반환
	}

	function replace_html($str){

		//내부링크 마크업 및 외부링크 마크업 변환
		$strtemp = repalce_link($str,"[$","|","$]","<a href@@@'###'>~~~<span class@@@'glyphicon glyphicon-globe' aria-hidden@@@'true'></span></a>");
		$strtemp = repalce_link($strtemp,"[[","|","]]","<a href@@@'page.php?title@@@###'>~~~</a>");

		//내부사진 및 외부사진 마크업 변환
		$strtemp = repalce_img($strtemp,"[사진 ","<img src@@@'image.php?title@@@");
		$strtemp = repalce_img($strtemp,"[외부사진 ","<img src@@@'");

		//앞과 뒤가 같은 마크업들(제목,굵게,기울이기 등) 변환
		$strtemp = repalce_sametoken($strtemp,"====","<h4>","</h4>");
		$strtemp = repalce_sametoken($strtemp,"===","<h3>","</h3>");
		$str_arr = repalce_sametoken($strtemp,"==",'<h2 id="section##">',"</h2><hr />",true);
		$strtemp = $str_arr[0];  $index = $str_arr[1];
		$strtemp = repalce_sametoken($strtemp,"'''''","<strong><em>","</em></strong>");
		$strtemp = repalce_sametoken($strtemp,"'''","<strong>","</strong>");
		$strtemp = repalce_sametoken($strtemp,"''","<em>","</em>");

		//그외 간단한 html과 마크업과의 차이가 거의 없는 경우
		//단순str_replace로 변환
		$strtemp = str_replace("&lt;br /&gt;","<br />",$strtemp);
		$strtemp = str_replace("&lt;sup&gt;","<sup>",$strtemp);
		$strtemp = str_replace("&lt;/sup&gt;","</sup>",$strtemp);
		$strtemp = str_replace("&lt;sub&gt;","<sub>",$strtemp);
		$strtemp = str_replace("&lt;/sub&gt;","</sub>",$strtemp);
		$strtemp = str_replace("&lt;del&gt;","<s>",$strtemp);
		$strtemp = str_replace("&lt;/del&gt;","</s>",$strtemp);
		$strtemp = str_replace("&lt;under&gt;","<u>",$strtemp);
		$strtemp = str_replace("&lt;/under&gt;","</u>",$strtemp);
		$strtemp = str_replace("&lt;small&gt;","<small>",$strtemp);
		$strtemp = str_replace("&lt;/small&gt;","</small>",$strtemp);
		$strtemp = str_replace("</h3><br />","</h2>",$strtemp);
		$strtemp = str_replace("</h4><br />","</h4>",$strtemp);
		$strtemp = str_replace("&gt;인용끝","</blockquote>",$strtemp);
		$strtemp = str_replace("&gt;인용","<blockquote>",$strtemp);
		$strtemp = str_replace("<hr /><br />","<hr />",$strtemp);
		$strtemp = str_replace("{{숨기기 시작}}",'<a class@@@"btn btn-info btn-lg btn-block" data-toggle@@@"collapse" href@@@"#collapseExample" aria-expanded@@@"false" aria-controls@@@"collapseExample">펼쳐보기</a><div class@@@"collapse" id@@@"collapseExample"><div class@@@"well">',$strtemp);
		$strtemp = str_replace("{{숨기기 끝}}","</div></div>",$strtemp);

		//요약표 변환
		$arr_temp = explode("{{|",$strtemp);
		$strtemp = $arr_temp[0];
		$sum_table;
		for($i = 1;$i < count($arr_temp);$i++){
			$arr_temp2 = explode("|}}",$arr_temp[$i]);
			if(count($arr_temp2)==2){
				$arr_temp3 = explode("|",$arr_temp2[0]);
				$sum_table = "<table class@@@'data'>";
				for($j = 0;$j<count($arr_temp3);$j++){
					$sum_table = $sum_table."<tr><th>".str_replace("=","</th></tr><tr><td>",$arr_temp3[$j])."</td></tr>";
				}
				$sum_table = $sum_table."</table>";
				$strtemp = $sum_table.$strtemp.$arr_temp2[1];
			}
			else{
				$strtemp = $strtemp.$arr_temp[$i];
			}
		}

		//표 변환
		$arr_temp = explode("{|",$strtemp);
		$strtemp = $arr_temp[0];
		$tr;		$td;
		for($i = 1;$i < count($arr_temp);$i++){
			$arr_temp2 = explode("|}",$arr_temp[$i]);
			if(count($arr_temp2)==2){
				preg_match_all("/\|-(.*)<br \/>/U",$arr_temp2[0],$tr); //|-(내용)<br />에서 내용 부분을 정규식으로 추출
				$strtemp = $strtemp."<table class='table table-striped' style='width:auto;'>";
				for($j = 0;$j < count($tr[0]);$j++){
					if(mb_strpos($tr[0][$j],"!!",0,"utf-8")){
						$strtemp = $strtemp."<tr><th>".str_replace("!!","</th><th>",$tr[1][$j])."</th></tr>";
					}else{
						$strtemp = $strtemp."<tr><td>".str_replace("||","</td><td>",$tr[1][$j])."</td></tr>";
					}
				}
				$strtemp = $strtemp."</table>".$arr_temp2[1];
			}
			else{
				$strtemp = $strtemp.$arr_temp[$i];
			}
		}

		$str_arr = repalce_ref($strtemp); //각주 변환
  		$strtemp = $str_arr[0]; $ref = $str_arr[1]; //반환 받은 배열을 전체문자열은 strtemp로, 각주부분은 ref로 저장

		//각주가 빈 경우 무시
		if($ref != ""){
			//만약 문서에 각주가 들어가 위치가 표시 되어있지 않으면 문서 최하단에 자동생성
			if(mb_strpos($strtemp,"{{각주}}",0,"utf-8")==false){
				$strtemp = $strtemp."<h2 id='auto_ref'>각주</h2><hr />".$ref;
				if($index != "")
					$index = str_replace("</ol></div>","<li><a href='#auto_ref'>각주</a></li></ol></div>",$index);
			}else{
				$strtemp = str_replace("{{각주}}",$ref,$strtemp); //각주가 들어갈 위치로 표시된곳에 각주 삽입
			}
		}

		//요약부분을 p#summary로 변환
		$strtemp = str_replace("&lt;summary&gt;","<p id='summary'>",$strtemp);
		$strtemp = str_replace("&lt;/summary&gt;","</p>".$index,$strtemp);

		$strtemp = str_replace("@@@","=",$strtemp); //@@@으로 =을 대체 했던 부분들을 다시 =으로 바꾸어줌

		return $strtemp; //변환된 문자열 반환
	}

	/*입력받은 문자열을 summary부분만 반환 시켜줌 */
	function divText($text){
		$arr_temp = explode("</summary>",$text);
		$arr_temp[0] = str_replace("<summary>","",$arr_temp[0]);
		return $arr_temp;
	}

	/*검색결과호출시 마크업언어를 제거하여 출력해줌*/
	function removeTag($text){
		$text = str_replace("===","",$text);
		$text = str_replace("==","",$text);
		$text = str_replace("'''","",$text);
		$text = str_replace("<summary>","",$text);
		$text = str_replace("</summary>","",$text);
		$text = str_replace("[[","",$text);
		$text = str_replace("]]","",$text);
		$text = str_replace("[$","",$text);
		$text = str_replace("$]","",$text);
		$text = str_replace("<sup>","",$text);
		$text = str_replace("</sup>","",$text);
		$text = str_replace("<ref>","",$text);
		$text = str_replace("</ref>","",$text);
		$text = str_replace("{{각주}}","",$text);
		$text = str_replace("{{|","",$text);
		$text = str_replace("|}}","",$text);
		$text = str_replace("|","",$text);
		return $text;
	}
?>
