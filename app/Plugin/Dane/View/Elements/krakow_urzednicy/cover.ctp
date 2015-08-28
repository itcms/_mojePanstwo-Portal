<?
$this->Combinator->add_libs('js', '../plugins/highstock/js/highstock');
$this->Combinator->add_libs('js', '../plugins/highstock/locals');
$this->Combinator->add_libs('js', 'Dane.DataBrowser.js');

$options = array(
    'mode' => 'init',
);
?>
<div class="col-md-8">

    <div class="databrowser-panels">

        <? if (@$dataBrowser['aggs']['all']['oswiadczenia']['top']['hits']['hits']) { ?>
            <div class="databrowser-panel">
                <h2>Oświadczenia majątkowe:</h2>

                <div class="aggs-init">

                    <div class="dataAggs">
                        <div class="agg agg-Dataobjects">
                            <? if ($dataBrowser['aggs']['all']['oswiadczenia']['top']['hits']['hits']) { ?>
                                <ul class="dataobjects">
                                    <? foreach ($dataBrowser['aggs']['all']['oswiadczenia']['top']['hits']['hits'] as $doc) { ?>
                                        <li>
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

        <? if (isset($osoba) && ($organizacje = $osoba->getLayer('organizacje'))) { ?>
            <div class="databrowser-panel">
                <h2>Powiązania w KRS:</h2>

                <div class="aggs-init">

                    <div class="dataAggs">
                        <div class="agg agg-Dataobjects">
                            <ul class="dataobjects">
                                <? foreach ($organizacje as $organizacja) {
                                    $kapitalZakladowy = (float)$organizacja['kapital_zakladowy'];
                                    ?>
                                    <li>
                                        <?

                                        $organizacja['firma'] = $organizacja['nazwa'];
                                        $role = $organizacja['role'];
                                        unset($organizacja['role']);

                                        $doc = array(
                                            'fields' => array(
                                                'dataset' => array(
                                                    'krs_podmioty'
                                                ),
                                                'source' => array(
                                                    array(
                                                        'data' => $organizacja,
                                                    ),
                                                ),
                                                'id' => array(
                                                    array(
                                                        $organizacja['id'],
                                                    ),
                                                ),
                                            ),
                                            '_id' => false,

                                        );


                                        echo $this->Dataobject->render($doc, 'default');
                                        ?>

                                        <? if ($role) { ?>
                                            <ul class="list-group less-borders role">
                                                <? foreach ($role as $rola) {
                                                    ?>
                                                    <li class="list-group-item">
                                                        <p><span
                                                                class="label label-primary"><?= $rola['label'] ?></span> <? if (isset($rola['params']['subtitle']) && $rola['params']['subtitle']) { ?>
                                                                <span
                                                                    class="sublabel normalizeText"><?= $rola['params']['subtitle'] ?></span><? } ?>
                                                        </p>
                                                    </li>
                                                <?
                                                }
                                                ?>
                                            </ul>
                                        <? } ?>

                                    </li>
                                <? } ?>
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        <? } ?>

    </div>

</div>
