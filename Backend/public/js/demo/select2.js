(function($) {
    "use strict";
    $(document).ready(function() {
        $('.student-select').each(function(item) {
            var selectize = $('#'+ $(this).data('student-select')).selectize({
                create: false,
                sortField: 'text',
                placeholder: 'Tìm kiếm học sinh...',
                allowClear: true
            });
        })
        $('.class-select').each(function(item) {
            var selectize = $('#'+ $(this).data('class-select')).selectize({
                create: false,
                sortField: 'text',
                placeholder: 'Tìm kiếm lớp học...',
                allowClear: true
            });
        })
    });
})(jQuery); // End of use strict
