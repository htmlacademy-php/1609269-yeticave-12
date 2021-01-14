<main>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach($bids as $bid):?>
        <tr class="rates__item <?=(!$bid['lot_status']) ? "rates__item--end" :""?>">       
          <!-- rates__item--end , rates__item--win-->
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=e($bid['img_link'])?>" width="54" height="40" alt="Сноуборд">
            </div>
            <div>
                <h3 class="rates__title"><a href="lot.php?id=<?=e($bid['lot_id'])?>"><?=e($bid['name'])?></a></h3>
                <p><?=e(($bid['сontact']) ?? "")?></p>
            </div>
          </td>
          <td class="rates__category"><?=e($categorys[$bid['category_id']]['category'])?></td>

          <td class="rates__timer">
                <?php list($hours,$min) = diff_time($bid['date_completion']);
                      $s = $bid['lot_status'];
                if($_SESSION['user']['id'] != $bid['winner_id']):?>
                <div class="timer <?=($hours<1 && $s) ? "timer--finishing" : ((!$s) ? "timer--end" :"") ?>">
                      <?=($s) ? e($hours.":".$min):"Торги окончены"?></div>
                <?php else:?>
                <div class="timer <?=($hours<1 && $s) ? "timer--finishing" : ((!$s) ? "timer--win" :"") ?>">
                      <?=($s) ? e($hours.":".$min) :"Ставка выиграла"?></div>
                <?php endif;?>
            </td>

          <td class="rates__price " style="color : <?=($bid['price']==$bid['max_price'])?"green":"red"?>;"><?=e(price_format($bid['price']))?></td>
          <td class="rates__time"><?=e($bid['date_create'])?></td>
        </tr>
        <?php endforeach;?>
      </table>
    </section>
  </main>