<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/new_auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/member_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';
@$mmseq = $_SESSION['mmseq'];
//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
$title = '추가사항';
$teb_seq = 3;
$step = get_member_step($db,$mmseq);
$certificate_list = get_certificate_list($db,$mmseq); // 어학 / 자격증 / 수상
$career_list = get_career_list($db,$mmseq); // 경력사항
$education_list = get_education_list($db,$mmseq); // 학력사항
$appointment_list = get_appointment_list($db,$mmseq); // 발령
$activity_list = get_activity_list($db,$mmseq); // 교육 / 활동
$paper_list = get_paper_list($db,$mmseq); // 논문 / 저서
$project_list = get_project_list($db,$mmseq); // 프로젝트
$punishment_list = get_punishment_list($db,$mmseq); // 상벌
$premier = get_premier_list($db,$mmseq);
?>
<style>
    .table-project:first-child{margin-top: 0px; border-top: none;}
    .table-project{margin-top: 15px; border-top: 1px solid #d1d1d1;}
    
    table tbody td textarea{resize: none; width:100%; height:100%;}
    .table-form-activity div.uploader{width: auto !important;}
</style>
<!-- WRAP -->
<div id="wrap" class="depth-main newcomer">
<? include $_SERVER['DOCUMENT_ROOT'].'/include/new_head.php'; ?>
<!-- CONTENT -->
<div id="container" class="newcomer-info" style="max-width: 1420px;">
	<div id="content" class="content-primary">
		<!-- 추가사항 -->
		<div class="personal-info" style="width: 100%;">
            <? include $_SERVER['DOCUMENT_ROOT'].'/new/info_link.php'; ?>
            <form id="info_form3">
                <div class="section">
                    <h3 class="section-title">학력사항(필수사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="education" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="education" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap" id="form-education">
                    <?if(empty($education_list)){?>
                        <table class="data-table table-form-education">
                            <caption>학력사항 입력표</caption>
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 1%" />
                                <col style="width: 5%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                                <col style="width: 1%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">입학일</th>
                                    <th scope="col">졸업일</th>
                                    <th scope="col">학교명</th>
                                    <th scope="col">학력</th>
                                    <th scope="col">전공</th>
                                    <th scope="col">학위</th>
                                    <th scope="col">졸업구분</th>
                                    <th scope="col">주야간구분</th>
                                    <th scope="col">기타</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="text" class="input-text input-datepicker input-validate" name="me_sdate[]" title="재직 시작일" readonly value="">
                                    </td>
                                    <td>
                                        <input type="text" class="input-text input-datepicker input-validate" name="me_edate[]" title="재직 종료일" readonly value="">
                                    </td>
                                    <td><input class="input-text input-validate" type="text" name="me_name[]" title='학교명' value=""></td>
                                    <td>
                                        <select name="me_level[]">
                                            <option value="1">고등학교</option>
                                            <option value="2">전문대</option>
                                            <option value="3">대학교</option>
                                            <option value="4">대학원</option>
                                        </select>
                                    </td>
                                    <td><input class="input-text input-validate" type="text" name="me_major[]" title="전공"></td>
                                    <td>
                                        <select name="me_degree[]">
                                            <option value="1">없음</option>
                                            <option value="2">고등학교</option>
                                            <option value="3">전문학사</option>
                                            <option value="4">학사</option>
                                            <option value="5">석사</option>
                                            <option value="6">박사</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="me_graduate_type[]">
                                            <option value="1">졸업</option>
                                            <option value="2">재학</option>
                                            <option value="3">수료</option>
                                            <option value="4">중퇴</option>
                                            <option value="5">졸업예정</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="me_weekly[]">
                                            <option value='1'>해당없음</option>
                                            <option value='2'>주간</option>
                                            <option value='3'>야간</option>
                                        </select>
                                    </td>
                                    <td><input class="input-text" type="text" name="me_etc[]" title="기타"></td>
                                </tr>
                            </tbody>
                        </table>
                        <?}else{?>
                            
                            <table class="data-table table-form-education">
                                <caption>학력사항 입력표</caption>
                                    <colgroup>
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 5%" />
                                    <col style="width: 1%" />
                                    <col style="width: 5%" />
                                    <col style="width: 1%" />
                                    <col style="width: 1%" />
                                    <col style="width: 1%" />
                                    <col style="width: 5%" />
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col">입학일</th>
                                        <th scope="col">졸업일</th>
                                        <th scope="col">학교명</th>
                                        <th scope="col">학력</th>
                                        <th scope="col">전공</th>
                                        <th scope="col">학위</th>
                                        <th scope="col">졸업구분</th>
                                        <th scope="col">주야간구분</th>
                                        <th scope="col">기타</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?foreach ($education_list as $key =>$val){?>
                                    <tr>
                                        <td><input type="text" class="input-text input-datepicker" name="me_sdate[]" readonly value="<?=substr($val['me_sdate'],0,10)?>"></td>
                                        <td>
                                            <input type="text" class="input-text input-datepicker" name="me_edate[]" readonly value="<?=substr($val['me_edate'],0,10)?>">
                                        </td>
                                        <td class="insert"><input type="text" class="input-text" name="me_name[]"  value="<?=$val['me_name']?>" style=""></td>
                                        <td>
                                            <select name="me_level[]">
                                                <option value='1' <?if($val['me_level']=='1' || empty($val['me_level'])){?>selected<?}?>>고등학교</option>
                                                <option value='2' <?if($val['me_level']=='2'){?>selected<?}?>>전문대학</option>
                                                <option value='3' <?if($val['me_level']=='3'){?>selected<?}?>>대학교</option>
                                                <option value='4' <?if($val['me_level']=='4'){?>selected<?}?>>대학원</option>
                                            </select>
                                        </td>
                                        <td class="insert"><input type="text" class="input-text" name="me_major[]" value="<?=$val['me_major']?>"></td>
                                        <td class="insert">
                                            <select name="me_degree[]">
                                                <option value='1' <?if($val['me_degree']=='1' || empty($val['me_degree'])){?>selected<?}?>>없음</option>
                                                <option value='2' <?if($val['me_degree']=='2'){?>selected<?}?>>고등학교</option>
                                                <option value='3' <?if($val['me_degree']=='3'){?>selected<?}?>>전문학사</option>
                                                <option value='4' <?if($val['me_degree']=='4'){?>selected<?}?>>학사</option>
                                                <option value='5' <?if($val['me_degree']=='5'){?>selected<?}?>>석사</option>
                                                <option value='6' <?if($val['me_degree']=='6'){?>selected<?}?>>박사</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="me_graduate_type[]">
                                                <option value='1' <?if($val['me_graduate_type']=='1'){?>selected<?}?>>졸업</option>
                                                <option value='2' <?if($val['me_graduate_type']=='2'){?>selected<?}?>>재학</option>
                                                <option value='3' <?if($val['me_graduate_type']=='3'){?>selected<?}?>>수료</option>
                                                <option value='4' <?if($val['me_graduate_type']=='4'){?>selected<?}?>>중퇴</option>
                                                <option value='5' <?if($val['me_graduate_type']=='5'){?>selected<?}?>>졸업예정</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="me_weekly[]">
                                                <option value='1'<?if($val['me_weekly']=='1'){?>selected<?}?>>해당없음</option>
                                                <option value='2'<?if($val['me_weekly']=='2'){?>selected<?}?>>주간</option>
                                                <option value='3'<?if($val['me_weekly']=='3'){?>selected<?}?>>야간</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" class="input-text" name="me_etc[]" value="<?=$val['me_etc']?>">
                                        </td>
                                    </tr>
                                <?}?>
                                </tbody>
                            </table>
                        <?}?>
                    </div>
			    </div>
            </form>
            <form id="info_form2">
			    <div class="section">
                    <h3 class="section-title">경력사항(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="another" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="another" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap" id="form-another">
                    <?if(empty($career_list)){?>
                        <table class="data-table table-form-another">
                            <caption>경력사항 입력표</caption>
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 8%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th scope="col">시작일</th>
                                    <td>
                                        <input type="text" class="input-text input-datepicker" name="mc_sdate[]" readonly value="">
                                    </td>
                                    <th scope="col">종료일</th>
                                    <td>
                                        <input type="text" class="input-text input-datepicker"  name="mc_edate[]" readonly value="">
                                    </td>
                                    <th scope="col">회사명</th>
                                    <td class="insert"><input type="text" class="input-text"  name="mc_company[]" value="" style=""></td>
                                    <th scope="col">근무부서</th>
                                    <td class="insert"><input type="text" class="input-text" name="mc_group[]" value=""></td>
                                    <th scope="col">최종직위</th>
                                    <td class="insert"><input type="text" class="input-text" name="mc_position[]" value="" style=""></td>
                                    <th scope="col">담당업무</th>
                                    <td class="insert"><input type="text" class="input-text" name="mc_duties[]" value="" style=""></td>
                                </tr>
                                <tr> 
                                    <th scope="col">경력기술</th>
                                    <td class="insert" colspan=11><input type="text" class="input-text"  name="mc_career[]" value="" style=""></td>
                                </tr>
                            </tbody>
                        </table>
                        <?}else{?>
                            <?foreach ($career_list as $key =>$val){?>
                                <table class="data-table table-form-another">
                                    <caption>경력사항 입력표</caption>
                                    <colgroup>
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 8%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                        <col style="width: 5%" />
                                    </colgroup>
                                    <tbody>
                                        <tr>
                                            <th scope="col">시작일</th>
                                            <td>
                                                <input type="text" class="input-text input-datepicker" name="mc_sdate[]" readonly value="<?=substr($val['mc_sdate'],0,10)?>">
                                            </td>
                                            <th scope="col">종료일</th>
                                            <td>
                                                <input type="text" class="input-text input-datepicker"  name="mc_edate[]" readonly value="<?=substr($val['mc_edate'],0,10)?>">
                                            </td>
                                            <th scope="col">회사명</th>
                                            <td class="insert"><input type="text" class="input-text"  name="mc_company[]" value="<?=$enc->decrypt($val['mc_company'])?>" style=""></td>
                                            <th scope="col">근무부서</th>
                                            <td class="insert"><input type="text" class="input-text" name="mc_group[]" value="<?=$val['mc_group']?>"></td>
                                            <th scope="col">최종직위</th>
                                            <td class="insert"><input type="text" class="input-text" name="mc_position[]" value="<?=$enc->decrypt($val['mc_position'])?>" style=""></td>
                                            <th scope="col">담당업무</th>
                                            <td class="insert"><input type="text" class="input-text" name="mc_duties[]" value="<?=$enc->decrypt($val['mc_duties'])?>" style=""></td>
                                        </tr>
                                        <tr> 
                                            <th scope="col">경력기술</th>
                                            <td class="insert" colspan=11><input type="text" class="input-text"  name="mc_career[]" value="<?=$val['mc_career']?>" style=""></td>
                                        </tr>
                                    </tbody>
                                </table>
                            <?}?>
                        <?}?>
                    </div>
			    </div>
            </form>
            <form id="info_form1">
			    <div class="section">
                    <h3 class="section-title">어학 / 자격증(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="cert" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="cert" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table table-form-cert">
                            <caption>어학 / 자격증</caption>
                            <colgroup>
                                <col style="width: 5%" />
                                <col style="width: 3%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">자격증 명</th>
                                    <th scope="col">취득일</th>
                                    <th scope="col">등급/점수</th>
                                    <th scope="col">취득기관</th>
                                    <th scope="col">자격번호</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?if(empty($certificate_list)){?>
                                    <tr>
                                        <td class="insert"><input type="text" class="input-text" name="mct_cert_name[]"  value="" style=""></td>
                                        <td>
                                            <input type="text" class="input-text input-datepicker" name="mct_date[]" readonly value="">
                                        </td>
                                        <td class="insert"><input type="text" class="input-text"  name="mct_class[]" value="" style=""></td>
                                        <td class="insert"><input type="text" class="input-text"  name="mct_institution[]" value="" style=""></td>
                                        <td class="insert"><input type="text" class="input-text"  name="mct_num[]" value=""></td>
                                    </tr>
                                <?}else{?>
                                    <?foreach ($certificate_list as $key =>$val){?>
                                        <tr>
                                            <td class="insert"><input type="text" class="input-text" name="mct_cert_name[]"  value="<?=$val['mct_cert_name']?>" style=""></td>
                                            <td>
                                                <input type="text" class="input-text input-datepicker" name="mct_date[]" readonly value="<?=substr($val['mct_date'],0,10)?>">
                                            </td>
                                            <td class="insert"><input type="text" class="input-text"  name="mct_class[]" value="<?=$enc->decrypt($val['mct_class'])?>" style=""></td>
                                            <td class="insert"><input type="text" class="input-text"  name="mct_institution[]" value="<?=$enc->decrypt($val['mct_institution'])?>" style=""></td>
                                            <td class="insert"><input type="text" class="input-text"  name="mct_num[]" value="<?=$val['mct_num']?>"></td>
                                        </tr>
                                    <?}?>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>

            <form id="info_form9">
                <div class="section">
                    <h3 class="section-title">수상경력(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="prize2" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="prize2" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <table class="data-table table-form-prize2 left" style="border-top:1px solid #d1d1d1;">
                        <caption>수상경력</caption>
                        <colgroup>
                            <col style="width: 1%" />
                            <col style="width: 5%" />
                            <col style="width: 5%" />
                        </colgroup>
                        <thead>
                            <tr>
                                <th scope="col">일자</th>
                                <th scope="col">수상내용</th>
                                <th scope="col">수상기관</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?if(empty($premier)){?>
                            <tr>
                                <td class="insert"><input title = "일자" value="" type="text" class="input-text input-datepicker" name="mpd_date[]" readonly></td>
                                <td class="insert"><input title="수상내용" type="text" class="input-text"  name="mpd_content[]" value=""></td>
                                <td class="insert"><input title="수상기관" type="text" class="input-text"  name="mpd_institution[]" value=""></td>
                            </tr>
                        <?}else{?>
                        <?foreach ($premier as $key =>$val){?>
                                <tr>
                                    <td class="insert"><input title = "일자" type="text" value="<?=substr($val['mpd_date'],0,10)?>" class="input-text input-datepicker" name="mpd_date[]" readonly></td>
                                    <td class="insert"><input title="수상내용" type="text" class="input-text"  name="mpd_content[]" value="<?=$val['mpd_content']?>"></td>
                                    <td class="insert"><input title="수상기관" type="text" class="input-text"  name="mpd_institution[]" value="<?=$val['mpd_institution']?>"></td>
                                </tr>
                            <?}?>
                        <?}?>
                        </tbody>
                    </table>
                </div>
            </form>
            <form id="info_form5">
                <div class="section">
                    <h3 class="section-title">교육 / 활동(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="activity" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="activity" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap" id="form-activity">
                        <table class="data-table table-form-activity left">
                            <caption>교육 / 활동</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 5%" />
                                <col style="width: 5%" />
                                <col style="width: 20%" />
                                <col style="width: 8%" />
                                <col style="width: 5%" />
                                <col style="width: 14%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">구분</th>
                                    <th scope="col">시작일</th>
                                    <th scope="col">종료일</th>
                                    <th scope="col">교육 및 활동명</th>
                                    <th scope="col">기관명</th>
                                    <th scope="col">역할</th>
                                    <th scope="col">증빙서류</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?if(empty($activity_list)){?>
                                <tr>
                                    <td class="insert">
                                        <select name='mad_type[]'>
                                            <option value='1'>사외</option>
                                            <option value='2'>사내</option>
                                        </select>
                                    </td>  
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="mad_sdate[]" readonly value=""></td>
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="mad_edate[]" readonly value=""></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mad_name[]"  value=""></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mad_institution[]"  value=""></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mad_role[]"  value=""></td>
                                    <td scope="insert">
                                        <input type="file" class="file-activity" name="mad_file[]">
                                    </td>
                                </tr>
                            <?}else{?>
                                <?foreach ($activity_list as $index =>$val){?>
                                    <tr>
                                        <td class="insert">
                                            <select name='mad_type[]'>
                                                <option value='1' <?if($val['mad_type']=='1' || empty($val['mad_type'])){?>selected<?}?>>사외</option>
                                                <option value='2' <?if($val['mad_type']=='2' || empty($val['mad_type'])){?>selected<?}?>>사내</option>
                                            </select>
                                        </td>  
                                        <td class="insert"><input type="text" class="input-text input-datepicker" name="mad_sdate[]" readonly value="<?=substr($val['mad_sdate'],0,10)?>"></td>
                                        <td class="insert"><input type="text" class="input-text input-datepicker" name="mad_edate[]" readonly value="<?=substr($val['mad_edate'],0,10)?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mad_name[]"  value="<?=$enc->decrypt($val['mad_name'])?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mad_institution[]"  value="<?=$enc->decrypt($val['mad_institution'])?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mad_role[]"  value="<?=$val['mad_role']?>"></td>
                                        <td scope="insert">
                                        <?if(!empty($val['mad_file'])){?>
                                            <a href="<?=$val['mad_file']?>" download><?=$val['mad_file_name']?></a>&nbsp;&nbsp;&nbsp;<span class="ico down"></span>
                                        <?}?>
                                            <input type="file" class="file-activity" name="mad_file[]">
                                            <input type="hidden" name="mad_file_remain[]" value="<?=$val['mad_file']?>">
                                            <input type="hidden" name="mad_file_name_remain[]" value="<?=$val['mad_file_name']?>">
                                        </td>
                                    </tr>
                                <?}?>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
			    </div>
            </form>
            <form id="info_form6">
                <div class="section">
                    <h3 class="section-title">논문 / 저서(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="paper" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="paper" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table table-form-paper">
                            <caption>논문 / 저서</caption>
                            <colgroup>
                                <col style="width: 8%" />
                                <col style="width: 20%" />
                                <col style="width: 10%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">발행일</th>
                                    <th scope="col">논문 및 저서명</th>
                                    <th scope="col">발행정보</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?if(empty($paper_list)){?>
                                <tr>
                                    <td class="insert"><input type="text" title="발행일" class="input-text input-datepicker" name="mp_date[]" style="max-width:80%;" readonly value=""></td>
                                    <td class="insert"><input type="text" title="논문 및 저서명" class="input-text" name="mp_name[]"  value=""></td>
                                    <td scope="insert"><input type="text" title="발행정보" class="input-text" name="mp_institution[]"  value=""></td>
                                </tr>
                            <?}else{?>
                                <?foreach ($paper_list as $key =>$val){?>
                                    <tr>
                                        <td class="insert"><input type="text" title="발행일" class="input-text input-datepicker" name="mp_date[]" style="max-width:80%;" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
                                        <td class="insert"><input type="text" title="논문 및 저서명" class="input-text" name="mp_name[]"  value="<?=$enc->decrypt($val['mp_name'])?>"></td>
                                        <td scope="insert"><input type="text" title="발행정보" class="input-text" name="mp_institution[]"  value="<?=$enc->decrypt($val['mp_institution'])?>"></td>
                                    </tr>
                                <?}?>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <form id="info_form7">
                <div class="section">
                    <h3 class="section-title">프로젝트(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="project" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="project" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap" id="form-project">
                        <table class="data-table table-form-project left">
                            <caption>프로젝트</caption>
                            <colgroup>
                                <col style="width: 4%" />
                                <col style="width: 4%" />
                                <col style="width: 10%" />
                                <col style="width: 8%" />
                                <col style="width: 3%" />
                                <col style="width: 5%" />
                                <col style="width: .5%" />
                                <col style="width: 8%" />
                                <col style="width: 8%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">시작일</th>
                                    <th scope="col">종료일</th>
                                    <th scope="col">프로젝트명</th>
                                    <th scope="col">프로젝트 기관</th>
                                    <th scope="col">기여도</th>
                                    <th scope="col">역할</th>
                                    <th scope="col">완료여부</th>
                                    <th scope="col">내용(배운점 등)</th>
                                    <th scope="col">키워드</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?if(empty($project_list)){?>
                                <tr>
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_sdate[]" readonly value=""></td>
                                    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_edate[]" readonly value=""></td>
                                    <td class="insert"><input type="text" class="input-text" name="mpd_name[]"  value=""></td>
                                    <td class="insert"><input type="text" class="input-text" name="mpd_institution[]"  value=""></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mpd_contribution[]"  value=""></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mpd_position[]"  value=""></td>
                                    <td scope="insert">
                                        <select name="mpd_result[]">
                                            <option value="1">진행중</option>
                                            <option value="2">완료</option>
                                            <option value="3">보류</option>
                                            <option value="4">취소</option>
                                        </select>
                                    </td>
                                    <td scope="insert"><textarea class="text-area" rows=5 name="mpd_content[]"></textarea></td>
                                    <td scope="insert"><input type="text" class="input-text" name="mpd_keyword[]"  value=""></td>
                                </tr>
                            <?}else{?>
                                <?foreach ($project_list as $index =>$val){?>
                                    <tr>
                                        <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_sdate[]" readonly value="<?=substr($val['mpd_sdate'],0,10)?>"></td>
                                        <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_edate[]" readonly value="<?=substr($val['mpd_edate'],0,10)?>"></td>
                                        <td class="insert"><input type="text" class="input-text" name="mpd_name[]"  value="<?=$enc->decrypt($val['mpd_name'])?>"></td>
                                        <td class="insert"><input type="text" class="input-text" name="mpd_institution[]"  value="<?=$val['mpd_institution']?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mpd_contribution[]"  value="<?=$val['mpd_contribution']?>"></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mpd_position[]"  value="<?=$val['mpd_position']?>"></td>
                                        <td scope="insert">
                                            <select name="mpd_result[]">
                                                <option value="1" <?if($val['mpd_result']=="1"){?>selected<?}?>>진행중</option>
                                                <option value="2" <?if($val['mpd_result']=="2"){?>selected<?}?>>완료</option>
                                                <option value="3" <?if($val['mpd_result']=="3"){?>selected<?}?>>보류</option>
                                                <option value="4" <?if($val['mpd_result']=="4"){?>selected<?}?>>취소</option>
                                            </select>
                                        </td>
                                        <td scope="insert"><textarea class="text-area" rows=5 name="mpd_content[]"><?=$val['mpd_content']?></textarea></td>
                                        <td scope="insert"><input type="text" class="input-text" name="mpd_keyword[]"  value="<?=$val['mpd_keyword']?>"></td>
                                    </tr>
                                <?}?>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
            <!-- <form id="info_form8">
                <div class="section">
                    <h3 class="section-title">상벌(선택사항)</h3>
                    <div class="btn-aside">
                        <a data-btn="prize" class="btn type01 small btn-add-tr" href="#">추가</a>
                        <a data-btn="prize" class="btn type01 small btn-remove-tr" href="#">삭제</a>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table table-form-prize left">
                            <caption>상벌</caption>
                            <colgroup>
                                <col style="width: 1%" />
                                <col style="width: 3%" />
                                <col style="width: 8%" />
                                <col style="width: 10%" />
                                <col style="width: 5%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th scope="col">구분</th>
                                    <th scope="col">일자</th>
                                    <th scope="col">상벌명</th>
                                    <th scope="col">사유 및 내용</th>
                                    <th scope="col">비고</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?if(empty($punishment_list)){?>
                                <tr>
                                    <td scope="insert">
                                        <select name='mp_type[]'>
                                            <option value='1'>상</option>
                                            <option value='2'>벌</option>
                                        </select>
                                    </td>
                                    <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker" name="mp_date[]" readonly value=""></td>
                                    <td scope="insert"><input type="text" title="상벌명" class="input-text" name="mp_title[]" value=""></td>
                                    <td scope="insert"><input type="text" title="내용" class="input-text" name="mp_content[]" value=""></td>
                                    <td scope="insert"><input type="text" title="비고" class="input-text" name="mp_etc[]" value=""></td>
                                </tr>
                            <?}else{?>
                                <?foreach($punishment_list as $index =>$val){?>
                                    <tr>
                                        <td scope="insert">
                                            <select name='mp_type[]'>
                                                <option value='1' <?if($val['mp_type']=='1'){?>selected<?}?>>상</option>
                                                <option value='2' <?if($val['mp_type']=='2'){?>selected<?}?>>벌</option>
                                            </select>
                                        </td>
                                        <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker" name="mp_date[]" readonly value="<?=substr($val['mp_date'],0,10)?>"></td>
                                        <td scope="insert"><input type="text" title="상벌명" class="input-text" name="mp_title[]" value="<?=$val['mp_title']?>"></td>
                                        <td scope="insert"><input type="text" title="내용" class="input-text" name="mp_content[]" value="<?=$val['mp_content']?>"></td>
                                        <td scope="insert"><input type="text" title="비고" class="input-text" name="mp_etc[]" value="<?=$val['mp_etc']?>"></td>
                                    </tr>
                                <?}?>
                            <?}?>
                            </tbody>
                        </table>
                    </div>
			    </div>
            </form> -->
			<div class="btn-area">
<!--				<a class="btn type02 large" href="./recruitStep02.php"><span class="ico prev"></span>이전</a>-->
<!--				<a class="btn type02 large" id="btn_save">임시저장</a>-->
<!--				<a class="btn type03 large" href="./recruitStep04.php">다음<span class="ico next"></span></a>-->
                <a class="btn type03 large" id="btn_save">다음</a>
			</div>
		</div>
		<!-- // 추가사항 -->
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>
<!-- // WRAP -->
<script>
    $('.input-datepicker').datepicker();
    /* 동적 생성 함수 */
    $('.btn-add-tr').click(function(e){
        e.preventDefault();
        var btn_type = $(this).data('btn');
        var text_cert = '<tr>\n' +
                        '    <td class="insert"><input type="text" class="input-text" name="mct_cert_name[]"  value="" style=""></td>\n' +
                        '    <td>\n' +
                        '        <input type="text" class="input-text input-datepicker" name="mct_date[]" readonly value="">\n' +
                        '    </td>\n' +
                        '    <td class="insert"><input type="text" class="input-text"  name="mct_class[]" value="" style=""></td>\n' +
                        '    <td class="insert"><input type="text" class="input-text"  name="mct_institution[]" value="" style=""></td>\n' +
                        '    <td class="insert"><input type="text" class="input-text"  name="mct_num[]" value=""></td>\n' +
                        '</tr>';
        var text_another = '<table class="data-table table-form-another">\n' +
                            '<caption>경력사항 입력표</caption>\n' +
                            '<colgroup>\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 8%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '    <col style="width: 5%" />\n' +
                            '</colgroup>\n' +
                            '<tbody>\n' +
                            '    <tr>\n' +
                            '        <th scope="col">시작일</th>\n' +
                            '        <td>\n' +
                            '            <input type="text" class="input-text input-datepicker" name="mc_sdate[]" readonly value="">\n' +
                            '        </td>\n' +
                            '        <th scope="col">종료일</th>\n' +
                            '        <td>\n' +
                            '            <input type="text" class="input-text input-datepicker"  name="mc_edate[]" readonly value="">\n' +
                            '        </td>\n' +
                            '        <th scope="col">회사명</th>\n' +
                            '        <td class="insert"><input type="text" class="input-text"  name="mc_company[]" value="" style=""></td>\n' +
                            '        <th scope="col">근무부서</th>\n' +
                            '        <td class="insert"><input type="text" class="input-text" name="mc_group[]" value=""></td>\n' +
                            '        <th scope="col">최종직위</th>\n' +
                            '        <td class="insert"><input type="text" class="input-text" name="mc_position[]" value="" style=""></td>\n' +
                            '        <th scope="col">담당업무</th>\n' +
                            '        <td class="insert"><input type="text" class="input-text" name="mc_duties[]" value="" style=""></td>\n' +
                            '    </tr>\n' +
                            '    <tr> \n' +
                            '        <th scope="col">경력기술</th>\n' +
                            '        <td class="insert" colspan=11><input type="text" class="input-text"  name="mc_career[]" value="" style=""></td>\n' +
                            '    </tr>\n' +
                            '</tbody>\n' +
                        '</table>';
        var text_education =
        '<tr>\n' +
        '    <td>\n' +
        '        <input type="text" class="input-text input-datepicker input-validate" name="me_sdate[]" title="재직 시작일" readonly value="">\n' +
        '    </td>\n' +
        '    <td>\n' +
        '        <input type="text" class="input-text input-datepicker input-validate" name="me_edate[]" title="재직 종료일" readonly value="">\n' +
        '    </td>\n' +
        '    <td><input class="input-text input-validate" type="text" name="me_name[]" title="학교명" value=""></td>\n' +
        '    <td>\n' +
        '        <select name="me_level[]">\n' +
        '            <option value="1">고등학교</option>\n' +
        '            <option value="2">대학교</option>\n' +
        '            <option value="3">전문대</option>\n' +
        '            <option value="4">대학원</option>\n' +
        '        </select>\n' +
        '    </td>\n' +
        '    <td><input class="input-text input-validate" type="text" name="me_major[]" title="전공"></td>\n' +
        '    <td>\n' +
        '        <select name="me_degree[]">\n' +
        '            <option value="1">없음</option>\n' +
        '            <option value="2">고등학교</option>\n' +
        '            <option value="3">전문학사</option>\n' +
        '            <option value="4">학사</option>\n' +
        '            <option value="5">석사</option>\n' +
        '            <option value="6">박사</option>\n' +
        '        </select>\n' +
        '    </td>\n' +
        '    <td>\n' +
        '        <select name="me_graduate_type[]">\n' +
        '            <option value="1">졸업</option>\n' +
        '            <option value="2">재학</option>\n' +
        '            <option value="3">수료</option>\n' +
        '            <option value="4">중퇴</option>\n' +
        '            <option value="5">졸업예정</option>\n' +
        '        </select>\n' +
        '    </td>\n' +
        '    <td>\n' +
        '        <select name="me_weekly[]">\n' +
        '            <option value="1">해당없음</option>\n' +
        '            <option value="2">주간</option>\n' +
        '            <option value="3">야간</option>\n' +
        '        </select>\n' +
        '    </td>\n' +
        '    <td><input class="input-text" type="text" name="me_etc[]" title="기타"></td>\n' +
        '</tr>';
        var text_issuance =
        '<tr>\n' +
            '                            <td><input type="text" title="발령일자" class="input-text input-datepicker" name="ma_date[]" style="max-width:80%;" readonly value=""></td>\n' +
            '                            <td><input type="text" title="구분"     class="input-text" name="ma_type[]"  value=""></td>\n' +
            '                            <td><input type="text" title="발령회사 및 부서" class="input-text" name="ma_company[]"  value=""></td>\n' +
            '                            <td><input type="text" title="직위" class="input-text" name="ma_position[]"  value=""></td>\n' +
            '                            <td><input type="text" title="담당직무" class="input-text" name="ma_job[]"  value=""></td>\n' +
            '                        </tr>';
        var text_activity=
        '<tr>\n' +
        '    <td class="insert">\n' +
        '        <select name="mad_type[]">\n' +
        '            <option value="1">사외</option>\n' +
        '            <option value="2">사내</option>\n' +
        '        </select>\n' +
        '    </td>  \n' +
        '    <td class="insert"><input type="text" class="input-text input-datepicker" name="mad_sdate[]" readonly value=""></td>\n' +
        '    <td class="insert"><input type="text" class="input-text input-datepicker" name="mad_edate[]" readonly value=""></td>\n' +
        '    <td scope="insert"><input type="text" class="input-text" name="mad_name[]"  value=""></td>\n' +
        '    <td scope="insert"><input type="text" class="input-text" name="mad_institution[]"  value=""></td>\n' +
        '    <td scope="insert"><input type="text" class="input-text" name="mad_role[]"  value=""></td>\n' +
        '    <td scope="insert">\n' +
        '        <input type="file" class="file-activity" name="mad_file[]">\n' +
        '    </td>\n' +
        '</tr>';
        var text_paper=
        '<tr>\n' +
            '                                    <td class="insert"><input type="text" title="발행일" class="input-text input-datepicker" name="mp_date[]" style="max-width:80%;" readonly value=""></td>\n' +
            '                                    <td class="insert"><input type="text" title="논문 및 저서명" class="input-text" name="mp_name[]"  value=""></td>\n' +
            '                                    <td scope="insert"><input type="text" title="발행정보" class="input-text" name="mp_institution[]"  value=""></td>\n' +
            '                                </tr>';
        var text_project=
        '<tr>\n' +
        '    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_sdate[]" readonly value=""></td>\n' +
        '    <td class="insert"><input type="text" class="input-text input-datepicker" name="mpd_edate[]" readonly value=""></td>\n' +
        '    <td class="insert"><input type="text" class="input-text" name="mpd_name[]"  value=""></td>\n' +
        '    <td class="insert"><input type="text" class="input-text" name="mpd_institution[]"  value=""></td>\n' +
        '    <td scope="insert"><input type="text" class="input-text" name="mpd_contribution[]"  value=""></td>\n' +
        '    <td scope="insert"><input type="text" class="input-text" name="mpd_position[]"  value=""></td>\n' +
        '    <td scope="insert">\n' +
        '        <select name="mpd_result[]">\n' +
        '            <option value="1">진행중</option>\n' +
        '            <option value="2">완료</option>\n' +
        '            <option value="3">보류</option>\n' +
        '            <option value="4">취소</option>\n' +
        '        </select>\n' +
        '    </td>\n' +
        '    <td scope="insert"><textarea class="text-area" rows=5 name="mpd_content[]"></textarea></td>\n' +
        '    <td scope="insert"><input type="text" class="input-text" name="mpd_keyword[]"  value=""></td>\n' +
        '</tr>';
        var text_prize =
        ' <tr>\n' +
        '    <td scope="insert">\n' +
        '        <select name="mp_type[]">\n' +
        '            <option value="1">상</option>\n' +
        '            <option value="2">벌</option>\n' +
        '        </select>\n' +
        '    </td>\n' +
        '    <td scope="insert"><input type="text" title="일자" class="input-text input-datepicker" name="mp_date[]" readonly value=""></td>\n' +
        '    <td scope="insert"><input type="text" title="상벌명" class="input-text" name="mp_title[]" value=""></td>\n' +
        '    <td scope="insert"><input type="text" title="내용" class="input-text" name="mp_content[]" value=""></td>\n' +
        '    <td scope="insert"><input type="text" title="비고" class="input-text" name="mp_etc[]" value=""></td>\n' +
        '</tr>';
        var text_prize2 =
        ' <tr>\n' +
        '    <td class="insert"><input title = "일자" value="" type="text" class="input-text input-datepicker" name="mpd_date[]" readonly></td>\n' +
        '    <td class="insert"><input title="수상내용" type="text" class="input-text"  name="mpd_content[]" value=""></td>\n' +
        '    <td class="insert"><input title="수상기관" type="text" class="input-text"  name="mpd_institution[]" value=""></td>\n' +
        '</tr>';
        if(btn_type == 'cert'){
            $('.table-form-cert tbody').append(text_cert);
        }else if(btn_type == 'another'){
            $('#form-another').append(text_another);
        }else if(btn_type == 'education'){
            $('.table-form-education tbody').append(text_education);
        }else if(btn_type == 'issuance'){
            $('.table-form-issuance tbody').append(text_issuance);
        }else if(btn_type == 'activity'){
            $('.table-form-activity tbody').append(text_activity);
        }else if(btn_type == 'paper'){
            $('.table-form-paper tbody').append(text_paper);
        }else if(btn_type == 'project'){
            $('.table-form-project tbody').append(text_project);
        }else if(btn_type == 'prize'){
            $('.table-form-prize tbody').append(text_prize);
        }else if(btn_type == 'prize2'){
            $('.table-form-prize2 tbody').append(text_prize2);
        }
        
        $(document).find('.input-datepicker').removeAttr('id').removeClass('hasDatepicker').datepicker();

        /* 동적생성시 form 재설정 함수 */
        $('select, input[type=radio], input[type=checkbox], input[type=file]').uniform({
            fileDefaultHtml: '',
            fileButtonHtml: '파일첨부'
        });
        // select
        $('.select-label').click(function () {
            $(this).parents('.data-down').toggleClass('active');
        });
        $('.select-list li').click(function () {
            $(this).parents('.select-list').siblings('.select-label').text($(this).text());
            $(this).parents('.data-down').removeClass('active');
        });
    });
    $('.btn-remove-tr').click(function(e){
        e.preventDefault();
        var btn_type = $(this).data('btn');
        var table_count;
        
        if(btn_type == 'education'){
            table_count = $('.table-form-education tbody tr').length;
            if(table_count == 1) return alert('학력사항은 모두 삭제할 수 없습니다.');
            $('.table-form-education tbody tr:last-child').remove();
        }else if(btn_type=='another'){
            $('.table-form-another:last-child').remove();
        }else{
            $('.table-form-'+btn_type+' tbody tr:last-child').remove();
        }
    });

    $.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // Skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // Adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // Push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // Fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // Named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
        });

        return json;
    };
    $('#btn_save').click(function(e){
        e.preventDefault();
        var validate = true;

        $('#info_form3').find('.input-validate').each(function(e){
            var val = $.trim($(this).val());
            var txt = $(this).attr('title');

            if(val==""){
                $(this).focus();
                alert(  reulReturner(txt) + " 입력해 주세요");
                validate = false;
                return false;
            }
        });

        var data1 = $('#info_form1').serializeObject();
        var data2 = $('#info_form2').serializeObject();
        var data3 = $('#info_form3').serializeObject();
        //var data4 = $('#info_form4').serializeObject(); ,'appoint':data4  ,#info_form4
       // var data5 = $('#info_form5').serializeObject(); ,'activity':data5
        var data6 = $('#info_form6').serializeObject();
        var data7 = $('#info_form7').serializeObject();
        var data8 = $('#info_form8').serializeObject();
        var data9 = $('#info_form9').serializeObject();

        var datas = {'sert':data1,'career':data2,'edu':data3,'paper':data6,'project':data7,'punishment':data8,'premier':data9};
        if(validate){
            //if(confirm("추가사항을 임시저장을 하시겠습니까?")){
                hlb_fn_ajaxTransmit("/@proc/new/new_profileProc_other.php", datas);
            //}
        }
    })
    function active_data(){
        var form = $('#info_form5')[0];
        var data = new FormData(form);
        hlb_fn_file_ajaxTransmit("/@proc/new/new_profileProc_other_v2.php", data);
    }
    function fn_callBack(calback_id, result, textStatus){
        if(calback_id=='new_profileProc_other'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                //alert(result.msg);
                active_data();
                //location.reload();
                //location.href="/";
            }
        }
        if(calback_id=='new_profileProc_other_v2'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                //alert(result.msg);
                new_step_info(4);
                //location.reload();
                //location.href="/";
            }
        }
    }


</script>