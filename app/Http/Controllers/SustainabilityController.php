<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Sustainability;
use App\Models\SustainabilityTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Str;

class SustainabilityController extends Controller
{
    public function index(Request $request)
    {
        $sort_search =null;
        $sustainabilities = Sustainability::orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $sustainabilities = $sustainabilities->where('name', 'like', '%'.$sort_search.'%');
        }
        $sustainabilities = $sustainabilities->paginate(15);

        return view('backend.product.sustainabilities.index', compact('sustainabilities', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        $categories=Category::where('parent_id',0)->get();
        return view('backend.product.sustainabilities.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request): RedirectResponse
    {

        $sustainability = new Sustainability();
        $sustainability->name = $request->name;
        // $sustainability->image = $request->image;
        $sustainability->description = $request->description;
        $sustainability->weight = $request->weight;

        if ($request->slug != null) {
            $sustainability->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->slug));
        }
        else {
            $sustainability->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(1);
        }


        $sustainability->save();

        $sustainability_translation = SustainabilityTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'sustainability_id' => $sustainability->id]);
        $sustainability_translation->name = $request->name;
        $sustainability_translation->description = $request->description;
        $sustainability_translation->image = $request->image;
        $sustainability_translation->save();

        flash(translate('Sustainability has been inserted successfully'))->success();
        return redirect()->route('sustainabilities.index');
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $sustainability = Sustainability::findOrFail($id);
        $categories=Category::where('parent_id',0)->get();

        return view('backend.product.sustainabilities.edit', compact('sustainability','categories', 'lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $sustainability = Sustainability::findOrFail($id);
        if($request->lang == env("DEFAULT_LANGUAGE")){
            $sustainability->name = $request->name;
            $sustainability->description = $request->description;
        }

        // $sustainability->image = $request->image;

        $sustainability->weight = $request->weight;

        if ($request->slug != null) {
            $sustainability->slug = strtolower($request->slug);
        }
        else {
            $sustainability->slug = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $request->name)).'-'.Str::random(5);
        }

        $sustainability->save();

        $sustainability_translation = SustainabilityTranslation::firstOrNew(['lang' => $request->lang, 'sustainability_id' => $sustainability->id]);

        $sustainability_translation->name = $request->name;
        $sustainability_translation->description = $request->description;
        $sustainability_translation->image = $request->image;

        $sustainability_translation->save();


        flash(translate('Sustainability has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function show($id): RedirectResponse
    {

        $sustainability = Sustainability::findOrFail($id);

        // Sustainability Translations Delete
        foreach ($sustainability->sustainability_translations as $key => $sustainability_translation) {
            $sustainability_translation->delete();
        }
        $sustainability->delete();


        flash(translate('Sustainability has been deleted successfully'))->success();
        return redirect()->route('sustainabilities.index');
    }


}
