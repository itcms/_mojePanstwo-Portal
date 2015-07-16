/*global $,JQuery, mPHeart, google*/
var googleMap,
    geolocalizateMe,
    markers = [];

function initialize() {
    var polandLatlng = new google.maps.LatLng(51.919438, 19.145136),
        mapOptions = {
            zoom: 6,
            center: polandLatlng
        },
        markerImage = {
            url: 'http://maps.gstatic.com/mapfiles/place_api/icons/geocode-71.png',
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(25, 25)
        };

    googleMap = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

    google.maps.event.addListenerOnce(googleMap, 'idle', function () {
        $('.googleBtn').fadeIn();
        $('.googleMapElement').addClass('loaded');
    });



    google.maps.event.addListener(googleMap, "click", function (event) {
        clearMarkers();
        markers = [];

        var marker = new google.maps.Marker({
            map: googleMap,
            icon: markerImage,
            position: event.latLng
        });

        markers.push(marker);
    });

    var input = document.getElementById('pac-input');
    googleMap.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var searchBox = new google.maps.places.SearchBox(input);
    google.maps.event.addListener(searchBox, 'places_changed', function () {
        var places = searchBox.getPlaces();

        if (places.length == 0) {
            return;
        }
        for (var i = 0, marker; marker = markers[i]; i++) {
            marker.setMap(null);
        }

        clearMarkers();
        markers = [];

        var bounds = new google.maps.LatLngBounds();
        for (var i = 0, place; place = places[i]; i++) {
            var marker = new google.maps.Marker({
                map: googleMap,
                icon: markerImage,
                title: place.name,
                position: place.geometry.location
            });

            markers.push(marker);
            bounds.extend(place.geometry.location);
        }
        googleMap.fitBounds(bounds);
    });

    var googleMapBlock = $('.googleMapElement'),
        lat = parseFloat(googleMapBlock.find('input[name="geo_lat"]').val()),
        lng = parseFloat(googleMapBlock.find('input[name="geo_lng"]').val());

    if(lat > 0 && lng > 0) {
        clearMarkers();
        markers = [];

        var marker = new google.maps.Marker({
            map: googleMap,
            icon: markerImage,
            position: {lat: lat, lng: lng}
        });

        markers.push(marker);
    }

    google.maps.event.addListener(googleMap, 'bounds_changed', function () {
        var bounds = googleMap.getBounds();
        searchBox.setBounds(bounds);
    });

    geolocalizateMe = function () {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

                clearMarkers();
                markers = [];

                var bounds = new google.maps.LatLngBounds();
                var marker = new google.maps.Marker({
                    map: googleMap,
                    icon: markerImage,
                    position: pos
                });

                markers.push(marker);

                bounds.extend(pos);
                googleMap.fitBounds(bounds);
            }, function () {
                handleNoGeolocation(true);
            });
        } else {
            // Browser doesn't support Geolocation
            handleNoGeolocation(false);
        }

        function handleNoGeolocation(errorFlag) {
            var content = '';

            if (errorFlag) {
                content = 'Błąd: System geolokalizacji nie odpowiada.';
            } else {
                content = 'Błąd: Twoja przeglądarka nie wspiera geolokalizacji.';
            }

            var options = {
                map: googleMap,
                position: polandLatlng,
                content: content,
                zoom: 6
            };

            var infowindow = new google.maps.InfoWindow(options);
            googleMap.setCenter(options.position);
        }
    };
}

function clearMarkers() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(null);
    }
}


//ASYNC INIT GOOGLE MAP JS//
function loadScript() {
    if ((typeof google !== "undefined") && google.maps) {
        initialize();
    } else {
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = 'https://maps.googleapis.com/maps/api/js?sensor=false&language=' + mPHeart.language.twoDig + '&libraries=places&callback=initialize';
        document.body.appendChild(script);
    }
}

