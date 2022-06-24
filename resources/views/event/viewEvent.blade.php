@extends('base')
@section('content')
    @include('shared.growl')

    @if ($action == 'moderate')
        <script>
            function changeStatus(status) {
                document.getElementById('status').value = status;
                document.getElementById('moderateForm').submit();
            }
        </script>
    @endif

    <div class="d-flex flex-row flex-fill p-2">
        <div class="flex-fill flex-shrink-1 w-25 d-flex flex-column">
            <div class="p-2 bd-highlight">
                @if ($event->image != null)
                    <img src="{{ asset('public/images/event_images/' . $event->image) }}" class="card-img-top px-2 pt-2"
                        alt="No Image">
                @else
                    <img src="{{ asset('public/images/no-image-placeholder.png') }}" class="card-img-top px-2 pt-2"
                        alt="No Image">
                @endif
            </div>
            <div class="p-2 bd-highlightr">
                <h5>Event information</h5>
                <i class="fa-solid fa-lg fa-calendar"></i> <?php echo $event->start_date; ?> - <?php echo $event->end_date; ?>
            </div>
            <div class="p-2 bd-highlight">
                <i class="fa-solid fa-lg fa-location-pin"></i></i> <?php echo $event->location; ?>
            </div>
        </div>
        <div class="vr"></div>
        <div class="flex-fill ps-2 w-100 d-flex flex-column">
            <div class="p-2 bd-highlight">
                <h1><?php echo $event->name; ?></h1>
            </div>
            <div class="hr"></div>
            <div class="p-2 bd-highlight flex-fill">
                <h3>Event description</h3>
                <?php echo $event->description; ?>
            </div>
            <div class="p-2 bd-highlight align-self-end">
                @switch($action)
                    @case('participate')
                        <form action="{{ route('event.participate', ['id' => $event->id]) }}" method="POST">
                            @csrf
                            <input name="isParticipant" id="isParticipant" type="hidden" value="{{ $isParticipant }}">
                            @if ($event->participant_count == $event->limit)
                                <span class="d-inline-block" tabindex="0" data-bs-toggle="tooltip"
                                    title="Event full">
                            @endif
                            @if ($isParticipant)
                                <button type="submit" class="btn btn-danger">
                                    Leave
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary" <?php if ($event->participant_count == $event->limit){ ?> disabled <?php } ?>>
                                    Participate
                                </button>
                            @endif
                            @if ($event->participant_count == $event->limit)
                                </span>
                            @endif
                        </form>
                    @break

                    @case('moderate')
                        <form id="moderateForm" action="{{ route('event.moderate', ['id' => $event->id]) }}" method="POST">
                            @csrf
                            <input name="status" id="status" type="hidden" value="">
                            <button onclick="changeStatus('DENIED')" class="btn btn-danger">
                                Deny
                            </button>
                            <button onclick="changeStatus('APPROVED')" class="btn btn-primary"?>
                                Approve
                            </button>
                        </form>
                    @break

                    @default
                        Please add case for new action
                @endswitch

            </div>
        </div>
    </div>
@endsection
