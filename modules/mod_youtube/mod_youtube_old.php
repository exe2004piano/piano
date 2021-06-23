<?php
return;
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
        <nav class="b-slider">
            <ul class="b-slider__list b-slider__list--news" data-max="4">
<?php
    $i=0;
    foreach($text AS $t)
    {
        $n = explode("=", $t);
        $img = trim($n[0]);
        $adr_ = '';

        if(file_exists(JPATH_ROOT.'/images/'.$img.".mp41"))
        {
            $adr_ = "
            <video
                class='mp4_'
                autoplay='autoplay'
                loop
                poster='http://pianino.by/images/{$img}.jpg'
                width='260'
                height='150'
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
           $adr_ = "<img src='http://pianino.by/images/temp.png' rel='http://pianino.by/images/{$img}.jpg' class='postloader_src' alt='{$n[1]}'>";
        }





    //    JGI4XMie4Vo = Новый обзор на новинку: цифровое пианино Yamaha YDP-143 = /novosti/item/novyj-obzor-na-novinku-tsifrovoe-pianino-yamaha-ydp-143
    ?>
                <li class="b-slider__item">
                    <div class="b-slider__content">
                        <div class="b-slider__reviewImg">
                            <?php echo $adr_; ?>
                        </div>
                        <a href="<?php echo trim($n[2]); ?>" class="b-slider__reviewTitle"><?php echo $n[1]; ?></a>
                    </div>
                </li>
    <?php
        $i++;
        if($i>7)
            break;
    }
?>
            </ul>
            <div class="b-slider__nav b-slider__nav--left"></div>
            <div class="b-slider__nav b-slider__nav--right"></div>
        </nav>
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

