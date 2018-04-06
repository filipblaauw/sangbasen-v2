<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FishRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      return [
        'date' => 'required|date_format:Y-m-d',
        'time' => 'required',
        'species' => 'required',
        'river' => 'required',
        'zone' => 'nullable',
        'lat' => 'required',
        'lng' => 'required',
        'weight' => 'required',
        'length' => 'nullable',
        'bait' => 'nullable',
        'line' => 'nullable',
        'waterTemp' => 'nullable',
        'waterLevel' => 'nullable',
        'sex' => 'required',
        'farmed' => 'required',
        'lice' => 'required',
        'released' => 'required',
        'photo' => 'nullable',
        'description' => 'nullable'
      ];
    }

    /**
     * Translate fields with user friendly name.
     *
     * @return array
     */
    public function attributes()
    {
      return [
        'date' => trans('fish.date'),
        'lat' => trans('fish.lat'),
        'time' => trans('fish.time'),
        'species' => trans('fish.species'),
        'river' => trans('fish.river'),
        'zone' => trans('fish.zone'),
        'weight' => trans('fish.weight'),
        'length' => trans('fish.length'),
        'bait' => trans('fish.bait'),
        'line' => trans('fish.line'),
        'waterTemp' => trans('fish.watertemp'),
        'waterLevel' => trans('fish.waterlevel'),
        'sex' => trans('fish.sex'),
        'farmed' => trans('fish.farmed'),
        'lice' => trans('fish.lice'),
        'released' => trans('fish.released'),
        'photo' => trans('fish.photo'),
        'description' => trans('fish.description')
      ];
    }
}
