jQuery(document).ready(function($) {
    $(document).on('click', '.nanopay-tip-link-container', function(e) {
        e.preventDefault();
        var authorSlug = $(this).data('author-id');
        
        $.ajax({
            url: nanopay_tipping_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'nanopay_get_author_info',
                author_id: authorSlug  // We're now sending the author slug
            },
            success: function(response) {
                if (response.success) {
                    openNanoPayTip(response.data.address, response.data.default_tip);
                } else {
                    alert('Unable to tip: ' + response.data);
                }
            },
            error: function() {
                alert('An error occurred. Please try again later.');
            }
        });
    });

    function openNanoPayTip(address, amount) {
        NanoPay.open({
            address: address,
            amount: amount,
            currency: 'NANO',
            message: 'Thank you for the great content!',
            position: 'bottom',
            success: function(block) {
                console.log('Tip successful:', block);
                alert('Thank you for your tip!');
            },
            cancel: function() {
                console.log('Tip cancelled');
            }
        });
    }
});