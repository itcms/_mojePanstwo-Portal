<div class="objectSideInner">

    <div class="block">
		
        <ul class="dataHighlights side">

            <? if ($druk->getData('tytul')) { ?>
			    <li class="dataHighlight big">
			        <p class="_label">Druk</p>
			
			        <p class="_value"><?= $druk->getData('tytul'); ?></p>
			    </li>
			<? } ?>
            
                        
            <? if( $druk->getDate() ) {?>
	            <li class="dataHighlight">
			        <p class="_label">Data przedstawienia</p>
			
			        <p class="_value"><?= dataSlownie($druk->getDate()); ?></p>
			    </li>
            <? } ?>
            
            <? if( $druk->getData('autor_str') ) {?>
	            <li class="dataHighlight">
			        <p class="_label">Autor</p>
			
			        <p class="_value"><?= $druk->getData('autor_str'); ?></p>
			    </li>
            <? } ?>
            
            <li class="dataHighlight">
                <a href="<?= $druk->getUrl() . '/tresc' ?>"><span class="icon icon-moon">&#xe610;</span>Treść
                    druku<span class="glyphicon glyphicon-chevron-right"></span></a>
            </li>

        </ul>
    </div>


</div>