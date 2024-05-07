$(window).on('load', function () {


  /*-------------mobile btn---------------------*/


  $(".mobile_btn").click(function () {
    $(".mobile_nav").slideToggle();
    $(".side_menu").fadeToggle(200);
    $('#nav-icon4').toggleClass('open');
    $('html,body').toggleClass('open');
  });



  // $('.bannerslider ul').slick({
  //   centerMode: false,
  //   infinite: true,
  //   slidesToShow: 1,
  //   slidesToScroll: 1,
  //   arrows: false,
  //   dots: false,
  //   adaptiveHeight: true
  // });

  $(".forget_btn").click(function () {
    $('.forget__form').slideToggle();
  });





  $(window).bind("scroll", function () {


    if ($(window).scrollTop() > 120) {
      $("header").addClass("scroll");

    }
    if ($(window).scrollTop() < 30) {
      $("header").removeClass("scroll").removeClass("scrolltop");

    }


  });



});

