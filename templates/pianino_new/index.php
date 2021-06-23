<?php
defined('_JEXEC') or die;


if(strpos(" ".$_SERVER['REQUEST_URI'], "/index.php")>0)
{
    header("Location: /");
    die;
}

include_once (JPATH_ROOT.'/z/cron.php');

global $user;
global $is_manager;
global $roi_id;
global $roicode_products;

/*
// --- проверим онлайнер:
$ft = filemtime($_SERVER['DOCUMENT_ROOT']."/z/orders.onl");
if(time()-$ft>60)
{
    // --- прошло больше 1 минуты, можно опросить скрипт:
    @include_once($_SERVER['DOCUMENT_ROOT']."/z/test_onliner_cart.php");
}
*/

$user = JFactory::getUser();
$db = JFactory::getDbo();
$addr = trim($_SERVER['REQUEST_URI']);
if(strpos(" ".$addr, '?')>0)
    $addr = substr($addr, 0, strpos($addr, '?'));

$is_manager = 0;
// --- если зашли с рабочего компа, то менеджер
if($_SERVER['REMOTE_ADDR']=='213.184.241.76')
    $is_manager = 1;

if($is_manager==0)
{
// --- либо если залогинены под менеджеревским аккаунтом
    $q = "SELECT `value` FROM #__z_config WHERE id=21 OR id=22"; // --- тут список id менеджеров, которым будем показывать инфу о ценах конкурентов и прочее
    $db->setQuery($q);
    $res = $db->loadObjectList();


    foreach($res AS $r)
    {
        foreach(explode(",", $r->value) AS $v)
            if( ($v*1>0) && ($user->id==$v*1) )
                $is_manager = 1;
    }
}

global $valute;
$v_id = 1*$_COOKIE['currency'];
if(isset($_GET['prods_from_search_valute']))
    $v_id = $_GET['prods_from_search_valute']*1;

// --- если незадана валюта либо задана неверно, то установим по-умолчанию:
if( ($v_id<1) || ($v_id>3) )
    $v_id = 1;

// --- проверим на запросы экспорта прайсов и товаров:
include(dirname(__FILE__)."/exports.php");

?>
<!doctype html>
<html lang="ru-RU" dir="ltr">

<head>
  <? if(!isset($_GET['prods_from_search'])) { ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <?php
    unset($this->_scripts);
    unset($this->_css);
    unset($this->_styles);
    ?>
  <?php
        $this->setGenerator("");
        foreach ($this->_links AS $key=>$val)
        {
            $new_key = str_replace('http://', 'https://', $key);
            unset($this->_links[$key]);
            $this->_links[$new_key]=$val;
        }
    ?>

  <!-- Google Analytics -->
  <script src="https://www.googletagmanager.com/gtag/js?id=UA-57190626-1"></script>
  <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'UA-57190626-1');
  </script>
  <!-- End Google Analytics -->

  <jdoc:include type="head" />
  <meta name="yandex-verification" content="7ca87b3b49aae4a4" />
  <meta name="google-site-verification" content="joCW7KuOOuNtv4pEIsfpQTkwecJ6bVL-sTzqEIapgrQ" />
  <meta name="google-site-verification" content="a0qTQpPYwwWudRfB0WLPfncq2BMeymHyRiWkje6auEA" />

  <meta name="geo.placename" content="ул. Сурганова 57б, Минск, Беларусь" />
  <meta name="geo.position" content="53.9286880;27.5829580" />
  <meta name="geo.region" content="BY-" />
  <meta name="ICBM" content="53.9286880, 27.5829580" />

  <meta property="og:title" content="Магазин музыкальных инструментов" />
  <meta property="og:image" content="https://piano.by/templates/pianino_new/i/logo.png " />
  <meta property="og:description"
    content=" Пианино.by - первый музыкальный магазин клавишных инструментов с доставкой по все Беларусии! Смотрите на нашем сайте цены, отзывы, бесплатные консультации. " />
  <meta property="og:type" content="website" />
  <meta property="og:url" content=" https://piano.by/ " />
  <link rel="apple-touch-icon" sizes="57x57" href="/templates/pianino_new/icons/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/templates/pianino_new/icons/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/templates/pianino_new/icons/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/templates/pianino_new/icons/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/templates/pianino_new/icons/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/templates/pianino_new/icons/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/templates/pianino_new/icons/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/templates/pianino_new/icons/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/templates/pianino_new/icons/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192" href="/templates/pianino_new/icons/android-icon-192x192.png">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <link rel='stylesheet' media='print' onload="this.media='all'" type='text/css'
    href='https://cdn.callbackkiller.com/widget/cbk.css' />
  <link media='print' onload="this.media='all'"
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">

  <script src="/templates/pianino_new/js/jquery-3.4.1.min.js"></script>
  <link rel='stylesheet' type='text/css' href='/templates/pianino_new/css/libs.min.css' />
  <link rel='stylesheet' type='text/css' href='/templates/pianino_new/css/main.css' />
  <script>
  redhlpSettings = {
    avatar: "https://piano.by/images/consultant_pianino.png"
  };
  </script>

  <?php  // --- временно убрали по просьбе Стаса от 30.03.21   include(dirname(__FILE__)."/roistat.php"); ?>
  <link rel="author" href="https://plus.google.com/u/0/109653537106534401631" />
  <link rel="publisher" href="https://plus.google.com/u/0/b/100351020638901901476" />
  <? } ?>


  <? if(strpos($_SERVER['REQUEST_URI'], '/item/')) { // --- новость или др. элемент зоо ?>
  <link rel='stylesheet' type='text/css' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css' />
  <? } ?>


