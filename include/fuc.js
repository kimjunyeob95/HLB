function move_detail(seq,subquery){
    location.href = '/ess/noticeDetail?seq='+seq+subquery;
}
function move_hr_detail(seq,subquery){
    location.href = '/ess/hrnoticeDetail?seq='+seq+subquery;
}
function move_member_detail(seq,subquery){
    location.href = '/hass/employeacceptDetail?seq='+seq+subquery;
}



function new_step_info(type){
    if(type=='1'){
        location.href="/new/recruitStep01";
    }else if(type=='2'){
        location.href="/new/recruitStep02";
    }else if(type=='3'){
        location.href="/new/recruitStep03";
    }else if(type=='4'){
        location.href="/new/recruitStep04";
    }else{
        location.href="/new/recruitConfirm01";

    }
}
function chk_eng(object){
    var REG_alpha = /^[A-Za-z0-9]*$/ ;
    if(REG_alpha.test(object.value) == false ){
        object.value = object.value.slice(0, object.maxLength);
    }
}
function chk_num(object){
    var REG_alpha = /^[0-9]*$/ ;
    if(REG_alpha.test(object.value) == false ){
        object.value = object.value.slice(0, object.maxLength);
    }
}
function chk_email(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}
//내국인 주민등록번호유효성검사
function chk_fnrrn(rrn)
{
    var sum = 0;
    if (rrn.length != 13) {
        return false;
    } else if (rrn.substr(6, 1) != 1 && rrn.substr(6, 1) != 2 && rrn.substr(6, 1) != 3 && rrn.substr(6, 1) != 4) {
        return false;
    }

    for (var i = 0; i < 12; i++) {
        sum += Number(rrn.substr(i, 1)) * ((i % 8) + 2);
    }
    if (((11 - (sum % 11)) % 10) == Number(rrn.substr(12, 1))) {
        return true;
    }
    return false;
}

//외국인 주민등록번호유효성검사.
function chk_fnfgn(rrn)
{
    var sum = 0;
    if (rrn.length != 13) {
        return false;
    } else if (rrn.substr(6, 1) != 5 && rrn.substr(6, 1) != 6 && rrn.substr(6, 1) != 7 && rrn.substr(6, 1) != 8) {
        return false;
    }

    if (Number(rrn.substr(7, 2)) % 2 != 0) {
        return false;
    }
    for (var i = 0; i < 12; i++) {
        sum += Number(rrn.substr(i, 1)) * ((i % 8) + 2);
    }
    if ((((11 - (sum % 11)) % 10 + 2) % 10) == Number(rrn.substr(12, 1))) {
        return true;
    }
    return false;
}

function chk_compare(val1,val2){
    if(va1==val2){
        return true;
    }else{
        return false;
    }
}

function maxLengthCheck(object){
    if (object.value.length > object.maxLength){
        object.value = object.value.slice(0, object.maxLength);
    }
}
/**
 * 더블킬릭방지 사용방법
 * 클릭 1회 시작후
 * if(doubleSubmitCheck()) return false;
 * 작업이 끝난후
 * doubleSubmitFlag = false;
 */
var doubleSubmitFlag = false;
function doubleSubmitCheck(){
    if(doubleSubmitFlag){
        return doubleSubmitFlag;
    }else{
        doubleSubmitFlag = true;
        return false;
    }
}
