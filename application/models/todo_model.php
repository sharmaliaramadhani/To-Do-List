<?php
class todo_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    public function get_tasks()
    {
        $tasks = $this->db->get('tasks')->result();
        foreach ($tasks as $task) {
            $task->subtask = $this->db->get_where('subtasks', ['task_id' => $task->id])->result();
        }
        return $tasks;
    }

    public function add_task($task, $deadline)
    {
        $this->db->insert('tasks', [
            'task' => $task,
            'deadline' => $deadline,
            'status' => 'Belum dikerjakan'
        ]);
        return $this->db->insert_id();
    }

    public function add_subtask($task_id, $subtask)
    {
        return $this->db->insert('subtasks', [
            'task_id' => $task_id,
            'subtask' => $subtask
        ]);
    }

    public function delete_task($id)
    {
        $this->db->delete('subtasks', ['task_id' => $id]);
        return $this->db->delete('tasks', ['id' => $id]);
    }

    public function update_task($id, $task, $deadline, $status)
    {
        $this->db->where('id', $id);
        $this->db->update('tasks', [
            'task' => $task,
            'deadline' => $deadline,
            'status' => $status
        ]);
    }

    public function delete_subtasks($task_id)
    {
        $this->db->delete('subtasks', ['task_id' => $task_id]);
    }

    public function delete_subtask($id)
    {
        return $this->db->delete('subtasks', ['id' => $id]);
    }

    public function update_subtask($id, $subtask)
    {
        $this->db->where('id', $id);
        return $this->db->update('subtasks', ['subtask' => $subtask]);
    }

    public function update_subtask_status($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update('subtasks', ['is_done' => $status]);
    }

    public function get_subtask_by_id($id)
    {
        return $this->db->get_where('subtasks', ['id' => $id])->row();
    }
}
