$(document).ready(function () {
    
    // Step show event
    $("#smartwizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection, stepPosition) {
       //alert("You are on step "+stepNumber+" now");
       if(stepPosition === 'first'){
            $("#prev-btn").addClass('disabled');
            $("#next-btn").removeClass('disabled');
            $("#fin-btn").addClass('disabled');
            if($('#elemento').length)
            {
                document.getElementById("fin-btn").disabled = true; 
            }  
       }else if(stepPosition === 'final'){
            $("#next-btn").addClass('disabled');
            $("#prev-btn").removeClass('disabled');
            $("#fin-btn").removeClass('disabled');
            if($('#elemento').length)
            {
                document.getElementById("fin-btn").disabled = false; 
            }
       }else{
            $("#prev-btn").removeClass('disabled');
            $("#next-btn").removeClass('disabled');
       }
    });
    
    // Smart Wizard
    $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'arrows',
            transitionEffect:'fade',
            showStepURLhash: true,
            enableAllSteps: false,
            noForwardJumping: false,
            keyNavigation:false,
            toolbarSettings: {
                showNextButton: false, // show/hide a Next button
                showPreviousButton: false // show/hide a Previous button
            }
    });
    
    $("#prev-btn1").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });
    
    $("#next-btn1").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
    
    $("#prev-btn2").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn2").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
    
    $("#prev-btn3").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn3").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
    
    $("#prev-btn4").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn4").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
    
    $("#prev-btn5").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn5").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
    
    $("#prev-btn6").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn6").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
    
    $("#prev-btn7").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn7").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
});