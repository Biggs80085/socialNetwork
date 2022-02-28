jQuery(document).ready(function() {
    /** notification */
    loadnotification();
    setInterval(function() {
        loadnotification();
    }, 5000);

    function loadnotification() {
        $.ajax({
            url: "notification.php",
            method: "POST",
            success: function(data) {
                $('#menu').html(data);
            }
        });
    }

    $('#search').keyup(function() {
        $('#resultat').html('');
        var res = $(this).val();
        var type = $('#type').val();
        if (res != "") {
            $.ajax({
                type: 'GET',
                url: 'search_sending.php',
                data: 'search=' + res + '&type=' + type,
                success: function(data) {
                    if (data != "") {
                        $('#resultat').append(data);
                    } else {
                        document.getElementById('resultat').innerHTML = "<div>Aucun Resultat</div>"
                    }
                }
            });
        }
    });
});