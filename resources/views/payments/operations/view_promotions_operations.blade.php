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
            preloader.load();
            $.ajax({
                type: "post",
                url: "{{ route('manage_promotions') }}",
                data: {
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    preloader.stop();
                    $('#modal_title').html("Manage Promotions");
                    $('#modal_body').html(response);
                    $('#modal_create').modal('show');
                }
            });
        });
    });
</script>
