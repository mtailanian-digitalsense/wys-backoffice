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

class SpaceCreateLayout extends Rows
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

        ];
    }
}
