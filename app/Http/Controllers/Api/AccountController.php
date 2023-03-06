<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;

class AccountController extends Controller
{
    public function index()
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

    public function store(StoreAccountRequest $request)
    {
        $account = Account::where('username', $request->username)
                ->orWhere('email', $request->email)
                ->first();

        // return ($account);

        // ($account) ? $s = "true" : $s = "false";
        ($account) ?  
            throw new AccountResponseException("Username / Email already exists.", 422) : 
            $account = Account::create($request->all()); 

        return response()->json([
            'message' => "Account has been registered.",
            'account' => $account
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // try {
            $account = Account::find($id);

            if ($account) {
                return response()->json([
                    'message' => "Displaying account details.",
                    'account' => $account
                ], 200);
            }

            // return response()->json([
            //     'error' => 'eww'
            // ], 404);
        // } catch (Exception $ex) {
            // abort(500, 'Could not create office or assign');
        // }

        // $account = Account::find($id);

        // if ($account) {
        //     return response()->json([
        //         'message' => "Displaying account details.",
        //         'account' => $account
        //     ], 200);
        // }

        // throw new AccountResponseException("Account cannot be found.", 404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account = Account::find($id);

        is_null($account) ? throw new AccountResponseException("The account does not exist", 404) :
            $account->update($request->all());

        return response()->json([
            'message' => "Account details has been updated.",
            'account' => $account
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account = Account::find($id);

        is_null($account) ? throw new AccountResponseException("Account does not exist.", 404) : $account->delete();

        return response()->json([ 'message' => "Account deleted." ], 200);
    }
}
