<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{
    public function viewApprovedEvents()
    {
        $events = Event::where('status', Event::APPROVED)->get();

        $data = [
            'events' => $events,
            'hasDelete' => 0,
            'viewRouteUrl' => 'event.show',
            'searchRoute' => 'event.viewAll.search'
        ];

        return view('event.listEvent')->with($data);
    }

    public function searchApprovedEvents(Request $request)
    {
        $data = $request->only('search');

        $events = Event::where('status', Event::APPROVED)
            ->where(function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['search']}%")
                    ->orWhere('location', 'like', "%{$data['search']}%");
            })->get();

        $data = [
            'events' => $events,
            'hasDelete' => 0,
            'viewRouteUrl' => 'event.show',
            'searchRoute' => 'event.viewAll.search'
        ];

        return view('event.listEvent')->with($data);
    }

    public function getAddEventView()
    {
        return view('event.addEvent');
    }

    public function getEditEventView($id)
    {
        $event = Event::find($id);

        if (strcmp($event->user_id, Auth::id()) == 0) {
            return view('event.editEvent')->with('event', $event);
        } else {
            return redirect()->route('event.viewCreated')->withError('You are not the owner of this event');
        }

        return view('event.editEvent')->with('event', $event);
    }

    public function editEvent(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ]);

        $event = Event::find($id);

        if (strcmp($event->user_id, Auth::id()) == 0) {
            $data = $request->all();

            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = date('YmdHi') . $file->getClientOriginalName();
                $file->move(public_path('images/event_images'), $filename);
                $data['image'] = $filename;
            }

            $event = $this->updateEvent($data, $event);

            return redirect()->route('event.viewCreated')->withSuccess('Event edit successful');
        } else {
            return redirect()->route('event.viewCreated')->withError('You are not the owner of this event');
        }
    }

    public function deleteEvent($id)
    {
        $event = Event::find($id);

        if (strcmp($event->user_id, Auth::id()) == 0) {
            $event->participant()->detach();
            $imagePath = public_path("images/event_images/" . $event->image);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
            $event->delete();

            return redirect()->route('event.viewCreated');
        } else {
            return response('Cannot delete event you did not create', 403);
        }
    }

    public function viewEvent($id)
    {
        $event = Event::withCount('participant')->find($id);
        $isParticipant = 0;

        if ($event->status != Event::APPROVED) {
            return redirect()->route("home")->withError("Event is unavailable");
        }

        if ($event->hasParticipant(Auth::id())) {
            $isParticipant = 1;
        }

        $data = [
            'event' => $event,
            'isParticipant' => $isParticipant,
            'action' => 'participate'
        ];

        return view('event.viewEvent')->with($data);
    }

    public function participateEvent(Request $request, $id)
    {
        $request->validate([
            'isParticipant' => 'required'
        ]);

        $data = $request->all();

        if ($this->addParticipant($id, $data['isParticipant'])) {
            $message = "";
            if ($data['isParticipant']) {
                $message = "You have left the event";
            } else {
                $message = "You are participating in the event";
            }
            return redirect()->route('event.show', ['id' => $id])->withSuccess($message);
        } else {
            $message = "";
            if ($data['isParticipant']) {
                $message = "Cannot leave event after start date";
            } else {
                $message = "Event is full";
            }
            return redirect()->route('event.show', ['id' => $id])->withError($message);
        }
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'limit' => 'required',
            'startDate' => 'required',
            'endDate' => 'required',
        ]);

        $data = $request->all();

        if ($request->file('image')) {
            $file = $request->file('image');
            $filename = date('YmdHi') . $file->getClientOriginalName();
            $file->move(public_path('images/event_images'), $filename);
            $data['image'] = $filename;
        } else {
            $data['image'] = "";
        }

        $event = $this->createEvent($data);

        return redirect()->route('event.show', ['id' => $event->id])->withSuccess("Event created");
    }

    public function showPendingEvents()
    {
        $events = Event::all();

        $data = [
            'events' => $events,
            'hasDelete' => 0,
            'viewRouteUrl' => 'event.moderate.show',
            'searchRoute' => 'event.moderate.search'
        ];

        return view('event.listEvent')->with($data);
    }

    public function searchModerateEvents(Request $request)
    {
        $data = $request->only('search');

        $events = Event::where(function ($query) use ($data) {
            $query->where('name', 'like', "%{$data['search']}%")
                ->orWhere('location', 'like', "%{$data['search']}%");
        })->get();

        $data = [
            'events' => $events,
            'hasDelete' => 0,
            'viewRouteUrl' => 'event.show',
            'searchRoute' => 'event.moderate.search'
        ];

        return view('event.listEvent')->with($data);
    }

    public function moderateEventView($id)
    {
        $event = Event::find($id);

        $data = [
            'event' => $event,
            'isParticipant' => 0,
            'action' => 'moderate'
        ];

        return view('event.viewEvent')->with($data);
    }

    public function moderateEvent(Request $request, $id)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $status = $request->only('status');

        $event = Event::find($id);

        $event->status = $status['status'];

        $event->save();

        return redirect()->route('event.moderate.showAll')->withSuccess("Event status updated");
    }

    public function viewParticipatingEvents()
    {
        $user = Auth::user();

        $events = $user->participatingEvents;

        $data = [
            'events' => $events,
            'hasDelete' => 0,
            'viewRouteUrl' => 'event.show',
            'searchRoute' => 'event.searchParticipating'
        ];

        return view('event.listEvent')->with($data);
    }

    public function searchParticipatingEvents(Request $request)
    {
        $userId = Auth::id();

        $data = $request->only('search');

        $events = Event::whereHas('participant', function ($query) use ($userId) {
            return $query->where('user_id', '=', $userId);
        })->where(function ($query) use ($data) {
            $query->where('name', 'like', "%{$data['search']}%")
                ->orWhere('location', 'like', "%{$data['search']}%");
        })->get();

        $data = [
            'events' => $events,
            'hasDelete' => 0,
            'viewRouteUrl' => 'event.show',
            'searchRoute' => 'event.searchParticipating'
        ];

        return view('event.listEvent')->with($data);
    }

    public function viewCreatedEvents()
    {
        $user = Auth::user();

        $events = $user->createdEvents;

        $data = [
            'events' => $events,
            'hasDelete' => 1,
            'viewRouteUrl' => 'event.editView',
            'searchRoute' => 'event.created.search'
        ];

        return view('event.listEvent')->with($data);
    }

    public function searchCreatedEvents(Request $request)
    {
        $userId = Auth::id();

        $data = $request->only('search');

        $events = Event::where('user_id', $userId)
            ->where(function ($query) use ($data) {
                $query->where('name', 'like', "%{$data['search']}%")
                    ->orWhere('location', 'like', "%{$data['search']}%");
            })->get();

        $data = [
            'events' => $events,
            'hasDelete' => 1,
            'viewRouteUrl' => 'event.editView',
            'searchRoute' => 'event.created.search'
        ];

        return view('event.listEvent')->with($data);
    }

    private function addParticipant($id, $isParticipant)
    {
        $event = Event::withCount('participant')->find($id);

        $now = new Carbon();

        if ($now->lessThan($event->start_date)) {
            if ($isParticipant) {
                $event->participant()->detach(Auth::id());
            } else {
                if ($event->participant_count < $event->limit) {
                    $event->participant()->attach(Auth::id());
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }

    private function createEvent(array $data)
    {
        $owner = Auth::user();

        return $owner->createdEvents()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $data['image'],
            'location' => $data['location'],
            'limit' => $data['limit'],
            'start_date' => $data['startDate'],
            'end_date' => $data['endDate'],
            'status' => Event::PENDING
        ]);
    }

    private function updateEvent(array $data, $event)
    {
        $event->name = $data['name'];
        $event->description = $data['description'];
        if (array_key_exists('image', $data)) {
            $event->image = $data['image'];
        }
        $event->start_date = $data['startDate'];
        $event->end_date = $data['endDate'];
        $event->status = Event::PENDING;

        return $event->save();
    }
}
