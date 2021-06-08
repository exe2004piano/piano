var vsego_kolvo;
vsego_kolvo = 0;


// ----------------------------------------- BASE64:
var Base64 = {
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if( isNaN(chr2) ) {
                enc3 = enc4 = 64;
            }else if( isNaN(chr3) ){
                enc4 = 64;
            }
            output = output +
                this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },

    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));
            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if( enc3 != 64 ){
                output = output + String.fromCharCode(chr2);
            }
            if( enc4 != 64 ) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },

    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if( c < 128 ){
                utftext += String.fromCharCode(c);
            }else if( (c > 127) && (c < 2048) ){
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;

    },

    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while( i < utftext.length ){
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }else if( (c > 191) && (c < 224) ) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}
// ----------------------------------------- END BASE64

function show_postloader()
{
    $.each($(".postloader_back"), function()
    {
        $(this).css("background-image","url('"+$(this).attr('rel')+"')");
    });

    $.each($(".postloader_src"), function()
    {
        $(this).attr("src",$(this).attr('rel'));
    });
}



var name_;
var val_;
var tm_;
var is_dostavka;

function pixel()
{
    $.get("/z/sync/pixel.php", {name_: name_, val_:val_});
}

function show_dostavka(id)
{
    $('#'+id).show(200);
    is_dostavka = 1;
}

function hide_dostavka()
{
    is_dostavka++;
    if(is_dostavka!=2)
    {
        $('.dostavka_info').hide("slow");
    }
}

function set_currency_(id)
{
    id=1*id;
    set_cookie("currency", id);
}

function go_to_url(url)
{
    window.open(url, '_blank');
}

