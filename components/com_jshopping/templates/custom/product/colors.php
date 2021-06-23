<?php
	$color_name = 'meta_title_be-BY';
	$c_title = trim($product->$color_name);
	$c_title = trim(mb_substr($c_title, 0, mb_strlen($c_title, "utf-8")-2, "utf-8"));

	// --- иногда товары заканчиваются не маркой цвета - предусмотреть на будущее
	$q = "
SELECT p.product_id, p.extra_field_8 color, c.category_id
FROM #__jshopping_products AS p
LEFT JOIN #__jshopping_products_to_categories AS c ON c.product_id = p.product_id
WHERE p.`meta_title_be-BY` LIKE " . $db->quote($c_title.'%') ." AND p.extra_field_8<>'0' AND p.extra_field_8<>''
GROUP BY p.product_id
ORDER BY c.product_ordering
";

	// AND p.product_id<>{$product->product_id}
	// ($product->extra_field_8);

	$db->setQuery($q);
	if( ($res = $db->loadObjectList()) && (sizeof($res)>1) )
	{
		// --- есть цвета, отличные от нашего = есть смысл загрузить цвета
		$q = "SELECT id, `name_ru-RU` title FROM #__jshopping_products_extra_field_values WHERE field_id=8 ORDER BY id";
		$db->setQuery($q);
		$temp_colors = $db->loadObjectList();
		$colors = null;
		foreach($temp_colors AS $c)
			$colors[$c->id] = $c->title;
		unset($temp_colors);
		?>

		<div class="inf__body-row">
			<p class="inf__body-title">Варианты цветов:</p>
			<div class="color-palette-wrap">

				<?php
					$i=1;
					foreach($res AS $r)
					{
						$link = SEFLink('index.php?option=com_jshopping&controller=product&task=view&category_id='.$r->category_id.'&product_id='.$r->product_id, 1);
						if($r->product_id!=$product->product_id)
						{
							?>

							<div class="color-palette__item">
								<a href="<?=$link;?>" title="<?php echo $colors[$r->color]; ?>" class="color-palette__color color_<?php echo $r->color;?>" ></a>
							</div>
							<?
						}
						else
						{
							?>
                            <div class="color-palette__item">
                                <a href="" onclick="location.reload(); return false;" title="<?php echo $colors[$r->color]; ?>" class="color-palette__color color_<?php echo $r->color;?>" ></a>
                            </div>
							<?
						}
					}
				?>
			</div>
		</div>
		<?php
	}
