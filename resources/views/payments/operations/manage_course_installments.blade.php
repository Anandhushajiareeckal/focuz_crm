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
            manage_course_installment();
        });
    });

    async function manage_course_installment(student_id = '', s_id = '', c_id = '', specialization_keys = '',
        status_load = 'active') {

        preloader.load();
        try {
            const response = await new Promise((resolve, reject) => {
                $.ajax({
                    type: "post",
                    url: "{{ route('manage_course_installment') }}",
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

            $('#modal_title').html("Manage Courses Installments");
            $('#modal_body').html(response);
            $('#modal_create').modal('show');
            $('#modal_body .selectpicker').selectpicker();
            $('#modal_body #status_load').val(status_load);
            let params = {
                'active': "true",
            };

            preloader.stop();

        } catch (error) {
            preloader.stop();
            // console.error("Error in manage_course:", error);
        }

    }
</script>
