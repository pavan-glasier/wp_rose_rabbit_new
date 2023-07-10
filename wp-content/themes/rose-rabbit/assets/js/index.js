(function (e) {
  (e.fn.vsmobilemenu = function (t) {
     var s = e.extend({
           menuToggleBtn: ".vs-menu-toggle",
           bodyToggleClass: "vs-body-visible",
           subMenuClass: "vs-submenu",
           subMenuParent: "vs-item-has-children",
           subMenuParentToggle: "vs-active",
           meanExpandClass: "vs-mean-expand",
           appendElement: '<span class="vs-mean-expand"></span>',
           subMenuToggleClass: "vs-open",
           toggleSpeed: 400,
        },
        t
     );
     return this.each(function () {
        var t = e(this);

        function a() {
           t.toggleClass(s.bodyToggleClass);
           var a = "." + s.subMenuClass;
           e(a).each(function () {
              e(this).hasClass(s.subMenuToggleClass) &&
                 (e(this).removeClass(s.subMenuToggleClass),
                    e(this).css("display", "none"),
                    e(this).parent().removeClass(s.subMenuParentToggle));
           });
        }
        t.find("li").each(function () {
           var t = e(this).find("ul");
           t.addClass(s.subMenuClass),
              t.css("display", "none"),
              t.parent().addClass(s.subMenuParent),
              t.prev("a").append(s.appendElement),
              t.next("a").append(s.appendElement);
        });
        var n = "." + s.meanExpandClass;
        e(n).each(function () {
              e(this).on("click", function (t) {
                 var a;
                 t.preventDefault(),
                    (a = e(this).parent()),
                    e(a).next("ul").length > 0 ?
                    (e(a).parent().toggleClass(s.subMenuParentToggle),
                       e(a).next("ul").slideToggle(s.toggleSpeed),
                       e(a).next("ul").toggleClass(s.subMenuToggleClass)) :
                    e(a).prev("ul").length > 0 &&
                    (e(a).parent().toggleClass(s.subMenuParentToggle),
                       e(a).prev("ul").slideToggle(s.toggleSpeed),
                       e(a).prev("ul").toggleClass(s.subMenuToggleClass));
              });
           }),
           e(s.menuToggleBtn).each(function () {
              e(this).on("click", function () {
                 a();
              });
           }),
           t.on("click", function (e) {
              e.stopPropagation(), a();
           }),
           t.find("div").on("click", function (e) {
              e.stopPropagation();
           });
     });
  }),
  e(".vs-menu-wrapper").vsmobilemenu();
  var t = "",
     s = ".scrollToTop";
  e(window).on("scroll", function () {
        var a, n, o, i, r;
        (a = e(".sticky-active")),
        (n = "active"),
        (o = "will-sticky"),
        (i = e(window).scrollTop()),
        (r = a.css("height")),
        a.parent().css("min-height", r),
           e(window).scrollTop() > 50 ?
           (a.parent().addClass(o), i > t ? a.removeClass(n) : a.addClass(n)) :
           (a.parent().css("min-height", "").removeClass(o), a.removeClass(n)),
           (t = i),
           e(this).scrollTop() > 500 ?
           e(s).addClass("show") :
           e(s).removeClass("show");
     }),
     e(s).each(function () {
        e(this).on("click", function (s) {
           return (
              s.preventDefault(),
              e("html, body").animate({
                    scrollTop: 0,
                 },
                 t / 3
              ),
              !1
           );
        });
     }),
     (a = ".sidemenu-wrapper_"),
     (n = ".sideMenuCls"),
     (o = "show"),
     e(".sideMenuToggler").on("click", function (t) {
        t.preventDefault(), e(a).addClass(o);
     }),
     e(a).on("click", function (t) {
        t.stopPropagation(), e(a).removeClass(o);
     }),
     e(a + " > div").on("click", function (t) {
        t.stopPropagation(), e(a).addClass(o);
     }),
     e(n).on("click", function (t) {
        t.preventDefault(), t.stopPropagation(), e(a).removeClass(o);
     }),

     jQuery(".question").click(function () {
        jQuery(this).toggleClass("question-active");
        jQuery(".question").not(this).removeClass('question-active').siblings('[class=hidden]').css("display", "none");
        jQuery(this).next().slideToggle(700);
        jQuery(this).children("img.arrow").toggleClass("arrow-active");
     })

  // model
  var modal = document.getElementById("myModal");
  var btn = document.getElementById("myBtn");
  var span = document.getElementsByClassName("close")[0];
  if( btn ){   
     if(modal){
        btn.onclick = function () {
           modal.style.display = "block";
        }
        span.onclick = function () {
           modal.style.display = "none";
        }
        window.onclick = function (event) {
           if (event.target == modal) {
              modal.style.display = "none";
           }
        }
     }
  }
  // faq
  const imgs = document.querySelectorAll('.img-select a');
  const imgBtns = [...imgs];
  let imgId = 1;

  imgBtns.forEach((imgItem) => {
     imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
     });
  });

  function slideImage() {
   const displayWidth = document.querySelector('.img-showcase .cart__product:first-child');
   if(displayWidth){
   const ClientWidth = displayWidth.clientWidth;
   document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * ClientWidth}px)`;
}
  }
  window.addEventListener('resize', slideImage);

  // text animation
  var textWrapper = document.querySelector('.text-first');
  if( textWrapper ){
     textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
     anime.timeline({
           loop: false
        })
        .add({
           targets: '.text-first .letter',
           translateX: [40, 0],
           translateZ: 0,
           opacity: [0, 1],
           easing: "easeOutExpo",
           duration: 3000,
           delay: (el, i) => 500 + 30 * i
        })
  }
})(jQuery);