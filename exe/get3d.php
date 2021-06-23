<head>

</head>
<body style="overflow: hidden;">
<center>
    <h3>3D-модель товара.</h3>
</center>
<div class="threesixty treeD" style="width: 100%; height: 100%; max-width: 100%; max-height: 100%;">
    <img src="/images/360.png" style="position: absolute; z-index: 999999;" />
    <div class="spinner">
        <span>0%</span>
    </div>
    <ol class="threesixty_images"></ol>
</div>

<script type="text/javascript" src='//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
<link href="/js/threesixty.css" rel="stylesheet" type="text/css"/>
<script src="/js/threesixty.min.js" type="text/javascript"></script>
<script>
    window.onload = init;
    var treeD;
    var images_count = 16; //
    function init(){

        treeD = $('.treeD').ThreeSixty({
            totalFrames: images_count, // Total no. of image you have for 360 slider
            endFrame: images_count, // end frame for the auto spin animation
            currentFrame: 1, // This the start frame for auto spin
            imgList: '.threesixty_images', // selector for image list
            progress: '.spinner', // selector to show the loading progress
            imagePath:'/images/3d/<?=$_GET['id'];?>/', // path of the image assets
            filePrefix: '', // file prefix if any
            ext: '.jpg', // extention for the assets
            height: '100%',
            width: '100%',
            autoplayDirection: -1,
            framerate: 15,
            disableSpin: true,
            inverted: true,
            dragTolerance: 1,  // Rotation speed when dragging. Default: 10
            swipeTolerance: 1, // Rotation speed when swiping. Default: 10
        });
    }
</script>

<style>
    .threesixty_images li {height: 100%;}
    .threesixty_images li img {max-height: 100%; max-width: 100%;}
</style>
</body>