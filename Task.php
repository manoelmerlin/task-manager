<?php
require_once "Database.php";

class Task {
    public function list()
    {
        $db = new Database();
        $tasks = $db->makeQuery('SELECT * FROM tasks ORDER BY priority ASC');

        return $this->jsonResponse($tasks);
    }

    public function add($data)
    {
        $status = 'success';
        $db = new Database();

        $data['deadline'] = date('Y-m-d H:i:s', strtotime(str_ireplace('/', '-', $data['deadline'])));
        $data['deadline'] = str_replace('T', ' ', $data['deadline']) . ':00';

        if (!$db->save('tasks', $data)) {
            $status = 'error';
        }

        return $this->jsonResponse(array(
            'status' => $status,
            'task' => $data,
        ));
    }

    public function edit()
    {

    }

    public function delete()
    {
        $status = 'success';

        $id = $_GET['task_id'];
        $db = new Database();

        if (!$db->delete('tasks', $id)) {
            $status = 'error';
        }

        return $this->jsonResponse(array(
            'status' => $status,
        ));
    }

    public function jsonResponse($data)
    {
        header("Content-Type: application/json; charset=UTF-8");
        print_r(json_encode($data, true));
    }

    public function getOneTask() {
        $id = $_GET['task_id'];
        $db = new Database();

        $task = $db->makeQuery("SELECT * FROM tasks where tasks.id = '" . $id ."'");

        return $this->jsonResponse(array(
            'task' => $task[0],
        ));
    }
}

$action = $_GET['action'];
$task = new Task();

switch($action) {
    case 1:
        $task->add($_POST);
        break;
    case 2:
        $task->edit();
        break;
    case 3:
        $task->delete();
        break;
    case 4:
        $task->getOneTask();
        break;
    default:
    $task->list();
        break;
}