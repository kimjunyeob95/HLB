<?
include $_SERVER['DOCUMENT_ROOT'].'/lib/common.php';
include $_SERVER['DOCUMENT_ROOT'].'/auth_check.php';
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/include/head.php';

//echo $enc->encrypt("서울시 강남구 역삼동 721-11");
@$mm_coseq = $_SESSION['mInfo']['mc_coseq'];
//echo('<pre>');print_r($_SESSION);echo('</pre>');
//$query = "select * from ess_member_base emb
//            join ess_member_code emc on emb.mmseq =  emc.mc_mmseq
//            join tbl_ess_group teg on emc.mc_group = teg.tg_seq
//            where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and emc.mc_coseq =  {$mm_coseq} and tg_parent_seq <> 0 order by tg_parent_seq asc";
//$ps = pdo_query($db,$query,array());
//$list = array();
//while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
//    array_push($list,$data);
//}
//대표이사 선택
$query = "select * from tbl_relation_group trg  
            left join ess_member_code emc on trg_mmseq = emc.mc_mmseq
            left join ess_member_base emb on trg_mmseq = emb.mmseq
            left join tbl_ess_group teg on trg_group = teg.tg_seq
            left join tbl_position tpt on trg_coseq = tpt.tp_coseq
            where emc.mc_position in(select tp_seq from tbl_position where tp_title='대표이사' and tp_coseq={$_SESSION['mInfo']['mc_coseq']}) and tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and tpt.tp_title='대표이사' and emb.mm_super_admin='F' and emb.mm_is_del='FALSE' and emb.mm_status='Y' and trg.trg_coseq = {$mm_coseq} and emc.mc_coseq =  {$mm_coseq} and tg_parent_seq <> 0 group by mmseq order by tp_regdate desc";
$ps = pdo_query($db,$query,array());
$list_CEO = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_CEO,$data);
}

$query = "select *,CASE WHEN teg.tg_mms_mmseq = emb.mmseq
            THEN '1'
            ELSE '0'
            END
            AS teammanage from tbl_relation_group trg  
            left join ess_member_code emc on trg_mmseq = emc.mc_mmseq
            left join ess_member_base emb on trg_mmseq = emb.mmseq
            left join tbl_ess_group teg on trg_group = teg.tg_seq
            where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and emb.mm_super_admin='F' and emb.mm_is_del='FALSE' and emb.mm_status='Y' and trg.trg_coseq = {$mm_coseq} and emc.mc_coseq =  {$mm_coseq} and tg_parent_seq <> 0 order by teammanage desc,tg_seq asc";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
// echo('<pre>');print_r($list);echo('</pre>');


$query = "select * from tbl_ess_group teg join tbl_coperation tc on teg.tg_coseq = tc.co_seq
            where tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and tg_parent_seq = 0";
$ps = pdo_query($db,$query,array());
$data_parent = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select tg_seq from tbl_ess_group where tg_parent_seq =0 and tg_coseq = {$_SESSION['mInfo']['mc_coseq']}";
$ps = pdo_query($db,$query,array());
$tg_seq = $ps ->fetch(PDO::FETCH_ASSOC);
if(empty($list) || empty($tg_seq) ){
    page_move('/','조직이 비어있습니다.');
}
$query = "select * from tbl_ess_group where tg_seq in 
                (SELECT tg_seq FROM
                    (SELECT tg_seq,tg_title,tg_parent_seq,tg_coseq,
                            CASE WHEN tg_seq = {$tg_seq['tg_seq']} THEN @idlist := CONCAT(tg_seq)
                                 WHEN FIND_IN_SET(tg_parent_seq,@idlist) THEN @idlist := CONCAT(@idlist,',',tg_seq)
                            END as checkId
                     FROM tbl_ess_group
                     ORDER BY tg_seq ASC) as T
                WHERE checkId IS NOT NULL and tg_coseq = {$_SESSION['mInfo']['mc_coseq']} and tg_parent_seq <> 0) group by tg_seq;";
$list_group = array();
$ps = pdo_query($db,$query,array());
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_group,$data);
}
//echo('<pre>');print_r($list);echo('</pre>');
// echo('<pre>');print_r($list_group);echo('</pre>');
?>
<script type="text/javascript" src="/ess/js/orgchart.js"></script>
<style>
    .node.department text{font-size: 24px !important;}
    .node text{font-size: 16px !important;}
    div.search-div{opacity:0 !important;}
    .bg-toolbar-container {bottom: 0px !important; top: 290px !important;}
</style>
<div id="wrap" class="depth-main">
<!-- HEADER -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/gnb.php';?>



