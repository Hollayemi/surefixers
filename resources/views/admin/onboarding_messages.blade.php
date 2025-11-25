@extends('admin.master_layout')
@section('title')
<title>{{__('admin.Onboarding Messages')}}</title>
@endsection
@section('admin-content')
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

            <!-- Statistics Card -->
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

            <!-- Filters and Message Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Send Onboarding Messages</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.onboarding.send-to-selected') }}" method="POST" id="sendMessageForm">
                                @csrf
                                
                                <!-- Filters Section -->
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Filter by State</label>
                                            <select name="filter_state" id="filterState" class="form-control">
                                                <option value="">All States</option>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Filter by User Type</label>
                                            <select name="filter_user_type" id="filterUserType" class="form-control">
                                                <option value="">All Users</option>
                                                <option value="provider">Providers</option>
                                                <option value="client">Clients</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Filter by Category</label>
                                            <select name="filter_category" id="filterCategory" class="form-control">
                                                <option value="">All Categories</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Filter by Service</label>
                                            <select name="filter_service" id="filterService" class="form-control">
                                                <option value="">All Services</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="button" class="btn btn-primary" id="applyFiltersBtn">
                                            <i class="fas fa-filter"></i> Apply Filters
                                        </button>
                                        <button type="button" class="btn btn-secondary" id="resetFiltersBtn">
                                            <i class="fas fa-undo"></i> Reset Filters
                                        </button>
                                    </div>
                                </div>

                                <!-- Message Template -->
                                <div class="form-group">
                                    <label>Message Template <span class="text-danger">*</span></label>
                                    <textarea 
                                        name="message_template" 
                                        class="form-control" 
                                        style="min-height: 120px;"
                                        placeholder="Use @{{name}}, @{{email}}, @{{phone}}, @{{password}} as placeholders"
                                        required
                                    >Hello @{{name}}, Welcome to our platform! 
Your account has been created. 
Login credentials - Email: @{{email}}, Password: @{{password}}. 
Please change your password after first login.</textarea>
                                    <small class="form-text text-muted">
                                        Available placeholders: @{{name}}, @{{email}}, @{{phone}}, @{{password}}
                                    </small>
                                </div>

                                <!-- Selection Info -->
                                <div class="alert alert-info" id="selectionInfo" style="display: none;">
                                    <strong><span id="selectedCount">0</span></strong> user(s) selected
                                </div>

                                <!-- Action Buttons -->
                                <div class="form-group">
                                    <button type="button" class="btn btn-success" id="selectAllBtn">
                                        <i class="fas fa-check-square"></i> Select All
                                    </button>
                                    <button type="button" class="btn btn-warning" id="deselectAllBtn">
                                        <i class="fas fa-square"></i> Deselect All
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn btn-primary" 
                                        data-toggle="modal" 
                                        data-target="#sendConfirmModal"
                                        id="sendSelectedBtn"
                                        disabled
                                    >
                                        <i class="fas fa-paper-plane"></i> Send to Selected
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Users Table -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pending Users List (<span id="tableCount">{{ $pendingUsers->count() }}</span>)</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="pendingUsersTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAllCheckbox">
                                            </th>
                                            <th>{{__('admin.SN')}}</th>
                                            <th>{{__('admin.Name')}}</th>
                                            <th>{{__('admin.Email')}}</th>
                                            <th>{{__('admin.Phone')}}</th>
                                            <th>Type</th>
                                            <th>{{__('admin.Status')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pendingUsers as $index => $user)
                                        <tr data-user-id="{{ $user->id }}">
                                            <td>
                                                <input type="checkbox" class="user-checkbox" value="{{ $user->id }}" name="user_ids[]" form="sendMessageForm">
                                            </td>
                                            <td>{{ ++$index }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone ?? 'N/A' }}</td>
                                            <td>
                                                @if($user->is_provider)
                                                    <span class="badge badge-primary">Provider</span>
                                                @else
                                                    <span class="badge badge-info">Client</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->onboarding_message_sent)
                                                    <span class="badge badge-success">Sent</span>
                                                @else
                                                    <span class="badge badge-warning">Pending</span>
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
            </div>
        </div>
    </section>
</div>

<!-- Send Confirmation Modal -->
<div class="modal fade" id="sendConfirmModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Send Messages</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to send onboarding messages to <strong><span id="modalSelectedCount">0</span></strong> selected user(s)?</p>
                <p class="text-danger"><strong>Warning:</strong> This will generate new passwords and send SMS messages.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('admin.Close')}}</button>
                <button type="button" class="btn btn-primary" id="confirmSendBtn">
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
        var table = $('#pendingUsersTable').DataTable({
            "pageLength": 25,
            "order": [[1, "asc"]]
        });

        // Update selection count
        function updateSelectionCount() {
            var count = $('.user-checkbox:checked').length;
            $('#selectedCount').text(count);
            $('#modalSelectedCount').text(count);
            
            if (count > 0) {
                $('#selectionInfo').show();
                $('#sendSelectedBtn').prop('disabled', false);
            } else {
                $('#selectionInfo').hide();
                $('#sendSelectedBtn').prop('disabled', true);
            }
        }

        // Handle individual checkbox change
        $(document).on('change', '.user-checkbox', function() {
            updateSelectionCount();
            
            // Update select all checkbox
            var totalCheckboxes = $('.user-checkbox').length;
            var checkedCheckboxes = $('.user-checkbox:checked').length;
            $('#selectAllCheckbox').prop('checked', totalCheckboxes === checkedCheckboxes);
        });

        // Handle select all checkbox
        $('#selectAllCheckbox').on('change', function() {
            var isChecked = $(this).is(':checked');
            $('.user-checkbox').prop('checked', isChecked);
            updateSelectionCount();
        });

        // Select All button
        $('#selectAllBtn').on('click', function() {
            $('.user-checkbox').prop('checked', true);
            $('#selectAllCheckbox').prop('checked', true);
            updateSelectionCount();
        });

        // Deselect All button
        $('#deselectAllBtn').on('click', function() {
            $('.user-checkbox').prop('checked', false);
            $('#selectAllCheckbox').prop('checked', false);
            updateSelectionCount();
        });

        // Apply Filters
        $('#applyFiltersBtn').on('click', function() {
            var params = {
                state: $('#filterState').val(),
                user_type: $('#filterUserType').val(),
                category: $('#filterCategory').val(),
                service: $('#filterService').val()
            };
            
            var queryString = $.param(params);
            window.location.href = '{{ route("admin.onboarding.index") }}?' + queryString;
        });

        // Reset Filters
        $('#resetFiltersBtn').on('click', function() {
            window.location.href = '{{ route("admin.onboarding.index") }}';
        });

        // Confirm send
        $('#confirmSendBtn').on('click', function() {
            var checkedCount = $('.user-checkbox:checked').length;
            
            if (checkedCount === 0) {
                toastr.error('Please select at least one user');
                $('#sendConfirmModal').modal('hide');
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');
            $('#sendMessageForm').submit();
        });

        // Test Twilio connection
        $('#testConnectionBtn').on('click', function() {
            var btn = $(this);
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

        // Set filter values from URL parameters
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('state')) $('#filterState').val(urlParams.get('state'));
        if (urlParams.has('user_type')) $('#filterUserType').val(urlParams.get('user_type'));
        if (urlParams.has('category')) $('#filterCategory').val(urlParams.get('category'));
        if (urlParams.has('service')) $('#filterService').val(urlParams.get('service'));
    });
})(jQuery);
</script>
@endsection