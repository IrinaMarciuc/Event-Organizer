@extends('base')
@section('content')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const linkedPicker1Element = document.getElementById('startDatePicker');
            const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element, {
                useCurrent: false,
                restrictions: {
                    minDate: new Date()
                },
                display: {
                    components: {
                        decades: true,
                        year: true,
                        month: true,
                        date: true,
                        hours: false,
                        minutes: false,
                        seconds: false,
                    }
                }
            });
            // linked1.dates.formatInput = function(date) {
            //     var month = '' + (date.getMonth() + 1),
            //         day = '' + date.getDate(),
            //         year = date.getFullYear();

            //     if (month.length < 2)
            //         month = '0' + month;
            //     if (day.length < 2)
            //         day = '0' + day;

            //     return [day, month, year].join('/');
            // }
            const linked2 = new tempusDominus.TempusDominus(document.getElementById('endDatePicker'), {
                useCurrent: false,
                display: {
                    components: {
                        decades: true,
                        year: true,
                        month: true,
                        date: true,
                        hours: false,
                        minutes: false,
                        seconds: false,
                    }
                }
            });
            // linked2.dates.formatInput = function(date) {
            //     var month = '' + (date.getMonth() + 1),
            //         day = '' + date.getDate(),
            //         year = date.getFullYear();

            //     if (month.length < 2)
            //         month = '0' + month;
            //     if (day.length < 2)
            //         day = '0' + day;

            //     return [day, month, year].join('/');
            // }

            //using event listeners
            linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
                linked2.clear();
                linked2.updateOptions({
                    restrictions: {
                        minDate: e.detail.date
                    },
                    display: {
                        components: {
                            decades: true,
                            year: true,
                            month: true,
                            date: true,
                            hours: false,
                            minutes: false,
                            seconds: false,
                        }
                    }
                });
            });

            document.getElementById('image').onchange = function (evt) {
                var tgt = evt.target || window.event.srcElement,
                    files = tgt.files;

                // FileReader support
                if (FileReader && files && files.length) {
                    var fr = new FileReader();
                    fr.onload = function () {
                        document.getElementById("imageDisplay").src = fr.result;
                    }
                    fr.readAsDataURL(files[0]);
                }
            }
        });

        function submitForm() {
            if (validateForm()) {
                document.getElementById("editEventForm").submit();
            }
        }

        function validateForm() {
            form = document.getElementById("editEventForm");

            if (!form.checkValidity()) {
                form.classList.add('was-validated')
                return false;
            }

            return true;
        }
    </script>

    <form class="d-flex flex-fill needs-validation" id="editEventForm" method="POST" action="{{ route('event.edit', ['id' => $event->id]) }}"
        enctype="multipart/form-data" novalidate>
        @method('PUT')
        @csrf
        <div class="d-flex flex-row flex-fill p-2">
            <div class="flex-fill flex-shrink-1 w-25 d-flex flex-column">
                <div class="p-2 bd-highlight align-self-center">
                    <h5>Event image</h5>
                    <div class="form-group mb-3">
                        <input id="image" type="file" accept="image/*" id="image" class="form-control" name="image">
                    </div>
                </div>
                <div class="p-2 bd-highlight align-self-center">
                    @if ($event->image != null)
                        <img id="imageDisplay" src="{{ asset('public/images/event_images/' . $event->image) }}"
                            class="card-img-top px-2 pt-2" alt="No Image">
                    @else
                        <img id="imageDisplay" src="{{ asset('public/images/no-image-placeholder.png') }}"
                            class="card-img-top px-2 pt-2" alt="No Image">
                    @endif
                </div>
                <div class="p-2 bd-highlight align-self-center">
                    <h5>Event information</h5>
                    <div class="form-group mb-3">
                        <div class='row' id="datePicker">
                            <div class='col-sm-6'>
                                <label for='startDate' class='form-label'>From</label>
                                <div class='input-group log-event' id='startDatePicker' data-td-target-input='nearest'
                                    data-td-target-toggle='nearest'>
                                    <input id='startDate' name="startDate" type='text' class='form-control'
                                        data-td-target='#startDatePicker' value="{{ $event->start_date->format('m/d/Y') }}"/>
                                    <span class='input-group-text' data-td-target='#startDatePicker'
                                        data-td-toggle='datetimepicker'>
                                        <span class='fa-solid fa-calendar'></span>
                                    </span>
                                </div>
                            </div>
                            <div class='col-sm-6'>
                                <label for='endDate' class='form-label'>To</label>
                                <div class='input-group log-event' id='endDatePicker' data-td-target-input='nearest'
                                    data-td-target-toggle='nearest'>
                                    <input id='endDate' name="endDate" type='text' class='form-control'
                                        data-td-target='#endDatePicker' value="{{ $event->end_date->format('m/d/Y') }}"/>
                                    <span class='input-group-text' data-td-target='#endDatePicker'
                                        data-td-toggle='datetimepicker'>
                                        <span class='fa-solid fa-calendar'></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vr mx-1"></div>
            <div class="flex-fill w-100 d-flex flex-column">
                <div class="p-2 bd-highlight">
                    <h3>Event name</h3>
                    <div class="form-group mb-3">
                        <input type="text" placeholder="Name" id="name" class="form-control" name="name" value="{{ $event->name }}">
                    </div>
                </div>
                <div class="p-2 bd-highlight flex-fill">
                    <h3>Event description</h3>
                    <div class="form-group mb-3">
                        <textarea placeholder="Description" id="description" class="form-control" name="description" rows="17">{{ $event->description }}</textarea>
                    </div>
                </div>
                <div class="p-2 bd-highlight align-self-end">
                    <button type="button" onclick="submitForm()" class="btn btn-primary">Done</button>
                </div>
            </div>
        </div>
    </form>
@endsection
