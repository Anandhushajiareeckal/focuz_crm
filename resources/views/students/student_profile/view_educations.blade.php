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
                                <td>Other Degree Name</td>
                                <td>Other University Name</td>
                                <td>Pass out year</td>
                                <td>GPA</td>
                               
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($educationDataAr as $educationData)
                                <tr>
                                    <td>{{$educationData->other_degree_name}}</td>
                                    <td>{{$educationData->other_college_name}}</td>
                                    <td>{{$educationData->graduation_year}}</td>
                                    <td>{{$educationData->gpa}}</td>
                                   
                                </tr>
                            @endforeach
                        </tbody>

                </table>
            </div>
        </div>

         <div class="card">
            <div class="card-header">
                <h3 class="card-title">University Details</h3>
            </div>
            <div class="card-body  table-responsive">
                <table id="view_students" style="font-size:10pt;" class="table table-bordered table-striped">
                    <thead>
                            <tr>
                                
                                <td>ABC ID</td>
                                <td>DEB ID</td> <!-- was BED ID -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($educationDataAr as $educationData)
                                <tr>
                                   
                                    <td>{{$educationData->abc_id}}</td>
                                    <td>{{$educationData->deb_id}}</td> <!-- match the form -->
                                </tr>
                            @endforeach
                        </tbody>

                </table>
            </div>
        </div>
        
         
    </div>
</div>
