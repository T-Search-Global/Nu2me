@extends('Dashboard.Layouts.master_dashboard')

@section('heading')
    Payments
@endsection
<style>
    .dashboard-main .left-panel .left-panel-menu ul li a.listing-active {
        background-color: rgba(250, 250, 250, 0.1);
        font-weight: 600;
        border-left: 5px solid #fff;
        transition: .3s;
    }

    .mobile-listing-active {
        font-weight: 700 !important;
        border-bottom: 1px solid;
    }
</style>
@section('content')

    <div class="body-overlay-scroll">
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="listingTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Owner</th>
                                <th>Feature</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Image</th>
                                <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($listings as $key => $listing)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $listing->name ?? 'N/A' }}</td>
                                    <td>{{ $listing->user->first_name . ' ' . $listing->user->last_name ?? 'N/A' }}</td>
                                    <td>{{ $listing->feature_check ? 'Yes' : 'No' }}</td>
                                    <td>{{ $listing->category ?? '' }}</td>
                                    <td>{{ $listing->location ?? '' }}</td>


                                    <td>
                                        @if ($listing->images->first())
                                            <img src="{{ asset($listing->images->first()->image_path) }}" alt="Image"
                                                height="40">
                                        @else
                                            <span class="text-muted">No image</span>
                                        @endif
                                    </td>


                                    <td>
                                        {{ $listing->created_at ?? '' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No listings found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    @section('scripts')
        <script>
            $(document).ready(function() {
                $('#listingTable').DataTable({
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
        </script>
    @endsection
@endsection
