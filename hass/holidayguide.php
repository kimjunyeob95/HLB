<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/hass/hass_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/holiday_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/editor_include.php';
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mmseq = $_SESSION['mmseq'];
@$coseq = $_SESSION['mInfo']['mc_coseq'];

@$markup_text = '<div id="content" class="content-primary">
                    <h2 class="content-title">근태</h2>
                    <div class="dilligence-intro">
                        <p class="text">근무시간을 준수하고 성실하게 업무에 임하는 것은<br>
                        조직 구성원 공동의 목표를 달성하기 위한 기본 원칙입니다.<br>
                        출퇴근(지각/조퇴/외출 포함), 휴가/휴무/휴직, 출장/교육 등 <br>
                        근무상태 일체를 뜻하는 ‘근태(勤怠)’는 근무질서 확립에 영향을 미치며,<br>
                        임금 및 인력운영 등 인사부문의 기초자료로 활용되므로 정확하게 관리되어야 합니다.</p>
                        <strong>올바른 근태관리, 가장 기본적인 책임이자 의무입니다.</strong>
                    </div>
                    <h3 class="section-title">근태 유형</h3>
                    <div class="category-wrap">
                        <!-- 근무 -->
                        <div class="sort work">
                            <h4>근무</h4>
                            <div class="table-wrap">
                                <table class="data-table left">
                                    <caption>근무표</caption>
                                    <colgroup>
                                        <col style="width: 135px;">
                                        <col style="width: *">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th scope="row">정상근무</th>
                                            <td>근무, 출장, 교육등</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">특별근무</th>
                                            <td>휴일근로 등</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="sort holiday">
                            <h4>휴무</h4>
                            <div class="table-wrap">
                                <table class="data-table left">
                                    <caption>휴무표</caption>
                                    <colgroup>
                                        <col style="width: 135px;">
                                        <col style="width: *">
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th scope="row">휴(무)일</th>
                                            <td>근로자의 날, 주휴일, 공휴일 등</em>
                                        </tr>
                                        <tr>
                                            <th scope="row">휴가</th>
                                            <td>연월차휴가, 하기휴가, 생리휴가, 경조휴가, 공가 등</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">휴직</th>
                                            <td>산재휴직, 공상휴직, 상병휴직, 육아휴직,  등</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">기타</th>
                                            <td>지각, 유계결근 등</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <h3 class="section-title">근태 관리 유의사항</h3>
                    <div class="note-wrap">
                        <ul class="data-list">
                            <li class="type01">
                                <h4>임직원 개개인</h4>
                                <div>
                                    근태는 임금 및 인력운영 등 인사부문의 기초자료로 활용되는 중요한 정보입니다.<br>
                                    본인의 근태가 정확하게 입력될 수 있도록 근태 담당자에게 요청하고  시스템에 등록된 근태 내역을 <br>
                                    매일 꼼꼼하게 확인하셔야 합니다.
                                    <span>출장 · 교육 · 휴가 · 휴직 등 특이사항이 있을 경우 사전에 팀/부서 근태 담당자에게 근태 입력을 요청하시고, <br>
                                    (필요 시) 증빙자료*를 기한 내 제출해주시기 바랍니다. <br>
                                    *근태 유형에 따라 인사팀 혹은 부서 근태 담당자에게 제출 </span>
                                </div>
                            </li>
                            <li class="type02">
                                <h4>근태 담당자</h4>
                                <div>
                                    근태 담당자는 담당 팀/부서원들의 근무상태 일체(근무/휴무)를 파악하고,<br>
                                    매일의 근태 내역을 성실 · 정확 · 공정하게 시스템에 등록한 뒤,<br>
                                    근태 관리자/책임자에게 결재상신을 올리고, (필요 시) 증빙자료를 받아 별도 보관해야 합니다.
                                </div>
                            </li>
                            <li class="type03">
                                <h4>근태 책임자</h4>
                                <div>근태 책임자는 팀/부서원 전체의 근태관리를 총괄하며 관리·감독에 대한 책임을 집니다. <br>
                                일일근태, 휴일근무, 휴가, 휴직 등 부서원의 유형별 근무 현황을 일단위로 점검하여<br>
                                근태 담당자가 상신한 근태문서를 결재해야 합니다.<br>
                                개인별 근무 및 휴가 현황을 정기적으로 확인하여 부서 근태에 대한 정합성을 높이고,<br>
                                팀/부서원에게 적절한 휴식을 부여하여 업무 몰입도/능률을 향상시킬 수 있도록 지원해주시기 바랍니다.</div>
                            </li>
                            <li class="type04">
                                <h4>인사팀 담당자</h4>
                                <div>인사팀 담당자는<br>
                                현업의 근태 책임자에 의해 확정된 근태데이터를 기반으로,<br>
                                담당 지역별 근태를 관리하고 급여 및 인력운영에 공정하고 정확하게 반영합니다. </div>
                            </li>
                        </ul>
                    </div>
                </div>';

$query ="select * from tbl_holiday_guide where th_coseq = {$coseq}";
$ps = pdo_query($db,$query,array());
$data_html = $ps ->fetch(PDO::FETCH_ASSOC);

// echo('<pre>');print_r($data_html);echo('</pre>');exit;
if(empty($data_html)){
    $data_html['th_html']=$markup_text;
}

?>
<style>
    table tbody tr{cursor: pointer;}
</style>
<!--좌측메뉴 활성화 경우에 class : depth03 -->
<div id="wrap" class="depth03">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/hass_head.php'; ?>

<!-- CONTENT -->
<div id="container" class="ehr-main"> <!-- 180124 클래스 추가(닫기버튼 눌렀을 경우 aside-open 추가) -->
	<?include $_SERVER['DOCUMENT_ROOT'].'/hass/lnb.php';?>

	<!-- 내용 -->
	<div id="content" class="content-primary">
        <h2 class="content-title">근태 가이드 관리</h2>
		<div class="section-wrap">
            <!-- 공지사항 -->
            <form id ="frm" method="post" action="/@proc/hass/holidayguideProc.php" >
                <div class="table-wrap">
                    <table class="data-table" id="tb_insaHeader">
                        <caption>근태 가이드 관리</caption>
                        <colgroup>
                            <col style="width: 140px" />
                            <col style="width: *" />
                        </colgroup>
                        <tbody>
                            <tr>
                                <th scope="col">근태 가이드 내용</th>
                                <td>
                                    <textarea class="form-control" readonly id="nContent" name="th_html" style="height:250px;">
                                        <?=$data_html['th_html']?>
                                    </textarea>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
            <!-- //공지사항 -->
            <div style="text-align:center;margin-top:50px;">
                <div class="button-area large">
                    <button type="button" data-btn="" id="btn_save" class="btn type01 large btn-footer">저장<span class="ico save"></span></button>
                </div>
            </div>
		</div>
	</div>
	<!-- // 내용 -->
</div>
<!-- // CONTENT -->
<? include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php'; ?>
<!-- // WRAP -->
<script>
    $('#aside-menu .tree-wrap>li:eq(3)').addClass('active');

    (function () {
        new FroalaEditor("#nContent",{
        'key' : 'aLF3c1C10D6A4E3E2C2C-7tdvkiD-11ldB-7j1A11lE-13D1yahB3D3C10A6C3B4F6F3G3C3==',
		heightMin: 300,
        attribution: false,
		imageUploadURL: '/manage/proc/imageUpload.php', // 업로드 처리 end point
		imageUploadParam: 'file', // 파일 파라메터명
		imageUploadMethod: 'POST',
		imageAllowedTypes: ['jpeg', 'jpg', 'png'],
		imageMaxSize: 20 * 1024 * 1024 // 최대 이미지 사이즈 : 2메가
      });
    })();

    $('#btn_save').click(function(){
        if(confirm('근태 가이드를 저장하시겠습니까?')){
            $('#frm').submit();
        }else{
            return false;
        }
    })
</script>


