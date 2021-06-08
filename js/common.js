/*IOS scroll*/
var body = document.body,
  currentPos = 0;

function hideScroll() {
  var intElemScrollTop = parseInt(window.pageYOffset),
    topElem = body.style.top;
  currentPos = intElemScrollTop;
  body.classList.add('hide--scroll');
  body.style.position = 'fixed';
  topElem = intElemScrollTop * (-1);
  body.style.top = topElem + 'px';
}

function showScroll() {
  body.classList.remove('hide--scroll');
  body.style.position = '';
  body.style.top = '';
  window.scrollTo(0, currentPos);
}

function validateForm(form) {
  var currentForm = form,
    save = currentForm.find("[type=submit]"),
    inputText = currentForm.find("input[type=text]"),
    inputEmail = currentForm.find("input[type=email]"),
    inputPhone = currentForm.find("input[type=tel]");

  inputText.on("input", function () {
    currentForm.removeClass("is--valid");
  })

  inputEmail.on("input", function () {
    currentForm.removeClass("is--valid");
  })

  inputPhone.on("input", function () {
    currentForm.removeClass("is--valid");
  })

  save.on("click", function () {

    if (currentForm.hasClass("is--valid")) {
      return true;
    } else {
      /************Валидация поля inputText*************/
      inputText.each(function (i) {
        var el = $(this);

        if (el.val() !== "" && el.length) {
          el.siblings("p").remove();
          el.removeClass("is--error");
        } else {
          el.siblings("p").remove();
          el.addClass("is--error");
          el.after("<p>Поле обязательно для заполнения.</p>");
        }
      })
      /************конец Валидации поля inputText*************/

      /************Валидация поля inputPhone*************/
      inputPhone.each(function (i) {
        var el = $(this);

        if (el.val() !== "" && el.length) {
          el.siblings("p").remove();
          el.removeClass("is--error");
        } else {
          el.siblings("p").remove();
          el.addClass("is--error");
          el.after("<p>Поле обязательно для заполнения.</p>");
        }
      });

      /************конец Валидации поля inputPhone*************/

      /************Валидация поля E-mail*************/
      inputEmail.each(function (i) {
        var el = $(this);

        if (el.val() !== "" && el.length) {
          var patternEmail = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
          if (patternEmail.test(el.val())) {
            el.removeClass("is--error");
            el.siblings("p").remove();
          } else {
            el.siblings("p").remove();
            el.after("<p>Не верный формат.</p>");
          }

        } else {
          el.siblings("p").remove();
          el.addClass("is--error");
          el.after("<p>Поле обязательно для заполнения.</p>");
        }
      });

      /************конец Валидации поля E-mail*************/

      /************Проверка нет ли ошибок в полях*************/
      if (currentForm.find(".bv-form__input").hasClass("is--error")) {
        currentForm.removeClass("is--valid");
      } else {
        currentForm.addClass("is--valid");
        save.click();
      }
      /************конец проверки нет ли ошибок в полях*************/

      return false;
    }
  });
}

function checkListScreen() {
  if ($(window).width() <= 767) {
    $("#products_ul").addClass("is--show");
  }

  if ($(window).width() <= 767 && $("#products_ul").attr("data-screen") == "screenTable" || $(window).width() <= 767 && $("#products_ul").attr("data-screen") == "screenItems") {
    $("#products_ul").attr("data-screen", "screenList");
    $(".b-filter__screenLink--type2").removeClass("active");
    $(".b-filter__screenLink--type3").removeClass("active");
    $(".b-filter__screenLink--type1").addClass("active");
  }
}

var openPopup = function (popupName) {
  var popups = $(".pop-ups");

  if (!$("body").hasClass("hide--scroll")) {
    hideScroll();
  }

  var currentPopup = popups.find('.pop__up[data-name-popup=' + popupName + ']');

  popups.addClass('is--active');

  setTimeout(function () {
    popups.addClass('is--fade');
  }, 100)

  setTimeout(function () {
    currentPopup.addClass('is--show');
  }, 200)
}

window.openPopup = openPopup;

