    <section class="lot-item container">
      <h2><?=e($lot['name'])?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=e($lot['img_link'])?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?=e($lot['category'])?></span></p>
          <p class="lot-item__description"><?=e($lot['description'])?></p>
        </div>
        <div class="lot-item__right">
        <?php list($hours,$min) = diff_time($lot['date_completion'])?>
        <?php $finishing = ($hours<1) ? "timer--finishing" : "" ?>
        <?php if($is_auth and $lot['lot_status']):?>
          <div class="lot-item__state">
              <div class="lot-item__timer timer <?=$finishing?>">
                <?= e($hours.":".$min)?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=e(price_format($lot["price"]))?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=e(price_format($lot["min_bid"]))?></span>
              </div>
            </div>
            <form class="lot-item__form" action="<?=$_SESSION['link']?>" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item <?=e(($error) ? "form__item--invalid":"")?>">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" value='<?=e($_POST['cost'] ?? e($lot["min_bid"]))?>'>
                <span class="form__error"> <?=e(($error) ?? "")?></span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>
        <?php endif;?>
        <?php if(count($bids)):?>
          <div class="history">
            <h3>Последние <span><?=e(count($bids))?></span> ставок(ка)</h3>
          <table class="history__list">
          <?php foreach($bids as $bid):?>
              <tr class="history__item" <?=($_SESSION['user']['name'] == $bid['name']) ? 'style ="background-color:#FFFFE0"':""?>>
                <td class="history__name"><?=e($bid['name'])?></td>
                <td class="history__price"><?=e($bid['price'])?></td>
                <td class="history__time"><?=e($bid['date_create'])?></td>
              </tr>
          <?php endforeach?>
            </table>
          </div>
        <?php endif?>
        </div>
      </div>
    </section>
  </main>
</div>
