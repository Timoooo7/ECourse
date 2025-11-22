<?php

namespace App\Livewire\Component;

use App\Models\Batch;
use Livewire\Component;
use Livewire\Attributes\On;

class BatchManager extends Component
{
    public $batch_list;
    public $search_name;
    public $filter_day = 0;
    public $b_day = 1;
    public $b_time;
    public $showAddBatchModal = false;
    public $days = [
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        6 => 'Saturday'
    ];
    protected $rules = [
        'b_day' => 'required|numeric',
        'b_time' => 'required',
    ];

    public function render()
    {
        $this->batch_list = Batch::query()->orderBy('day', 'asc')->orderBy('time', 'asc');
        if (!empty($this->search_name)) {
            $this->batch_list->where('id', 'like', '%' . $this->search_name . '%')
                ->orWhereHas('teacher', function ($q) {
                    $q->where('name', 'like', '%' . $this->search_name . '%');
                })
                ->orWhereHas('students', function ($q) {
                    $q->where('name', 'like', '%' . $this->search_name . '%');
                });
        }
        if ($this->filter_day > 0) {
            $this->batch_list->where('day', $this->filter_day);
        }
        $this->batch_list = $this->batch_list->get();
        return view('livewire.component.batch_manager');
    }

    public function mount()
    {
        $this->filter_day = now()->format('w') != 5 ? now()->format('w') : 4;
        // dd($this->filter_day);
    }

    #[On('update-teacher-batch')]
    public function loadBatch()
    {
        $this->batch_list = Batch::all();
    }
    #[On('update-student-batch')]
    public function reloadBatch()
    {
        $this->batch_list = Batch::all();
    }

    public function addBatch()
    {
        $this->validate();
        Batch::create([
            'day' => $this->b_day,
            'time' => $this->b_time,
        ]);
        $this->showAddBatchModal = false;
        $this->dispatch('update-batch');
    }

    public function deleteBatch($id)
    {
        $batch = Batch::find($id);
        $batch->students()->detach();
        $batch->teacher_id = null;
        $batch->save();
        $batch->delete();
        $this->dispatch('update-batch');
        $this->loadBatch();
    }

    public function setPhoneFilterDay($i)
    {
        $this->filter_day = $i;
    }
}
