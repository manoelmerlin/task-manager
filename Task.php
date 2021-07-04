<?php
require_once "Database.php";

class Task {
    public function list()
    {
        $db = new Database();
        $tasks = $db->makeQuery('SELECT * FROM tasks ORDER BY priority ASC');
        return $tasks;
    }

    public function add()
    {
        $db = new Database();
        $data = $_POST;
        $data['deadline'] = date('Y-m-d H:i:s', strtotime(str_ireplace('/', '-', $data['deadline'])));
        $db->save('tasks', $data);

        return header('location: index.php');
    }

    public function edit()
    {
        $db = new Database();
        $data['deadline'] = date('Y-m-d H:i:s', strtotime(str_ireplace('/', '-', $_POST['deadline'])));

        $db->update('tasks', $_POST);
        return header('location: index.php');
    }

    public function delete($id)
    {
        $db = new Database();
        $db->delete('tasks', $id);

        return header('location: index.php');
    }

    public function jsonResponse($data)
    {
        header("Content-Type: application/json; charset=UTF-8");
        print_r(json_encode($data, true));
    }

    public function getOneTask($id) {
        $db = new Database();

        $task = $db->makeQuery("SELECT * FROM tasks where tasks.id = '" . $id ."'");

        return $this->jsonResponse(array(
            'task' => $task[0],
        ));
    }
}

$task = new Task();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id']))
        $task->edit();
    else
        $task->add();
}

if (isset($_GET['task_id'])) {
    $task = new Task();
    $task->delete($_GET['task_id']);
}

if (isset($_GET['id_task'])) {
    $task = new Task();
    $task->getOneTask($_GET['id_task']);
}