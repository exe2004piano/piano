<?php

defined( '_JEXEC' ) or die();
// --- текстовый обзор зоо в товар:

                $q = "
                SELECT i.application_id, i.name, i.alias, i.elements, i.params, cat.alias cat_alias
                FROM #__zoo_item AS i
                LEFT JOIN #__zoo_application AS cat ON cat.id=i.application_id
                LEFT JOIN #__z_zoo_to_products AS z_c ON z_c.zoo_id=i.id
                WHERE z_c.product_id={$product->product_id}
                ORDER BY z_c.id
                DESC LIMIT 1
                ";
                $db->setQuery($q);
                if($a = $db->loadObject())
                {

                    $text_new = '';
                    $e = json_decode($a->elements);
                    foreach($e AS $key=>$value)
                    {
                        foreach($value AS $k=>$v)
                        {
                            if(strlen($v->value)>100)
                                $text_new = $v->value;
                        }
                    }

                    if($text_new!='')
                    {
                        $img = "";

                        if(strpos($text_new, '<img')>0)
                        {
                            $img = substr($text_new, strpos($text_new, '<img'));
                            $img = substr($img, 0, strpos($img, '>')+strlen('>'));
                        }
                        $title = $a->name;
                        $text_new = trim(no_tags($text_new));
                        $text_new = substr($text_new, 0, 600);
                        $text_new = no_tags(substr($text_new, 0, strrpos($text_new, ' ')) . '...');
                        $link = "/".$a->cat_alias."/item/".$a->alias;

                        ?>
                        <div class="b-detal__textReview">
                            <h2 class="b-section__title b-section__title--notLink">Текстовый обзор</h2>
                            <div class="b-detal__textReview-content">
                                <div class="b-detal__textReview-img">
                                    <?php echo $img; ?>
                                </div>
                                <div class="b-detal__textReview-text">
                                    <div class="b-item__reviewText">
                                        <p><?php echo $text_new; ?></p>
                                    </div>
                                    <a href="<?php echo $link;?>" class="b-item__reviewMore-modal">Читать далее</a>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                }
