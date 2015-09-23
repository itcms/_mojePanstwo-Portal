<?
$objectRenderOptions = array(
    'forceLabel' => (isset($dataBrowserObjectRender) && isset($dataBrowserObjectRender['forceLabel'])) ? (boolean)$dataBrowserObjectRender['forceLabel'] : false,
);


$path = App::path('Plugin');
$file = $path[0] . '/Dane/View/Elements/' . $theme . '/' . $object->getDataset() . '.ctp';
$file_exists = file_exists($file);

$shortTitle = (isset($options['forceTitle'])) ?
    $options['forceTitle'] :
    $object->getShortTitle();

$object_content_sizes = $object->getDefaultColumnsSizes();

if( !isset($truncate) )
	$truncate = 150;

// debug( $object->getData() ); 

$this->Dataobject->setObject($object);
?>
<div class="objectRender<? if ($alertsStatus) {
    echo " unreaded";
} else {
    echo " readed";
} ?><? if ($classes = $object->getClasses()) {
    echo " " . implode(' ', $classes);
} ?>"
     oid="<?php echo $object->getId() ?>" gid="<?php echo $object->getGlobalId() ?>">

    <div class="row">

        <div class="data col-xs-12">
			
            <? /* if ($sentence = $object->getSentence()) { ?>
                <p class="sentence"><?= $sentence ?></p>
            <? } */ ?>

            <div>


                <? if ($object->getThumbnailUrl($thumbSize)) {

                    $size = $object_content_sizes[0];
                    if ($object->getPosition()) {
                        $size--;
                    }

                    ?>
                    <div
                        class="attachment col-xs-<?= $size + 2 ?> col-sm-<?= $size + 1 ?> col-md-<?= $size ?> text-center">
                        <?php if ($object->getUrl() != false) { ?>
                        <a class="thumb_cont" href="<?= $object->getUrl() ?>">
                            <?php } ?>
                            <img class="thumb pull-right" src="<?
                            $img_link = $object->getThumbnailUrl($thumbSize);
                            $str = preg_replace('#^https?:#', '', $img_link);
                            echo $img_link;
                            ?>" alt="<?= strip_tags($object->getTitle()) ?>" onerror="imgFixer(this)"/>
                            <?php if ($object->getUrl() != false) { ?>
                        </a>
                    <?php } ?>

                    </div>
                                        
                    <div class="content col-xs-<?= $object_content_sizes[1] - 2 ?> col-sm-<?= $object_content_sizes[1] - 1 ?> col-md-<?= $object_content_sizes[1] ?>">

                        <? if ($object->force_hl_fields || $objectRenderOptions['forceLabel']) { ?>
                            <p class="header">
                                <?= $object->getLabel(); ?>
                            </p>
                        <? } ?>

                        <p class="title">
                            <?php if ($object->getUrl() != false) { ?>
                            <a href="<?= $object->getUrl() ?>" title="<?= strip_tags($object->getTitle()) ?>">
                                <?php } ?>
                                <?= $this->Text->truncate($shortTitle, $truncate) ?>
                                <?php if ($object->getUrl() != false) { ?>
                            </a> <?
                        }
                        if ($object->getTitleAddon()) {
                            echo '<small>' . $object->getTitleAddon() . '</small>';
                        } ?>
                        </p>

                        <? if ($metaDesc = $object->getMetaDescription()) { ?>
                            <p class="meta meta-desc"><?= $metaDesc ?></p>
                        <? } ?>

                        <?
                        if ($file_exists) {
                            echo $this->element('Dane.' . $theme . '/' . $object->getDataset(), array(
                                // 'item' => $item,
                                'object' => $object,
                                'hlFields' => $hlFields,
                                'hlFieldsPush' => $hlFieldsPush,
                                'defaults' => $defaults,
                            ));
                        } else {

                            // echo $this->Dataobject->highlights($hlFields, $hlFieldsPush, $defaults);
                        }
                        ?>

                        <? if (
                            ($object->hasHighlights()) &&
                            ($highlight = $object->getLayer('highlight'))
                        ) { ?>
                            <? if ($highlight[0] != '<em>' . $object->getShortTitle() . '</em>') { ?>
                                <div class="description highlight">
                                    <?= $highlight[0] ?>
                                </div>
                            <? } ?>
                        <? } elseif ($object->getDescription()) { ?>
                            <div class="description">
                                <?= $object->getDescription() ?>
                            </div>
                        <? } ?>

                    </div>

                <? } else { ?>
                    <div class="content<? if ($object->getPosition()) { ?> col-md-11<? } ?>">
                        
												
                        <? if ($object->getIcon()) {
                            echo $object->getIcon();
                        } ?>
						
						<? if( $object->getSideLabel() ) { ?>
						<h3 class="label-glos"><?= $object->getSideLabel() ?></h3>
						<? } ?>
						
                        <div class="<? if ($object->getIcon()) { ?>object-icon-side <? } ?> <? if( $object->getSideLabel() ) { echo 'marginRight'; } ?>">

                            <? if ($object->force_hl_fields || $objectRenderOptions['forceLabel']) { ?>
                                <p class="header">
                                    <?= $object->getLabel(); ?>
                                </p>
                            <? } ?>

                            <p class="title">
                                <?php if ($object->getUrl() != false){ ?>
                                <a href="<?= $object->getUrl() ?>" title="<?= strip_tags($object->getTitle()) ?>">
                                    <?php } ?>
                                    <?= $this->Text->truncate($shortTitle, $truncate) ?>
                                    <?php if ($object->getUrl() != false){ ?>
                                </a> <?
                            }
                            if ($object->getTitleAddon()) {
                                echo '<small>' . $object->getTitleAddon() . '</small>';
                            } ?>
                            </p>

                            <? if ($metaDesc = $object->getMetaDescription()) { ?>
                                <p class="meta meta-desc"><?= $metaDesc ?></p>
                            <? } ?>

                            <?

                            // debug( $object->getData() );
                            if ($file_exists) {
                                echo $this->element('Dane.' . $theme . '/' . $object->getDataset(), array(
                                    'object' => $object,
                                    'hlFields' => $hlFields,
                                    'hlFieldsPush' => $hlFieldsPush,
                                    'defaults' => $defaults,
                                ));
                            } else {
                                // echo $this->Dataobject->highlights($hlFields, $hlFieldsPush, $defaults);
                            }
                            ?>

                            <? if (
                                ($object->hasHighlights()) &&
                                ($highlight = $object->getLayer('highlight'))
                            ) { ?>
                                <? if ($highlight[0] != '<em>' . $object->getShortTitle() . '</em>') { ?>
                                    <div class="description">
                                        <?= $highlight[0] ?>
                                    </div>
                                <? } ?>
                            <? } elseif ($object->getDescription()) { ?>
                                <div class="description">
                                    <?= $this->Text->truncate($object->getDescription(), 300) ?>
                                </div>
                            <? } ?>

                        </div>

                    </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>