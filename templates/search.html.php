<main>
    <div class="container">
        <section class="lots">
            <?php if($lots):?>
                <h3>Результаты поиска по запросу «<span><?=e($_GET['search'])?></span>»</h3>
            <?php else:?>
                <h3>По запросу «<span><?=e($_GET['search'])?></span>» ничего не найдено!</h3>
                <p>Попробуйте ввести другой запрос или <a href = "index.php">перейдите в каталог</a>!</p>
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
                    <span class="lot__cost"><?=e(price_format($lot['price']))?></span>
                    </div>
                    <?php list($hours,$min) = diff_time($lot['date_completion'])?>
                    <div class="lot__timer timer <?=($hours<1) ? "timer--finishing" : "" ?>"><?=e($hours.":".$min)?>
                </div>
                </div>
            </li>
            <?php endforeach;?>
            </ul>
        </section>
        <?php if($lots):?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a <?=($_GET['page'] - 1 >= 1) ?'href="search.php?page='.($_GET['page']-1).'"':null?>>Назад</a></li>
            <?php for($i = 0; $i < $count_page;$i++):?>
            <li class="pagination-item <?=($_GET['page'] - 1 == $i) ? 'pagination-item-active':null?>">
                <a <?='href="search.php?page='.($i+1).'"'?>><?=e($i + 1)?></a></li>
            <?php endfor;?>
            <li class="pagination-item pagination-item-next">
                <a <?=($_GET['page'] + 1 <= $count_page) ?'href="search.php?page='.($_GET['page']+1).'"':null?>>Вперед</a></li>
        </ul>
        <?php endif;?>
    </div>
</main>