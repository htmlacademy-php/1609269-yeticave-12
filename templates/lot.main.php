<main>
    <nav class="nav">
      <ul class="nav__list container">
        <li class="nav__item">
          <a href="all-lots.html">Доски и лыжи</a>
        </li>
        <li class="nav__item">
          <a href="all-lots.html">Крепления</a>
        </li>
        <li class="nav__item">
          <a href="all-lots.html">Ботинки</a>
        </li>
        <li class="nav__item">
          <a href="all-lots.html">Одежда</a>
        </li>
        <li class="nav__item">
          <a href="all-lots.html">Инструменты</a>
        </li>
        <li class="nav__item">
          <a href="all-lots.html">Разное</a>
        </li>
      </ul>
    </nav>

<?php foreach($products as $product):?>

    <section class="lot-item container">
      <h2><?=$product['name'];?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$product['img_link']?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?=$product['category']?></span></p>
          <p class="lot-item__description"><?=$product['description']?></p>
        </div>
        <div class="lot-item__right">
        <?php list($hours,$min) = diff_time($product['date_completion'])?>
        <?php $finishing = ($hours<1) ? "timer--finishing" : "" ?>
          <div class="lot-item__state">
              <div class="lot-item__timer timer <?=$finishing?>">
                <?= $hours.":".$min?>
            </div>
            <div class="lot-item__cost-state">
              <div class="lot-item__rate">
                <span class="lot-item__amount">Текущая цена</span>
                <span class="lot-item__cost"><?=price_format($product["price"])?></span>
              </div>
              <div class="lot-item__min-cost">
                Мин. ставка <span><?=price_format($product["min_bid"])?></span>
              </div>
            </div>
            <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post" autocomplete="off">
              <p class="lot-item__form-item form__item form__item--invalid">
                <label for="cost">Ваша ставка</label>
                <input id="cost" type="text" name="cost" placeholder="<?=$product["min_bid"]?>">
                <span class="form__error">Введите наименование лота</span>
              </p>
              <button type="submit" class="button">Сделать ставку</button>
            </form>
          </div>

<?php endforeach;?>

<?php if(count($bids) != 0):?>
          <div class="history">
            <h3>История ставок (<span><?=count($bids)?></span>)</h3>
          <table class="history__list">
<?php foreach($bids as $bid):?>
              <tr class="history__item">
                <td class="history__name"><?=$bid['name']?></td>
                <td class="history__price"><?=$bid['price']?></td>
                <td class="history__time"><?=$bid['date_create']?></td>
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

<footer class="main-footer">
  <nav class="nav">
    <ul class="nav__list container">
      <li class="nav__item">
        <a href="all-lots.html">Доски и лыжи</a>
      </li>
      <li class="nav__item">
        <a href="all-lots.html">Крепления</a>
      </li>
      <li class="nav__item">
        <a href="all-lots.html">Ботинки</a>
      </li>
      <li class="nav__item">
        <a href="all-lots.html">Одежда</a>
      </li>
      <li class="nav__item">
        <a href="all-lots.html">Инструменты</a>
      </li>
      <li class="nav__item">
        <a href="all-lots.html">Разное</a>
      </li>
    </ul>
  </nav>
