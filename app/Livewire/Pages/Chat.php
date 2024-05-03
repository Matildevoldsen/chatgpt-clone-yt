<?php

namespace App\Livewire\Pages;

use App\Models\Conversation;
use Livewire\Component;

class Chat extends Component
{
    public Conversation $conversation;
    public string$title = 'New Chat';

    public function mount()
    {
        if ($this->conversation->title) {
            $this->title = $this->conversation->title;
        }
    }

    public function render()
    {
        return view('livewire.pages.chat')
            ->title($this->title);
    }
}
