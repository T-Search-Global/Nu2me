@extends('Dashboard.Layouts.master_dashboard')

@section('heading')
    Events
@endsection
<style>
    .dashboard-main .left-panel .left-panel-menu ul li a.event-active {
        background-color: rgba(250, 250, 250, 0.1);
        font-weight: 600;
        border-left: 5px solid #fff;
        transition: .3s;
    }

    .mobile-event-active {
        font-weight: 700 !important;
        border-bottom: 1px solid;
    }
</style>
@section('content')
    <div class="body-overlay-scroll">
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="table-responsive">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <!-- Announcement Modal Trigger Button -->
                    {{-- <div class="mt-4">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createAnnouncementModal">
                            Create Events
                        </button>
                    </div> --}}

                    <table class="table table-bordered table-hover" id="ChargeTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Event</th>
                                {{-- <th>Description</th> --}}
                                <th>Image</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Paid</th>
                                <th>Approve</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $key => $event)
                                <tr>
                                    <td>{{ $key + 1 ?? '' }}</td>
                                    <td>{{ $event->name ?? '' }}</td>
                                    {{-- <td>{{ $event->description ?? '' }}</td> --}}

                                    <td>
                                        @if ($event->image)
                                            <img src=" {{ asset('storage/' . $event->image) }}" alt=""
                                                width="40" height="40">
                                        @endif

                                    </td>
                                    <td>{{ $event->user->first_name ?? '' }}</td>
                                    <td>{{ $event->user->email ?? '' }}</td>
                                    <td>{{ $event->is_event_paid ? 'paid' : 'not paid' }}</td>
                                    <td>{{ $event->approve ? 'yes' : 'no' }}</td>
                                    <td>{{ $event->created_at ?? '' }}</td>


                                    <td>
                                        @if (!$event->approve)
                                            <!-- Approve Button -->
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#approveModal"
                                                onclick="setApproveEventId({{ $event->id }})">
                                                Approve
                                            </button>
                                        @else
                                            <span class="badge bg-success">Approved</span>
                                        @endif
                                    </td>




                                </tr>
                            @endforeach




                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


    <!-- Create Announcement Modal -->
    <div class="modal fade" id="createAnnouncementModal" tabindex="-1" aria-labelledby="createAnnouncementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAnnouncementModalLabel">Create Events</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Event Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Event name">
                        </div>

                        {{-- Image --}}
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" name="image" id="image" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create Event</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>





    {{-- create event --}}
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="" id="approveForm">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Event</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to approve this event?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Yes, Approve</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#ChargeTable').DataTable({
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'csv',
                        text: 'Export to CSV',
                        className: 'btn btn-outline-primary btn-sm'
                    },
                    {
                        extend: 'pdf',
                        text: 'Export to PDF',
                        className: 'btn btn-outline-danger btn-sm',
                        orientation: 'landscape', // For wider tables
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        className: 'btn btn-outline-secondary btn-sm'
                    }
                ]
            });

        });


        function fillChargeModal(id, feature, additional) {
            document.getElementById('charge_id').value = id;
            document.getElementById('feature_charge').value = feature;
            document.getElementById('additional_charge').value = additional;
        }


        function setApproveEventId(id) {
            const form = document.getElementById('approveForm');
            form.action = `/events/approve/${id}`; // adjust path if route is prefixed
        }
    </script>
@endsection
@endsection
