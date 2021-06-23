// function itemHeight() {
// 	var $el = $('.b-section__page'),
// 		$bunner = $el.find('.b-section__bunner');
// 	if ($el.height() >= 835) {
// 		$('.b-itemImg__imgMain').css('top', '65px');
// 		$bunner.hide();
// 		if ($el.height() >= 900) {
// 			$bunner.show()
// 		}
// 	} else {
// 		$('.b-itemImg__imgMain').css('top', '0px');
// 		$bunner.hide()
// 	}
// }

// function scrollStyle() {
// 	if ($(window).width() >= 991) {
// 		$(".js-scrollBar").niceScroll({
// 			cursorcolor: "#333",
// 			cursoropacitymin: 0.8,
// 			cursorwidth: 10,
// 			enablemousewheel: !1
// 		})
// 	} else {}
// }
$(document).ready(function () {
	// $(".fancybox").fancybox({
	// 	padding: 0,
	// 	helpers: {
	// 		overlay: {
	// 			locked: !1
	// 		},
	// 		title: {
	// 			type: 'outside'
	// 		},
	// 		thumbs: {
	// 			width: 80,
	// 			height: 80
	// 		}
	// 	}
	// });
	// scrollStyle();
	// itemHeight();
	// $('.b-slider__item').hover(function () {
	// 	var productTrue = $(this).find('.b-slider__optionStatus').hasClass('b-slider__optionStatus--inStock');
	// 	$basket = $(this).find('.b-slider__optionBasket'), $oneClick = $(this).find('.b-slider__oneClick');
	// 	if (!($basket.hasClass('active')) && productTrue) {
	// 		$oneClick.show(300)
	// 	}
	// });
	// $('.b-slider__item').mouseleave(function () {
	// 	var $basket = $(this).find('.b-slider__optionBasket'),
	// 		$oneClick = $(this).find('.b-slider__oneClick');
	// 	if (!($basket.hasClass('active'))) {
	// 		$oneClick.slideUp(300)
	// 	}
	// });
	// $('.js-blockAncher').click(function (e) {
	// 	var id = $(this).attr('href');
	// 	var top = $(id).offset().top - 30;
	// 	if (top < 0) {
	// 		top = 0
	// 	};
	// 	$('body,html').animate({
	// 		scrollTop: top
	// 	}, 1000);
	// 	e.preventDefault()
	// });
	// $('.js-tabWrap').on('click', '[data-tab]', function (e) {
	// 	var $wrap = $(this).closest('.js-tabWrap');
	// 	$wrap.find('[data-tab]').removeClass('active');
	// 	$(this).addClass('active');
	// 	var target = '[data-tabContent=' + $(this).attr('data-tab') + ']';
	// 	$wrap.find('[data-tabContent]').hide();
	// 	$wrap.find(target).show();
	// 	var $tabSlider = $(target).find('.b-slider__list');
	// 	var $slider = $tabSlider,
	// 		$navLeft = $tabSlider.closest('.b-slider').find('.b-slider__nav--left'),
	// 		$navRight = $tabSlider.closest('.b-slider').find('.b-slider__nav--right'),
	// 		$paginator = $tabSlider.closest('.b-slider').find('.b-slider__paginator');
	// 	var maxScreen = +$tabSlider.attr('data-max');
	// 	var itemWidth = 300;
	// 	var last = $slider.hasClass('b-slider__list--last') && $(window).width() >= '991' && $(window).width() <= '1279';
	// 	if (last) {
	// 		maxScreen = 3
	// 	}
	// 	if ($(window).width() <= '768') {
	// 		itemWidth = 480
	// 	};
	// 	if ($slider.hasClass('b-slider__list--foto')) {
	// 		itemWidth = 300
	// 	}
	// 	$slider.carouFredSel({
	// 		direction: "left",
	// 		responsive: !0,
	// 		scroll: {
	// 			items: 1,
	// 			duration: 100000,
	// 			pauseOnHover: !0
	// 		},
	// 		items: {
	// 			width: itemWidth,
	// 			visible: {
	// 				min: 1,
	// 				max: maxScreen
	// 			}
	// 		},
	// 		prev: {
	// 			button: $navLeft,
	// 		},
	// 		next: {
	// 			button: $navRight,
	// 		},
	// 		pagination: $paginator
	// 	});
	// 	e.preventDefault()
	// });
	// $('.td.option i').on('click', function (e) {
	// 	$(this).toggleClass('active')
	// });
	// $(window).on("load", function () {
	// 	// slider();
	// 	// $('.b-tab__content').hide();
	// 	// $('.b-tab__content.active').show()
	// 	// if ($(window).width() <= '991') {
	// 	// 	$('.b-foto__slider').hide()
	// 	// } else {
	// 	// 	var $sliderWidth = $('.caroufredsel_wrapper');
	// 	// 	$sliderWidth.each(function () {
	// 	// 		var sliderWidth = $(this).width() + 10;
	// 	// 		$(this).width(sliderWidth)
	// 	// 	})
	// 	// }
	// 	// $('#ascrail2000-hr').addClass('fixed');
	// 	// $('.b-footer').waypoint({
	// 	// 	handler: function (event, direction) {
	// 	// 		if (direction === "down") {
	// 	// 			$('#ascrail2000-hr').removeClass('fixed')
	// 	// 		} else {
	// 	// 			$('#ascrail2000-hr').addClass('fixed')
	// 	// 		}
	// 	// 	},
	// 	// 	offset: '98%'
	// 	// })
	// });

	// function slider() {
	// 	if ($('.b-slider__list').length > 0) {
	// 		$('.b-slider__list').each(function () {
	// 			var $slider = $(this),
	// 				$navLeft = $(this).siblings('.b-slider__nav--left'),
	// 				$navRight = $(this).siblings('.b-slider__nav--right'),
	// 				$paginator = $(this).siblings('.b-slider__paginator');
	// 			var maxScreen = +$(this).attr('data-max');
	// 			var itemWidth = 300;
	// 			var last = $slider.hasClass('b-slider__list--last') && $(window).width() >= '991' && $(window).width() <= '1279';
	// 			if (last) {
	// 				maxScreen = 3
	// 			}
	// 			if ($(window).width() <= '768') {
	// 				itemWidth = 480
	// 			};
	// 			if ($slider.hasClass('b-slider__list--foto')) {
	// 				itemWidth = 300
	// 			}
	// 			$slider.carouFredSel({
	// 				direction: "left",
	// 				responsive: !0,
	// 				scroll: {
	// 					items: 1,
	// 					duration: 1000,
	// 					pauseOnHover: !0
	// 				},
	// 				items: {
	// 					width: itemWidth,
	// 					visible: {
	// 						min: 1,
	// 						max: maxScreen
	// 					}
	// 				},
	// 				prev: {
	// 					button: $navLeft,
	// 				},
	// 				next: {
	// 					button: $navRight,
	// 				},
	// 				pagination: $paginator
	// 			})
	// 		})
	// 	}
	// };
	// $(window).resize(function () {
	// 	itemSlide();
	// 	// scrollStyle();
	// 	itemHeight();
	// 	$('.b-slider__list').each(function () {
	// 		var $slider = $(this),
	// 			$navLeft = $(this).closest('.b-slider').find('.b-slider__nav--left'),
	// 			$navRight = $(this).closest('.b-slider').find('.b-slider__nav--right'),
	// 			$paginator = $(this).closest('.b-slider').find('.b-slider__paginator');
	// 		var maxScreen = +$(this).attr('data-max');
	// 		var itemWidth = 300;
	// 		var last = $slider.hasClass('b-slider__list--last') && $(window).width() >= '991' && $(window).width() <= '1279';
	// 		if (last) {
	// 			maxScreen = 3
	// 		}
	// 		if ($(window).width() <= '768') {
	// 			itemWidth = 480
	// 		};
	// 		if ($slider.hasClass('b-slider__list--foto')) {
	// 			itemWidth = 300
	// 		}
	// 		$slider.carouFredSel({
	// 			direction: "left",
	// 			responsive: !0,
	// 			scroll: {
	// 				items: 1,
	// 				duration: 1000,
	// 				pauseOnHover: !0
	// 			},
	// 			items: {
	// 				width: itemWidth,
	// 				visible: {
	// 					min: 1,
	// 					max: maxScreen
	// 				}
	// 			},
	// 			prev: {
	// 				button: $navLeft,
	// 			},
	// 			next: {
	// 				button: $navRight,
	// 			},
	// 			pagination: $paginator
	// 		})
	// 	})
	// });

	// function itemSlide() {
	// 	var direction;
	// 	($(window).width() <= '991') ? direction = 'right': direction = 'up';

	// 	$('.b-itemImg__imgList').carouFredSel({
	// 		direction: direction,
	// 		responsive: !0,
	// 		scroll: {
	// 			items: 1,
	// 			duration: 1000,
	// 			pauseOnHover: !1
	// 		},
	// 		items: {
	// 			width: 80,
	// 			height: 80,
	// 			visible: {
	// 				min: 1,
	// 				max: 4
	// 			}
	// 		},
	// 		prev: {
	// 			button: $('.b-itemImg__imgList-top')
	// 		},
	// 		next: {
	// 			button: $('.b-itemImg__imgList-bottom')
	// 		}
	// 	});

	// }
	// itemSlide();
	// $("a[rel^='prettyPhoto']").prettyPhoto({
	// 	social_tools: '',
	// 	autoplay: !1,
	// 	show_title: !1
	// });
	// var clock, timer = $('#clock').attr('data-time');
	// clock = $('#clock').FlipClock({
	// 	clockFace: 'DailyCounter',
	// 	autoStart: !1,
	// 	language: 'russian'
	// });
	// clock.setTime(timer);
	// clock.setCountdown(!0);
	// clock.start();
	// $('.js-index').click(function () {
	// 	if ($(window).width() <= '991') {
	// 		$('.js-menu').toggle(500)
	// 	}
	// });
	// if ($(window).width() <= '991') {
	// 	$('.search').remove();
	// 	$('.b-menu').append('<span class="search"></span>');
	// 	$('.search').click(function () {
	// 		$('.b-header__serach').slideToggle()
	// 	})
	// };
	// $(window).resize(function () {
	// 	if ($(window).width() <= '991') {
	// 		$('.search').remove();
	// 		$('.b-menu').append('<span class="search"></span>');
	// 		$('.search').click(function () {
	// 			$('.b-header__serach').slideToggle()
	// 		})
	// 	} else {
	// 		$('.search').remove()
	// 	}
	// })
	// $('.b-slider__optionNone').each(function () {
	// 	var text = $(this).text();
	// 	if (text == '') {
	// 		$(this).text('Аналоги')
	// 	}
	// });
	// $('.b-filter__currency--productFilter').on('click', '.b-filter__currencyLink', function (e) {
	// 	e.preventDefault()
	// 	$('.b-filter__currency--productFilter').find('.b-filter__currencyLink').removeClass('active');
	// 	$(this).addClass('active');
	// });
	// $('.b-foto__show').click(function () {
	// 	$(this).siblings('.b-foto__slider').slideDown();
	// 	$(this).slideUp()
	// });


	// $('.b-counter__nav--minus').click(function () {
	// 	var area = $(this).siblings('.b-counter__area');
	// 	var value = +area.val();
	// 	area.val(++value)
	// });
	// $('.b-counter__nav--plus').click(function () {
	// 	var area = $(this).siblings('.b-counter__area');
	// 	var value = +area.val();
	// 	if (value > 1) {
	// 		area.val(--value)
	// 	}
	// });

	// $('.b-counter__area').on("change keyup input click", function () {
	// 	if (this.value.match(/[^0-9]/g)) {
	// 		this.value = this.value.replace(/[^0-9]/g, '')
	// 	}
	// });

	// $('.b-catList__link i').click(function (e) {
	// 	if ($(this).hasClass('active')) {
	// 		$(this).closest('a').siblings('.b-catList__sub').slideUp();
	// 		$(this).removeClass('active');
	// 		e.preventDefault()
	// 	} else {
	// 		$('.b-catList__sub').slideUp();
	// 		$('.b-catList__link i').removeClass('active');
	// 		$(this).addClass('active');
	// 		$(this).closest('a').siblings('.b-catList__sub').slideDown();
	// 		e.preventDefault()
	// 	}
	// });

	// $('.b-catList__subShow').click(function (e) {
	// 	$(this).siblings('.b-catList__subList').animate({
	// 		height: 'auto'
	// 	}, 1000).css('max-height', 'none');
	// 	$(this).slideUp();
	// 	e.preventDefault()
	// });

	// $('.js-show').click(function (e) {
	// 	var $target = $(this).siblings('.js-hideContent');
	// 	if ($(this).hasClass('active')) {
	// 		$(this).text('Показать все').removeClass('active');
	// 		$target.slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).text('Скрыть').addClass('active');
	// 		$target.slideDown();
	// 		e.preventDefault()
	// 	}
	// });

	// $('.b-detal__characteristicsMore').click(function (e) {
	// 	var $target = $(this).siblings('.b-detal__characteristics-TableWrap--hide');
	// 	if ($(this).hasClass('active')) {
	// 		$(this).text('Показать все').removeClass('active');
	// 		$target.slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).text('Скрыть').addClass('active');
	// 		$target.slideDown();
	// 		e.preventDefault()
	// 	}
	// });

	
	// $('.b-detal__videoTitle').click(function (e) {
	// 	$('.b-detal__videoWrap').slideToggle()
	// });
	// $('.b-item__reviewMore').click(function (e) {
	// 	var $target = $(this).siblings('.b-item__reviewText--hide');
	// 	if ($(this).hasClass('active')) {
	// 		$(this).text('Читать все').removeClass('active');
	// 		$target.slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).text('Скрыть').addClass('active');
	// 		$target.slideDown();
	// 		e.preventDefault()
	// 	}
	// });
	// $('.b-text__showMore').click(function (e) {
	// 	var $target = $(this).siblings('.b-text__content--hide');
	// 	if ($(this).hasClass('active')) {
	// 		$(this).text('Показать еще').removeClass('active');
	// 		$target.slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).text('Скрыть').addClass('active');
	// 		$target.slideDown();
	// 		e.preventDefault()
	// 	}
	// });

	// $('.b-header__searchInput').focus(function () {
	// 	$('.b-header__searchList').slideDown()
	// });

	// $('.b-header__searchInput').focusout(function () {
	// 	function hide() {
	// 		$('.b-header__searchList').slideUp();
	// 		$('.b-header__searchInput').val('')
	// 	};
	// 	setTimeout(hide, 500)
	// });
	// $('.b-header__optionLink--basket').click(function (e) {
	// 	if ($(this).hasClass('active')) {
	// 		$(this).removeClass('active');
	// 		$(this).siblings('.b-header__basketList').slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).addClass('active');
	// 		$(this).siblings('.b-header__basketList').slideDown();
	// 		e.preventDefault()
	// 	}
	// });
	
	
	

	// $('.b-filter__more').click(function (e) {
	// 	if ($(this).hasClass('active')) {
	// 		$(this).removeClass('active');
	// 		$(this).siblings('.b-filter__list--hide').slideUp();
	// 		$(this).text('Показать еще');
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).addClass('active');
	// 		$(this).siblings('.b-filter__list--hide').slideDown();
	// 		$(this).text('Скрыть');
	// 		e.preventDefault()
	// 	}
	// });
	// $('.js-title').on('click', function (e) {
	// 	if ($(window).width() <= 768) {
	// 		$(this).toggleClass('active');
	// 		$(this).siblings('.js-hide').slideToggle()
	// 	}
	// 	e.preventDefault()
	// });
	// $('.b-footer__title').click(function (e) {
	// 	if ($(window).width() <= '991') {
	// 		if ($(this).hasClass('active')) {
	// 			$(this).removeClass('active');
	// 			$(this).siblings('.b-footer__list').slideUp();
	// 			e.preventDefault()
	// 		} else {
	// 			$('.b-footer__title').removeClass('active');
	// 			$(this).addClass('active');
	// 			$('.b-footer__list').slideUp();
	// 			$(this).siblings('.b-footer__list').slideDown();
	// 			e.preventDefault()
	// 		}
	// 	}
	// });
	// $('.b-footer__anchor').click(function (e) {
	// 	$('html, body').animate({
	// 		scrollTop: $('body').offset().top
	// 	}, 500);
	// 	e.preventDefault()
	// });
	// $('.b-mobButton').click(function (e) {
	// 	if ($(this).hasClass('active')) {
	// 		$(this).removeClass('active');
	// 		$(this).siblings('.b-header__list').slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).addClass('active');
	// 		$(this).siblings('.b-header__list').slideDown();
	// 		e.preventDefault()
	// 	}
	// });
	// $('.js-menuButton').click(function (e) {
	// 	$(this).toggleClass('active');
	// 	$(this).siblings('.js-menu').slideToggle();
	// 	e.preventDefault()
	// });
	// $('.js-subTitle').on('click', function (e) {
	// 	$(this).toggleClass('active');
	// 	$(this).next('.js-subItem').slideToggle();
	// 	e.preventDefault()
	// });
	// $('.b-info__title').click(function (e) {
	// 	if ($(this).hasClass('active')) {
	// 		$(this).removeClass('active');
	// 		$(this).siblings('.b-info__text').slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).addClass('active');
	// 		$(this).siblings('.b-info__text').slideDown();
	// 		e.preventDefault()
	// 	}
	// });
	
	// $('.b-alphabet__letter').click(function () {
	// 	if ($(window).width() <= 991) {
	// 		$(this).toggleClass('active');
	// 		$(this).siblings('.b-alphabet__list').slideToggle('active')
	// 	}
	// });
	// $('.b-basket__showMore').click(function (e) {
	// 	if ($(this).hasClass('active')) {
	// 		$(this).removeClass('active');
	// 		$(this).siblings('.b-basket__option').slideUp();
	// 		e.preventDefault()
	// 	} else {
	// 		$(this).addClass('active');
	// 		$(this).siblings('.b-basket__option').slideDown();
	// 		e.preventDefault()
	// 	}
	// });
	
	// $('.b-menu--hide').find('.b-menu__mainLink').removeClass('active');
	// $('.b-filter__blockLink').click(function (e) {
	// 	$('.b-filter__blockWrap').animate({
	// 		width: '220px'
	// 	}, 500);
	// 	$('.b-item').animate({
	// 		left: '220px'
	// 	}, 500);
	// 	$(this).slideUp();
	// 	e.preventDefault()
	// });
	// var widthScreen = $(window).width();
	// $('.submenu_in').click(function (e) {
	// 	var $sub = $(this).siblings('.js-subMenu');
	// 	if ($(window).width() <= '991') {
	// 		if ($(this).hasClass('active')) {
	// 			$(this).removeClass('active');
	// 			$('.js-subMenu').slideUp();
	// 			e.preventDefault()
	// 		} else {
	// 			$('.submenu_in').removeClass('active');
	// 			$(this).addClass('active');
	// 			$('.js-subMenu').slideUp();
	// 			$sub.slideDown();
	// 			e.preventDefault()
	// 		}
	// 	}
	// });
	// $('.b-header__submenu-link').click(function (e) {
	// 	if ($(window).width() <= '991') {
	// 		$('.submenu_in').removeClass('active');
	// 		$('.js-subMenu').slideUp();
	// 		$('html, body').animate({
	// 			scrollTop: $('body').offset().top
	// 		}, 500)
	// 		e.preventDefault()
	// 	}
	// });
	
	
	// $('.b-slider__optionList').on('click', 'a', function (e) {
	// 	$(this).toggleClass('active');
	// 	e.preventDefault()
	// });
	// $('.b-filter__close').on('click', 'span', function () {
	// 	$('.b-filter__blockWrap').animate({
	// 		width: '0px'
	// 	}, 500);
	// 	$('.b-item').animate({
	// 		left: '0px'
	// 	}, 500);
	// 	$('.b-filter__blockLink').slideDown()
	// });
	// $(document).click(function (e) {
	// 	// if ($(e.target).closest(".js-menuButton").length == 0 && $(e.target).closest(".js-menu").length == 0 && (($(window).width() <= '991') || $('.b-menu').hasClass('b-menu--hide'))) {
	// 	// 	$('.js-menu').slideUp();
	// 	// 	$('.js-menuButton').removeClass('active')
	// 	// }
	// 	// if ($(e.target).closest(".b-header__optionItem--basket").length == 0) {
	// 	// 	$('.b-header__basketList').slideUp()
	// 	// }
	// 	// if ($(e.target).closest(".b-menu__item").length == 0) {
	// 	// 	$('.js-subMenu').slideUp();
	// 	// 	$('.submenu_in').removeClass('active')
	// 	// }
	// 	// if ($(e.target).closest(".js_listWrap").length == 0) {
	// 	// 	$('.b-header__currencyBlock').slideUp();
	// 	// 	$('.b-header__contactNavBlock').slideUp();
	// 	// 	$('.js_listLink').removeClass('active')
	// 	// }
	// 	// if ($(e.target).closest(".b-filter__blockWrap").length == 0 && $(e.target).closest(".b-filter__blockLink").length == 0 && ($(window).width() <= '991')) {
	// 	// 	$('.b-filter__blockWrap').animate({
	// 	// 		width: '0px'
	// 	// 	}, 500);
	// 	// 	$('.b-item').animate({
	// 	// 		left: '0px'
	// 	// 	}, 500);
	// 	// 	$('.b-filter__blockLink').slideDown()
	// 	// }
	// });


	
	// $.datepicker.setDefaults($.extend($.datepicker.regional.ru));

	// function formatDate(date) {
	// 	var dd = date.getDate();
	// 	if (dd < 10) dd = '0' + dd;
	// 	var mm = date.getMonth() + 1;
	// 	if (mm < 10) mm = '0' + mm;
	// 	var yy = date.getFullYear() % 100;
	// 	if (yy < 10) yy = '0' + yy;
	// 	return dd + '.' + mm + '.' + yy
	// }
	// var d = new Date(2014, 0, 30);
	// var formDate = formatDate(d);

	// $("#datapicker").attr("placeholder", formDate);
	// // $("#datapicker").datepicker();
	// if ($(window).width() <= '991') {
	// 	var pageYLabel = 0;
	// 	var checkScroll = !0;
	// 	var timeScroll = 800;
	// 	window.onscroll = function () {
	// 		var pageY = window.pageYOffset || document.documentElement.scrollTop;
	// 		var innerHeight = document.documentElement.clientHeight;
	// 		var path = $('.b-section__anchor').attr('data-path');
	// 		if (checkScroll) {
	// 			switch (path) {
	// 				case '':
	// 					if (pageY > innerHeight) {
	// 						$('.b-section__anchor').attr('data-path', 'up')
	// 					}
	// 					break;
	// 				case 'up':
	// 					if (pageY < innerHeight) {
	// 						$('.b-section__anchor').attr('data-path', '')
	// 					}
	// 					break;
	// 				case 'down':
	// 					if (pageY > innerHeight) {
	// 						$('.b-section__anchor').attr('data-path', 'up')
	// 					}
	// 					break
	// 			}
	// 		}
	// 	}
	// };
	// $('.b-section__anchor').click(function (e) {
	// 	var pageY = window.pageYOffset || document.documentElement.scrollTop;
	// 	var path = $(this).attr('data-path');
	// 	switch (path) {
	// 		case 'up':
	// 			$(this).attr('data-path', 'down');
	// 			pageYLabel = pageY;
	// 			checkScroll = !1;
	// 			$("html,body").animate({
	// 				"scrollTop": 0
	// 			}, timeScroll);
	// 			window.setTimeout(function () {
	// 				checkScroll = !0
	// 			}, timeScroll);
	// 			break;
	// 		case 'down':
	// 			$(this).attr('data-path', 'up');
	// 			checkScroll = !1;
	// 			$("html,body").animate({
	// 				"scrollTop": pageYLabel
	// 			}, timeScroll);
	// 			window.setTimeout(function () {
	// 				checkScroll = !0
	// 			}, timeScroll);
	// 			break
	// 	};
	// 	e.preventDefault()
	// });

	// function textCut() {
	// 	$('.b-slider__list--last .b-slider__title, .b-slider__newsText, .b-main__articleLink span').textTailor({
	// 		minFont: 9,
	// 		fit: !1,
	// 		preWrapText: !1,
	// 		lineHeight: 'inherit',
	// 		resizable: !0,
	// 		ellipsis: !0,
	// 		debounce: !1,
	// 		center: !1,
	// 		justify: !1
	// 	})
	// }
	// setTimeout(function () {
	// 	textCut()
	// }, 1000);
	// $('.slider-for').slick({
	// 	slidesToShow: 1,
	// 	slidesToScroll: 1,
	// 	arrows: !1,
	// 	fade: !0,
	// 	asNavFor: '.slider-nav'
	// });
	// $('.slider-nav').slick({
	// 	slidesToShow: 4,
	// 	slidesToScroll: 1,
	// 	asNavFor: '.slider-for',
	// 	dots: !1,
	// 	centerMode: !1,
	// 	focusOnSelect: !0
	// });
	// $(".js-menuWeypoint").waypoint({
	// 	handler: function (event, direction) {
	// 		var $navWrap = $(this),
	// 			$nav = $navWrap.find('.b-nav__menu-wrap');
	// 		var navHeight = $nav.outerHeight();
	// 		if (direction == 'down') {
	// 			$navWrap.css({
	// 				'height': navHeight
	// 			});
	// 			$nav.addClass("fixed").stop().css("top", -navHeight).animate({
	// 				"top": 0
	// 			})
	// 		} else {
	// 			$navWrap.css({
	// 				'height': 'auto'
	// 			});
	// 			$nav.removeClass("fixed").stop().animate({
	// 				"top": ''
	// 			})
	// 		}
	// 	},
	// 	offset: function () {
	// 		var $nav = $(".js-menuWeypoint").find('.b-nav__menu-wrap');
	// 		return -($nav.outerHeight())
	// 	}
	// });
	// $('.js-anchor').click(function () {
	// 	var el = $(this).attr('href'),
	// 		top = $(el).offset().top - $('.b-nav__menu-wrap').outerHeight() - 30;
	// 	console.log(top);
	// 	$('html, body').animate({
	// 		scrollTop: top
	// 	}, 500);
	// 	return !1
	// });
	// var sections = $('.js-anchorContent');
	// var nav_links = $('.b-nav__menu-link');
	// sections.waypoint({
	// 	handler: function (event, direction) {
	// 		var activeSection = $(this);
	// 		if (direction === "up") {
	// 			activeSection = activeSection.prevAll('.js-anchorContent')
	// 		}
	// 		var activeLink = $('.b-nav__menu-link[href="#' + activeSection.attr("id") + '"]');
	// 		nav_links.removeClass("active");
	// 		activeLink.addClass("active")
	// 	},
	// 	offset: '35%'
	// })
})