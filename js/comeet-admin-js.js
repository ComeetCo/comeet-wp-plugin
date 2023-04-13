jQuery(document).ready(function($){

    $('.branding_categories').change(function(){
        var selected_value = $(this).val();
        if(selected_value == 'default'){
            $('.comeet_default_disabled').show();
            $('.comeet_val_select').hide();
        } else {
            $('.comeet_default_disabled').hide();
            $('.comeet_default_disabled').attr('disabled', 'disabled');
            $('.comeet_val_select').hide();
            $('.branding_selected_value_'+selected_value).show();
            $('.branding_selected_value_'+selected_value).attr('disabled', false);
        }
    });

    $("#comeet_field_website").click(function(){
        if($(this).is(":checked")){
            $(".comeet_website_required input").attr("disabled", false);
        } else {
            $(".comeet_website_required input").attr("disabled", true);
            $(".comeet_website_required input").prop("checked", false);
        }
    });

    $("#comeet_field_coverletter").click(function(){
        if($(this).is(":checked")){
            $(".comeet_coverletter_required input").attr("disabled", false);
        } else {
            $(".comeet_coverletter_required input").attr("disabled", true);
            $(".comeet_coverletter_required input").prop("checked", false);
        }
    });

    $("#comeet_field_portfolio").click(function(){
        if($(this).is(":checked")){
            $(".comeet_portfolio_required input").attr("disabled", false);
        } else {
            $(".comeet_portfolio_required input").attr("disabled", true);
            $(".comeet_portfolio_required input").prop("checked", false);
        }
    });

    $("#comeet_field_personalnote").click(function(){
        if($(this).is(":checked")){
            $(".comeet_personalnote_required input").attr("disabled", false);
        } else {
            $(".comeet_personalnote_required input").attr("disabled", true);
            $(".comeet_personalnote_required input").prop("checked", false);
        }
    });

    if(!$("#comeet_social_sharing_on_positions").is(":checked") && !$("#comeet_social_sharing_on_careers").is(":checked")){
        $(".comeet_social_options").hide();
    }
    $("#comeet_social_sharing_on_positions, #comeet_social_sharing_on_careers").click(function(){
        if(!$("#comeet_social_sharing_on_positions").is(":checked") && !$("#comeet_social_sharing_on_careers").is(":checked")){
            $(".comeet_social_options").hide();
        } else {
            $(".comeet_social_options").show();
        }
    });

    $('#comeet_404_option').change(function(){
        console.log('Change detected');
        var comeet_404_option_selected_value = $(this).val();
        if(comeet_404_option_selected_value == 'redirect_to_page'){
            $('#error_404_page').attr('disabled', false);
        } else {
            $('#error_404_page').attr('disabled', true);
            $('#error_404_page').val('-1');
        }
    });

    $('.comeet_option_title_wrap').click(function(){
        $(this).find('.dashicons').toggleClass('comeet_rotate_icon_right');
        let section_to_open = $(this).find('h2').data('section');
        console.log('Sectio to open is: ',section_to_open);
        $('.'+section_to_open).toggleClass('comeet_normal_height');

        /*$('.'+section_to_open).toggle({
         'height' : '725px'
        }); */
    });


});