(function ($) {
    "use strict";

    function initializeDataTables() {
        $('.class-table').each(function () {
            let tableId = $(this).attr('id');
            if ($.fn.DataTable.isDataTable('#' + tableId)) {
                $('#' + tableId).DataTable().destroy();
            }
            $('#' + tableId).DataTable({
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                "ordering": true,
                "searching": true,
                "language": {
                    "lengthMenu": "Hiển thị _MENU_ dòng",
                    "search": "Tìm kiếm:",
                    "paginate": {
                        "next": "Tiếp",
                        "previous": "Trước"
                    },
                    "info": "Hiển thị _START_ đến _END_ trong _TOTAL_ dòng",
                    "infoEmpty": "Hiển thị 0 đến 0 trong 0 dòng"
                }
            });
        });
    }

    $(document).ready(function () {
        initializeDataTables();
    });

    // Re-initialize tables after form submission
    $('form').on('submit', function () {
        setTimeout(function () {
            initializeDataTables();
        }, 500);
    });
})(jQuery);
