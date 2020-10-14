<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingStatus;
use App\Models\PropertyType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ListingController extends Controller
{
    public function showListing($id = null) {

        $listing = Listing::where(['id'=>$id, 'user_id'=>Auth::user()->id])->first();
        $listing = is_object($listing) ? $listing : new Listing();
        $videos = $listing->videos()->get();
        $amenities = $listing->amenities()->where('is_custom', false);
        $custom_amenities = $listing->amenities()->where('is_custom', true)->get(['amenity']);
        $property_videos = [];
        $property_custom_amenities = [];
        foreach($custom_amenities as $custom_amenity) {
            $property_custom_amenities[] = $custom_amenity['amenity'];
        }
        foreach($videos as $video) {
            $property_videos[] = ['provider' => $video->provider, 'videoId' => $video->video_id];
        }
        return view('users/listing-form', [
            'propertyTypes' => PropertyType::all(),
            'listingStatus' => ListingStatus::all(),
            'listing' => $listing,
            'videos' => json_encode($property_videos),
            'custom_amenities' => json_encode($property_custom_amenities),
            'states' => [
                'AL',
                'AK',
                'AZ',
                'AR',
                'CA',
                'CO',
                'CT',
                'DE',
                'FL',
                'GA',
                'HI',
                'ID',
                'IL',
                'IN',
                'IA',
                'KS',
                'KY',
                'LA',
                'ME',
                'MD',
                'MA',
                'MI',
                'MN',
                'MS',
                'MO',
                'MT',
                'NE',
                'NV',
                'NH',
                'NJ',
                'NM',
                'NY',
                'NC',
                'ND',
                'OH',
                'OK',
                'OR',
                'PA',
                'RI',
                'SC',
                'SD',
                'TN',
                'TX',
                'UT',
                'VT',
                'VA',
                'WA',
                'WV',
                'WI',
                'WY',
            ],
            'property_amenities'    => [
                'Internal Amenities'    => [
                    'Alarm System',
                    'Basement - Finished',
                    'Basement - Unfinished',
                    'Bonus Room',
                    'Broadband Available',
                    'Concierge Service',
                    'Elevator',
                    'Fireplace(s)',
                    'Gym (internal)',
                    'Hot Tub/Spa (internal)',
                    'Humidifier',
                    'Pool (internal)',
                    'Office/Den',
                    'Satellite Dish(es)',
                    'Sauna (internal)',
                    'Skylights',
                    'Surround Sound',
                    'Vaulted Ceilings',
                    'Water Softener',
                    'Wet Bar',
                    'Wine Storage',
                ],
                'External Amenities'=> [
                    'Barn/Stable – Detached',
                    'Carport',
                    'Garage-Attached',
                    'Garage-Unattached',
                    'Hot Tub/Spa (external)',
                    'Outbuilding',
                    'Pool (external)',
                    'Parking/Garage Included',
                    'Roof Top Deck',
                    'Sauna (external)',
                    'Spa',
                    'Sports Court',
                    'Swimming Pool',
                    'Tennis Court',
                    'Workshop – Detached',
                ],
                'Property Amenities' => [

                    'Boat Facilities',
                    'Club House',
                    'Community Beach',
                    'Corner Lot',
                    'Country Club',
                    'Cul-de-sac Location',
                    'Dock',
                    'Fenced Yard',
                    'Float',
                    'Fully Fenced',
                    'Gated Community',
                    'Garden Area',
                    'Golf Course Lot',
                    'Gym (building)',
                    'High-Rise',
                    'Landscaped',
                    'Low-Rise',
                    'Partially Fenced',
                    'Patio',
                    'Pool (building)',
                    'Sprinkler System',
                ],
                'Appliances' => [
                    'Convection Oven',
                    'Dishwasher',
                    'Dryer',
                    'Freezer',
                    'Garbage Disposal',
                    'Indoor Grill',
                    'Microwave',
                    'Oven Range',
                    'Refrigerator',
                    'Stainless Steel',
                    'Stove',
                    'Trash Compactor',
                    'Washer',
                ],
                'Cooling/Heating' => [
                    'Central Air',
                    'Central Electric',
                    'Electric Baseboard',
                    'Forced Air',
                    'Fuel Oil',
                    'Gravity Air',
                    'Heat Pump',
                    'Hot Water / Steam',
                    'Multi Zone A/C',
                    'Natural Gas',
                    'Propane',
                    'Radiant',
                    'Solar',
                    'Swamp Cooler',
                    'Wall Furnace',
                    'Window/Wall Unit',
                    'Wood',
                ],
                'Other Amenities'   => [
                    'Air Purifier',
                    'Balcony',
                    'Cable Ready',
                    'Gas Line Hook-up for BBQ',
                    'Intercom',
                    'Lobby',
                    'Pets Allowed',
                    'Storm Shutters (manual)',
                    'Valet Parking',
                ]
            ]
        ]);
    }

    public function saveListingParts(Request $request) {
        // we know it's new one and data is about address
        if (!$request->id) {
            if (!$request->street) {
                return response()->json(['success' => false, 'message' => 'street is required']);
            }
            try {
//                $listing = new Listing($request->all());
                $newListing = $request->user()->listings()->create($request->all());
            } catch (\Exception $e) {
                $exception = config('app.debug') ? $e->getMessage() : 'Error in saving the listing';
                return response()->json(['success' => false, 'message' => $exception]);
            }
            return response()->json(['success' => true, 'id' => $newListing->id]);
        } else {
            $listing = Listing::find($request->id);
            if ($listing->user_id !== $request->user()->id) {
                return response()->json(['success'=>false, 'message'=> 'Unauthorized']);
            }

            // from videos page
            if ($request->has('listing_videos')) {
                $videos = json_decode($request->listing_videos);
                $property_videos = [];
                foreach ($videos as $video) {
                    $property_videos[] = [
                        'provider' => $video->provider,
                        'video_id' => $video->videoId
                    ];
                }
                $listing->videos()->delete();
                $listing->videos()->createMany($property_videos);
            } else if ($request->has('custom_amenities')) {
                $amenities = json_decode($request->custom_amenities);
                $custom_amenities = [];
                foreach ($amenities as $amenity) {
                    $custom_amenities[] = [
                        'amenity'   => $amenity,
                        'is_custom' => true
                    ];
                }
                $listing->amenities()->delete();
                $listing->amenities()->createMany($custom_amenities);
            } else {
                $listing->fill($request->all());
                $listing->save();
            }
            return response()->json([
                'success'=> true
            ]);
        }
    }

    public function getFields(Request $request) {
        if (!$request->has(['fields', 'id'])) {
            return response()->json(['success' => false, 'message' => "'fields' and 'id' are required"]);
        }
        $listing = Listing::where(['id' => $request->id, 'user_id' => $request->user()->id])->first();

        if ($listing === null) return response()->json(['success'=> true, 'fields' => []]);

        // Make sure only fillable fields will be sent out
        $white_list = $listing->getFillable();
        $fields = explode(',', $request->fields);
        $fields = array_intersect($fields, $white_list);
        $res_fields = [];
        foreach($fields as $field) {
            $res_fields[$field] = $listing[$field];
        }
        return response()->json($res_fields);
    }

    public function getNearbySchools(Request $request) {
        $lng = $request->lng;
        $lat = $request->lat;
        $state = $request->state;

        if (!$lng || !$lat || !$state) {
            return response()->json(['success' => false, 'message' => 'lng, lat and state are required']);
        }
//        $resElementary = Http::get('https://api.greatschools.org/schools/nearby',[
//            'key'   => 'a46e8bc708fdbd23cec422804df8b61c',
//            'state' => $state,
//            'lat'   => $lat,
//            'lon'   => $lng,
//            'levelCode' => 'elementary-schools',
//            'limit' => 10
//        ]);
//        $resMiddle = Http::get('https://api.greatschools.org/schools/nearby',[
//            'key'   => 'a46e8bc708fdbd23cec422804df8b61c',
//            'state' => $state,
//            'lat'   => $lat,
//            'lon'   => $lng,
//            'levelCode' => 'middle-schools',
//            'limit' => 10
//        ]);
//        $resHigh = Http::get('https://api.greatschools.org/schools/nearby',[
//            'key'   => 'a46e8bc708fdbd23cec422804df8b61c',
//            'state' => $state,
//            'lat'   => $lat,
//            'lon'   => $lng,
//            'levelCode' => 'high-schools',
//            'limit' => 10
//        ]);

        if ($resElementary->ok() && $resMiddle->ok() && $resHigh->ok()) {
            $resElementary = simplexml_load_string($resElementary);
            $resMiddle = simplexml_load_string($resMiddle);
            $resHigh = simplexml_load_string($resHigh);


//            return $resElementary;
            return response()->json([
                'elementarySchool' => $resElementary,
                'middleSchool' => $resMiddle,
                'highSchool' => $resHigh,
            ]);
        }
    }
    //
}
