String.prototype.capitalizeFirstLetter = function () {
	"use strict";
	return this.charAt(0).toUpperCase() + this.slice(1);
};

var BDLapp = function () {
	this.variable = {
		link: null,
		jsScrollHandler: null,
		jsScrollTarget: null
	};

	this.init = function () {
		this.treeLoad();
		this.itemInit();
		this.subitemInit();
	};

	this.setup = function () {
		var self = this;

		if (History.getState().data.item == undefined) {
			var href = window.location.pathname,
				link_id = href.substr(href.lastIndexOf('/') + 1).split(',')[0],
				data = $('#tree').jstree(true).get_node('link_id=' + link_id);

			if (data) {
				self.variable.link = data.parents.reverse();
				self.variable.link.push(data.id);
			}
		} else {
			self.variable.link = History.getState().data.item;

			if (self.variable.link.slice(self.variable.link.indexOf('&') + 1)) {
				self.variable.link = self.variable.link.split('&');
			}
		}

		self.treeFocus();
	};

	this.treeLoad = function () {
		var self = this,
			$leftSideAccordion = $('#leftSideAccordion'),
			$tempItemOpisModal = $('#temp_item_opis_modal'),
			tree = $('#tree'),
			json = tree.data('structure'),
			rootData = [];

		/** @namespace json.kategorie */
		$.each(json.kategorie, function (itemKey, itemData) {
			/** @namespace itemData.dane */
			var item = {
				'data ': {id: itemKey},
				'text': itemData.dane.tytul.toLowerCase().capitalizeFirstLetter(),
				'id': 'kategoria_id=' + itemKey,
				'a_attr': {
					'href': '#',
					'data-item': 'kategoria_id=' + itemKey
				},
				'children': true
			}, itemChildren = [];

			/** @namespace itemData.grupy */
			$.each(itemData.grupy, function (grupyKey, grupyData) {
				var grupy = {
					'data': {'id': grupyKey},
					'text': grupyData.dane.tytul.toLowerCase().capitalizeFirstLetter(),
					'id': 'grupa_id=' + grupyKey,
					'a_attr': {
						'href': '#',
						'data-item': 'kategoria_id=' + itemKey + '&grupa_id=' + grupyKey
					},
					'children': true
				}, grupyChildren = [];

				/** @namespace grupyData.podgrupy */
				$.each(grupyData.podgrupy, function (podgrupyKey, podgrupyData) {
					var podgrupy;
					podgrupy = {
						data: {'id': podgrupyKey},
						'text': podgrupyData.dane.tytul.toLowerCase().capitalizeFirstLetter(),
						'id': 'link_id=' + podgrupyKey,
						'a_attr': {
							'href': '/dane/bdl_wskazniki/' + podgrupyKey,
							'data-item': 'kategoria_id=' + itemKey + '&grupa_id=' + grupyKey + '&link_id=' + podgrupyKey,
							'target': '_self'
						}
					};
					grupyChildren.push(podgrupy);
				});

				grupy.children = grupyChildren;
				itemChildren.push(grupy);
			});

			item.children = itemChildren;
			rootData.push(item);
		});

		tree.jstree({
			'core': {
				'data': rootData
			},
			"json_data": {
				"progressive_render": true
			},
			"plugins": ["themes", "json_data", "ui"]
		}).bind("select_node.jstree", function (e, item) {
			e.preventDefault();

			if (item.node.a_attr.href.charAt(0) === '#') {
				tree.jstree("toggle_node", item.node.id);
				self.treeFocusActive();
			} else {
				if (History.pushState !== undefined) {
					var obj = {Page: item.node.text, Url: item.node.a_attr.href, item: item.node.a_attr['data-item']};
					History.pushState(obj, obj.Page, obj.Url, obj.item);
				}
				self.headerUpdate(item.node.data.id, item.node.text, item.node.a_attr.href);
				self.itemLoad(item);
			}

			self.treeScrollReload();
		}).bind("loaded.jstree", function () {
			var $treeBlock = $('.treeBlock');

			if (self.variable.link) {
				self.treeFocus();
			}

			$leftSideAccordion.removeClass('init').find('h3 span').remove();

			$leftSideAccordion.find('.accordion').accordion({
				heightStyle: "fill",
				create: function () {
					$treeBlock.css('height', $('.noOverflow').outerHeight() - $('.noOverflow .suggesterBlock').outerHeight() - 5);
					$('.jScrollPane').jScrollPane();
					self.treeScrollReload();
				}
			});

			self.setup();

			$(window).resize(function () {
				$treeBlock.css('height', 'auto');
				$leftSideAccordion.find('.accordion').accordion("refresh");
				$treeBlock.css('height', $('.noOverflow').outerHeight() - $('.noOverflow .suggesterBlock').outerHeight() - 5);
				self.treeScrollReload();
			});
		});

		if ($tempItemOpisModal.length) {
			var editor = $('#temp_item_opis_modal #editor');

			editor.wysihtml5.locale['pl-PL'].emphasis = {
				bold: "B",
				italic: "I",
				underline: "U"
			};

			editor.wysihtml5({
				toolbar: {
					"font-styles": true,
					"emphasis": true,
					"lists": false,
					"html": false,
					"link": true,
					"image": false,
					"color": false,
					"blockquote": false
				},
				'locale': 'pl-PL',
				parser: function (html) {
					return html;
				}
			});

			$(".import_input").bind('click', function () {
				$(".input_url").removeClass('hidden');
			});

			$(".bdl_input").bind('click', function () {
				$(".input_url").addClass('hidden');
			});

			$(".lista_wskz li")
				.bind('mouseenter', function () {
					$(this).find(".remove_btn").removeClass('hidden');
				})
				.bind('mouseleave', function () {
					$(this).find(".remove_btn").addClass('hidden');
				});

			$("#temp_item_savebtn").click(function () {
				$('#temp_item_opis_modal').modal('hide');
			});

			$("#new_temp_item").click(function () {
				$('#temp_item_opis_modal').modal('show');
			});
		}
	};
	this.treeScrollReload = function () {
		var self = this,
			api = $('.jScrollPane').data('jsp');
		if (api) {
			setTimeout(function () {
				api.reinitialise();
				if (self.variable.jsScrollTarget !== null) {
					clearTimeout(self.variable.jsScrollHandler);
					self.variable.jsScrollHandler = setTimeout(function () {
						var linkPos = $('a[id="' + self.variable.jsScrollTarget + '_anchor"]').parents('li').offset().top - $('.suggesterBlock').outerHeight() - ($('.noOverflow').outerHeight() / 2);
						api.scrollTo(0, linkPos, 'ease');
						self.variable.jsScrollTarget = null;
					}, 250);
				}
			}, 200);
		}
	};
	this.treeFocus = function () {
		var self = this,
			tree = $('#tree');

		if (self.variable.link) {
			for (var i = 0; i < self.variable.link.length; i++) {
				var link = self.variable.link[i];

				if (link !== undefined || link != '#') {
					if (link.match("link_id=")) {
						$('a[id="' + link + '_anchor"]').addClass('jstree-clicked');
					} else {
						tree.jstree("open_node", link);
					}
					self.variable.jsScrollTarget = self.variable.link[self.variable.link.length - 1];
					self.treeScrollReload();
				}
			}
		}
	};
	this.treeFocusActive = function () {
		var self = this,
			activeLink = History.getState().data.item || self.variable.link || false;

		if (typeof(activeLink) === "string") {
			activeLink = activeLink.split('&');
		}

		if (activeLink) {
			var link = activeLink[activeLink.length - 1];

			if (link.match("link_id=")) {

				$('a[id="' + link + '_anchor"]').addClass('jstree-clicked');
			}
		}
	};
	this.itemLoad = function (item) {
		var self = this;

		$.ajax({
			url: item.node.a_attr.href + '.html',
			type: "GET",
			dataType: "html",
			beforeSend: function () {
				self.loading();
			},
			complete: function (res) {
				var bdlBlock = $('#bdl_wskaznik_block'),
					html = res.responseText;

				if (bdlBlock.length)
					bdlBlock.replaceWith(html);
				else
					$(html).appendTo('#_main .objectsPage .objectsPageWindow .objectsPageContent');

				self.itemInit(item);
				self.loaded();
			}
		})
	};
	this.itemInit = function (item) {
		var self = this,
			bdlWskazniki = jQuery('#bdl-wskazniki'),
			wskazniki = bdlWskazniki.find('.wskaznik'),
			filters = $('#filters_form');

		if (filters.length) {
			filters.find('.filter .value > select').each(function () {
				var filter = $(this);

				filter.on('change', function () {
					var item = $('#tree .jstree-clicked'),
						filterSerialize = filters.serialize();

					$.ajax({
						url: item.attr('href') + '.html?' + filterSerialize,
						type: "GET",
						dataType: "html",
						beforeSend: function () {
							self.loading();
						},
						complete: function (res) {
							if (History.pushState !== undefined) {
								var obj = {
									Page: item.text(),
									Url: item.attr('href') + '?' + filterSerialize,
									item: item.attr('data-item')
								};
								History.pushState(obj, obj.Page, obj.Url, obj.item);
							}
							var bdlBlock = $('#bdl_wskaznik_block'),
								html = res.responseText;

							if (bdlBlock.length)
								bdlBlock.replaceWith(html);
							else
								$(html).appendTo('#_main .objectsPage .objectsPageWindow .objectsPageContent');

							self.itemInit();
							self.loaded();
						}
					})
				})
			});
		}

		if (wskazniki.length) {
			wskazniki.each(function () {
				var el = $(this),
					data = el.data('years');

				el.find('h2>a').click(function (e) {
					var that = $(this),
						text = that.text().capitalizeFirstLetter(),
						href = that.attr('href');

					e.preventDefault();

					if (History.pushState !== undefined) {
						var obj = {Page: text, Url: href};
						History.pushState(obj, obj.Page, obj.Url);
					}

					self.headerUpdate('', text, href);
					if (!that.hasClass('subItemLoaded')) {
						that.addClass('subItemLoaded');
						self.subitemLoad(el);
					}
				});

				if (data) {
					var chart_div = el.find('.chart'),
						label = [],
						value = [];

					jQuery.each(data, function () {
						label.push(this[0]);
						value.push(Number(this[1]));
					});

					chart_div.highcharts({
						title: {
							text: ''
						},
						chart: {
							backgroundColor: null
						},
						credits: {
							enabled: false
						},
						xAxis: {
							categories: label
						},
						yAxis: {
							title: ''
						},
						tooltip: {
							valueSuffix: ''
						},
						legend: {
							enabled: false,
							align: 'left'
						},
						series: [
							{
								name: "Wartość",
								data: value
							}
						]
					});
				}
			});
		}

		$('.bdl-select select').selectpicker();
	};
	this.subitemLoad = function (item) {
		var self = this;

		$.ajax({
			url: '/dane/bdl_wskazniki/' + item.attr('data-id') + '/kombinacje/' + item.attr('data-dim_id') + '.html',
			type: "GET",
			dataType: "html",
			beforeSend: function () {
				self.loading();

				item.find('.map').fadeOut(function () {
					item.find('.charts').animate({
						width: '100%'
					}, {
						duration: 600,
						step: function () {
							item.find('.chart').highcharts().reflow()
						}
					});
				});
			},
			complete: function (res) {
				var bdlDetail = item.find('.bdl-details'),
					html = res.responseText;

				if (bdlDetail.length)
					bdlDetail.replaceWith(html);
				else
					item.append(html);

				self.subitemInit();
				self.loaded();
			}
		});
	};
	this.subitemInit = function () {
		var bdlWskazniki = jQuery('#bdl-wskazniki'),
			wskazniki = bdlWskazniki.find('.wskaznik'),
			wskaznikiTable,
			geoType;

		if (typeof(local_data) == 'undefined' || local_data == undefined || local_data.length == 0)
			return false;

		geoType = local_data.length > 0 ? local_data.length > 16 ? local_data.length > 380 ? 'gminy' : 'powiaty' : 'wojewodztwa' : false;

		if (!geoType)
			return false;

		$.getJSON(mPHeart.constant.ajax.api + '/geo/geojson/get?quality=4&types=' + geoType, function (data) {
			var geo = Highcharts.geojson(data, 'map');
			var max = 0, min = 9999999999;
			for (var i = 0; i < geo.length; i++) {
				var found = false;
				for (var k = 0; k < local_data.length; k++) {
					if (geo[i].properties.id == local_data[k].local_id) {
						geo[i].value = parseFloat(local_data[k].lv);
						geo[i].id = 'o' + geo[i].properties.id;
						found = true;
						break;
					}
				}
				if (!found)
					geo[i].value = 0;

				if (geo[i].value > max)
					max = geo[i].value;

				if (geo[i].value < min)
					min = geo[i].value;
			}

			var type = 'linear';
			if (min == 0 && max == 0)
				max = 1;

			var highmap = $('#highmap');
			highmap.css('height', '85vh');

			highmap.highcharts('Map', {
				title: {
					text: ' '
				},
				chart: {
					backgroundColor: null
				},
				mapNavigation: {
					enabled: true,
					enableMouseWheelZoom: false,
					buttonOptions: {
						verticalAlign: 'bottom'
					}
				},
				credits: {
					enabled: false
				},
				legend: {
					enabled: false
				},
				tooltip: {
					pointFormat: '{point.name}: {point.value} ' + (this.unit !== undefined ? this.unit : unitStr),
					headerFormat: ''
				},
				colorAxis: {
					minColor: '#ffffff',
					maxColor: '#006df0',
					min: min,
					max: max,
					type: type
				},
				plotOptions: {
					series: {
						//allowPointSelect: true,
						point: {
							events: {
								mouseOver: function () {
									this.graphic.toFront();
								},
								mouseOut: function () {

								},
								select: function () {
									this.graphic.toFront();
								},
								click: function () {
									var index = this.index + 1;
									$('tr.wskaznikStatic').each(function () {
										var _this = $(this);
										var _index = $(this).attr('data-local_id');
										if (_index == index) {
											$('html, body').animate({
												scrollTop: _this.offset().top
											}, 1000);
											if (!_this.hasClass('clicked'))
												_this.click();
											return true;
										}

										_this
											.removeClass('clicked')
											.find('.wskaznikChart')
											.hide();
									});
								}
							}
						},
						states: {
							select: {
								borderColor: '#014068',
								borderWidth: 1
							},
							hover: {
								borderColor: '#014068',
								borderWidth: 1,
								brightness: false,
								color: false
							}
						}
					}
				},
				series: [{
					data: geo,
					nullColor: '#ffffff',
					borderWidth: 1,
					borderColor: '#777'
				}]
			});

			$('tr.wskaznikStatic')
				.on("mouseenter", function () {
					var id = parseInt($(this).attr('data-local_id'));
					highmap
						.highcharts()
						.get('o' + id)
						.select();
				})
				.on("mouseleave", function () {
					var id = parseInt($(this).attr('data-local_id'));
					highmap
						.highcharts()
						.get('o' + id)
						.select(false);
				})
				.on("click", function () {
					var wskaznik = $(this),
						wskaznikwidth = $(this).outerWidth(),
						wskaznikData = wskaznik.data(),
						wskaznikChart = wskaznik.find('.wskaznikChart');

					if (wskaznik.hasClass('clicked')) {
						wskaznikChart.hide();
						wskaznik.removeClass('clicked');
						return false;
					}

					wskaznikChart.css({'width': wskaznikwidth});

					$.ajax({
						url: '/dane/bdl_wskazniki/local_chart_data_for_dimmensions.json?dims=' + wskaznikData.dim_id + '&localtype=' + wskaznikData.local_type + '&localid=' + wskaznikData.local_id,
						type: "POST",
						dataType: "json",
						beforeSend: function () {
							wskaznikChart.slideDown();
							wskaznikChart.find('.chart .progress-bar').attr('aria-valuenow', '45').css('width', '45%');
						},
						always: function () {
							wskazniki.find('.chart .progress-bar').attr('aria-valuenow', '80').css('width', '80%');
						},
						complete: function (res) {
							var data = res.responseJSON.data;

							var chart = data,
								label = [],
								value = [];

							$.each(chart, function () {
								label.push(this.y);
								value.push(Number(this.v));
							});

							wskaznikChart.highcharts({
								title: {
									text: ''
								},
								chart: {
									height: 150
								},
								credits: {
									enabled: false
								},
								xAxis: {
									categories: label
								},
								yAxis: {
									title: ''
								},
								tooltip: {
									valueSuffix: ''
								},
								legend: {
									enabled: false,
									align: 'left'
								},
								series: [
									{
										name: unitStr,
										data: value
									}
								]
							});
						}
					});

					wskaznik.addClass('clicked');
				});

			wskaznikiTable = bdlWskazniki.find('.localDataTable tbody');

			$('.localDataSearch input').keyup(function () {
				var input = $(this).val();

				if (input != '') {
					wskaznikiTable.find('tr').hide();
					wskaznikiTable.find('td:contains(' + input + ')').parent().show();
					wskaznikiTable.find('[data-ay-sort-weight*="' + input + '"]').parent().show();
				} else {
					wskaznikiTable.find('tr:hidden').show();
				}
			});

			$('.localDataSearch .btn').click(function (e) {
				e.preventDefault();
				$('.localDataSearch input').val('');
				wskaznikiTable.find('tr:hidden').show();
			});

		});

		$('.bdl-select select').selectpicker();
	};

	this.headerUpdate = function (id, text, href) {
		var header = $('.appHeader');

		if (header.length) {
			header.attr({
				'data-object_id': id || '',
				'data-url': href || '#'
			}).find('a.trimTitle').attr({
				'title': text,
				'href': href || '#'
			}).find('span[itemprop="name"]').get(0).lastChild.nodeValue = text;
		}
	};

	this.loading = function () {
		var main = $('#_main'),
			w = main.find('.objectsPage').width();

		if (main.find('.curtain').length == 0) {
			main.append(
				$('<div></div>').addClass('curtain loading').css({
					'background-color': 'rgba(255, 255, 255, .7)',
					'background-position': Math.floor(w / 2) + 'px 50%',
					'width': w,
					'height': '100%',
					'display': 'block',
					'left': '385px',
					'position': 'fixed',
					'top': 0,
					'z-index': 2

				})
			);
		}
	};

	this.loaded = function () {
		$('#_main').find('.curtain').remove();
	};


	this.init();
};

(function ($) {
	var _bdl_app = new BDLapp();
}(jQuery));