<!-- CONTENT -->
<div id="container" class="result sub-main" >

	<div id="content" class="content-primary">
		<!-- 180314 추가 -->
		<!-- <div class="info-wrap" >
			<div class="plan">
				<h3 class="subject">업무담당 <a href="#" class="btn-more">더보기</a></h3>
				<div class="table-wrap">
					
				</div>
			</div>
			<div class="take-charge">
				<h3 class="subject">인사 담당</h3>
				<div class="table-wrap">
					<table class="data-table">
						<colgroup>
							<col style="width: 100%;">
						</colgroup>
						<thead>
							<tr>
								<th scope="col">인사 담당자</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>인사지원실 조윤해 사원</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div> -->
		<!-- // 180314 추가 -->
		<!-- 180314 삭제 -->
		<!-- <div class="task">
			<div class="section">
				<h2 class="content-title">업무담당</h2>
				<h3 class="section-title">인사제도팀</h3>
				<div class="text-info">
					<span>총원 25명, 부재 2명(휴직1명, 파견1명), 현인원 23명</span>
				</div>
				<div class="aside">자세히보기<a href="#" class="btn-more">더보기</a></div>
				<div class="table-wrap">
					<table class="data-table info01">
						<caption>업무담당자 표</caption>
						<colgroup>
							<col style="width: 12.5%">
							<col style="width: 12.5%">
							<col style="width: 12.5%">
							<col style="width: 12.5%">
							<col style="width: 12.5%">
							<col style="width: 12.5%">
							<col style="width: 12.5%">
							<col style="width: 12.5%">
						</colgroup>
						<thead>
							<tr>
								<th scope="col" class="info01">파트장</th>
								<th scope="col" class="info01">보직과장</th>
								<th scope="col" class="info01">파견인원</th>
								<th scope="col" class="info02 first">휴직인원</th>
								<th scope="col" class="info02">WG그룹장</th>
								<th scope="col" class="info02">안전담당</th>
								<th scope="col" class="info02">근태담당</th>
								<th scope="col" class="info02">보안담당</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="info01"><div>전태현 과장 외 2명</div></td>
								<td class="info01"><div>이지영 과장 외 1명</div></td>
								<td class="info01"><div>김판경 대리 외 2명</div></td>
								<td class="info02 first"><div>박준혁 사원 외 2명</div></td>
								<td class="info02"><div>전태현 과장 외2명</div></td>
								<td class="info02"><div>이지영 과장</div></td>
								<td class="info02"><div>김판경 대리</div></td>
								<td class="info02"><div>박준혁 사원</div></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div> -->
		<!-- 180314 삭제 -->
		<div class="organize-group" style="margin-top:30px;">
			<h2 class="content-title" style="height: 68px;">워킹그룹 조직도</h2>
			<div class="tab-wrap">
				<span class="aside-text">현재 속하신 조직의 조직도입니다. </span>
				<ul class="tab">
					<li class="active"><a href="organize01">조직도</a></li>
				</ul>

				<div id="organize01" class="tab-cont active">
					<!-- 조직도 붙이는 영역  -->
					<div class="cont" style="margin-bottom: 40px;"  id="tree">
						
					</div>
					<!-- //조직도 붙이는 영역  -->

				<div class="table-wrap sum-table" style="display:none;">
							<table class="data-table">
								<colgroup>
									<col />
									<col />
									<col />
									<col />
								</colgroup>
								<thead>
									<tr>
										<th scope="col">구분</th>
										<th scope="col" class="sum">계</th>
										<th scope="col">부장</th>
										<th scope="col">차장</th>
										<th scope="col">과장</th>
										<th scope="col">대리</th>
										<th scope="col">사원</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th class="left" scope="row">現 인원(명)</th>
										<td class="sum">10</td>
										<td>-</td>
										<td>3</td>
										<td>3</td>
										<td>3</td>
										<td>1</td>
									</tr>
									<tr>
										<th class="left" scope="row">제도기획 W/G</th>
										<td class="sum">3</td>
										<td>-</td>
										<td>1</td>
										<td>1</td>
										<td>1</td>
										<td>-</td>
									</tr>
									<tr>
										<th class="left" scope="row">커뮤니케이션 W/G</th>
										<td class="sum">3</td>
										<td>-</td>
										<td>1</td>
										<td>1</td>
										<td>-</td>
										<td>1</td>
									</tr>
									<tr>
										<th class="left" scope="row">팀장 직속</th>
										<td class="sum">3</td>
										<td>-</td>
										<td>-</td>
										<td>1</td>
										<td>2</td>
										<td>-</td>
									</tr>
								</tbody>
							</table>
							<p class="notice">※ 팀/파트 내 하위조직의 편제 및 담당업무 지정은 소속 조직의 원활한 관리를 위해 해당 조직장의 재량으로 이루어지는 것이며 인사발령으로 관리되지 않습니다.</p> <!-- 180116 텍스트 수정 -->
						</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
