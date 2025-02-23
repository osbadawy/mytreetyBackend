<?php

namespace App\Http\Controllers;

use App\Models\faq;
use App\Models\FaqTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $faqs = faq::orderBy('created_at', 'desc');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $faqs = $faqs->where('title', 'like', '%' . $sort_search . '%');
        }
        $faqs = $faqs->paginate(15);

        return view('backend.faqs.index', compact('faqs', 'sort_search'));

    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create()
    {
        return view('backend.faqs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(Request $request): RedirectResponse
    {
        $faq = new faq;

        $faq->title = $request->title;
        $faq->sub_title = $request->sub_title;
        $faq->type = $request->type;
        $faq->save();

        $faq_translation = FaqTranslation::firstOrNew(['lang' => $request->lang, 'faq_id' => $faq->id]);

        $faq_translation->title = $request->title;
        $faq_translation->sub_title = $request->sub_title;

        $faq_translation->save();


        flash(translate('Faq has been created successfully'))->success();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     */
    public function show($id): RedirectResponse
    {
        $faq = faq::findOrFail($id);

        // faq Translations Delete
        foreach ($faq->faq_translations as $key => $faq_translation) {
            $faq_translation->delete();
        }
        $faq->delete();


        flash(translate('Faq has been deleted successfully'))->success();
        return redirect()->route('faqs.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     */
    public function edit(Request $request, $id)
    {
        $lang = $request->lang;
        $faq = faq::findOrFail($id);

        return view('backend.faqs.edit', compact('faq', 'lang'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $faq = faq::findOrFail($id);
        if ($request->lang == env("DEFAULT_LANGUAGE")) {
            $faq->title = $request->title;
            $faq->sub_title = $request->sub_title;
        }

        $faq->type = $request->type;

        $faq->save();

        $faq_translation = FaqTranslation::firstOrNew(['lang' => $request->lang, 'faq_id' => $faq->id]);

        $faq_translation->title = $request->title;
        $faq_translation->sub_title = $request->sub_title;
        $faq_translation->faq_id = $faq->id;
        $faq_translation->lang = $request->lang;
        $faq_translation->save();


        flash(translate('Faq has been updated successfully'))->success();
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($id): RedirectResponse
    {
        $faq = faq::findOrFail($id);

        // faq Translations Delete
        foreach ($faq->faq_translations as $key => $faq_translation) {
            $faq_translation->delete();
        }
        $faq->delete();


        flash(translate('Faq has been deleted successfully'))->success();
        return redirect()->route('faqs.index');
    }
}
