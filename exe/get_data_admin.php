<?php


if($_POST['abracodabra']!='codabraabra')
    die;

if(!defined('_JEXEC'))
{
    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);
    if (file_exists(dirname(__FILE__) . '/defines.php'))
    {
        include_once dirname(__FILE__) . '/defines.php';
    }
    if (!defined('_JDEFINES'))
    {
        define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
        require_once JPATH_BASE.'/includes/defines.php';
    }
    require_once JPATH_BASE.'/includes/framework.php';
    $app = JFactory::getApplication('site');
    $app->initialise();
}

$db = JFactory::getDBO();


// выберем всех за последние сутки
// 24 * 60 *60 = 86400
$db->setQuery("SELECT z_zapros, z_zapros_real, z_code, z_comp, last_time, id, last_page, last_phone FROM #__z_roistat_new WHERE last_time+86400>".time() . " AND last_page<>'' ORDER BY id DESC");
if($res = $db->loadObjectList())
{
    echo "
    <table>
        <tr>
                <td style='width: 70px!important;'><b>ROI ID</b></td>
                <td style='width: 200px!important;'><b>Коннект</b></td>
                <td style='width: 200px!important;' ><b>Инфо</b></td>
                <td class='big_td'>Телефоны</td>
                <td class='big_td'>Последняя страница</td>
        </tr>
    ";

    $last_users = null;

    foreach($res AS $a)
    {
        $z_code = "---";
        $z_comp = $a->z_comp;
        $z_zapros = $a->z_zapros;
        $z_zapros_real = $a->z_zapros_real;

        switch($a->z_code)
        {
            case "11":
                $z_code = "Yandex-Direct";
                break;
            case "12":
                $z_code = "Yandex-RSYA";
                break;
            case "13":
                $z_code = "Yandex-SEO";
                break;


            case "21":
                $z_code = "Google-Adwords";
                break;
            case "22":
                $z_code = "Google-KMS";
                break;
            case "23":
                $z_code = "Google-SEO";
                break;

            case "31":
                $z_code = "Adtarget.me";
                break;
            case "41":
                $z_code = "youtube";
                break;
        }



        $phones = "";
        $temp = explode("/", $a->last_phone);
        foreach($temp AS $t)
            if(trim($t)!='')
                $phones .= trim($t)."<br />";

        // --- самые последние=активные последние 5 секунд, их сразу покажем

        $time = date("H-i-s", $a->last_time);

        $info = $z_code;
        if($z_comp!='')
            $info .= "<br />\n" . $z_comp;
        if($z_zapros!='')
            $info .= "<br />\n" . $z_zapros;
        if($z_zapros_real!='')
            $info .= "<br />\n(" . $z_zapros_real . ")";

        if($a->last_time+5>time())
        echo
        "
        <tr>
            <td>{$a->id}</td>
            <td>{$time}</td>
            <td>{$info}</td>
            <td>{$phones}</td>
            <td>". urldecode($a->last_page) . "</td>
        </tr>
        ";
        else
            $last_users[$a->last_time] =
            "
            <tr class='gray'>
                <td>{$a->id}</td>
                <td>{$time}</td>
                <td>{$info}</td>
                <td>{$phones}</td>
                <td>". urldecode($a->last_page) . "</td>
            </tr>
            ";

    }

    echo "<tr><td><br /><br /><br /><br /><br /><br />Кто уже отключился:<br /></td></tr>";

        krsort($last_users);

        foreach($last_users AS $key=>$l)
            echo $l;

        echo "</table>";

}
