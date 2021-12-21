<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';

@$month = $_REQUEST['month'];
@$year = $_REQUEST['year'];
@$type = $_REQUEST['type'];
@$mm_code = $_SESSION['mInfo']['mc_code'];
@$mc_coseq = $_SESSION['mInfo']['mc_coseq'];
if(empty($month) || empty($year)){
	echo json_encode($result);
	exit;
}
$list = array();

//보여줄 급여 쿼리
$query="select * from hass_upload_excel_data hue
join (select * from hass_upload_file where hu_del='False' and hu_salaryDate_year = ? and hu_salaryDate_month = ?
order by hu_showDate desc limit 1 ) hu
on hu.hu_salaryDate_month = hue.hue_salaryDate_month and
hu.hu_salaryDate_year = hue.hue_salaryDate_year and
hu.hu_hss_code = hue.hue_hss_code and
hue.hue_del = 'False' and
hue.hue_ess_code =? and hue.hue_hss_seq=?";
$ps = pdo_query($db,$query,array($year,$month,$mm_code,$mc_coseq));
$data = $ps->fetch(PDO::FETCH_ASSOC);
if(empty($data)){
    echo 'false';exit;
}
?>
    <tr>
        <th scope="row">기본급</th>
        <td class="right"><?=number_format($data['pay01'])?></td>
        <th scope="row">수당</th>
        <td class="right"><?=number_format($data['pay02'])?></td>
        <th scope="row">국민연금</th>
        <td class="right"><?=number_format($data['deduct01'])?></td>
        <th scope="row">건강보험</th>
        <td class="right"><?=number_format($data['deduct02'])?></td>
    </tr>
    <tr>
        <th scope="row">식대</th>
        <td class="right"><?=number_format($data['pay03'])?></td>
        <th scope="row">차량보조금</th>
        <td class="right"><?=number_format($data['pay04'])?></td>
        <th scope="row">고용보험</th>
        <td class="right"><?=number_format($data['deduct03'])?></td>
        <th scope="row">장기요양보험료</th>
        <td class="right"><?=number_format($data['deduct04'])?></td>
    </tr>
    <tr>
        <th scope="row">학자금지원</th>
        <td class="right"><?=number_format($data['pay05'])?></td>
        <th scope="row">연차수당</th>
        <td class="right"><?=number_format($data['pay06'])?></td>
        <th scope="row">소득세</th>
        <td class="right"><?=number_format($data['deduct05'])?></td>
        <th scope="row">지방소득세</th>
        <td class="right"><?=number_format($data['deduct06'])?></td>
    </tr>
    <tr>
        <th scope="row">육아수당</th>
        <td class="right"><?=number_format($data['pay07'])?></td>
        <th scope="row">주말근무수당</th>
        <td class="right"><?=number_format($data['pay08'])?></td>
        <th scope="row">학자금상환액</th>
        <td class="right"><?=number_format($data['deduct07'])?></td>
        <th scope="row">건강보험 정산금</th>
        <td class="right"><?=number_format($data['deduct08'])?></td>
    </tr>
    <tr>
        <th scope="row">급여 소급액</th>
        <td class="right"><?=number_format($data['pay09'])?></td>
        <th scope="row">기타수당</th>
        <td class="right"><?=number_format($data['pay10'])?></td>
        <th scope="row">요양보험 정산금</th>
        <td class="right"><?=number_format($data['deduct09'])?></td>
        <th scope="row">국민연금 소급분</th>
        <td class="right"><?=number_format($data['deduct10'])?></td>
    </tr>
    <tr>
        <th scope="row">주식매수선택권행사이익</th>
        <td class="right"><?=number_format($data['pay11'])?></td>
        <th scope="row"></th>
        <td class="right"></td>
        <th scope="row">고용보험 정산금</th>
        <td class="right"><?=number_format($data['deduct11'])?></td>
        <th scope="row"></th>
        <td class="right"></td>
    </tr>
    <tr>
        <td class="center" colspan="4"><span class="label">지급(계)  :</span>  <?=number_format($data['pay12'])?>원</td>
        <td class="center" colspan="4"><span class="label">공제(계)  :</span>  <?=number_format($data['deduct12'])?>원</td>
    </tr>
