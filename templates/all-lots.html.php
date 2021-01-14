<main>
    <div class="container">
      <section class="lots">
        <h2>Все лоты в категории <span><?=e($categorys[$_GET['id']]['category'])?></span></h2>
        <ul class="lots__list">
        <?php if($lots):?>
        <?php foreach($lots as $lot):?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?=e($lot['img_link'])?>" width="350" height="260" alt="Сноуборд">
            </div>
            <div class="lot__info">
              <span class="lot__category"><?=e($categorys[$lot['category_id']]['category'])?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=e($lot['id'])?>"><?=e($lot['name'])?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount">Стартовая цена</span>
                  <span class="lot__cost"><?=e(price_format($lot['price']))?></span>
                </div>
                <?php list($hours,$min) = diff_time($lot['date_completion'])?>
                <div class="lot__timer timer <?=e(($hours<1)? "timer--finishing":"")?>">
                <?=e($hours.":".$min)?>
                </div>
              </div>
            </div>
          </li>
        <?php endforeach;?>
        <?php else:?>
            <p>Данная категория пуста!</p>
        <?php endif;?>
      </section>
      <?php if($lots and $count_page > 1):?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
            <?=($page != 1) ? '<a href="all-lots.php?'.e(http_creator($page-1,$limit,'id',$_GET['id'])).'">Назад</a></li>':""?>
                <?php for($i = 1; $i <= $count_page;$i++):?>
                <li class="pagination-item <?=e(($page == $i) ? 'pagination-item-active':"")?>">
                    <a <?='href="all-lots.php?'.e(http_creator($i,$limit,'id',$_GET['id'])).'"'?>><?=e($i)?></a></li>
                <?php endfor;?>
            <li class="pagination-item pagination-item-next">
            <?=($page >= $count_page) ? "":'<a href="all-lots.php?'.e(http_creator($page+1,$limit,'id',$_GET['id'])).'">Вперед</a></li>'?>
        </ul>
      <?php endif;?>
    </div>
  </main>
