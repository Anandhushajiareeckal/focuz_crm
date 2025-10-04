@php
    $start_date = '';
    $endDate = '';
    if ($course_data->start_date !== null) {
        $start_date = date('d-m-Y', strtotime($course_data->start_date));
        $endDate = date('d-m-Y', strtotime($course_data->end_date));
    }
@endphp
<div class="form-row">
    <div class="col-md-2">
        <div class="form-group">
            <label for="startDate">Start Date</label>
            <input value="{{ $start_date }}" placeholder="Select date from" readonly autocomplete="off" type="text"
                class="form-control form-control-sm form_inputs" id="startDate" name="startDate" required>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label for="endDate">End Date</label>
            <input value="{{ $endDate }}" placeholder="Select date to" readonly autocomplete="off" type="text"
                class="form-control form-control-sm form_inputs" id="endDate" name="endDate" required>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="endDate">Course Fee</label>
            <input type="number" name="course_fee" id="course_fee" class="form-control form-control-sm"
                placeholder="Enter the amount">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="commission">Course Conmission</label>
            <input type="number" name="commission" id="commission" class="form-control form-control-sm"
                placeholder="Enter the amount">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label for="other_fees">Course Other Fees</label>
            <input type="number" name="other_fees" id="other_fees" class="form-control form-control-sm"
                placeholder="Enter the amount">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <br />
            <button class="btn btn-sm btn-warning"><i class="fa fa-plus"></i></button>
            <button class="btn btn-sm btn-warning"><i class="fa fa-times"></i></button>
        </div>
    </div>
</div>
