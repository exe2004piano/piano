<?php defined( '_JEXEC' ) or die(); // --- модальные окна ?>

<div class="pop-ups">
  <? /* Новые поп апы сделать */ ?>


  <? /* Аналоги */ ?>
    <div class="pop__up" data-name-popup="analog" aria-labelledby="modal">
        <div class="pop__up-row">
            <div class="pop__up-title">
                <p>Подобрать аналог</p>
            </div>
            <div class="pop__up-text">
                <p>Подберем оптимальный аналог данной модели!</p>
            </div>

            <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" >

                <div class="bv-form__items">
                    <input type="hidden" id="product_analog" value="" name="product_title" />
                    <input type="hidden" id="product_analog_id" value="" name="pcmr_id" />
                    <input type="hidden" name="form_type" value="form_analog" />
                    <label class="bv-form__item">
                        <input type="text" class="bv-form__input" placeholder="Имя" name="user_name" required>
                    </label>
                    <label class="bv-form__item">
                        <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
                    </label>
                </div>

                <input type="submit" class="bv-btn bv-btn--third bv-form__submit" value="Подобрать"
                       onclick="event_send('Kupit_v_1klik2', 'Kupitv1klik2'); retail_one_click();">
            </form>


            <div class="close" data-name-popup="fastOrder" aria-label="Close">
                <span class="close__part" aria-hidden="true"></span>
                <span class="close__part" aria-hidden="true"></span>
            </div>
        </div>
    </div>














  <? /* Заказать */ ?>
  <div class="pop__up" data-name-popup="toOrder" aria-labelledby="modal">
    <div class="pop__up-row">
      <div class="pop__up-text">
        <p>Оставьте свои контактные данные. <br> Наши менеджеры свяжутся с вами для уточнения деталей заказа.</p>
      </div>

      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" novalidate>

        <input type="hidden" id="product_order" value="" name="product_title" />
        <input type="hidden" id="product_order_id" name="pcmr_id" value="" />
        <input type="hidden" id="product_order_price" name="pcmr_price" value="" />
        <input type="hidden" name="form_type" value="form_oneclick" />

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <input type="submit" class="bv-btn bv-btn--third bv-form__submit" value="Оповестить"
          onclick="event_send('Kupit_v_1klik2', 'Kupitv1klik2'); retail_one_click();">
      </form>

      <div class="pop__up-policy">
        <p>Нажатием кнопки «Оповестить» я даю свое согласие на обработку персональных данных</p>
      </div>

      <div class="close" data-name-popup="fastOrder" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>
  <? /* end. Новые поп апы сделать */ ?>






    <a href="#" data-get-popup="thankspopup" id="thanks_btn"></a>
    <div class="pop__up" data-name-popup="thankspopup" aria-labelledby="modal">
        <div class="pop__up-row">
            <div class="pop__up-text" >
                <p>
                    <img src="/templates/pianino_new/i/logo.svg" alt="" class="thanks_btn_img" />
                </p>

                <p> Спасибо! Ваши данные отправлены нашим менеджерам.
                    <br />
                    С Вами свяжутся как можно быстрее
                </p>
            </div>
            <div class="close" data-name-popup="thankspopup" aria-label="Close">
                <span class="close__part" aria-hidden="true"></span>
                <span class="close__part" aria-hidden="true"></span>
            </div>
        </div>
    </div>







  <? /* Оповестить */ ?>
  <div class="pop__up" data-name-popup="notify" aria-labelledby="modal">
    <div class="pop__up-row">
      <div class="pop__up-title">
        <p>Оповестить</p>
      </div>

      <div class="pop__up-text">
        <p>Оставьте свои контактные данные. <br> Наши менеджеры свяжутся с вами для уточнения деталей заказа.</p>
      </div>

      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" novalidate>

        <input type="hidden" id="product_anons_title" value="" name="product_title" />
        <input type="hidden" id="product_anons_id" name="pcmr_id" value="" />
        <input type="hidden" id="product_anons_price" name="pcmr_price" value="" />
        <input type="hidden" name="form_type" value="form_anons" />

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <input type="submit" class="bv-btn bv-btn--third bv-form__submit" value="Оповестить"
          onclick="event_send('Kupit_v_1klik2', 'Kupitv1klik2'); retail_one_click();">
      </form>

      <div class="pop__up-policy">
        <p>Нажатием кнопки «Оповестить» я даю свое согласие на обработку персональных данных</p>
      </div>

      <div class="close" data-name-popup="fastOrder" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>
  <? /* end. Новые поп апы сделать */ ?>














  <? /* корзина */ ?>
  <div class="pop__up" data-name-popup="toCart" aria-labelledby="modalProducts">
    <div class="pop__up-row">
      <div class="pop__up-top">
        <p>Всего товаров в корзине: <strong id="basket_items_num"></strong></p>
        <span>Итоговая сумма: <strong id="basket_items_summ"></strong></span>
      </div>

      <div id="basket_items"></div>

      <div class="pop__up-inf">
        <div class="pop__up-btns">
          <div class="pop__up-btn">
            <a href="/basket" class="bv-btn bv-btn--third"><span class="bv-btn__text">Оформить заказ</span></a>
          </div>

          <div class="pop__up-btn">
            <a href="#" class="bv-btn" data-id="close"><span class="bv-btn__text">Продолжить покупки</span></a>
          </div>
        </div>
      </div>


      <div class="close" data-name-popup="toCart" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>







  <? /* экспресс-доставка */ ?>
  <div class="pop__up" data-name-popup="expressDelivery" aria-labelledby="modalFeedback2">
    <div class="pop__up-row">
      <div class="pop__up-title">
        <p>Доставим по Минску <span>за 2 часа!</span></p>
      </div>

      <div class="pop__up-text">
        <p>Наш курьер уже готов ехать! Ему нужен только Ваш номер телефона!</p>
      </div>

      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form">

        <input type="hidden" id="product_express" value="" name="product_title" />
        <input type="hidden" id="product_express_id" name="pcmr_id" value="" />
        <input type="hidden" id="product_express_price" name="pcmr_price" value="" />
        <input type="hidden" name="form_type" value="form_express" />

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <input type="submit" class="bv-btn bv-btn--third bv-form__submit" value="Оформить заказ"
          onclick="event_send('Kupit_v_1klik2', 'Kupitv1klik2'); retail_one_click();">
      </form>

      <div class="pop__up-policy">
        <p>Нажатием кнопки «Оформить заказ» я даю свое согласие на обработку персональных данных</p>
      </div>

      <div class="close" data-name-popup="expressDelivery" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>










  <? /* Купить в 1 клик: */ ?>
  <div class="pop__up" data-name-popup="fastOrder" aria-labelledby="modalFeedback4">
    <div class="pop__up-row">
      <div class="pop__up-title">
        <p>Купить в 1 клик</p>
      </div>

      <div class="pop__up-text">
        <p>Оставьте свои контактные данные. <br> Наши менеджеры свяжутся с вами для уточнения деталей заказа.</p>
      </div>

      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" novalidate>

        <input type="hidden" id="product_one_click_title" value="" name="product_title" />
        <input type="hidden" id="product_one_click_id" name="pcmr_id" value="" />
        <input type="hidden" id="product_one_click_price" name="pcmr_price" value="" />
        <input type="hidden" name="form_type" value="form_oneclick" />

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <input type="submit" class="bv-btn bv-btn--third bv-form__submit" value="Оформить заказ"
          onclick="event_send('Kupit_v_1klik2', 'Kupitv1klik2'); retail_one_click();">
      </form>

      <div class="pop__up-policy">
        <p>Нажатием кнопки «Оформить заказ» я даю свое согласие на обработку персональных данных</p>
      </div>

      <div class="close" data-name-popup="fastOrder" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>










  <? /* Рассрочка: */ ?>
  <div class="pop__up" data-name-popup="paymentInParts" aria-labelledby="modalFeedback5">
    <div class="pop__up-row">
      <div class="pop__up-title">
        <p>Купить в рассрочку</p>
      </div>

      <div class="pop__up-subtitle">
        <p id="product_rassrochka_title"></p>
      </div>

      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" novalidate>
        <input type="hidden" id="product_rassrochka" name="product_title" value="" />
        <input type="hidden" id="product_rassrochka_id" value="" name="pcmr_id" />
        <input type="hidden" id="product_rassrochka_price" value="" name="pcmr_price" />
        <input type="hidden" name="form_type" value="form_rassrochka" />

        <div class="checkbox-list" id="checkbox-list">
          <div class="checkbox-list__item">
            <p>Ваш вариант рассрочки:</p>
          </div>

          <p id="rassrochka_variants"></p>
        </div>

        <div class="pop__up-text">
          <p>Хотите купить данный товар в рассрочку? Просто отправьте нам заявку - и в течение нескольких минут наши
            менеджеры перезвонят Вам для уточнения деталей!</p>
        </div>
        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <input onclick="event_send('kupit_rassrochka2', 'kupitRassrochka2');" type="submit"
          class="bv-btn bv-btn--third bv-form__submit" value="Оформить заказ">
      </form>


      <div class="pop__up-policy">
        <p>Нажатием кнопки «Оформить заказ» я даю свое согласие на обработку персональных данных</p>
      </div>

      <div class="close" data-name-popup="fastOrder" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>










  <? /* Нашли дешевле: */ ?>
  <div class="pop__up pop__up--second" data-name-popup="cheepOrder" aria-labelledby="modalFeedback3">
    <div class="pop__up-row">
      <div class="pop__up-title">
        <p>Нашли этот товар дешевле?</p>
      </div>

      <div class="pop__up-text">
        <p>Акция “Нашли дешевле” действует при условии, что найденное Вами предложение в другом магазине является
          актуальным и аналогичным нашему, с единственным различием - цена!</p>
        <p>Комплектация, гарантия, цвет, наличие инструмента и условия доставки должны быть одинаковыми.</p>
        <p>Также обращаем Ваше внимание на то, что предложенная Вами стоимость будет рассматриваться и оговариваться
          индивидуально в зависимости от выбранного товара.</p>
        <p>Приятных покупок!</p>
      </div>

      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" novalidate>

        <input type="hidden" id="product_cheap" value="" name="product_title" />
        <input type="hidden" id="product_cheap_id" value="" name="pcmr_id" />
        <input type="hidden" name="form_type" value="form_cheap" />

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="email" class="bv-form__input" placeholder="Email" name="user_email" required>
          </label>

          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ссылка на товар в другом магазине" name="user_url"
              required>
          </label>
        </div>

        <div class="bv-form__items">
          <label class="bv-form__item">
            <textarea name="user_comment" placeholder="Ваш комментарий" class="bv-form__input"></textarea>
          </label>
        </div>

        <input onclick="fbq('track','Lead'); event_send('Nashli_deshevle2', 'NashliDeshevke2');" type="submit"
          class="bv-btn bv-btn--third bv-form__submit" value="Отправить заявку">
      </form>

      <div class="close" data-name-popup="cheepOrder" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>










  <? /* Обратный звонок: */ ?>
  <div class="pop__up pop__up--second" data-name-popup="call" aria-labelledby="modalFeedback3">
    <div class="pop__up-row">
      <div class="pop__up-title">
        <p>Обратный звонок</p>
      </div>
      <form action="/exe/send_form.php" method="post" class="pop__up-form bv-form" novalidate>

        <input type="hidden" name="form_type" value="form_callback" />

        <div class="bv-form__items">
          <label class="bv-form__item">
            <input type="text" class="bv-form__input" placeholder="Ваше имя" name="user_name" required>
          </label>

          <label class="bv-form__item">
            <input type="tel" class="bv-form__input" placeholder="Телефон" name="user_phone" required>
          </label>
        </div>

        <input type="submit" class="bv-btn bv-btn--third bv-form__submit" value="Заказать обратный звонок" onclick="fbq('track','Lead');">
      </form>

      <div class="close" data-name-popup="call" aria-label="Close">
        <span class="close__part" aria-hidden="true"></span>
        <span class="close__part" aria-hidden="true"></span>
      </div>
    </div>
  </div>
  
</div>






<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="pswp__bg"></div>
  <div class="pswp__scroll-wrap">
    <div class="pswp__container">
      <div class="pswp__item"></div>
      <div class="pswp__item"></div>
      <div class="pswp__item"></div>
    </div>
    <div class="pswp__ui pswp__ui--hidden">
      <div class="pswp__top-bar">
        <div class="pswp__counter"></div>
        <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
        <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
        <div class="pswp__preloader">
          <div class="pswp__preloader__icn">
            <div class="pswp__preloader__cut">
              <div class="pswp__preloader__donut"></div>
            </div>
          </div>
        </div>
      </div>
      <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
        <div class="pswp__share-tooltip"></div>
      </div>
      <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
      </button>
      <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
      </button>
      <div class="pswp__caption">
        <div class="pswp__caption__center"></div>
      </div>
    </div>
  </div>
</div>


<? // End New popups ?>

