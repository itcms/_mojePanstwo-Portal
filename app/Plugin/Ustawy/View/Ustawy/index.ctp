<?php $this->Combinator->add_libs('css', $this->Less->css('ustawy', array('plugin' => 'Ustawy'))) ?>
<?php $this->Combinator->add_libs('js', 'ustawy.ustawy.js') ?>

<div class="appHeader">
    <div class="container innerContent">
        <h1><?php echo __d('ustawy', 'LC_USTAWY_HEADLINE'); ?></h1>

        <div class="col-xs-12 col-sm-8 col-sm-offset-2">
            <form class="searchInput" action="/ustawy">
                <div class="input-group main_input">
                    <input name="q" value="" type="text"
                           placeholder="<?php echo __d('ustawy', 'LC_USTAWY_SZUKAJ_ORGANIZACJI'); ?>"
                           class="form-control input-lg">
	                <span class="input-group-btn">
	                      <input type="submit" class="btn btn-success input-lg"
                                 value="<?= __d('ustawy', 'LC_USTAWY_SZUKAJ') ?>"/>
	                </span>
                </div>
            </form>
            <div id="shortcuts">
                <ul>
                    <li data-target="#ustawyCarousel" class="results hidden"
                        data-slide-to="0">
                        <a href="#search"><?php echo __d('ustawy', 'LC_USTAWY_WIECEJ_WYNIKI_WYSZUKIWANIA'); ?></a>
                    </li>
                    <li data-target="#ustawyCarousel" class="active" data-slide-to="1">
                        <a href="dane/ustawy"
                           target="_self"><?php echo __d('ustawy', 'LC_USTAWY_WIECEJ_NAJNOWSZE'); ?></a>
                    </li>
                    <li data-target="#ustawyCarousel" data-slide-to="2">
                        <a href="dane/ustawy?typ_id[]=3"
                           target="_self"><?php echo __d('ustawy', 'LC_USTAWY_WIECEJ_KODEKSY'); ?></a>
                    </li>
                    <li data-target="#ustawyCarousel" data-slide-to="3">
                        <a href="dane/ustawy?typ_id[]=2"
                           target="_self"><?php echo __d('ustawy', 'LC_USTAWY_WIECEJ_KONSTYTUCJE'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div id="ustawyCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item results">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <h2><?= __d('ustawy', 'LC_USTAWY_WYNIKI_WYSZUKIWANIA') ?>:</h2>
                    <ul>

                    </ul>
                </div>
            </div>
            <div class="item active">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <h2><?= __d('ustawy', 'LC_USTAWY_NIEDAWNO_WESZLY_W_ZYCIE') ?>:</h2>

                        <ul>
                            <? foreach ($data['niedawno_weszly'] as $obj) {
                                $obj = $obj->getData();?>
                                <li>
                                    <a href="/dane/ustawy/<?= $obj['id'] ?>"
                                       target="_self"
                                       title="<?= $obj['tytul'] ?>"><?= __d('ustawy', 'LC_USTAWY_TITLE_USTAWA') ?> <?= $obj['tytul_skrocony'] ?>
                                        <small><?= __d('ustawy', 'LC_USTAWY_PUBLIKACJA') ?>
                                            : <?= $obj['data_publikacji'] ?></small>
                                    </a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <h2><?= __d('ustawy', 'LC_USTAWY_NIEDLUGO_WEJDA_W_ZYCIE') ?>:</h2>

                        <ul>
                            <?
                            foreach ($data['niedlugo_wejda'] as $obj) {
                                $obj = $obj->getData();?>
                                <li>
                                    <a href="/dane/ustawy/<?= $obj['id'] ?>"
                                       target="_self"
                                       title="<?= $obj['tytul'] ?>"><?= __d('ustawy', 'LC_USTAWY_TITLE_USTAWA') ?> <?= $obj['tytul_skrocony'] ?>
                                        <small><?= __d('ustawy', 'LC_USTAWY_PUBLIKACJA') ?>
                                            : <?= $obj['data_publikacji'] ?></small>
                                    </a>
                                </li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="item">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <ul class="single">
                        <?
                        foreach ($data['kodeksy'] as $obj) {
                            $obj = $obj->getData();?>
                            <li>
                                <a href="/dane/ustawy/<?= $obj['id'] ?>"
                                   target="_self" title="<?= $obj['tytul'] ?>"><?= $obj['tytul_skrocony'] ?>
                                    <small><?= __d('ustawy', 'LC_USTAWY_PUBLIKACJA') ?>
                                        : <?= $obj['data_publikacji'] ?></small>
                                </a>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            </div>
            <div class="item">
                <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                    <ul class="single">
                        <?
                        foreach ($data['konstytucje'] as $obj) {
                            $obj = $obj->getData();?>
                            <li>
                                <a href="/dane/ustawy/<?= $obj['id'] ?>"
                                   target="_self" title="<?= $obj['tytul'] ?>"><?= $obj['tytul_skrocony'] ?>
                                    <small><?= __d('ustawy', 'LC_USTAWY_PUBLIKACJA') ?>
                                        : <?= $obj['data_publikacji'] ?></small>
                                </a>
                            </li>
                        <? } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>