<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Teste</title>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link href="css/style.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
</head>

<?php require_once "Task.php"; ?>

<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container-fluid">
			<a class="navbar-brand" href="#">Task Manager</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="#">Home</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="row m-0 d-flex justify-content-center mt-3">
		<div class="col-md-6">

			<div class="card-list">
				<div class="card-list-head">
					<h6 class="p-3">Tarefas</h6>
					<div class="p-3">
						<button id="btn-add" class="btn-success">
							Adicionar
						</button>
					</div>
				</div>

				<?php
					$task = new Task();
					$tasks = $task->list();
				?>

				<?php foreach ($tasks as $task) : ?>
					<?php
						switch($task['priority']) {
							case 1:
								$priority = 'bg-danger';
								break;
							case 2:
								$priority = 'bg-warning';
								break;
							case 3:
								$priority = 'bg-primary';
								break;
						}
					?>
					<div class="card-list-body">
						<div class="card card-task">
							<div class="progress">
								<div class="progress-bar <?= $priority ?>" role="progressbar" style="width: 75%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<div class="card-body">
								<div class="card-title">
									<a href="#">
										<h6 data-filter-by="text" class="H6-filter-by-text"><?= $task['name'] ?> -
											<small class="text-dark font-11">Prazo : <?= date('Y-m-d', strtotime($task['deadline'])) ?></small>
										</h6>
									</a>
								</div>
								<div class="card-meta">
									<div class="d-flex align-items-center">
										<p>
											<?= $task['description'] ?>
										</p>
									</div>
									<div class="dropdown card-options p-0">
										<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
										</button>
										<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
											<li><a class="dropdown-item text-success" href="#">Finalizar</a></li>
											<li><a data-id="<?= $task['id'] ?>" class="dropdown-item text-primary edit-btn" href="#">Editar</a></li>
											<li><a class="dropdown-item text-danger" href="Task.php?task_id=<?= $task['id'] ?>">Deletar</a></li>
										</ul>
									</div>
									<small class="text-small font-11">Criado em : <?= date('Y-m-d', strtotime($task['created'])) ?></small>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>

	<div id="modal-add" class="modal text-dark" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Adicionar tarefa</h5>
					<button type="button" class="close-modal" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<form action="Task.php" method="POST" id="task-form">
						<input id="task-id" name="id" type="hidden">
						<div class="mb-3">
							<label for="task-name" class="form-label">Nome</label>
							<div id="validate-task-name"></div>
							<input name="name" type="text" class="form-control" id="task-name">
						</div>

						<div class="mb-3">
							<label for="task-description" class="form-label">Descrição</label>
							<div id="validate-task-description"></div>
							<textarea name="description" class="form-control" id="task-description" rows="3"></textarea>
						</div>

						<div class="mb-3">
							<label for="task-priority" class="form-label">Prioridade</label>
							<div id="validate-task-priority"></div>
							<select name="priority" class="form-select" id="task-priority" aria-label="Default select example">
								<option value="" selected>Selecione</option>
								<option value="1">Alta</option>
								<option value="2">Média</option>
								<option value="3">Baixa</option>
							</select>
						</div>

						<div class="mb-3">
							<label for="task-deadline" class="form-label">Prazo</label>
							<div id="validate-task-deadline"></div>
							<input name="deadline" type="text" class="form-control" id="task-deadline">
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button id="submit-form" type="button" class="btn btn-success">Salvar</button>
					<button type="button" class="btn btn-danger close-modal" data-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>

	<script src="js/task.js" type="text/javascript"></script>
</body>

</html>