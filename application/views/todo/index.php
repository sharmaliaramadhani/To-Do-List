<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f0f2f5;
        }

        .todo-card {
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .task-item {
            background-color: #e9f7ef;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }

        .task-item:hover {
            transform: scale(1.02);
        }

        .form-section {
            background-color: #d1f7d6;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(103, 174, 110, 0.5);
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="todo-card mx-auto" style="max-width: 700px;">
            <h2 class="text-center mb-4"><i class="bi bi-list-check me-2"></i>To-Do List</h2>

            <form method="post" action="<?php echo site_url('todo/add'); ?>" class="form-section" style="background-color: #5B913B;">
                <div class="mb-3">
                    <input type="text" name="task" required class="form-control" placeholder="Task" />
                </div>
                <div class="mb-3">
                    <input type="date" name="deadline" id="deadline" class="form-control" required />
                </div>
                <div id="subtasks" class="mb-3">
                    <div class="mb-2 d-flex align-items-center gap-2">
                        <input class="form-control" type="text" name="subtasks[]" placeholder="Subtask" required>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSubtask(this)">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success" type="button" onclick="addSubtask('subtasks')" style="background-color: #77B254;">
                        <i class="bi bi-plus-circle"></i> Add Subtask
                    </button>
                    <button class="btn btn-success" type="submit">
                        <i class="bi bi-check2-circle"></i> Add Task
                    </button>
                </div>
            </form>

            <ul class="list-unstyled mt-4">
                <?php foreach ($tasks as $task): ?>
                    <li class="task-item">
                        <h5 class="mb-1 text-success"><?php echo $task->task; ?></h5>
                        <p class="mb-1"><strong>Deadline:</strong> <?php echo date('d M Y', strtotime($task->deadline)); ?></p>
                        <p class="mb-1"><strong>Status:</strong> <?php echo $task->status; ?></p>

                        <div class="mb-2">
                            <a href="<?php echo site_url('todo/delete/' . $task->id); ?>" class="btn btn-sm btn-outline-danger me-2" style="background-color: #F8F5E9;" onclick="return confirm('Jadwal Telah Di Hapus');">
                                Delete
                            </a>
                            <a href="#" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $task->id; ?>">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        </div>
                        <?php if (!empty($task->subtask)): ?>
    <ul class="ms-3">
    <?php foreach ($task->subtask as $subtask): ?>
        <li>
            <form method="post" action="<?php echo site_url('todo/toggle_subtask/' . $subtask->id); ?>" class="d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input 
                        class="form-check-input" 
                        type="checkbox" 
                        onchange="this.form.submit()" 
                        <?php echo $subtask->is_done ? 'checked' : ''; ?> 
                    />
                    <label class="form-check-label <?php echo $subtask->is_done ? 'text-decoration-line-through text-muted' : ''; ?>">
                        <?php echo $subtask->subtask; ?>
                    </label>
                </div>
                <div>
                    <a href="#" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editSubtaskModal<?php echo $subtask->id; ?>">
                        <i class="bi bi-pencil"></i>
                    </a>
                </div>
            </form>
        </li>

        <!-- Modal Edit Subtask -->
        <div class="modal fade" id="editSubtaskModal<?php echo $subtask->id; ?>" tabindex="-1" aria-labelledby="editSubtaskModalLabel<?php echo $subtask->id; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="<?php echo site_url('todo/update_subtask/' . $subtask->id); ?>">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Subtask</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Subtask</label>
                                <input type="text" name="subtask" class="form-control" value="<?php echo $subtask->subtask; ?>" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </ul>
<?php endif; ?>

                    </li>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?php echo $task->id; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="post" action="<?php echo site_url('todo/update/' . $task->id); ?>">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Task</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Task</label>
                                            <input type="text" name="task" class="form-control" value="<?php echo $task->task; ?>" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deadline</label>
                                            <input type="date" name="deadline" class="form-control" value="<?php echo $task->deadline; ?>" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="Belum dikerjakan" <?php if ($task->status == 'Belum dikerjakan') echo 'selected'; ?>>Belum dikerjakan</option>
                                                <option value="Sedang dikerjakan" <?php if ($task->status == 'Sedang dikerjakan') echo 'selected'; ?>>Sedang dikerjakan</option>
                                                <option value="Selesai" <?php if ($task->status == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                            </select>
                                        </div>
                                        <div id="subtasks<?php echo $task->id; ?>">
                                            <?php foreach ($task->subtask as $subtask): ?>
                                                <div class="mb-2 d-flex align-items-center gap-2">
                                                    <input type="text" name="subtasks[]" class="form-control" value="<?php echo $subtask->subtask; ?>" required />
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSubtask(this)">
                                                        <i class="bi bi-x-circle"></i>
                                                    </button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="btn btn-outline-info btn-sm mt-2" type="button" onclick="addSubtask('subtasks<?php echo $task->id; ?>')">
                                            <i class="bi bi-plus-circle"></i> Add Subtask
                                        </button>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <script>
        function addSubtask(subtasksId) {
            const div = document.createElement('div');
            div.classList.add('mb-2');
            div.innerHTML = `
                <div class="d-flex align-items-center gap-2">
                    <input class="form-control" type="text" name="subtasks[]" placeholder="Subtask" required>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeSubtask(this)">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </div>
            `;
            document.getElementById(subtasksId).appendChild(div);
        }

        function removeSubtask(button) {
            button.closest('.mb-2').remove();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>