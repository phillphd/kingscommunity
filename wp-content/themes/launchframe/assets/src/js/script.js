(function($, w){
    "use strict";
    /* global
     * console, setTimeout */

var theWindow = $(window);
var wb = {
    scrolled: {
        test: function() {
            return true;
        },
        run: function() {
            theWindow.on("scroll", function() {
				var stop = theWindow.scrollTop();
				if (stop > 100) {
					$("body").addClass("scrolled");
				} else {
					$("body").removeClass("scrolled");
				}
			});
        }
    },
    mobileNavTrigger: {
        test: function() {
            return true;
        },
        run: function() {
            $(".mobile-nav-trigger").on("click", function(event) {
                $("body").toggleClass("open-nav");
            });
        }
    },
    slickHero: {
        test: function() {
            return $(".big-slick").length;
        },
        run: function() {
            $(".big-slick").slick({
                centerMode: true,
                slidesToShow: 1,
                variableWidth: true,
                arrows: false,
                dots: true,
                infinite: false,
                initialSlide: 1,
                focusOnSelect: true
            });
        }
    },
    videolauncher: {
        test: function() {
            return true;
        },
        run: function() {
            var b = $("body");
            var playerHtml = "<div class=\"video-player\"><div class=\"player-container\"><div class=\"player-inner-wrap\"><div class=\"iframe-wrapper\"><div class=\"close-btn\"><svg width=\"12px\" height=\"12px\" viewPort=\"0 0 12 12\" version=\"1.1\" xmlns=\"http://www.w3.org/2000/svg\"><line x1=\"1\" y1=\"11\" x2=\"11\" y2=\"1\" stroke=\"#FFF\" stroke-width=\"2\"/><line x1=\"1\" y1=\"1\" x2=\"11\" y2=\"11\" stroke=\"#FFF\" stroke-width=\"2\"/></svg></div></div></div></div></div>";
            b.append(playerHtml);

            var player = $(".video-player");
            b.on("click", ".video-modal-trigger", function(event) {
                event.preventDefault();
                var videoID = $(this).data("vimeoid");
                // Default to Vimeo
                var url = "https://player.vimeo.com/video/" + videoID + "?byline=0&badge=0";
                // Change to Youtube
                if(videoID.length === 11 && !$.isNumeric(videoID)) {
                    url = "https://www.youtube.com/embed/" + videoID;
                }

                var iframeHtml = "<iframe src=\"" + url + "\" width=\"100%\" height=\"100%\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
                b.addClass("video-on");
                player.css("display", "block");
                setTimeout(function() {
                    player.css("opacity", "1");
                    player.addClass("zoomed");
                    setTimeout(function() {
                        player.find(".iframe-wrapper").append(iframeHtml);
                    }, 500);
                }, 50);
            });
            player.on("click", ".close-btn", function(event) {
                event.preventDefault();
                player.css("opacity", "0").removeClass("zoomed");
                b.removeClass("video-on");
                setTimeout(function() {
                    player.css("display", "none");
                    player.find("iframe").remove();
                }, 500);
            });
        }
    },
    faq: {
        test: function() {
            return $(".faq__question").length;
        },
        run: function() {
            $("body").on("click touchstart", ".faq__question", function() {
                $(".faq li").removeClass("faq--active");
                $(this).parent("li").addClass("faq--active");
            });
        }
    },
    gallery: {
        test: function() {
            return $(".lightboxgallery-gallery").length;
        },
        run: function() {
            $(document).on("click", ".lightboxgallery-gallery-item", function(event) {
                event.preventDefault();
                $(this).lightboxgallery({
                    showCounter: true,
                    showTitle: true,
                    showDescription: true
                });
            });
        }
    }
};

Object.keys(wb).forEach(function(key){
    if (wb[key].test()){ wb[key].run(); }
});

}(jQuery, window));