jQuery(document).ready(function($)
{

    $("#mic").on("click", function()
    {
        $("#search_words").val("Слушаю Вас! Что найти?");

        var recognizer = new webkitSpeechRecognition();
        recognizer.interimResults = false;
        recognizer.lang = 'ru-Ru';
        recognizer.onresult = function (event)
        {
            var result = event.results[event.resultIndex];
            if (result.isFinal)
            {
                $("#search_words").val(result[0].transcript);
                $(".b-header__serachButton").trigger("click");
            }
        };
        recognizer.start();
        return false;
    });




    $(".b-filter__blockLinkFind").on("click", function(){
        $(".popular_links").toggle(300);
        return false;
    });


    $("#r_up").on("click", function()
    {
        var id = $(".rassrochka_button.active").attr('rel');
        id--;
        if(id==0)
            id=$(".rassrochka_button").length;

        $(".rassrochka_button.active").removeClass("active");
        $("#rassrochka_"+id).addClass("active");
        $("#rassrochka_var_"+id).prop("checked", true);
    });


    $("#r_down").on("click", function()
    {
        var id = $(".rassrochka_button.active").attr('rel');
        id++;
        if($("#rassrochka_"+id).length==0)
            id=1;
        $(".rassrochka_button.active").removeClass("active");
        $("#rassrochka_"+id).addClass("active");
        $("#rassrochka_var_"+id).prop("checked", true);
    });

    $("[name=dostavka_type]").click(function()
    {
        type = $(this).val();
        if(type=='1')
        {
            $("#block_adr").show(300);
            $("#block_sam").hide(300);
        }
        else
        {
            $("#block_adr").hide(300);
            $("#block_sam").show(300);
        }
    });

    if($("#img3d").length>0)
    {
        do_img3d();

        $("#img3d_scroll").scroll(function()
        {
            left = -($("#img3d_scroll div").offset().left)/10+37;
            left = left.toFixed(0);

            if(left<1)
                left = 0;
            if(left>360)
                left = 360;

            el = $("#img3d img:visible");
            if($(el).next().length>0)
            {
                $(el).hide();
                $("#img3d_"+left).show();
                // $(el).next().show();
            }
        });
    }



    refresh_basket();
    $(".mp4_").each(function()
    {
        $(this).play();
    });

    refresh_listing_count();
    refresh_compare();
    refresh_like();

    $(".b-nav__menu-item a").click(function()
    {
        $(".b-nav__menu-item a").removeClass("active");
        $(this).addClass("active");
    });

    $(window).scroll(function()
    {
        current_scroll = '';
        $('.scroll_tag').each(function(index, value)
        {
            pos = $(this).offset();
            x = pos.top - $(window).scrollTop();
            if(x<220)
            {
                // --- мы пролистали ниже каких-то из наших меток, нужно найти максимально близкую к краю окна
                current_scroll = $(this).attr("id");
            }

            $(".js-anchor").removeClass("active");
            if(current_scroll!='')
            {

                $("."+current_scroll).addClass("active");
            }
            else
            {

            }

        });

    });

    $(".megablock").on("mouseover", function()
        {
            $(this).stop().animate({opacity:100}, 200);
            rel = $(this).attr("rel");
            url = rel.replace('/images/attrib/', '/images/attrib/color/');
            $('.megablock').addClass("transparent");
            $.each($('.megablock'),function()
            {
                if($(this).attr("rel")!=rel)
                    $(this).children("div").stop().animate({opacity:0.15}, 200);
            });

            $("#megablocks").stop().removeClass("transparent");
            $("#megablocks").stop().css({"background-image":"url('"+url+"')"}).animate({opacity:1}, 500);
        }
    );


    $(".megablock").on("mouseout", function()
        {
            $("#megablocks").addClass("transparent");
            $.each($('.megablock'),function()
            {
                $(this).children("div").stop().animate({opacity:100}, 200);
                url = $(this).attr("rel");
                $(this).removeClass("transparent");
                $(this).css({"background-image":"url('"+url+"')"});
            });
        }
    );




    $("body").on("click", function()
    {
        hide_dostavka();
    });

    $("input").keyup(function()
    {
        name_ = $(this).prop("name");
        val_ = $(this).val();
        clearTimeout(tm_);
        tm_ = setTimeout(pixel, 500);
    });


    $("[name = 'user_comment']").keyup(function()
    {
        name_ = $(this).prop("name");
        val_ = $(this).val();
        clearTimeout(tm_);
        tm_ = setTimeout(pixel, 500);
    });



    if($(".random_banner").length>0)
    {
        id_ = "random1";
        done_ = 0;
        i=0;

        $.each($(".random_banner"), function()
        {
            i++;
            if(done_==1) return;

            rand_ = Math.random()*100;
            if( (rand_>65) || ((rand_>35) && (i>1)) )
            {
                id_ = $(this).attr("id");
                done_ = 1;
                return;
            }
        });


        $("#"+id_).removeClass("random_banner");
    }



    $(".vopros").click(function()
    {
        if($(this).hasClass('vopros_open'))
            opened = true;
        else
            opened = false;

        $.each($(".vopros"), function()
        {
            $(this).removeClass('vopros_open');
        });

        $.each($(".otvet"), function()
        {
            $(this).slideUp(300);
        });

        if(opened==false)
        {
            id = $(this).attr('rel');
            $("#otvet_"+id).slideToggle();
            if($(this).hasClass('vopros_open'))
            {
                $(this).removeClass('vopros_open');
            }
            else
            {
                $(this).addClass('vopros_open');
            }
        }
    });


    $(document).mousemove(function(e)
    {
        doc_x = ($(window).width()-95);
        doc_y = ($(window).height()-95);

        dx = doc_x-e.clientX;
        dy = doc_y-e.clientY;

        if( (Math.abs(dx)<70) && (Math.abs(dy)<70) )
        {
            $("#my_hunter").addClass("my_hunter_round");
        }
        else
        {
            $("#my_hunter").removeClass("my_hunter_round");
        }

        ang = Math.atan2(dy, dx)/Math.PI*180-45;
        //$("#my_hunter").rotate(ang);
    });


    $("#email_send_button").click(function(e)
        {
            email = $("#email_send").val();
            $.post("/exe/email_send.php", {email: email}, function(data)
            {
                if(data==1)
                {
                    $("#email_send").removeClass('error');
                    location.href = "#emails";
                }
                else
                {
                    $("#email_send").addClass('error');
                }
            });
            e.preventDefault();
            return false;
        }
    );


    $("#filter_order").change(function()
    {
        set_cookie("filter_order", $(this).val());
        refresh_product_listing();
    });


    $('#search_words').keyup(function (event)
    {
        t = $('#search_words').val();

        $.get('/components/com_jshopping/finder.php', {word : t}, function(data)
            {
                $("#search_results").html(data);
            }
        );
    });

    $('#search_words').focusin(function()
    {
        $(".b-header__serachExample").hide();
    });


    $('#search_words').focusout(function()
    {
        $(".b-header__serachExample").show();
    });

    setTimeout(show_postloader, 100);

    $(".b-header__basketText").each(function()
    {
        $(this).html($("#basket_get_summ").val());
    });


    $(".filter_item label").click(function()
    {
        // --- функция сработает после всех обработчиков клика и прочего
        setTimeout(refresh_product_listing, 100);
    });


});




