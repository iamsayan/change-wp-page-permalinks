jQuery(document).ready(function($) {
    $('#saveForm').submit(function() {
        $('#progressMessage').show();
        $(".save-settings").addClass("disabled");
        $(".save-settings").val( CWPPLocalizeScript.saving );
        $(this).ajaxSubmit({
            success: function() {
                $('#progressMessage').fadeOut();
                $('#saveMessage').show().delay(4000).fadeOut();
                $(".save-settings").removeClass("disabled");
                $(".save-settings").val( CWPPLocalizeScript.savemsg );
                if ($('#change-trigger').val() == 'yes') {
                    $.ajax({
                        type: "POST",
                        url: CWPPLocalizeScript.ajaxurl,
                        dataType: "json",
                        data: {
                            action: "cwpp_trigger_flush_rewrite_rules",
                        }
                    });
                }
                var link = $('#view-link');
                link.attr('href', link.data('link') + $('#rule').val());
            }
        });
        return false;
    });
    $('.cwpp-change').change(function() {
        $('#change-trigger').val('yes');
    });
    $('#rewrite-rule').on('click', function() {
        $('#change-trigger').val('yes');
    }); 
    $("#rewrite-rule").change(function() {
        if ($('#rewrite-rule').is(':checked')) {
            $('.custom-rule').show();
            $('#rule').attr('required', 'required');
        }
        if (!$('#rewrite-rule').is(':checked')) {
            $('.custom-rule').hide();
            $('#rule').removeAttr('required');
        }
    });
    $("#rewrite-rule").trigger('change');
    $(".coffee-amt").change(function() {
        var btn = $('.buy-coffee-btn');
        btn.attr('href', btn.data('link') + $(this).val());
    });
    $(".coffee-amt").trigger('change');
});