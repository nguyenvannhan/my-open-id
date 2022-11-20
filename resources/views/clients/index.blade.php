@extends('clients.layout')

@section('client_content')
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="mb-0">Client List</h2>
            <a class="btn btn-primary" href="{{ route('clients.create') }}">{{ __('Add New') }}</a>
        </div>

        <div id="client-content-area table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="column">{{ __('Name') }}</th>
                        <th scope="column">{{ __('Client ID') }}</th>
                        <th scope="column">{{ __('Redirect URL') }}</th>
                        <th scope="column">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->redirect }} </td>
                            <td>
                                <a href="{{ route('clients.edit', ['id' => $client->id]) }}" class="mx-2">Edit</a>
                                <a href="#" data-id="{{ $client->id }}"
                                    class="mx-2 delete-btn text-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            const deleteUrl = "{{ route('clients.delete') }}";

            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                if (confirm('Do you really want to delete this client?') == true) {
                    const id = $(this).data('id');

                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            id
                        },
                        success: function(res) {
                            alert('Delete client successfully!')

                            window.location.reload();
                        },
                        error: function(xhr, status, err) {
                            alert('Delete client Failed')
                        },
                    });
                }
            });
        });
    </script>
@endsection
