<?php
defined( '_JEXEC' ) or die();
// --- вывод мануала если есть

                    if(file_exists('manual/' . $it->m_title . "/" .$it->product_ean . '.pdf'))
                    {
                        // $db->setQuery("SELECT * FROM #__z_config WHERE name='before_info' OR name='after_info' ORDER BY name DESC");
                        // $res = $db->loadObjectList();
                        // $row->text = str_replace('Инструкция}', 'Инструкция}<a href="/manual/' . $it->m_title . "/" . $it->product_ean . '.pdf" target="_blank" >' . $res[0]->value . " " . $it->title . " " . $res[1]->value . '</a>', $row->text);

                        ?>
                        <div class="b-detal__material js_listWrap">
                            <h2 class="b-section__title b-section__title--notLink">Дополнительные материалы</h2>
                            <a href="#" class="b-detal__materialShow js_listLink">Показать инструкции</a>
                            <ul class="b-detal__materialList js_listBlock">
                                <li class="b-detal__materialItem">
                                    <div class="b-detal__materialImg">
                                        <img src="/templates/pianino_new/i/materials.png" alt="">
                                    </div>
                                    <div class="b-detal__materialContent">
                                        <h4 class="b-detal__materialTitle">Инструкция по эксплуатации</h4>
                                        <a href="/manual/<?php echo $it->m_title . "/" . $it->product_ean . ".pdf"; ?>" target="_blank" class="b-detal__materialLink">Посмотреть</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    <?php
                    }
