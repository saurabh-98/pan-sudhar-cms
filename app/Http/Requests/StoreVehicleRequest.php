<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE
    |--------------------------------------------------------------------------
    */

    public function authorize(): bool
    {
        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | RULES
    |--------------------------------------------------------------------------
    */

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | VEHICLE
            |--------------------------------------------------------------------------
            */

            'vehicle_number' => [

                'required',
                'string',
                'max:50'

            ],

            'vehicle_type' => [

                'nullable',
                'string',
                'max:100'

            ],

            /*
            |--------------------------------------------------------------------------
            | DRIVER
            |--------------------------------------------------------------------------
            */

            'driver_name' => [

                'nullable',
                'string',
                'max:255'

            ],

            'driver_phone' => [

                'nullable',
                'string',
                'max:20'

            ],

            /*
            |--------------------------------------------------------------------------
            | HELPER
            |--------------------------------------------------------------------------
            */

            'helper_name' => [

                'nullable',
                'string',
                'max:255'

            ],

            'helper_phone' => [

                'nullable',
                'string',
                'max:20'

            ],

            /*
            |--------------------------------------------------------------------------
            | CAPACITY
            |--------------------------------------------------------------------------
            */

            'capacity' => [

                'required',
                'integer',
                'min:1'

            ],

            /*
            |--------------------------------------------------------------------------
            | GPS DEVICE
            |--------------------------------------------------------------------------
            */

            'gps_device_id' => [

                'nullable',
                'string',
                'max:100'

            ],

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            'status' => [

                'required',

                'in:active,inactive,maintenance'

            ],

            /*
            |--------------------------------------------------------------------------
            | ROUTE
            |--------------------------------------------------------------------------
            */

            'route_id' => [

                'nullable',

                'exists:transport_routes,id'

            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MESSAGES
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            'vehicle_number.required' =>

                'Vehicle number is required.',

            'capacity.required' =>

                'Vehicle capacity is required.',

            'capacity.integer' =>

                'Capacity must be a valid number.',

            'capacity.min' =>

                'Capacity must be at least 1.',

            'route_id.exists' =>

                'Selected route does not exist.',

            'status.in' =>

                'Invalid vehicle status selected.',

        ];
    }
}