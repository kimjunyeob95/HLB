<?

header("Content-Type: text/html; charset=UTF-8");
include $_SERVER['DOCUMENT_ROOT'].'/manage/include/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/manage/include/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/var.php';

include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/orgmanage_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/info_model.php';

session_start();
if(empty($_SESSION['admin_info'])){
	page_move('/manage/login.php');
	exit;
}

//만 나이 계산
function getManNai($data){
    $birth_year = substr((int)$data['mm_birth'],0,4);
    $birth_month = substr((int)$data['mm_birth'],5,2);
    $brith_day = substr((int)$data['mm_birth'],8,2);

    $now_year = date("Y");
    $now_month = date("m");
    $now_day = date("d");

    if($birth_month < $now_month){
       $age = $now_year - $birth_year;
    }else if($birth_month == $now_month){
     if($brith_day <= $now_day)
      $age = $now_year - $birth_year;
     else
      $age = $now_year - $birth_year -1;
    }else{
       $age = $now_year - $birth_year-1;
    }
    return $age;
}

$db=db_con($_cfg['dsn']);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$db->exec("set names utf8");


@$mc_coseq = $_REQUEST['mc_coseq'];
@$mmseq = $_REQUEST['mmseq'];
$enc = new encryption();

if(empty($mc_coseq) || empty($mmseq)){
	page_move('../main.php','잘못된 접근입니다.');exit;
}

$query="SELECT * FROM tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq WHERE co_is_del='FALSE' ORDER BY co_seq asc ";
$ps2 = pdo_query($db, $query, array());
$coperationList = array();
while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
	array_push($coperationList, $data2);
}

$member_info = get_member_info_admin($db,$mmseq,$mc_coseq); // 개인정보    
$evaluation_list = get_evaluation_list_admin($db,$mmseq); // 인사평가
$group_list = get_group_list_admin_all($db,$mc_coseq); //부서
$education_list = get_education_list_admin($db,$mmseq); // 학력
$certificate_list = get_certificate_list_admin($db,$mmseq); // 어학 / 자격증

$member_group_list = get_member_group_admin($db,$mmseq,$mc_coseq);

$position_list = get_position_list_admin($db,1,$mc_coseq); //직책
$position_list2 = get_position_list_admin($db,2,$mc_coseq); //직위
$position_list3 = get_position_list_admin($db,3,$mc_coseq); //직군
$position_list4 = get_position_list_admin($db,4,$mc_coseq); //고용
$position_list5 = get_position_list_admin($db,5,$mc_coseq); //사원
$list = get_group_list_all_v3($db,$mc_coseq);

$query = "select count(*) as cnt from ess_member_code em join tbl_ess_group  te on em.mc_group = te.tg_seq where tg_coseq = {$mc_coseq} and mc_coseq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$data = $ps ->fetch(PDO::FETCH_ASSOC);
$group_cnt = $data['cnt'];
$group_check_list = array();
foreach ($member_group_list as $val){
    array_push($group_check_list, $val['tg_seq']);
}
function has_children($rows,$id) {
    foreach ($rows as $row) {
        if ($row['tg_parent_seq'] == $id)
            return true;
    }
    return false;
}
function group_check($seq){
    global $group_check_list;
    if(in_array($seq,$group_check_list)){
        return 'checked';
    }
}

//총근속
$date = date('Y-m-d', (strtotime('- 1 month', strtotime($member_info['mc_regdate']))));
$datetime1 = new DateTime($date);
$datetime2 = new DateTime(date('Y-m-d'));
$regdate_interval = $datetime1->diff($datetime2);

?>
    <div class="col-md-1 center ajax-table">
        <table class="table table-bordered table-striped table-hover mb-none go-detail" style="table-layout:fixed " onClick="detail_page(<?=$member_info['mc_coseq']?>,<?=$member_info['mmseq']?>);">
            <tbody>
                <tr colspan=3>
                    <th colspan=3 style="text-align: center !important; height: 150px; width: 150px;">
                        <img src="<?=$member_info['mm_profile']?>" width="100" height="130">
                    </th>
                </tr>
                <tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;"><?=$enc->decrypt($member_info['mm_name'])?>(<?=$member_info['mc_code']?>)</th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;">만 <?=getManNai($member_info)?>세</th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;">
                            <?for($i2=0;$i2<sizeof($coperationList);$i2++){?>
                                <?if($member_info['mc_coseq']==$coperationList[$i2]['co_seq']){?><?=$coperationList[$i2]['co_name']?> <?if($coperationList[$i2]['co_subname']!=""){?> ( <?=$coperationList[$i2]['co_subname']?> )<?}?><?}?>
                            <?}?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;"><?=$education_list[0]['me_name']?> <?=$education_list[0]['me_major']?></th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 80px;">
                            <?foreach ($member_group_list as $val){?>
                                <?=$val['tg_title']?><br>
                            <?}?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;">
                            총 근속 <?=$regdate_interval->format('%y')?>년 <?=$regdate_interval->format('%m')?>개월
                        </th>
                    </tr>   
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;">
                            <?foreach ($position_list2 as $val){?>
                                <?if($val['tp_seq']==$member_info['mc_position2']){echo ''.$val['tp_title'];}?>
                            <?}?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 60px;">
                            <?foreach ($position_list as $val){?>
                                <?if($val['tp_seq']==$member_info['mc_position']){echo ''.$val['tp_title'];}?>
                            <?}?>
                        </th>
                    </tr>
                    <tr>
                        <th colspan=3 style="text-align: center !important; height: 100px;">
                            <?foreach ($certificate_list as $val){?>
                                <?=$val['mct_cert_name']?><br>
                            <?}?>
                        </th>
                    </tr>
                </tr>
                <tr>
                    <th colspan=3 style="text-align: center !important; height: 50px;"><?=substr($member_info['mc_affiliate_date'],0,10)?> / <br><?=substr($member_info['mc_regdate'],0,10)?></th>
                </tr>
                <tr>
                    <th colspan=3 style="text-align: center !important; height: 50px;"><?=$member_info['mc_job']?></th>
                </tr>
                <tr>
                    <th colspan=3 style="text-align: center !important; height: 50px;">
                        <?foreach ($position_list3 as $val){?>
                            <?if($val['tp_seq']==$member_info['mc_position3']){echo ''.$val['tp_title'];}?>
                        <?}?>
                    </th>
                </tr>
                <tr>
                    <tr>
                        <?for($i = 0; $i<3; $i++){?>
                            <th style="text-align: center !important; height: 31px;"><?=$evaluation_list[$i]['me_year']?></th>
                        <?}?>
                    </tr>
                    <tr>
                        <?for($i = 0; $i<3; $i++){?>
                            <th style="text-align: center !important; height: 31px;">
                                <?foreach($evaluation_class_array as $key => $val2){?>
                                    <?if($key==$evaluation_list[$i]['me_class_1']){echo $val2;};?>
                                <?}?>
                            </th>
                        <?}?>
                    </tr>
                    <tr>
                        <?for($i = 0; $i<3; $i++){?>
                            <th style="text-align: center !important; height: 31px;">
                                <?foreach($evaluation_class_array as $key => $val2){?>
                                    <?if($key==$evaluation_list[$i]['me_class_2']){echo $val2;};?>
                                <?}?>
                            </th>
                        <?}?>
                    </tr>
                </tr>
            </tbody>
        </table>
    </div>