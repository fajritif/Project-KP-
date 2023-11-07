<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="{{ url('') }}/css/video-js.css">
  <script src="{{ url('') }}/js/video.min.js"></script>
  <script src="{{ url('') }}/js/videojs-http-streaming.min.js"></script>
</head>
<body>
  <div>
    <video id="my_video_1" class="video-js vjs-layout-tiny vjs-16-9	vjs-default-skin" controls preload="auto" data-setup='{}'>
      <!-- Link source diisi link dimana file hasil convert dari ffmpeg disimpan -->
      <source src="{{ route('get-stream-file', $device) }}" type="application/x-mpegURL" id="player">
    </video>
  </div>
  <script>
    var player = videojs('my_video_1', {
      autoplay: true
    });
    player.play();
  </script>
</body>
</html>

