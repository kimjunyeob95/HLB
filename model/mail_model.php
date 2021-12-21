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
        <meta content="IE=edge" http-equiv="X-UA-Compatible" />
	    <meta content="telephone=no" name="format-detection" />
        <title>'.$coperation.'</title>
    </head>

    <body>
        <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
        <!--[if mso]>
        <style type=”text/css”>
        .fallback-text {
        font-family: Arial, sans-serif;
        }
        </style>
        <![endif]-->
        <style>
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
        </style>
        <div style="margin:0;padding:32px 60px 0;width:100px;">
            <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
        </div>
        <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

            <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

            <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
            성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.' <br>
            정보수정구분<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$type.'<br>
            주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
            </p>
            <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
                감사합니다.
            </p>
        </div>

            <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
                <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
            </div>
        </div>
    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 개인정보 승인/반려 메일발송
 **/
function sendmail_memberInfo($coperation,$name,$email,$type,$result){
    $to_name =  "[".$coperation."] 인사정보 수정 요청이 ".$result."되었습니다.";
    $to_email = $email;
    $to_from = $coperation;
    $mm_name = $name;
    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="IE=edge" http-equiv="X-UA-Compatible" />
	    <meta content="telephone=no" name="format-detection" />
        <title>'.$coperation.'</title>
    </head>

    <body>

        <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
        <!--[if mso]>
        <style type=”text/css”>
        .fallback-text {
        font-family: Arial, sans-serif;
        }
        </style>
        <![endif]-->
        <style>
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
        </style>
        <div style="margin:0;padding:32px 60px 0;width:100px;">
            <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
        </div>
        <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

            <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

            <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
            성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.' <br>
            정보수정구분<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$type.'<br>
            주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
            </p>
            <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
                감사합니다.
            </p>
        </div>

            <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
                <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
            </div>
        </div>
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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
        <!--[if mso]>
        <style type=”text/css”>
        .fallback-text {
        font-family: Arial, sans-serif;
        }
        </style>
        <![endif]-->
        <style>
            a[x-apple-data-detectors] {
                color: inherit !important;
                text-decoration: none !important;
                font-size: inherit !important;
                font-family: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
            }
        </style>
        <div style="margin:0;padding:32px 60px 0;width:100px;">
            <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
        </div>
        <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

            <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

            <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
            성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.' <br>
            신청기간<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$holiday.'<br>
            사유<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$reason.'<br>
            주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
            </p>
            <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
                감사합니다.
            </p>
        </div>

            <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
                <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
            </div>
        </div>

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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.' <br>
        신청기간<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$holiday.'<br>
        사유<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$reason.'<br>
        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>
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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        '.$mm_name.'님의 비밀번호가 ['.$reset_password.']으로 초기화되었습니다.<br>
                        개인정보 보호를 위하여 로그인 후 바로 변경해주세요.<br>
        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>

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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        '.$mm_name.'님의 비밀번호가 변경되었습니다.<br>
                        만약 본인이 변경하지 않았을 경우, 인사담당자에게<br>
                        초기화를 요청해주세요.<br>
        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>

    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 신규입사자 등록 (입사자한테)
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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        '.$coperation.' 인사시스템 이용을 위하여, 아래의 사이트로 접속하여 정보를 입력해주세요.<br>
                        주소 : <a href="https://hrms.hlb-group.com/new/">이동</a><br><br>
        성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.'<br>
        사번<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mccode.'<br>
        이메일<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$to_email.'<br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>

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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        '.$coperation.' 인사시스템 등록이 완료되었습니다.<br>
                        다음의 정보로 로그인이 가능합니다.<br>
                        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.'<br>
        법인<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$coperation.'<br>
        사번<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mccode.'<br>
        비밀번호<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$password.'<br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>
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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        '.$coperation.' 인사시스템 등록이 반려되었습니다.<br>
                        정보수정 후 재제출 바랍니다.<br>
        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>
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

        <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        '.$tn_title.' 공지사항이 등록되었습니다.<br>
        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>
        </body>
        </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}

/**
 * 신규입사자 등록 new -> hass
 **/
function sendmail_newReg_toHass($coperation,$name,$email,$mccode){
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

    <div style="width:100%;min-width:320px;max-width:1000px;margin:50px auto;overflow:hidden;box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);">
    <!--[if mso]>
    <style type=”text/css”>
    .fallback-text {
    font-family: Arial, sans-serif;
    }
    </style>
    <![endif]-->
    <style>
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }
    </style>
    <div style="margin:0;padding:32px 60px 0;width:100px;">
        <img src="https://hrms.hlb-group.com/data/logo/'.$_SESSION['mInfo']['co_logo'].'" alt="HLB" style="display:block;margin:0;width:100%;">
    </div>
    <div style="margin:0;padding:40px 70px 120px;font-family: Helvetica, Arial, Calibri, sans-serif;font-size: 16px;line-height:1.4;text-align: left;color: #555">

        <p class="fallback-text" style="font-size: 24px;text-align: left;color: #000;font-weight:bold;">'.$to_name.'</p>

        <p class="fallback-text" style="margin:32px 0 0;font-size: 16px;text-align: center;color: #555;">
        신규 입사자 등록이 요청되었습니다. 확인 바랍니다.<br>
        주소 : <a href="https://hrms.hlb-group.com/">이동</a><br><br>
        성명<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mm_name.'<br>
        사번<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$mccode.'<br>
        이메일<span style="display:inline-block;width: 1px;height: 12px;margin: 0 10px;background-color: #ccc;"></span> '.$to_email.'<br><br>
        </p>
        <p class="fallback-text" style="margin:60px 0 0;font-size: 16px;text-align: left;color: #333;font-weight:bold;">
            감사합니다.
        </p>
    </div>

        <div style="margin:0;padding:32px 60px;background:#f9f9f9;">
            <p class="fallback-text" style="margin:0;font-size: 12px;line-height:1.5;letter-spacing: .6px;text-align: left;color: #666;">Copyright 2021 HLB CO.,LTD. All Rights Reserved.</p>
        </div>
    </div>

    </body>
    </html>';
    $zzz = send_mail($mm_name, $to_email, $to_name, $html, null, null,$to_from);
}
?>
