<style type="text/css">
    .video-box{ position: relative; }
    .modal-contant{        
        background-color: rgba(0,0,0,0.5);
        position: fixed;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        z-index: 9;        
        align-items: center;
        justify-content: center;
        display: none;   
        transition: all 0.5s ease-in-out;
        -webkit-transition: all 0.5s ease-in-out;
        /*transform: scale(0);*/
    }
    .video-box.modal-in .modal-contant{
        /*transform: scale(1);   */ 
        display: flex;   
    }
    .embed-size{
        width: 90%;
        height: 85%;
    }
    .view-full,
    .view-full:hover,
    .view-full:focus,
    .view-full:active{
        color: #fff;
        background: #34bfa3;
        position: absolute;
        bottom: 0;
        right: 0;
        padding: 12px 20px;
        border-top-left-radius: 6px;
        text-decoration: none; 
    }
    .close-full-screen,
    .close-full-screen:hover,
    .close-full-screen:focus,
    .close-full-screen:active{
        color: #fff;
        position: absolute;
        top: 20px;
        right: 20px;
        font-size: 50px;
        line-height: 1;
        text-decoration: none;
    }
    @media (max-width: 767px){
        .embed-size{
            width: 95%;
            height: 50%;
        }
    }
       
</style>
<div class="wrap-1000">
    <div class="card padding-20">
        <div class="row">
            <div class="col-md-12">
                <div class="video-title">How it works?</div>
                <div class="video-box">
                    <iframe src="https://player.vimeo.com/video/583145312" width="100%" height="600px" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <!--<iframe allowfullscreen="allowfullscreen" frameBorder="0" width="100%" height="400" src="https://www.youtube.com/embed/7Le4UBwh46E"></iframe>-->                  
                    <div class="modal-contant">
                        <!--<embed class="embed-size" src="https://www.youtube.com/embed/7Le4UBwh46E?autoplay=1&mute=1">-->
                        <iframe src="https://player.vimeo.com/video/583145312" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        <a href="javascript:void(0);" class="close-full-screen">&times;</a>
                    </div>
                    <!-- <a href="javascript:void(0);" class="view-full">Full Screen</a>                     -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="video-title">How To Setup a Discount Upsell</div>
                <div class="video-box">
                    <iframe src="https://player.vimeo.com/video/583143226" width="100%" height="600px" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <div class="modal-contant">
                        <iframe src="https://player.vimeo.com/video/583143226" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        <a href="javascript:void(0);" class="close-full-screen">&times;</a>
                    </div>
                    <!-- <a href="javascript:void(0);" class="view-full">Full Screen</a> -->
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="video-title">How To Setup a Cross Sell</div>
                <div class="video-box">
                    <iframe src="https://player.vimeo.com/video/583149133" width="100%" height="600px" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    <div class="modal-contant">
                        <iframe src="https://player.vimeo.com/video/583149133" width="100%" height="100%" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                        <a href="javascript:void(0);" class="close-full-screen">&times;</a>
                    </div>
                    <!-- <a href="javascript:void(0);" class="view-full">Full Screen</a> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.view-full').click(function (){
        $(this).parent().addClass('modal-in');
    });
    $('.close-full-screen').click(function (){
        $(this).parent().parent().removeClass('modal-in');
    });
    
</script>