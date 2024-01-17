(function ($) {
    "use strict";
    
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Product Quantity
    $(document).ready(function () {
        $('.quantity button').on('click', function () {
            var button = $(this);
            var oldValue = button.parent().parent().find('input').val();
            if (button.hasClass('btn-plus')) {
                $.ajax({
                    url: '/cart/add/'+ button.data('prod_id'),
                    type: 'get',
                    data: {},
                    success: function(response){ 
                        button.parent().parent().find('input').val(response.quantity);
                        $("#price_"+button.data('prod_id')).html(parseFloat(response.price).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                        $("#subtotalPrice").html(parseFloat(response.subtotalPrice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                        $("#totalPrice").html(parseFloat(response.totalPrice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                        $("#shippingPrice").html(parseFloat(response.cheapestShipping).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                        if (response.quantity > 1) {
                            button.parent().parent().find('button').first().prop('disabled',false);
                        }
                        if (response.productStock < 1) {
                            button.prop('disabled',true);
                        }
                    }
                });
            } else {
                if (oldValue > 1) {
                    var newVal = parseFloat(oldValue) - 1;
                    $.ajax({
                        url: '/cart/sub/'+ button.data('prod_id'),
                        type: 'get',
                        data: {},
                        success: function(response){ 
                            button.parent().parent().find('input').val(response.quantity);
                            $("#price_"+button.data('prod_id')).html(parseFloat(response.price).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                            $("#subtotalPrice").html(parseFloat(response.subtotalPrice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                            $("#totalPrice").html(parseFloat(response.totalPrice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                            $("#shippingPrice").html(parseFloat(response.cheapestShipping).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');    
                            if (response.quantity > 1) {
                                button.parent().parent().find('button').prop('disabled',false);
                            }
                            if (response.quantity == 1) {
                                button.prop('disabled',true);
                            }
                        }
                    });
                } else {
                    newVal = 1;
                    button.prop('disabled',true);
                }
            }
        });
    });

    $(document).ready(function () {
        $('.shipment-list').find('input[type=radio]').click(function(event) {
            console.log(event.target.id)
            $.ajax({
                url: '/checkout/shipping/' + event.target.id,
                type: 'get',
                data: {},
                success: function(response){ 
                    $("#shippingPrice").html(parseFloat(response.shippingPrice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                    $("#totalPrice").html(parseFloat(response.totalPrice).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1 ").toString().replace('.',',') + ' zł');
                }
            });
        });
    });


})(jQuery);

