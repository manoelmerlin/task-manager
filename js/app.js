
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
        let url;

        if ($("#task-id").val() != '') {
            url = 'Task.php?action=2'
        } else {
            url = 'Task.php?action=1';
        }

        $.ajax({
            url: url,
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
   
    }
});
