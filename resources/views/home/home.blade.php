@extends('base')
@section('content')
    @include('shared.growl')
    <script>
        function handleClick(pageUrl) {
            window.location.href = pageUrl;
        }
    </script>

    <div class="p-2 w-100 d-flex flex-column">
        <div class="p-2 mx-auto">
            <h4>Welcome</h4>
        </div>
        <div class="p-2 w-50 mx-auto">
            <form class="w-100" id="searchForm" action="{{ route('event.viewAll.search') }}" method="post">
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
    
        <div class="cotainer mx-5 w-100 flex-fill">
            @if (count($events) == 0)
                No events to load
            @else
                <div class="row row-cols-8">
                    @foreach ($events as $event)
                        <div class="col p-3">
                            <div class="card border-light event-card" onclick="handleClick('{{ route('event.show', ['id' => $event->id]) }}')">
                                @if ($event->image != null)
                                    <img src="{{ asset('public/images/event_images/' . $event->image) }}"
                                        class="card-img-top px-2 pt-2" alt="No Image">
                                @else
                                    <img src="{{ asset('public/images/no-image-placeholder.png') }}"
                                        class="card-img-top px-2 pt-2" alt="No Image">
                                @endif
                                <div class="card-body">
                                    <h4 class="card-title"><?php echo $event->name; ?></h4>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <?php echo $event->start_date->toFormattedDateString(); ?> - <?php echo $event->end_date->toFormattedDateString(); ?>
                                    </h6>
                                    <p class="card-text">
    
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
