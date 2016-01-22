<?php

App::uses('ApplicationsController', 'Controller');

/**
 * @property TwitterComponent Twitter
 */
class MediaController extends ApplicationsController
{
    public $settings = array(
        'id' => 'media',
        'title' => 'Państwo w mediach społecznościowych',
        'shortTitle' => 'mediach',
        'subtitle' => 'Polityka w mediach społecznościowych',
        'headerImg' => 'media',
    );

    public $components = array('Media.Twitter');

    public $mainMenuLabel = 'Analiza';
    private $twitterAccountType = '0';
    private $twitterTimerange = '1D';
	
	private $accounts_map = array(
		'politycy' => array(7, 'Politycy na Twitterze'),
		'ngo' => array(9, 'Organizacje pozarządowe na Twitterze'),
		'komentatorzy' => array(2, 'Komentatorzy na Twitterze'),
		'urzedy' => array(3, 'Urzędy na Twitterze'),
		'miasta' => array(10, 'Miasta na Twitterze'),
		'media' => array(6, 'Media na Twitterze'),
		'partie' => array(8, 'Partie polityczne na Twitterze'),
	);
	
    public function prepareMetaTags()
    {
        parent::prepareMetaTags();
        $this->setMeta('og:image', FULL_BASE_URL . '/media/img/social/media.jpg');
    }


