// Call the dataTables jQuery plugin
(function($) {
    "use strict";
    $(document).ready(function() {
        $('.class-table').each(function() {
            let tableId = $(this).data('class-table');
            let table = new DataTable('#' + tableId);
        });
    });
})(jQuery); // End of use strict