<?/*
  <!-- Facebook Pixel Code -->
  <script>
  ! function(f, b, e, v, n, t, s) {
    if (f.fbq) return;
    n = f.fbq = function() {
      n.callMethod ?
        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
    };
    if (!f._fbq) f._fbq = n;
    n.push = n;
    n.loaded = !0;
    n.version = '2.0';
    n.queue = [];
    t = b.createElement(e);
    t.async = !0;
    t.src = v;
    s = b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t, s)
  }(window, document, 'script',
    'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '474216530034138');
  fbq('track', 'PageView');
  </script>
  <noscript>
    <img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=474216530034138&ev=PageView&noscript=1" ; />
  </noscript>
  <!-- End Facebook Pixel Code -->
*/?>

  <? if(strpos(" ".$_SERVER['REQUEST_URI'], 'cifrovye-pianino')>0) { ?>
  <!-- Marquiz script start -->
  <script async src="//script.marquiz.ru/v1.js" type="application/javascript"></script>
  <script>
  document.addEventListener("DOMContentLoaded", function() {
    Marquiz.init({
      id: '5e2594d93c798700445362dc',
      autoOpen: 90,
      autoOpenFreq: 'once',
      openOnExit: false
    });
  });
  </script>
  <!-- Marquiz script end -->
  <? } ?>



</head>

