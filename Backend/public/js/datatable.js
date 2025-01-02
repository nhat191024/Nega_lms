(function ($) {
    "use strict";

    function initializeSelectPicker() {
        $('.selectpicker').selectpicker({
            liveSearch: true,
            size: 10,
            width: '100%',
            noneResultsText: 'Không tìm thấy kết quả {0}'
        });
    }

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
        initializeSelectPicker();
        initializeSpecificDataTables();
    });

    // Re-initialize tables after form submission
    $('form').on('submit', function () {
        setTimeout(function () {
            initializeDataTables();
            initializeSelectPicker();
        }, 500);
    });

    function initializeSpecificDataTables() {
        $('#averageScoreTable').DataTable({
            "pageLength": 5,
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(được lọc từ _MAX_ tổng số bản ghi)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "previous": "Trước",
                    "next": "Tiếp theo",
                    "last": "Cuối cùng"
                }
            }
        });

        $('#topSubmissionsTable').DataTable({
            "pageLength": 5,
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(được lọc từ _MAX_ tổng số bản ghi)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "previous": "Trước",
                    "next": "Tiếp theo",
                    "last": "Cuối cùng"
                }
            }
        });

        $('#latestStudentsTable').DataTable({
            "pageLength": 5,
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(được lọc từ _MAX_ tổng số bản ghi)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "previous": "Trước",
                    "next": "Tiếp theo",
                    "last": "Cuối cùng"
                }
            }
        });

        $('#quiz, #quiz-published, #quiz-private, #quiz-close, #question').DataTable({
            "pageLength": 5,
            "language": {
                "lengthMenu": "Hiển thị _MENU_ bản ghi mỗi trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ bản ghi",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(được lọc từ _MAX_ tổng số bản ghi)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu tiên",
                    "previous": "Trước",
                    "next": "Tiếp theo",
                    "last": "Cuối cùng"
                }
            }
        });
        
    }
})(jQuery);