    public function view()
    {
		$this->set('last_month_report', $this->Twitter->getLastMonthReport());
        $this->set('dropdownRanges', $this->Twitter->getDropdownRanges());

		$timerange = false;
		$init = false;

        if(isset($this->request->query['t']))
            $this->set('t', $this->request->query['t']);

		if( !isset($this->request->query['t']) ) {
			$this->request->query['t'] = '1D';
			$init = true;
		}

		$date_histogram = array(
            'field' => 'date',
            'interval' => 'day',
            'format' => 'yyyy-MM-dd',
        );

		if( $this->twitterTimerange = $this->request->query['t'] ) {
            $timerange = $this->Twitter->getTimerange($this->twitterTimerange);
            if(!$timerange)
                $this->redirect('/media');
		}

		$timerange['init'] = $init;

		$selectedAccountsFilter = array(
			'term' => array(
				'data.twitter.konto_obserwowane' => '1',
			),
		);

		$mentions_accounts_filter = array(
	        'bool' => array(
		        'must_not' => array(
			        'term' => array(
				        'twitter-mentions.account_id' => '0',
			        ),
		        ),
	        ),
        );
		
		if( isset($this->request->query['a']) )
	        foreach( $this->accounts_map as $k => $v )
		        if( $v[0] == $this->request->query['a'] ) {
					$t = @$this->request->query['t'];
					return $this->redirect('/media/' . $k . ($t ? '?t=' . $t : ''));
				}
		
		if(
			@$this->request->params['id'] && 
			array_key_exists($this->request->params['id'], $this->accounts_map)
		) {
			$item = $this->accounts_map[ $this->request->params['id'] ];
			$this->request->query['a'] = $item[0];
			$this->chapter_selected = $this->request->params['id'];
			$this->title = $item[1];
			$this->set('accountTypeLabel', $item[1]);
		}
		
        if(
        	isset($this->request->query['a']) &&
        	array_key_exists($this->request->query['a'], $this->Twitter->twitterAccountTypes) &&
            $this->twitterAccountType = $this->request->query['a']
        ) {

        	$selectedAccountsFilter['term'] = array(
	        	'data.twitter.twitter_account_type_id' => $this->twitterAccountType,
        	);

        	$mentions_accounts_filter = array(
		        'bool' => array(
			        'must' => array(
				        'term' => array(
					        'twitter-mentions.account_type_id' => $this->request->query['a'],
				        ),
			        ),
		        ),
	        );

        }



        $this->set('twitterAccountTypes', $this->Twitter->twitterAccountTypes);
        $this->set('twitterAccountType', $this->twitterAccountType);

        $this->set('twitterTimeranges', $this->Twitter->twitterTimeranges);
        $this->set('twitterTimerange', $this->twitterTimerange);

		$this->set('timerange', $timerange);

        $datasets = $this->getDatasets('media');
        $selectedAccountsAggs = array(
	        'top' => array(
	            'top_hits' => array(
	                'sort' => array(
		                'data.twitter.liczba_zaangazowan' => array(
			                'order' => 'desc',
		                ),
	                ),
	                'fielddata_fields' => array('id', 'date', 'dataset'),
	                'size' => 20,
	            ),
	        ),
	        'accounts_engagement' => array(
	            'terms' => array(
	                'field' => 'data.twitter.twitter_account_id',
	                'order' => array(
		                'engagement_count' => 'desc',
	                ),
	                'size' => 10,
	            ),
	            'aggs' => array(
	                'name' => array(
		                'terms' => array(
			                'field' => 'data.twitter_accounts.name',
			                'size' => 1,
		                ),
	                ),
	                'image_url' => array(
		                'terms' => array(
			                'field' => 'data.twitter_accounts.profile_image_url_https',
			                'size' => 1,
		                ),
	                ),
	                'account_type' => array(
		                'terms' => array(
			                'field' => 'data.twitter.twitter_account_type_id',
			                'size' => 1,
		                ),
	                ),
	                'engagement_count' => array(
		                'sum' => array(
			                'field' => 'data.twitter.liczba_zaangazowan',
		                ),
	                ),
	            ),
	        ),
	        'accounts_tweets' => array(
	            'terms' => array(
	                'field' => 'data.twitter.twitter_account_id',
	                'size' => 10,
	            ),
	            'aggs' => array(
	                'name' => array(
		                'terms' => array(
			                'field' => 'data.twitter_accounts.name',
			                'size' => 1,
		                ),
	                ),
	                'image_url' => array(
		                'terms' => array(
			                'field' => 'data.twitter_accounts.profile_image_url_https',
			                'size' => 1,
		                ),
	                ),
	                'account_type' => array(
		                'terms' => array(
			                'field' => 'data.twitter.twitter_account_type_id',
			                'size' => 1,
		                ),
	                ),
	            ),
	        ),
	        'accounts_engagement_tweets' => array(
	            'terms' => array(
	                'field' => 'data.twitter.twitter_account_id',
	                'size' => 10,
	                'order' => array(
		                'engagement_count' => 'desc',
	                ),
	            ),
	            'aggs' => array(
	                'name' => array(
		                'terms' => array(
			                'field' => 'data.twitter_accounts.name',
			                'size' => 1,
		                ),
	                ),
	                'image_url' => array(
		                'terms' => array(
			                'field' => 'data.twitter_accounts.profile_image_url_https',
			                'size' => 1,
		                ),
	                ),
	                'account_type' => array(
		                'terms' => array(
			                'field' => 'data.twitter.twitter_account_type_id',
			                'size' => 1,
		                ),
	                ),
	                'engagement_count' => array(
		                'avg' => array(
			                'field' => 'data.twitter.liczba_zaangazowan',
		                ),
	                ),
	            ),
	        ),
	        'tags' => array(
	            'nested' => array(
	                'path' => 'twitter-tags',

	            ),
	            'aggs' => array(
	                'tags' => array(
		                'terms' => array(
			                'field' => 'twitter-tags.id',
			                'size' => 20,
			                'order' => array(
				                'rn' => 'desc',
			                ),
		                ),
		                'aggs' => array(
			                'label' => array(
				                'terms' => array(
					                'field' => 'twitter-tags.name',
					                'size' => 1,
				                ),
			                ),
			                'rn' => array(
				                'reverse_nested' => '_empty',
				                'aggs' => array(
					                'engagement_count' => array(
						                'sum' => array(
							                'field' => 'data.twitter.liczba_zaangazowan',
						                ),
					                ),
				                ),
			                ),
		                ),
	                ),
	            ),
	        ),
	        'sources' => array(
	            'terms' => array(
	                'field' => 'data.twitter.source_id',
	                'size' => 5,
	            ),
	            'aggs' => array(
	                'label' => array(
		                'terms' => array(
			                'field' => 'data.twitter.source',
			                'size' => 1,
		                ),
	                ),
	            ),
	        ),
	    );
		
		
		// debug($selectedAccountsFilter);
		

        $options = array(
            'searchTag' => array(
	            'href' => '/media',
	            'label' => 'Media',
            ),
            'conditions' => array(
                'dataset' => array_keys($datasets),
            ),
            'cover' => array(
                'view' => array(
                    'plugin' => 'Media',
                    'element' => 'cover',
                ),
                'aggs' => array(
	                'dataset' => array(
	                    'terms' => array(
	                        'field' => 'dataset',
	                    ),
	                    'visual' => array(
	                        'skin' => 'datasets',
	                        'class' => 'special',
	                        'field' => 'dataset',
	                        'dictionary' => $datasets,
	                    ),
	                ),
	                /*
	                'accounts' => array(
		                'scope' => 'global',
		                'filter' => array(
			                'bool' => array(
				                'must' => array(
					                array(
						                'term' => array(
							                'dataset' => 'twitter_accounts',
						                ),
					                ),
					                array(
						                'nested' => array(
							                'path' => 'twitter_accounts-followers',
							                'filter' => array(
								                'term' => array(
									                'twitter_accounts-followers.date' => array(
									                	substr($timerange['labels']['min'], 0, 10),
									                	substr($timerange['labels']['max'], 0, 10),
									                ),
								                ),
							                ),
						                ),
					                ),
				                ),
			                ),
		                ),
		                'aggs' => array(
			                'accounts' => array(
				                'terms' => array(
					                'field' => 'id',
				                ),
				                'aggs' => array(
					                'name' => array(
						                'terms' => array(
							                'field' => 'twitter_accounts.name',
							                'size' => 1,
						                ),
					                ),
					                'photo' => array(
						                'terms' => array(
							                'field' => 'twitter_accounts.profile_image_url_https',
							                'size' => 1,
						                ),
					                ),
					                'counts' => array(
						                'nested' => array(
							                'path' => 'twitter_accounts-followers',
						                ),
						                'aggs' => array(
							                'dates' => array(
								                'filter' => array(
									                'term' => array(
										                'twitter_accounts-followers.date' => array(
											                substr($timerange['labels']['min'], 0, 10),
										                	substr($timerange['labels']['max'], 0, 10),
										                )
									                ),
								                ),
								                'aggs' => array(
									                'dates' => array(
										                'terms' => array(
											                'field' => 'twitter_accounts-followers.date',
											                'size' => 2,
										                ),
										                'aggs' => array(
											                'count' => array(
												                'terms' => array(
													                'field' => 'twitter_accounts-followers.count',
													                'size' => 1,
												                ),
											                ),
										                ),
									                ),
								                ),
							                ),
						                ),
					                ),
				                ),
			                ),
		                ),
	                ),
	                */
	                'tweets' => array(
		                'scope' => 'global',
		                'filter' => array(
			                'bool' => array(
				                'must' => array(
					                array(
						                'term' => array(
							                'dataset' => 'twitter',
						                ),
					                ),
					                array(
						                'term' => array(
							                'data.twitter.retweet' => '0',
							            ),
					                ),
				                ),
			                ),
		                ),
		                'aggs' => array(
			                'global_timerange' => array(
						        'filter' => array(
							        'range' => array(
						                'date' => $timerange['histogram_filter'],
					                ),
						        ),
						        'aggs' => array(
							        'selected_accounts' => array(
								        'filter' => $selectedAccountsFilter,
								        'aggs' => array(
									        'histogram' => array(
								                'date_histogram' => $date_histogram,
							                ),
								        ),
							        ),
							        'target_timerange' => array(
								        'filter' => array(
									        'range' => array(
								                'date' => $timerange['target_filter'],
							                ),
								        ),
								        'aggs' => array(
									        'accounts' => array(
										        'filter' => $selectedAccountsFilter,
										        'aggs' => $selectedAccountsAggs,
									        ),
									        'mentions' => array(
										        'nested' => array(
											        'path' => 'twitter-mentions',
										        ),
										        'aggs' => array(
											        'accounts' => array(
												        'filter' => $mentions_accounts_filter,
												        'aggs' => array(
													        'ids' => array(
														        'terms' => array(
															        'field' => 'twitter-mentions.account_id',
															        'size' => 10,
														        ),
														        'aggs' => array(
															        'screen_name' => array(
																        'terms' => array(
																	        'field' => 'twitter-mentions.screen_name',
																	        'size' => 1,
																        ),
															        ),
															        'name' => array(
																        'terms' => array(
																	        'field' => 'twitter-mentions.name',
																	        'size' => 1,
																        ),
															        ),
                                                                    'photo' => array(
                                                                        'terms' => array(
                                                                            'field' => 'twitter-mentions.account_photo_url',
                                                                            'size' => 1,
                                                                        ),
                                                                    ),
														        )
													        ),
												        ),
											        ),
										        ),
									        ),
								        ),
							        ),
						        ),
					        ),
		                ),
	                ),
                ),
            ),
            'aggs' => array(
                'dataset' => array(
                    'terms' => array(
                        'field' => 'dataset',
                    ),
                    'visual' => array(
                        'skin' => 'datasets',
                        'class' => 'special',
                        'field' => 'dataset',
                        'dictionary' => $datasets,
                    ),
                ),
            ),
            'apps' => true,
        );

        $this->Components->load('Dane.DataBrowser', $options);
        $this->render('Dane.Elements/DataBrowser/browser-from-app');

    }

