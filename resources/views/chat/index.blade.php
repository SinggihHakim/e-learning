@extends('layouts.app')
@section('title', 'Chat')
@section('page-title', 'Pesan (Chat)')

@section('content')
<div class="card" style="display:flex; height: calc(100vh - 160px); padding: 0; overflow: hidden;">
    <!-- Sidebar Chat -->
    <div style="width: 320px; border-right: 1px solid var(--border); display: flex; flex-direction: column; background: #fff;">
        <div style="padding: 16px; border-bottom: 1px solid var(--border);">
            <input type="text" placeholder="Cari kontak..." style="width: 100%; padding: 8px 12px; border-radius: 20px; border: 1px solid var(--border); background: var(--bg);">
        </div>
        <div style="overflow-y: auto; flex: 1;">
            @foreach($chatPartners as $user)
                <a href="{{ route('chat.index', ['user' => $user->id]) }}" style="text-decoration: none; color: inherit;">
                    <div style="padding: 14px 16px; border-bottom: 1px solid var(--border); display: flex; gap: 12px; align-items: center; cursor: pointer; background: {{ isset($activeUser) && $activeUser->id == $user->id ? 'var(--bg)' : 'transparent' }}; transition: background 0.2s;">
                        <x-avatar :user="$user" size="44" />
                        <div style="flex: 1; overflow: hidden;">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h4 style="font-size: 0.95rem; font-weight: 600; text-overflow: ellipsis; white-space: nowrap; overflow: hidden; margin: 0;">{{ $user->name }}</h4>
                            </div>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin: 2px 0 0; text-overflow: ellipsis; white-space: nowrap; overflow: hidden;">{{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
            @if($chatPartners->isEmpty())
                <div style="padding: 24px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">Belum ada kontak percakapan. Mulai obrolan dengan teman sekelas atau guru Anda.</div>
            @endif
        </div>
    </div>

    <!-- Area Obrolan Utama -->
    <div style="flex: 1; display: flex; flex-direction: column; background: #efeae2;">
        @if(isset($activeUser))
            <div style="padding: 12px 24px; background: #fff; border-bottom: 1px solid var(--border); display: flex; align-items: center; gap: 12px; z-index: 10;">
                <x-avatar :user="$activeUser" size="40" />
                <div>
                    <h3 style="font-size: 1rem; font-weight: 600; margin: 0;">{{ $activeUser->name }}</h3>
                    <p style="font-size: 0.75rem; color: var(--text-muted); margin: 0;">{{ ucfirst($activeUser->role) }}</p>
                </div>
            </div>

            <!-- Balon Pesan -->
            <div id="chat-messages" style="flex: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;" x-data="chatSystem()" x-init="initChat({{ $activeUser->id }})" x-ref="chatContainer">
                @foreach($messages as $msg)
                    @php $isMine = $msg->sender_id === auth()->id(); @endphp
                    <div style="display: flex; justify-content: {{ $isMine ? 'flex-end' : 'flex-start' }}; margin-bottom: 8px;">
                        <div style="max-width: 65%; padding: 10px 14px; border-radius: 12px; position: relative; font-size: 0.9rem;
                            {{ $isMine ? 'background: #dcf8c6; border-top-right-radius: 2px;' : 'background: #fff; border-top-left-radius: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);' }}">
                            {!! nl2br(e($msg->message)) !!}
                            <div style="font-size: 0.65rem; color: #888; text-align: right; margin-top: 4px;">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input area -->
            <div style="padding: 16px 24px; background: #f0f2f5; border-top: 1px solid #d1d7db;">
                <form id="sendForm" style="display: flex; gap: 12px; align-items: flex-end;" onsubmit="event.preventDefault(); window.sendMessageNow();">
                    @csrf
                    <input type="hidden" id="receiver_id" name="receiver_id" value="{{ $activeUser->id }}">
                    <textarea id="message-input" name="message" rows="1" placeholder="Ketik pesan..." required autofocus style="flex: 1; border-radius: 24px; padding: 12px 20px; resize: none; border: none; outline: none; box-shadow: 0 1px 3px rgba(0,0,0,0.08); font-size: 0.95rem; line-height: 1.4; max-height: 120px;" oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>
                    
                    <button type="submit" style="width: 48px; height: 48px; border-radius: 50%; background: var(--primary); color: #fff; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 2px 5px rgba(37,99,235,0.3); transition: transform 0.2s;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                    </button>
                </form>
            </div>
        @else
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--text-muted);">
                <svg width="80" height="80" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="opacity: 0.2; margin-bottom: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                <h3 style="font-weight: 500;">Mulai Obrolan</h3>
                <p style="font-size: 0.9rem;">Pilih kontak di sebelah kiri untuk mengirim pesan</p>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    @if(isset($activeUser))
    function scrollChatToBottom() {
        const chatBox = document.getElementById('chat-messages');
        chatBox.scrollTop = chatBox.scrollHeight;
    }
    
    // Initial scroll
    scrollChatToBottom();

    // Setup CSRF header for all fetch requests
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Function to fetch messages via Polling
    function fetchMessages() {
        fetch(`{{ url('chat/fetch') }}/{{ $activeUser->id }}`)
            .then(response => response.json())
            .then(data => {
                const chatBox = document.getElementById('chat-messages');
                const wasAtBottom = chatBox.scrollHeight - chatBox.scrollTop === chatBox.clientHeight;
                
                chatBox.innerHTML = '';
                
                data.messages.forEach(msg => {
                    const isMine = msg.sender_id == {{ auth()->id() }};
                    const justify = isMine ? 'flex-end' : 'flex-start';
                    const bgUrl = isMine ? '#dcf8c6' : '#fff';
                    const borderR = isMine ? 'border-top-right-radius: 2px;' : 'border-top-left-radius: 2px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);';
                    const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    const div = document.createElement('div');
                    div.style.cssText = `display: flex; justify-content: ${justify}; margin-bottom: 8px;`;
                    div.innerHTML = `
                        <div style="max-width: 65%; padding: 10px 14px; border-radius: 12px; position: relative; font-size: 0.9rem; background: ${bgUrl}; ${borderR}">
                            ${msg.message.replace(/\\n/g, '<br>')}
                            <div style="font-size: 0.65rem; color: #888; text-align: right; margin-top: 4px;">${time}</div>
                        </div>
                    `;
                    chatBox.appendChild(div);
                });
                
                // If user was looking at the bottom, auto scroll down when new message arrives
                if(wasAtBottom) scrollChatToBottom();
            });
    }

    // Polling interval every 3 seconds
    setInterval(fetchMessages, 3000);

    // Send Message AJAX
    window.sendMessageNow = function() {
        const input = document.getElementById('message-input');
        const message = input.value;
        if(!message.trim()) return;
        
        input.value = '';
        input.style.height = 'auto'; // reset textarea height
        
        fetch(`{{ route('chat.send') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                receiver_id: {{ $activeUser->id }},
                message: message
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                fetchMessages();
                setTimeout(scrollChatToBottom, 100);
            }
        });
    }
    
    // Support enter to send, shift+enter to newline
    document.getElementById('message-input').addEventListener('keydown', function(e) {
        if(e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            window.sendMessageNow();
        }
    });

    @endif
</script>
@endpush
@endsection