<!-- // CONTENT -->
<?include $_SERVER['DOCUMENT_ROOT'].'/include/footer.php';?>

<script>
//$('.header-wrap ').addClass('active');
// $('.depth01:eq(0)').addClass('active');
// $('.depth02:eq(0)').find('li:eq(0)').addClass('active');
</script>
    <script>
    

window.onload = function () {
    
    OrgChart.templates.isla.plus = '<circle cx="15" cy="15" r="15" fill="#ffffff" stroke="#ef8440" stroke-width="1"></circle>'
        + '<text text-anchor="middle" style="font-size: 18px;cursor:pointer;" fill="#ef8440" x="15" y="22">{collapsed-children-count}</text>';

    OrgChart.templates.invisibleGroup.padding = [20, 0, 0, 0];

    var chart = new OrgChart(document.getElementById("tree"), {
        zoom: false,
        template: "isla",
        enableDragDrop: false,
        assistantSeparation: 170,
        menu: {
            pdfPreview: {
                text: "Export to PDF",
                icon: OrgChart.icon.pdf(24, 24, '#ef8440'),
                onClick: preview
            },
            csv: { text: "Save as CSV" }
        },
        nodeMenu: {
            details: { text: "상세보기" },
            //edit: { text: "Edit" },
            //add: { text: "Add" },
            //remove: { text: "Remove" }
        },
        align: OrgChart.ORIENTATION,
        toolbar: {
            // expandAll: true,
            layout: true,
            zoom: true,
            fit: true,
            fullScreen: true,
            
        },
        nodeBinding: {
            field_0: "성명",
            field_1: "직급",
            field_2: "직무",
            img_0: "img",
        },
        tags: {
            "top-management": {
                template: "invisibleGroup",
                subTreeConfig: {
                    orientation: OrgChart.orientation.bottom,
                    collapse: {
                        level: 1
                    }
                }
            },
           
            "hr-team": {
                subTreeConfig: {
                    layout: OrgChart.treeRightOffset,
                    collapse: {
                        level: 2
                    }
                },
            },
            "sales-team": {
                subTreeConfig: {
                    layout: OrgChart.treeLeftOffset,
                    collapse: {
                        level: 2
                    }
                },
            },
            "seo-menu": {
                nodeMenu: {
                    //addSharholder: { text: "Add new sharholder", icon: OrgChart.icon.add(24, 24, "#7A7A7A"), onClick: addSharholder },
                    //addDepartment: { text: "Add new department", icon: OrgChart.icon.add(24, 24, "#7A7A7A"), onClick: addDepartment },
                    //addAssistant: { text: "Add new assitsant", icon: OrgChart.icon.add(24, 24, "#7A7A7A"), onClick: addAssistant },
                    //edit: { text: "Edit" },
                    details: { text: "상세보기" },
                }
            },
            "menu-without-add": {
                nodeMenu: {
                    details: { text: "상세보기" },
                    //edit: { text: "Edit" },
                    //remove: { text: "Remove" }
                }
            },
            "department": {
                template: "group",
                nodeMenu: {
                  //  addManager: { text: "Add new manager", icon: OrgChart.icon.add(24, 24, "#7A7A7A"), onClick: addManager },
                   // remove: { text: "Remove department" },
                    //edit: { text: "Edit department" },
                    //nodePdfPreview: { text: "Export department to PDF", icon: OrgChart.icon.pdf(24, 24, "#7A7A7A"), onClick: nodePdfPreview }
                }
            }
        }
    });


    chart.on("added", function (sender, id) {
        sender.editUI.show(id);
    });

    chart.on('drop', function (sender, draggedNodeId, droppedNodeId) {
        var draggedNode = sender.getNode(draggedNodeId);
        var droppedNode = sender.getNode(droppedNodeId);

        if (droppedNode.tags.indexOf("department") != -1 && draggedNode.tags.indexOf("department") == -1) {
            var draggedNodeData = sender.get(draggedNode.id);
            draggedNodeData.pid = null;
            draggedNodeData.stpid = droppedNode.id;
            sender.updateNode(draggedNodeData);
            return false;
        }
    });

    chart.editUI.on('field', function (sender, args) {
        var isDeprtment = sender.node.tags.indexOf("department") != -1;
        var deprtmentFileds = ["name"];
        if (isDeprtment && deprtmentFileds.indexOf(args.name) == -1) {
            return false;
        }
    });

    chart.on('exportstart', function (sender, args) {
        
        args.styles = document.getElementById('myStyles').outerHTML;
    });
    child_list = new Array();

	<?if(!empty($list_CEO)){?>
		child_list.push(
			<?for($i=0;$i<sizeof($list_CEO);$i++){
				if($i==0){
			?>
				{ id: "<?=$data_parent['tg_seq']+$count?>", pid: "CEO", tags: ["대표이사"], 성명: "<?=$enc->decrypt($list_CEO[$i]['mm_name'])?>", 직급 : "<?=get_position_title_type($db,$list_CEO[$i]['mc_position2'],2)?>(대표이사)", 직군 : "<?=get_position_title_type($db,$list_CEO[$i]['mc_position3'],3)?>" , img: "<?=$list_CEO[$i]['mm_profile']?>",입사일 : '<?=substr($list_CEO[$i]['mc_regdate'],0,10)?>' },
			<?}else{?>
				{ id: 1+<?=$i?>, pid: "CEO", tags: ["대표이사"], 성명: "<?=$enc->decrypt($list_CEO[$i]['mm_name'])?>", 직급 : "<?=get_position_title_type($db,$list_CEO[$i]['mc_position2'],2)?>(대표이사)", 직군 : "<?=get_position_title_type($db,$list_CEO[$i]['mc_position3'],3)?>" , img: "<?=$list_CEO[$i]['mm_profile']?>",입사일 : '<?=substr($list_CEO[$i]['mc_regdate'],0,10)?>' },
			<?}}?>
		);
	<?} else {?>
		<?if(!empty($_SESSION['mInfo']['co_subname'])){?>
		child_list.push({
			id: 1, pid: "CEO", tags: ["<?=$_SESSION['mInfo']['co_name']?> (<?=$_SESSION['mInfo']['co_subname']?>)"], 성명: "<?=$_SESSION['mInfo']['co_name']?> (<?=$_SESSION['mInfo']['co_subname']?>)",img : "/data/logo/<?=$_SESSION['mInfo']['co_logo']?>"
		});
		<?}else{?>
		child_list.push({
			id: 1, pid: "CEO", tags: ["<?=$_SESSION['mInfo']['co_name']?>"], 성명: "<?=$_SESSION['mInfo']['co_name']?>",img : "/data/logo/<?=$_SESSION['mInfo']['co_logo']?>"
		});
		<?}?>
    <?}?>
    <?for($i=0;$i<sizeof($list_group);$i++){?>
    child_list.push({ id: "<?=$list_group[$i]['tg_seq']?>", pid: "<?=$list_group[$i]['tg_parent_seq']?>", tags: ["<?=$list_group[$i]['tg_seq']?>", "department"], 성명: "<?=$list_group[$i]['tg_title']?>" });
    <?}?>

    <?for($i=0;$i<sizeof($list);$i++){?>
    child_list.push({ id: "<?="i".$i?>", stpid: "<?=$list[$i]['tg_seq']?>", 성명: `<? if($list[$i]['teammanage'] == 1){echo $enc->decrypt($list[$i]['mm_name'])." (팀장)";}else{echo $enc->decrypt($list[$i]['mm_name']);}?>`, 직급 : "<?=get_position_title_type($db,$list[$i]['mc_position2'],2)?>",직군 : "<?=get_position_title_type($db,$list[$i]['mc_position3'],3)?>" , 직무 : "<?=$list[$i]['mc_job']?>" , img: "<?=$list[$i]['mm_profile']?>",입사일 : '<?=substr($list[$i]['mc_regdate'],0,10)?>' });
    <?}?>
    chart.load(child_list);
    function preview() {
        OrgChart.pdfPrevUI.show(chart, {
            format: 'A4'
        });
    }

    function nodePdfPreview(nodeId) {
        OrgChart.pdfPrevUI.show(chart, {
            format: 'A4',
            nodeId: nodeId
        });
    }

    function addSharholder(nodeId) {
        chart.addNode({ id: OrgChart.randomId(), pid: nodeId, tags: ["menu-without-add"] });
    }

    function addAssistant(nodeId) {
        var node = chart.getNode(nodeId);
        var data = { id: OrgChart.randomId(), pid: node.stParent.id, tags: ["assistant"] };
        chart.addNode(data);
    }


    function addDepartment(nodeId) {
        var node = chart.getNode(nodeId);
        var data = { id: OrgChart.randomId(), pid: node.stParent.id, tags: ["department"] };
        chart.addNode(data);
    }

    function addManager(nodeId) {
        chart.addNode({ id: OrgChart.randomId(), stpid: nodeId });
    }
    setTimeout(() => {
        $('.bg-toolbar-container div').data('tlbr','fit').click();    
    }, 500);
    
    $('body').find('[data-id="container"]').children('div').children('div').eq(1).children('div').eq(1).text();
};

    </script>
