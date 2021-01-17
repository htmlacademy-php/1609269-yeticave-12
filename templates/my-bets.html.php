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
                <p><?=e(($bid['contact']) ?? "")?></p>
            </div>
          </td>
          <td class="rates__category"><?=e($categorys[$bid['category_id']]['category'])?></td>

          <td class="rates__timer">
                <?php list($hours,$min) = diff_time($bid['date_completion']);
                      $s = $bid['lot_status'];
                      $p = $bid['price'];
                      $m_p_user = $bid['max_price_1_user'];
                if(!$bid['winner_id']):?>
                <div class="timer <?=($hours<1) ? "timer--finishing" : ""?>">
                      <?=e($hours.":".$min)?></div>
                <?php elseif($_SESSION['user']['id'] == $bid['winner_id']):?>
                <div class="timer timer--win">
                      <?=($s) ? e($hours.":".$min) :(($p == $m_p_user) ?"Ставка выиграла":"Вы изменили ставку")?></div>
                <?php else:?>
                <div class="timer timer--end">
                <?=($s) ? e($hours.":".$min) :(($p == $m_p_user) ?"Ставка проиграла":"Вы изменили ставку")?></div>
                <?php endif;?>
            </td>

          <td class="rates__price " style="color : <?=($bid['price']==$bid['max_price'])?"green":"red"?>;"><?=e(price_format($bid['price']))?></td>
          <td class="rates__time"><?=e($bid['date_create'])?></td>
        </tr>
        <?php endforeach;?>
      </table>
      <?php if($bids and $count_page > 1):?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
            <?=($page != 1) ? '<a href="my-bets.php?'.e(http_creator($page-1,$limit)).'">Назад</a></li>':""?>
                <?php for($i = 1; $i <= $count_page;$i++):?>
                <li class="pagination-item <?=e(($page == $i) ? 'pagination-item-active':"")?>">
                    <a <?='href="my-bets.php?'.e(http_creator($i,$limit)).'"'?>><?=e($i)?></a></li>
                <?php endfor;?>
            <li class="pagination-item pagination-item-next">
            <?=($page >= $count_page) ? "":'<a href="my-bets.php?'.e(http_creator($page+1,$limit)).'">Вперед</a></li>'?>
        </ul>
      <?php elseif(!$bids):?>
        <p>У вас пока нет ставок!</p>
      <?php endif;?>
    </section>
  </main>