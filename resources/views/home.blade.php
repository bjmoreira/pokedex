@extends('layouts.admin')

@section('content')

  <div id="content">

    <!-- Begin Page Content -->
    <div class="container-fluid">

      <!-- Page Heading -->
      <h1 class="h3 mb-2 text-gray-800">Pokemons</h1>
      <p class="mb-4">Lista de todos os pokemons.</p>

      <!-- DataTales Example -->
      <div class="card shadow mb-4">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Lista</h6>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
              <thead>
                <tr>
                  <th>Name</th>

                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Name</th>

                </tr>
              </tfoot>
              
            </table>
          </div>
        </div>
      </div>

    </div>
    <!-- /.container-fluid -->

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/datatables-home.js') }}"></script>

  </div>
@endsection
