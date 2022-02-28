$(document).ready(function() {

    /** pop notif */
    loadnotificationpop();
    setInterval(function() {
        loadnotificationpop();
    }, 5000);

    function loadnotificationpop() {
        $.ajax({
            url: "notification_pop.php",
            method: "POST",
            success: function(data) {
                $('#notif').html(data);
            }
        })
    }
    /** Fin pop notif */


    $('#findfriend').keyup(function() {
        search($(this).val());
    });

    function search(value) {
        $('#affiche div').each(function() {
            var find = 'false';
            $(this).each(function() {
                if ($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {
                    find = 'true';
                }
            });
            if (find == 'true') {
                $(this).show();
            } else {
                $(this).hide();
            }

        });
    }

});