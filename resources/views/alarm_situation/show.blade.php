@extends('layouts.app')

@section('content')
    <script>
        let videoHandler = class VideoHandler{

            init(){
                this.hideVideosBeforeDownloaded();
            }

            getVideos(){
                return $('.alarm-video');
            }

            checkVideoAvailability (url){
                let http = new XMLHttpRequest();
                http.open('HEAD', url, false);
                http.send();
                return http.status != 404;
            }

            hideVideosBeforeDownloaded(){
                $(document).ready(function(){
                    $('.alarm-video').css('opacity', 0);
                });
            }


        }

        videoHandler.init();

    </script>
    <div class="container">
        <h3 style="color:#009688">Видео свидетелей</h3><br/>
        @if(count($videos) === 0)
            <h5>Данных нет</h5>
        @else
            <?php $video_num = 0; ?>
            @foreach($videos as $video)
                @if($video_num == 0)
                    <div class="row">
                @endif

                    <div class="col-md-4">
                        <span id="loading">Loading ...</span>
                        <p>{{$video->time}}</p>
                        <video class="alarm-video" data-id="{{$video->id}}"
                               data-src="/public/data/{{$video->device_id}}/video/{{$video->name}}.ogg" width="320" height="240" controls>
                            <source id="camera_video" src="/public/data/{{$video->device_id}}/video/{{$video->name}}.ogg" type="video/ogg">
                            Your browser does not support the video tag.
                        </video>
                    </div>
               <?php
                    $video_num++;
                    if($video_num == 3){
                        $video_num = 0;
                    }
               ?>
               @if($video_num == 3)
                    </div>
               @endif
            @endforeach
        @endif
    </div>
@endsection