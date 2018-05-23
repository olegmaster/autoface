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
            <div id="map" style="height:700px;width:130%;"></div>

            <script>



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


                function initMap() {


                    var uluru = {lat: 49.98884148, lng: 36.22041411};
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 14,
                        center: uluru,
                        styles: arrOfStyles
                    });


                    var allZones = [];
                    var allMarkers = [];

                    loadAllDevices();

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
                        // /device/get-all-for-map/
                        askServerForDevices();
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
                                console.log(data);
                                mapAddMarker(data.latitude, data.longitude);
                            }
                        });
                    }



                    function mapAddMarker(lat, lng){

                        var marker = new google.maps.Marker({
                            position: {lat: lat, lng: lng},
                            map: map
                        });
                        allMarkers.push(marker);
                    }

                    function cleanMarkers(){
                        for (var i = 0; i < allMarkers.length; i++) {
                            allMarkers[i].setMap(null);
                        }
                    }



                    function addZoneClick(){
                        $('.add-zone').click(function(){
                            if(!deviceIsSelected()){
                                alert('Выберите устройство');
                                return;
                            }
                            createZone();
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

                    function createZone(){

                        var cityCircle = createZoneCircle(null);
                        var zoneData = getZoneData(cityCircle);
                        var id = addNewZoneOnServer(zoneData);
                        cityCircle.id = id;
                        addRadiusChangeListenerToCircle(cityCircle);
                        addCenterChangeListenerToCircle(cityCircle);
                        allZones.push(cityCircle);
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

                    init: function(){

                        this.clickOnDeviceHandle();

                        this.clickOnCameraHandle();

                        this.clickOnVideoImageHandle();

                        this.clickOnNextButtonHandle();

                        this.clickOnPrevButtonHandle();

                        this.catchImageHover();

                        this.updateImages();
                    },

                    clickOnCameraHandle: function(){
                        let self = this;
                        $('.camera').click(function(){
                            self.higlightCameraIcon(this);
                            self.camera = this.dataset.cam_id;
                            self.downloadImages();
                        });
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
                        let myVar = setInterval(function(){
                            let res = path.split('/');
                            let res2 = res[res.length-1].split('.');
                            let videoUrl = 'public/data/video/' + res2[0] + ".ogg";
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
                        $.ajax({
                            type:'POST',
                            url:'/api/video/required',
                            data:{
                                '_token' : '<?php echo csrf_token() ?>',
                                'path' : path
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
                        $.ajax({
                            type:'GET',
                            url:'/image/get/'+this.device + '/' +this.camera + '/' + this.page,
                            data:'_token = <?php echo csrf_token() ?>',
                            success:function(data){
                                let images = data.images;
                                let res = '';
                                if(images.length > 0) {

                                    images.forEach(function(element){
                                        res += "<img src='";
                                        res += element.name;
                                        res += "' ></img>";
                                        res += '<span class="enlarge-image">Увеличить</span>';
                                    });
                                    $("#images-container").html(res);
                                    self.showNextButton();
                                } else {
                                    $("#images-container").html('Нет Данных');
                                    self.hideButtons();
                                }
                            }
                        });
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
                        Доьавить зону+
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
        <div class="col-md-1" id="prev_go"><button>Prev</button></div>
        <div id="images-container" class="col-md-6"><span style="color:white"><h1></h1></span></div>
        <div class="col-md-1" id="next_go"><button>Next</button></div>
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
