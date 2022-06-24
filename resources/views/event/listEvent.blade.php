@extends('base')
@section('content')
    @include('shared.growl')
    <script>
        function handleClick(pageUrl, event) {
            if (!event.target.tagName.toLowerCase() === 'button') {
                window.location.href = pageUrl;
            }
        }

        @if ($hasDelete)
            function updateForm(action, event) {
                document.getElementById('deleteForm').action = action;
            }
        @endif
    </script>

    <div class="p-2 w-50 mx-auto d-flex flex-column">
        <div class="p-2">
            <form class="w-100" id="searchForm" action="{{ route($searchRoute) }}" method="post">
                <div class="input-group mb-3">
                    @method('post')
                    @csrf
                    <input id="search" name="search" type="text" class="form-control"
                        placeholder="Search by name or location" aria-label="Search by name or location"
                        aria-describedby="searchButton">
                    <button type="submit" class="btn btn-primary" type="button" id="searchButton">
                        <i class="fa-solid fa-magnifying-glass"></i></button>

                </div>
            </form>
        </div>
        <div class="p-2 flex-fill">
            @foreach ($events as $event)
                <div class="d-flex flex-row bd-highlight border-bottom align-items-center hover-pointer"
                    onclick="handleClick('{{ route('event.editView', ['id' => $event->id]) }}', event)">
                    <div class="p-2 bd-highlight">
                        @if ($event->image != null)
                            <img src="{{ asset('public/images/event_images/' . $event->image) }}"
                                class="card-img-top px-2 pt-2" style="width:125px;height:125px;" alt="No Image">
                        @else
                            <img src="{{ asset('public/images/no-image-placeholder.png') }}"
                                class="card-img-top px-2 pt-2" style="width:125px;height:125px;" alt="No Image">
                        @endif
                    </div>
                    <div class="p-2 bd-highlight flex-fill w-50">
                        <h3><?php echo $event->name; ?></h3>
                    </div>
                    <div class="p-2 bd-highlight d-flex flex-column">
                        <div class="p-2 bd-highlight">
                            From
                        </div>
                        <div class="p-2 bd-highlight flex-fill">
                            To
                        </div>
                    </div>
                    <div class="p-2 bd-highlight flex-fill w-50 d-flex flex-column">
                        <div class="p-2 bd-highlight">
                            <?php echo $event->start_date->toFormattedDateString(); ?>
                        </div>
                        <div class="p-2 bd-highlight flex-fill">
                            <?php echo $event->end_date->toFormattedDateString(); ?>
                        </div>
                    </div>
                    @if ($hasDelete)
                        <div class="p-2 bd-highlightd">
                            <button class="btn btn-danger"
                                onclick="updateForm('{{ route('event.delete', ['id' => $event->id]) }}')"
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                                Delete
                            </button>
                        </div>
                        <div class="p-2 bd-highlightd">
                            <a class="btn btn-primary" href="{{ route($viewRouteUrl, ['id' => $event->id]) }}">
                                Edit
                            </a>
                        </div>
                    @else
                        <div class="p-2 bd-highlightd">
                            <a class="btn btn-primary" href="{{ route($viewRouteUrl, ['id' => $event->id]) }}">
                                View
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        @if ($hasDelete)
            <!-- Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Delete event</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete this event?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <button type="button" onclick="document.getElementById('deleteForm').submit()"
                                class="btn btn-primary">Yes</button>
                        </div>
                    </div>
                </div>
            </div>

            <form id="deleteForm" action="" method="post">
                @method('delete')
                @csrf
            </form>
        @endif
    </div>
@endsection