    public function propozycje_kont()
    {

	    $accounts = $this->Media->getAccountsPropositions();

		$keys = array_slice($accounts['keys'], 0, 20);
		// $keys = $accounts['keys'];

        $options = array(
            'searchTitle' => 'Szukaj w tweetach i kontach Twitter...',
            'conditions' => array(
                'dataset' => 'twitter',
                // 'twitter.twitter_user_id' => $accounts['keys'],
            ),
            'cover' => array(
                'view' => array(
                    'plugin' => 'Media',
                    'element' => 'propozycje_kont',
                ),
                'aggs' => array(
	                /*
	                'accounts' => array(
		                'filter' => array(
			                'bool' => array(
				                'must' => array(
					                array(
						                'term' => array(
							                'dataset' => 'twitter_accounts',
						                ),
					                ),
					                array(
						                'term' => array(
							                'data.twitter_accounts.twitter_id' => $keys,
						                ),
					                ),
				                ),
			                ),
		                ),
		                'aggs' => array(
			                'ids' => array(
				                'top_hits' => array(
					                'size' => 100,
				                ),
			                ),
		                ),
		                'scope' => 'global',
	                ),
	                */
	                'new' => array(
		                'filter' => array(
			                'bool' => array(
				                'must' => array(
					                array(
						                'terms' => array(
							                'data.twitter.twitter_user_id' => $keys,
						                ),
					                ),
					                array(
						                'term' => array(
							                'data.twitter.retweet' => '0',
						                ),
					                ),
				                ),
			                ),
		                ),
		                'aggs' => array(
			                'accounts' => array(
				                'terms' => array(
					                'field' => 'twitter.twitter_user_id',
					                'size' => 100,
				                ),
				                'aggs' => array(
					                'tweets' => array(
						                'top_hits' => array(
							                'sort' => array(
								                'data.twitter.liczba_zaangazowan' => array(
									                'order' => 'desc',
								                ),
							                ),
							                'fielddata_fields' => array('dataset', 'id', 'date'),
							                'size' => 3,
						                ),
						            ),
				                ),
			                ),
		                ),
	                ),

                ),
            ),
            'apps' => true,
        );

        $this->set('twitterAccountTypes', $this->Twitter->twitterAccountTypes);
		$this->set('accounts', $accounts['items']);
		$this->set('time', $accounts['time']);
        $this->Components->load('Dane.DataBrowser', $options);
        $this->render('Dane.Elements/DataBrowser/browser-from-app');

    }

