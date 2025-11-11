$(document).ready(function () {
    function updateDateTime() {
        const now = new Date();
        const dateOpt = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        $("#currentDate").text(now.toLocaleDateString("vi-VN", dateOpt));
        $("#currentTime").text(now.toLocaleTimeString("vi-VN"));
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
    $(".btn-group .btn").on("click", function () {
        $(".btn-group .btn").removeClass("active");
        $(this).addClass("active");
    });
    $(".stats-card").hover(
        function () { $(this).addClass("hover-card"); },
        function () { $(this).removeClass("hover-card"); }
    );

});