$(document).ready(function () {

  /**Mask phone**/
  if ($("[type='tel']").length) {
    $("[type='tel']").mask("+375 (99) 999 99 99");
  }

  $(".js-anchor").on("click", function (e) {
    e.preventDefault();
    $('html, body').animate({
      scrollTop: $("body").find($(this).attr("href")).offset().top - 80,
    }, 800);
  })

  if ((window.location.hash) && (window.location.hash == '#thanks')) {
    setTimeout(function () {
      $("#thanks_btn").trigger("click");
    }, 500);
  }

  $(".b-detal__aboutTitle.js-title").on("click", function () {
    $(this).toggleClass("is--open");
  })

  $(".accordion-header").on("click", function (e) {
    e.preventDefault();
    var el = $(this),
      parent = el.parents(".accordion-item"),
      wrap = el.parents(".accordion");

    if (parent.hasClass("active")) {
      parent.removeClass("active");
    } else {
      wrap.find(".active").removeClass("active")
      parent.addClass("active");
    }
  })

  $("#banner3d").on("click", function () {
    $(this).hide();

    $("#frame-3d").show();
  })

  $("#frame-3d .close-3d").on("click", function () {
    $(this).parent().hide();

    $("#banner3d").show();
  })

  $('.b-filter__categoryList-button').click(function (e) {
    var $target = $(this).siblings('.b-filter__categoryList');
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $target.slideUp();
      e.preventDefault()
    } else {
      $(this).addClass('active');
      $target.slideDown();
      e.preventDefault()
    }
  });


  $('.js_listLink').click(function (e) {
    var $wrap = $(this).closest('.js_listWrap');
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
      $wrap.find('.js_listBlock').slideUp();
      e.preventDefault()
    } else {
      $(this).addClass('active');
      $wrap.find('.js_listBlock').slideDown();
      e.preventDefault()
    }
  });


  $('.b-delivery__cityList-region').click(function () {
    if ($(window).width() <= '991') {
      $('.b-delivery__cityList-list').slideUp();
      $(this).siblings('.b-delivery__cityList-list').slideDown()
    }
  });


  if ($(window).width() < 768 && $(".list-brands").length) {
    $(".list-brands").attr("data-screen", "screenList")
  }


  $('.b-delivery__title').click(function (e) {
    if ($(window).width() <= '991') {
      $(this).toggleClass('active');
      $(this).siblings('.b-delivery__productList').slideToggle();
      e.preventDefault()
    }
  });

  $('.b-catList__link i').click(function (e) {
    if ($(this).hasClass('active')) {
      $(this).closest('a').siblings('.b-catList__sub').slideUp();
      $(this).removeClass('active');
      e.preventDefault()
    } else {
      $('.b-catList__sub').slideUp();
      $('.b-catList__link i').removeClass('active');
      $(this).addClass('active');
      $(this).closest('a').siblings('.b-catList__sub').slideDown();
      e.preventDefault()
    }
  });

  $('.b-catList__subShow').click(function (e) {
    $(this).siblings('.b-catList__subList').animate({
      height: 'auto'
    }, 1000).css('max-height', 'none');
    $(this).slideUp();
    e.preventDefault()
  });

  $("#rassrochka_variants").html($("#rassrochka_variants_hidden").html());
  $("#rassrochka_variants_hidden").html("");
  rassrochka_var_class_refresh();

  $(".rassrochka_var_input").on("change", function () {
    $("#product_rassrochka").val($(".rassrochka_var_input:checked").data('prod'));
  });


  /**vars*/
  var popin = $("#popin"),
    listBtn = $("#list-btn"),
    list = $("#list"),
    searchInput = $("#search_words"),
    searchPrompt = $("#prompt"),
    searchRes = $("#search_results"),
    micro = $("#mic"),
    sandwich = $("#sandwich"),
    windowWidth = $(window).width(),
    browsers = {
      isAndroid: /Android/.test(navigator.userAgent),
      isCordova: !!window.cordova,
      isEdge: /Edge/.test(navigator.userAgent),
      isFirefox: /Firefox/.test(navigator.userAgent),
      isChrome: /Google Inc/.test(navigator.vendor),
      isChromeIOS: /CriOS/.test(navigator.userAgent),
      isChromiumBased: !!window.chrome && !/Edge/.test(navigator.userAgent),
      isIE: /Trident/.test(navigator.userAgent),
      isIOS: /(iPhone|iPad|iPod)/.test(navigator.platform),
      isOpera: /OPR/.test(navigator.userAgent),
      isSafari: /Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent),
      isTouchScreen: ('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch,
      isWebComponentsSupported: 'registerElement' in document && 'import' in document.createElement('link') && 'content' in document.createElement('template')
    },
    nav = $("#navigation"),
    header = $("#header"),
    anchor = $(".b-footer__anchor"),
    footerNavTitle = $('.b-footer__title'),
    video = $('.video-wrap'),
    filterBtn = $("#filter-btn"),
    filter = $("#filter-wrap"),
    closeFilter = $("#hide-filter"),
    showAll = $('.b-filter-toggle'),
    showLinks = $(".popular-links-show"),
    mobSearchBtn = $("#mobile-search"),
    mobSearch = $("#mobile-search-body"),
    popularLinks = $(".popular_links"),
    likeBtn = $("#like-mobile"),
    dataVal = $("[data-value]"),
    carousel = $(".slick-carousel"),
    cloudimage = $("#init-cloudimage"),
    initRow = $("#row-init"),
    cardVideo = $("#init-video"),
    bvSelectUp = $(".bv-select__up"),
    bvSelectDown = $(".bv-select__down"),
    rowSelect = $("#row-select"),
    popups = $(".pop-ups"),
    checkboxList = $("#checkbox-list"),
    toggle = $('[data-toggle]'),
    tabsTbl = $(".tbl-tabs__link");

  /*************checkboxList*****************/
  // checkboxList.find("input").on("click", function () {
  //   var currentEl = $(this),
  //     text = currentEl.siblings("label").find("strong").attr("data-part");

  //   checkboxList.find("input").each(function (i) {
  //     var currentEl = $(this);
  //     currentEl.prop("checked", false);
  //   });

  //   currentEl.prop("checked", true);
  //   rowSelect.find(".is--active").removeClass("is--active");
  //   rowSelect.find("[data-part=" + text + "]").addClass("is--active");
  // })

  /*********cabinet tabs*******/
  tabsTbl.on("click", function (e) {
    e.preventDefault();
    var currentEl = $(this),
      dataShow = $(".tbl__body").find("[data-show]");

    tabsTbl.removeClass("is--active");
    currentEl.addClass("is--active");

    if (currentEl.attr("data-show-link") == "false") {
      dataShow.attr("data-show", "false");
    } else {
      dataShow.attr("data-show", "true");
    }
  })

  /*toggle*/
  toggle.on("click", function (e) {
    e.preventDefault();
    var el = $(this),
      parent = el.parent(),
      attr = el.attr("data-toggle"),
      wrap = el.parents("[data-toggle-wrap]");

    if (parent.hasClass("is--open")) {
      parent.removeClass("is--open");
      wrap.find("[data-toggle-show=" + attr + "]").slideUp()
    } else {
      wrap.find(".is--open").removeClass("is--open")
      wrap.find("[data-toggle-show]").slideUp();

      parent.addClass("is--open");
      wrap.find("[data-toggle-show=" + attr + "]").slideDown();
    }
  });

  /**************initialize video/cloudimage in detail card product****************/
  cloudimage.on("click", function (e) {
    e.preventDefault();
    initRow.removeClass("is--video");
    initRow.addClass("is--cloudimage");
  })

  cardVideo.on("click", function (e) {
    e.preventDefault();
    initRow.removeClass("is--cloudimage");
    initRow.addClass("is--video");
  })

  /**************bv select****************/
  bvSelectUp.on("click", function (e) {
    e.preventDefault();

    var currentEl = $(this),
      parents = $(currentEl).parents(".bv-select"),
      activePart = parents.find(".bv-select__item.is--active");

    if (activePart.prev(".bv-select__item").length == 1) {
      activePart.removeClass("is--active").prev(".bv-select__item").addClass("is--active");

      var attr = parents.find(".is--active").attr("data-part");

      checkboxList.find("[data-part=" + attr + "]").parents(".checkbox-list__item").find("input").click();
    }
  });

  bvSelectDown.on("click", function (e) {
    e.preventDefault();

    var currentEl = $(this),
      parents = $(currentEl).parents(".bv-select"),
      activePart = parents.find(".bv-select__item.is--active");

    if (activePart.next(".bv-select__item").length == 1) {
      activePart.removeClass("is--active").next(".bv-select__item").addClass("is--active");

      var attr = parents.find(".is--active").attr("data-part");
      checkboxList.find("[data-part=" + attr + "]").parents(".checkbox-list__item").find("input").click();
    }
  });

  /**************initialize slick-slider****************/
  if (carousel.length) {
    carousel.each(function () {
      var slider = $(this);

      if (slider.is('.card-list')) {
        slider.slick({
          slidesToShow: 1,
          slideToScroll: 1,
          swipe: false,
          dots: false,
          arrows: false,
          infinite: false,
          speed: 500,
          fade: true,
          cssEase: 'ease-in-out',
          asNavFor: $(".card-nav__items.slick-carousel"),
          responsive: [{
            breakpoint: 768,
            settings: {
              swipe: true
            }
          }]
        });

        $(".card-nav__items.slick-carousel").on("click", function (e) {
          if ($(this).has(e.target).length == 1 && initRow.hasClass("is--video") || $(this).has(e.target).length == 1 && initRow.hasClass("is--cloudimage")) {
            initRow.attr("class", "card-list-row");
          }
        });

      } else if (slider.is('.card-nav__items')) {
        slider.slick({
          slidesToShow: 5,
          slidesToScroll: 1,
          swipe: false,
          dots: false,
          vertical: true,
          arrows: true,
          infinite: false,
          focusOnSelect: true,
          asNavFor: $(".card-list.slick-carousel"),
          responsive: [{
              breakpoint: 1237,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
              }
            },
            {
              breakpoint: 1136,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
                vertical: false,
              }
            },
            {
              breakpoint: 601,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
                vertical: false,
              }
            }
          ]
        });
      } else if (slider.is('.slider-banner')) {
        slider.slick({
          slidesToShow: 1,
          slideToScroll: 1,
          swipe: true,
          dots: false,
          arrows: false,
          infinite: true,
          speed: 500,
          fade: true,
          cssEase: 'ease-in-out',
          asNavFor: $(".slider-banner__nav.slick-carousel"),

          responsive: [{
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: true
            }
          }]
        });
      } else if (slider.is('.slider-banner__nav')) {
        slider.slick({
          slidesToShow: 3,
          slideToScroll: 3,
          swipe: false,
          dots: false,
          arrows: false,
          infinite: true,
          variableWidth: true,
          asNavFor: $(".slider-banner.slick-carousel"),
          focusOnSelect: true,
        });

        slider.find(".slick-slide").on("click", function () {
          slider.addClass("off--click");

          setTimeout(function () {
            slider.removeClass("off--click");
          }, 500);
        })

      } else if (slider.is('.slider-product')) {
        slider.slick({
          slidesToShow: 4,
          slideToScroll: 4,
          prevArrow: $(".slider-product__prev"),
          nextArrow: $(".slider-product__next"),
          swipe: false,
          dots: true,
          infinite: false,
          responsive: [{
              breakpoint: 1302,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                swipe: true,
              }
            },
            {
              breakpoint: 992,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                swipe: true,
              }
            },

            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                swipe: true,
              }
            }
          ]
        });
      } else if (slider.is('.slider-preview')) {
        slider.slick({
          slidesToShow: 1,
          slideToScroll: 1,
          swipe: false,
          dots: false,
          arrows: false,
          infinite: true,
          speed: 1200,
          autoplay: true,
          autoplaySpeed: 3000,
          pauseOnHover: false,
          fade: true
        });
      } else if (slider.is('.b-section__slider')) {
        slider.slick({
          lazyLoad: 'ondemand',
          slidesToShow: 5,
          slideToScroll: 1,
          swipe: true,
          dots: false,
          arrows: false,
          infinite: true,
          speed: 1200,
          autoplay: true,
          autoplaySpeed: 2000,
          pauseOnHover: false,
          responsive: [{
              breakpoint: 1290,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
              }
            },

            {
              breakpoint: 1047,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 1,
              }
            },

            {
              breakpoint: 768,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 1,
                variableWidth: true
              }
            },
          ]
        });
      } else if (slider.is('.list')) {
        slider.slick({
          slidesToShow: 4,
          swipe: false,
          dots: true,
          infinite: false,
          prevArrow: "<div class='slick-prev'><svg class='back-icon'><use class='back-icon__part' xlink: href='/templates/pianino_new/i/sprite.svg#back' ></use></svg></div>",
          nextArrow: "<div class='slick-next'><svg class='back-icon'><use class='back-icon__part' xlink: href='/templates/pianino_new/i/sprite.svg#back' ></use></svg></div>",
          responsive: [{
              breakpoint: 901,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
              }
            },

            {
              breakpoint: 768,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
              }
            },

            {
              breakpoint: 481,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
              }
            },

            {
              breakpoint: 473,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                swipe: true,
                arrows: false
              }
            },
          ]
        });
      } else if (slider.is('.b-review__items.b-review__slider')) {
        slider.slick({
          slidesToShow: 4,
          slidesToScroll: 4,
          swipe: false,
          dots: false,
          infinite: false,
          prevArrow: "<div class='slick-prev'><svg class='back-icon'><use class='back-icon__part' xlink: href='/templates/pianino_new/i/sprite.svg#back' ></use></svg></div>",
          nextArrow: "<div class='slick-next'><svg class='back-icon'><use class='back-icon__part' xlink: href='/templates/pianino_new/i/sprite.svg#back' ></use></svg></div>",
          responsive: [{
              breakpoint: 1200,
              settings: {
                slidesToShow: 5,
                slidesToScroll: 5,
              }
            },

            {
              breakpoint: 990,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
              }
            },

            {
              breakpoint: 600,
              settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
              }
            },

            {
              breakpoint: 400,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
              }
            },
          ]
        });
      } else if (slider.is(".b-slider__list--kit")) {
        slider.slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          swipe: true,
          dots: false,
          arrows: true,
          infinite: false,
        });
      } else if (slider.is(".certificates")) {
        slider.slick({
          slidesToShow: 6,
          slidesToScroll: 6,
          swipe: true,
          dots: false,
          arrows: true,
          infinite: false,
          responsive: [{
              breakpoint: 1200,
              settings: {
                slidesToShow: 4,
                slidesToScroll: 4,
              }
            },

            {
              breakpoint: 768,
              settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
              }
            },
          ]
        });
      } else if (slider.is(".accordion")) {
        slider.slick({
          slidesToShow: 1,
          slidesToScroll: 1,
          swipe: true,
          dots: false,
          arrows: true,
          infinite: false,
          responsive: [{
              breakpoint: 9999,
              settings: "unslick"
            },
            {
              breakpoint: 768,
              settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
                swipe: true,
                dots: true,
                arrows: true,
                infinite: false,
              }
            }
          ]
        })
      } else if (slider.is(".new-section-slider")) {
        slider.slick({
          slidesToShow: 3,
          slidesToScroll: 3,
          swipe: true,
          dots: false,
          arrows: true,
          infinite: false,
          responsive: [{
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
            }
          }]
        })
      }
    });
  }

  if ($(".b-slider__list--news").length) {
    $(".b-slider__list--news").slick({
      slidesToShow: 4,
      slidesToScroll: 4,
      swipe: true,
      dots: true,
      arrows: false,
      infinite: false,
      responsive: [{
          breakpoint: 1280,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
          }
        },
        {
          breakpoint: 930,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2,
          }
        },

        {
          breakpoint: 630,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1,
          }
        }
      ]
    });
  }



  $(".b-detal__characteristics-Td.is--button").on("click", function (e) {
    e.preventDefault();
    var parents = $(this).parents(".b-detal__characteristics-Table")
    parents.toggleClass("is--show");

    if (parents.hasClass("is--show")) {
      $(this).text("Скрыть характеристики")
    } else {
      $(this).text("Подробные характеристики")
    }
  });

  $(".tbl-block__btn").on("click", function (e) {
    e.preventDefault();
    var parents = $(this).parents(".tbl-block")
    parents.toggleClass("is--show");

    if (parents.hasClass("is--show")) {
      $(this).text("Скрыть характеристики")
    } else {
      $(this).text("Подробные характеристики")
    }
  });

  /************check list product**********/
  if ($("#products_ul").length) {
    checkListScreen();
  }

  /**************change place < 360 favorite btn****************/
  if (windowWidth <= 360) {
    nav.find(".links").append(likeBtn);
  }


  /************************change price card-product*******************************/
  if (dataVal.length) {
    function detectPrice() {
      $("[data-row-price1]").text($("[data-value].is--active").attr("data-price1"));
      $("[data-row-price2]").text($("[data-value].is--active").attr("data-price2"));
    }

    detectPrice();

    dataVal.on('click', function (e) {
      var currentEl = $(this),
        value = currentEl.attr("data-value"),
        price1 = currentEl.attr("data-price1"),
        price2 = currentEl.attr("data-price2");

      dataVal.removeClass("is--active");
      currentEl.addClass("is--active");

      if (value == "usd" || value == "rur") {
        rowSelect.addClass("is--hide");
      } else if (rowSelect.hasClass("is--hide")) {
        rowSelect.removeClass("is--hide");
      }

      detectPrice();

    });
  }



  /**************filter btn show all****************/
  if (showAll.length) {
    showAll.on("click", function (e) {
      e.preventDefault();
      var el = $(this);

      el.siblings(".b-filter__list").toggleClass("is--show");
      el.toggleClass("is--hide");

      if (el.hasClass("is--hide")) {
        el.text("Скрыть");
      } else {
        el.text("Смотреть еще");
      }


    })
  }

  /**************Description show all in card product ****************/
  if ($(".limited-content").length) {
    var block = $(".limited-content__block");
    if (block.outerHeight() > 500) {
      block.addClass("is--hide");
      $(".limited-content__btn").on("click", function (e) {
        e.preventDefault();
        var el = $(this);

        el.siblings(block).toggleClass("is--visible");

        if (block.hasClass("is--visible")) {
          el.text("Скрыть");
        } else {
          el.text("Смотреть еще");
        }

        $("body, html").scrollTop($(".limited-content__block").offset().top);
      })
    }
  }

  /**************popular links show all****************/
  showLinks.on("click", function (e) {
    e.preventDefault();
    var el = $(this);

    el.siblings(popularLinks).toggleClass("is--active");
    el.toggleClass("is--hide");

    if (el.hasClass("is--hide")) {
      el.text("Скрыть");
    } else {
      el.text("Смотреть еще");
    }
  })

  /**************video onload on click****************/
  if (video.length) {

    video.on("click", function (e) {
      e.preventDefault();
      video.find("iframe").each(function (i) {
        var videoStarted = $(this);
        videoStarted.attr('src', '');
        videoStarted.parents(video).removeClass("is--onload");
      })

      var el = $(this),
        iframeTag = el.find("iframe");
      if (!el.hasClass("is--onload")) {
        iframeTag.attr("src", iframeTag.attr("data-src"));
        iframeTag[0].src += "?autoplay=1";

        el.addClass("is--onload");
      }
    })


    $(window).scroll(function () {
      if ($(".video-wrap").hasClass("is--onload")) {
        if ($(document).scrollTop() + $(window).height() > $(document).find(".video-wrap.is--onload").offset().top && $(document).scrollTop() - $(document).find(".video-wrap.is--onload").offset().top < $(document).find(".video-wrap.is--onload").height()) {} else {
          $(document).find(".video-wrap.is--onload iframe").removeAttr("src");
          $(document).find(".video-wrap.is--onload").removeClass("is--onload");
        }
      }
    });
  }

  /**************anchor****************/
  if ($(document).scrollTop() + $(window).height() > $(header).offset().top && $(document).scrollTop() - $(header).offset().top < $(header).height()) {
    anchor.removeClass("is--active");
  } else {
    anchor.addClass("is--active");
  }

  anchor.click(function (e) {
    $('html, body').animate({
      scrollTop: $('body').offset().top
    }, 500);
    e.preventDefault()
  });

  /**************cloned menu for mobile, tablet****************/
  nav.prepend("<ul class='b-menu b-menu--second'></ul>");
  nav.find(".b-menu").html(list.html());
  nav.prepend("<p class='b-menu-toggle'>Каталог</p>");

  var str = '<a href="#" class="b-toggle-mob"></a>';
  nav.find(".b-menu--second .b-menu__item").each(function () {
    $(this).prepend(str);
  })

  $(document).on("click", ".b-menu-toggle", function () {
    var currentEl = $(this);

    currentEl.siblings(".b-menu--second").slideToggle("fast");
    currentEl.toggleClass("is--open");
  })


  $(document).on("click", ".b-toggle-mob", function () {
    var currentEl = $(this),
      parent = currentEl.parent();

    parent.find(".b-menu__subContent").slideToggle("fast");
    currentEl.toggleClass("is--open");
  })

  /*********userAgent detect browser********/
  if (browsers.isChrome == true || browsers.isOpera == true) {
    micro.addClass("is--show");

    micro.on("click touchend", function () {
      searchInput.val("Слушаю Вас! Что найти?");
      searchPrompt.addClass("is--hide");
      var recognizer = new webkitSpeechRecognition();
      recognizer.interimResults = false;
      recognizer.lang = 'ru-Ru';
      recognizer.onresult = function (event) {
        var result = event.results[event.resultIndex];
        if (result.isFinal) {
          searchInput.val(result[0].transcript);
          $(".bv-btn--search").trigger("click")
        }
      };
      recognizer.start();
      return !1
    });
  }

  /**header popin*/
  popin.on("click touchend", function (e) {
    e.preventDefault();
    popin.toggleClass("is--active");

  })

  /**header menu category*/
  listBtn.on("click touchend", function (e) {
    e.preventDefault();
    if (listBtn.hasClass("js-index")) {
      return false;
    } else {
      listBtn.toggleClass("is--active");
      list.slideToggle();
    }
  })

  /**header search popup*/
  if (searchInput.val() != "") {
    searchPrompt.addClass("is--hide");
  }

  searchInput.focus(function () {
    searchRes.removeClass("is--hide");
    t = searchInput.val();
    $.get('/components/com_jshopping/finder.php', {
      word: t
    }, function (data) {
      searchRes.html(data);
    })

    setTimeout(function () {
      $('.lazyload').lazyload({
        load: function load(img) {
          img.fadeOut(0, function () {
            img.fadeIn(100);
          });
        }
      });
    }, 300)
  });

  searchInput.focusout(function () {
    if (!searchRes.hasClass("is--hover")) {
      searchRes.addClass("is--hide");
      searchRes.html('');
    }
  });

  searchRes.hover(
    function () {
      searchRes.addClass("is--hover");
    },
    function () {
      searchRes.removeClass("is--hover");
    }
  );

  searchInput.focusin(function () {
    searchPrompt.addClass("is--hide");
  });

  searchInput.focusout(function () {
    if (searchInput.val() == '') {
      searchPrompt.removeClass("is--hide");
    }
  });


  searchInput.keyup(function (event) {
    t = searchInput.val();
    $.get('/components/com_jshopping/finder.php', {
      word: t
    }, function (data) {
      searchRes.html(data);
      $('#search_results .lazyload').lazyload({
        load: function load(img) {
          img.fadeOut(0, function () {
            img.fadeIn(100);
          });
        }
      });
    })
  });


  /***sandwich mobile***/
  sandwich.on("click", function () {
    if (sandwich.hasClass("is--active--search")) {
      mobSearchBtn.click();
    } else {
      sandwich.toggleClass("is--active");
      nav.toggleClass("is--active");
      if (nav.hasClass("is--active")) {
        nav.css("top", $(".header__middle").outerHeight() + "px");
      }

      $("#bg").toggleClass("is--active");
      if (sandwich.hasClass("is--active")) {
        hideScroll();
      } else {
        showScroll();
      }
    }
  })

  /***mobile search***/
  mobSearchBtn.on("click", function () {

    mobSearch.toggleClass("is--active");
    sandwich.toggleClass("is--active--search");
    mobSearch.css("top", $(".header__middle").outerHeight() + "px");

    if (mobSearch.hasClass("is--active")) {
      hideScroll();
    } else {
      showScroll();
    }

  })


  /***filter mobile***/
  filterBtn.on("click", function (e) {
    e.preventDefault();
    filter.toggleClass("is--active");

    $("#bg").toggleClass("is--active");

    if (filter.hasClass("is--active")) {
      hideScroll();
    } else {
      showScroll();
    }
  })

  closeFilter.on("click", function (e) {
    e.preventDefault();
    filterBtn.click();
  })

  /***footer nav***/
  if (windowWidth <= 991) {
    footerNavTitle.on("click", function (e) {
      e.preventDefault();
      var currentEl = $(this),
        list = currentEl.siblings('.b-footer__list');


      if (currentEl.hasClass('active')) {
        currentEl.removeClass('active');
        list.slideUp();
      } else {
        $('.b-footer__title').removeClass('active');
        currentEl.addClass('active');
        $('.b-footer__list').slideUp();
        list.slideDown();
      }

    });
  }

  /************popups************/
  $(document).on('click', '[data-get-popup]', function (e) {
    e.preventDefault();

    hideScroll();

    var attr = $(this).attr('data-get-popup'),
      currentPopup = popups.find('.pop__up[data-name-popup=' + attr + ']');

    popups.addClass('is--active');

    setTimeout(function () {
      popups.addClass('is--fade');
    }, 100)
    setTimeout(function () {
      currentPopup.addClass('is--show');
    }, 200)
  })


  $(document).on('click', '.close', function () {
    if ($("body").hasClass("hide--scroll")) {
      showScroll();
    }

    var attr = $(this).attr('data-name-popup'),
      currentPopup = popups.find('.pop__up[data-name-popup=' + attr + ']');

    currentPopup.removeClass('is--show');

    popups.find(".is--show").removeClass("is--show");
    setTimeout(function () {
      popups.removeClass('is--fade')
    }, 200)
    setTimeout(function () {
      popups.removeClass('is--active')
    }, 400)

  });

  /**close popup**/
  $("[data-id='close']").on("click", function (e) {
    e.preventDefault();
    $(this).parents(".pop__up").find(".close").click();
  })

  /**close popup or popin on click window**/
  $(document).on("mouseup touchend", function (e) {
    var popinBlock = popin.siblings(".popin__block"),
      popup = popups.find('.is--show');

    if (popup.is(':visible') && popups.hasClass('is--active')) {
      if (popup.has(e.target).length === 0) {
        popup.find(".close").click();
      }
    }

    if (searchRes.is(":visible")) {
      if (!searchRes.is(e.target) && searchRes.has(e.target).length === 0 && !$(".bv-form__wrap").is(e.target) && !$(".bv-form__wrap").has(e.target).length === 0) {
        searchRes.html('');
      }
    }

    if (popin.hasClass('is--active')) {
      if (!popin.is(e.target) && popin.has(e.target).length === 0 && !popinBlock.is(e.target) && popinBlock.has(e.target).length === 0) {
        popin.click();
      }
    }

    if (nav.hasClass('is--active')) {
      if (!nav.is(e.target) && nav.has(e.target).length === 0 && !sandwich.is(e.target) && sandwich.has(e.target).length === 0) {
        sandwich.click();
      }
    }

    if (filter.hasClass('is--active')) {
      if (!filter.is(e.target) && filter.has(e.target).length === 0) {
        filterBtn.click();
      }
    }

    if (listBtn.hasClass('is--active')) {
      if (!listBtn.is(e.target) && listBtn.has(e.target).length === 0 && !list.is(e.target) && list.has(e.target).length === 0) {
        listBtn.click();
      }
    }
  });


  /**************counter cart****************/
  $(document).on("input keypress", '.counter input', function (e) {
    var currentEl = $(this);

    if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    } else if (currentEl.val().length > 2) {
      currentEl.val(currentEl.val().slice(0, -1));
    }
  });

  $(document).on("click", "[data-counter-next]", function () {
    var el = $(this);

    if (el.hasClass('is--loading')) {
      return false;
    } else {

      var parents = $(this).parents(".counter"),
        counterInput = parents.find("input"),
        oldValue = parseFloat(counterInput.val()),
        counterMax = counterInput.attr('max');

      if (oldValue >= counterMax) {
        var newVal = oldValue;
      } else {
        var newVal = oldValue + 1;
      }

      counterInput.val(newVal);
      counterInput.trigger("change");
      // el.addClass("is--loading");
    }

  });

  $(document).on("click", "[data-counter-prev]", function () {
    var el = $(this);

    if (el.hasClass('is--loading')) {
      return false;
    } else {
      var parents = $(this).parents(".counter"),
        counterInput = parents.find("input"),
        oldValue = parseFloat(counterInput.val()),
        counterMin = counterInput.attr('min');

      if (oldValue <= counterMin) {
        var newVal = oldValue;
      } else {
        var newVal = oldValue - 1;
      }

      counterInput.val(newVal);
      counterInput.trigger("change");
      // el.addClass("is--loading");
    }
  });

  /********Удалить если не нужно**********/

  $(document).on('change', '.counter input[type="number"]', function () {
    // var input = $(this);
    // $.ajax({
    //   type: 'post',
    //   url: ajaxData.url,
    //   data: {
    //     action: 'product_quantity_update',
    //     quantity: input.val(),
    //     productKey: input.data('cart_item_key')
    //   },
    //   success: function (response) {
    //     $('a.header__item-icon.sprite-icon').replaceWith(response.fragments['a.header__item-icon.sprite-icon']);
    //     $('main#cart-products').replaceWith(response.fragments['main#cart-products']);
    //   },
    // });
  });


  $(document).on('keypress', '.counter input[type="number"]', function (e) {
    // var input = $(this);

    // if (e.which == "13") {
    //   $.ajax({
    //     type: 'post',
    //     url: ajaxData.url,
    //     data: {
    //       action: 'product_quantity_update',
    //       quantity: input.val(),
    //       productKey: input.data('cart_item_key')
    //     },
    //     success: function (response) {
    //       $('a.header__item-icon.sprite-icon').replaceWith(response.fragments['a.header__item-icon.sprite-icon']);
    //       $('main#cart-products').replaceWith(response.fragments['main#cart-products']);
    //     },
    //   });
    // }
  });

  /********Конец Удалить если не нужно**********/


  /***********show all text***********/
  $('.b-text__showMore').on("click", function (e) {
    e.preventDefault()
    var $target = $(this).siblings('.b-text__content--hide');
    if ($(this).hasClass('active')) {
      $(this).text('Показать еще').removeClass('active');
      $target.slideUp();
      e.preventDefault()
    } else {
      $(this).text('Скрыть').addClass('active');
      $target.slideDown();
    }
  });


  /***********init Validate form***********/
  popups.find(".pop__up-form").each(function () {
    validateForm($(this));
  })

  /***********PhotoSwipe***********/
  var container = [];

  $('#gallery').find('.card-list__item').each(function (i) {
    var $link = $(this).find('figure a'),
      item = {
        src: $link.attr('href'),
        w: $link.attr('data-width'),
        h: $link.attr('data-height'),
      };
    container.push(item);
  });

  $('#gallery .card__img').click(function (event) {
    event.preventDefault();
    var $pswp = $('.pswp')[0],
      options = {
        index: $(this).parents('.card-list__item').index(),
        bgOpacity: 0.85,
        showHideOpacity: true
      };

    console.log($(this).parents('.card-list__item').index());

    var gallery = new PhotoSwipe($pswp, PhotoSwipeUI_Default, container, options);
    gallery.init();
  });

  /*******data-tabs********/
  $(document).on("click", "[data-tab]", function (e) {
    e.preventDefault();
    var currentEl = $(this),
      attr = currentEl.attr("data-tab"),
      parents = currentEl.parents("[data-tab-wrap]");

    parents.find(".active").removeClass("active");

    parents.find("[data-tab=" + attr + "]").addClass("active");
    parents.find("[data-tabcontent=" + attr + "]").addClass("active");
    parents.find(".b-tab__content.active .slick-carousel").slick('refresh');
  })


  /**********b-filter*********/
  $('.b-filter__screenLink').click(function (e) {
    e.preventDefault()
    $('.b-filter__screenLink').removeClass('active');
    $(this).addClass('active');
    id = $(this).attr("rel");
    set_cookie("screen_type", id);
    var typeScreen = $(this).attr('data-screen'),
      $itemList = $('#products_ul');
    $itemList.removeAttr('data-screen');
    $itemList.attr('data-screen', typeScreen);
  });


  $('.js_filterLink.active + .js_filterBlock').show();
  $('.js_filterLink').on("click", function (e) {
    console.log($(".data-title__block").is(e.target));
    if ($(".data-title__block").is(e.target)) {
      return false;
    } else {
      var $wrap = $(this).closest('.js_filterWrap');
      if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $wrap.find('.js_filterBlock').slideUp();
        e.preventDefault()
      } else {
        $(this).addClass('active');
        $wrap.find('.js_filterBlock').slideDown();
        e.preventDefault()
      }
    }
  });

  $("#submit_filter").on("click", function (e) {
    e.preventDefault();
    refresh_product_listing();
    return false;
  })


  $('.b-sliderRange__input').focusout(function () {
    if ($(this).val() == '') {
      $(this).val('0')
    }
  });

  $(document).on("input keypress", ".b-sliderRange__input", function (e) {
    if (e.which != 13 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
      return false;
    }
  });


  var costMin = $('#minCost').attr('data-min');
  var costMax = $('#maxCost').attr('data-max');
  var value1 = $("input#minCost").val();
  var value2 = $("input#maxCost").val();
  $("#sliderRange").slider({
    min: costMin,
    max: costMax / 10,
    values: [value1, value2],
    range: !0,
    stop: function (event, ui) {
      $("input#minCost").val($("#sliderRange").slider("values", 0));
      $("input#maxCost").val($("#sliderRange").slider("values", 1));
      refresh_product_listing()
    },
    slide: function (event, ui) {
      $("input#minCost").val($("#sliderRange").slider("values", 0));
      $("input#maxCost").val($("#sliderRange").slider("values", 1))
    }
  });
  $("input#minCost").on("change", function () {
    var value1 = $("input#minCost").val();
    var value2 = $("input#maxCost").val();
    if (parseInt(value1) > parseInt(value2)) {
      value1 = value2;
      $("input#minCost").val(value1)
    }
    $("#sliderRange").slider("values", 0, value1);

    refresh_product_listing();
  });
  $("input#maxCost").on("change", function () {
    var value1 = $("input#minCost").val();
    var value2 = $("input#maxCost").val();
    // if (value2 > costMax) {
    // 	value2 = costMax;
    // 	$("input#maxCost").val(costMax)
    // }
    // if (parseInt(value1) > parseInt(value2)) {
    // 	value2 = value1;
    // 	$("input#maxCost").val(value2)
    // }

    $("#sliderRange").slider("values", 1, value2);

    refresh_product_listing();
  });

  $('.b-filter__tagLine-close').click(function (e) {
    $(this).closest('.b-filter__tagLine-item').remove();
    e.preventDefault()
  });


  // $('.b-sliderRange__input').keypress(function (event) {
  // 	var key, keyChar;
  // 	if (!event) var event = window.event;
  // 	if (event.keyCode) key = event.keyCode;
  // 	else if (event.which) key = event.which;
  // 	if (key == null || key == 0 || key == 8 || key == 13 || key == 9 || key == 46 || key == 37 || key == 39) return !0;
  // 	keyChar = String.fromCharCode(key);
  // 	if (!/\d/.test(keyChar)) return !1;
  // });



  // $('.b-sliderRange__input').click(function () {
  // 	$(this).val('')
  // });

  // $('.b-modal__sliderItem').each(function () {
  // 	var $sliderRange = $(this);
  // 	var maxNum = +$(this).attr('data-max');
  // 	var $inputTarget = $(this).closest('.b-modal__sliderLine').find('.b-modal__sliderInput');
  // 	// $(this).slider({
  // 	// 	min: costMin,
  // 	// 	max: maxNum,
  // 	// 	stop: function (event, ui) {
  // 	// 		$inputTarget.val($sliderRange.slider("values", 1))
  // 	// 	},
  // 	// 	slide: function (event, ui) {
  // 	// 		$inputTarget.val($sliderRange.slider("values", 1))
  // 	// 	}
  // 	// })
  // });
});


