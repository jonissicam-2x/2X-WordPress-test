jQuery(function ($) {

    function loadExperts() {
        $.ajax({
            url: expertAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'filter_experts',
                industry: $('#industry-filter').val(),
                location: $('#location-filter').val(),
                search: $('#expert-search').val()
            },
            success: function (response) {
                $('#roster-results').html(response);
            },
            error: function(xhr) {
                // console.log(xhr.responseText);
            }
        });

    }

$('#industry-filter').on('change', loadExperts);
$('#location-filter').on('change', loadExperts);
    $('#expert-search').keyup(loadExperts);

});
