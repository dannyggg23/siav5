$(document).ready(function(){

	var show = 0;

	$('.dti_config_menu').on('click',function(){

		if (show == 1) {
			$('.dti_sidebar').addClass("dti_sidebar_show");
			$('.dti_header').addClass("dti_header_show");
			$('.dti_contenido').addClass("dti_contenido_show");
			show = 0;
		}else{
			$('.dti_sidebar').removeClass("dti_sidebar_show");
			$('.dti_header').removeClass("dti_header_show");
			$('.dti_contenido').removeClass("dti_contenido_show");
			show = 1;
		}

	});
        
        $(".dti_menu a").click(function() {
            var link = $(this);
            var closest_ul = link.closest("ul");
            var parallel_active_links = closest_ul.find(".active");
            var closest_li = link.closest("li");
            var link_status = closest_li.hasClass("active");
            var count = 0;

            closest_ul.find("ul").slideUp(function() {
                if (++count == closest_ul.find("ul").length)
                    parallel_active_links.removeClass("active");
            });

            if (!link_status) {
                closest_li.children("ul").slideDown();
                closest_li.addClass("active");
            }
        });

        //Label Floating
        $(".mat-input").focus(function(){
            $(this).parent().addClass("is-active is-completed");
            $(this).parent().parent().addClass("is-active is-completed");
        });

        $(".mat-input").focusout(function(){
            if($(this).val() === ""){
                $(this).parent().removeClass("is-completed");
                $(this).parent().parent().removeClass("is-completed");
            }
            $(this).parent().parent().removeClass("is-active");
            $(this).parent().removeClass("is-active");
        });

});