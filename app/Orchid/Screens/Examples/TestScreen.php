<?php

namespace App\Orchid\Screens\Examples;

use App\Wrappers\ContractApiWrapper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layout;
use Orchid\Screen\Screen;

class TestScreen extends Screen
{
    /**
     * Display header name
     *
     * @var string
     */
    public $name = 'Idea Screen';

    /**
     * Display header description
     *
     * @var string
     */
    public $description = 'Idea Screen';

    /**
     * Query data
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'place' => [
                '1' => ['lat' => 1, 'caca' => 9],
            ],
        ];
    }

    /**
     * Button commands
     *
     * @return array
     */
    public function commandBar(): array
    {
        return [
            Button::make(__('Save'))
                ->icon('icon-check')
                ->method('save'),
        ];
    }

    /**
     * Views
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Upload::make('file')
            ]),
        ];
    }

    /**
     * @param Request $request
     *
     */
    public function save(Request $request)
    {
        $api = (new ContractApiWrapper);


        /*
        $file = Attachment::where('id', 4)->firstOrFail();
        $filePath = Storage::disk('public')->path(str_replace('/storage/', '', $file->relativeUrl));
        $content = fopen($filePath, 'r');
        dd($api->save_image($content));
        dd($content);
        */
    }
}
