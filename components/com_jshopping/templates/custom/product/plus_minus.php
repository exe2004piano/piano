<?php
defined( '_JEXEC' ) or die();
// ---- плюсы минусы для кого:


/*
if(strlen(trim(no_tags($this->product->tab_5)))>1)
{
?>
    <p></p>
    <iframe width="100%" height="150" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/tracks/<?php echo $this->product->tab_5; ?>&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&visual=true"></iframe>
    <p></p><div class="clr" style="height: 15px!important;"></div>
<?php
}
*/

if(strlen(trim(no_tags($this->product->z_text_plus.$this->product->z_text_minus)))>30)
{
    // echo '<div class="b-detal__aboutTitle" style="margin-top: 30px;">Достоинства модели</div>';

    if(strlen(trim($this->product->z_text_plus))>30)
    {
        ?>
        <nav class="b-detal__about">
            <h4 class="b-detal__aboutTitle p14 js-title">Плюсы:</h4>
            <ul class="b-detal__aboutList js-hide">
                <?php
                $temp = explode("\n", $this->product->z_text_plus);
                foreach($temp AS $t)
                {
                    $t = trim(str_replace("-", " ", no_tags($t)));
                    if($t!='')
                        echo '<li class="b-detal__aboutItem">• '.$t.'</li>';
                }
                ?>
            </ul>
        </nav>
    <?php
    }
    ?>


    <?php
    if(strlen(trim($this->product->z_text_minus))>30)
    {
        ?>
        <nav class="b-detal__about">
            <h4 class="b-detal__aboutTitle p14 js-title">Минусы:</h4>
            <ul class="b-detal__aboutList js-hide">
                <?php
                $temp = explode("\n", $this->product->z_text_minus);
                foreach($temp AS $t)
                {
                    $t = trim(str_replace("-", " ", no_tags($t)));
                    if($t!='')
                        echo '<li class="b-detal__aboutItem">• '.$t.'</li>';
                }
                ?>
            </ul>
        </nav>
    <?php
    }
    ?>


    <?php
    if(strlen(trim($this->product->z_text_for))>30)
    {
        ?>
        <nav class="b-detal__about">
            <h4 class="b-detal__aboutTitle p14 js-title">Для кого предназначена модель:</h4>
            <ul class="b-detal__aboutList js-hide">
                <?php
                $temp = explode("\n", $this->product->z_text_for);
                foreach($temp AS $t)
                {
                    $t = trim(str_replace("-", " ", no_tags($t)));
                    if($t!='')
                        echo '<li class="b-detal__aboutItem">• '.$t.'</li>';
                }
                ?>
            </ul>
        </nav>
    <?php
    }
    ?>


<?php
}
?>