$(document).ready(function () {
    var objectMain = $('.objectMain'),
        form = objectMain.find('form'),
        imageEditor = objectMain.find('.image-editor'),
        imageAlert = imageEditor.find('.alert.alert-danger'),
        imageChoosed = imageEditor.find('input[name="cover_photo"]'),
        googleBtn = $('.googleBtn'),
        googleLocMeBtn = $('#loc'),
        googleMapBlock = $('.googleMapElement'),
        header = $('.appHeader.dataobject').first(),
        dataset = header.attr('data-dataset'),
        object_id = header.attr('data-object_id'),
        opis = $('#dzialanieOpis');

        /*opis.wysihtml5({
            toolbar: {
                "font-styles": true, //Font styling, e.g. h1, h2, etc.
                "emphasis": true, //Italics, bold, etc.
                "lists": false, //(Un)ordered lists, e.g. Bullets, Numbers.
                "html": false, //Button which allows you to edit the generated HTML.
                "link": true, //Button to insert a link.
                "image": false, //Button to insert an image.
                "color": false, //Button to change color of font
                "blockquote": false
            },
            'locale': 'pl-NEW',
            parser: function (html) {
                return html;
            }
        });*/

    tinymce.init({
        selector: "#dzialanieOpis",
        language : 'pl',
        plugins: "media image",
        menubar: "edit format edit insert",
        valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|"
        + "onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|"
        + "onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
        + "name|href|target|title|class|onfocus|onblur],strong/b,em/i,strike,u,"
        + "#p,-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
        + "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
        + "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
        + "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
        + "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
        + "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
        + "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,"
        + "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
        + "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
        + "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
        + "|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,"
        + "button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
        + "valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],"
        + "input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],"
        + "kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],"
        + "q[cite],samp,select[disabled|multiple|name|size],small,"
        + "textarea[cols|rows|disabled|name|readonly],tt,var,big,"
        + "iframe[src|title|width|height|allowfullscreen|frameborder]"
    });

        cropItErrorMsg = function () {
            if (mPHeart.language.twoDig == 'pl') {
                if (error.code === 0) {
                    error.message = 'Błąd ładowowania zdjęcia - proszę spróbować inne.'
                } else if (error.code === 1) {
                    error.message = 'Zdjęcie nie spełnia zalecanej wielkości.'
                }
            }

            if (alert.length) {
                alert.text(error.message);
            } else {
                imageAlert = $('<div></div>').addClass('alert alert-danger').text(error.message);

                el.find('.image-editor').prepend(
                    imageAlert.slideDown()
                );
            }
        };


    if (imageEditor.length) {
        var imageWidth = 874,
            imageHeight = 347,
            imgEditorWidth = imageEditor.width(),
            imgEditorHeight = imageHeight * (imageEditor.width() / imageWidth),
            exportZoom = imageWidth / imageEditor.width();

        imageEditor.css({'width': imgEditorWidth, height: imgEditorHeight}).cropit({
            imageState: {
                src: (imageChoosed.val() !== "") ? imageChoosed.val() : ''
            },
            width: imgEditorWidth,
            height: imgEditorHeight,
            exportZoom: exportZoom,
            onImageLoaded: function () {
                imageEditor.find('.alert').slideUp("normal", function () {
                    $(this).remove();
                });
            },
            onFileReaderError: function (evt) {
                cropItErrorMsg(evt);
            },
            onImageError: function (evt) {
                cropItErrorMsg(evt);
            }
        });
        objectMain.find('.submitBtn').click(function (e) {
            e.preventDefault();
            imageChoosed.val(imageEditor.cropit('export', {
                type: 'image/jpeg',
                quality: .9
            }));

            if (markers.length) {
                googleMapBlock.find('input[name="geo_lat"]').val(markers[0].getPosition().lat());
                googleMapBlock.find('input[name="geo_lng"]').val(markers[0].getPosition().lng());
            }

            objectMain.find('form').submit();
        })
    }
    googleBtn.click(function () {
        var $pac = $('#pac-input');

        googleMapBlock.slideToggle();
        $('#loc').css('left', $pac.position().left + $pac.outerWidth() - 8);
        $('html, body').animate({
            scrollTop: $('#googleMap').offset().top
        });
    });

    googleLocMeBtn.click(function () {
        geolocalizateMe();
    });

    form.submit(function () {
        tinyMCE.triggerSave();
        var self = $(this);
        var inputs = $(this).serializeArray();

        var id = 0;
        for(var i = 0; i < inputs.length; i++)
            if(inputs[i].name == 'id') {
                id = parseInt(inputs[i].value);
                inputs.splice(i, 1);
            }

        var cropitFields = ['imageSrc', 'offset', 'zoom'];
        for(var m = 0; m < cropitFields.length; m++) {
            var v = cropitFields[m];
            if(v == 'offset') {
                inputs.push({
                    name: 'x',
                    value: imageEditor.cropit(v).x
                });

                inputs.push({
                    name: 'y',
                    value: imageEditor.cropit(v).y
                });
            } else {
                inputs.push({
                    name: v == 'imageSrc' ? 'cover_photo' : v,
                    value: imageEditor.cropit(v)
                });
            }
        }

        $.ajax({
            url: '/dane/' + dataset + '/' + object_id + '/dzialania' + (id > 0 ? '/' + id : '') + '.json',
            method: id > 0 ? 'PUT' : 'POST',
            data: inputs,
            beforeSend: function () {
                self.find('.submitBtn').addClass('loading disabled')
            },
            success: function (res) {

                if(res.success && id) {
                    window.location = '/dane/' + dataset + '/' + object_id + '/dzialania/' + id;
                } else {
                    window.location = '/dane/' + dataset + '/' + object_id + '/dzialania/' + res.success;
                }

            }
        });

        return false;
    });

    $('.cancelBtn').click(function() {
        window.location = '/dane/' + dataset + '/' + object_id;
    });

    $('.btn[data-action="delete"]').click(function() {
        var id = $(this).data('id');
        if(confirm("Czy na pewno chcesz usunąć to działanie?")) {
            $.ajax({
                url: '/dane/' + dataset + '/' + object_id + '/dzialania/' + id + '.json',
                method: 'DELETE',
                data: [],
                success: function(res) {
                    window.location = '/dane/' + dataset + '/' + object_id;
                }
            });
        }
    });

    /* Tags autocomplete input */
    $(function() {
        $('.tags input.tagit').tagit({
            allowSpaces: true,
            removeConfirmation: true,
            autocomplete: {
                source: function( request, response ) {
                    $.getJSON("/dane/tematy.json?term=" + request.term, function(res) {
                        var data = [];
                        for(var i = 0; i < res.length; i++)
                            data.push(res[i].label);

                        response(data);
                    });
                },
                minLength: 3
            }
        });
    });

    /*ASYNCHRONIZE ACTION FOR GOOGLE MAP*/
    window.onload = loadScript();
});