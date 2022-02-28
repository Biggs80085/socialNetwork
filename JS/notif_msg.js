function notif_msg() {
    $.ajax({
        url: "notification_msg.php",
        method: "POST",
        success: function(data) {
            $('#notif_msg').html(data);
        }
    });
}