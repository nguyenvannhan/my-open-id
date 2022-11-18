<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Laravel\Passport\ClientRepository;
use Laravel\Passport\Http\Rules\RedirectRule;
use Laravel\Passport\Passport;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;

class ClientController extends Controller
{
    public function __construct(
        protected ClientRepository $clients,
        protected ValidationFactory $validation,
        protected RedirectRule $redirectRule
    ) {}

    public function index(): View
    {
        $clients = Passport::client()->orderBy('name', 'DESC')->get();

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
            $request->user()->getAuthIdentifier(), $request->name, $request->redirect,
            null, false, false, (bool) $request->input('confidential', true)
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
}
