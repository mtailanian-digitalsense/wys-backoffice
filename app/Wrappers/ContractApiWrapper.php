<?php


namespace App\Wrappers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;
use Psr\Http\Message\StreamInterface;


class ContractApiWrapper
{

    /**
     * @var mixed
     */
    private $token;
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->token = config('contract.token');
        $this->client = new Client([
            'base_uri' => config("contract.url"),
            'timeout' => 100,
        ]);
    }

    /**
     * @return mixed
     */
    public function get_spaces()
    {
        $spaces = json_decode($this->get("spaces"));
        $categories = $this->get_categories();
        $subcategories = $this->get_subcategories();

        foreach ($spaces as $space) {
            $subcategory = $this->get_subcategory($subcategories, $space->subcategory_id);
            $category = $this->get_category($categories, $subcategory->category_id);
            $space->subcategory_name = $subcategory->name;
            $space->category_name = $category->name;
        }

        return $spaces;
    }

    /**
     * @return mixed
     */
    public function get_categories()
    {
        return json_decode($this->get("spaces/subcategories"));
    }

    /**
     * @return array
     */
    public function get_subcategories(): array
    {
        $subcategories = array();
        $categories = $this->get_categories();
        foreach ($categories as $category) {
            $subcategories = array_merge($subcategories, $category->subcategories);
        }

        return $subcategories;
    }

    /**
     * @param $categories
     * @param $id
     * @return mixed|null
     */
    private function get_category($categories, $id)
    {
        foreach ($categories as $category) {
            if ($category->id == $id) {
                return $category;
            }
        }
        return null;
    }

    /**
     * @param $subcategories
     * @param $id
     * @return mixed|null
     */
    private function get_subcategory($subcategories, $id)
    {
        foreach ($subcategories as $category) {
            if ($category->id == $id) {
                return $category;
            }
        }
        return null;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function save_space($data)
    {
        return $this->post('spaces/create', $data);
    }

    /**
     * @param $spaceId
     * @param $data
     * @return mixed
     */
    public function update_space($spaceId, $data)
    {
        return $this->put('spaces/' . $spaceId, $data);
    }

    /**
     * @param $spaceId
     * @return array
     */
    public function get_images($spaceId): array
    {
        $response = json_decode($this->get('spaces/' . $spaceId));
        return ['model_2d' => $response->model_2d, 'model_3d' => $response->model_3d];
    }

    /**
     * @return mixed
     */
    public function get_zones()
    {
        return json_decode($this->get("buildings/zones"));
    }

    /**
     * @param $data
     * @return mixed
     */
    public function save_zone($data)
    {
        return $this->post('buildings/zones', $data);
    }

    /**
     * @param $zone_id
     * @return mixed
     */
    public function get_buildings_by_zone($zone_id)
    {
        return $this->post('buildings/by_zone', ['zone_id' => $zone_id]);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function save_building($data)
    {
        return $this->post('buildings', $data);
    }

    /**
     * @param $buildingId
     * @return mixed
     */
    public function get_building($buildingId)
    {
        return json_decode($this->get('buildings/' . $buildingId), true);
    }

    /**
     * @param $buildingId
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function update_building($buildingId, $data)
    {
        return $this->put('buildings/' . $buildingId, $data);
    }

    /**
     * @param $zoneId
     * @param $data
     * @return mixed
     * @throws GuzzleException
     */
    public function update_zone($zoneId, $data)
    {
        return $this->put('buildings/zones/' . $zoneId, $data);
    }


    /**
     * @param $file
     * @return mixed
     * @throws GuzzleException
     */
    public function save_buildings_file($file)
    {
        return $this->post_file(fopen($file, 'r'), 'buildings/upload');
    }

    /**
     * @param $file
     * @return mixed
     * @throws GuzzleException
     */
    public function save_designs_cost_file($file)
    {
        return $this->post_file(fopen($file, 'r'), 'prices/design/upload', $file->getClientOriginalName());
    }


    /**
     * @param $file
     * @return mixed
     * @throws GuzzleException
     */
    public function save_cost_file($file)
    {
        return $this->post_file(fopen($file, 'r'), 'prices/upload', $file->getClientOriginalName());
    }

    /**
     * @param $file
     * @return mixed
     * @throws GuzzleException
     */
    public function save_cost_file_2($file)
    {
        return $this->put_file(fopen($file, 'r'), 'prices/comments', $file->getClientOriginalName());
    }


    /**
     * @param $buildingId
     * @param $link
     * @return mixed
     * @throws GuzzleException
     */
    public function save_building_images($buildingId, $link)
    {
        return $this->post('buildings/' . $buildingId . '/images', $link);
    }

    /**
     * @param $buildingId
     * @return mixed
     * @throws GuzzleException
     */
    public function get_building_images($buildingId)
    {
        return json_decode($this->get('buildings/' . $buildingId . '/images'));
    }

    /**
     * @param $buildingId
     * @param $renter
     * @return mixed
     * @throws GuzzleException
     */
    public function save_renter($buildingId, $renter)
    {
        return $this->post('buildings/' . $buildingId . '/renters', $renter);
    }

    /**
     * @param $buildingId
     * @param $renterId
     * @param $renter
     * @return mixed
     * @throws GuzzleException
     */
    public function update_renter($buildingId, $renterId, $renter)
    {
        return $this->put('buildings/' . $buildingId . '/renters/' . $renterId, $renter);
    }

    /**
     * @param $buildingId
     * @param $renterId
     * @return StreamInterface
     * @throws GuzzleException
     */
    public function delete_renter($buildingId, $renterId): StreamInterface
    {
        return $this->delete('buildings/' . $buildingId . '/renters/' . $renterId);
    }

    /**
     * @param $buildingId
     * @param $floor
     * @return mixed
     */
    public function save_floor($buildingId, $floor)
    {
        return $this->post('buildings/' . $buildingId . '/floors', $floor);
    }

    /**
     * @param $buildingId
     * @param $floorId
     * @param $floor
     * @return mixed
     */
    public function update_floor($buildingId, $floorId, $floor)
    {
        return $this->put('buildings/' . $buildingId . '/floors/' . $floorId, $floor);
    }

    /**
     * @param $buildingId
     * @param $floorId
     * @return StreamInterface
     */
    public function delete_floor($buildingId, $floorId): StreamInterface
    {
        return $this->delete('buildings/' . $buildingId . '/floors/' . $floorId);
    }

    /**
     * @param $file
     * @return mixed
     */
    public function save_image($file)
    {
        return $this->post_file($file, 'filestorage/save');
    }

    /**
     * @param $file
     * @param null $fileName
     * @param $endpoint
     * @return mixed
     * @throws GuzzleException
     */
    private function post_file($file, $endpoint, $fileName = null)
    {       
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $response = $this->client->request('POST', $endpoint, [
            'debug' => true,
            'headers' => $headers,
            'multipart' => [
                [
                    'name' => 'file',
                    'filename' => $fileName ?? Str::random(),
                    'contents' => $file,
                ],
            ],
        ])->getBody();
        return json_decode($response);
    }

    private function put_file($file, $endpoint, $fileName = null)
    {       
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
        ];
        $response = $this->client->request('PUT', $endpoint, [
            'debug' => true,
            'headers' => $headers,
            'multipart' => [
                [
                    'name' => 'file',
                    'filename' => $fileName ?? Str::random(),
                    'contents' => $file,
                ],
            ],
        ])->getBody();
        return json_decode($response);
    }


    /**
     * @param $endpoint
     * @return StreamInterface
     * @throws GuzzleException
     */
    private function get($endpoint): StreamInterface
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
        return $this->client->request('GET', $endpoint, [
            'headers' => $headers
        ])->getBody();
    }

    /**
     * @param $endpoint
     * @param $dataArray
     * @return mixed
     * @throws GuzzleException
     */
    private function post($endpoint, $dataArray)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $response = $this->client->request('POST', $endpoint, [
            'body' => json_encode($dataArray),
            'headers' => $headers
        ])->getBody();

        return json_decode($response);
    }

    /**
     * @param $endpoint
     * @param $dataArray
     * @return mixed
     * @throws GuzzleException
     */
    private function put($endpoint, $dataArray)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];
        $response = $this->client->request('PUT', $endpoint, [
            'body' => json_encode($dataArray),
            'headers' => $headers
        ])->getBody();

        return json_decode($response);
    }

    /**
     * @param $endpoint
     * @return StreamInterface
     * @throws GuzzleException
     */
    private function delete($endpoint): StreamInterface
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json',
        ];
        return $this->client->request('DELETE', $endpoint, [
            'headers' => $headers
        ])->getBody();
    }


}
