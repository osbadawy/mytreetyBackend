<?php

namespace App\Http\Controllers;

use App\Models\AppTranslation;
use App\Models\faq;
use App\Models\FaqTranslation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Session;
use App\Models\Language;
use App\Models\ProductTranslation;
use App\Models\Translation;
use Artisan;
use Cache;
use DB;
use JoggApp\GoogleTranslate\GoogleTranslateFacade;
use Storage;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $request->session()->put('locale', $request->locale);
        $language = Language::where('code', $request->locale)->first();
        flash(translate('Language changed to') ." ". $language->name)->success();
    }

    public function index(Request $request)
    {
        $languages = Language::paginate(10);
        return view('backend.setup_configurations.languages.index', compact('languages'));
    }

    public function create(Request $request)
    {
        return view('backend.setup_configurations.languages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $language = new Language;
        $language->name = $request->name;
        $language->code = $request->code;
        $language->app_lang_code = $request->app_lang_code;
        $language->save();

        Cache::forget('app.languages');

        flash(translate('Language has been inserted successfully'))->success();
        return redirect()->route('languages.index');
    }

    public function show(Request $request, $id)
    {
        $sort_search = null;
        $language = Language::findOrFail($id);
        $lang_keys = Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'));
        if ($request->has('search')) {
            $sort_search = $request->search;
            $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
        }
        $lang_keys = $lang_keys->paginate(50);
        return view('backend.setup_configurations.languages.language_view', compact('language', 'lang_keys', 'sort_search'));
    }

    public function edit($id)
    {
        $language = Language::findOrFail($id);
        return view('backend.setup_configurations.languages.edit', compact('language'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $language = Language::findOrFail($id);
        if (env('DEFAULT_LANGUAGE') == $language->code) {
            flash(translate('Default language can not be edited'))->error();
            return back();
        }
        $language->name = $request->name;
        $language->code = $request->code;
        $language->app_lang_code = $request->app_lang_code;
        $language->save();

        Cache::forget('app.languages');

        flash(translate('Language has been updated successfully'))->success();
        return redirect()->route('languages.index');
    }

    public function key_value_store(Request $request)
    {
        $language = Language::findOrFail($request->id);
        foreach ($request->values as $key => $value) {
            $translation_def = Translation::where('lang_key', $key)->where('lang', $language->code)->latest()->first();
            if ($translation_def == null) {
                $translation_def = new Translation;
                $translation_def->lang = $language->code;
                $translation_def->lang_key = $key;
                $translation_def->lang_value = $value;
                $translation_def->save();
            } else {
                $translation_def->lang_value = $value;
                $translation_def->save();
            }
        }
        Cache::forget('translations-' . $language->code);
        flash(translate('Translations updated for ') . $language->name)->success();
        return back();
    }

    public function update_rtl_status(Request $request): int
    {
        $language = Language::findOrFail($request->id);
        $language->rtl = $request->status;
        if ($language->save()) {
            flash(translate('RTL status updated successfully'))->success();
            return 1;
        }
        return 0;
    }

    public function destroy($id): RedirectResponse
    {
        $language = Language::findOrFail($id);
        if (env('DEFAULT_LANGUAGE') == $language->code) {
            flash(translate('Default language can not be deleted'))->error();
        } else {
            if ($language->code == Session::get('locale')) {
                Session::put('locale', env('DEFAULT_LANGUAGE'));
            }
            Language::destroy($id);
            flash(translate('Language has been deleted successfully'))->success();
        }
        return redirect()->route('languages.index');
    }


    //App-Translation
    public function importEnglishFile(Request $request): RedirectResponse
    {
        $path = Storage::disk('local')->put('app-translations', $request->lang_file);

        $contents = file_get_contents(public_path($path));

        try {
            foreach (json_decode($contents) as $key => $value) {
                AppTranslation::updateOrCreate(
                    ['lang' => 'en', 'lang_key' => $key],
                    ['lang_value' => $value]
                );
            }
        } catch (\Throwable $th) {
            //throw $th;
        }

        flash(translate('Translation keys has been imported successfully. Go to App Translation for more..'))->success();
        return back();
    }

    public function showAppTranlsationView(Request $request, $id)
    {
        $sort_search = null;
        $language = Language::findOrFail($id);
        $lang_keys = AppTranslation::where('lang', 'en');
        if ($request->has('search')) {
            $sort_search = $request->search;
            $lang_keys = $lang_keys->where('lang_key', 'like', '%' . $sort_search . '%');
        }
        $lang_keys = $lang_keys->paginate(50);
        return view('backend.setup_configurations.languages.app_translation', compact('language', 'lang_keys', 'sort_search'));
    }

    public function storeAppTranlsation(Request $request)
    {
        $language = Language::findOrFail($request->id);
        foreach ($request->values as $key => $value) {
            AppTranslation::updateOrCreate(
                ['lang' => $language->app_lang_code, 'lang_key' => $key],
                ['lang_value' => $value]
            );
        }
        flash(translate('App Translations updated for ') . $language->name)->success();
        return back();
    }

    public function exportARBFile($id)
    {
        $language = Language::findOrFail($id);
        try {
            // Write into the json file
            $filename = "app_{$language->app_lang_code}.arb";
            $contents = AppTranslation::where('lang', $language->app_lang_code)->pluck('lang_value', 'lang_key')->toJson();

            return response()->streamDownload(function () use ($contents) {
                echo $contents;
            }, $filename);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function fix_translations()
    {
        $translations = DB::table('translations')->where('lang', 'en')->get();
         $catstranslations= DB::table('category_translations')->where('lang', 'en')->get();
         $faqstranslations= DB::table('faq_translations')->where('lang', 'en')->get();
         $guidestranslations= DB::table('guide_translations')->where('lang', 'en')->get();

        // normal translations
        foreach ($translations as $key => $translation) {


            $translationDE = DB::table('translations')->where('lang', 'de')->where('lang_key', $translation->lang_key)->first();

            if (!$translationDE) {

                $lang_value = GoogleTranslateFacade::justTranslate($translation->lang_value, 'de');

                $translationDE = DB::table('translations')->insert([
                    'lang' => 'de',
                    'lang_key' => $translation->lang_key,
                    'lang_value' => $lang_value

                ]);
            }
        }

        //category translations
         foreach ($catstranslations as $key => $translation) {


             $translationDE = DB::table('category_translations')->where('lang', 'de')->where('name', $translation->name)->first();

             if (!$translationDE) {

                 $lang_value = GoogleTranslateFacade::justTranslate($translation->name, 'de');

                 $translationDE = DB::table('category_translations')->insert([
                     'lang' => 'de',
                     'name' => $lang_value,
                     'category_id' => $translation->category_id

                 ]);
             }
         }

        $faqs=faq::all();
        foreach ($faqs as $key => $faq) {
           $trans=new FaqTranslation;
           $trans->title=$faq->title;
           $trans->sub_title=$faq->sub_title;
           $trans->faq_id=$faq->id;
           $trans->lang='en';
           $trans->save();
        }

        //faq translations
        foreach ($faqs as $key => $translation) {


            $translationDE = DB::table('faq_translations')->where('lang', 'de')->where('title', $translation->title)->first();

            if (!$translationDE) {

                $title = GoogleTranslateFacade::justTranslate($translation->title, 'de');
                $sub_title = GoogleTranslateFacade::justTranslate($translation->sub_title, 'de');

                $translationDE = DB::table('faq_translations')->insert([
                    'lang' => 'de',
                    'title' => $title,
                    'sub_title' => $sub_title,
                    'faq_id' => $translation->id

                ]);
            }
        }

        Artisan::call('cache:clear');
        flash(translate('Static Translation fixed'))->success();

        return redirect()->back();
    }

    public function fix_products_translations(): RedirectResponse
    {
        $translations = DB::table('product_translations')->where('lang', 'en')->get();

        foreach ($translations as $key => $translation) {

            if($translation->description){

                $lang = GoogleTranslateFacade::detectLanguage($translation->description)['language_code'];
            }else{
                $lang = GoogleTranslateFacade::detectLanguage($translation->name)['language_code'];

            }

            if ($lang == 'de') {
                // Product Translations
                $product_translation = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $translation->product_id]);
                $product_translation->name = $translation->name;
                $product_translation->description = $translation->description;
                $product_translation->save();

                $product_translation_en = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $translation->product_id]);
                // $product_translation_en->name = GoogleTranslateFacade::justTranslate($translation->name, 'en');
                $product_translation_en->name = $translation->name;

                if ($translation->description) {
                    $product_translation_en->description = GoogleTranslateFacade::justTranslate($translation->description, 'en');
                }
                $product_translation_en->save();
            } else {
                // Product Translations
                $product_translation = ProductTranslation::firstOrNew(['lang' => 'en', 'product_id' => $translation->product_id]);
                $product_translation->name = $translation->name;
                $product_translation->description = $translation->description;
                $product_translation->save();

                $product_translation_de = ProductTranslation::firstOrNew(['lang' => 'de', 'product_id' => $translation->product_id]);
                $product_translation_de->name = $translation->name;
                if ($translation->description) {
                    $product_translation_de->description = GoogleTranslateFacade::justTranslate($translation->description, 'de');
                }
                $product_translation_de->save();
            }
        }

        flash(translate('Products Translation fixed'))->success();
        Artisan::call('cache:clear');
        return redirect()->back();
    }
}
