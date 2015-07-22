<?

$this->Combinator->add_libs('css', $this->Less->css('zamowienia', array('plugin' => 'ZamowieniaPubliczne')));
$this->Combinator->add_libs('js', '../plugins/highcharts/js/highcharts');
$this->Combinator->add_libs('js', '../plugins/highcharts/locals');
$this->Combinator->add_libs('js', 'Dane.DataBrowser.js');

$options = array(
    'mode' => 'init',
);
?>
<div class="col-md-9">
    <div class="databrowser-panels">

        <? if ($object->getId() == 903) { ?>


            <div class="databrowser-panel">
                <h2>Najnowsze posiedzenia rady dzielnicy:</h2>

                <div class="aggs-init">

                    <div class="dataAggs">
                        <div class="agg agg-Dataobjects">
                            <? if ($dataBrowser['aggs']['all']['posiedzenia']['top']['hits']['hits']) { ?>
                                <ul class="dataobjects">
                                    <? foreach ($dataBrowser['aggs']['all']['posiedzenia']['top']['hits']['hits'] as $doc) { ?>
                                        <li>
                                            <?
                                            echo $this->Dataobject->render($doc, 'default');
                                            ?>
                                        </li>
                                    <? } ?>
                                </ul>
                                <div class="buttons">
                                    <a href="<?= $dzielnica->getUrl() ?>/posiedzenia" class="btn btn-primary btn-sm">Zobacz
                                        więcej</a>
                                </div>
                            <? } ?>

                        </div>
                    </div>


                </div>
            </div>

            <div class="databrowser-panel">
                <h2>Radni dzielnicy:</h2>

                <div class="aggs-init">

                    <div class="dataAggs">
                        <div class="agg agg-Dataobjects">
                            <? if ($dataBrowser['aggs']['all']['radni']['top']['hits']['hits']) { ?>
                                <ul class="dataobjects row">
                                    <? foreach ($dataBrowser['aggs']['all']['radni']['top']['hits']['hits'] as $doc) { ?>
                                        <li class="col-md-6">
                                            <?
                                            echo $this->Dataobject->render($doc, 'default');
                                            ?>
                                        </li>
                                    <? } ?>
                                </ul>
                            <? } ?>

                        </div>
                    </div>


                </div>
            </div>



        <? } ?>

    </div>

</div><div class="col-md-3">
	
	<?
    $this->Combinator->add_libs('css', $this->Less->css('banners-box', array('plugin' => 'Dane')));
	$this->Combinator->add_libs('css', $this->Less->css('pisma-button', array('plugin' => 'Pisma')));
    $this->Combinator->add_libs('js', 'Pisma.pisma-button');
    echo $this->element('tools/pismo', array(
	    'label' => '<strong>Wyślij pismo</strong> do Rady Dzielnicy',
	    'adresat' => 'dzielnice:' . $dzielnica->getId(),
    ));
    ?>
	
</div>