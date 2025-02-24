<?php
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteUpdated implements ShouldBroadcast {
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $postId;
    public $newCount;
    public $userId;

    public function __construct($postId, $newCount) {
        $this->postId = $postId;
        $this->newCount = $newCount;
    }

    public function broadcastOn() {
        return new Channel('post-votes');
    }

    public function broadcastAs() {
        return 'vote.updated';
    }
}
