<?php

namespace App\Livewire\Components;

use Livewire\Component;

class Sidebar extends Component
{
    public $conversations;

    public function mount()
    {
        $this->conversations = auth()->user()->conversations()->limit(10)->orderBy('updated_at', 'desc')->get();
    }

    public function new()
    {
        auth()->user()->conversations()->create([]);
    }
    public function render()
    {
        return view('livewire.components.sidebar');
    }
}
