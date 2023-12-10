<?php

namespace App\Http\Services\Slider;

use App\Models\Slider;
use Symfony\Component\HttpFoundation\Session\Session;
use Illuminate\Support\Facades\Storage;

class SliderService
{
    public function insert($request)
    {
        try {
            // $request->except('_token');
            Slider::create($request->input());
            $request->session()->flash('success', 'Them Slider moi thanh cong');
        } catch (\Exception $err) {
            $request->session()->flash('error', 'Them Slider That bai');
            \Log::info($err->getMessage());

            return false;
        }

        return true;
    }

    public function get()
    {
        return Slider::orderByDesc('id')->paginate(15);
    }

    public function update($request, $slider)
    {
        try {
            $slider->fill($request->input());
            $slider->save();
            $request->session()->flash('success', 'Cap nhap slider thanh cong');
        } catch (\Exception $err) {
            $request->session()->flash('error', 'Cap nhap Slider khong thanh cong');
            \Log::info($err->getMessage());
            return false;
        }

        return true;
    }

    public function destroy($request)
    {
        $slider = Slider::where('id', $request->input('id'))->first();
        if ($slider) {
            $path = str_replace('storage', 'public', $slider->thumb);
            Storage::delete($path);
            $slider->delete();
            return true;
        }

        return false;
    }

    public function show()
    {
        return Slider::where('active', 1)->orderByDesc('sort_by')->get();
    }
}