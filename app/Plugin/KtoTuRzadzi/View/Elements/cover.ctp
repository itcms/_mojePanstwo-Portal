<?php
$this->Combinator->add_libs('css', $this->Less->css('administracja', array('plugin' => 'KtoTuRzadzi')));
$this->Combinator->add_libs('js', 'KtoTuRzadzi.administracja.js');
?>

<? /*
<div class="col-xs-12 col-sm-4 col-md-1-5 noleftpadding dataAggsContainer">
	<div class="mp-sticky mp-sticky-disable-sm-4" data-widthFromWrapper="false">
	    <? echo $this->Element('Dane.DataBrowser/app_chapters'); ?>
	</div>
</div>

<div id="bdl_div" class="col-xs-12 col-sm-8 col-md-4-5 norightpadding">
*/ ?>

<div id="bdl_div" class="col-xs-12">
	<div class="dataWrap">

		<div class="appBanner bottom-border">
			<h1 class="appTitle">Kto tu rządzi?</h1>
			<p class="appSubtitle">Informacje o urzędach i urzędnikach publicznych.</p>
		</div>
	
	</div>


    <div id="administracja">

        <div class="content col-xs-12 row">
            <div class="row items">
                <? foreach ($data['files'] as $item) { ?>
                    <div class="block col-md-<?= $item['width'] ?>">
                        <div class="item" data-id="<?= $item['id'] ?>">

                            <a href="/dane/instytucje/<?= $item['id'] ?>" class="inner"
                               data-title="<?= $item['nazwa'] ?>"
                               data-info='{
                                        "adres": ["Skwer kard. Wyszyńskiego 9 01-015 Warszawa"],
                                        "www": ["http://www.pg.gov.pl/bip/"],
                                        "email":["BPG@pg.gov.pl"],
                                        "telefon": ["22 125-14-91"],
                                        "fax": ["22 125-18-82"],
                                        "instytucje": ["Prokuratura Apelacyjna w Krakowie", "Prokuratura Okręgowa w Kielcach","Prokuratura Okręgowa w Krakowie","Prokuratura Okręgowa w Tarnowie","Prokuratura Okręgowa w Nowym Sączu"]
                                    }'>

                                <div class="logo">
                                    <img src="/KtoTuRzadzi/img/instytucje/<?= $item['id'] ?>.png"
                                         title="<?= $item['nazwa'] ?>" onerror="imgFixer(this)"/>
                                </div>

                                <div class="details"><? if ($item['budzet_plan']) { ?><span class="detail">
                                        Budżet: <?= number_format_h($item['budzet_plan'] * 1000) ?></span><? } ?>
                                </div>


                                <div class="title">
                                    <div class="nazwa"><?= $item['nazwa'] ?></div>
                                </div>

                                <div class="text">
                                    <?= $item['opis_html'] ?>
                                </div>

                            </a>

                        </div>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>



</div>

