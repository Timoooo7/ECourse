<?php

namespace App\Livewire\Component;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginButton extends Component
{
    public $status;

    public function mount()
    {
        $this->status = Auth::user();
    }

    public function login()
    {
        if (!$this->status) {
            return redirect()->route('login');
        }
    }
    public function render()
    {
        return view('livewire.component.login-button');
    }
}
