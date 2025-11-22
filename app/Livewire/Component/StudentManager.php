<?php

namespace App\Livewire\Component;

use App\Models\Batch;
use App\Models\School;
use App\Models\Student;
use Livewire\Component;
use Livewire\Attributes\On;

class StudentManager extends Component
{

    public $student_list;
    public $school_list;
    public $batch_list;
    public $selected_student;
    public $s_name;
    public $s_gender = 'male';
    public $s_school;
    public $us_name;
    public $us_gender;
    public $us_school;
    public $search_name_student;
    public $showAddStudentModal = false;
    public $showAssignStudentModal = false;
    public $showEditStudentModal = false;
    public $days = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        6 => 'Saturday'
    ];
    protected $rules = [
        's_name' => 'required|string',
        's_gender' => 'required|string',
        's_school' => 'nullable|numeric',
    ];

    public function render()
    {
        $this->student_list = Student::query(); // Replace with your model

        if (!empty($this->search_name_student)) {
            $this->student_list->where('name', 'like', '%' . $this->search_name_student . '%');
        }
        $this->student_list = $this->student_list->get();
        return view('livewire.component.student_manager');
    }

    public function mount()
    {
        $this->batch_list = collect([]);
    }

    public function loadSchool()
    {
        $this->school_list = School::all();
    }


    public function addStudent()
    {
        $this->validate();
        Student::create([
            'name' => $this->s_name,
            'gender' => $this->s_gender,
            'school_id' => $this->s_school,
        ]);
        $this->showAddStudentModal = false;
    }

    #[On('update-student-batch')]
    public function loadBatch()
    {
        $this->batch_list = Batch::with(['students'])->orderBy('day', 'asc')->orderBy('time', 'asc')->get();
    }

    #[On('update-batch')]
    public function reloadBatch()
    {
        $this->batch_list = Batch::with(['students'])->orderBy('day', 'asc')->orderBy('time', 'asc')->get();
    }

    public function setStudent($student)
    {
        $this->selected_student = Student::find($student['id']);
        $this->us_name = $this->selected_student->name;
        $this->us_gender = $this->selected_student->gender;
        $this->us_school = $this->selected_student->school_id;
    }

    public function updateStudentBatch($batch_id)
    {
        $batch = Batch::find($batch_id);
        $batch->students()->attach($this->selected_student->id);
        $batch->save();
        $this->showAssignStudentModal = false;
        $this->dispatch('update-student-batch');
    }

    public function editStudent()
    {
        $student = Student::find($this->selected_student->id);
        $student->name = $this->us_name;
        $student->gender = $this->us_gender;
        $student->school_id = $this->us_school;
        $student->save();
        $this->dispatch('update-student-batch');
        $this->showEditStudentModal = false;
    }

    public function deleteStudent()
    {
        $student = Student::find($this->selected_student->id);
        $student->batchs()->detach();
        $student->delete();
        $this->loadBatch();
        $this->selected_student = null;
        $this->dispatch('update-teacher-batch');
        $this->showEditStudentModal = false;
    }
}
