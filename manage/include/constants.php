<?php
/**
 * 상수 선언
 */

// DB 공통상태 상수
defined('COMMON_STATUS_NORMAL')  OR define('COMMON_STATUS_NORMAL', 0);
defined('COMMON_STATUS_NOT_USE')  OR define('COMMON_STATUS_NOT_USE', 1);
defined('COMMON_STATUS_DELETED')  OR define('COMMON_STATUS_DELETED', 3);



// 관리자 데이터 상태 (정상:0, 1:잠금, 삭제:3)
defined('ADM_STATUS_NORMAL')  OR define('ADM_STATUS_NORMAL', COMMON_STATUS_NORMAL);
defined('ADM_STATUS_LOCKED')  OR define('ADM_STATUS_LOCKED', COMMON_STATUS_NOT_USE);
defined('ADM_STATUS_DELETED')  OR define('ADM_STATUS_DELETED', COMMON_STATUS_DELETED);


// 기업 데이터 상태 (정상:0, 사용안함:1, 삭제:3)
defined('COMP_STATUS_NORMAL')  OR define('COMP_STATUS_NORMAL', COMMON_STATUS_NORMAL);
defined('COMP_STATUS_NOT_USED')  OR define('COMP_STATUS_NOT_USED', COMMON_STATUS_NOT_USE);
defined('COMP_STATUS_DELETED')  OR define('COMP_STATUS_DELETED', COMMON_STATUS_DELETED);

// 제보 유형 제보유형(불법 금품수수 및 공금 횡령:1, 불법노사 관계:2, 직장내 괴롭힘, 성희롱:3, 불공정거래:4, 기타 윤리지침 등을 포함한 사내 규정 위반사항:5)
defined('REPORT_TYPE_MONEY')  OR define('REPORT_TYPE_MONEY', 1);
defined('REPORT_TYPE_ILLEGAL_LABOR')  OR define('REPORT_TYPE_ILLEGAL_LABOR', 2);
defined('REPORT_TYPE_HARRASSMENT_AND_SEXUAL')  OR define('REPORT_TYPE_HARRASSMENT_AND_SEXUAL', 3);
defined('REPORT_TYPE_UNFAIR_BUSSINESS')  OR define('REPORT_TYPE_UNFAIR_BUSSINESS', 4);
defined('REPORT_TYPE_ETC_IN_COMAPNY_RULE')  OR define('REPORT_TYPE_ETC_IN_COMAPNY_RULE', 5);

function get_report_type_str($idx) {
    $str = "";
    switch(intval($idx)) {
        case REPORT_TYPE_MONEY: {
            $str = "불법 금품수수, 재산범죄";
        }break;
        case REPORT_TYPE_ILLEGAL_LABOR: {
            $str = "노동문제, 인사비리";
        }break;
        case REPORT_TYPE_HARRASSMENT_AND_SEXUAL: {
            $str = "직장내 괴롭힘, 성희롱";
        }break;
        case REPORT_TYPE_UNFAIR_BUSSINESS: {
            $str = "불공정거래, 기밀유출";
        }break;
        case REPORT_TYPE_ETC_IN_COMAPNY_RULE: {
            $str = "기타 사내규정 위반";
        }break;
    }
    return $str;
}



// 제보 처리 진행단계(미확인:0, 담당자확인:1, 진행중:2, 종결:3)
defined('REPORT_PROCESS_NOT_VIEWD')  OR define('REPORT_PROCESS_NOT_VIEWD', 0);
defined('REPORT_PROCESS_CONFIRMED')  OR define('REPORT_PROCESS_CONFIRMED', 1);
defined('REPORT_PROCESS_ING')  OR define('REPORT_PROCESS_ING', 2);
defined('REPORT_PROCESS_COMPLETED')  OR define('REPORT_PROCESS_COMPLETED', 3);


function get_report_proccess_status_str($idx) {
    $str = "";
    switch(intval($idx)) {
        case REPORT_PROCESS_NOT_VIEWD: {
            $str = "미확인";
        }break;
        case REPORT_PROCESS_CONFIRMED: {
            $str = "담당자확인";
        }break;
        case REPORT_PROCESS_ING: {
            $str = "진행중";
        }break;
        case REPORT_PROCESS_COMPLETED: {
            $str = "종결";
        }break;
    }
    return $str;
}

defined('REPORT_BOARD_NORMAL_NOTICE_SEQ')  OR define('REPORT_BOARD_NORMAL_NOTICE_SEQ', 999999);



defined('REPLY_TYPE_REPORT_DATA')  OR define('REPLY_TYPE_REPORT_DATA', 0);
defined('REPLY_TYPE_REPORT_BOARD_DATA')  OR define('REPLY_TYPE_REPORT_BOARD_DATA', 1);





defined('CRIPTO_KEYPATH')  OR define('CRIPTO_KEYPATH', $_SERVER['DOCUMENT_ROOT']."/hotlineInfo.txt");
defined('MASKED_NAME_EXCEPT_LIST')  OR define('MASKED_NAME_EXCEPT_LIST', array('익명', '담당자', '제보자', '관리자', '최고관리자'));


?>