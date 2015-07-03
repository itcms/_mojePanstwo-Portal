<?= $this->Element('dataobject/pageBegin', array('renderFile' => 'page-bdl_wskazniki')); ?>
<?= $this->Element('bdl_select', array('expand_dimension' => $expand_dimension, 'dims' => $dims)); ?>

    <div class="treeBlock hidden-xs col-sm-4 col-md-3">
        <?
        $this->Combinator->add_libs('js', 'Bdl.jstree.min');
        $this->Combinator->add_libs('js', 'Bdl.bdl');
        ?>
        <div
            id="tree" <?= printf('data-structure="%s"', htmlspecialchars(json_encode($tree), ENT_QUOTES, 'UTF-8')) ?>></div>
    </div>
    <div id="bdl-wskazniki" class="col-xs-12 col-sm-8 col-md-9">
        <? if (in_array('bdl_opis', $object_editable)) {
            echo $this->element('Dane.bdl_opis');
        } ?>

        <div class="object">
            <?
            if (!empty($expanded_dimension)) {
                foreach ($expanded_dimension['options'] as $option) {
                    if (isset($option['data'])) {
                        echo $this->element('Dane.bdl_wskaznik', array(
                            'data' => $option['data'],
                            'url' => $object->getUrl(),
                            'title' => $option['value'],
                        ));
                    }
                }
            }
            ?>
        </div>
    </div>

<?= $this->Element('dataobject/pageEnd'); ?>