var cookie_time = 30*24*3600*1000;

function set_cookie(name,value,expire){
    var exp=new Date();
    var cookieexpire=exp.getTime()+expire;
    exp.setTime(cookieexpire);
    document.cookie=name+"="+value+";path=/;expires="+exp.toGMTString();
}

function get_cookie(name) {
    var pattern = "(?:; )?" + name + "=([^;]*);?";
    var regexp  = new RegExp(pattern);

    if (regexp.test(document.cookie))
        return decodeURIComponent(RegExp["$1"]);

    return false;
}

function delete_cookie(name, path, domain) {
    set_cookie(name, null, 0);
    return true;
}
// -------------------------------------------------
function array_unique(inArr){
    var uniHash={}, outArr=[], i=inArr.length;
    while(i--) uniHash[inArr[i]]=i;
    for(i in uniHash) outArr.push(i);
    return outArr
}


// ---------- BASKET:
function add_to_basket(id, kolvo)
{
    if(kolvo==undefined)
        kolvo = 1;

    $.post("/z/get_prod_gp.php", {id:id}, function(data)
    {
        var res = JSON.parse(data);
        dataLayer.push({
            'event': 'addToCart',
            'ecommerce': {
                'currencyCode': 'BYN',
                'add': {
                    'products': [{
                        'name': res.name,
                        'id': id,
                        'price': res.product_price,
                        'brand': res.m_name,
                        'category': res.cat_parent_name+'/'+res.cat_name,
                        'quantity': kolvo
                    }]
                }
            }
        });
    });



    basket = get_cookie("new_basket");      // --- берем корзину из кукисов
    itog = '';


    if(basket)
    {
        arr = basket.split("~");                // --- делаем массив
        exist = 0;                              // --- считаем что элемент пока не добавлен
        for(i=0;i<arr.length-1;i++)             // --- проверим каждый нет ли уже такого как наш
        {
            item = arr[i].split("=");
            if (id!=item[0])    // --- это не добавленный элемент
            {
                itog = itog + item[0] + '=' + item[1] + '~';
            }
            else
            {
                // --- нашли наш элемент:
                exist = 1;
                item[1] = 1*item[1];
                item[1] += (1*kolvo);
                if(item[1]<=0)      // --- если мы не удаляли элемент
                    item[1] = 1;    // --- то запретим удаление

                itog = itog + item[0] + '=' + item[1] + '~';
                vsego_kolvo += item[1];
            }
        }

        if(exist==0)
        {
            if (kolvo>0)
                itog = itog + id + '=' + kolvo + '~';
        }
    }
    else
    {
        if (kolvo>0)
            itog = itog + id + '=' + kolvo + '~';
    }



    set_cookie("new_basket", itog, cookie_time);
    refresh_basket();
}


