<main>
    <section class="rates container">
      <h2>Мои ставки</h2>
      <table class="rates__list">
        <?php foreach($bids as $bid):
        $w = $bid['winner_id'];
        $p = $bid['price'];
        $m_p = $bid['max_price'];
        $m_p_s = $bid['max_price_same_user']?>
        <tr class="rates__item <?=(!$w) ?"":(($_SESSION['user']['id'] == $w && $p == $m_p_s)?"rates__item--win":"rates__item--end")?>">       
          <td class="rates__info">
            <div class="rates__img">
              <img src="<?=e($bid['img_link'])?>" width="54" height="40" alt="Сноуборд">
            </div>
            <div>
                <h3 class="rates__title"><a href="lot.php?id=<?=e($bid['lot_id'])?>"><?=e($bid['name'])?></a></h3>
                <p><?=($w && $_SESSION['user']['id'] == $w)? e($bid['contact']):""?></p>
            </div>
          </td>
          <td class="rates__category"><?=e($categorys[$bid['category_id']]['category'])?></td>

          <td class="rates__timer">
                <?php list($hours,$min) = diff_time($bid['date_completion']);
                if(!$w):?>
                <div class="timer <?=($hours<1) ? "timer--finishing" : (($p == $m_p_s) ? "": "timer timer--end")?>">
                      <?=($p == $m_p_s) ? e($hours.":".$min):"Вы изменили ставку"?></div>
                <?php elseif($_SESSION['user']['id'] == $w):?>
                <div class="timer timer--win">
                  <?=(!$w) ? e($hours.":".$min) :(($p == $m_p_s) ?"Ставка выиграла":"Вы изменили ставку")?></div>
                <?php else:?>
                <div class="timer timer--end">
                  <?=(!$w) ? e($hours.":".$min) :(($p == $m_p_s) ?"Ставка проиграла":"Вы изменили ставку")?></div>
                <?php endif;?>
            </td>

          <td class="rates__price " style="color : <?=($p == $m_p)?"green":"red"?>;"><?=e(price_format($bid['price']))?></td>
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