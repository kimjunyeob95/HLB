<h2 class="content-title"><?=$title?></h2>
<ol class="step">
    <li class=' <?if($teb_seq >=1){?>active<?}?> <?if($teb_seq >1){?>complete<?}?>' onclick="new_step_info(1)"><a href="javascript:void(0);">1</a></li>
    <li class=' <?if($teb_seq >=2){?>active<?}?> <?if($teb_seq >2){?>complete<?}?>' <?if($step >= 1){?>onclick="new_step_info(2)"<?}else{?>onclick="alert('이전 페이지를 모두 작성해주세요.');"<?}?> ><a href="javascript:void(0);">2</a></li>
    <li class=' <?if($teb_seq >=3){?>active<?}?> <?if($teb_seq >3){?>complete<?}?>' <?if($step >= 2){?>onclick="new_step_info(3)"<?}else{?>onclick="alert('이전 페이지를 모두 작성해주세요.');"<?}?> ><a href="javascript:void(0);">3</a></li>
    <li class=' <?if($teb_seq ==4){?>active<?}?>' <?if($step >= 3){?>onclick="new_step_info(4)"<?}else{?>onclick="alert('이전 페이지를 모두 작성해주세요.');"<?}?>><a href="javascript:void(0);">4</a></li>
</ol>