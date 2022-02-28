function load_publi() {
    $.ajax({
        url: "display_publi.php",
        method: "POST",
        success: function(data) {
            $('#display_publi').html(data);
        }
    });
}