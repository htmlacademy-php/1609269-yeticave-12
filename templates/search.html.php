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
                <span class="lot__category"><?=e($categorys[$lot['category_id']]['category'])?></span>
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
        <?php if($lots and $count_page > 1):?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
            <?=($page != 1) ? '<a href="search.php?'.e(http_creator($page-1,$limit,'search',$_GET['search'])).'">Назад</a></li>':""?>
                <?php for($i = 1; $i <= $count_page;$i++):?>
                <li class="pagination-item <?=e(($page == $i) ? 'pagination-item-active':"")?>">
                    <a <?='href="search.php?'.e(http_creator($i,$limit,'search',$_GET['search'])).'"'?>><?=e($i)?></a></li>
                <?php endfor;?>
            <li class="pagination-item pagination-item-next">
            <?=($page >= $count_page) ? "":'<a href="search.php?'.e(http_creator($page+1,$limit,'search',$_GET['search'])).'">Вперед</a></li>'?>
        </ul>
        <?php endif;?>
    </div>
</main>