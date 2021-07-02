$(function() {
    const btnAdd = $('#btn-add');
    const modal = $('#modal-add');
    const btnClose = $('.close-modal');
    const btnSubmit = $('#submit-form');
    const taskFormInputs = $('form#task-form :input');
    const cardDiv = $('#card-div');

    $(function() {
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
    });

    listTasks();

    btnAdd.on('click', () => {
        modal.show();
        $('input, textarea').val('');
    });

    btnClose.on('click', () => {
        modal.hide();
    });

    btnSubmit.on('click', () => {
        validateForm();
    });

    $(document).on('click', '.edit-task', function() {
        modal.show();
        getOneTask($(this).data('id'));
    });

    $(document).on('click', '.delete-task', function() {
        let taskId = $(this).data('id');

        $.ajax({
            url : `Task.php?action=3&task_id=${taskId}`,
            type: 'GET',
            success: data => {
                if (data.status == 'success') {
                    removeTaskCard(taskId);
                }
            }
        });
    });

    function validateForm() {
        let submitForm = true;
        let formData = {};

        taskFormInputs.each(function() {
            if ($(this).val() === '') {
                submitForm = false;
                $(`#validate-${$(this).attr('id')}`).html('<span class="text-danger">Preenchimento obrigatório</span>');
            } else {
                $(`#validate-${$(this).attr('id')}`).html('');
                let inputName = $(this).attr('name');
                formData[inputName] = $(this).val();
            }
        });

        if (submitForm) {
            submitFormTask(formData);
        }
    }

    function listTasks() {
        $.ajax({
            url: 'Task.php?action',
            type: 'GET',
            success: data => {
                $.each(data, function(key, data) {
                    makeCardStructure(data)
                });
            }
        });
    }

    function submitFormTask(data) {
        $.ajax({
            url: 'Task.php?action=1',
            type: 'POST',
            data: data,
            success: data => {
                 if (data.status == 'success') {
                    makeCardStructure(data.task);
                }
            }
        });
    }

    function removeTaskCard(id) {
        let divToRemove = $(`#task-${id}`);

        divToRemove.remove();
    }

    function makeCardStructure(data) {
        let priority;
        let deadlineFormatted = moment(data.deadline).format('DD-MM-YYYY HH:MM');
        let classPriority;

        switch(data.priority) {
            case "1":
                priority = 'Alta';
                classPriority = 'bg-danger';
                break;
            case "2":
                priority = 'Média';
                classPriority = 'bg-info';
                break;
            case "3":
                priority = 'Baixa';
                classPriority = 'bg-primary';
                break;
        }

        let cardStructure = `
            <div id="task-${data.id}" class="col-md-4 d-flex justify-content-center p-3">
                <div class="card text-white ${classPriority} mb-3 card-task">
                    <div class="row m-0">
                        <div class="card-header col-11">${data.name}</div>
                        <div class="card-header col-1">
                            <div class="dropdown">
                                <button class="dropdown-toggle ${classPriority} border-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li><a data-id="${data.id}" class="dropdown-item edit-task" href="#">Editar</a></li>
                                    <li><a data-id="${data.id}" class="dropdown-item delete-task" href="#">Deletar</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="card-text">${data.description}</p>
                        <div>
                            Prazo : ${deadlineFormatted}
                        </div>
                    </div>
                    <div>
                        Prioridade: ${priority}
                    </div>
                </div>
            </div>
        `;

        cardDiv.append(cardStructure)
    }

    function getOneTask(id) {
        $.ajax({
            url: `Task.php?action=4&task_id=${id}`,
            type: 'GET',
            success: data => {
                $.each(data.task, function(key, value) {

                    if ($(`#task-${key}`).length > 0) {
                        $(`#task-${key}`).val(value)
                    }
                });
            }
        });
    }
});
