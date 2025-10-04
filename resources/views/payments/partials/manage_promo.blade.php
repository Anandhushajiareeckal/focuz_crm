<form id="discountForm">
    @csrf <!-- Laravel CSRF token for security -->
    <input type="hidden" name="id_exist" value="{{$promo_data->id }}">

    <div class="row">
        <div class="col">
            <div class="alert alert-sm alert-info d-none" id="alert_modal">

            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="promoCode">Promo Code</label>
        <input value="{{ $promo_data->promocode }}" type="text" class="form-control form-control-sm form_inputs"
            id="promoCode" name="promoCode" placeholder="Enter Promo Code" required>
    </div>

    <!-- Description Field -->
    <div class="form-group">
        <label for="description">Description</label>
        <textarea class="form-control form-control-sm form_inputs" id="description" name="description"
            placeholder="Enter Description" required>{{ nl2br($promo_data->description) }}</textarea>
    </div>

    <!-- Discount Amount Field -->
    <div class="form-group">
        <label for="amount">Discount Amount</label>
        <input value="{{ $promo_data->discount_amount }}" type="number"
            class="form-control form-control-sm form_inputs" id="amount" name="amount"
            placeholder="Enter Discount Amount" required>
    </div>

    <!-- Date Fields (Start Date and End Date in the same row) -->
    @php
        $start_date = '';
        $endDate = '';
        if ($promo_data->start_date !== null) {
            $start_date = date('d-m-Y', strtotime($promo_data->start_date));
            $endDate = date('d-m-Y', strtotime($promo_data->end_date));
        }
    @endphp
    <div class="form-row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="startDate">Start Date</label>
                <input value="{{ $start_date }}" placeholder="Select date from" readonly autocomplete="off"
                    type="text" class="form-control form-control-sm form_inputs" id="startDate" name="startDate"
                    required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="endDate">End Date</label>
                <input value="{{ $endDate }}" placeholder="Select date to" readonly autocomplete="off"
                    type="text" class="form-control form-control-sm form_inputs" id="endDate" name="endDate"
                    required>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <button type="button" onclick="submitDiscountForm()" id="create_discount" class="btn btn-primary">Submit</button>
</form>

<script>
    $(document).ready(function() {
        // Initialize jQuery UI Datepicker for start and end date fields
        $("#startDate").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            minDate: 0
        });

        $("#endDate").datepicker({
            dateFormat: 'dd-mm-yy',
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            minDate: 0
        });
    });

    function submitDiscountForm() {
        preloader.load();
        $.ajax({
            type: "POST",
            url: "{{ route('save_promo') }}", // Assuming 'save_promo' is the named route to store the discount
            data: $('#discountForm').serialize(), // Serialize the form data
            success: function(response) {
                preloader.stop(); // Stop loading animation
                $('#alert_modal').addClass('d-none');
                $('#alert_modal').html("");
                $('.form_inputs').removeClass('error-outline');
                $('#modal_create').modal('hide');
                window.alert("Promotion Added Successfully")
            },
            error: function(xhr) {
                preloader.stop(); // Stop loading animation
                $('#alert_modal').addClass('d-none');
                $('#alert_modal').html("");
                $('.form_inputs').removeClass('error-outline');
                if (xhr.responseJSON.error) {
                    window.alert(xhr.responseJSON.error);
                } else {
                    let errors = xhr.responseJSON.errors;
                    let error = ""
                    $.each(errors, function(key, value) {

                        $('#' + key).addClass('error-outline'); // Add error class to invalid fields
                        error = value;
                    });
                    $('#alert_modal').removeClass('d-none');
                    $('#alert_modal').html(error);
                }
            }
        });
    }
</script>
