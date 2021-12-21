<?php
	header("Content-Type: text/html; charset=UTF-8");
	//include_once $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';

	function filter_email_address($email) {  
		 $email = filter_var($email, FILTER_SANITIZE_EMAIL);  
	  
		 if(filter_var($email, FILTER_VALIDATE_EMAIL))   
			 return TRUE;  
		 else  
			return FALSE;  
	}  

	function send_mail($TO_NAME, $TO_ADDR, $TITLE, $CONTENT, $CC, $BCC, $TO_FROM='에이치엘비') {
		$result = array('code'=>'false', 'msg'=>'');
		//이메일 정규식
		if(filter_email_address($TO_ADDR) == false) {  
			$result['msg']='이메일 형식이 잘못되었습니다.';
			return json_encode($result, JSON_UNESCAPED_UNICODE);
		}


		if(empty($TITLE) || empty($CONTENT)) {
			 $result = array('code'=>'false', 'msg'=>'제목 OR 내용이 없습니다.');
			return json_encode($result, JSON_UNESCAPED_UNICODE);
		}

		if(empty($TO_NAME) || empty($TO_ADDR)) {
			 $result = array('code'=>'false', 'msg'=>'메일 수신 대상이 없습니다.');
			return json_encode($result, JSON_UNESCAPED_UNICODE);
		}
		

		$nameFrom = $TO_FROM;
		$mailFrom = "hrms@hlb-group.com"; //해당 도메인설정
		$nameTo = $TO_NAME;
		$mailTo = $TO_ADDR;
		// $cc = "참조";
		// $bcc = "숨은참조";
		$subject = $TITLE;
		$content = $CONTENT;


		$charset = "UTF-8";
		$nameFrom = "=?$charset?B?" . base64_encode($nameFrom) . "?=";
		$nameTo = "=?$charset?B?" . base64_encode($nameTo) . "?=";
		$subject = "=?$charset?B?" . base64_encode($subject) . "?=";
		$header = "Content-Type: text/html; charset=utf-8\r\n";
		$header.= "MIME-Version: 1.0\r\n";
		$header.= "Return-Path: <" . $mailFrom . ">\r\n";
		$header.= "From: 인사시스템  <" . $mailFrom . ">\r\n";
		$header.= "Reply-To: <" . $mailFrom . ">\r\n";

		if ($CC) $header.= "Cc: " . $CC . "\r\n";
		if ($BCC) $header.= "Bcc: " . $BCC . "\r\n";
		
		$result = mail($mailTo, $subject, $content, $header, $mailFrom);
		// echo('<pre>');print_r($result);echo('</pre>');exit;
		if (!$result) {
			$result = array('code'=>'false', 'msg'=>'전송실패');
		} else {
			$result = array('code'=>'true', 'msg'=>'전송성공');
		}
        
		// echo json_encode($result); //
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}

?>
