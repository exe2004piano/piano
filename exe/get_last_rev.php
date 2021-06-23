<?php

function parse_after($temp, $tag, $tag_end)
{
    if(strpos($temp, $tag)>0)
    {
        $start = strpos($temp, $tag) + strlen($tag);
        $end = strpos($temp, $tag_end, $start);
        return trim(substr($temp, $start, $end-$start));
    }
    else
        return "";
}

$all = $all1 = Array();
$num = 0;

// ---y.market
if(isset($_POST['market']))
{
    $tt = $_POST['market']; //file_get_contents('https://market.yandex.ru/shop/134731/reviews');
    $text = explode('n-product-review-user__name', iconv("windows-1251", "utf-8", $tt));
    unset($text[0]);
    unset($tt);
    foreach($text AS $t)
    {
        $title = $name = parse_after($t, 'itemprop="author">', '</span>');
        $data = parse_after($t, '<span class="n-product-review-item__date-region">', '<');
        $ocenka = parse_after($t, 'class="n-product-review-item__rating-label">', '<');
        $ball = parse_after($t, '<meta itemprop="ratingValue" content="', '"');
        $info = parse_after($t, '<dd class="n-product-review-item__text">', '</dd>');

        $all[$num]['type'] = 'market';
        $all[$num]['name'] = trim($name);
        $all[$num]['data'] = trim($data);
        $all[$num]['title'] = trim($title);
        $all[$num]['ocenka'] = trim($ocenka);
        $all[$num]['ball'] = trim($ball);
        $all[$num]['info'] = trim($info);

        $num++;
        if($num>=5)
            break;
    }
}



// --- onliner:
if(isset($_POST['onliner']))
{
    $text = explode('<div class="review-block">', iconv("windows-1251", "utf-8", $_POST['onliner']));
    unset($text[0]);


    foreach($text AS $t)
    {
        $name = parse_after($t, '</span>', '</a>');
        $data = parse_after($t, '<span class="review-date">', '</span>');
        $title = parse_after($t, '<p class="review-title">', '</p>');
        $ocenka = parse_after($t, '<p class="review-rating-post">', '</p>');
        $ball = trim(parse_after($t, '<p class="sells-desc__stars', '"'));
        $ball = trim(str_replace(Array("_", "0"), "", $ball));
        $info = parse_after($t, '<p class="review-bayer-comment">', '</p>');

        $all[$num]['type'] = 'onliner';
        $all[$num]['name'] = trim($name);
        $all[$num]['data'] = trim($data);
        $all[$num]['title'] = trim($title);
        $all[$num]['ocenka'] = trim($ocenka);
        $all[$num]['ball'] = trim($ball);
        $all[$num]['info'] = trim($info);

        $num++;
        if($num>=10)
            break;
    }
}


file_put_contents($_SERVER['DOCUMENT_ROOT'].'/rev.all', serialize($all));
