<?
$nationList=array();

$query="SELECT * FROM tbl_nation_code order by nationCodeKr ";
$ps = pdo_query($db,$query,array());
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
	array_push($nationList,$data);
}



function getCountryText($code){
	$nationList = $GLOBALS['nationList'];
	for($i=0;$i<sizeof($nationList);$i++){
		if($nationList[$i]['nationCode']==$code){
			return $nationList[$i]['nationCodeKr'];
		}
	}
	
}

function getGenderText($gender){
    if($gender=="M"){
        $selectTag="남성";
    }else{
        $selectTag="여성";
    }
    return $selectTag;
}

function getNationTag($code){
	$selectTag="<select name='nation'  id='mm_country' class='select'>";
	$nationList = $GLOBALS['nationList'];
	for($i=0;$i<sizeof($nationList);$i++){
		if($nationList[$i]['nationCode']==$code){
				$selectTag.="<option value='".$nationList[$i]['nationCode']."' selected>".$nationList[$i]['nationCodeKr']."</option>";
		}else{
				$selectTag.="<option value='".$nationList[$i]['nationCode']."'>".$nationList[$i]['nationCodeKr']."</option>";
		}
	}
	$selectTag.="</select>";
	return $selectTag;
}
function getNationTag_log($code){
    $selectTag="<select name='em_country'  id='em_country' class='select'>";
    $nationList = $GLOBALS['nationList'];
    for($i=0;$i<sizeof($nationList);$i++){
        if($nationList[$i]['nationCode']==$code){
            $selectTag.="<option value='".$nationList[$i]['nationCode']."' selected>".$nationList[$i]['nationCodeKr']."</option>";
        }else{
            $selectTag.="<option value='".$nationList[$i]['nationCode']."'>".$nationList[$i]['nationCodeKr']."</option>";
        }
    }
    $selectTag.="</select>";
    return $selectTag;
}

function getNationTag_v2($code,$title){
    $selectTag="<select name='mm_country'  id='mm_country' class='select' title = '".$title."'";
    $nationList = $GLOBALS['nationList'];
    for($i=0;$i<sizeof($nationList);$i++){
        if($nationList[$i]['nationCode']==$code){
            $selectTag.="<option value='".$nationList[$i]['nationCode']."' selected>".$nationList[$i]['nationCodeKr']."</option>";
        }else{
            $selectTag.="<option value='".$nationList[$i]['nationCode']."'>".$nationList[$i]['nationCodeKr']."</option>";
        }
    }
    $selectTag.="</select>";
    return $selectTag;
}

function getNationTag_v3($code,$title){
    $selectTag="<select name='mm_from'  id='mm_from' class='select' title = '".$title."'";
    $nationList = $GLOBALS['nationList'];
    for($i=0;$i<sizeof($nationList);$i++){
        if($nationList[$i]['nationCode']==$code){
            $selectTag.="<option value='".$nationList[$i]['nationCode']."' selected>".$nationList[$i]['nationCodeKr']."</option>";
        }else{
            $selectTag.="<option value='".$nationList[$i]['nationCode']."'>".$nationList[$i]['nationCodeKr']."</option>";
        }
    }
    $selectTag.="</select>";
    return $selectTag;
}

function getNationTag2($code){
	$selectTag="<select name='nation'  id='mm_from' class='select'>";
	$nationList = $GLOBALS['nationList'];
	for($i=0;$i<sizeof($nationList);$i++){
		if($nationList[$i]['nationCode']==$code){
				$selectTag.="<option value='".$nationList[$i]['nationCode']."' selected>".$nationList[$i]['nationCodeKr']."</option>";
		}else{
				$selectTag.="<option value='".$nationList[$i]['nationCode']."'>".$nationList[$i]['nationCodeKr']."</option>";
		}
	}
	$selectTag.="</select>";
	return $selectTag;
}
function getNationTag2_log($code){
    $selectTag="<select name='em_from'  id='em_from' class='select'>";
    $nationList = $GLOBALS['nationList'];
    for($i=0;$i<sizeof($nationList);$i++){
        if($nationList[$i]['nationCode']==$code){
            $selectTag.="<option value='".$nationList[$i]['nationCode']."' selected>".$nationList[$i]['nationCodeKr']."</option>";
        }else{
            $selectTag.="<option value='".$nationList[$i]['nationCode']."'>".$nationList[$i]['nationCodeKr']."</option>";
        }
    }
    $selectTag.="</select>";
    return $selectTag;
}

function getGender($gender){
	if($gender=="M"){
		return "남성";
	}else{
		return "여성";
	}
}


function getGenderTag($gender){
	$selectTag="<select name='gender' id='mm_gender'>";
	if($gender=="M"){
		$selectTag.="<option value='M' selected>남성</option>";
		$selectTag.="<option value='F'>여성</option>";
	}else{
		$selectTag.="<option value='M'>남성</option>";
		$selectTag.="<option value='F' selected>여성</option>";
	}
	
	$selectTag.="</select>";
	return $selectTag;
	
}
function getGenderTag_log($gender){
    $selectTag="<select name='em_gender' id='em_gender'>";
    if($gender=="M"){
        $selectTag.="<option value='M' selected>남성</option>";
        $selectTag.="<option value='F'>여성</option>";
    }else{
        $selectTag.="<option value='M'>남성</option>";
        $selectTag.="<option value='F' selected>여성</option>";
    }

    $selectTag.="</select>";
    return $selectTag;

}
function getGenderTag_v2($gender){
    $selectTag="<select name='mm_gender' id='mm_gender' title ='성별'>";
    if($gender=="M"){
        $selectTag.="<option value='M' selected>남성</option>";
        $selectTag.="<option value='F'>여성</option>";
    }else{
        $selectTag.="<option value='M'>남성</option>";
        $selectTag.="<option value='F' selected>여성</option>";
    }

    $selectTag.="</select>";
    return $selectTag;

}
?>