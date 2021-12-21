<?
    $query = "select * from ess_member_base emb 
    join ess_member_code emc on emb.mmseq = emc.mc_mmseq 
    join tbl_coperation tc on tc.co_seq = emc.mc_coseq  where emb.mmseq = {$_SESSION['mmseq']} and tc.co_status='T' ";
    $ps = pdo_query($db,$query,array());
    $list_gnb = array();
    while($data_gnb = $ps ->fetch(PDO::FETCH_ASSOC)) {
        array_push($list_gnb, $data_gnb);
    }
    // echo('<pre>');print_r($list_gnb);echo('</pre>');

?>

<!-- HEADER -->
<div id="header">
	<!-- header-wrap -->
	<div class="header-wrap">
        <h1 style="background: url('/data/logo/<?=$_SESSION['mInfo']['co_logo']?>') 50% 50% no-repeat; background-size: contain;"><a href="/"><span class="blind">HLB</span></a></h1>
		<!-- personal-info -->
		<div class="personal-info">
            <?if($list_gnb[0]['mm_super_admin'] != 'T'){?>
                <div class="position"><span class="name"><?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?> </span>님 환영합니다.</div>
                <?}else{?>
                <div class="select-area" style="display: inline-block; float: left; padding-right: 26px;">
                    <div class="insert">
                        <select class="select" id="change_coperation">
                            <option value="0" selected style="display: none;">
                                <?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?>님 환영합니다
                            </option> 
                            <?foreach ($list_gnb as $index => $val){?>
                                <option value="<?=$val['mc_seq']?>">
                                    <?=$val['co_name']?>
                                    <?if(!empty($val['co_subname'])){?> <?=$val['co_subname']?><?}?>
                                </option>
                            <?}?>
                        </select>
                    </div>
                </div>
            <?}?>
            <!-- <div class="state logout"><a href="/@proc/logout.php">비밀번호 변경</a></div> -->
			<!-- <div class="position"><span class="name"><?=$enc->decrypt($_SESSION['mInfo']['mm_name'])?></span> 님 환영합니다.</div> -->
			<div class="state logout"><a href="/@proc/logout.php">Logout</a></div>
		</div>
		<!-- // personal-info -->
		<!-- utill-menu -->
		<div class="utill-menu">
			<!-- 활성화 class active -->

            <ul>
                <li class=""><a href="/">My HR</a></li>
				<li class="active"><a href="../hass/index">인사 담당</a></li>
			</ul>
			<!--// 활성화 class active -->
		</div>
		<!--// utill-menu -->
	</div>
</div>
<!-- // HEADER -->
<script>
    $('#change_coperation').change(function(){
        mc_seq = $(this).val();
        var data = { 'mc_seq' : mc_seq };
        hlb_fn_ajaxTransmit_v2("/@proc/login_change.php", data);
    })

    function fn_callBack_v2(calback_id, result, textStatus){
        if(calback_id=='login_change'){
            if(result.code=='FALSE'){
                alert(result.msg);
                return;
            }else{
                alert(result.msg);
                location.reload();
            }
        }
    }
</script>