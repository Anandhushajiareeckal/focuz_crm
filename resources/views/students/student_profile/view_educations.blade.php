<div class="row">
    <div class="col">
        {{-- View Educational Qualification --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">View Educational Qualification</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>SSLC Board</th>
                            <th>SSLC Passout Year</th>
                            <th>Intermediate Board</th>
                            <th>Intermediate Passout Year</th>
                            <th>Other Degree Name</th>
                            <th>Other University Name</th>
                            <th>Pass Out Year</th>
                            <th>GPA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($educationDataAr as $educationData)
                            <tr>
                                <td>{{ $educationData->sslc_board }}</td>
                                <td>{{ $educationData->sslc_passout }}</td>
                                <td>{{ $educationData->intermediate_board }}</td>
                                <td>{{ $educationData->intermediate_passout }}</td>
                                <td>{{ $educationData->other_degree_name }}</td>
                                <td>{{ $educationData->other_college_name }}</td>
                                <td>{{ $educationData->graduation_year }}</td>
                                <td>{{ $educationData->gpa }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- View University Details --}}
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title">View University Details</h3>
            </div>
            <div class="card-body table-responsive">
                <table style="font-size:10pt;" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ABC ID</th>
                            <th>DEB ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($educationDataAr as $educationData)
                            <tr>
                                <td>{{ $educationData->abc_id }}</td>
                                <td>{{ $educationData->deb_id }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>