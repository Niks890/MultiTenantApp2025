$(document).ready(function () {
    const $connectionSelect = $('#tenancy_db_connection');
    const $hostInput = $('#tenancy_db_host');
    const $portInput = $('#tenancy_db_port');

    function updateHostAndPort() {
        const $selectedOption = $connectionSelect.find('option:selected');
        const host = $selectedOption.data('host');
        const port = $selectedOption.data('port');

        if (host && port) {
            $hostInput.val(host).addClass('highlighted');
            $portInput.val(port).addClass('highlighted');

            setTimeout(function () {
                $hostInput.removeClass('highlighted');
                $portInput.removeClass('highlighted');
            }, 100);
        }
    }

    $connectionSelect.on('change', updateHostAndPort);
    updateHostAndPort();
});
