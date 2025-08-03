<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\Novitest;

class BroadcastDemo extends Component
{
    public $messages = [];

    protected $listeners = [
        'echo:demo-channel,demo-event' => 'onEventReceived'
    ];

    public function send()
    {
        broadcast(new Novitest('Pozdrav sa servera!'));
    }

    public function onEventReceived($payload)
    {
        $this->messages[] = $payload['message'] ?? 'Nema poruke';
    }

    public function render()
    {
        return view('livewire.broadcast-demo');
    }
}
