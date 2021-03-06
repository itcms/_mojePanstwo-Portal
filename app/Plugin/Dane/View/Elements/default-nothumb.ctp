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

if (!isset($truncate))
    $truncate = 150;

$this->Dataobject->setObject($object);

$_manage = isset( $manage ) ? $manage: false;
?>
<div class="objectRender<? echo ($alertsStatus) ? " unreaded" : " readed";
if ($classes = $object->getClasses()) {
    echo " " . implode(' ', $classes);
} ?><? if($object->getOptions('manage')) {?> manage<?}?>" oid="<?php echo $object->getId() ?>" gid="<?php echo $object->getGlobalId() ?>">
		
    <div class="row">
        <div class="data col-xs-12">
            
            <? if($object->getOptions('manage')) {?>
        		
        		<input class="manage-checkbox" type="checkbox" name="id" value="<?= $object->getId() ?>" />
        		
        	<? } ?>
            
            <div class="main_content<? if($object->getOptions('manage')) {?> manage<? } ?>">
                <?
                if ($object->getPosition()) {
                    ?>
                    <div class="content col-md-1">
                        <span class="badge badge-position pull-right"><?= $object->getPosition() ?></span>
                    </div>
                    <?
                }
                ?>
                <div class="content">

                    <? if ($alertsButtons) { ?>
                        <div class="alertsButtons pull-right">
                            <input class="btn btn-xs read" type="button"
                                   value="<?php echo __d('powiadomienia', 'LC_POWIADOMIENIA_OPTIONS_ALERT_BUTTON_READ'); ?>"/>
                            <input class="btn btn-xs unread" type="button"
                                   value="<?php echo __d('powiadomienia', 'LC_POWIADOMIENIA_OPTIONS_ALERT_BUTTON_UNREAD'); ?>"/>
                        </div>
                    <? } ?>

                    <? if ($object->getSideLabel()) { ?>
                        <h3 class="label-glos"><?= $object->getSideLabel() ?></h3>
                    <? } ?>

                    <div>
						
						<? 
	                        $nolabel = true;
	                        if (
	                        	( $object->force_hl_fields || $objectRenderOptions['forceLabel'] ) &&
	                        	( $label = $object->getLabel() )
	                        ) { $nolabel = false; ?>
                            <p class="header">
                                <?= $label ?>
                            </p>
                        <? } ?>

                        <p class="title<? if( $nolabel ) {?> margin-top-5<? } ?>">

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

            </div>
        </div>
    </div>
</div>
