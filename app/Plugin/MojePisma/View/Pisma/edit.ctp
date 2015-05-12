<?php echo $this->Html->css('../plugins/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.min', array('block' => 'cssBlock')); ?>

<?php echo $this->Html->script('../plugins/bootstrap3-wysiwyg/dist/bootstrap3-wysihtml5.all', array('block' => 'scriptBlock')); ?>
<?php echo $this->Html->script('../plugins/bootstrap3-wysiwyg/dist/locales/bootstrap-wysihtml5.pl-PL', array('block' => 'scriptBlock')); ?>

<?php $this->Combinator->add_libs('css', $this->Less->css('pisma', array('plugin' => 'MojePisma'))) ?>
<?php $this->Combinator->add_libs('js', 'MojePisma.pisma.js') ?>

<?= $this->Element('appheader') ?>

<? echo $this->Element('MojePisma.editor', array('title' => isset($pismo['tytul']) ? $pismo['tytul'] : '')); ?>