$(function() {
    const btnAdd = $('#btn-add');
    const modal = $('#modal-add');
    const btnClose = $('.close-modal');
    const btnSubmit = $('#submit-form');
    const taskFormInputs = $('form#task-form :input');
    const taskForm = $('from#task-form');
    const editBtn = $(".edit-btn")

    $('#task-deadline').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 1901,
        maxYear: parseInt(moment().format('YYYY'),10),
        timePicker: true,
        timePicker24Hour: true,
        startDate: moment().startOf('hour'),
        endDate: moment().startOf('hour').add(32, 'hour'),
        autoUpdateInput: false,
        locale: {
            format: 'DD/MM/YYYY',
        }
    })
    .on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm')).prev().css({
            opacity: 0,
            display: 'none',
        });
    });

    btnAdd.on('click', () => {
        modal.show();
        $('input, textarea').val('');
        taskFormInputs.each(function() {
            $(`#validate-${$(this).attr('id')}`).html('');
        });
    });

    btnClose.on('click', () => {
        taskFormInputs.each(function() {
           $(this).val('');
        });

        modal.hide();
    });

    btnSubmit.on('click', () => {
        validateForm();
    });

    editBtn.on('click', function() {
        modal.show();
        let taskId = $(this).data('id');

        $.ajax({
            url: `Task.php?&id_task=${taskId}`,
            type: 'GET',
            success: data => {
                $.each(data.task, function(key, value) {
                    if ($(`#task-${key}`).length > 0) {
                        $(`#task-${key}`).val(value)
                    }
                });
            }
        });
    });

    function validateForm() {
        let submitForm = true;

        taskFormInputs.each(function() {
            if ($(this).val() === '' && $(this).attr('name') != 'id') {
                submitForm = false;
                $(`#validate-${$(this).attr('id')}`).html('<span class="text-danger">Preenchimento obrigatório</span>');
            } else {
                $(`#validate-${$(this).attr('id')}`).html('');
            }
        });

        if (submitForm) {
            $('#task-form').submit();
        }
    }
});