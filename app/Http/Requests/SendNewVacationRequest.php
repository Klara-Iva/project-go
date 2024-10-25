<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendNewVacationRequest extends FormRequest
{
    protected $remainingVacationDays;

    public function setRemainingVacationDays($remainingVacationDays)
    {
        $this->remainingVacationDays = $remainingVacationDays;
    }

    public function rules()
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days_off' => 'required|integer|min:1|max:' . $this->remainingVacationDays,
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',
            'start_date.after_or_equal' => 'The start date cannot be in the past.',
            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after start day.',
            'days_off.required' => 'Please specify the number of days off.',
            'days_off.integer' => 'Days off must be a whole number.',
            'days_off.min' => 'You must request at least 1 day off.',
            'days_off.max' => 'You cannot request more days off than your available annual leave days (' . $this->remainingVacationDays . ').',
        ];
    }

}