    public function manage_account()
    {

	    if(
	    	isset( $this->request->data['id'] ) &&
	    	isset( $this->request->data['add'] )
    	) {

	    	$res = $this->Media->manage_account($this->request->data);
	    	$this->Session->setFlash( $res['msg'] );

    	}

    	return $this->redirect($this->referer());

    }

    public function media_2013()
    {
        $this->title = "Media polityczne w 2013 roku";
        //$this->loadDatasetBrowser('twitter_accounts');
    }

    public function getChapters()
    {

	    $mode = false;
		$items = array();
		$app = $this->getApplication( $this->settings['id'] );

		if(
			isset( $this->request->query['q'] ) &&
			$this->request->query['q']
		) {
						
			$items[] = array(
				'id' => '_results',
				'label' => 'Szukaj na Twitterze:',
				'href' => '/' . $this->settings['id'] . '?q=' . urlencode( $this->request->query['q'] ),
				'icon' => 'appIcon',
				'appIcon' => $app['icon'],
				'class' => '_label',
			);

			if( $this->chapter_selected=='view' )
				$this->chapter_selected = '_results';
			$mode = 'results';

		} else {
			
			
			$items[] = array(
				'label' => 'Państwo na Twitterze',
				'href' => '/' . $this->settings['id'],
				'class' => '_label no-border-bottom',
				'icon' => 'appIcon',
				'appIcon' => $app['icon'],
			);
			
			$items[] = array(
				'id' => 'politycy',
				'label' => 'Politycy',
				'href' => '/media/politycy',
				'icon' => 'icon-datasets-dot',
			);
			
			$items[] = array(
				'id' => 'urzedy',
				'label' => 'Urzędy',
				'href' => '/media/urzedy',
				'icon' => 'icon-datasets-dot',
			);
			
			$items[] = array(
				'id' => 'miasta',
				'label' => 'Miasta',
				'href' => '/media/miasta',
				'icon' => 'icon-datasets-dot',
			);
			
			$items[] = array(
				'id' => 'komentatorzy',
				'label' => 'Komentatorzy polityczni',
				'href' => '/media/komentatorzy',
				'icon' => 'icon-datasets-dot',
			);
			
			$items[] = array(
				'id' => 'ngo',
				'label' => 'NGO',
				'href' => '/media/ngo',
				'icon' => 'icon-datasets-dot',
			);
			
			$items[] = array(
				'id' => 'partie',
				'label' => 'Partie polityczne',
				'href' => '/media/partie',
				'icon' => 'icon-datasets-dot',
				'class' => 'border-bottom',
			);
			
			
			
		}

		if(
			( $map = @$this->viewVars['dataBrowser']['aggs_visuals_map']['dataset']['dictionary'] ) &&
			( $datasets = @$this->viewVars['dataBrowser']['aggs']['dataset']['buckets'] )
		) {

			foreach( $map as $key => $value ) {

				if( !isset($value['menu_id']) )
					$value['menu_id'] = '';
								
				$item = array(
					'id' => $value['menu_id'],
					'label' => $value['label'],
					'href' => '/' . $this->settings['id'] . '/' . $value['menu_id'],
					'icon' => 'icon-datasets-' . $key,

				);

				if( $mode == 'results' ) {

					$item['href'] .= '?q=' . urlencode( $this->request->query['q'] );

					foreach( $datasets as $d ) {

						if( $d['key']==$key ) {

							if( $d['doc_count'] ) {
								$item['count'] = $d['doc_count'];
								$items[] = $item;
							}

							break;

						}
					}

				} else {

					$items[] = $item;

				}

			}

		}
		
        foreach($items as $i => $item) {

            if(isset($item['submenu'])) {
                $items[$i]['submenu']['selected'] = $this->chapter_submenu_selected;
            }

        }
        
		$output = array(
			'items' => $items,
			'selected' => ($this->chapter_selected=='view') ? false : $this->chapter_selected,
		);
				

	    if( $this->isSuperUser() )
		    $output['items'][] = array(
			    'id' => 'propozycje_kont',
			    'href' => 'propozycje_kont',
			    'label' => 'Propozycje nowych kont',
			    'icon' => 'icon-datasets-twitter_accounts',
		    );

	    return $output;

    }
}
