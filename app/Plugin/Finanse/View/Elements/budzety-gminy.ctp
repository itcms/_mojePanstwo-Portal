<?
$this->Combinator->add_libs('css', $this->Less->css('zamowienia', array('plugin' => 'ZamowieniaPubliczne')));
$this->Combinator->add_libs('js', '../plugins/highstock/js/highstock');
$this->Combinator->add_libs('js', '../plugins/highstock/locals');
$this->Combinator->add_libs('js', 'Dane.DataBrowser.js');

$this->Combinator->add_libs('css', 'icon-dzialy');
$this->Combinator->add_libs('css', $this->Less->css('sections', array('plugin' => 'FinanseGmin')));
$this->Combinator->add_libs('css', $this->Less->css('mp-sections', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-gminy-krakow', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('view-gminy-krakow-finanse', array('plugin' => 'Dane')));
$this->Combinator->add_libs('js', 'Dane.view-gminy-krakow-finanse');
$this->Combinator->add_libs('js', 'Finanse.budzety-gminy');

$options = array(
    //'mode' => 'init',
    'mode' => $mode,
);

?>
<div class="col-sm-12">
    <div class="dataWrap">
        <div class="appBanner">
            <h1 class="appTitle">Budżet gmin</h1>
        </div>
    </div>

    <? if (isset($filter_options)) { ?>
        <div>
            <div class="appSwitchers text-center">
                <form id="dataForm" method="get" class="col-sm-12">

                    <div class="row">

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="dataSelect">Dane: </label>
                                <select id="dataSelect" class="form-control" name="data">
                                    <? foreach ($filter_options['data']['items'] as $i => $item) { ?>
                                        <option
                                            value="<?= $item['id'] ?>"<? if ($filter_options['data']['selected_id'] == $item['id']) echo ' selected'; ?>>
                                            <?= $item['label'] ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="rangeSelect">Analizowany okres: </label>
                                <select id="rangeSelect" class="form-control" name="timerange">
                                    <? foreach ($filter_options['timerange']['items'] as $i => $item) { ?>
                                        <option
                                            value="<?= $item['id'] ?>"<? if ($filter_options['timerange']['selected_id'] == $item['id']) echo ' selected'; ?>>
                                            <?= $item['label'] ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="modeSelect">Porównywane gminy: </label>
                                <select id="modeSelect" class="form-control" name="compare">
                                    <? foreach ($filter_options['compare']['items'] as $i => $item) { ?>
                                        <option
                                            value="<?= $item['id'] ?>"<? if ($filter_options['compare']['selected_id'] == $item['id']) echo ' selected'; ?>>
                                            <?= $item['label'] ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    <? } ?>


    <div id="mp-sections">
        <div class="content">
            <? if (isset($main_chart)) { ?>
                <div id="mainChart" class="">
                    <div class="histogram_cont" style="height: 222px">
                        <div class="histogram" data-mode="<?= $mode; ?>" data-median="<?= $global['median'] ?>"
                             data-title="<?= $main_chart['title'] ?>" data-subtitle="<?= $main_chart['subtitle'] ?>"
                             data-interval="<?= $histogram_interval; ?>"
                             data-histogram='<?= json_encode($global['histogram']) ?>'>
                        </div>
                    </div>
                    <div class="gradient_cont" style="top: -35px;">
                        <span class="gradient"></span>
                        <ul class="addons">
                            <li class="min">
                                <a href="/dane/gminy/<?= $global['min']['id'] ?>">
                                    <span class="n"><?= $global['min']['label'] ?></span>
                                    <span class="v"><?= number_format_h($global['min']['value']) ?></span>
                                </a>
                            </li>
                            <li class="_median" style="left: <?= round($global['median_left']) ?>%">
                                Mediana<br/><?= number_format_h($global['median']) ?>
                            </li>
                            <li class="max">
                                <a href="/dane/gminy/<?= $global['max']['id'] ?>">
                                    <span class="n"><?= $global['max']['label'] ?></span>
                                    <span class="v"><?= @number_format_h($global['max']['value']) ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            <? } ?>
            <div class="row text-center margin-top-20">
                <div class="col-sm-8 col-sm-offset-2">
                    <p>Podaj nazwę swojej gminy, aby sprawdzić jej finanse:</p>


                    <div class="suggesterBlockGminy">
                        <div class="searcher form-group has-feedback">
                            <div class="col-md-12">
                                <div class="input-group">
                                    <input class="form-control hasclear input-lg ui-autocomplete-input" placeholder="Zacznij pisać aby znaleźć gmine..." name="q" value="" data-dataset="gminy" data-url="" data-autocompletion="true" autocomplete="off" required="" type="text">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary input-lg" type="submit">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

            <div class="row items">
                <? foreach ($dzialy as $dzial) { if(!isset($dzial['global'])) continue; ?>
                    <div class="block col-xs-12 col-sm-6 col-md-3">
                        <div class="item <?= $dzial['global']['class'] ?>" data-id="<?= $dzial['id'] ?>">

                            <a href="#<?= $dzial['id'] ?>" class="inner" data-title="<?= $dzial['label'] ?>">

                                <div class="logo">
                                    <i class="icon-dzialy-<?= $dzial['id'] ?>"></i>
                                </div>

                                <div class="details"><span class="detail">
                                        <?= number_format_h($dzial['global']['cur']) ?></span>
                                </div>

                                <div class="title">
                                    <div class="nazwa"><?= $this->Text->truncate($dzial['label'], 50) ?></div>
                                </div>

                                <div class="subtitle" style="display: none;">
                                    <h3>Szczegółowe wydatki gminy w tym dziale:</h3>
                                </div>


                                <div class="chart" style="display: none;">
                                    <div class="histogram_cont">
                                        <div class="histogram" data-mode="<?= $mode; ?>"
                                             data-median="<?= $dzial['global']['median'] ?>"
                                             data-title="<?= $dzial['label'] ?>"
                                             data-subtitle="<?= $main_chart['subtitle'] ?>"
                                             data-interval="<?= $dzial['global']['interval'] ?>"
                                             data-histogram='<?= json_encode($dzial['global']['histogram']) ?>'>
                                        </div>
                                    </div>
                                    <div class="gradient_cont">
                                        <span class="gradient"></span>
                                        <ul class="addons">
                                            <li class="min">
                                                <div class="link"
                                                     data-href="/dane/gminy/<?= $dzial['global']['min']['id'] ?>">
                                                    <span class="n"><?= $dzial['global']['min']['label'] ?></span>
                                                    <span
                                                        class="v"><?= number_format_h($dzial['global']['min']['value']) ?></span>
                                                </div>
                                            </li>
                                            <li class="_median"
                                                style="left: <?= round($dzial['global']['median_left']) ?>%">
                                                Mediana<br/><?= number_format_h($dzial['global']['median']) ?></li>
                                            <li class="max">
                                                <div class="link"
                                                     data-href="/dane/gminy/<?= $dzial['global']['min']['id'] ?>">
                                                    <span class="n"><?= $dzial['global']['max']['label'] ?></span>
                                                    <span
                                                        class="v"><?= number_format_h($dzial['global']['max']['value']) ?></span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>