function reload()
{
    setTimeout(function(){location.reload()}, 500);
}

function show_basket()
{
//    $(".cartbox .cartbut a").click();
}


function delete_from_basket(id)
{
    basket = get_cookie("new_basket");      // --- берем корзину из кукисов
    itog = '';
    $("#product_in_basket_"+id).hide();

    if(basket)
    {

        $.post("/z/get_prod_gp.php", {id:id}, function(data)
        {
            var res = JSON.parse(data);
            dataLayer.push({
                'event': 'removeFromCart',
                'ecommerce': {
                    'currencyCode': 'BYN',
                    'add': {
                        'products': [{
                            'name': res.name,
                            'id': id,
                            'price': res.product_price,
                            'brand': res.m_name,
                            'category': res.cat_parent_name+'/'+res.cat_name,
                            'quantity': 1
                        }]
                    }
                }
            });
        });

        arr = basket.split("~");                // --- делаем массив
        exist = 0;                              // --- считаем что элемент пока не добавлен
        for(i=0;i<arr.length-1;i++)             // --- проверим каждый нет ли уже такого как наш
        {
            item = arr[i].split("=");
            if (id!=item[0])    // --- это не добавленный элемент
            {
                itog = itog + item[0] + '=' + item[1] + '~';
            }
        }
    }
    set_cookie("new_basket", itog, cookie_time);
    refresh_basket();
}



function refresh_basket()
{
    $.get("/exe/get_basket_ajax.php", {}, function(data)
        {
            $(".b-header__basketList").each(function()
            {
                $(this).html(data);
            });

            $(".b-header__basketText").each(function()
            {
                $(this).html($("#basket_get_summ").val());
            });

            vsego_kolvo = $("#basket_get_num").val();
            vsego_kolvo = 1*vsego_kolvo;
            $("#cart_span_mobile").html(vsego_kolvo);
            if(vsego_kolvo>0)
                $("#cart_span_mobile").addClass("active");
            else
                $("#cart_span_mobile").removeClass("active");
        }
    );
}

function clear_basket()
{
    set_cookie("new_basket", "");
    refresh_basket();
}


function clear_prod_visit()
{
    delete_cookie('prod_visit');
    $("#prod_last_visit_section").hide(300);
}

function parseGetParams() {
    var $_GET = {};
    var __GET = window.location.search.substring(1).split("&");
    for(var i=0; i<__GET.length; i++) {
        var getVar = __GET[i].split("=");
        $_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1];
    }
    return $_GET;
}

function del_extra_price()
{
    max_v = $("#maxCost").attr("data-max");
    $("#sliderRange").slider("values",0,0);
    $("#sliderRange").slider("values",1,max_v);
    $("#minCost").val("0");
    $("#maxCost").val(max_v);

    refresh_product_listing();
}

function del_extra_vendor(el)
{
    rel = $(el).attr('rel');
    $("#"+rel).prop("checked", false);
    // $("#submit_filter").trigger("click");
    refresh_product_listing();
}

function del_attr(el)
{
    $("#attr_"+el).prop("checked", false);
    refresh_product_listing();
}

function del_extra(el)
{
    rel1 = $(el).attr('rel1');
    rel2 = $(el).attr('rel2');
    $("#extra_"+rel1+"_"+rel2).prop("checked", false);
    // $("#submit_filter").trigger("click");
    refresh_product_listing();
}

function del_extra_slide(extra_id)
{
    min_ = $("#extra_slide_min_"+extra_id).val();
    max_ = $("#extra_slide_max_"+extra_id).val();

    $("#sliderRange_"+extra_id).slider("values",0,min_);
    $("#sliderRange_"+extra_id).slider("values",1,max_);
    $("#slide_min_"+extra_id).val(min_);
    $("#slide_max_"+extra_id).val(max_);

    refresh_product_listing();
}

function refresh_listing_count()
{
    $("#all_num_products_show").html($("#all_num_products").val());
}




