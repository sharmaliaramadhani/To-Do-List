<?php
class Todo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('todo_model');
        $this->load->helper('url');
    }

    public function index()
    {
        $data['tasks'] = $this->todo_model->get_tasks();
        $this->load->view('todo/index', $data);
    }

    public function add()
    {
        $task = $this->input->post('task');
        $deadline = $this->input->post('deadline');

        if ($task) {
            $task_id = $this->todo_model->add_task($task, $deadline);
            $subtasks = $this->input->post('subtasks');

            if (!empty($subtasks)) {
                foreach ($subtasks as $subtask) {
                    $this->todo_model->add_subtask($task_id, $subtask);
                }
            }
        }
        redirect('todo');
    }

    public function delete($id)
    {
        $this->todo_model->delete_task($id);
        redirect('todo');
    }

    public function update($id)
    {
        $task = $this->input->post('task');
        $deadline = $this->input->post('deadline');
        $status = $this->input->post('status');

        if ($task) {
            $this->todo_model->update_task($id, $task, $deadline, $status);
            $this->todo_model->delete_subtasks($id);

            $subtasks = $this->input->post('subtasks');
            if (!empty($subtasks)) {
                foreach ($subtasks as $subtask) {
                    $this->todo_model->add_subtask($id, $subtask);
                }
            }
        }
        redirect('todo');
    }

    public function delete_subtask($id)
    {
        $this->todo_model->delete_subtask($id);
        redirect('todo');
    }

    public function update_subtask($id)
    {
        $subtask = $this->input->post('subtask');
        if ($subtask) {
            $this->todo_model->update_subtask($id, $subtask);
        }
        redirect('todo');
    }

    public function toggle_subtask($id)
    {
        $subtask = $this->todo_model->get_subtask_by_id($id); // FIXED
        $new_status = $subtask->is_done ? 0 : 1;
        $this->todo_model->update_subtask_status($id, $new_status); // FIXED
        redirect($_SERVER['HTTP_REFERER']);
    }
}
