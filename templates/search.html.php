<main>
    <div class="container">
        <section class="lots">
            <?php if($lots):?>
                <h2>Результаты поиска по запросу «<span><?=e($_GET['search'])?></span>»</h2>
            <?php else:?>
                <h2>По запросу «<span><?=e($_GET['search'])?></span>» ничего не найдено!</h2>
            <?php endif;?>
            <ul class="lots__list">
            <?php foreach($lots as $lot):?>
            <li class="lots__item lot">
                <div class="lot__image">
                <img src="<?=e($lot['img_link'])?>" width="350" height="260" alt="Сноуборд">
                </div>
                <div class="lot__info">
                <span class="lot__category"><?=e($lot['category'])?></span>
                <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=e($lot['id'])?>"><?=e($lot['name'])?></a></h3>
                <div class="lot__state">
                    <div class="lot__rate">
                    <span class="lot__amount">Стартовая цена</span>
                    <span class="lot__cost"><?=e(price_format($lot['start_price']))?></span>
                    </div>
                    <?php list($hours,$min) = diff_time($lot['date_completion'])?>
                    <div class="lot__timer timer <?=($hours<1) ? "timer--finishing" : "" ?>"><?=e($hours.":".$min)?>
                </div>
                </div>
            </li>
            <?php endforeach;?>
            </ul>
        </section>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
    </div>
</main>