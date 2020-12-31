<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">

        <!-- Перебор простого массива -->
        <?php foreach($categorys as $category):?>

            <li class="promo__item promo__<?= $tag_category['code']?>">
                <a class="promo__link" href="pages/all-lots.html"><?=e($category['category'])?></a>
            </li>
            <?php endforeach;?>
        </ul>

    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">

        <!-- Перебор двойного массива-->

        <?php foreach($products as $product => $tag_products):?>

            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?=e($tag_products['img_link'])?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?=e($tag_products['category'])?></span>
                    <h3 class="lot__title"><a class="text-link" href="lot.php?<?='id='.e($tag_products['id'])?>"><?=e($tag_products['name'])?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount">Стартовая цена</span>
                            <span class="lot__cost"><?=e(price_format($tag_products['price']))?></span>
                        </div>
                        <?php list($hours,$min) = diff_time($tag_products['date_completion'])?>
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

