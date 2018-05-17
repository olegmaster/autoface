@extends('layouts.app')

@section('content')
<div class="container">
        <div class="col-md-12">
            <div id="map" style="height:700px;width:130%;"></div>
            <script src="{{ asset('js/map.js') }}" defer></script>
            <script>
                function initMap() {

                    var locations = [
                    @foreach ($locations as $location)
                            ["{{$location['name']}}",{{$location['latitude']}} , {{$location['longitude']}}],
                    @endforeach
                    ];

                    var uluru = {lat: 49.98884148, lng: 36.22041411};
                    var map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 14,
                        center: uluru,
                        styles: [{
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
                            }]
                    });


                    for (i = 0; i < locations.length; i++) {
                        marker = new google.maps.Marker({
                            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                            map: map
                        });
                    }

                    let devices = devicesInMenu;
                    devices.addCkickOnMenuEventListener();


                }


            </script>
            <script async defer
                    src="https://maps.googleapis.com/maps/api/js?key={{config('app.google_geocoding_api_key')}}&callback=initMap&language=ru">
            </script>
            <script>
                let imageLoader = {
                    device: undefined,
                    camera: undefined,
                    page: 1,
                    init: function(){

                        $('#prev_go').css('opacity' , '0');
                        $('#next_go').css('opacity' , '1');

                        let self = this;
                        $('.devices-list').click(function(){
                            self.device = this.dataset.id;
                            self.downloadImages();
                            // console.log(self.device);
                        });

                        $('.camera').click(function(){
                           self.camera = this.dataset.cam_id;
                            self.downloadImages();
                           // console.log(self.camera);
                        });

                        $(document).on('click', '#images-container img', function(){
                            let path = this.getAttribute('src');
                            self.setVideoRequired(path);
                            $('#video_up').css('opacity', 0);
                            $('#loading').css('opacity',1);

                            let myVar = setInterval(function(){
                                let res = path.split('/');
                                let res2 = res[res.length-1].split('.');
                                let videoUrl = 'public/data/video/' + res2[0] + ".mp4";
                                if(self.imageExists(videoUrl)){
                                    $('#camera_video').attr('src', videoUrl);
                                    var video = document.getElementById('video_up');
                                    $('#video_up').css('opacity', 1);
                                    $('#loading').css('opacity',0);
                                    clearInterval(myVar);
                                    video.load();
                                    video.play();
                                }
                            }, 500);
                            $('#myModal').modal('show');
                        });

                        $('#prev_go').click(function(){
                            self.goPrev();
                            if(self.page == 1){
                                $('#prev_go').css('opacity' , '0');
                            }
                        });

                        $('#next_go').click(function(){
                            self.goNext();
                            $('#prev_go').css('opacity' , '1');
                        });

                        this.updateImages();
                    },

                    updateImages: function(){
                        let self = this;
                        let myVar2 = setInterval(function(){
                            let modalShown = $('#myModal').hasClass('show');
                            console.log(modalShown);
                            if(self.page == 1 && modalShown == false){
                                console.log(self.page);
                                self.downloadImages();
                            }
                        }, 25000);


                    },

                    setVideoRequired: function(path){
                        $.ajax({
                            type:'POST',
                            url:'/api/video/required',
                            data:{
                                '_token' : '<?php echo csrf_token() ?>',
                                'path' : path
                            },
                            success:function(data){
                                console.log(data);
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
                                    });
                                    $("#images-container").html(res);
                                } else {
                                    $("#images-container").html('Нет Данных');
                                }
                                console.log(data.images);

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
                <div class="col-md-12">
                    <h4>Камеры</h4>
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
                    <source id="camera_video" src="public/data/video/cam1_17_05_2018_11_54_33.mp4" type="video/mp4">

                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endsection
