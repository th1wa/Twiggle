(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();
    
    
    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').addClass('shadow-sm').css('top', '0px');
        } else {
            $('.sticky-top').removeClass('shadow-sm').css('top', '-100px');
        }
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });


    // Date and time picker
    $('.date').datetimepicker({
        format: 'L'
    });
    $('.time').datetimepicker({
        format: 'LT'
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        dotsData: true,
    });


    // Testimonials carousel
    $('.testimonial-carousel').owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        loop: true,
        nav: false,
        dots: true,
        items: 1,
        dotsData: true,
    });

    // Appointment form submission
    $('#appointmentForm').on('submit', function(e) {
        e.preventDefault();
        
        var form = $(this);
        var formData = new FormData(this);
        var submitBtn = form.find('button[type="submit"]');
        var originalBtnText = submitBtn.text();
        
        // Disable button and show loading state
        submitBtn.prop('disabled', true).text('Submitting...');
        
        $.ajax({
            type: 'POST',
            url: form.attr('action'),
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                var messageDiv = $('#formMessage');
                
                if (response.success) {
                    messageDiv.removeClass('alert-danger').addClass('alert-success');
                    messageDiv.html('<strong>Success!</strong> ' + response.message);
                    messageDiv.show();
                    
                    // Reset form
                    form[0].reset();
                    
                    // Hide message after 5 seconds
                    setTimeout(function() {
                        messageDiv.fadeOut();
                    }, 5000);
                } else {
                    messageDiv.removeClass('alert-success').addClass('alert-danger');
                    messageDiv.html('<strong>Error!</strong> ' + response.message);
                    messageDiv.show();
                }
            },
            error: function(xhr, status, error) {
                var messageDiv = $('#formMessage');
                messageDiv.removeClass('alert-success').addClass('alert-danger');
                messageDiv.html('<strong>Error!</strong> Failed to submit appointment request. Please try again.');
                messageDiv.show();
            },
            complete: function() {
                // Re-enable button
                submitBtn.prop('disabled', false).text(originalBtnText);
            }
        });
    });

    
})(jQuery);
