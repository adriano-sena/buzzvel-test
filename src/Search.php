<?php


namespace Buzzvel\Search;

use Buzzvel\Search\SearchHelper;



class Search
{


    /**
     *
     * Function that returns a json of a list of hotels order by the parameter
     * Selected by the user
     *
     * @param $latitude
     * @param $longitude
     * @param $orderby
     *
     *
     */
    public static function getNearbyHotels ($latitude, $longitude, $orderby = "proximity")
    {
        $hotels = SearchHelper::getHotelsList();
        if ($orderby == "pricepernight") {
            $hotelsList = SearchHelper::orderHotelsByPrice($latitude,$longitude, $hotels);
            SearchHelper::getFormatedHotelList($hotelsList);

        }else{
            $hotelsList = SearchHelper::orderHotelsByProximity($hotels, $latitude, $longitude);
            SearchHelper::getFormatedHotelList($hotelsList);
        }

    }
}