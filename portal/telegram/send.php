<?php
function escapeMarkdownV2($text) {
    $escape_chars = ['_', '*', '[', ']', '(', ')', '~', '`', '>', '#', '+', '-', '=', '|', '{', '}', '.', '!'];
    foreach ($escape_chars as $char) {
        $text = str_replace($char, '\\' . $char, $text);
    }
    return $text;
}

function sendTelegramMessage($chat_id, $text)
{
    try {
        $bot_token = '6046108813:AAG-wbm2d3YMAEr8YHVmtUPqHCYi8kBmCUk';
        $url = "https://api.telegram.org/bot$bot_token/sendMessage";

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
            'parse_mode' => 'MarkdownV2'
        ];

        $options = [
            'http' => [
                'method'  => 'POST',
                'header'  => "Content-Type:application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        file_get_contents($url, false, $context);
    } catch (\Throwable $th) {
        echo "There was an error: " . $th->getMessage();
    }
}

if (isset($_POST['chat_id']) && isset($_POST['text'])) {
    try {
        $chat_id = $_POST['chat_id'];
        $text = $_POST['text'];

        sendTelegramMessage($chat_id, $text);
        echo "Message sent to chat ID: $chat_id";
    } catch (\Throwable $th) {
        echo "There was an error: " . $th->getMessage();
    }
} else {
    // echo "Chat ID or message not provided.";
}
