@extends('Dashboard.Layouts.master_dashboard')

@section('heading')
    Listing Charges
@endsection
<style>
    .dashboard-main .left-panel .left-panel-menu ul li a.profile-active {
        background-color: rgba(250, 250, 250, 0.1);
        font-weight: 600;
        border-left: 5px solid #fff;
        transition: .3s;
    }

    .mobile-profile-active {
        font-weight: 700 !important;
        border-bottom: 1px solid;
    }
</style>
@section('content')
    <div class="body-overlay-scroll">
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="table-responsive">

                    <table class="table table-bordered table-hover" id="ChargeTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>1st Feature Listing Charge</th>
                                <th>Additional Listing Charge</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ $charge->feature_listing_amount ?? 'N/A' }}</td>
                                <td>{{ $charge->additional_listing_amount ?? 'N/A' }}</td>

                                <td>{{ $charge->created_at ? $charge->created_at->format('d M Y, h:i A') : 'N/A' }}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#editChargeModal"
                                        onclick="fillChargeModal({{ $charge->id }}, {{ $charge->feature_listing_amount }}, {{ $charge->additional_listing_amount }})">
                                        Edit
                                    </button>

                                </td>
                            </tr>

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

{{-- modal for update --}}
<!-- Edit Charge Modal -->
<div class="modal fade" id="editChargeModal" tabindex="-1" aria-labelledby="editChargeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('updateCharges') }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="charge_id">

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Charges</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
              <div class="mb-3">
                  <label for="feature_charge" class="form-label">Feature Listing Charge</label>
                  <input type="number" step="0.01" class="form-control" name="feature_listing_amount" id="feature_charge" required>
              </div>
              <div class="mb-3">
                  <label for="additional_charge" class="form-label">Additional Listing Charge</label>
                  <input type="number" step="0.01" class="form-control" name="additional_listing_amount" id="additional_charge" required>
              </div>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-success">Save Changes</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
