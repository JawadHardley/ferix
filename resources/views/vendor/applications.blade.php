@extends('layouts.admin.main')
@section('content')


<div class="row">
    <div class="col-12">
        <x-errorshow />
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of all Feri Applications</h3>
            </div>
            <div class="table-respnsive">
                <table class="table table-selectable card-table table-vcenter text-nowrap datatable">
                    <thead>
                        <tr>
                            <th>
                                ID
                            </th>
                            <th>Reference</th>
                            <th>Applicant</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th>PO</th>
                            <th>Manifest</th>
                            <th>Type</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th>Query</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $record)
                        <tr>
                            <td><span class="text-secondary">{{ $record->id }}</span></td>
                            <td><a href="{{ route('vendor.showApp', ['id' => $record->id]) }}" class="text-reset"
                                    tabindex="-1">
                                    {{ ucfirst($record->company_ref) }}
                                </a></td>
                            <td>
                                {{ $record->applicantName }}
                            </td>
                            <td>
                                {{ $record->companyName }}
                            </td>
                            <td>
                                {{ $record->created_at->format('j F Y') }}

                            </td>
                            <td>
                                {{ ucfirst($record->fix_number) }}
                            </td>
                            <td>
                                {{ ucfirst($record->manifest_no) }}
                            </td>
                            <td>
                                {{ ucfirst($record->transport_mode) }}
                            </td>
                            <td class="text-center">
                                @if ($record->status == 1 || $record->status == 2)
                                <i class="fa fa-spinner" data-bs-toggle="tooltip" data-bs-placement="top"
                                    title="In progress"></i>
                                @endif

                                @if ($record->status == 3 || $record->status == 4)
                                <a href="{{ route('certificate.downloaddraft', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
                                    <i class="fa fa-file" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Draft"></i>
                                </a>
                                @endif

                                @if ($record->status == 5)
                                <a href="{{ route('certificate.download', ['id' => $record->id]) }}"
                                    class="text-decoration-none" download>
                                    <i class="fa fa-certificate" data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="Certificate"></i>
                                </a>
                                @endif
                            </td>
                            <td>
                                @if ($record->status == 1)
                                <!-- <span class="badge bg-danger me-1"></span> New Entry -->
                                <span class="status-dot status-dot-animated status-red me-1"></span> New Entry
                                @elseif ($record->status == 2)
                                <span class="badge bg-warning me-1"></span> Process
                                @elseif ($record->status == 3)
                                <span class="status-dot status-cyan me-1"></span> Awaiting Approval
                                @elseif ($record->status == 4)
                                <span class="badge bg-primary me-1"></span> Approved
                                @elseif ($record->status == 5)
                                <span class="status-dot status-green me-1"></span> Complete
                                @endif
                            </td>
                            <td class="">

                                @php
                                $unreadChats = $chats->filter(function ($chat) use ($record) {
                                return ($chat->user_id !== Auth::id() && $chat->read === 0) && $chat->application_id ==
                                $record->id;
                                });
                                @endphp

                                <a href="#" class="text-decoration-none" data-bs-toggle="modal"
                                    data-bs-target="#chat{{ $record->id }}">
                                    <i class="fa fa-bell"></i>

                                    @if ($unreadChats->isNotEmpty())
                                    <span class="badge bg-red mb-2"></span>
                                    @endif
                                </a>

                                <!-- Modal for query -->
                                <div class="modal fade" id="chat{{ $record->id }}" tabindex="-1"
                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-3" id="exampleModalLabel">Queries</h1>
                                                <span class="fs-5 ms-auto">
                                                    <a href="{{ route('vendor.readchat', ['id' => $record->id]) }}">mark
                                                        as read</a>
                                                </span>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card">
                                                    <div class="card-body scrollable"
                                                        style="height: 300px; overflow-y: auto;">
                                                        <div class="chat">
                                                            <div class="chat-bubbles">
                                                                <form
                                                                    action="{{ route('vendor.sendchat', ['id' => $record->id]) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    @foreach($chats as $chat)

                                                                    @if(($chat->user_id == Auth::user()->id ||
                                                                    $chat->user->role ==
                                                                    Auth::user()->role) && $chat->application_id ==
                                                                    $record->id)
                                                                    <div class="chat-item mb-3">
                                                                        <div
                                                                            class="row align-items-end justify-content-end">
                                                                            <div class="col col-lg-10">
                                                                                <div class="chat-bubble chat-bubble-me">
                                                                                    @if($chat->del == 0)
                                                                                    <div class="chat-bubble-title">
                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col chat-bubble-author">
                                                                                                {{ $chat->user->name }}
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-auto chat-bubble-date fs-4">
                                                                                                {{ $chat->formatted_date }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="chat-bubble-body">
                                                                                        <p>{{ $chat->message }}</p>
                                                                                    </div>
                                                                                    @if($chat->user->id ==
                                                                                    Auth::user()->id)
                                                                                    <span class="fs-5">
                                                                                        <a
                                                                                            href="{{ route('vendor.deletechat', ['id' => $chat->id]) }}">delete</a>
                                                                                    </span>
                                                                                    @endif
                                                                                    @else
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <p>
                                                                                                <i
                                                                                                    class="fa fa-ban"></i>
                                                                                                Deleted message
                                                                                            </p>
                                                                                            <span
                                                                                                class="fs-5">{{ $chat->formatted_date }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>

                                                                            <div class="col-auto">
                                                                                <span class="avatar avatar-1">
                                                                                    <i
                                                                                        class="fa fa-user-shield p-auto"></i>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @elseif($chat->application_id ==
                                                                    $record->id)
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
                                                                                            <div
                                                                                                class="col chat-bubble-author">
                                                                                                {{ $chat->user["name"] }}
                                                                                            </div>
                                                                                            <div
                                                                                                class="col-auto chat-bubble-date">
                                                                                                {{ $chat->formatted_date }}
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="chat-bubble-body">
                                                                                        <p>{{ $chat->message }}</p>
                                                                                    </div>
                                                                                    @else
                                                                                    <div class="row">
                                                                                        <div class="col">
                                                                                            <p>
                                                                                                <i
                                                                                                    class="fa fa-ban"></i>
                                                                                                Deleted message
                                                                                            </p>
                                                                                            <span
                                                                                                class="fs-5">{{ $chat->formatted_date }}</span>
                                                                                        </div>
                                                                                    </div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endif
                                                                    @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer px-4 pb-4">
                                                <div class="input-group input-group-flat">
                                                    <input type="text" name="message" class="form-control"
                                                        autocomplete="off" placeholder="Type message">
                                                    <span class="input-group-text">
                                                        <button type="submit" class="btn border-0">
                                                            <i class="fa fa-paper-plane"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </td>
                            <td class="text-end">
                                <div class="dropdown">
                                    <a href="#" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                                        Actions
                                    </a>
                                    <div class="dropdown-menu dropstart">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mXX">
                                            <i class="fa fa-message pe-2"></i>Query
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('vendor.showApp', ['id' => $record->id]) }}">
                                            <i class="fa fa-eye pe-2"></i>View
                                        </a>
                                    </div>
                                </div>
                                <!-- <div class="dropdown">
                                    <button class="btn dropdown-toggle align-text-top" data-bs-boundary="viewport"
                                        data-bs-toggle="dropdown">Actions</button>
                                    <div class="dropdown-menu dropdown-menu-start">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#mXX">
                                            <i class="fa fa-message pe-2"></i>Query
                                        </a>
                                        <a class="dropdown-item"
                                            href="{{ route('vendor.showApp', ['id' => $record->id]) }}">
                                            <i class="fa fa-eye pe-2"></i>View
                                        </a>

                                    </div>
                                </div> -->
                            </td>
                        </tr>


                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-secondary">Entries</p>
                <ul class="pagination m-0 ms-auto">
                    {{ $records->links() }}
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection