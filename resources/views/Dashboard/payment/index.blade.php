@extends('Dashboard.Layouts.master_dashboard')

@section('heading')
    Payments
@endsection
<style>
    .dashboard-main .left-panel .left-panel-menu ul li a.payment-active {
        background-color: rgba(250, 250, 250, 0.1);
        font-weight: 600;
        border-left: 5px solid #fff;
        transition: .3s;
    }

    .mobile-payment-active {
        font-weight: 700 !important;
        border-bottom: 1px solid;
    }
</style>
@section('content')
    <div class="body-overlay-scroll">
        <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <div class="table-responsive">

                    <table class="table table-bordered table-hover" id="paymentTable">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>User Email</th>
                                <th>Listing Title</th>
                                <th>Amount</th>
                                <th>Payment Type</th>
                                <th>Gateway</th>
                                <th>Status</th>
                                <th>Transaction ID</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments as $key => $payment)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $payment->user->first_name ?? 'N/A' }}</td>
                                    <td>{{ $payment->user->email ?? 'N/A' }}</td>
                                    <td>{{ $payment->listing->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($payment->amount ?? 0, 2) }}</td>
                                    <td>{{ $payment->payment_type ? ucfirst($payment->payment_type) : 'N/A' }}</td>
                                    <td>{{ $payment->payment_gateway ? ucfirst($payment->payment_gateway) : 'N/A' }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $payment->payment_status === 'completed' ? 'success' : 'warning' }}">
                                            {{ $payment->payment_status ? ucfirst($payment->payment_status) : 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
                                    <td>{{ $payment->created_at ? $payment->created_at->format('d M Y, h:i A') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">No payment records found.</td>
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
                $('#paymentTable').DataTable({
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