function refresh_product_listing()
{
    // --- обработка смены данных фильтра
    // пройдемся по всем отмеченным полям фильтра


    filter = '';
    tags = '';

    price_min = $("#minCost").val();
    price_max = $("#maxCost").val();
    price_super_max = $("#maxCost").attr("data-max");

    start = $("#page_start").val();

    if( (price_min*1>0) || (price_max*1<price_super_max*1) )
    {
        tags = tags + '<li class="b-filter__tagLine-item"><p class="b-filter__tagLine-title panel_price_item">Стоимость от ' + price_min + ' до ' + price_max + ' р.</p><a href="#" class="b-filter__tagLine-close" id="price_min_max" onclick="del_extra_price(); return false;"></a></li>';
    }

    // --- значения всех экстрафилдов-чекбоксов
    $.each($(".filter_item input:checked"), function()
    {
        filter = filter + $(this).attr('id') + '~';
        tag = $(this).attr('tag');

        tags = tags + '<li class="b-filter__tagLine-item"><p class="b-filter__tagLine-title">' + tag + '</p><a href="#" class="b-filter__tagLine-close" onclick="$(\'#'+$(this).attr('id')+'\').trigger(\'click\'); refresh_product_listing(); return false;" ></a></li>';
    });


    // --- значения всех экстрафилдов-слайдеров
    $.each($(".extra_slide_class"), function()
    {
        extra_id = $(this).attr('rel');

        min_ = $("#extra_slide_min_"+extra_id).val();
        max_ = $("#extra_slide_max_"+extra_id).val();

        min_v = $("#slide_min_"+extra_id).val();
        max_v = $("#slide_max_"+extra_id).val();

        tag_name = $("#extra_slide_title_"+extra_id).html();

        min_v = 1*(min_v.trim());
        max_v = 1*(max_v.trim());
        min_ = 1*(min_.trim());
        max_ = 1*(max_.trim());

        if( (min_<min_v) || (max_>max_v) )
        {
            filter = filter + 'extraslide_'+extra_id+'_'+min_v+'_'+max_v+'~';
            $("#extraslide_"+extra_id).val(min_v+"_"+max_v);
            tags = tags + '<li class="b-filter__tagLine-item"><p class="b-filter__tagLine-title">' + tag_name + ' от ' + min_v + ' до ' + max_v + '</p><a href="#" class="b-filter__tagLine-close" id="extra_slide_tag_'+extra_id+'" onclick="del_extra_slide('+extra_id+'); return false;"></a></li>';
        }

    });


    // --- категория, цены, номер страницы
    cat_id = $("#cat_id").val();

    $.post('/exe/get_filter_products.php', {cat_id:cat_id, filter:filter, price_min:price_min, price_max:price_max, price_super_max:price_super_max, start:start, ajax:1}, function(data)
    {
        all = data.split('~~~~~');
        // alert(all[0]);

        $("#products_ul").html(all[0]);
        $("#filter_panel_ul").html(tags);
        setTimeout(show_postloader, 100);
        refresh_listing_count();
        url = all[1];
        if(url != window.location)
        {
            window.history.pushState(null, null, url);
        }
        $("#pagination").html(all[2]);
    });
}



function get_page_start(page_start)
{
    $("#page_start").val(page_start);
    $("#submit_filter").trigger("click");
    // refresh_product_listing();
}

function send_basket()
{
    error = 0;

    dost_type = $("[name=dostavka_type]:checked").val();

    $("[name=user_name]").removeClass('error');
    $("[name=user_phone]").removeClass('error');
    $("[name=user_mail]").removeClass('error');
    $("[name=user_city]").removeClass('error');
    $("[name=user_street]").removeClass('error');

    if($("[name=user_name]").val()=='')
    {
        $("[name=user_name]").addClass('error');
        error=1;
    }

    if($("[name=user_phone]").val()=='')
    {
        $("[name=user_phone]").addClass('error');
        error=1;
    }

    if($("[name=user_mail]").val()=='')
    {
        $("[name=user_mail]").addClass('error');
        error=1;
    }

    if(dost_type=="1")
    {
        if($("[name=user_city]").val()=='')
        {
            $("[name=user_city]").addClass('error');
            error=1;
        }

        if($("[name=user_street]").val()=='')
        {
            $("[name=user_street]").addClass('error');
            error=1;
        }
    }

    if(error==0)
        $("#basket_form").submit();
}



