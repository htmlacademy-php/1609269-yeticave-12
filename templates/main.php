<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">

        <!-- Перебор простого массива -->
        <?php foreach($categorys as $category):?>

            <li class="promo__item promo__item--<?=e($category['code'])?>">
                <a class="promo__link" href="all-lots.php?id=<?=e($category['id'])?>"><?=e($category['category'])?></a>
            </li>
            <?php endforeach;?>
        </ul>

    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
        <?php foreach($lots as $lot):?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=e($lot['img_link'])?>" width="350" height="260" alt="">
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
                        <?php $finishing = ($hours<1) ? "timer--finishing" : "" ?>
                        <div class="lot__timer timer <?= $finishing ?>">
                           <?=e($hours.":".$min)?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach;?>
        </ul>
    </section>
</main>

