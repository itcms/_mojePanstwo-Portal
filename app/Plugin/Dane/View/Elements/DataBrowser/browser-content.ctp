<?

$this->Combinator->add_libs('css', $this->Less->css('dataobject', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('dataobjectpage', array('plugin' => 'Dane')));
$this->Combinator->add_libs('css', $this->Less->css('DataBrowser', array('plugin' => 'Dane')));
$this->Combinator->add_libs('js', '../plugins/highcharts/js/highcharts');
$this->Combinator->add_libs('js', '../plugins/highcharts/locals');
$this->Combinator->add_libs('js', 'Dane.DataBrowser.js');
$this->Combinator->add_libs('js', 'Dane.DataAggsDropdown.js');

?>

<div class="modal modal-api-call">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title"><span class="glyphicon glyphicon-cog"></span> REST API</h4>
            </div>
            <div class="modal-body">

                <? if( isset($dataBrowser['api_call']) ) {?>

                    <p>Aby pobrać dane widoczne na tym ekranie, wyślij żądanie HTTP GET pod adres:</p>

                    <a class="modal-api-call-link" target="_blank"
                       href="<?= $dataBrowser['api_call'] ?>"><?= htmlspecialchars($dataBrowser['api_call']) ?></a>

                <? } ?>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

<?
if ($dataBrowser['mode'] == 'cover') { ?>

    <?= $this->element($dataBrowser['cover']['view']['plugin'] . '.' . $dataBrowser['cover']['view']['element']); ?>


    <?

} else {

    $params = $this->Paginator->params();

    ?>

    <? if ($displayAggs && !empty($dataBrowser['aggs'])) { ?>

        <div class="col-md-<?= $columns[1] ?> col-xs-12 dataAggsContainer">

            <? if( isset($sideElement) ) echo $this->Element($sideElement) ?>



            <? if(isset($menu) && isset($menu['items'])) {

                echo $this->Element('Dane.DataBrowser/browser-menu', array(
                    'menu' => $menu,
                ));

            } else {

                echo $this->Element('Dane.DataBrowser/aggs', array(
                    'data' => $dataBrowser,
                ));

            } ?>

        </div>
    <? } ?>
    <div class="col-xs-12 col-md-<?= $displayAggs ? $columns[0] : 12 ?>">

        <div class="dataWrap">
            <? if(isset($dataBrowser['aggs_visuals_map']) && count($dataBrowser['aggs_visuals_map']) > 0) { ?>
                <ul class="nav nav-pills margin-top-10 dataAggsDropdownList nopadding" role="tablist">
                    <?
	                    foreach($dataBrowser['aggs_visuals_map'] as $name => $map) {

		                    if( isset($map['target']) && ($map['target']=='filters') ) {

			                    if( !isset($map['all']) )
			                    	$map['all'] = 'Wszystko';

                    ?>
                        <li role="presentation" class="dropdown dataAggsDropdown<?= isset($this->request->query['conditions'][$map['field']]) ? ' active' : ''; ?>"
                            data-skin="<?= $map['skin'] ?>"
                            data-aggs='<?= json_encode($dataBrowser['aggs'][$name]) ?>'
                            data-cancel-request="<?= $map['cancelRequest'] ?>"
                            data-label-dictionary='<?= json_encode(isset($map['dictionary']) ? $map['dictionary'] : array()) ?>'
                            data-choose-request="<?= $map['chooseRequest'] ?>"
                            data-all-label="<?= $map['all'] ?>"
                            data-is-selected="<?= isset($this->request->query['conditions'][$map['field']]) ?>">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <? if(isset($this->request->query['conditions'][$map['field']])) { ?>
                                    <?= isset($dataBrowser['aggs'][$name]['buckets'][0]['label']['buckets'][0]['key']) ? $dataBrowser['aggs'][$name]['buckets'][0]['label']['buckets'][0]['key'] : (isset($dataBrowser['aggs'][$name]['buckets'][0]['key']) ? $dataBrowser['aggs'][$name]['buckets'][0]['key'] : 'Usuń filtr'); ?>
                                <? } else { ?>
                                    <?= $map['all'] ?>
                                <? } ?>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu"></ul>
                        </li>
                    <? } } ?>
                </ul>
            <? } ?>

            <div class="dataObjects margin-top-10">

                <div class="innerContainer update-objects">

                    <?
                    if (isset($dataBrowser['hits'])) {
                        if (empty($dataBrowser['hits'])) {
                            echo '<p class="noResults">' . __d('dane', 'LC_DANE_BRAK_WYNIKOW') . '</p>';
                        } else {
                            ?>
                            <ul class="list-group list-dataobjects">
                                <?

                                $params = array();
                                if( isset($truncate) )
                                    $params['truncate'] = $truncate;

                                foreach ($dataBrowser['hits'] as $object) {

                                    echo $this->Dataobject->render($object, $dataBrowser['renderFile'], $params);
                                }
                                ?>
                            </ul>
                            <?
                        }
                    }
                    ?>

                </div>

            </div>

            <div class="dataPagination">
                <ul class="pagination">
                    <?php

                    //$this->MPaginator->options['url'] = array('alias' => 'prawo');
                    //$this->MPaginator->options['paramType'] = 'querystring';

                    //echo $this->MPaginator->first('&larr;', array('tag' => 'li', 'escape' => false), '<a href="#">&larr;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));


                    // echo $this->MPaginator->prev('&laquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&laquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                    echo $this->MPaginator->numbers(array('separator' => '', 'tag' => 'li', 'currentLink' => true, 'currentClass' => 'active', 'currentTag' => 'a'));
                    // echo $this->MPaginator->next('&raquo;', array('tag' => 'li', 'escape' => false), '<a href="#">&raquo;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                    //echo $this->MPaginator->last('&rarr;', array('tag' => 'li', 'escape' => false), '<a href="#">&rarr;</a>', array('class' => 'prev disabled', 'tag' => 'li', 'escape' => false));
                    ?>
                </ul>
            </div>

        </div>

    </div>


<? } ?>