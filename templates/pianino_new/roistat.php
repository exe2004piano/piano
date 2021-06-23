<?php defined( '_JEXEC' ) or die();


$is_bot = preg_match(
        "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i",
        $_SERVER['HTTP_USER_AGENT']
);

if($is_bot)
	return;

include_once($_SERVER['DOCUMENT_ROOT']."/configuration.php");
$c = new JConfig;
$to = $c->crm;
global $roi_id;
global $z_country;

if( ($z_country!='BY') && ($z_country!='RU') )
{
	$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/log/no_roi.txt', "a");
	fwrite($fp, $z_country.": ".serialize($_SERVER)."\r\n\r\n");
	fclose($fp);

	return;
}

function http_post ($url, $data)
{
    $data_url = http_build_query ($data);
    $data_len = strlen ($data_url);

    return array ('content'=>file_get_contents ($url, false, stream_context_create (array ('http'=>array ('method'=>'POST'
    , 'header'=>"Connection: close\r\nContent-Length: $data_len\r\n"
    , 'content'=>$data_url
    ))))
    , 'headers'=>$http_response_header
    );
}


// проверим на ботность, если нет - то запишем данные первого входа
$a = $_SERVER['HTTP_USER_AGENT'];
$bot = 0;
$roi_id = 0;
$roi_code = 99;
$is_new = 0;


