@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Onboarding Messages')}}</title>
@endsection
@section('admin-content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{__('admin.Onboarding Messages')}}</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a>
                </div>
                <div class="breadcrumb-item">{{__('admin.Onboarding Messages')}}</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Test Connection Button -->
            <div class="row mb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-info" id="testConnectionBtn">
                        <i class="fas fa-check-circle"></i> Test Twilio Connection
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Pending Users</h4>
                            </div>
                            <div class="card-body">
                                {{ $pendingUsers->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send to All Users -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Send to All Pending Users</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.onboarding.send-to-all') }}" method="POST" id="sendToAllForm">
                                @csrf
                                <div class="form-group">
                                    <label>Message Template <span class="text-danger">*</span></label>
                                    <textarea 
                                        name="message_template" 
                                        class="form-control" 
                                        rows="5" 
                                        placeholder="Use @{{name}}, @{{email}}, @{{phone}}, @{{password}} as placeholders"
                                    >
Hello @{{name}}, Welcome to our platform! 
Your account has been created. 
Login credentials - Email: @{{email}}, Password: @{{password}}. 
Please change your password after first login.
                                    </textarea>

                                    <small class="form-text text-muted">
                                        Available placeholders: @{{name}}, @{{email}}, @{{phone}}, @{{password}}
                                    </small>
                                </div>

                                <div class="form-group">
                                    <button 
                                        type="button" 
                                        class="btn btn-primary" 
                                        data-toggle="modal" 
                                        data-target="#sendToAllModal"
                                    >
                                        <i class="fas fa-paper-plane"></i> 
                                        Send to All ({{ $pendingUsers->count() }} Users)
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Send to Selected Users -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Send to Selected Users</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.onboarding.send-to-selected') }}" method="POST" id="sendToSelectedForm">
                                @csrf
                                <div class="form-group">
                                    <label>Message Template <span class="text-danger">*</span></label>
                                    <textarea 
                                        name="message_template" 
                                        class="form-control" 
                                        rows="5" 
                                        placeholder="Use @{{name}}, @{{email}}, @{{phone}}, @{{password}} as placeholders"
                                    >
Hello @{{name}}, Welcome to our platform! 
Your account has been created. 
Login credentials - Email: @{{email}}, Password: @{{password}}. 
Please change your password after first login.
                                    </textarea>
                                </div>

                                <div class="form-group">
                                    <label>Phone Numbers</label>
                                    <div id="phoneNumbersContainer">
                                        <div class="input-group mb-2 phone-number-row">
                                            <input type="text" name="phone_numbers[]" class="form-control phone-input" placeholder="+1234567890">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-danger remove-phone-btn" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-success btn-sm mt-2" id="addPhoneBtn">
                                        <i class="fas fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendToSelectedModal">
                                        <i class="fas fa-paper-plane"></i> Send to Selected
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Users List -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pending Users List</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="pendingUsersTable">
                                    <thead>
                                        <tr>
                                            <th>{{__('admin.SN')}}</th>
                                            <th>{{__('admin.Name')}}</th>
                                            <th>{{__('admin.Email')}}</th>
                                            <th>{{__('admin.Phone')}}</th>
                                            <th>{{__('admin.Status')}}</th>
                                            <th>{{__('admin.Action')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingUsers as $index => $user)
                                        <tr>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($user->onboarding_message_sent)
                                                    <span class="badge badge-success">Sent</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-primary btn-sm add-to-selected-btn" data-phone="{{ $user->phone }}">
                                                    <i class="fas fa-plus"></i> Add to Selected
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Send to All Confirmation Modal -->
<div class="modal fade" id="sendToAllModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Send to All</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to send onboarding messages to <strong>{{ $pendingUsers->count() }}</strong> users?</p>
                <p class="text-danger"><strong>Warning:</strong> This will generate new passwords for all users and send SMS messages.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.Close')}}</button>
                <button type="button" class="btn btn-primary" id="confirmSendToAll">
                    <i class="fas fa-paper-plane"></i> Yes, Send Messages
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Send to Selected Confirmation Modal -->
<div class="modal fade" id="sendToSelectedModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Send to Selected</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to send onboarding messages to the selected phone numbers?</p>
                <p class="text-danger"><strong>Warning:</strong> This will generate new passwords and send SMS messages.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.Close')}}</button>
                <button type="button" class="btn btn-primary" id="confirmSendToSelected">
                    <i class="fas fa-paper-plane"></i> Yes, Send Messages
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function($) {
    "use strict";
    
    $(document).ready(function() {
        // Initialize DataTable
        $('#pendingUsersTable').DataTable();

        // Add phone number field
        $('#addPhoneBtn').on('click', function() {
            const phoneRow = `
                <div class="input-group mb-2 phone-number-row">
                    <input type="text" name="phone_numbers[]" class="form-control phone-input" placeholder="+1234567890">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-danger remove-phone-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#phoneNumbersContainer').append(phoneRow);
        });

        // Remove phone number field
        $(document).on('click', '.remove-phone-btn', function() {
            $(this).closest('.phone-number-row').remove();
        });

        // Add user phone to selected list
        $(document).on('click', '.add-to-selected-btn', function() {
            const phone = $(this).data('phone');
            if (phone) {
                let exists = false;
                $('.phone-input').each(function() {
                    if ($(this).val() === phone) {
                        exists = true;
                    }
                });

                if (!exists) {
                    const phoneRow = `
                        <div class="input-group mb-2 phone-number-row">
                            <input type="text" name="phone_numbers[]" class="form-control phone-input" value="${phone}" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger remove-phone-btn">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    $('#phoneNumbersContainer').append(phoneRow);
                    $('html, body').animate({
                        scrollTop: $('#sendToSelectedForm').offset().top - 100
                    }, 500);
                } else {
                    toastr.warning('Phone number already added');
                }
            }
        });

        // Confirm send to all
        $('#confirmSendToAll').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
            $('#sendToAllForm').submit();
        });

        // Confirm send to selected
        $('#confirmSendToSelected').on('click', function() {
            const phoneInputs = $('.phone-input').filter(function() {
                return $(this).val().trim() !== '';
            });

            if (phoneInputs.length === 0) {
                toastr.error('Please add at least one phone number');
                $('#sendToSelectedModal').modal('hide');
                return;
            }

            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
            $('#sendToSelectedForm').submit();
        });

        // Test Twilio connection
        $('#testConnectionBtn').on('click', function() {
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Testing...');

            $.ajax({
                url: '{{ route("admin.onboarding.test-connection") }}',
                type: 'GET',
                success: function(response) {
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Test Twilio Connection');
                    
                    if (response.success) {
                        toastr.success('Twilio connection successful!');
                    } else {
                        toastr.error('Twilio connection failed: ' + response.message);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle"></i> Test Twilio Connection');
                    toastr.error('Error testing connection');
                }
            });
        });
    });
})(jQuery);
</script>
@endsection
