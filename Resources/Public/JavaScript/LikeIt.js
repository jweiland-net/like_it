$(document).ready(function() {

    // Initially check if user has liked and refresh amount of like
    $('.tx-likeit-container').each(function () {
        var $container = $(this);
        var table = $container.data('table');
        var uid = $container.data('uid');

        $.ajax({
            url: '/index.php?eID=tx_likeit_like&action=check&table=' + table + '&uid=' + uid,
            success: function (result) {
                processResult($container, result);
            }
        });
    });

    // Toggle like and refresh amount of like
    $('.tx-likeit-like').on('click', function(e) {
        e.preventDefault();
        var $container = $(this).parent('.tx-likeit-container');
        var table = $container.data('table');
        var uid = $container.data('uid');

        $.ajax({
            url: '/index.php?eID=tx_likeit_like&action=toggle&table=' + table + '&uid=' + uid,
            success: function (result) {
                processResult($container, result);
            }
        });
    });
});

/**
 * Process ajax result from actions check OR toggle.
 *
 * @param $container
 * @param result
 */
function processResult($container, result) {
    $container.find('.tx-likeit-amount-of-likes .amount-of-likes').text(result.amountOfLikes);
    if (result.liked === true) {
        $container.find('.like-message').text(TYPO3.settings.tx_likeit.message_liked);
        $container.find('.tx-likeit-like').addClass('liked');
    } else {
        $container.find('.like-message').text(TYPO3.settings.tx_likeit.message_not_liked);
        $container.find('.tx-likeit-like').removeClass('liked');
    }
}
