$(document).ready(function () {
	// 디자인 form요소 적용
	$('select, input[type=radio], input[type=checkbox], input[type=file]').uniform({
		fileDefaultHtml: '',
		fileButtonHtml: '파일첨부'
	});



	$(function(){
		$(".input-search input:disabled").each(function(){
			$(this).parent().addClass('disabled');
		});
		$(this).removeAttr('disabled');
		$(this).parent(".input-search").removeClass('disabled');
	});



	//gnb
	$('.gnb .depth01').hover(function () {
			var eq = $('.depth01').index(this);

			$('.header-wrap').addClass('active');
			$('.gnb .depth01').removeClass('active');
			$('.gnb .depth01:eq(' + eq + ')').addClass('active');

		}, function () {});

		$('#header').hover(function(){},function(){
			$('.gnb .depth01').removeClass('active');
			var cnt= 0;
			$('.gnb .depth02').find('li').each(function(eq){
				if($(this).hasClass('active')){
					cnt++;
					$(this).parent('ul').parent('.depth01').addClass('active');
				}
			});
			
			if(cnt==0) $('.header-wrap').removeClass('active');
		});

	// hr-list open

	$('.hr-list dt').each(function(){
			
		var $target = $('.hr-list dt')
		$('dd:not(:first)').css('display','none');
		$target.click(function(e){

			if($('+dd',this).css('display')=='none'){
				$('dd').slideUp(250);
				$('+dd',this).slideDown(250);
				$target.removeClass('active');
				$(this).addClass('active');
			}
		})
		
	});




	// 탭
	$('.tab li a').each(function(){
		
		var this_href=$(this).attr('href');
		$(this_href).css('display','none').siblings('.tab-cont').first().css('display','block');
		$(this).click(function(e){
				
			e.preventDefault();
			//탭활성화
			$(this).parent('li').addClass('active').siblings('li').removeAttr('class');
			//타켓 디스플레이
			$(this_href).css('display','block').siblings('.tab-cont').css('display','none');
			// 프레임 사이즈 조절
            mainFrameResize();
			
		});
		
	});

	//탭 large 
	$('.tab.large').each(function () {
		var divide = $(this).find('li').size();
		$(this).find('li').each(function () {
			$(this).css('width', parseFloat(100 / divide) + '%');
		});
	});


	//top버튼
	$(".btn-top").addClass("btn-scrolldown").addClass("scroll");

	$(".btn-top").click(function (e) {
		e.preventDefault();
		if ($(this).hasClass('btn-scrolldown')) {
			var offset = $("#footer").offset().top;
			$("html, body").stop().animate({ scrollTop: offset }, 400, function () {
				//$('.btn-top').removeClass('btn-scrolldown').700('scroll');
			});
		} else {
			$("html, body").stop().animate({ scrollTop: 0 }, 400, function () {
				//$('.btn-top').addClass('btn-scrolldown').addClass('scroll');

			});
		}
	});

	$(window).on('scroll', function () {
		var wScrollTop = $(window).scrollTop();

		//var maxHeight = $(document).height() - $(window).height() - $('#footer').height();	
		if (wScrollTop > 0) {
			$('.btn-top').removeClass('btn-scrolldown').removeClass('scroll');
		} else {
			$('.btn-top').addClass('btn-scrolldown').addClass('scroll');
			$(".btn-top").css({ 'bottom': "50px", position: "" });
		}
	}).trigger("scroll");

	// button
	$(document).on('mousedown', 'button, input[type="button"], input[type="submit"]', function(e) {
		e.preventDefault();
	});

	// select
	$('.select-label').click(function () {
		$(this).parents('.data-down').toggleClass('active');
	});
	$('.select-list li').click(function () {
		$(this).parents('.select-list').siblings('.select-label').text($(this).text());
		$(this).parents('.data-down').removeClass('active');
	});


	//layer popup center
	jQuery.fn.center = function () {
		this.css("position", "absolute");
		this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop()) + 500 + "px");
		this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
		return this;
	}
	
	//layer popup open
	$('.open-laypop').each(function() {
		var $target = $(this).attr("href");
		$(this).click(function(e) {
			e.preventDefault();
			$('.layer-popup').hide();
			$($target).fadeIn(200).center();
			$('.dimmed').show();
			$('.layer-popup').append("<div class='dimmed'></div>");
		 });
	});

	// layer popup close
	$('.layer-pop .pop-close, .layer-pop .dimmed').click(function(){
		$('.dimmed').hide();
		$('.layer-pop').hide();
	});


	// mss header team menu
	$('.select-team .current').click(function() {
		$(this).parent('.select-team').toggleClass('active');
	});


	//전체메뉴 열림
	$('.all-menu').click(function(){
		$('.menu-wrap').addClass('active');
	});
	//전체메뉴 닫음
	$('.menu-wrap .btn-close').click(function(){
		$('.menu-wrap').removeClass('active');
	});

	try {
	//로딩
	var circle = new Sonic({

			width: 100,
			height: 100,

			stepsPerFrame: 1,
			trailLength: 1,
			pointDistance: .025,

			strokeColor: '#c41230',

			fps: 20,

			setup: function() {
				this._.lineWidth = 2;
			},
			step: function(point, index) {

				var cx = this.padding + 50,
					cy = this.padding + 50,
					_ = this._,
					angle = (Math.PI/180) * (point.progress * 360);

				this._.globalAlpha = Math.max(.5, this.alpha);

				_.beginPath();
				_.moveTo(point.x, point.y);
				_.lineTo(
					(Math.cos(angle) * 35) + cx,
					(Math.sin(angle) * 35) + cy
				);
				_.closePath();
				_.stroke();

				_.beginPath();
				_.moveTo(
					(Math.cos(-angle) * 32) + cx,
					(Math.sin(-angle) * 32) + cy
				);
				_.lineTo(
					(Math.cos(-angle) * 27) + cx,
					(Math.sin(-angle) * 27) + cy
				);
				_.closePath();
				_.stroke();

			},
			path: [
				['arc', 50, 50, 40, 0, 360]
			]

	});

	circle.play();
	$('#loading .wrap .bar').append(circle.canvas);
	$('#loading').append("<div class='dimmed'></div>").hide();
	
	//로딩
	var circle2 = new Sonic({
	    
	    width: 100,
	    height: 100,
	    
	    stepsPerFrame: 1,
	    trailLength: 1,
	    pointDistance: .025,
	    
	    strokeColor: '#c41230',
	    
	    fps: 20,
	    
	    setup: function() {
	        this._.lineWidth = 2;
	    },
	    step: function(point, index) {
	        
	        var cx = this.padding + 50,
	        cy = this.padding + 50,
	        _ = this._,
	        angle = (Math.PI/180) * (point.progress * 360);
	        
	        this._.globalAlpha = Math.max(.5, this.alpha);
	        
	        _.beginPath();
	        _.moveTo(point.x, point.y);
	        _.lineTo(
	                (Math.cos(angle) * 35) + cx,
	                (Math.sin(angle) * 35) + cy
	        );
	        _.closePath();
	        _.stroke();
	        
	        _.beginPath();
	        _.moveTo(
	                (Math.cos(-angle) * 32) + cx,
	                (Math.sin(-angle) * 32) + cy
	        );
	        _.lineTo(
	                (Math.cos(-angle) * 27) + cx,
	                (Math.sin(-angle) * 27) + cy
	        );
	        _.closePath();
	        _.stroke();
	        
	    },
	    path: [
	           ['arc', 50, 50, 40, 0, 360]
	           ]
	    
	});
	
	circle2.play();
	$('#extraLoading .wrap .bar').append(circle2.canvas);
	$('#extraLoading').append("<div class='dimmed'></div>").hide();
	} catch(e) {
	    // 통합결재(구) 문제로 인해 수정
	    //console.log("loading Error");
	}
	
	// 20171212, IE 팝업에서 새로 스크롤 발생 시 가로 스크롤도 함께 생기는 문제 수정.
    if( navigator.userAgent.indexOf("MSIE")>=0 || navigator.userAgent.indexOf("Trident")>=0 ){ // IE가 문제이므로 IE만 동작.
        if( $("body").find("div").hasClass("window-popup") ){ // IE면서 팝업화면일 경우 가로스크롤 생기는 문제
            //$.logger("팝업 맞다");
            $(".window-popup").parents("body").css( "overflow-x", "hidden" );
        }
    }
});


