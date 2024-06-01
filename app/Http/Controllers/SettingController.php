<?php

namespace App\Http\Controllers;

use App\Http\Requests\Setting\StoreSettingRequest;
use App\Http\Requests\Setting\UpdateSettingRequest;
use App\Http\Resources\SettingResource;
use App\Models\Setting;

class SettingController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $settings = Setting::all();
    // convert above to key/value object
    $settings = $settings->mapWithKeys(function ($setting) {
      return [$setting['key'] => $setting['value']];
    });

    return $this->sendResponse($settings, 'Settings retrieved successfully.');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(StoreSettingRequest $request)
  {
    $setting = Setting::make($request->all());
    $setting->save();

    return $this->sendResponse(new SettingResource($setting), 'Setting created successfully.');
  }

  /**
   * Display the specified resource.
   */
  public function show(Setting $setting)
  {
    return $this->sendResponse(new SettingResource($setting), 'Setting retrieved successfully.');
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateSettingRequest $request)
  {
    $settings = $request->all();

    foreach ($settings as $key => $value) {
      Setting::where('key', $key)->update(['value' => $value]);
    }

    return $this->sendResponse(new SettingResource($settings), 'Setting updated successfully.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Setting $setting)
  {
    $setting->delete();

    return $this->sendResponse([], 'Setting deleted successfully.');
  }
}
