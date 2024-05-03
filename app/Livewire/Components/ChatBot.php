<?php

namespace App\Livewire\Components;

use App\Models\Conversation;
use App\Models\Message;
use Livewire\Component;
use OpenAI\Laravel\Facades\OpenAI;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class ChatBot extends Component
{
    public Conversation $conversation;
    public $messages = [];
    public string $model;
    public $answer = null;
    public bool $responding = false;
    protected $listeners = ['messageAdded' => 'loadMessages'];

    public function mount()
    {
        $this->loadMessages();
    }

    public function loadMessages(): void
    {
        $this->messages = $this->conversation->messages()->oldest()->get()->map(function (Message $message) {
            $message->content = app(MarkdownRenderer::class)
                ->highlightTheme('github-dark')
                ->toHtml($message->content);

            return $message;
        });
    }

    public function sendMessage($text): void
    {
        $this->conversation->messages()->create([
            'content' => $text,
            'is_user_message' => true
        ]);
    }

    public function respond(): void
    {
        $allMessages = $this->conversation->messages->map(function (Message $message) {
            return ['role' => $message->is_user_message ? 'user' : 'system', 'content' => $message->content];
        })->toArray();

        if (is_null($this->conversation->title)) {
            $title = $this->generateTitle($allMessages);
            $this->js("document.title = '{$title}'");
            $this->conversation->update(['title' => $title]);
        }

        $stream = OpenAI::chat()->createStreamed([
            'model' => $this->model ?? 'gpt-3.5-turbo',
            'messages' => $allMessages,
        ]);

        $entireMessage = '';

        foreach ($stream as $response) {
            $this->responding = false;
            $this->answer = $response->choices[0]->delta->content;
            $entireMessage .= $this->answer;
            $this->stream(to: 'response', content: $this->answer);
        }

        $this->answer = $entireMessage;

        //Store Conversation into the database from AI
        $this->conversation->messages()->create([
            'content' => $entireMessage,
            'is_user_message' => false
        ]);

        //dispatch event to update the conversation
        $this->dispatch('messageAdded');
    }

    private function generateTitle(array $messages): string
    {
        $prompt = [
            'role' => 'system',
            'content' => 'Create a title based on previous messages, without anything but the title. Title should be without quotation marks and not be prefixed with anything like "Title:"'
        ];

        $messagesWithInstruction = array_merge($messages, [$prompt]);
        $response = OpenAI::chat()->create([
           'model' => 'gpt-3.5-turbo',
           'messages' => $messagesWithInstruction,
        ]);
        if (!empty($response->choices)) {
            $choices = $response->choices;
            $titleResponse = end($choices);
            return $titleResponse->message->content ?? 'New Chat';
        }

        return 'New Chat';
    }

    public function clearState(): void
    {
        $this->responding = false;
        $this->answer = null;
    }

    public function render()
    {
        return view('livewire.components.chat-bot');
    }
}
