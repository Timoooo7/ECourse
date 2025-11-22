<?php

namespace App\Livewire\Component;

use App\Models\Batch;
use App\Models\Teacher;
use Livewire\Component;

class Schedule extends Component
{
    // constant
    public $days = [
        0 => 'All',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        6 => 'Saturday',
    ];
    // model
    public $search_filter;
    public $day_filter = 0;
    public $teacher_filter = 0;
    public $phone_selected_day;
    // data
    public $teacher_list;
    public $batch_list;

    public function render()
    {
        $this->batch_list = Batch::query()->orderBy('day', 'asc')->orderBy('time', 'asc');
        if (!empty($this->search_filter)) {
            $this->phone_selected_day = 0;
        }
        if ($this->teacher_filter > 0) {
            $this->batch_list->orWhereHas('teacher', function ($q) {
                $q->where('id', $this->teacher_filter);
            });
        }
        if ($this->day_filter > 0) {
            $this->batch_list->where('day', $this->day_filter);
        }
        if (!empty($this->search_filter)) {
            $this->batch_list
                ->orWhereHas('students', function ($q) {
                    $q->where('name', 'like', '%' . $this->search_filter . '%');
                });
        }
        $this->batch_list = $this->batch_list->with(['teacher', 'students'])->get();

        return view('livewire.component.schedule');
    }

    public function mount()
    {
        $this->phone_selected_day = now()->format('w') != 5 ? now()->format('w') : 4;
    }

    public function loadTeacher()
    {
        $this->teacher_list = Teacher::all();
    }

    public function setPhoneDay($i)
    {
        $this->phone_selected_day = $i;
    }
}
