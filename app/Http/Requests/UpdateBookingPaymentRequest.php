<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingPaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_status' => 'required|in:pending,paid,partial,cancelled',
            'payment_slip' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ];
    }
}