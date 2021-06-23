<?php

defined('_JEXEC') or die;
$db = JFactory::getDbo();
$q = "SELECT * FROM #__z_config WHERE name='youtube'";
$db->setQuery($q);
$res = $db->loadObject();

$text = explode("\n", $res->value);


// --- видео на главной:

$addr = trim($_SERVER['REQUEST_URI']);
if(strpos(" ".$addr, '?')>0)
    $addr = substr($addr, 0, strpos($addr, '?'));


if($addr == '/')
{
?>
<section class="b-review">
    <div class="container">
        <div class="b-section__title">
            <span>Наши видеообзоры</span>
            <a href="/youtube">Смотреть все</a>
        </div>


        <div class="b-review__items">

            <?php
            $i=0;
            foreach($text AS $t)
            {
                $n = explode("=", $t);
                $img = trim($n[0]);
                $adr_ = '';

                if(file_exists(JPATH_ROOT.'/images/'.$img.".mp4"))
                {
                    $adr_ = "
            <video
                autoplay='autoplay'
                loop
                poster='".get_webp("/images/{$img}.jpg")."'
                >
                    <source src='/images/{$img}.mp4' type='video/mp4'></source>
            </video>";
                }
                else
                {
                    if(!file_exists(JPATH_ROOT.'/images/'.$img.".jpg"))
                    {
                        $img = 'youtube';
                    }
                    $adr_ = "<img src='/images/{$img}.jpg' alt='{$n[1]}'>";
                }

                echo "
                <div class='b-review__item'>
                    <a href='".trim($n[2])."' class='b-review__block'>
                       <div class='lazyload'>
                        <!-- 
                        {$adr_}
                        <p>{$n[1]}</p>
                        -->
                       </div>
                    </a>
                </div>";

                $i++;
                if($i>3)
                    break;
            }
            ?>

        </div>

    </div>
</section>
<?php
}

















else
// --- видео на канале:
{
    echo "<h1><a href='https://www.youtube.com/user/pianinoby/feed' target='_blank'>Наш канал на Youtube</a></h1>\n";
    echo "<table style='width:100%;'>\n";
    foreach($text AS $t)
        if(trim($t)!='')
        {
            $n = explode("=", $t);
            if(trim($n[0])!='')
            {
                echo "<tr>\n";
                echo "<td>" . trim($n[1]) . "<br /><iframe width='600' height='340' src='//www.youtube.com/embed/" . trim($n[0]) . "?rel=0&amp;controls=1&amp;showinfo=0' frameborder='0' ></iframe><br /><br /><br /></td>\n";
                echo "</tr>\n";
            }
        }
    echo "</table>\n";
}

