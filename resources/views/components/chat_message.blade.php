@if($chat->user_id == Auth::user()->id || $chat->user->role ==
Auth::user()->role)
<div class="chat-item mb-3">
    <div class="row align-items-end justify-content-end">
        <div class="col col-lg-10">
            <div class="chat-bubble chat-bubble-me">
                @if($chat->del == 0)
                <div class="chat-bubble-title">
                    <div class="row">
                        <div class="col chat-bubble-author">
                            {{ $chat->user->name }}
                        </div>
                        <div class="col-auto chat-bubble-date fs-4">
                            {{ $chat->formatted_date }}</div>
                    </div>
                </div>
                <div class="chat-bubble-body">
                    <p>{{ $chat->message }}</p>
                </div>
                @if($chat->user->id == Auth::user()->id)
                <span class="fs-5">
                    <a href="{{ route('vendor.deletechat', ['id' => $chat->id]) }}">delete</a>
                </span>
                @endif
                @else
                <div class="row">
                    <div class="col">
                        <p>
                            <i class="fa fa-ban"></i>
                            Deleted message
                        </p>
                        <span class="fs-5">{{ $chat->formatted_date }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-auto">
            <span class="avatar avatar-1">
                <i class="fa fa-user-shield p-auto"></i>
            </span>
        </div>
    </div>
</div>
@else
<div class="chat-item mb-3">
    <div class="row align-items-end">
        <div class="col-auto">
            <span class="avatar avatar-1">
                <i class="fa fa-user  p-auto"></i>
            </span>
        </div>
        <div class="col col-lg-10">
            <div class="chat-bubble">
                @if($chat->del == 0)
                <div class="chat-bubble-title">
                    <div class="row">
                        <div class="col chat-bubble-author">
                            {{ $chat->user["name"] }}
                        </div>
                        <div class="col-auto chat-bubble-date">
                            {{ $chat->formatted_date }}</div>
                    </div>
                </div>
                <div class="chat-bubble-body">
                    <p>{{ $chat->message }}</p>
                </div>
                @else
                <div class="row">
                    <div class="col">
                        <p>
                            <i class="fa fa-ban"></i>
                            Deleted message
                        </p>
                        <span class="fs-5">{{ $chat->formatted_date }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif