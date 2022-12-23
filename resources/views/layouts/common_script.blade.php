<script>
    function initSelectPicker() {
        $('.selectpicker').select2({
            theme: "bootstrap-5",
            dropdownCssClass: "select2--small",
        });
    }

    function fillArteriskForLabel() {
        var rawData = $('#require-list').val();
        if (!rawData) {
            return false;
        }

        var requireList = (rawData).split(',');
        if (requireList.length > 0) {
            requireList.forEach(function(item) {
                $(`[name=${item}`).parent().find('label').append(
                    `<i class="fa-solid fa-asterisk is-required"></i>`
                );
            });
        }
    }
    (function($) {

        "use strict";

        var fullHeight = function() {

            $('.js-fullheight').css('height', $(window).height());
            $(window).resize(function() {
                $('.js-fullheight').css('height', $(window).height());
            });

        };
        fullHeight();

        initSelectPicker();
        $('.datepicker').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            clearBtn: true,
            todayBtn: true,
            todayHighlight: true,
        }).on('show', function() {
            // $('.datepicker-days').find('.datepicker-switch')
            //     .addClass('datepicker-switch-alt').removeClass('datepicker-switch');
        });

        $('.selectpicker').select2({
            theme: "bootstrap-5",
            dropdownCssClass: "select2--small",
        });

        $('.selectpicker-no-search').select2({
            theme: "bootstrap-5",
            dropdownCssClass: "select2--small",
            minimumResultsForSearch: -1,
        });

        $('#sidebarCollapse').click(function() {
            if ($('#sidebar').hasClass('active')) {
                $('#sidebar').removeClass('active')
            } else {
                $('#sidebar').addClass('active')
            }
        })

        fillArteriskForLabel();
    })(jQuery);
</script>
