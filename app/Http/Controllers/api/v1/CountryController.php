<?php


namespace App\Http\Controllers\api\v1;


use App\Codes;
use App\Models\Country;
use App\Wrappers\ContractApiWrapper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;


class CountryController extends Controller
{
    /**
     * Account details
     * @return JsonResponse
     */
    public function countries()
    {
        $countries = Country::all('id', 'name', 'short_code');
        return response()->json([
            'status_code' => Codes::SUCCESS,
            'countries' => $countries,
        ], 200);
    }

    public function test()
    {
        $data = [
            "active" => true,
            "down_gap" => 0,
            "height" => 0,
            "left_gap" => 0,
            "model_2d" => "string",
            "model_3d" => "string",
            "name" => "string",
            "regular" => true,
            "right_gap" => 0,
            "subcategory_id" => 1,
            "up_gap" => 0,
            "width" => 0
        ];
        $spaces = (new ContractApiWrapper)->get_zones();
        return response()->json([
            'message' => $spaces,
        ], 200);
    }
}
