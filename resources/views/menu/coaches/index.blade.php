@extends('layouts.master')
@section('content')
<link rel="stylesheet" href="{{asset('/css/app.css')}}">

<div class="wrapper mt-5">
  <!-- Content Wrapper. Contains page content -->
  <div class="">

    <!-- Main content -->
    <section class="content">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Coaches</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered table-hover table-striped data-table" id="data-table">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Gym Name</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                 
                  </tbody>
                </table>
              </div>
             
              <!-- /.card-body -->
            </div>

            <!-- /.card -->
            {{-- modal  --}}
            <div class="modal" id="deleteAlert" tabindex="-1">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header text-center">
                    <h1 class="modal-title text-center mx-auto"><span class="badge bg-danger">Warning</span></h1>
                  </div>
                  <div class="modal-body bg-secondary text-white">
                    <p class="text-center h3 ">Do you want to delete This Post ? </p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <a href="javascript:void(0)"  class="btn btn-danger btn-xl mx-3 deleteManager" data-original-title="Delete">Delete</a>
                  </div>
                </div>
              </div>
            </div>
          {{-- end of modal --}}
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<script>

$(function () {
  $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('coaches.index') }}",
        columns: [
            {data: 'id'},
            {data: 'name'},
            {data: 'gym.name'},    
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    } );
    var coachId;
    $('body').on('click', '.delete', function() {
       coachId = $(this).data("id");

       $('body').on('click','.deleteManager', () => {
        $.ajax({
            url: "/coaches/" + coachId,
            type: "DELETE",
            data: {_token: '{!! csrf_token() !!}',}, 
            success:(response) =>
            {
              $('#deleteAlert').modal('hide');
              table.ajax.reload();
            }  
          });
        });
    });
});



</script>

@endsection