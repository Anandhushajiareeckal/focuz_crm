<script src="{{ asset('/js/selectpicker_data.js') }}"></script>
<section class="pt-5 fixed-top pb-5">
    <div class="container-full ">
        <div class="row bg-light shadow-sm  pt-3 pl-5  pb-2 ">
            <div class="col-md-2 d-none d-sm-none d-md-block"></div>
            <div class="col-sm-12 col-md-10 col-12">
                <button class="btn btn-sm btn-dark" id="add_new"><i class="fa fa-plus"></i>
                    &nbsp;Add New </button>
            </div>

        </div>
    </div>
</section>

<script>
    $(document).ready(function() {
        $('#add_new').click(function(e) {
            e.preventDefault();
            manage_course();
        });
    });

    async function manage_course(u_id = '', s_id = '', c_id = '', specialization_keys = '', status_load = 'active') {

        preloader.load();
        try {
            const response = await new Promise((resolve, reject) => {
                $.ajax({
                    type: "post",
                    url: "{{ route('manage_courses') }}",
                    data: {
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        reject(error);
                    }
                });
            });

            $('#modal_title').html("Manage Courses");
            $('#modal_body').html(response);
            $('#modal_create').modal('show');
            $('#modal_body .selectpicker').selectpicker();
            $('#modal_body #status_load').val(status_load);
            let params = {
                'active': "true",
            };
            await loadOptions(params, "{{ url('load_university') }}", updateOptions, '#modal_body #university',
                u_id, u_id);
            await loadOptions({}, "{{ url('load_streams') }}", updateOptions, '#modal_body #stream', s_id);
            if (u_id != '') {

                let params = {
                    'university_id': u_id,
                }
                await loadOptions(params, "{{ url('load_specialization') }}",
                    updateOptions,
                    '#modal_body #specialization', specialization_keys);
                await getCourseScheduleForm();
            }
            preloader.stop();
            $('#modal_body #specialization').on('show.bs.select', async function(e) {
                e.preventDefault();
                $('#modal_body #alert_modal').html("")
                $('#modal_body #alert_modal').addClass('d-none');
                $('#modal_body #alert_modal').removeClass('d-block');

                const university_id = $('#modal_body #university').val();
                const specialization_ids = $('#modal_body #specialization').val();

                if (!university_id) {
                    $('#modal_body #alert_modal').html("Please select a university to proceed")
                    $('#modal_body #alert_modal').removeClass('d-none');
                    $('#modal_body #alert_modal').addClass('d-block');
                } else {

                    let params = {
                        'university_id': university_id,
                    }
                    loadOptions(params, "{{ url('load_specialization') }}",
                        updateOptions,
                        '#modal_body #specialization', specialization_ids);
                    const $searchInput = $(this).closest('.bootstrap-select').find(
                        '.bs-searchbox input');

                    $searchInput.on('keyup', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const name = $(this).val();
                            if (name == '') {
                                $('#modal_body #alert_modal').html(
                                    "Please enter a value to create a new specialization");
                                $('#modal_body #alert_modal').removeClass('d-none');
                                $('#modal_body #alert_modal').addClass('d-block');
                            } else {
                                params = {
                                    'name': name,
                                    'university_id': university_id,
                                    '_token': $('meta[name="csrf-token"]').attr('content')
                                };

                                preloader.load();
                                $.ajax({
                                    type: "post",
                                    url: "{{ url('save_specialization') }}",
                                    data: params,
                                    success: function(response) {
                                        preloader.stop();
                                    }
                                });
                            }
                        }
                    });
                }
            });
        } catch (error) {
            preloader.stop();
            // console.error("Error in manage_course:", error);
        }

    }
</script>
