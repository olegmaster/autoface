@extends('layouts.app')

@section('content')
<div class="container">
        <div class="col-md-12">
            <div id="large-image-window">
                <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <img src="" id="large-image" >
            </div>
            <div id="affilate-device-window">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="close" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h3>Пользователь для аффилиации:</h3>
                        <br/>
                    </div>
                    <div class="col-md-12">
                        <div id="get-user-message"></div>
                        <input type="text" id="user-for-affiliation" placeholder="логин" />
                        <input type="button" class="btn btn-success" id="search-user" value="Поиск" />
                        <input type="hidden" id="user-value" />
                        <div id="users-affiliation-list"></div>
                        <br/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row set-time-interval">
                            <div class="col-md-3"></div>
                            <div class="col-md-6"><p>Время аффилиации (если оставить пустым то на все время)</p></div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <input id="affiliation_date_from" type="date">&nbsp;
                            </div>
                            <div class="col-md-3">
                                <input id="affiliation_time_from" type="time">
                            </div>
                            <div class="col-md-3">
                                <input id="affiliation_date_to" type="date">&nbsp;
                            </div>
                            <div class="col-md-3">
                                <input id="affiliation_time_to" type="time">
                            </div>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-md-6">
                        Добавить зону аффилиации
                    </div>
                    <div class="col-md-6">
                        <input type="checkbox" id="add-zone-or-not">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <input type="button" class="btn btn-success" id="add-affiliation" value="Аффилировать устройство" />
                    </div>
                </div>

            </div>
            <div id="map" style="height:700px;width:130%;"></div>

            <script>


                let userFinder = new class {
                    constructor() {
                        this.buttonSelector = 'search-user';
                        this.searchFieldSelecor = 'user-for-affiliation';
                        this.userValueSelector = 'user-value';
                        this.userSearchMessageSelector = 'get-user-message';
                        this.userFinded = false;


                        this.onclickSearchHandle();
                    }

                    onclickSearchHandle(){
                        document.onreadystatechange = () => {
                            if(document.readyState === 'complete'){
                                document.getElementById(this.buttonSelector).onclick = () => {
                                    this.getUserFromServer();
                                };
                            }
                        }
                    }

                    getUserLogin(){
                        let login = document.getElementById(this.searchFieldSelecor).value;
                        return login;
                    }

                    getUserFromServer(){

                        let user = {};
                        let login = this.getUserLogin();

                        if(login === ''){
                            this.userFinded = false;
                            this.showMessage('Пользователь не найден');
                            return false;
                        }

                        let url = '/user/find/' + login;
                        let xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject('Microsoft.XMLHTTP');
                        xhr.open('GET', url);
                        xhr.async = false;
                        xhr.onreadystatechange = () => {
                            if( xhr.readyState == 3 && xhr.status == 200){
                                user = JSON.parse(xhr.responseText);

                                if(this.userIsEmpty(user)) {
                                    this.userFinded = false;
                                    this.showMessage('Пользователь не найден');
                                    return false;
                                }

                                this.userFinded = true;
                                this.storeUser(user.id);
                                this.showMessage('Пользователь найден');

                            }
                        };

                        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        xhr.send();

                        return true;

                    }

                    showMessage(message){
                        document.getElementById(this.userSearchMessageSelector).innerHTML = message;
                    }

                    storeUser(userLogin){
                        document.getElementById(this.userValueSelector).value  = userLogin;
                    }

                    userIsEmpty(user){
                        if(Object.keys(user).length === 0 && user.constructor === Object) {
                            return true;
                        }
                        return false;
                    }

                    getUserLoginChecked(){
                        let userLogin = document.getElementById(this.userValueSelector).value;
                        if( userLogin === ''){
                            return false;
                        } else{
                            return userLogin;
                        }
                    }





                };



                var arrOfStyles = [{
                    "featureType": "all",
                    "elementType": "labels",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "saturation": 36
                            },
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 40
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "visibility": "on"
                            },
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "all",
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "visibility": "on"
                            },
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.country",
                        "elementType": "labels.text.fill",
                        "stylers": [

                            {
                                "visibility": "on"
                            },
                            {
                                "color": "#e5c163"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.locality",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "visibility": "on"
                            },
                            {
                                "color": "#c4c4c4"
                            }
                        ]
                    },
                    {
                        "featureType": "administrative.neighborhood",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#e5c163"
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 21
                            },
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "poi.business",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "visibility": "on"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#42a378"
                            },
                            {
                                "lightness": "0"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#e5c163"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 18
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#575757"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "color": "#2c2c2c"
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "color": "#999999"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 19
                            }
                        ]
                    },
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#000000"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    }];


                var affiliator = {};

                affiliator.init = function(){
                    this.closeClickListen();
                    this.askAffiliationsForConfirm();
                };

                affiliator.showAffiliatorWindow = function() {
                    $('#affilate-device-window').css('opacity', 1);
                    $('#affilate-device-window').css('z-index', '2');
                };

                affiliator.hideAffiliatorWindow = function() {
                    $('#affilate-device-window').css('opacity', 0);
                    $('#affilate-device-window').css('z-index', 0);
                };

                affiliator.closeClickListen = function() {
                    $(document).ready(() => {
                        $('#affilate-device-window .close').click(() => {
                            this.hideAffiliatorWindow();
                        })
                    });
                };

                affiliator.addZone = function(){
                    let checkbox = document.getElementById('add-zone-or-not');
                    if(checkbox.checked){
                        return true;
                    } else {
                        return false;
                    }
                };

                affiliator.doTimeFrom = function(date_from, time_from){
                    if(date_from === ''){
                        return '2000-01-01 00:00:00';
                    } else if(time_from === ''){
                        return date_from + ' 00:00:00';
                    } else {
                        return date_from + ' ' + time_from + ':00';
                    }
                };

                affiliator.doTimeTo = function(date_to, time_to){
                    if(date_to === ''){
                        return '2050-01-01 00:00:00';
                    } else if(time_to === ''){
                        return date_to + ' 00:00:00';
                    } else {
                        return date_to + ' ' + time_to + ':00';
                    }
                };

                affiliator.getTime = function(){
                    let date_from = $('#affiliation_date_from').val();
                    let time_from = $('#affiliation_time_from').val();
                    this.time_from = this.doTimeFrom(date_from, time_from);

                    let date_to = $('#affiliation_date_to').val();
                    let time_to = $('#affiliation_time_to').val();
                    this.time_to = this.doTimeTo(date_to, time_to);
                };







                affiliator.checkDeviceIsSelected = function() {
                    if(imageLoader.device !== undefined){
                        return true;
                    } else{
                        return false;
                    }
                };

                affiliator.askAffiliationsForConfirm = function() {
                    let self = this;
                    let affiliations = this.getAffiliationsForConfirm();
                    affiliations.forEach(function(item){
                        let confirmed = confirm('user ' + item.user_id + ' ask to affiliate device ' + item.device_id);
                        if(confirmed){
                            self.acceptAffiliation(item.id);
                        } else {
                            self.rejectAffiliation(item.id);
                        }
                    });
                };

                affiliator.getAffiliationsForConfirm = function() {
                    let affiliatons = [];
                    $.ajax({
                        type: 'GET',
                        url: '/get-affilations-for-confirm',
                        async: false,
                        success: function(data){
                            affiliatons = data;
                        }
                    });

                    return affiliatons;
                };

                affiliator.acceptAffiliation = function(affiliation_id){
                    $.ajax({
                        type: 'GET',
                        url: '/confirm-affiliation/' + affiliation_id,
                        success: function(data){
                        }
                    });
                };

                affiliator.rejectAffiliation = function(affiliation_id){
                    $.ajax({
                        type: 'GET',
                        url: '/reject-affiliation/' + affiliation_id,
                        success: function(data){
                        }
                    });
                };

                affiliator.getAllUsers = function() {
                    let self = this;
                    $.ajax({
                            type: 'GET',
                            url: '/users/get/all',
                            success: function(data){
                                //console.log(data);
                                let usersCheckbox = self.createUsersCheckbox(data);
                                self.appendUsersCheckbox(usersCheckbox);
                            }
                     });
                };

                affiliator.createUsersCheckbox = function(userData) {
                    let checkbox = '<select name="affiliated_user">';
                    userData.forEach(function(elem){
                        checkbox += '<option value="' + elem.id + '">' + elem.name + '</option>';
                    });
                    checkbox += '</select>';
                    return checkbox;
                };

                affiliator.appendUsersCheckbox = function(checkbox){
                    $('#users-affiliation-list').append(checkbox);
                };

                affiliator.getAffiliatedDevices = function(){
                    let affiliatedDevices = [];
                    $.ajax({
                        type: 'GET',
                        url: '/get-affiliated-devices',
                        async: false,
                        success: function(data){
                            console.log(data);
                            affiliatedDevices = data;
                        }
                    });
                    return affiliatedDevices;
                };

                affiliator.init();


                function initMap() {

                    let positionByTimeShow = new class {

                        constructor(){
                            this.imageSelector = '#images-container img';
                            this.imageId = null;
                            this.marker = null;
                            this.positionLatitude = null;
                            this.positionLongitude = null;
                        }

                        doThis(){
                            this.catchHowerImage();
                            this.catchOutImage();
                        }

                        catchHowerImage(){
                            let self = this;
                            $(document).on('mouseenter', this.imageSelector, function(){
                                self.imageId = this.dataset.id;
                                self.getPositionFromServer();

                                self.marker = addImageMarker(self.positionLatitude, self.positionLongitude);
                            });
                        }

                        catchOutImage(){
                            let self = this;
                            $(document).on('mouseleave', this.imageSelector, function(){
                                self.imageId = null;
                                this.positionLatitude = null;
                                this.positionLongitude = null;
                                self.cleanMarker();
                            })
                        }

                        getPositionFromServer(){
                            let self = this;
                            let url = '/api-get-position-by-image/' + self.imageId;
                            $.ajax({
                                type: 'GET',
                                url: url,
                                async: false,
                                success: function(data){
                                    self.positionLatitude = data.latitude;
                                    self.positionLongitude = data.longitude;
                                }
                            });
                        }

                        addPositionMarker(){
                            if(this.positionLatitude === null || this.positionLongitude === null){
                                return false;
                            }
                            this.marker = addImageMarker(this.positionLatitude, this.positionLongitude);
                        }

                        cleanMarker(){
                            if(this.marker === null){
                                return false;
                            }
                            this.marker.setMap(null)
                        }

                    };

                    positionByTimeShow.doThis();

                    var uluru = {lat: 49.98884148, lng: 36.22041411};
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 14,
                        center: uluru,
                        styles: arrOfStyles
                    });


                    var allZones = [];
                    var allMarkers = [];

                    loadAllDevices();

                    loadAffiliatedDevices();

                    loadZonesOnClickDevice();

                    loadDeviceOnCLickDevice();

                    addZoneClick();

                    function loadZonesOnClickDevice(){
                        $('.devices-list').click(function(){
                            var device_id = this.dataset.id;
                            loadZones(device_id);
                        });

                    }

                    function loadAllDevices(){
                        askServerForDevices();
                    }

                    function loadAffiliatedDevices(){
                        let affiliatedDevices = affiliator.getAffiliatedDevices();
                        if(typeof affiliatedDevices === "object") {
                            affiliatedDevices.forEach(function(item){
                                addAffiliatedMarker(item.latitude, item.longitude);
                                console.log(item);
                            });
                        }

                    }

                    function askServerForDevices(){
                        $.ajax({
                            type: 'GET',
                            url: '/get-all-device-for-map',
                            success: function(data){
                                console.log(data);
                                data.forEach(function(elem){
                                    mapAddMarker(elem.latitude, elem.longitude);
                                });
                            }
                        });
                    }

                    function loadDeviceOnCLickDevice(){
                        $('.devices-list').click(function(){
                            cleanMarkers();
                            var device_id = this.dataset.id;
                            loadDeviceMarker(device_id);
                        });
                    }

                    function loadDeviceMarker(device_id){
                        $.ajax({
                            type: 'GET',
                            url: '/device/get-for-map/' + device_id,
                            success: function(data){
                                mapAddMarker(data.latitude, data.longitude);
                            }
                        });
                    }



                    function mapAddMarker(lat, lng){

                        var marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
                            map: map
                        });
                        //console.log(marker);
                        //marker.setAnimation(google.maps.Animation.BOUNCE);
                        allMarkers.push(marker);
                    }

                    function cleanMarkers(){
                        for (var i = 0; i < allMarkers.length; i++) {
                            allMarkers[i].setMap(null);
                        }
                    }

                    function addAffiliatedMarker(lat, lng){
                        var marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
                            map: map
                        });
                        //console.log(marker);
                        marker.setAnimation(google.maps.Animation.BOUNCE);
                        allMarkers.push(marker);
                    }

                    function addImageMarker(lat, lng){
                        let image = '/img/target.png';
                        var marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
                            map: map,
                            icon: image,
                        });
                        return marker;
                    }



                    function addZoneClick(){


                        $(document).ready(() => {

                            $('.add-zone').click(function(){
                                if(!deviceIsSelected()){
                                    alert('Выберите устройство');
                                    return;
                                }
                                affiliator.showAffiliatorWindow();

                            });


                            $('#add-affiliation').click(() => {

                                let affiliated_user = userFinder.getUserLoginChecked();
                                if(!affiliated_user){
                                    alert('Пользователь не найден');
                                    return false;
                                }

                                affiliator.hideAffiliatorWindow();
                                if(affiliator.addZone()){
                                    createZone(affiliated_user);
                                    return;
                                }

                                var zone_id = null;
                                var device_id = imageLoader.device;
                                affiliator.getTime();
                                addAffiliationOnServer(affiliated_user, zone_id, device_id, affiliator.time_from, affiliator.time_to);


                            })
                        });
                    }

                    function loadZones(device_id){

                        clearMap();

                        $.ajax({
                            type: 'GET',
                            url: '/zone/get-all/' + device_id,
                            success: function(data){
                                data.forEach(function(elem){
                                    addZone(elem);
                                });
                            }
                        });
                    }

                    function addZone(zoneData){
                        var cityCircle = createZoneCircle(zoneData);
                        addRadiusChangeListenerToCircle(cityCircle);
                        addCenterChangeListenerToCircle(cityCircle);
                        allZones.push(cityCircle);
                    }

                    function clearMap(){

                        for (var i=0; i < allZones.length; i++)
                        {
                            allZones[i].setMap(null);
                        }
                        allZones = [];
                    }

                    function createZone(affiliated_user){

                        affiliator.getTime();
                        var device_id = imageLoader.device;
                        let affilationId = addAffiliationOnServer(affiliated_user, null, device_id, affiliator.time_from, affiliator.time_to);

                        console.log(affilationId);
                        if(affilationId === null){
                            return;
                        }

                        var cityCircle = createZoneCircle(null);
                        var zoneData = getZoneData(cityCircle);
                        var zone_id = addNewZoneOnServer(zoneData);
                        bindAffiliationZone(affilationId, zone_id);

                        cityCircle.id = zone_id;
                        addRadiusChangeListenerToCircle(cityCircle);
                        addCenterChangeListenerToCircle(cityCircle);
                        allZones.push(cityCircle);
                    }

                    function bindAffiliationZone(affiliation_id, zone_id){

                        let data = {
                            affiliation_id: affiliation_id,
                            zone_id: zone_id,
                        };

                        $.ajax({
                            type:'POST',
                            url:'/bind-affiliation-zone',
                            data: data,
                            async: true,
                            success:function(data){
                                console.log(data);
                            }
                        });
                    }

                    function addAffiliationOnServer(affiliated_user, zone_id, device_id, time_from, time_to){

                        let my_affiliation_id = null;

                        let data = {
                            device_id: device_id,
                            zone_id: zone_id,
                            affiliate_user_id: affiliated_user,
                            time_from: time_from,
                            time_to: time_to
                        };

                        $.ajax({
                            type:'POST',
                            url:'/affiliation/add',
                            data: data,
                            async: false,
                            success:function(data){
                                my_affiliation_id = data;
                                console.log(data);
                            }
                        });

                        return my_affiliation_id;
                    }

                    function createZoneCircle(data){
                        if(data == null){
                            var cityCircle = new google.maps.Circle({
                                strokeColor: '#5555',
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: '#55555',
                                fillOpacity: 0.35,
                                map: map,
                                center: {lat: 49.98631114, lng: 36.22058061},
                                radius: 122,
                                editable: true
                            });
                            cityCircle.id = null;
                            return cityCircle;
                        } else {
                            var cityCircle = new google.maps.Circle({
                                strokeColor: '#5555',
                                strokeOpacity: 0.8,
                                strokeWeight: 2,
                                fillColor: '#55555',
                                fillOpacity: 0.35,
                                map: map,
                                center: {lat: data.latitude, lng: data.longitude},
                                radius: data.radius,
                                editable: true
                            });
                            cityCircle.id = data.id;
                            return cityCircle;
                        }
                    }

                    function getZoneData(circle){
                        var zoneData = {
                            id: circle.id,
                            device_id : imageLoader.device,
                            longitude: circle.getCenter().lng(),
                            latitude: circle.getCenter().lat(),
                            radius: circle.getRadius()
                        }
                        return zoneData;
                    }

                    function addRadiusChangeListenerToCircle(circle){
                        google.maps.event.addListener(circle, 'radius_changed', function(){
                            updateOnServer(circle);
                        });
                    }

                    function addCenterChangeListenerToCircle(circle){
                        google.maps.event.addListener(circle, 'center_changed', function(){
                            updateOnServer(circle);
                        });
                    }

                    function updateOnServer(circle){
                        var zoneData = getZoneData(circle);
                        updateZone(zoneData);
                    }

                    function updateZone(zoneData){
                        $.ajax({
                            type:'POST',
                            url:'/zone/change',
                            data: zoneData,
                            success:function(data){
                                id = data.id;
                            }
                        });
                    }

                    function getDeviceId(){
                        return imageLoader.device;
                    }

                    function deviceIsSelected(){
                        if(imageLoader.device !== undefined){
                            return true;
                        } else{
                            return false;
                        }
                    }

                    function addNewZoneOnServer(zoneData){
                        var id = undefined;
                        var dataAboutZone = {
                            device_id : zoneData.device_id,
                            longitude : zoneData.longitude,
                            latitude : zoneData.latitude,
                            radius: zoneData.radius
                        };
                        $.ajax({
                            type:'POST',
                            url:'/zone/add',
                            data: dataAboutZone,
                            async: false,
                            success:function(data){
                                id = data.id;
                            }
                        });
                        return id;
                    }



                }




                putDeviceOnAlarmClick();

                function putDeviceOnAlarmClick(){

                    $(document).ready(function(){
                        $('.put-to-alarm-box').click(function(e){
                            e.stopPropagation();
                            var device_id = $(this).data('id');
                            if(this.checked){

                                putDeviceOnAlarm(device_id);
                            } else {
                                takeofDeviceOnAlarm(device_id);
                            }
                        });
                    });

                }

                function takeofDeviceOnAlarm(device_id){
                    var data = {
                        id: device_id
                    }
                    $.ajax({
                        type: 'POST',
                        data: data,
                        url: '/takeof-device-on-alarm',
                        success: function(data){
                            console.log(data);
                        }
                    });
                }

                function putDeviceOnAlarm(device_id){
                    var data = {
                        id: device_id
                    }
                    $.ajax({
                        type: 'POST',
                        data: data,
                        url: '/put-device-on-alarm',
                        success: function(data){
                            console.log(data);
                        }
                    });
                }


            </script>
            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key={{config('app.google_geocoding_api_key')}}&callback=initMap&language=ru">
            </script>
            <script>

                let helper = {
                    showLoader: function(){
                        $('#video_up').css('opacity', 0);
                        $('#loading').css('opacity',1);
                    },
                    hideLoader: function(){
                        $('#video_up').css('opacity', 1);
                        $('#loading').css('opacity',0);
                    }
                };

                class Imager{

                    constructor(){
                        this.isOpen = false;
                    }

                    handle(){
                        this.enlargeImageClick();
                    }

                    enlargeImageClick(){
                        let self = this;
                        $(document).on('click', '.enlarge-image', function(){
                            self.showWindow();
                            self.catchCloseWindow();
                            let new_src = $(this).prev().attr('src');
                            self.updateImageSrc(new_src);
                        });
                    }

                    showWindow(){
                        this.isOpen = true;
                        $('#large-image-window').css('opacity', '1');
                        $('#large-image-window').css('z-index', '1');
                    }

                    catchCloseWindow(){
                        let self = this;
                        $('#large-image-window .close').click(function(){
                            self.isOpen = false;
                            $('#large-image-window').css('opacity', '0');
                            $('#large-image-window').css('z-index', '0');
                        });
                    }

                    updateImageSrc(new_src){
                        $('#large-image').attr('src', new_src);
                    }
                }

                var imager = new Imager();

                imager.handle();


                let imageLoader = {
                    device: undefined,
                    camera: undefined,
                    helper: helper,
                    page: 1,
                    imgHovered: false,
                    time_from: '2000-01-00 00:00:00',
                    time_to: '2050-01-00 00:00:00',

                    init: function(){

                        this.clickOnDeviceHandle();

                        this.clickOnCameraHandle();

                        this.clickOnVideoImageHandle();

                        this.clickOnNextButtonHandle();

                        this.clickOnPrevButtonHandle();

                        this.clickIntervalTimeHandle();

                        this.catchImageHover();

                        this.updateImages();


                    },

                    clickIntervalTimeHandle: function() {

                        $(document).ready(() => {
                            $('#set_time_interval').click(() => {
                                this.getTimeInterval();
                                this.downloadImages();
                            })
                        })
                    },

                    clickOnCameraHandle: function(){
                        let self = this;
                        $('.camera').click(function(){
                            self.higlightCameraIcon(this);
                            self.camera = this.dataset.cam_id;
                            self.downloadImages();
                        });
                    },

                    getTimeInterval: function(){
                        let date_from = $('#image_date_from').val();
                        let time_from = $('#image_time_from').val();
                        this.time_from = this.doTimeFrom(date_from, time_from);

                        let date_to = $('#image_date_to').val();
                        let time_to = $('#image_time_to').val();
                        this.time_to = this.doTimeTo(date_to, time_to);
                    },

                    doTimeFrom: function(date_from, time_from){
                        if(date_from === ''){
                            return '0000-00-00 00:00:00';
                        } else if(time_from === ''){
                            return date_from + ' 00:00:00';
                        } else {
                            return date_from + ' ' + time_from + ':00';
                        }
                    },

                    doTimeTo: function(date_to, time_to){
                        if(date_to === ''){
                            return '2050-00-00 00:00:00';
                        } else if(time_to === ''){
                            return date_to + ' 00:00:00';
                        } else {
                            return date_to + ' ' + time_to + ':00';
                        }
                    },

                    higlightCameraIcon: function(hh){
                        console.log($(hh));
                        $('.camera').removeClass('active-camera');
                        $(hh).addClass('active-camera');

                    },

                    clickOnVideoImageHandle: function(){
                        let self = this;
                        $(document).on('click', '#images-container img', function(){
                            let src = self.getCurrentImageSrcAttribute(this);
                            self.informServerWeNeedThisVideo(src);
                            self.helper.showLoader();
                            self.tryLoadVideo(src);
                            self.showModal();
                        });
                    },

                    clickOnDeviceHandle: function(){
                        let self = this;
                        $('.devices-list').click(function(){
                            self.device = this.dataset.id;
                            self.downloadImages();
                        });
                    },

                    clickOnNextButtonHandle: function(){
                        let self = this;
                        $('#next_go').click(function(){
                            self.goNext();
                            $('#prev_go').css('opacity' , '1');
                        });
                    },

                    clickOnPrevButtonHandle: function(){
                        let self = this;
                        $('#prev_go').click(function(){
                            self.goPrev();
                            if(self.page == 1){
                                self.hidePrevButton();
                            }
                        });
                    },

                    catchImageHover: function(){
                        var self = this;
                        $(document).on('mouseenter', '#images-container img', function(){
                            self.imgHovered = true;
                        });

                        $(document).on('mouseleave', '#images-container img', function(){
                            self.imgHovered = false;
                        });

                    },

                    hidePrevButton: function(){
                        $('#prev_go').css('opacity' , '0');
                    },

                    showNextButton: function(){
                        if(this.page !== undefined && this.camera !== undefined){
                            $('#next_go').css('opacity' , '1');
                        }
                    },

                    hideButtons: function(){
                        $('#prev_go').css('opacity' , '0');
                        $('#next_go').css('opacity' , '0');
                    },

                    getCurrentImageSrcAttribute: function(obj){
                        return obj.getAttribute('src');
                    },

                    showModal: function () {
                        $('#myModal').modal('show');
                    },

                    tryLoadVideo: function(path){
                        let self = this;
                        let tryLoadCounts = 0;
                        let myVar = setInterval(function(){
                            let res = path.split('/');
                            let res2 = res[res.length-1].split('.');
                            let videoUrl = 'public/data/' + self.device + '/video/' + res2[0] + ".ogg";
                            tryLoadCounts++;

                            if( tryLoadCounts > 100 ){
                                clearInterval(myVar);
                            }

                            if(self.imageExists(videoUrl)){
                                $('#camera_video').attr('src', videoUrl);
                                var video = document.getElementById('video_up');
                                self.helper.hideLoader();
                                clearInterval(myVar);
                                video.load();
                                video.play();
                            }
                        }, 500);
                    },

                    updateImages: function(){
                        let self = this;
                        let myVar2 = setInterval(function(){
                            let modalShown = $('#myModal').hasClass('show');
                            if(self.page == 1 && modalShown == false && !self.imgHovered && !imager.isOpen){
                                self.downloadImages();
                            }
                        }, 4000);
                    },

                    informServerWeNeedThisVideo: function(path){
                        let self = this;

                        $.ajax({
                            type:'POST',
                            url:'/api/video/required',
                            data:{
                                '_token' : '<?php echo csrf_token() ?>',
                                'path' : path,
                                'device_id': self.device
                            },
                            success:function(data){

                            }
                        });
                    },

                    goNext: function(){
                        let cur_page = this.page;
                        cur_page = cur_page + 1;
                        this.page = cur_page;
                        this.downloadImages();
                    },

                    goPrev: function(){
                        let cur_page = this.page;
                        if(cur_page > 1){
                            cur_page = cur_page -1;
                            this.page = cur_page;
                            this.downloadImages();
                        }
                    },

                    downloadImages: function(){
                        let self = this;
                        let data = {
                            'device_id': this.device,
                            'camera': this.camera,
                            'page': this.page,
                            'from_time': this.time_from,
                            'to_time': this.time_to,
                            '_token': '<?php echo csrf_token() ?>'
                        }
                        $.ajax({
                            type:'POST',
                            url:'/image/get',
                            data: data,
                            success:function(data){
                                let images = data.images;
                                if(images.length > 0) {
                                    self.putImages(images);
                                    self.showNextButton();
                                } else {
                                    $("#images-container").html('Нет Данных');
                                    self.hideButtons();
                                }
                            }
                        });
                    },

                    putImages: function(images){
                        let self = this;
                        let res = '';
                        images.forEach(function(element){
                            res += self.getImageHtml(element);
                        });
                        $("#images-container").html(res);
                    },

                    getImageHtml: function(element){
                        let res = '';
                        res += '<img src="';
                        res += element.name;
                        res += '" data-id="' + element.id + '" ></img>';
                        res += '<span class="enlarge-image">Увеличить</span>';
                        return res;
                    },

                    imageExists: function (image_url){

                        var http = new XMLHttpRequest();

                        http.open('HEAD', image_url, false);
                        http.send();

                        return http.status != 404;

                    }

                }

                $(document).ready(function(){
                    imageLoader.init();
                });


            </script>

    </div>
