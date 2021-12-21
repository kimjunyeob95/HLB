<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; 
include $_SERVER['DOCUMENT_ROOT'].'/ess/ess_model.php';
include $_SERVER['DOCUMENT_ROOT'].'/model/position_model.php';
?>
<?
$enc = new encryption();
$where=" WHERE co_is_del='FALSE' ";

$query="SELECT * FROM 
				 tbl_coperation as  A join tbl_code as B on A.co_type = B.code_seq  ";
$query.=$where;
$query.=" ORDER BY co_seq asc ";
$ps2 = pdo_query($db, $query, array());
$coperationList = array();
while($data2 = $ps2->fetch(PDO::FETCH_ASSOC)){
	array_push($coperationList, $data2);
}
$mc_coseq = $_REQUEST['mc_coseq'];
if(empty($mc_coseq)){
    $mc_coseq=1;
}
$query = "select * from ess_member_base emb 
            join ess_member_code emc on emb.mmseq =  emc.mc_mmseq
            join tbl_ess_group teg on emc.mc_group = teg.tg_seq
            where tg_coseq = {$mc_coseq} and emc.mc_coseq =  {$mc_coseq} and tg_parent_seq <> 0 order by tg_parent_seq asc";
$ps = pdo_query($db,$query,array());
$list = array();
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list,$data);
}
// echo('<pre>');print_r($list);echo('</pre>');exit;

$query = "select * from tbl_ess_group teg join tbl_coperation tc on teg.tg_coseq = tc.co_seq
            where tg_coseq = {$mc_coseq} and tg_parent_seq = 0";
$ps = pdo_query($db,$query,array());
$data_parent = $ps ->fetch(PDO::FETCH_ASSOC);

$query = "select tg_seq from tbl_ess_group where tg_parent_seq =0 and tg_coseq = {$mc_coseq}";
$ps = pdo_query($db,$query,array());
$tg_seq = $ps ->fetch(PDO::FETCH_ASSOC);

if(empty($list) || empty($tg_seq)){
    page_move('./tree_test_jun.php','조직이 비어있습니다.');
}

$query = "select * from tbl_ess_group where tg_seq in 
                (SELECT tg_seq FROM
                    (SELECT tg_seq,tg_title,tg_parent_seq,tg_coseq,
                            CASE WHEN tg_seq = {$tg_seq['tg_seq']} THEN @idlist := CONCAT(tg_seq)
                                 WHEN FIND_IN_SET(tg_parent_seq,@idlist) THEN @idlist := CONCAT(@idlist,',',tg_seq)
                            END as checkId
                     FROM tbl_ess_group
                     ORDER BY tg_seq ASC) as T
                WHERE checkId IS NOT NULL and tg_coseq = {$mc_coseq} and tg_parent_seq <> 0);";
$list_group = array();
$ps = pdo_query($db,$query,array());
while($data = $ps ->fetch(PDO::FETCH_ASSOC)){
    array_push($list_group,$data);
}
// echo('<pre>');print_r($data_parent);echo('</pre>');

?>


<!-- <script type="text/javascript" src="/manage/@resource/jquery.orgchart.js"></script> -->
<!-- <link rel="stylesheet" href="/manage/@resource/jquery.orgchart.css" /> -->
<script type="text/javascript" src="/ess/js/orgchart.js"></script>
<style>
    /* 조직도 */
