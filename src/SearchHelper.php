<?php


namespace Buzzvel\Search;


use GuzzleHttp\Client;
use PHPCoord\CoordinateReferenceSystem\Geographic2D;
use PHPCoord\GeographicPoint;
use PHPCoord\UnitOfMeasure\Angle\Degree;

class SearchHelper
{

    public static  function getHotelsList(){
        $client = new Client();
        $json =  $client->get('https://buzzvel-interviews.s3.eu-west-1.amazonaws.com/hotels.json')->getBody();
        return (json_decode($json, 1))['message'];
    }

    /**
     * @param $hotels
     * @return array
     *
     * Performs an array cleanup of unformatted data
     */
    public static function formatHotelList($hotels) : array{
        $str_starts = function ($hotel) : bool{
            return strpos($hotel[1], ' ') === 0 ;
        };

        $mapArrToHotel = function ($arr) use ($str_starts){
            if (is_null($arr[1])){
                unset($arr[1]);
                return [
                    'name' => $arr[0],
                    'latitude' => $arr[2],
                    'longitude' => $arr[3],
                    'price' => $arr[4]
                ];
            }
            if ($str_starts($arr)){
                $name = $arr[0] . $arr[1];
                unset($arr[1]);
                $latitude = $arr[2];
                $longitude = $arr[3];
                $price = $arr[4];
                return [
                    'name' => $name,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'price' => $price
                ];
            }
            return [
                'name' => $arr[0],
                'latitude' => $arr[1],
                'longitude' => $arr[2],
                'price' => $arr[3]
            ];
        };
        return  array_map(function($hotel) use ($mapArrToHotel){ return $mapArrToHotel($hotel);},$hotels);
    }

    /**
     * @param $hotels
     */
    public static function orderHotelsByProximity($hotels, $latitude, $longitude){
        $hotelsList = self::formatHotelList($hotels);

        $hotelsList = self::hotelsDistance($latitude, $longitude, $hotelsList);
        usort($hotelsList, fn ($first, $second) => (float) $first['distance'] <=> (float)$second['distance']);
        return $hotelsList;
    }

    /**
     * @param $hotels
     *
     * Receive a hotel array sanitized and order by price value
     */
    public static function orderHotelsByPrice($latitude, $longitude, $hotels) : array {
        $hotelList = self::formatHotelList($hotels);
        $hotelList = self::hotelsDistance($latitude, $longitude, $hotelList);
        usort($hotelList, fn ($first, $second) => (int) $first['price'] <=> (int )$second['price']);
        return $hotelList;
    }

    public static function calcDistanceOfPoints($latFrom, $longFrom, $latTo, $longTo){
        $crs = Geographic2D::fromSRID(Geographic2D::EPSG_WGS_84);
        $from = GeographicPoint::create(
            new Degree((float)$latFrom),
            new Degree((float)$longFrom),
            null,
            $crs
        );

        $to = GeographicPoint::create(
            new Degree((float)$latTo),
            new Degree((float)$longTo),
            null,
            $crs
        );
        return number_format($from->calculateDistance($to)->divide(1000)->getValue(), 2, ',', '');
    }

    public static function getFormatedHotelList($hotels){
        foreach ($hotels as $hotel){
            echo " • ". $hotel['name'] .", ".$hotel['distance']."Km, ". $hotel['price']. PHP_EOL;
        }

    }

    /**
     * @param $latitude
     * @param $longitude
     * @param array $hotelsList
     * @return array|array[]
     */
    public static function hotelsDistance($latitude, $longitude, array $hotelsList): array
    {
        $hotelsList = array_map(function ($hotel) use ($latitude, $longitude) {
            $distance = self::calcDistanceOfPoints($latitude, $longitude, $hotel['latitude'], $hotel['longitude']);
            return [
                'name' => $hotel['name'],
                'latitude' => $hotel['latitude'],
                'longitude' => $hotel['longitude'],
                'price' => $hotel['price'],
                'distance' => $distance
            ];
        }, $hotelsList);
        return $hotelsList;
    }

}