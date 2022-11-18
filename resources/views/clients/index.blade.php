@extends('clients.layout')

@section('client_content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Client List</h2>
            <a class="btn btn-primary" href="{{ route('clients.create') }}">Add New</a>
        </div>

        <div id="client-content-area table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="column">Name</th>
                        <th scope="column">Client ID</th>
                        <th scope="column">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->id }}</td>
                            <td>
                                <a href="{{ route('clients.edit', [ 'id' => $client->id ]) }}" class="mx-2">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
