<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Rules\RedirectRule;
use Laravel\Passport\Passport;

class ClientController extends Controller
{
    public function __construct(
        protected ClientRepository $clients,
        protected ValidationFactory $validation,
        protected RedirectRule $redirectRule
    ) {
    }

    public function index(): View
    {
        $clients = Passport::client()->where('revoked', false)->orderBy('name', 'DESC')->get();

        if (!Passport::$hashesClientSecrets) {
            $client = $clients->makeVisible('secret');
        }

        return view('clients.index')->with([
            'clients' => $clients
        ]);
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(Request $request): View
    {
        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
            'confidential' => 'boolean'
        ])->validate();

        $client = $this->clients->create(
            $request->user()->getAuthIdentifier(),
            $request->name,
            $request->redirect,
            null,
            false,
            false,
            (bool) $request->input('confidential', true)
        );

        if (Passport::$hashesClientSecrets) {
            $client = ['plainSecret' => $client->plainSecret] + $client->toArray();
            $secretHashed = true;
        } else {
            $client = $client->makeVisible('secret')->toArray();
            $secretHashed = false;
        }

        return view('clients.store_success', [
            'client' => $client,
            'secretHashed' => $secretHashed,
        ]);
    }

    public function edit(string $id): View
    {
        $clientRepo = Passport::client();

        $client = $clientRepo->where($clientRepo->getKeyName(), $id)
            ->where('revoked', false)->first();

        if ($client) {
            return view('clients.edit')->with([
                'client' => $client
            ]);
        }

        abort(404);
    }

    public function update(Request $request): RedirectResponse
    {
        $this->validation->make($request->only(['id']), [
            'id' => 'required|string|max:191'
        ])->validate();

        $clientRepo = Passport::client();

        $client = $clientRepo->where($clientRepo->getKeyName(), $id)
            ->where('revoked', false)->first();

        if (is_null($client)) {
            abort(404);
        }

        $this->validation->make($request->all(), [
            'name' => 'required|max:191',
            'redirect' => ['required', $this->redirectRule],
        ])->validate();

        if ($this->clients->update(
            $client,
            $request->name,
            $request->redirect
        )) {
            return redirect()->route('clients.index');
        }

        return back()->withInput()->withErrors([
            'update' => __('Update Failed')
        ]);
    }

    public function delete(Request $request): JsonResponse
        {
        $this->validation->make($request->only(['id']), [
            'id' => 'required|string|max:191',
        ])->validate();

        $clientRepo = Passport::client();

        $client = $clientRepo->where($clientRepo->getKeyName(), $request->id)
            ->where('revoked', false)->first();

        if (is_null($client)) {
            return response()->json([
                'message' => _('Can not find client'),
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $this->clients->delete($client);

        return response()->json([
            'message' => __('Delete Client Successfully'),
        ]);
    }
}