<body>




  <div class="wrapper" id="anchor">
    <? include_once __DIR__.'/views/header.php'; ?>
    <? include_once __DIR__.'/views/main.php'; ?>
    <? include_once __DIR__.'/views/footer.php'; ?>
	<? include_once __DIR__.'/views/modals/modals.php'; ?>
  </div>



  <a href="#anchor" class="b-footer__anchor"></a>

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript" >
      (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
          m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
      (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

      ym(15540343, "init", {
          clickmap:true,
          trackLinks:true,
          accurateTrackBounce:true,
          webvisor:true,
          ecommerce:"dataLayer"
      });
  </script>

  <noscript><div><img src="https://mc.yandex.ru/watch/15540343" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
  <!-- /Yandex.Metrika counter -->


  <jdoc:include type="modules" name="feedback" style="login" />

<script>
	  ! function(f, b, e, v, n, t, s) {
		if (f.fbq) return;
		n = f.fbq = function() {
		  n.callMethod ?
			n.callMethod.apply(n, arguments) : n.queue.push(arguments)
		};
		if (!f._fbq) f._fbq = n;
		n.push = n;
		n.loaded = !0;
		n.version = '2.0';
		n.queue = [];
		t = b.createElement(e);
		t.async = !0;
		t.src = v;
		s = b.getElementsByTagName(e)[0];
		s.parentNode.insertBefore(t, s)
	  }(window,
		document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
	  fbq('init', '1066230696728376'); // Insert your pixel ID here.
	  fbq('track', 'PageView');
	  </script>
	  <noscript>
          <img height="1" width="1" style="display:none"
		  src="https://www.facebook.com/tr?id=1066230696728376&ev=PageView&noscript=1" />
      </noscript>




	<!-- Google+ API -->
  <script src="https://apis.google.com/js/platform.js" async defer>
  {
    lang: 'ru'
  }
  </script>
  <!-- /Google+ API -->


  <script>

  function agoogle(action1) {
    if (jQ(".products-title").length > 0)
      prod = jQ(".products-title").html();
    else
      prod = jQ("title").text();

    _gaq.push(['_trackEvent', action1, 'view', prod]);
  }
  var y_;
  var g_;

  function y_g() {
    try {
      yaCounter15540343.reachGoal(y_);
        console.log("yaCounter");
    } catch (e) {}

    try {
      // --- старый gtag:
      ga('send', 'event', 'Knopka', g_);
        console.log("old_gtag");
    } catch (e) {}

      try {
          // --- новый gtag:
          gtag('event', g_, {'event_category' : 'Knopka'});
          console.log("new_gtag");
      } catch (e) {}
  }

  function event_send(yandex_, google_) {
    y_ = yandex_;
    g_ = google_;
    setTimeout(y_g, 100);
  }
  </script>

  <?

      // include_once($_SERVER['DOCUMENT_ROOT']."/z/sync/pixel.php"); --- временно отключаем пиксель на СРМ
/*
if( ($jshop_product_id>0) && ($roi_id>0) )
{
    // --- если мы показываем товар, то нужно сделать сопоставление рои_ид с товаром и записать этот код в базу
    $code = substr(strtoupper(md5($roi_id*$jshop_product_id)), 0, 4);
    echo "\n<script>document.getElementById('roicode').innerHTML = ' #{$code}';</script>\n";
    $db->setQuery("SELECT * FROM #__z_roi_to_product WHERE product_id={$jshop_product_id} AND roi_id={$roi_id}");
    if(!$db->loadObject())
    {
        $db->setQuery("INSERT INTO #__z_roi_to_product (product_id, roi_id, code) VALUES ({$jshop_product_id}, {$roi_id}, '{$code}')");
        $db->execute();
    }
}
*/

?>


  <script src="/templates/pianino_new/js/libs-new.js"></script>
  <script src="/templates/pianino_new/js/jquery-ui.js"></script>
  <script async src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe.min.js"></script>
  <script async src="https://cdnjs.cloudflare.com/ajax/libs/photoswipe/4.1.3/photoswipe-ui-default.min.js"></script>
  <script async src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
  <script src="/templates/pianino_new/js/js_06_04_21.js"></script>
  <script src="/templates/pianino_new/js/common.js"></script>

  <script async type="text/javascript"
    src="https://cdn.callbackkiller.com/widget/cbk.js?wcb_code=fb867ed2c97d37de274bc3650aa8a698" charset="UTF-8">
  </script>

  <script async type="text/javascript"
    src="/templates/pianino_new/js/searchSuper.js" charset="UTF-8">
  </script>

  <?php
  
/* --- временно убрали по просьбе Стаса от 30.03.21
    if( (sizeof($roicode_products)>0)  && ($roi_id>0) )
    {
        $ids = "-1";
        foreach($roicode_products AS $pr)
        {
            $ids .= ", ".$pr;
        }

        $db->setQuery("SELECT * FROM #__z_roi_to_product WHERE product_id IN ({$ids}) AND roi_id={$roi_id}");
        $prods = Array();
        if($all = $db->loadObjectList())
        {
            foreach($all AS $a)
                $prods[$a->product_id] = $a->code;
        }

        $scripts = "";
        foreach($roicode_products AS $r)
        {
            $code = substr(strtoupper(md5($roi_id*$r)), 0, 4);
            if(!isset($prods[$r]))
            {
                $db->setQuery("INSERT INTO #__z_roi_to_product (product_id, roi_id, code) VALUES ({$r}, {$roi_id}, '{$code}')");
                $db->execute();
            }
            $scripts .= "$('#roiprod_{$r}').html('Артикул: {$code}');\n";
        }

        echo "<script>
            $(document).ready(function()
            {
                {$scripts}
            });
        </script>";
    }
*/
?>
</body>

</html>