if((strpos(" ".$_SERVER['REQUEST_URI'], '404')<1) )
{
    if (!isset($_COOKIE['roi_new']))
    {
        // -- новый юзер, нужно установить куку = id захода
        $t = 1*time();
//        $q = "SELECT id FROM #__z_roistat_new WHERE trim(ip)=" . $db->quote(trim($_SERVER['REMOTE_ADDR'])) . " AND ({$t} - last_time<86400) LIMIT 1";
//        $db->setQuery($q);
//        if(!$res = $db->loadObject())
        {
            $roi_obj = new stdClass();
            $roi_obj->ip = $_SERVER['REMOTE_ADDR'];
            $roi_obj->referer = $_SERVER['HTTP_REFERER'];
            // $roi_obj->user_agent = $_SERVER['HTTP_USER_AGENT'];
            $roi_obj->cur_page = $_SERVER['REQUEST_URI'];
            // $roi_obj->all_info = serialize($_SERVER);
            $roi_obj->last_time = $t;
            $roi_obj->country = $z_country;

            include_once (JPATH_BASE."/z/roi_class.php");
            $itog = get_roi($roi_obj);
            $roi_obj->z_zapros = $itog->zapros;
            $roi_obj->z_zapros_real = $itog->zapros_real;

            $itog->site = "  " . $itog->site . "  ";

            // --- в зависимости от полученного источника перехода раскроим данные и запишем в таблицу:
            if(strpos($itog->site, 'Yandex-Direct')>0)
            {
                $roi_obj->z_code = $roi_code = 11;     // --- код яндекс-директа
                $roi_obj->z_istok = 'Yandex-Direct';
                $roi_obj->z_comp = trim(str_replace(array('Yandex-Direct', '/', '#'), '', $itog->site));
            }
            elseif(strpos($itog->site, 'Yandex-RSYA')>0)
            {
                $roi_obj->z_code = $roi_code = 12;     // --- Yandex-RSYA
                $roi_obj->z_istok = 'Yandex-RSYA';
                $roi_obj->z_comp = trim(str_replace(array('Yandex-RSYA', '/', '#'), '', $itog->site));
            }
            elseif(strpos($itog->site, 'yandex.')>0)
            {
                $roi_obj->z_code = $roi_code = 13;     // --- yandex-SEO
                $roi_obj->z_istok = 'Yandex-SEO';
                $roi_obj->z_comp = '';
            }
            elseif(strpos($itog->site, 'Google-Adwords')>0)
            {
                $roi_obj->z_code = $roi_code = 21;     // --- код Google-Adwords
                $roi_obj->z_istok = 'Google-Adwords';
                $roi_obj->z_comp = trim(str_replace(array('Google-Adwords', '/', '#'), '', $itog->site));
            }
            elseif(strpos($itog->site, 'Google-KMS')>0)
            {
                $roi_obj->z_code = $roi_code = 22;     // --- код Google-KMS
                $roi_obj->z_istok = 'Google-KMS';
                $roi_obj->z_comp = trim(str_replace(array('Google-KMS', '/', '#'), '', $itog->site));
            }
            elseif(strpos($itog->site, 'google.')>0)
            {
                $roi_obj->z_code = $roi_code = 23;     // --- код google-seo
                $roi_obj->z_istok = 'Google-SEO';
                $roi_obj->z_comp = '';
            }
            elseif(strpos($itog->site, 'adtarget.me')>0)
            {
                $roi_obj->z_code = $roi_code = 31;     // --- код adtarget.me
                $roi_obj->z_istok = 'Adtarget.me';
                $roi_obj->z_comp = trim(str_replace(array('adtarget.me', '/', '#'), '', $itog->site));
            }
            elseif(strpos($itog->site, 'youtube')>0)
            {
                $roi_obj->z_code = $roi_code = 41;     // --- код youtube
                $roi_obj->z_istok = 'youtube';
                $roi_obj->z_comp = trim(str_replace(array('youtube', '/', '#'), '', $itog->site));
            }
            else
            {
                $roi_obj->z_code = $roi_code = 99;     // --- код прямого захода либо уже был на страницах сайта
                $roi_obj->z_istok = trim($itog->site);
                $roi_obj->z_comp = '';
            }


            $result = $db->insertObject('#__z_roistat_new', $roi_obj);
            $id = $db->insertid();
			$is_new = 1;

            //$db->setQuery("UPDATE #__z_roistat_new SET user_agent='', all_info='' ");
            //$db->execute();
        }
        $roi_id = $id;
        // --- для новой записи юзера созданного ранее определим город с помощью яндекс-карт и запишем его в данную куку
        ?>
        <script>
            set_cookie('roi_new', '<?php echo $id; ?>', cookie_time);
            set_cookie('roi_new_city', '?', cookie_time);
        </script>
    <?php
    }
    else
    {
        // --- $_COOKIE['roi_new'] установлен, можем следить на перемещением юзера:
		/*
        $q = "SELECT cur_page FROM #__z_roistat_new WHERE id=" . (1*$_COOKIE['roi_new']);
        $db->setQuery($q);
        $res = $db->loadObject();
        $cur_page = $res->cur_page . " ~~~ " . $_SERVER['REQUEST_URI'];

        $q = "UPDATE #__z_roistat_new SET cur_page = " . $db->quote($cur_page) . " WHERE id=" . (1*$_COOKIE['roi_new']);
        $db->setQuery($q);
        $db->execute();
		*/
        $roi_id = $_COOKIE['roi_new'];
    }


    if($is_manager==1)
    {
        $roi_code = 33;
        $q = "UPDATE #__z_roistat_new SET z_code=33,z_istok='work',z_comp='work',user_agent='',all_info='' WHERE id={$roi_id}";
        $db->setQuery($q);
        $db->execute();
    }




    /*
     * EXECUTER:
     * отправка данных страницы в CRM
     * шлем roi_id + данные из _SERVER + номер товара если он есть и номер категории если она есть + номер страницыы зоо
     * + строку поиска и прочие данные - нужно всё продумать!
     * отправку будем делать для каждой страницы, чтобы определить все касания из поисковых систем
     */
	 
    if(($roi_code!=33) && ($is_new==1) )
    {
        $s_key = "GDFG345908sdw_34098apomzbsdfkjh5";
        $time = time();
        unset($send);
        $send =
        Array(
            'roi_id' => $roi_id,
            'roi_ip' => $_SERVER['REMOTE_ADDR'],
            'roi_referer' => $_SERVER['HTTP_REFERER'],
            'roi_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'roi_cur_page' => $_SERVER['REQUEST_URI'],
            'site_id' => 1,
            'key' => 'CVPOWE234598agiwSe2fsdjhfsdj90Ra',
            // --- пока что отправим и это, но нужно будет переделать !!!
            'roi_all_info' => ($_SERVER['REDIRECT_URL'] . "|" . $_SERVER['QUERY_STRING'] . "|" . $_SERVER['REQUEST_URI'] . "|" . serialize($_GET)),
            'time' => $time,
            'code' => (md5($s_key.$roi_id.$_SERVER['REMOTE_ADDR']."1".$_SERVER['HTTP_REFERER'].$time)),
            );

       http_post("http://{$to}/index.php?controller=ext&action=roi", $send);
    }
}
