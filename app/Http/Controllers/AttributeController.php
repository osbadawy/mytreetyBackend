<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\AttributeTranslation;
use App\Models\AttributeValue;
use App\Models\Color;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;

class AttributeController extends Controller
{

    public function index()
    {
        $attributes = Attribute::orderBy('created_at', 'desc')->get();
        return view('backend.product.attribute.index', compact('attributes'));
    }


    public function store(Request $request): RedirectResponse
    {
        $attribute = new Attribute;
        $attribute->name = $request->name;
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(translate('Attribute has been inserted successfully'))->success();
        return redirect()->route('attributes.index');
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $data['attribute'] = Attribute::findOrFail($id);
        $data['all_attribute_values'] = AttributeValue::with('attribute')->where('attribute_id', $id)->get();

        return view("backend.product.attribute.attribute_value.index", $data);
    }


    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $attribute = Attribute::findOrFail($id);
        return view('backend.product.attribute.edit', compact('attribute', 'lang'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $attribute = Attribute::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $attribute->name = $request->name;
        }
        $attribute->save();

        $attribute_translation = AttributeTranslation::firstOrNew(['lang' => $request->lang, 'attribute_id' => $attribute->id]);
        $attribute_translation->name = $request->name;
        $attribute_translation->save();

        flash(translate('Attribute has been updated successfully'))->success();
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store_attribute_value(Request $request): RedirectResponse
    {
        $attribute_value = new AttributeValue;
        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);
        $attribute_value->save();

        flash(translate('Attribute value has been inserted successfully'))->success();
        return redirect()->route('attributes.show', $request->attribute_id);
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function edit_attribute_value(Request $request, $id)
    {
        $attribute_value = AttributeValue::findOrFail($id);
        return view("backend.product.attribute.attribute_value.edit", compact('attribute_value'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update_attribute_value(Request $request, $id): RedirectResponse
    {
        $attribute_value = AttributeValue::findOrFail($id);

        $attribute_value->attribute_id = $request->attribute_id;
        $attribute_value->value = ucfirst($request->value);

        $attribute_value->save();

        flash(translate('Attribute value has been updated successfully'))->success();
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy_attribute_value($id): RedirectResponse
    {
        $attribute_values = AttributeValue::findOrFail($id);
        AttributeValue::destroy($id);

        flash(translate('Attribute value has been deleted successfully'))->success();
        return redirect()->route('attributes.show', $attribute_values->attribute_id);

    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $attribute = Attribute::findOrFail($id);

        foreach ($attribute->attribute_translations as $key => $attribute_translation) {
            $attribute_translation->delete();
        }

        Attribute::destroy($id);
        flash(translate('Attribute has been deleted successfully'))->success();
        return redirect()->route('attributes.index');

    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function colors(Request $request)
    {
        $sort_search = null;
        $colors = Color::orderBy('created_at', 'desc');

        if ($request->search != null) {
            $colors = $colors->where('name', 'like', '%' . $request->search . '%');
            $sort_search = $request->search;
        }
        $colors = $colors->paginate(10);

        return view('backend.product.color.index', compact('colors', 'sort_search'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store_color(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:colors|max:255',
        ]);
        $color = new Color;
        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;

        $color->save();

        flash(translate('Color has been inserted successfully'))->success();
        return redirect()->route('colors');
    }

    /**
     * @param Request $request
     * @param $id
     * @return Application|Factory|View
     */
    public function edit_color(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        return view('backend.product.color.edit', compact('color'));
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update_color(Request $request, $id): RedirectResponse
    {
        $color = Color::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:colors,code,' . $color->id,
        ]);

        $color->name = Str::replace(' ', '', $request->name);
        $color->code = $request->code;

        $color->save();

        flash(translate('Color has been updated successfully'))->success();
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy_color($id): RedirectResponse
    {
        Color::destroy($id);

        flash(translate('Color has been deleted successfully'))->success();
        return redirect()->route('colors');

    }

}
