@extends('layouts.admin.app')

@section('title', 'Live Chat')

@push('css')
    <style>
        /* Styles for the chat markup rendered by the AJAX/polling JS below —
           these class names are generated in JS and must stay in sync with it. */
        .chat_list {
            display: flex;
            align-items: center;
            padding: 14px 20px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background-color .15s ease;
        }

        .chat_list:hover {
            background-color: #f8fafc;
        }

        .chat_list.active_chat {
            background-color: var(--color-primary-50);
            box-shadow: inset 3px 0 0 var(--color-primary);
        }

        .chat_people {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-width: 0;
        }

        .chat_img {
            flex-shrink: 0;
        }

        .chat_img img {
            height: 40px;
            width: 40px;
            border-radius: 9999px;
            object-fit: cover;
            box-shadow: 0 0 0 1px #e2e8f0;
        }

        .chat_ib {
            flex: 1;
            min-width: 0;
        }

        .chat_ib h5 {
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .chat_ib h5 span {
            float: right;
            font-size: 11px;
            font-weight: 400;
            color: #94a3b8;
        }

        .chat_ib p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #64748b;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .incoming_msg {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            margin-bottom: 16px;
        }

        .incoming_msg_img {
            flex-shrink: 0;
        }

        .incoming_msg_img img {
            height: 32px;
            width: 32px;
            border-radius: 9999px;
            object-fit: cover;
            box-shadow: 0 0 0 1px #e2e8f0;
        }

        .received_msg {
            min-width: 0;
            max-width: 70%;
        }

        .received_withd_msg p {
            display: inline-block;
            margin: 0;
            padding: 10px 14px;
            background-color: #f1f5f9;
            color: #334155;
            font-size: 14px;
            line-height: 1.5;
            border-radius: 16px 16px 16px 4px;
            overflow-wrap: break-word;
            max-width: 100%;
        }

        .outgoing_msg {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }

        .sent_msg {
            max-width: 70%;
            text-align: right;
        }

        .sent_msg p {
            display: inline-block;
            margin: 0;
            padding: 10px 14px;
            background-color: var(--color-primary);
            color: #fff;
            font-size: 14px;
            line-height: 1.5;
            border-radius: 16px 16px 4px 16px;
            text-align: left;
            overflow-wrap: break-word;
            max-width: 100%;
        }

        .time_date {
            display: block;
            margin-top: 4px;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
@endpush

@section('content')

    <section class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Live Chat</h1>
                <p class="mt-1 text-sm text-slate-500">Chat with customers in real time</p>
            </div>
            <div class="flex items-center gap-3">
                <ol class="flex items-center gap-1 text-sm text-slate-400">
                    <li><a href="{{ route('admin.dashboard') }}" class="transition-colors hover:text-primary">Home</a></li>
                    <li><i class="fas fa-chevron-right text-[9px]"></i></li>
                    <li class="font-medium text-slate-600">Live Chat</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="mb-6">
        <div class="flex h-[calc(100vh-15rem)] min-h-[480px] overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            {{-- Conversation list --}}
            <aside class="flex w-2/5 max-w-xs shrink-0 flex-col border-r border-slate-200 bg-slate-50/50">
                <div class="flex items-center justify-between gap-2 border-b border-slate-200 bg-white px-5 py-4">
                    <h2 class="text-base font-semibold text-slate-900">Conversations</h2>
                    <span class="grid h-8 w-8 place-items-center rounded-lg bg-primary/10 text-primary">
                        <i class="fas fa-comments text-sm"></i>
                    </span>
                </div>
                <div class="inbox_chat flex-1 overflow-y-auto bg-white">
                    {{-- populated by liveChatUserList() --}}
                </div>
            </aside>

            {{-- Thread --}}
            <div class="flex min-w-0 flex-1 flex-col">
                <div class="msg_history flex-1 overflow-y-auto bg-slate-50/50 px-5 py-6">
                    {{-- populated by showLiveChatMessage() --}}
                </div>

                {{-- Composer --}}
                <div class="type_msg border-t border-slate-200 bg-white p-4">
                    <form method="post">
                        @csrf
                        <div class="input_msg_write flex items-center gap-3">
                            <input type="text" name="message" placeholder="Type a message"
                                class="write_msg h-11 w-full flex-1 rounded-full border border-slate-300 bg-white px-4 text-sm text-slate-700 shadow-sm transition-shadow focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20" />
                            <input type="hidden" id="user_id" name="user_id">
                            <button type="submit" id="submit_form" title="Send message"
                                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary text-white shadow-sm transition-all hover:bg-primary-600 hover:shadow focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 active:scale-[.98]">
                                <i class="fas fa-paper-plane" aria-hidden="true"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </section>

@endsection

@push('js')
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            let user_list = [];
            let user_id = 0;

            $(document).on('click', '#submit_form', function(e) {
                e.preventDefault();
                let token = $("input[name='_token']").val();
                let message = $("input[name='message']").val();
                let user_id = $("input[name='user_id']").val();

                if (message != '') {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('admin.connection.store.chat') }}",
                        data: {
                            '_token': token,
                            'message': message,
                            'user_id': user_id
                        },
                        dataType: "JSON",
                        success: function(response) {

                            if (response.alert == 'success') {
                                $("input[name='message']").val('')
                                showLiveChatMessage(user_id);
                                liveChatUserList();
                            }

                        },
                        error: function(e) {
                            console.log(e);
                        }
                    });
                }

            })


            function liveChatUserList() {

                $.ajax({
                    type: "GET",
                    url: "{{ route('admin.connection.live.chat.user.list') }}",
                    dataType: "JSON",
                    success: function(response) {

                        if (response != '') {

                            // let data = '';

                            $.each(response, function(key, val) {
                                if (user_list.indexOf(val.user_id) >= 0) return;

                                let className = '';
                                if (key == 0) {
                                    className = 'active_chat';

                                    // Get this function to this var
                                    $('input#user_id').val(val.user_id);
                                    user_id = val.user_id;

                                } else {
                                    className = '';
                                }

                                let img = '';
                                if (val.user.avatar != 'default.png') {
                                    img = '/uploads/member/' + val.user.avatar;
                                } else {
                                    img = 'https://ptetutorials.com/images/user-profile.png';
                                }

                                user_list.push(val.user_id);

                                data = '<div class="chat_list cursor-pointer ' + className +
                                    '" id="showUserChat" data-id="' + val.user_id + '">';
                                data += '<div class="chat_people">';
                                data += '<div class="chat_img"> <img src="' + img + '" alt="' +
                                    val.user.name + '"> </div>';
                                data += '<div class="chat_ib">';
                                data += '<h5>' + val.user.name + '</h5>';
                                data += '<p>' + val.message + '</p>';
                                data += '</div>';
                                data += '</div>';
                                data += '</div>';

                                $('.inbox_chat').append(data);
                            });


                        }
                    }
                });
            }

            function showLiveChatMessage(user_id) {

                $.ajax({
                    type: "GET",
                    url: "/admin/connection/live-chat-list/" + user_id,
                    dataType: "JSON",
                    success: function(response) {

                        let img = '';
                        if (response.avatar != 'default.png') {
                            img = '/uploads/member/' + response.avatar;
                        } else {
                            img = 'https://ptetutorials.com/images/user-profile.png';
                        }

                        let data = '';
                        $.each(response.chats, function(key, val) {

                            if (val.admin_message_log == 'incoming') {

                                data += '<div class="incoming_msg">';
                                data += '<div class="incoming_msg_img"> <img src="' + img +
                                    '" alt="sunil"> </div>';
                                data += '<div class="received_msg mb-2">';
                                data += '<div class="received_withd_msg">';
                                data += '<p>' + val.message + '</p>';
                                data += '<span class="time_date">' + moment(val.created_at)
                                    .format('LLL') + '</span>';
                                data += '</div>';
                                data += '</div>';
                                data += '</div>';

                            } else {
                                data += '<div class="outgoing_msg">';
                                data += '<div class="sent_msg">';
                                data += '<p>' + val.message + '</p>';
                                data += '<span class="time_date">' + moment(val.created_at)
                                    .format('LLL') + '</span> ';
                                data += '</div>';
                                data += '</div>';
                            }
                        });

                        $('.msg_history').html(data);
                    }
                });

            }

            // Show Single User Message
            $(document).on('click', '#showUserChat', function(e) {
                user_id = $(this).data('id');
                $('.chat_list').removeClass('active_chat');
                $(this).addClass('active_chat');
                $('input#user_id').val(user_id);

                showLiveChatMessage(user_id);

                $.ajax({
                    type: "GET",
                    url: '/admin/connection/live-chat/status/' + user_id,
                    dataType: "JSON",
                    success: function(response) {
                        console.log(response);
                    }
                });

            });

            showLiveChatMessage(user_id);
            liveChatUserList();

            setInterval(function() {
                showLiveChatMessage(user_id);
                liveChatUserList();
            }, 5000);

        });
    </script>
@endpush
