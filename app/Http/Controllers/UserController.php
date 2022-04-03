<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use League\CommonMark\Extension\CommonMark\Parser\Inline\BacktickParser;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function dashboarduser()
    {
        $user = User::count();
        $transaction = Transaction::count();
        $transactionaccept = Transaction::where('status', 'Sudah Tervalidasi')->count();
        $transactionproses = Transaction::where('status', 'Belum Validasi')->count();
        return view('dashboard', compact('user', 'transaction', 'transactionaccept', 'transactionproses'));
    }
    public function listaccount()
    {
        $user = User::paginate(20);

        return view('transaction.list-account', compact('user'));
    }


    public function editAccount($id)
    {
        $user = User::find($id);
        return view('transaction.edit-account', compact('user'));
    }
    public function updateAccount(Request $request, User $user, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'npm' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $user = User::find($id);

        $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'npm' => $request['npm'],
            'phone' => $request['phone'],
        ]);

        return redirect()->route('account.list')->with('success', ' Data telah diperbaharui!');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        return view('profile', compact('user'));
    }

    public function dashboard()
    {
        $user = User::count();
        $transaction = Transaction::count();
        $transactionaccept = Transaction::where('status', 'Sudah Tervalidasi')->count();
        $transactionproses = Transaction::where('status', 'Belum Validasi')->count();
        return view('dashboard-admin', compact('user', 'transaction', 'transactionaccept', 'transactionproses'));
    }


    public function update(Request $request, User $user, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'npm' => 'required',
            'phone' => 'required',
            'email' => 'required',
        ]);

        $user = User::find($id);

        $user->update([
            'name' => $request['name'],
            'email' => $request['email'],
            'npm' => $request['npm'],
            'phone' => $request['phone'],
        ]);

        return redirect()->route('profile')->with('success', ' Data telah diperbaharui!');
    }

    public function destroy(User $user, $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('account.list')->with('success', ' Data telah Dihapus!');
    }
}
