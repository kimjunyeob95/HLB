<?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/header.php'; ?>
<?



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



?>


<script type="text/javascript" src="/manage/@resource/jquery.orgchart.js"></script>
<link rel="stylesheet" href="/manage/@resource/jquery.orgchart.css" />
<style>
    div.orgChart div.node.level1 {
        background-color: #f0f0f0;
    }
    div.orgChart div.node.level1.special {
        background-color: white;
    }
    div.orgChart div.node.level2 {
        background-color: #fff;
    }
    div.orgChart div.node.level3 {
        background-color: #fff;
    }
    div.orgChart{
        background-color:#fff;
    }
    div.orgChart div.hasChildren{
        background-color: #fff;
    }
    div.orgChart div.node{border:none;}
    div.orgChart tr.lines td.right{border-left: 1px solid #ddd;}
    div.orgChart tr.lines td.top{border-top: 1px solid #ddd;}
    div.orgChart tr.lines td.line{width:5px;}
    div.orgChart tr.lines td.left{border-right:1px solid #ddd;}

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
                    <select class="form-control mb-md" name="mType" style="width:500px;margin-left:10px;">
                        <?for($i=0;$i<sizeof($coperationList );$i++){?>
                            <option value=""><?=$coperationList[$i]['co_name']?> <?if($coperationList[$i]['co_subname']!=""){?> ( <?=$coperationList[$i]['co_subname']?> )	<?}?></option>
                        <?}?>
                    </select>
                    <button class="btn btn-large btn-primary" style="float:right;margin-right:10px;"> <i class="fa fa-edit"></i> 조직도 수정하기</button>

                    <ul id="organisation" style="display:none;">
                        <li style="width:140px;height:180px;"><img src="/manage/@resource/ceo.png" alt="" style="width:130px;border-radius:10px;" title="대표이사"><em style="font-size:1.8rem;">CEO</em><br/><br/>
                            <ul >
                                <li  style="width:140px;height:200px;"><img src="/manage/@resource/bb.png" alt="" style="width:130px;border-radius:10px;" title="제작본부장"><br><p style="font-size:1.7rem;margin-top:5px;">제작본부 <em>(35) </em></p>
                                    <ul>
                                        <li  style="width:140px;height:290px;">기획팀<em>(10) </em></li>
                                        <li   style="width:140px;height:290px;">디자인팀<em>(10) </em></li>
                                        <li   style="width:140px;height:290px;">개발팀<em>(15) </em></li>
                                    </ul>
                                </li>
                                <li  style="width:140px;height:200px;"><img src="/manage/@resource/thumb_130x170.jpg" alt="" style="width:130px;border-radius:10px;" title="제작본부장"><br><p style="font-size:1.7rem;margin-top:5px;">HR팀<em>(3) </em></p></li>
                                <li   style="width:140px;height:200px;"><img src="/manage/@resource/bb.png" alt="" style="width:130px;border-radius:10px;" title="제작본부장"><p style="font-size:1.7rem;"><p style="font-size:1.7rem;margin-top:5px;">경영지원실<em>(5) </em></p>
                                    <ul>
                                        <li   style="width:140px;height:290px;">Maurice Fischer
                                            <ul>
                                                <li   style="width:140px;height:290px;">Peter Browning</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <div id="main">
                    </div>

                </div>




            </section>
            <?php include $_SERVER['DOCUMENT_ROOT'] . '/manage/include/footer.php'; ?>
            <script>
                $(function() {
                    $("#organisation").orgChart({container: $("#main"), stack: true, depth: 4});
                });
            </script>