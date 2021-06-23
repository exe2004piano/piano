<?
	defined('_JEXEC') or die('Restricted access');
	$alias = $temp[2];
	global $ordering_id;
	global $url;
	$ordering_id = 0;
	if(isset($_GET['order']))
		$ordering_id = 1*$_GET['order'];

	$db = JFactory::getDbo();
	$item = $db->setQuery("SELECT * FROM #__z_rating WHERE active=1 AND alias=".$db->quote($alias))->loadObject();

	if($item->id*1==0)
	{
		JError::raiseError(404, 'not found');
		return;
	}

	$app = JFactory::getApplication();
	$pathway = $app->getPathway();
	$pathway->addItem($item->h1, '/'.$temp[1].'/'.$temp[2]);

	$document = JFactory::getDocument();
	$document->setTitle($item->title);
	$document->setMetaData('description', $item->meta_desc);

	global $manager;
	$dop_q = '';
	if($manager!='')
    {
        $man = $db->setQuery("
            SELECT *
            FROM #__users 
            WHERE `username`=".$db->quote($manager))->loadObject();
        if($man->id*1==0)
        {
			JError::raiseError(404, 'not found');
			return;
        }

		$dop_q = " AND manager_id={$man->id}";
		$pathway->addItem("Мнение менеджера ".$man->name, '/'.$temp[1].'/'.$temp[2].'/'.$temp[3]);
        $info = $db->setQuery("SELECT * FROM #__z_rating_manager_text WHERE manager_id={$man->id} AND rating_id={$item->id}")->loadObject();
        if($info->id*1>0)
            $item->description = $info->info;
        else
			$item->description = '';
    }

	$cats = $db->setQuery("
      SELECT c.category_id c_id, c1.category_id c1_id, c2.category_id c2_id, c.params
      FROM #__jshopping_categories AS c
      LEFT JOIN #__jshopping_categories AS c1 ON c.category_id=c1.category_parent_id
      LEFT JOIN #__jshopping_categories AS c2 ON c1.category_id=c2.category_parent_id
      WHERE c.category_id={$item->cat_id}
    ")->loadObjectList();

	$cat_ids = $item->cat_id;
	$params = "";
	foreach ($cats AS $c)
	{
		$params = $c->params;
		if($c->c1_id*1 > 0)
			$cat_ids .= ", ".$c->c1_id;

		if($c->c2_id*1 > 0)
			$cat_ids .= ", ".$c->c2_id;
	}

	$prods = $db->setQuery("
        SELECT p.*
        FROM #__z_rating_products AS p
        LEFT JOIN #__jshopping_products_to_categories AS pc ON p.product_id=pc.product_id
        WHERE pc.category_id IN ({$cat_ids}) {$dop_q}
        ")->loadAssocList();

	if(sizeof($prods)==0)
	{
		JError::raiseError(404, 'not found');
		return;
	}

	$params = json_decode($params);
	$pars = Array();
	foreach ($params AS $p)
		$pars[$p->id] = $p->value;


	$ratings = Array();
	$managers = Array();
	$prods_ids = "-1";
	foreach($prods AS $p)
	{
		$managers[$p['manager_id']] = $p['manager_id'];
		if(!isset($ratings[$p['product_id']]))
		{
			$prods_ids .= ", ".$p['product_id'];
			$ratings[$p['product_id']] = Array();
		}

		if(!isset($ratings[$p['product_id']][$p['param_id']]))
		{
			$ratings[$p['product_id']][$p['param_id']] = Array(
				"value" => 0,
				"num" => 0,
				"managers" => Array(),
				"name" => $pars[$p['param_id']],
			);
		}

		$ratings[$p['product_id']][$p['param_id']]['value'] += $p['value'];
		$ratings[$p['product_id']][$p['param_id']]['managers'][$p['manager_id']] = $p['manager_id'];
		$ratings[$p['product_id']][$p['param_id']]['num']++;
	}

	$products = $db->setQuery("
        SELECT p.* , m.`name_ru-RU` m_name
        FROM #__jshopping_products AS p
        LEFT JOIN #__jshopping_manufacturers AS m ON m.manufacturer_id=p.product_manufacturer_id
        WHERE p.product_publish=1 AND p.sklad<>2 AND p.sklad<>3 AND p.sklad<>5 AND p.product_id IN ({$prods_ids})")->loadAssocList();

	$managers_ids = "-1";
	foreach($managers AS $m)
		$managers_ids .= ", ".$m;

	$managers = Array();
	$temp = $db->setQuery("SELECT username, `name` FROM #__users WHERE id IN ({$managers_ids})")->loadObjectList();
	foreach ($temp AS $t)
		$managers[$t->username] = $t->name;

	// --- attrs:
	$attrs = Array();
	$attrs_names = Array();
	$manufacturers = Array();
	$price_min = 999999999;
	$price_max = 0;
	$temp = explode(",", $item->attrs);
	foreach ($temp AS $t)
	{
		$t=1*$t;
		if($t==0)
			continue;

		$attrs[$t] = Array();
	}
	$attrs_ids = "-1";
	foreach ($products AS $p)
	{
		$manufacturers[$p['product_manufacturer_id']] = $p['m_name'];
	    if($p['product_price'] > $price_max)
			$price_max = $p['product_price'];

		if($p['product_price'] < $price_min)
			$price_min = $p['product_price'];

		foreach ($p AS $key=>$val)
		{
			if(strrpos($key, "tra_field_")<1)
				continue;
			if($val*1==0)
				continue;
			$key = 1*str_replace("extra_field_", "", $key);
			if( ($key==0) || (!isset($attrs[$key])) )
				continue;

			$attrs[$key][$val] = $val;
			$attrs_ids .= ", ".$val;
		}
	}


	if(strpos($item->attrs, ",")>0)
    {
		if($temp = $db->setQuery("SELECT * FROM #__jshopping_products_extra_fields WHERE id IN ({$item->attrs})")->loadObjectList())
		{
			foreach ($temp AS $t)
			{
				$attrs_names[$t->id] = $t->{"name_ru-RU"};
			}
		}
	}

	if($temp = $db->setQuery("SELECT * FROM #__jshopping_products_extra_field_values WHERE id IN ({$attrs_ids})")->loadObjectList())
	{
		foreach ($temp AS $t)
		{
			$attrs[$t->field_id][$t->id] = $t->{"name_ru-RU"};
		}
	}

// --------------------- фильтры
    echo "<div class='filters'>";
	if( (sizeof($attrs_names)>1) && (sizeof($attrs)>1) )
    {
        foreach ($attrs_names AS $a_id=>$a_name)
        {
            if(sizeof($attrs[$a_id])<2)
                continue;
            echo "<div>";
            echo "<select class='rating_filter' data-class='extra' id='extra_{$a_id}'>";
            echo "<option value=''>{$a_name}</option>";
            foreach ($attrs[$a_id] AS $av_id=>$av_name)
            {
                echo "<option value='{$av_id}'>{$av_name}</option>";
            }
            echo "</select>";
            echo "</div>";
        }
    }

    if(sizeof($manufacturers)>1)
    {
		echo "<div>";
		echo "<select class='rating_filter' data-class='manufacturers' id='manufacturers'>";
		echo "<option value=''>Производитель</option>";
		foreach ($manufacturers AS $m_id=>$m_name)
		{
			echo "<option value='manufacturer_{$m_id}'>{$m_name}</option>";
		}
		echo "</select>";
		echo "</div>";
    }
    echo "</div>";
// ------------------ END фильтры


	if(sizeof($managers)>1)
	{
		echo "<div class='filters'>Так же смотрите рейтинг инструментов от наших менеджеров: ";
		$r = "";
		foreach ($managers AS $m_log=>$m_name)
		{
			$r .= "<a href='{$url}{$m_log}'>{$m_name}</a> , ";
		}
		$r = substr($r, 0, strrpos($r, ','));
		echo $r;
		echo "</div>";
	}

?>
<div class="table-responsive">
    <table class="product_ratings">
        <tr>
            <td colspan="3" class="no-border-lrt"></td>
            <td>
                <a href="<?=$url;?>" class='<? if($ordering_id==0) echo "active";?>'>
                    Общий рейтинг
                </a>
            </td>
			<?php
				foreach ($ratings[$p['product_id']] AS $r_id => $val)
				{
					$class = "";
					if($ordering_id==$r_id)
						$class="active";
					echo "<td class='hide_mobile'>
                <a class='{$class}' href='{$url}?order={$r_id}'>{$pars[$r_id]}</a>
            </td>";
				}
			?>
        </tr>
		<?

			$rows = Array();
			$i=0;
			foreach($products AS $p)
			{
				$i++;

				$extra = "";
				foreach ($p AS $key=>$val)
				{
					if(strrpos($key, "tra_field_")<1)
						continue;
					if($val*1==0)
						continue;
					$key = 1*str_replace("extra_field_", "", $key);
					if( ($key==0) || (!isset($attrs[$key])) )
						continue;

					$extra.=" extra_val_{$val} ";
				}

				if($p['product_manufacturer_id']*1>0)
				    $extra .= " extra_val_manufacturer_".$p['product_manufacturer_id']." ";

				ob_start();
				?>
                <tr class="extra_val_tr <?=$extra;?>">
                    <td class="prod_num"></td>
                    <td class="prod_img">
                        <a target="_blank" href="<?=$p['real_link'];?>">
                            <img src="<?=(JIMG.$p['image']);?>" alt=""/>
                        </a>
                    </td>
                    <td class="prod_name">
                        <a target="_blank" href="<?=$p['real_link'];?>">
							<?=$p['name_ru-RU'];?>
                        </a><br /><br />
						<?php
							if($p['product_old_price']>0)
							{
								echo "<s>" . echo_price($p['product_old_price']) . "</s>";
							}
							echo "<span>".echo_price($p['product_price'])."</span>";
						?>
                    </td>
                    <td class="av_rating">
						<?
							$av_rating = 0;
							$av_num = 0;
							foreach ($ratings[$p['product_id']] AS $param_id => $rating)
							{
								$av_rating += $rating['value'];
								$av_num += $rating['num'];
							}

							$class = round($av_rating/$av_num*10);
							$rows[$i][0] = $av_rating/$av_num;

							$over50 = "";
							if($class>50)
								$over50 = ' over50 ';

						?>
                        <div class="progress-circle p<?=$class.$over50;?>">
                            <span><?=number_format($av_rating/$av_num, 1, ",", "");?></span>
                            <div class="left-half-clipper">
                                <div class="first50-bar"></div>
                                <div class="value-bar"></div>
                            </div>
                        </div>
						<?


							// echo "<div class='round_rating r_".round($av_rating/$av_num)."' >".number_format($av_rating/$av_num, 1, ".", "")."</div>";
						?>
                    </td>
					<?
						foreach ($ratings[$p['product_id']] AS $param_id => $rating)
						{
							$r = number_format($rating['value']/$rating['num'], 1, ",", "");
							$rows[$i][$param_id] = $rating['value']/$rating['num'];
							?>
                            <td class="str_rating hide_mobile"><?=$r;?></td>
							<?
						}
					?>
                </tr>
				<?
				$rows[$i]['content'] = ob_get_contents();
				ob_end_clean();
			}

			// rows теперь массив из наших строк, но их нужно еще отсортировать перед выводом
			// сортировать по убыванию значения rows[id] где id - параметр по которому нужна сортировка
			function cmp_function($a, $b)
			{
				global $ordering_id;
				return ($a[$ordering_id] < $b[$ordering_id]);
			}

			uasort($rows, 'cmp_function');

			foreach ($rows AS $r)
			{
				echo $r['content'] . "\n";
			}

		?>
    </table>
</div>

<div class="clr"></div>
<br />
<div>
	<?=$item->description;?>
</div>
<br />


<link href="/templates/pianino_new/css/new.css" rel="stylesheet" type="text/css" />



<script>
    $(document).on("ready", function(){
        $(".rating_filter").on("change", function()
        {
            var f_class='';
            $(".rating_filter").each(function()
            {
                if($(this).val()!='')
                    f_class += ".extra_val_"+$(this).val();
            });

            if(f_class!='') {
                $(".extra_val_tr").hide();
                $(f_class).show();
            }
            else
            {
                $(".extra_val_tr").show();
            }
        });
    });
</script>