.organize-group { margin: 0 auto; background-color: #f7f7f7; padding: 49px 0 100px; border-radius: 5px; }

.organize-group::before { background-color: transparent; width: 0; height: 0; }

.organize-group h2 { width: 1280px; margin: 0 auto; padding-bottom: 0; }

.organize-group h2 .aside-text { float: right; }

.organize-group h2 .chart-text { clear: both; width: 1280px; margin: 0 auto; padding-top: 10px; padding-bottom: 0; }

.organize-group .tab-wrap { width: 1280px; margin: 0 auto; position: relative;}

.organize-group .tab-cont { margin-top: 30px; }

.organize-group .cont { overflow: auto; height: 874px; }

.organize-group .cont img { text-align: center; }

.organize-group .table-wrap.sum-table { margin: 0 auto; }

.organize-group .table-wrap.sum-table th { background-color: #ffffff; }

.organize-group .table-wrap.sum-table td { background-color: #ffffff; border-right: 1px solid #d1d1d1; border-left: 1px solid #d1d1d1; }
.organize-group .table-wrap.sum-table .data-table tr th:first-child, .organize-group .table-wrap.sum-table .data-table tr td:first-child { border-left: 0 none; }
.organize-group .table-wrap.sum-table .data-table tr th:last-child, .organize-group .table-wrap.sum-table .data-table tr td:last-child { border-right: 0 none; }
.organize-group .table-wrap.sum-table .sum { background-color: #fff4f7; }
.organize-group .table-wrap.sum-table .notice { margin-top: 25px; font-size: 14px; letter-spacing: -0.5px; }
.tab-wrap .aside-text {
    position: absolute;
    right: 0;
    top: 10px;
}
.tab-wrap .tab {
    height: 44px;
    border-bottom: 1px solid #d1d1d1;
    padding-left:0;
}
.tab-wrap .tab li {
    float: left;
    margin-right: 10px;
    list-style: none;
}
.tab-wrap .tab li a:hover, .tab-wrap .tab li.active a {
    font-size: 15px;
    height: 44px;
    line-height: 44px;
    color: #fff;
    background-color: #ef8440;
    display: block;
    padding: 0 25px;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    -webkit-border-top-left-radius: 5px;
    -webkit-border-top-right-radius: 5px;
}
</style>
<section class="body">

	<!-- start: header -->
	<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_header.php'; ?>
	<!-- end: header -->

	<div class="inner-wrapper">
		<!-- start: sidebar -->
		<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/view_gnb_menu.php'; ?>
		<!-- end: sidebar -->
		<section class="panel panel-featured col-md-12 " style="border:none;">
		    <section role="main" class="content-body">
                <header class="page-header">
                    <h2>조직도 관리</h2>
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle=""></a>
                    </div>
                </header>
		        <!-- start: page -->
                <div class="row">
                    <select class="form-control mb-md" id="form_select" name="mc_coseq" style="width:500px;margin-left:10px;">
                        <?for($i=0;$i<sizeof($coperationList);$i++){?>
                        <option value="<?=$coperationList[$i]['co_seq']?>" <?if($coperationList[$i]['co_seq']==$mc_coseq){?>selected<?}?>><?=$coperationList[$i]['co_name']?> <?if($coperationList[$i]['co_subname']!=""){?> ( <?=$coperationList[$i]['co_subname']?> )	<?}?></option>
                        <?}?>
                    </select>
                    <!-- <button class="btn btn-large btn-primary" style="float:right;margin-right:10px;"> <i class="fa fa-edit"></i> 조직도 수정하기</button> -->
                    <div class="organize-group" style="margin-top:30px;">
                        <h2 class="content-title" style="height: 68px;"><?=$data_parent['co_name']?> (<?=$data_parent['co_subname']?>) &nbsp;조직도</h2>
                        <div class="tab-wrap">
                            <span class="aside-text"><?=$data_parent['co_name']?> (<?=$data_parent['co_subname']?>) &nbsp;조직도입니다. </span>
                            <ul class="tab">
                                <li class="active"><a href="organize01">조직도</a></li>
                            </ul>
                            <div id="organize01" class="tab-cont active">
                                <!-- 조직도 붙이는 영역  -->
                                <div class="cont" style="margin-bottom: 40px;" id="tree">
                                    
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
            </section>
        </section>
    </div>
</section>
<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>
 <script>
    $('#form_select').change(function(){
        location.href="./tree_test_jun.php?mc_coseq="+$(this).val();
    });
	$(document).ready(function(){
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
                details: { text: "Details" },
                //edit: { text: "Edit" },
                //add: { text: "Add" },
                //remove: { text: "Remove" }
            },
            align: OrgChart.ORIENTATION,
            toolbar: {
                fullScreen: false,
                zoom: true,
                fit: true,
                expandAll: true
            },
            nodeBinding: {
                field_0: "name",
                field_1: "직급",
                img_0: "img"
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
                        details: { text: "Details" },
                    }
                },
                "menu-without-add": {
                    nodeMenu: {
                        details: { text: "Details" },
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

        child_list.push({ id: "<?=$data_parent['tg_seq']?>", tags: ["<?=$data_parent['tg_seq']?>"], name: "<?=$data_parent['co_name']?>"});

        <?for($i=0;$i<sizeof($list_group);$i++){?>
        child_list.push({ id: "<?=$list_group[$i]['tg_seq']?>", pid: "<?=$list_group[$i]['tg_parent_seq']?>", tags: ["<?=$list_group[$i]['tg_seq']?>", "department"], name: "<?=$list_group[$i]['tg_title']?>" });
        <?}?>

        <?for($i=0;$i<sizeof($list);$i++){?>
        child_list.push({ id: "<?=$list[$i]['mc_code']?>", stpid: "<?=$list[$i]['tg_seq']?>", name: "<?=$enc->decrypt($list[$i]['mm_name'])?>", 직급 : "<?=get_position2_title_v2($db,$mc_coseq,$list[$i]['mc_position2'])?>" , 직무 : "<?=$list[$i]['mc_job2']?>" , img: "<?=$list[$i]['mm_profile']?>",입사일 : '<?=substr($list[$i]['mm_regdate'],0,10)?>' });
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
    })
    
</script>