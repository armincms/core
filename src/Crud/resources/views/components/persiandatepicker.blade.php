
jQuery(document).ready(function($) {  
    $('[role=publishing]').change(function(event) {
        /* Act on the event */
        if($(this).val() !== "{{ \Core\Crud\Statuses::key('scheduled') }}") {
            $($(this).data('target')).closest('p').hide(); 
        } else {
            $($(this).data('target')).closest('p').show();
        }
            
    }).change(); 
    $(".persian-datepicker").persianDatepicker({ 
            showGregorianDate: true,
            persianNumbers: true,
            formatDate: "YYYY/MM/DD hh:mm:ss",
            selectedBefore: false,
            selectedDate: null,
            startDate: null,
            endDate: null,
            prevArrow: '\u25c4',
            nextArrow: '\u25ba',
            theme: 'default',
            alwaysShow: false,
            selectableYears: null,
            selectableMonths: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
            cellWidth: 25, // by px
            cellHeight: 25, // by px
            fontSize: 13, // by px                
            isRTL: false,
            calendarPosition: {
                x: 0,
                y: 0,
            },
            onShow: function () {},
            onHide: function () {},
            onSelect: function () {}
    }); 
});  
@push('links')
<link rel="stylesheet" type="text/css" href="/admin/rtl/css/persianDatepicker-default.css">
@endpush
@push('scripts')
<script src="/admin/rtl/js/persianDatepicker.min.js"></script>
@endpush