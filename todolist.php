<?php
session_start();


if (!isset($_SESSION["todoList"])) {
    $_SESSION["todoList"] = array();
}
$todoList = $_SESSION["todoList"];


function appendData($task, $dueDate, $todoList) {
    $todoList[] = ['task' => $task, 'dueDate' => $dueDate, 'done' => false];
    return $todoList;
}


function deleteData($taskToDelete, $todoList) {
    foreach ($todoList as $index => $task) {
        if ($task['task'] === $taskToDelete) {
            unset($todoList[$index]);
        }
    }
    return array_values($todoList); 
}


function toggleTaskStatus($taskToToggle, $todoList) {
    foreach ($todoList as &$task) {
        if ($task['task'] === $taskToToggle) {
            $task['done'] = !$task['done'];
        }
    }
    return $todoList;
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["task"])) {
    if (!empty($_POST["task"])) {
        $dueDate = !empty($_POST["dueDate"]) ? $_POST["dueDate"] : "No due date";
        $todoList = appendData($_POST["task"], $dueDate, $todoList);
        $_SESSION["todoList"] = $todoList;
    } else {
        echo '<script>alert("Error: there is no data to add in array")</script>';
    }
}

if (isset($_GET['delete']) && isset($_GET['task'])) {
    $todoList = deleteData($_GET['task'], $todoList);
    $_SESSION["todoList"] = $todoList;
}


if (isset($_GET['toggle']) && isset($_GET['task'])) {
    $todoList = toggleTaskStatus($_GET['task'], $todoList);
    $_SESSION["todoList"] = $todoList;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple To-Do List</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 600px;
            padding: 20px;
            margin: 10px;
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
            font-size: 2em;
        }
        .task-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            font-size: 1.2em;
        }
        .task-item.done .task-text {
            text-decoration: line-through;
            color: gray;
        }
        .task-item .checkbox {
            transform: scale(1.5);
            margin-right: 10px;
        }
        .form-group input {
            font-size: 1.2em;
        }
        .btn {
            font-size: 1.2em;
        }
    </style>
</head>
<body>

<h1>To-Do List</h1>

<div class="card">
    <div class="card-header">Add a new task</div>
    <div class="card-body">
        <form method="post" action="">
            <div class="form-group">
                <input type="text" class="form-control" name="task" placeholder="Enter your task here">
            </div>
            <div class="form-group">
                <input type="date" class="form-control" name="dueDate" placeholder="Enter due date">
            </div>
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">Tasks</div>
    <ul class="list-group list-group-flush">
        <?php
        foreach ($todoList as $task) {
            $doneClass = $task['done'] ? 'done' : '';
            $checked = $task['done'] ? 'checked' : '';
            echo '<li class="list-group-item task-item ' . $doneClass . '">';
            echo '<div class="d-flex align-items-center">';
            echo '<input type="checkbox" class="checkbox" onclick="window.location.href=\'?toggle=true&task=' . urlencode($task['task']) . '\'" ' . $checked . '> ';
            echo '<span class="task-text">' . htmlspecialchars($task['task']) . ' (Due: ' . htmlspecialchars($task['dueDate']) . ')</span>';
            echo '</div>';
            echo '<a href="?delete=true&task=' . urlencode($task['task']) . '" class="btn btn-danger btn-sm">Delete</a>';
            echo '</li>';
        }
        ?>
    </ul>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
