<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Filesystem\Filesystem;
use File;

class LanguagesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('languages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($id == 1) {
            $code = 'tur';
            $language = 1;
        } elseif ($id == 2) {
            $code = 'en';
            $language = 2;
        } else {
            return redirect(route('languages.index'));
        }
        $language_files = [];
        $filesInFolder = File::files(base_path() . DIRECTORY_SEPARATOR .
            'resources' . DIRECTORY_SEPARATOR .
            'lang' . DIRECTORY_SEPARATOR .
            $code);
        foreach ($filesInFolder as $path) {
            $file_detail = pathinfo($path);
            $langFile = new Filesystem();
            $langFile = $langFile->get($file_detail['dirname'] . DIRECTORY_SEPARATOR . $file_detail['basename']);
            $langFile = str_replace('<?php', '', $langFile);
            $langArray = eval($langFile);
            $language_files[$file_detail['filename']] = $langArray;

        }
        unset($language_files['validation']);
        unset($language_files['pagination']);
        unset($language_files['passwords']);
        unset($language_files['auth']);
        return view('languages.edit', compact('language', 'language_files'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id == 1) {
            $code = 'tur';
            $language = 1;
        } elseif ($id == 2) {
            $code = 'en';
            $language = 2;
        } else {
            return redirect(route('languages.index'));
        }
        $filesInFolder = File::files(base_path() . DIRECTORY_SEPARATOR .
            'resources' . DIRECTORY_SEPARATOR .
            'lang' . DIRECTORY_SEPARATOR .
            $code);
        $inputs = $request->all();
        foreach ($filesInFolder as $path) {
            $file_detail = pathinfo($path);
            if (isset($inputs[$file_detail['filename']])) {
                $file_name = $inputs[$file_detail['filename']];
                $lastElement = end($file_name);
                $string = '<?php';
                $string = $string . PHP_EOL . 'return [';
                foreach ($file_name as $key => $value) {
                    $string = $string . '"' . $key . '" => "' . $value . '"';
                    if ($lastElement !== $value) {
                        $string = $string . ',' . PHP_EOL;
                    }
                    $language_files[$key] = $value;
                }
                $string = $string . '] ;';
                file_put_contents($file_detail['dirname'] . DIRECTORY_SEPARATOR . $file_detail['basename'], $string);
            }
        }

        flash()->success('Data was saved successfully');
        return redirect()->route('languages.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