</div>



<div class="footer">
    <div class="row">
        <div class="camera-select col-md-2">
            <div class="row">
                <div class="col-md-8">
                    <h4>Камеры</h4>
                </div>
                <div class="col-md-4">
                    <div class="add-zone">
                        <div class="btn btn-success">Аффилировать</div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div id="camera-1" class="camera" data-cam_id="1"></div>
                </div>
                <div class="col-md-6">
                    <div id="camera-2" class="camera" data-cam_id="2"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div id="camera-3" class="camera" data-cam_id="3"></div>
                </div>
                <div class="col-md-6">
                    <div id="camera-4" class="camera" data-cam_id="4"></div>
                </div>
            </div>
            <div>
                <div class="class-col-md-6">

                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="prev_go"><button>Prev</button></div>
            <div id="images-container"></div>
            <span style="color:white"><h1></h1></span>
            <div id="next_go"><button>Next</button></div>
        </div>
        <div class="col-md-4" >
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6"><p>Промежуток времени</p></div>
                <div class="col-md-3"></div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <input id="image_date_from" type="date" value="2018-06-01">&nbsp;
                </div>
                <div class="col-md-3">
                    <input id="image_time_from" type="time">
                </div>
                <div class="col-md-3">
                    <input id="image_date_to" type="date">&nbsp;
                </div>
                <div class="col-md-3">
                    <input id="image_time_to" type="time">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6"><button id="set_time_interval" class="btn btn-primary">Выбрать</button></div>
                <div class="col-md-3"></div>
            </div>

        </div>
    </div>
    <p></p>
</div>

<div id="myModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Play Video</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span id="loading">Loading ...</span>
                <video id="video_up" width="320" height="240" controls>
                    <source id="camera_video" src="" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