$(window).on("resize orientationchange", function () {
  /**vars*/
  var windowWidth = $(window).width(),
    nav = $("#navigation"),
    footerNavTitle = $('.b-footer__title'),
    mobSearch = $("#mobile-search-body"),
    likeBtnRow = $("#row-like-mob"),
    likeBtn = $("#like-mobile"),
    carousel = $(".slick-carousel");

  if ($(window).width() > 991) {
    $(".b-delivery-main .b-delivery__productList").removeAttr("style");
  }


  if ($(window).width() < 768 && $(".list-brands").length) {
    $(".list-brands").attr("data-screen", "screenList")
  } else {
    $(".list-brands").attr("data-screen", "screenItems")
  }



  /************check list product**********/
  if ($("#products_ul").length) {
    checkListScreen();
  }

  /**************slick-slider resize****************/
  if (carousel.length) {
    carousel.slick("resize");
  }

  /**************change place < 360 favorite btn****************/
  if (windowWidth <= 360) {
    nav.find(".links").append(likeBtn);
  } else {
    likeBtnRow.append(likeBtn);
  }



  /***footer nav***/
  if (windowWidth <= 991) {
    footerNavTitle.off("click");

    footerNavTitle.on("click", function (e) {
      e.preventDefault();
      var currentEl = $(this),
        list = currentEl.siblings('.b-footer__list');


      if (currentEl.hasClass('active')) {
        currentEl.removeClass('active');
        list.slideUp();
      } else {
        $('.b-footer__title').removeClass('active');
        currentEl.addClass('active');
        $('.b-footer__list').slideUp();
        list.slideDown();
      }
    });
  } else {
    footerNavTitle.siblings('.b-footer__list').removeAttr("style");
    footerNavTitle.off("click");
  }

  if (windowWidth <= 1199) {
    if (nav.hasClass("is--active")) {
      nav.css("top", $(".header__middle").outerHeight() + "px");
    }

    if (mobSearch.hasClass("is--active")) {
      mobSearch.css("top", $(".header__middle").outerHeight() + "px");
    }
  }

})


$(window).on("scroll", function () {
  /**vars*/
  var header = $("#header"),
    anchor = $(".b-footer__anchor");
  if ($(document).scrollTop() + $(window).height() > $(header).offset().top && $(document).scrollTop() - $(header).offset().top < $(header).height()) {
    anchor.removeClass("is--active");
  } else {
    anchor.addClass("is--active");
  }
})


$(window).on("load", function () {
  $('.lazyload').lazyload({
    load: function load(img) {
      img.fadeOut(0, function () {
        img.fadeIn(100);
      });
    }
  });


  var script = $(".lazyload-script");
  if (script.length) {
    setTimeout(function () {
      script.contents().each(function (index, node) {
        if (node.nodeType == 8) {
          // node is a comment
          $(node).replaceWith(node.nodeValue);
          script.find("script").unwrap();
        }
      });
    }, 1000)
  }
});