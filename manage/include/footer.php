		
		
		<!-- Vendor -->
		<script src="assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
		<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
		<script src="assets/vendor/nanoscroller/nanoscroller.js"></script>
		<script src="assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script src="assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
		<script src="assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>
		<!-- Specific Page Vendor -->
		<script src="assets/vendor/jquery-appear/jquery-appear.js"></script>
		<script src="assets/vendor/owl.carousel/owl.carousel.js"></script>
		<script src="assets/vendor/isotope/isotope.js"></script>
        
		
		
		
		
		<script src="assets/vendor/jquery-ui/jquery-ui.js"></script>
		<script src="assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
		<script src="assets/vendor/jquery-appear/jquery-appear.js"></script>
		<script src="assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
		<script src="assets/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
		<script src="assets/vendor/flot/jquery.flot.js"></script>
		<script src="assets/vendor/flot.tooltip/flot.tooltip.js"></script>
		<script src="assets/vendor/flot/jquery.flot.pie.js"></script>
		<script src="assets/vendor/flot/jquery.flot.categories.js"></script>
		<script src="assets/vendor/flot/jquery.flot.resize.js"></script>
		<script src="assets/vendor/jquery-sparkline/jquery-sparkline.js"></script>
		<script src="assets/vendor/raphael/raphael.js"></script>
		<script src="assets/vendor/morris.js/morris.js"></script>
		<script src="assets/vendor/gauge/gauge.js"></script>
		<script src="assets/vendor/snap.svg/snap.svg.js"></script>
	
	

		
		<script src="assets/vendor/liquid-meter/liquid.meter.js"></script>
		<script src="assets/vendor/chartist/chartist.js"></script>
		
		
		
		
		<!-- Theme Base, Components and Settings -->
		<script src="assets/javascripts/theme.js"></script>
		
		<!-- Theme Custom -->
		<script src="assets/javascripts/theme.custom.js"></script>
		
		<!-- Theme Initialization Files -->
		<script src="assets/javascripts/theme.init.js"></script>

				<!-- Specific Page Vendor -->
				<script src="assets/vendor/select2/js/select2.js"></script>
		<script src="assets/vendor/jquery-datatables/media/js/jquery.dataTables.js"></script>
		<script src="assets/vendor/jquery-datatables/extras/TableTools/js/dataTables.tableTools.min.js"></script>
		<script src="assets/vendor/jquery-datatables-bs3/assets/js/datatables.js"></script>
		

		<!-- Examples -->
		<script src="assets/javascripts/dashboard/examples.landing.dashboard.js"></script>

			<!-- Examples -->
			<script src="assets/javascripts/tables/examples.datatables.default.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.row.with.details.js"></script>
		<script src="assets/javascripts/tables/examples.datatables.tabletools.js"></script>

	<script src="assets/javascripts/ui-elements/examples.modals.js"></script>
	
		<script src="assets/javascripts/dashboard/examples.dashboard.js"></script>
		<script src="assets/javascripts/ui-elements/examples.charts.js"></script>
	
	


<!-- <script src="assets/js/jquery-ui-1.9.2.custom.min.js"></script>
<script src="@resource/plugin/datepicker/datepicker.js"></script>		
<link rel="stylesheet" href="@resource/plugin/datepicker/datepicker.css" />		 -->


<!--		<script>
				jQuery(function($){
					$.datepicker.regional['ko'] = {
						closeText: '??????',
						prevText: '?????????',
						nextText: '?????????',
						currentText: '??????',
						monthNames: ['1???(JAN)','2???(FEB)','3???(MAR)','4???(APR)','5???(MAY)','6???(JUN)',
						'7???(JUL)','8???(AUG)','9???(SEP)','10???(OCT)','11???(NOV)','12???(DEC)'],
						monthNamesShort: ['1???','2???','3???','4???','5???','6???',
						'7???','8???','9???','10???','11???','12???'],
						dayNames: ['???','???','???','???','???','???','???'],
						dayNamesShort: ['???','???','???','???','???','???','???'],
						dayNamesMin: ['???','???','???','???','???','???','???'],
						weekHeader: 'Wk',
						dateFormat: 'yy-mm-dd',
						firstDay: 0,
						isRTL: false,
						showMonthAfterYear: true,
						yearSuffix: ''};
					$.datepicker.setDefaults($.datepicker.regional['ko']);
/*
					$('.m_date_pick').datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: 'c-100:c+2'
					});
					$('.m_date_pick1').datepicker({
						changeMonth: true,
						changeYear: true,
						yearRange: 'c-100:c+2',
						maxDate: "+2w",
						minDate: 0
					});
*/
					setTimeout(function(){ 
						$('#ui-datepicker-div').hide();
					},200);
				});
		</script>
		-->
		
		
		<script type="text/javascript">
		var xOffset = 10;
		var yOffset = 10;
		$('.thumbnail2').mouseover(function(e){
			$("body").append("<p id='preview'><img src='"+ $(this).attr("src") +"' width='400px' /></p>"); //????????? ???????????? ??????                       
			$("#preview").css("top",(e.pageY - yOffset) + "px")
			$("#preview").css("left",(e.pageX + xOffset) + "px")
			$("#preview").fadeIn("fast"); //???????????? ?????? ?????? ??????
		});
		$('.thumbnail2').mouseout(function(e){
				$("#preview").remove();
		});
		</script>
		<script>
$(document).ready(function(){	
	//$('.sidebar-toggle').click();
});
</script>

	</body>
</html>