function put_text(text)
{
    $("#hidden_text").html(Base64.decode(text));
}

function put_text_rew(text)
{
    document.write(Base64.decode(text));
}

function set_currency(id)
{
    set_cookie("currency", id);
    location.reload();
}






// --- compare:
function add_to_compare(id)
{
    compare = get_cookie("new_compare");
    if(!compare)
        compare = '';

    compare = compare + id + "~";
    itog = '';
    num = 0;

    if(compare)
    {
        arr = compare.split("~");                // --- делаем массив
        arr = array_unique(arr);
        for(i=0;i<arr.length-1;i++)             // --- проверим каждый нет ли уже такого как наш
        {
            fid=1*arr[i];
            if (fid>0)    // --- это не добавленный элемент
            {
                itog = itog + fid + '~';
                num++;
            }
        }
    }

    set_cookie("new_compare", itog, cookie_time);
    refresh_compare(num);
}

function refresh_compare(num)
{
    if(num==undefined)
    {
        num=0;
        compare = get_cookie("new_compare");      // --- берем корзину из кукисов
        if(compare)
        {
            arr = compare.split("~");                // --- делаем массив
            arr = array_unique(arr);
            num = arr.length-1;
        }
    }
    $("#compare_span").html(num);
    $("#compare_span_mobile").html(num);

    if(num>0)
    {
        $("#compare_mobile").addClass("active");
        $("#compare_a").addClass("active");
        $("#compare_a").attr("href", "/compare");


        if($("#compare_b").length>0)
        {
            $("#compare_b").addClass("active");
            $("#compare_b").attr("href", "/compare");
            $("#compare_span_b").html(num);
        }
    }
}


function delete_from_compare(id)
{
    compare = get_cookie("new_compare");
    itog = '';

    if(compare)
    {
        arr = compare.split("~");                // --- делаем массив
        for(i=0;i<arr.length-1;i++)             // --- проверим каждый нет ли уже такого как наш
        {
            if (id!=arr[i])    // --- это не добавленный элемент
            {
                itog = itog + arr[i] + '~';
            }
        }
    }
    set_cookie("new_compare", itog, cookie_time);
    location.reload();
}

// --- like:
function add_to_like(id)
{
    like = get_cookie("new_like");
    if(!like)
        like = '';

    like = like + id + "~";
    itog = '';
    num = 0;

    if(like)
    {
        arr = like.split("~");                // --- делаем массив
        arr = array_unique(arr);
        for(i=0;i<arr.length-1;i++)             // --- проверим каждый нет ли уже такого как наш
        {
            fid=1*arr[i];
            if (fid>0)    // --- это не добавленный элемент
            {
                itog = itog + fid + '~';
                num++;
            }
        }
    }

    set_cookie("new_like", itog, cookie_time);
    refresh_like(num);
}

function refresh_like(num=0)
{
    if(num==0)
    {
        var like = get_cookie("new_like");
        if(like)
        {
            var arr = like.split("~");                // --- делаем массив
            arr = array_unique(arr);
            num = arr.length-1;
        }
    }

    $("#like_span").html(num);
    $("#like_span_mobile").html(num);

    if(num>0)
    {
        $("#like_a").addClass("active");
        $("#like_mobile").addClass("active");
        $("#like_a").attr("href", "/like-items");

        if($("#like_b").length>0)
        {
            $("#like_b").addClass("active");
            $("#like_b").attr("href", "/like-items");
            $("#like_span_b").html(num);
        }

    }
}


