<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="庫點子文創資訊產業有限公司" />
<meta name="keywords" Lang="EN" content="key" />
<meta name="keywords" Lang="zh-TW" content="庫點子文創資訊產業有限公司" />
<meta name="Description" Content="庫點子文創資訊產業有限公司" />
<meta name="COPYRIGHT" content="Copyright (c) by 庫點子文創資訊產業有限公司">
<LINK REL="SHORTCUT ICON" HREF="favicon.ico">

<title><?= $_Title . ' - ' . $_setting_['WO_Title'] ?></title>
<script src="js/jquery-3.7.1.min.js"></script>


<link rel="stylesheet" type="text/css" href="./slick/slick.css">
<link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">
<script type="text/javascript" src="slick/slick.js"></script>


<script type="text/javascript" src="system/assets/jquery/jquery-box-window.js"></script>
<script type="text/javascript" src="system/assets/datetimepicker/moment.min.js"></script>
<script type="text/javascript" src="system/assets/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<script type="text/javascript" src="system/assets/datetimepicker/bootstrap-datetimepicker.js"></script>
<link rel="stylesheet" href="system/assets/bootstrap/3.2.0/css/bootstrap.css" />
<link rel="stylesheet" href="system/assets/datetimepicker/bootstrap-datetimepicker.min.css" />
<script type="text/javascript" src="system/assets/datetimepicker/locale/zh-tw.js"></script>
<link rel="stylesheet" href="system/assets/css/box_window.css" />
<link rel="stylesheet" type="text/css" href="stylesheets/cssreset.css" />
<link rel="stylesheet" type="text/css" href="stylesheets/base.css" />



<script src="js/ani.js"></script>
<script src="js/jquery.session.js"></script>
<script src="js/web-checkinput.js?v=<?= date('YmdHis') ?>"></script>
<script src="js/web-main.js?v=<?= date('YmdHis') ?>"></script>

<script>
  $(window).bind("scroll", function() {

    if ($(window).scrollTop() > 20) {
      $("header").addClass("scroll");
      $("article").addClass("scroll");
    }
    if ($(window).scrollTop() < 20) {
      $("header").removeClass("scroll");
      $("article").removeClass("scroll");
    }



  });



  $(window).on('load', function() {



    $(".mobile_nav>ul>li>a").click(function() {
      $(".mobile_nav").slideUp();
      $("#nav-icon4").removeClass('open');
      $('html,body').removeClass('open');
    });

    $(".btn").click(function() {
      $(".mobile_nav").slideUp();
    });

    /*$(".employee").hover(function(){
    $(".remind").hide();
  });
*/



    $(".aside_menu_btn").click(function() {
      $(".aside_menu").slideToggle();
      $(".aside_menu_btn").toggleClass('open');
    });




    $wdth = $(window).width();


    if ($wdth < 855) {
      $('.intro_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#intro').offset().top - 87
        }, 1000);
      });
      $('.program_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#program').offset().top - 87
        }, 1000);
      });
      $('.download_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#download').offset().top - 87
        }, 1000);
      });
      $('.pay_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#pay').offset().top - 87
        }, 2000);
      });
    } else {

      $('.intro_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#intro').offset().top - 107
        }, 1000);
      });
      $('.program_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#program').offset().top - 107
        }, 1000);
      });
      $('.download_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#download').offset().top - 107
        }, 1000);
      });
      $('.pay_btn').click(function() {
        $('html,body').animate({
          scrollTop: $('#pay').offset().top - 107
        }, 2000);
      });

    }


  });

  $(window).resize(function() {
    $wdth = $(window).width();

    if ($wdth > 1020) {
      $("#nav-icon4").removeClass('open');
      $(".mobile_nav").slideUp();
    }


  });




  /*---去虛框-----*/

  jQuery(function($) {
    $("a").focus(function() {
      $(this).blur();
    });
  });
  /*---去虛框-----*/
</script>

<!--彈跳視窗插件-->
<script src="js/sweetalert.js"></script>