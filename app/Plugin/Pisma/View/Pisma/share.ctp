<?php $this->Combinator->add_libs('css', $this->Less->css('pisma', array('plugin' => 'Pisma'))) ?>
<?php $this->Combinator->add_libs('js', 'Pisma.rangy/rangy-core.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.rangy/rangy-classapplier.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.rangy/rangy-textrange.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.rangy/rangy-highlighter.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.pisma.js') ?>
<?php $this->Combinator->add_libs('js', 'Pisma.pisma-share.js') ?>

<?= $this->Element('appheader'); ?>

<div class="container">

    <? echo $this->element('Pisma.pismo-header', array(
        'pismo' => $pismo,
        'alert' => true,
    )); ?>
    <div class="row">
        <div id="stepper">
            <div class="content clearfix">
                <div class="col-md-10 view">
                    <? echo $this->Element('Pisma.render'); ?>
                </div>
                <div class="col-md-2 nopadding">
                    <div class="editor-tooltip">

                        <? $href_base = '/pisma/' . $pismo['alphaid'] . ',' . $pismo['slug']; ?>

                        <ul class="form-buttons">
                            <li class="inner-addon">
                                <button class="btn btn-info" type="button" ontouchstart="highlightSelectedText();"
                                        onclick="highlightSelectedText();">Ukryj tekst
                                </button>
                                <p class="desc">Zaznacz tekst aby go ukryć.</p>
                            </li>
                            <li class="inner-addon">
                                <button class="btn btn-info" type="button"
                                        ontouchstart="removeHighlightFromSelectedText();"
                                        onclick="removeHighlightFromSelectedText();">Odkryj tekst
                                </button>
                                <p class="desc">Zaznacz ukryty tekst aby od odkryć.</p>
                            </li>

                        </ul>

                        <ul class="form-buttons more-buttons-target" style="display: none;">
                            <li class="inner-addon left-addon">
                                <form onsubmit="return confirm('Czy na pewno chcesz usunąć to pismo?');" method="post"
                                      action="/pisma/<?= $pismo['alphaid'] ?>,<?= $pismo['slug'] ?>">
                                    <i class="glyphicon glyphicon-trash"></i>
                                    <input name="delete" type="submit" class="form-control btn btn-danger"
                                           value="Skasuj"/>
                                </form>
                            </li>
                        </ul>

                        <p class="more-buttons-switcher-cont">
                            <a class="more-buttons-switcher" data-mode="more" href="#more"><span
                                    class="glyphicon glyphicon-chevron-down"></span> <span
                                    class="text">Więcej</span></a>
                        </p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>