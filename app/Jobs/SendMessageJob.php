<?php

namespace App\Jobs;

use App\Models\MessageQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Http\Client\Response;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $messages; // अब multiple messages handle करेंगे

    public function __construct($messages)
    {
        // एक या multiple messages आ सकते हैं
        $this->messages = is_array($messages) ? $messages : [$messages];
    }

public function handle()
{
    if (empty($this->messages)) {
        Log::error("❌ No messages to send in job.");
        return;
    }

    $messages = $this->messages; // 👉 closure में use करने के लिए copy

    $responses = Http::pool(function ($pool) use ($messages) {

        $requests = [];

        foreach ($messages as $message) {

            // ChatWay send-message API
            $params = [
                'username' => 'acharyadronacharyaqqqq',
                'message'  => $message->content,
                'token'    => 'Z1p6RUp6YXdycDJmS291RGt2aGVoZz09qqq',
                'number'   => '91' . $message->receiver_number,
            ];

            $url = "https://int.chatway.in/api/send-msg?" . http_build_query($params);

            // Add request to pool
            $requests[] = $pool->timeout(10)->get($url);
        }

        return $requests;
    });

    // Process Pool Responses
    foreach ($responses as $index => $response) {

        $message = $this->messages[$index];

        if ($response instanceof \Illuminate\Http\Client\Response && $response->successful()) {

            // SUCCESS
            $message->message_status = 1;
            $message->sent_at = now();
            $message->response = $response->body();
            $message->save();

            Log::info("✅ Sent to {$message->receiver_number}");

        } else {

            // FAILURE
            $errorMessage = $response instanceof \Illuminate\Http\Client\Response
                ? $response->body()
                : ($response instanceof \Throwable
                    ? $response->getMessage()
                    : 'Unknown error');

            $message->message_status = 2;
            $message->response = $errorMessage;
            $message->save();

            Log::error("❌ Failed to send to {$message->receiver_number} — {$errorMessage}");
        }
    }
}




}
