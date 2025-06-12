@foreach($chats as $chat)

@include('components.chat_message', ['chat' => $chat])

@endforeach