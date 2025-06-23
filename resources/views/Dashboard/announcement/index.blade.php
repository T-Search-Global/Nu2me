@extends('Dashboard.Layouts.master_dashboard')

@section('heading')
   Announcement Notifications For All Users
@endsection
<style>
    .dashboard-main .left-panel .left-panel-menu ul li a.announcement-active {
        background-color: rgba(250, 250, 250, 0.1);
        font-weight: 600;
        border-left: 5px solid #fff;
        transition: .3s;
    }

    .mobile-announcement-active {
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
                    <div class="mt-4">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#createAnnouncementModal">
                            Create Announcement
                        </button>
                    </div>

                    <table class="table table-bordered table-hover" id="ChargeTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Image</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>


                            @foreach ($announcements as $key => $announcement)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $announcement->title }}</td>
                                    <td>{{ $announcement->message }}</td>
                                     <td>
                                    @if ($announcement->img)
                                       <img src="{{ asset('storage/' . $announcement->img) }}" width="100">
                                    @endif
                                    </td>
                                    <td>{{ $announcement->created_at }}</td>
                                    <td>
                                        <form action="{{ route('announcements.destroy', $announcement->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
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
            <form method="POST" action="{{ route('announcements.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAnnouncementModalLabel">Create Announcement</h5>
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

                        {{-- Title --}}
                        <div class="mb-3">
                            <label for="title" class="form-label">Announcement Title</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        {{-- Message --}}
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                        </div>

                        {{-- Image --}}
                        <div class="mb-3">
                            <label for="img" class="form-label">Image (optional)</label>
                            <input type="file" name="img" id="img" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Send Announcement</button>
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
    </script>
@endsection
@endsection
