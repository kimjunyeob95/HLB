<?php
/**
 * 개인정보 신청 메일발송
 **/
function sendmail_essInfo($coperation,$name,$email,$type){
    $to_name =  "[".$coperation."] 인사정보 수정이 요청되었습니다.";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        성명 : '.$mm_name.'
                    </p>
                    <p style="margin-top:32px">
                        정보수정구분 : '.$type.'
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 개인정보 승인/반려 메일발송
 **/
function sendmail_memberInfo($coperation,$name,$email,$type,$result){
    $to_name =  "[".$coperation."] 인사정보 수정 요청이 '.$result.'되었습니다.";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        성명 : '.$mm_name.'
                    </p>
                    <p style="margin-top:32px">
                        정보수정구분 : '.$type.'
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * ess휴가 신청 메일발송
 **/
function sendmail_essHoliday($coperation,$name,$email,$holiday,$reason){
    $to_name =  "[".$coperation."] 근태신청이 접수되었습니다.";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        성명 : '.$mm_name.'
                    </p>
                    <p style="margin-top:32px">
                        신청기간 : '.$holiday.'
                    </p>
                    <p style="margin-top:32px">
                        사유 : '.$reason.'
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * mss휴가 승인/반려 메일발송
 **/
// 법인명 , 반려인 , 반려인이메일 , 결과 , 기간 , 사유
function sendmail_mssHoliday($coperation,$name,$email,$result,$holiday,$reason){
    $to_name =  "[".$coperation."] 근태신청이 ".$result."되었습니다.";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        성명 : '.$mm_name.'
                    </p>
                    <p style="margin-top:32px">
                        신청기간 : '.$holiday.'
                    </p>
                    <p style="margin-top:32px">
                        사유 : '.$reason.'
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 비밀번호 초기화 처리 시
 **/
function sendmail_pwReset($coperation,$name,$email,$reset_password){
    $to_name =  "[".$coperation."] 비밀번호 전달";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        '.$mm_name.'님의 비밀번호가 ['.$reset_password.']으로 초기화되었습니다.<br>
                        개인정보 보호를 위하여 로그인 후 바로 변경해주세요.
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 비밀번호 변경
 **/
function sendmail_pwChange($coperation,$name,$email){
    $to_name =  "[".$coperation."] 비밀번호 변경";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        '.$mm_name.'님의 비밀번호가 변경되었습니다.<br>
                        만약 본인이 변경하지 않았을 경우, 인사담당자에게<br>
                        초기화를 요청해주세요.
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 신규입사자 등록
 **/
function sendmail_newReg($coperation,$name,$email,$mccode){
    $to_name =  "[".$coperation."] 신규입사자 등록";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        '.$coperation.' 인사시스템 이용을 위하여, 아래의 사이트로 접속하여 정보를 입력해주세요.<br>
                        주소 : <a href="http://58.224.253.9/new/">이동</a>
                    </p>
                    <p style="margin-top:32px">
                        성명 : '.$mm_name.'
                    </p>
                    <p style="margin-top:32px">
                        사번 : '.$mccode.'
                    </p>
                    <p style="margin-top:32px">
                        이메일 : '.$to_email.'
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 신규입사자 승인
 **/
function sendmail_newYes($coperation,$name,$email,$mccode,$password){
    $to_name =  "[".$coperation."] 신규입사자 승인";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        '.$coperation.' 인사시스템 등록이 완료되었습니다.<br>
                        다음의 정보로 로그인이 가능합니다.
                        주소 : <a href="http://58.224.253.9/">이동</a>
                    </p>
                    <p style="margin-top:32px">
                        성명 : '.$mm_name.'
                    </p>
                    <p style="margin-top:32px">
                        법인 : '.$coperation.'
                    </p>
                    <p style="margin-top:32px">
                        사번 : '.$mccode.'
                    </p>
                    <p style="margin-top:32px">
                        비밀번호 : '.$password.'
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}
/**
 * 신규입사자 반려
 **/
function sendmail_newNo($coperation,$name,$email){
    $to_name =  "[".$coperation."] 신규입사자 반려";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
            <tr>
                <td>
                    <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                </td>
            </tr>
            <tr>
                <td style="padding:32px 20px;font-size:20px;text-align:center;">
                    <p>
                        '.$coperation.' 인사시스템 등록이 반려되었습니다.<br>
                        정보수정 후 재제출 바랍니다.
                    </p>
                    <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                </td>
            </tr>
        </table>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 공지사항 등록
 **/
function sendmail_regNotice($coperation,$email,$tn_title){
    $to_name =  "[".$coperation."] 법인 공지사항";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $coperation;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">

        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>'.$coperation.'</title>
        </head>

        <body>

            <table cellpadding="0" cellspacing="0" style="width:100%;min-width:300px;max-width:720px;margin:0 auto;border:0 none;">
                <tr>
                    <td>
                        <div style="display:block;width:100%;height:3px;background: -moz-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: -webkit-linear-gradient(right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);background: linear-gradient(to right,  rgba(32,156,255,1) 0%, rgba(104,224,207,1) 100%);">&nbsp;</div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px 20px;font-size:20px;text-align:center;">
                        <p>
                        '.$tn_title.' 공지사항이 등록되었습니다.
                        </p>
                        <p style="text-align:right;margin-top:40px;">감사합니다.<Br>By '.$coperation.'</p>
                    </td>
                </tr>
            </table>
        </body>
        </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}
?>