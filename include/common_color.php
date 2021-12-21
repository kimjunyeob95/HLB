<?php
$query = "select co_color from tbl_coperation where co_seq = {$_SESSION['mInfo']['mc_coseq']}";
$ps = pdo_query($db,$query,array());
$common_color = $ps ->fetch(PDO::FETCH_ASSOC)['co_color'];
?>
<style>
.history-list li .box-center.our{background-color:<?=$common_color?>;}
.window-popup .popup-title{background-color:<?=$common_color?>;}
.section-wrap tr td.active{color:<?=$common_color?>; font-size: 18px; font-weight: bold;}
.profile-wrap .item-info .info .number{color: <?=$common_color?>;}
#wrap #header .header-wrap.active:after {background: <?=$common_color?>; }
#wrap #header .gnb .depth01.active > a,
#wrap #header .gnb .depth01 a:hover,
#wrap #header .gnb .depth01 a.active { color: <?=$common_color?>; border-bottom: 1px solid <?=$common_color?>;}
#wrap.depth03 #aside-menu .lnb li a:hover {color: <?=$common_color?>; }
#wrap.depth03 #aside-menu .lnb li a.active {color: <?=$common_color?>;}
#wrap.depth03 #aside-menu .lnb li ul li a {color: <?=$common_color?>; }
#wrap.depth03 #aside-menu .lnb li ul li a:hover, #wrap.depth03 #aside-menu .lnb li ul li a.active { color: <?=$common_color?>;}
#wrap.depth03 #aside-menu .lnb .sub li a:hover, #wrap.depth03 #aside-menu .lnb .sub li a.active { color: <?=$common_color?>; }
.menu-wrap .menu-inner .title { color: <?=$common_color?>; }
.em.weight { color: <?=$common_color?>; }
.em.weighty { color: <?=$common_color?>; }
.btn.type03 {background-color: <?=$common_color?>; border: 1px solid <?=$common_color?>; }
.btn.type08 { background-color: <?=$common_color?>; border: 1px solid <?=$common_color?>; }
.tab-wrap .tab li a:hover, .tab-wrap .tab li.active a { background-color: <?=$common_color?>; }
.info-state ul li:first-child { color: <?=$common_color?>;}
.info-state ul li:first-child span { color: <?=$common_color?>; }
.profile-tab .tab02 li a:hover, .profile-tab .tab02 li.active a { background-color: <?=$common_color?>; }
.state-complete li.complete span { color: <?=$common_color?>; }
.tag.type01 { background-color: <?=$common_color?>;}
#print.reward-pay .section-wrap .section-left .notice { color: <?=$common_color?>;}
#print.reward-pay .pay-list .data-table tbody th.cellselected, #print.reward-pay .pay-list .data-table tbody td.cellselected { color: <?=$common_color?>; }
.example-list li.type01:after { background-color: <?=$common_color?>; }
#evaluation-pop03 p span { color: <?=$common_color?>; }
.ui-dialog .ui-dialog-titlebar { background-color: <?=$common_color?>; }
.layer-popup .popup-title { background-color: <?=$common_color?>; }
.lounge-info .info-wrap span { color: <?=$common_color?>; }
.system-info .about .table-wrap .data-table tr td ul li.migrate div { background-color: <?=$common_color?>; }
.disciplinary-state .calender-body .item-table tbody td.day-off .day { color: <?=$common_color?>; }
.disciplinary-state .calender-body .item-table .data-header .day.today { background-color: <?=$common_color?>; }
.disciplinary-state .calender-body .pending:after { background-color: <?=$common_color?>; }
.profile-wrap .item-info .info .number { color: <?=$common_color?>; }
.team-anniversary .bx-wrapper .bx-pager.bx-default-pager a:hover, .team-anniversary .bx-wrapper .bx-pager.bx-default-pager a.active, .team-anniversary .bx-wrapper .bx-pager.bx-default-pager a:focus { background-color: <?=$common_color?>; }
.global-hr .support-menu .support-title { color: <?=$common_color?>; }
.global-hr-main .content-primary .data-down.city { background-color: <?=$common_color?>; }
.assign-support .text.weighty { color: <?=$common_color?>; }
.assign-support .process ul li.order02 .tag { background-color: <?=$common_color?>; }
.assign-support .notice.highlight { color: <?=$common_color?>;}
.assign-support .process02 .data-list li.sign div { background-color: <?=$common_color?>; }
.assign-support .notice-text { color: <?=$common_color?>; }
.resident-intro p span.em.weighty { color: <?=$common_color?>; }
.hr-info .visual .mCS-rounded-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar, .hr-info .visual .mCS-rounded-dots-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar { background-color: <?=$common_color?>; }
.retire-process .retire-intro .data-list li .text-wrap strong { color: <?=$common_color?>; }
.retire-process .table-wrap .notice-box strong { color: <?=$common_color?>; }
.login .fieldset .btn.large {background-color: <?=$common_color?>;}
.newcomer-info .alert-text strong { color: <?=$common_color?>; }
.newcomer-info .alert-text p { color: <?=$common_color?>;}
.newcomer-info .step li.active:after { background-color: <?=$common_color?>; }
.newcomer-info .step li.active a { background-color: <?=$common_color?>;}
.motivation-main #content .data-list li ul li:first-child .thumb span { background-color: <?=$common_color?>; }
.diligence .content-header h3 {background-color: <?=$common_color?>;}
.diligence .calender-body .item-table .data-header .day.today { background-color: <?=$common_color?>;}
.diligence .communication .bx-wrapper ul li .info-wrap .today em { background-color: <?=$common_color?>; }
.mss-main .diligence-control .data-table .total.type01 { background-color: <?=$common_color?>; }
.mss-main .diligence-control .state-bar .bar span.total01 { background-color: <?=$common_color?>; }
.mss-main .take-leave .section-left .graph-wrap.myself .graph-state .graph .current { background-color: <?=$common_color?>; }
.supporting-progress .total-state .item-list strong { color: <?=$common_color?>; }
.supporting-progress .calender-body .item-table tbody td:first-child .day { color: <?=$common_color?>; }
.supporting-progress .calender-body .item-table .data-header .day.today { background-color: <?=$common_color?>; }
.leadership-intro li h4 em { color: <?=$common_color?>; }
.leadership-process .process li.type02 div { background-color: <?=$common_color?>; }
.case-guide .tab-cont .tit { color: <?=$common_color?>; }
.promote-system .program-table table tbody td strong { color: <?=$common_color?>; }
.hr-info .visual .mCS-rounded-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar, .hr-info .visual .mCS-rounded-dots-dark.mCSB_scrollTools .mCSB_dragger .mCSB_dragger_bar { background-color: <?=$common_color?>; }
.profile-wrap .item-info .info .number { color: <?=$common_color?>; }
.my-profile .profile-header .data-info .date { color: <?=$common_color?>; }
.content-header .next-page { background-color: <?=$common_color?>; }
.process .data-list li.type02 div { background-color: <?=$common_color?>; }
.process.skill01 .data-list li.type02 div { background-color: <?=$common_color?>; }
.process.skill03 .data-list li.type02 div { background-color: <?=$common_color?>; }
.ev-element .capability .capa-wrap.type03 .data-list li { background-color: <?=$common_color?>; }
.ev-result .data-list li strong { color: <?=$common_color?>; }
.result .section .category.performance h4 { background-color: <?=$common_color?>; }
.reward-section .reward-all .amount { color: <?=$common_color?>; }
.reward-section .mydate-list li .date { color: <?=$common_color?>; }
.reward-detalle { background-color: <?=$common_color?>;}
.reward-pay .section-wrap .section-left .notice { color: <?=$common_color?>; }
.reward-pay .pay-list .data-table tbody th.cellselected02, .reward-pay .pay-list .data-table tbody td.cellselected02 { color: <?=$common_color?>; }
.charge-info .charge-list h3 {color: <?=$common_color?>;}
</style>