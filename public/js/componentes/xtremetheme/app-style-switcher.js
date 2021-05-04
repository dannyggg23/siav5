$(function() {
    "use strict";
    //****************************
    /* Left header Theme Change function Start */
    //****************************
    function handlelogobg() {
      
        $('.theme-color .theme-item .theme-link').on("click", function() {

            var logobgskin = $(this).attr("data-logobg");
            console.log("1: "+logobgskin);

            if(logobgskin!="undefined"){
                $.ajax({
                    url:'default/config_theme',
                    type: 'post',
                    data:{'op' : 'logo_theme','valor' : logobgskin},
                    success:function(data){
                        console.log(data);
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                     if ( console && console.log ) {
                        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                     }
                });
    
            }

            
           

            $('.topbar .top-navbar .navbar-header').attr("data-logobg", logobgskin);
        });
    };
    handlelogobg();
    //****************************
    /* Top navbar Theme Change function Start */
    //****************************
    function handlenavbarbg() {

        if ( $('#main-wrapper').attr('data-navbarbg') == 'skin6' ) {
                    // do this
                    $(".topbar .navbar").addClass('navbar-light');
                    $(".topbar .navbar").removeClass('navbar-dark');
                } else {
                    // do that
                    
                }
        $('.theme-color .theme-item .theme-link').on("click", function() {
        

            var navbarbgskin = $(this).attr("data-navbarbg");
            console.log("1: "+logobgskin);

            if(navbarbgskin!="undefined"){
                $.ajax({
                    url:'default/config_theme',
                    type: 'post',
                    data:{'op' : 'navbar_theme','valor' : navbarbgskin},
                    success:function(data){
                        console.log(data);
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                     if ( console && console.log ) {
                        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                     }
                });
            }

            

            $('#main-wrapper').attr("data-navbarbg", navbarbgskin);
            $('.topbar .navbar-collapse').attr("data-navbarbg", navbarbgskin);
            if ( $('#main-wrapper').attr('data-navbarbg') == 'skin6' ) {
                    // do this
                    $(".topbar .navbar").addClass('navbar-light');
                    $(".topbar .navbar").removeClass('navbar-dark');
                } else {
                    

                   
                    $(".topbar .navbar").removeClass('navbar-light');
                    $(".topbar .navbar").addClass('navbar-dark');
                }
        });
        
    };

    handlenavbarbg();
    
    //****************************
    // ManageSidebar Type
    //****************************
    function handlesidebartype() {
        
    };
    handlesidebartype();
     
    
    //****************************
    /* Manage sidebar bg color */
    //****************************
    function handlesidebarbg() {
        $('.theme-color .theme-item .theme-link').on("click", function() {
        

            var sidebarbgskin = $(this).attr("data-sidebarbg");
            console.log("3:"+sidebarbgskin);
            if (sidebarbgskin!="undefined"){
                $.ajax({
                    url:'default/config_theme',
                    type: 'post',
                    data:{'op' : 'sidebar_theme','valor' : sidebarbgskin},
                    success:function(data){
                        console.log(data);
                    }
                }).fail(function( jqXHR, textStatus, errorThrown ) {
                     if ( console && console.log ) {
                        Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                     }
                });
            }
           

            $('.left-sidebar').attr("data-sidebarbg", sidebarbgskin);
        });
    };
    handlesidebarbg();
    //****************************
    /* sidebar position */
    //****************************
    function handlesidebarposition() {
		$('#sidebar-position').change(function() {
        
           
            if( $(this).is(":checked")) {
                $('#main-wrapper').attr("data-sidebar-position", 'fixed' );
                $('.topbar .top-navbar .navbar-header').attr("data-navheader", 'fixed' );
            }else {
                $('#main-wrapper').attr("data-sidebar-position", 'absolute' ); 
                $('.topbar .top-navbar .navbar-header').attr("data-navheader", 'relative' );
            }
        });
        
	};
    handlesidebarposition ();
    //****************************
    /* Header position */
    //****************************
    function handleheaderposition() {
		$('#header-position').change(function() {
        

            if( $(this).is(":checked")) {
                $('#main-wrapper').attr("data-header-position", 'fixed' );
            }else {
                $('#main-wrapper').attr("data-header-position", 'relative' ); 
            }      
        });
	};
    handleheaderposition ();
    //****************************
    /* sidebar position */
    //****************************
    function handleboxedlayout() {
		$('#boxed-layout').change(function() {
        var valor=0;
            
            if( $(this).is(":checked")) {
                valor=1;
                $('#main-wrapper').attr("data-boxed-layout", 'boxed' );
            }else {
                $('#main-wrapper').attr("data-boxed-layout", 'full' ); 
            }

            $.ajax({
                url:'default/config_theme',
                type: 'post',
                data:{'op' : 'boxed-layout','valor' : valor},
                success:function(data){
                    console.log(data);
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                 if ( console && console.log ) {
                    Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                 }
            });


        });
        
	};
    handleboxedlayout ();
    //****************************
    /* Header position */
    //****************************
    function handlethemeview() {
		$('#theme-view').change(function() {
            var valor=0;
            if( $(this).is(":checked")) {
                //AJAX ACTUALIZAR EN LA BASE DE DATOS 1
                valor=1;
                $('body').attr("data-theme", 'dark' );
            }else {
                //AJAX ACTUALIZAR EN LA BASE DE DATOS 0
                $('body').attr("data-theme", 'light' ); 
            } 
            
            $.ajax({
                url:'default/config_theme',
                type: 'post',
                data:{'op' : 'actualizarThema','valor' : valor},
                success:function(data){
                    console.log(data);
                }
            }).fail(function( jqXHR, textStatus, errorThrown ) {
                 if ( console && console.log ) {
                    Swal.fire('Error!', errorThrown+', Los datos que ingresa son incorrectos!', 'error');
                 }
            });
        });
	};
    handlethemeview ();
});