jQuery(document).ready(function() {

    setInterval(function() {
        update_activity();
    }, 1000);

    function update_activity() {
        $.ajax({
            url: "update_activity.php",
            success: function() {}
        });
    }

});