<?php

namespace App\Livewire\Component;

use App\Models\Batch;
use App\Models\Teacher;
use Livewire\Component;
use Livewire\Attributes\On;

class TeacherManager extends Component
{
    public $teacher_list;
    public $batch_list;
    public $t_name;
    public $t_gender = 'male';
    public $ut_name;
    public $ut_gender;
    public $ut_color;
    public $selected_teacher;
    public $selected_teacher_name;
    public $selected_teacher_id;
    public $b_id;
    public $search_name;
    public $showAddTeacherModal = false;
    public $showAssignTeacherModal = false;
    public $showEditTeacherModal = false;
    public $days = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        6 => 'Saturday'
    ];
    public $colors = [
        'red',
        'orange',
        'amber',
        'yellow',
        'lime',
        'green',
        'emerald',
        'teal',
        'cyan',
        'sky',
        'blue',
        'violet',
        'fuchsia',
        'pink',
        'rose',
    ];
    protected $rules = [
        't_name' => 'required|string',
        't_gender' => 'required|string',
    ];

    public function render()
    {
        $this->teacher_list = Teacher::query(); // Replace with your model

        if (!empty($this->search_name)) {
            $this->teacher_list->where('name', 'like', '%' . $this->search_name . '%');
        }

        $this->teacher_list = $this->teacher_list->get();
        return view('livewire.component.teacher_manager');
    }

    public function mount()
    {
        $this->batch_list = collect([]);
    }


    public function addTeacher()
    {
        $this->validate();
        Teacher::create([
            'name' => $this->t_name,
            'gender' => $this->t_gender,
        ]);
        $this->showAddTeacherModal = false;
    }

    public function assignTeacher()
    {
        $batch = Batch::find($this->b_id);
        $batch->teachers()->attach($this->selected_teacher->id);
        $this->showAssignTeacherModal = false;
    }

    public function setTeacher($teacher)
    {
        $this->selected_teacher_name = $teacher['name'];
        $this->selected_teacher_id = $teacher['id'];
        $this->selected_teacher = Teacher::find($teacher['id']);
        $this->ut_name = $this->selected_teacher->name;
        $this->ut_gender = $this->selected_teacher->gender;
        $this->ut_color = $this->selected_teacher->color;
    }

    #[On('update-teacher-batch')]
    public function loadBatch()
    {
        $this->batch_list = Batch::with(['teacher'])->orderBy('day', 'asc')->orderBy('time', 'asc')->get();
    }

    #[On('update-batch')]
    public function reloadBatch()
    {
        $this->batch_list = Batch::with(['teacher'])->orderBy('day', 'asc')->orderBy('time', 'asc')->get();
    }

    public function updateTeacherBatch($batch_id)
    {
        $batch = Batch::find($batch_id);
        if ($batch->teacher_id > 0 && $batch->teacher_id != $this->selected_teacher_id) {
            return $this->js("alert('Other teacher is already assigned to this batch.')");
        }
        $batch->teacher_id = $batch->teacher_id ? null :  $this->selected_teacher_id;
        $batch->save();
        $this->dispatch('update-teacher-batch');
    }

    public function editTeacher()
    {
        $teacher = Teacher::find($this->selected_teacher->id);
        $teacher->name = $this->ut_name;
        $teacher->gender = $this->ut_gender;
        $teacher->color = $this->ut_color;
        $teacher->save();
        $this->dispatch('update-teacher-batch');
        $this->showEditTeacherModal = false;
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::find($id);
        $teacher->batchs()->update(['teacher_id' => null]);
        $teacher->delete();
        $this->loadBatch();
        $this->selected_teacher = null;
        $this->selected_teacher_id = null;
        $this->selected_teacher_name = null;
        $this->dispatch('update-teacher-batch');
        $this->showEditTeacherModal = false;
    }

    public function setColor($c)
    {
        $this->ut_color = $c;
    }
}
