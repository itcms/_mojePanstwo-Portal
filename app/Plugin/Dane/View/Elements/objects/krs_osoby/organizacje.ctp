<?
$this->Combinator->add_libs('css', $this->Less->css('DataBrowser', array('plugin' => 'Dane')));
?>
<div class="block col-xs-12">

    <header>Powiązane organizacje:</header>

    <section class="content">
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
    </section>
</div>