<?php
require_once "Database.php";

class Task {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function list()
    {
        if (isset($_GET['status']) && $_GET['status'] == 2) {
            $status = 2;
        } else {
            $status = 1;
        }

        $tasks = $this->db->makeQuery("SELECT * FROM tasks WHERE tasks.status = '" . $status ."' ORDER BY priority ASC");

        return $tasks;
    }

    public function add()
    {
        $data = $_POST;
        $data['deadline'] = date('Y-m-d H:i:s', strtotime(str_ireplace('/', '-', $data['deadline'])));
        $this->db->save('tasks', $data);

        return header('location: index.php');
    }

    public function edit()
    {
        $_POST['deadline'] = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $_POST['deadline'])));

        $this->db->update('tasks', $_POST);
        return header('location: index.php');
    }

    public function delete($id)
    {
        $this->db->delete('tasks', $id);

        return header('location: index.php');
    }

    public function jsonResponse($data)
    {
        header("Content-Type: application/json; charset=UTF-8");
        return print_r(json_encode($data, true));
    }

    public function getOneTask($id) {
        $task = $this->db->makeQuery("SELECT * FROM tasks where tasks.id = '" . $id ."'");

        return $this->jsonResponse(array(
            'task' => $task[0],
        ));
    }

    public function finishTask($id)
    {

        $this->db->makeQuery("UPDATE tasks SET tasks.status = 2 WHERE tasks.id = '" . $id ."'");
        return header('location: index.php');
    }
}

$task = new Task();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['id']))
        $task->edit();
    else
        $task->add();
}

if (isset($_GET['task_id']))
    $task->delete($_GET['task_id']);

if (isset($_GET['id_task']))
    $task->getOneTask($_GET['id_task']);

if (isset($_GET['taskid']) && isset($_GET['finish']))
    $task->finishTask($_GET['taskid']);
