<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Educational Qualification</h3>
            </div>
            <div class="card-body  table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            {{-- <td>Degree</td>
                            <td>University</td> --}}
                            <td>Other Degree name</td>
                            <td>Other University name</td>
                            {{-- <td>Course Name</td> --}}
                            <td>Pass out year</td>
                            <td>GPA</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($educationDataAr as $educationData)
                            <tr>
                                {{-- <td>{{$educationData->degrees->name}}</td> --}}
                                {{-- <td>{{$educationData->university->name}}</td> --}}
                                <td>{{$educationData->other_degree_name}}</td>
                                <td>{{$educationData->other_college_name}}</td>
                                {{-- <td>{{$educationData->field_of_study}}</td> --}}
                                <td>{{$educationData->graduation_year}}</td>
                                <td>{{$educationData->gpa}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
