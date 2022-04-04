<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\Space;

use App\Models\Category;
use App\Models\Country;
use App\Models\Subcategory;
use App\Orchid\Layouts\SubcategoryListener;
use Orchid\Attachment\File;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Cropper;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Layouts\Rows;

class SpaceEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     * @throws \Throwable|\Orchid\Screen\Exceptions\TypeException
     *
     */
    public function fields(): array
    {
        return [
            /**
             * Group::make([
             * Picture::make('model_3d')
             * ->title('Subir modelo 3D')
             * ->targetUrl(),
             * Picture::make('model_2d')
             * ->title('Subir modelo 2D')
             * ->targetUrl()
             * ]),
             **/

            Input::make('space.height')
                ->title('Altura:')
                ->placeholder('Ingrese la altura')
                ->required()
                ->type('number')
                ->help('Altura del espacio en cms'),

            Input::make('space.width')
                ->title('Ancho:')
                ->type('number')
                ->placeholder('Ingrese el ancho')
                ->required()
                ->help('Ancho del espacio en cms'),

            CheckBox::make('space.active')
                ->title('Estado del espacio')
                ->sendTrueOrFalse()
                ->placeholder('Activo')
                ->horizontal()
            ,

            CheckBox::make('space.regular')
                ->title('Espacio regular')
                ->disabled()
                ->sendTrueOrFalse()
                ->value(true)
                ->placeholder('Si')
                ->horizontal(),
        ];
    }
}
