<?php

namespace App\Http\Controllers;

use App\Models\PaymentModel;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  public function index(){
  $payments = PaymentModel::with(['user', 'listing'])->latest()->get();
    return view('Dashboard.payment.index', compact('payments'));
  }
}