function delete_from_like(id)
{
    like = get_cookie("new_like");      // --- берем compare из кукисов
    itog = '';

    if(like)
    {
        arr = like.split("~");                // --- делаем массив
        for(i=0;i<arr.length-1;i++)             // --- проверим каждый нет ли уже такого как наш
        {
            if (id!=arr[i])    // --- это не добавленный элемент
            {
                itog = itog + arr[i] + '~';
            }
        }
    }
    set_cookie("new_like", itog, cookie_time);
    location.reload();
}




function diff_items()
{
    dif = $("#compare").prop("checked");
    if(dif)
        $(".diff_items").hide();
    else
        $(".diff_items").show();

}


function set_credit_price(credit)
{
    set_cookie("credit_price", credit);
    location.reload();
}


function make_slider(extra_id, min, max, min_slide, max_slide)
{
    // функция создания слайда для экстрафилдов

    $("#sliderRange_"+extra_id).slider({
        min: min,
        max: max,
        values: [min_slide,max_slide],
        range: true,
        stop: function(event, ui) {
            $("input#slide_min_"+extra_id).val($("#sliderRange_"+extra_id).slider("values",0));
            $("input#slide_max_"+extra_id).val($("#sliderRange_"+extra_id).slider("values",1));
            refresh_product_listing();

        },
        slide: function(event, ui){
            $("input#slide_min_"+extra_id).val($("#sliderRange_"+extra_id).slider("values",0));
            $("input#slide_max_"+extra_id).val($("#sliderRange_"+extra_id).slider("values",1));
        }
    });
}

function set_video(link)
{
    $("#youtube_frame").attr("src", "https://www.youtube.com/embed/"+link+"?autoplay=1");
}

function do_img3d()
{
    /*
     el = $("#img3d img:visible");
     if($(el).next().length>0)
     {
     $(el).hide();
     $(el).next().show();
     }
     setTimeout(do_img3d, 150);
     */
}

function salon3d_show()
{
    $("#block3d a").hide();
    $("#frame3d").html("<iframe class='frame_3d' src='https://my.matterport.com/show/?m=wNbRLpLW8fG' allowfullscreen></iframe>");
    $('html, body').animate({ scrollTop: $("#frame3d").offset().top }, 500);
}


function one_click_info(type)
{
    if(type==1)
    {
        $("#one_click_title").html("Доставим по Минску<br /><span style='color: red; font-size: 28px;'>за 2 часа!</span>");
        $("#one_click_text").html("<p>Наш курьер уже готов ехать! Ему нужен только Ваш номер телефона!</p>");
    }
    else
    {
        $("#one_click_title").html("Купить в 1 клик");
        $("#one_click_text").html("<p>Оставьте свои контактные данные.<br /> Наши менеджеры свяжутся с вами для уточнения деталей заказа.</p>");
    }
}


function rassrochka_var_class_refresh()
{
    $(".rassrochka_label").on("click", function()
    {
        var id = $(this).attr("rel");
        $(".rassrochka_button.active").removeClass("active");
        $("#rassrochka_"+id).addClass("active");
        $("#rassrochka_"+id).trigger("click");
    });
}


function show_more_products(cat_id)
{
    $.get("/exe/get_sale_products.php", {cat_id:cat_id}, function(data)
    {
        $("#more_products_"+cat_id).html(data);
        show_postloader();
    });
}

function show_more_rev()
{
	$(".inv_rev").removeClass("inv_rev");
	$("#show_inv_rev").hide();
}

function gp_productClick(list, name, id, price, brand, category, position )
{
    dataLayer.push({
        'event': 'productClick',
        'ecommerce': {
            'click': {
                'actionField': {'list': list},
                'products': [{
                    'name': name,
                    'id': id,
                    'price': price,
                    'brand': brand,
                    'category': category,
                    'position': position
                }]
            }
        }
    });
}