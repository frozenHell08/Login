<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;
use App\Exceptions\AccountException;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function index() //working w/ pagination
    {
        $accounts = Account::paginate(request()->all());

        if (Account::count() == 0) {
            return response()->json([
                'message' => "There are no accounts registered."
            ]);
        }

        return response()->json([
            'accounts' => $accounts
        ], 200);
    }

    public function store(Request $request) //working
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'username' => ['required', 'max:255', 'unique:accounts'],
            'email' => ['required', 'email', 'max:255', 'unique:accounts'],
            'password' => ['required', 'min:8', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $account = Account::create($request->all());

        return response()->json([
            'message' => "Account has been registered.",
            'account' => $account
        ], 201);
    }

    public function show(Request $request, string $id) // working
    {
        $account = Account::find($id);

        if ($account) {
            return response()->json([
                'message' => "Displaying account details.",
                'account' => $account,
            ], 200);
        }

        throw new AccountException("Account cannot be found.", 404);
    }

    public function update(Request $request, string $id)
    {
        $account = Account::find($id);

        if (!$account) return throw new AccountException("The account does not exist", 404);
        
        $validator = Validator::make($request->all(), [
            'firstname' => ['required', 'max:255'],
            'lastname' => ['required', 'max:255'],
            'username' => ['required', 'max:255', Rule::unique('accounts')->ignore($account->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('accounts')->ignore($account->id)],
            'password' => ['required', 'min:8', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $account->update($request->all());
        
        return response()->json([
            'message' => "Account details has been updated.",
            'account' => $account
        ], 200);
    }

    public function destroy(Account $account)
    {
        $account = Account::find($id);

        is_null($account) ? throw new AccountException("Account does not exist.", 404) : $account->delete();

        return response()->json([ 'message' => "Account deleted." ], 200);
    }
}
