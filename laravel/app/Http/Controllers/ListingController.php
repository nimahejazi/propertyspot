<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingStatus;
use App\Models\PropertyType;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use RobotKudos\RKDB\Options;
use Stripe;

class ListingController extends Controller
{
    public function showListing($id = null) {

        $user_id = Auth::user()->id;
        $listing = Listing::where(['id'=>$id, 'user_id'=> $user_id])->first();
        $listing = is_object($listing) ? $listing : Listing::create(['user_id' => $user_id]);
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
        if ($request->has('street') && empty($request->street)) {
            return response()->json(['success' => false, 'message' => 'street is required']);
        }
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
            $custom_amenities = json_decode($request->custom_amenities);
            $amenities = json_decode($request->amenities);
            $this_amenities = [];
            foreach ($amenities as $amenity) {
                $this_amenities[] = [
                    'amenity'   => $amenity,
                    'is_custom' => false
                ];
            }
            foreach ($custom_amenities as $amenity) {
                $this_amenities[] = [
                    'amenity'   => $amenity,
                    'is_custom' => true
                ];
            }
            $listing->amenities()->delete();
            $listing->amenities()->createMany($this_amenities);
        } else {
            $listing->fill($request->all());
            $listing->save();
        }
        return response()->json([
            'success'=> true
        ]);
    }

    public function getFields(Request $request) {
        if (!$request->has(['fields', 'id'])) {
            return response()->json(['success' => false, 'message' => "'fields' and 'id' are required"]);
        }
        $listing = Listing::where(['id' => $request->id, 'user_id' => $request->user()->id])->first();

        if ($listing === null) return response()->json(['success'=> true, 'fields' => []]);

        // Make sure only fillable fields will be sent out
        // why?
//        $white_list = $listing->getFillable();
        $fields = explode(',', $request->fields);
//        $fields = array_intersect($fields, $white_list);
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
        $listing_id = $request->listing_id;

        if (!$lng || !$lat || !$state || !$listing_id) {
            return response()->json(['success' => false, 'message' => '"lng", "lat", "state" and "listing_id" are required']);
        }

        $listing = Listing::where('id', $listing_id)->firstOrFail();
        if ($listing->schools_fetched) {
            $schools = $listing->schools()->get(['name', 'elementary_school', 'middle_school', 'high_school']);
            return response()->json([
                'success'   => true,
                'schools'   => $schools
            ]);
        } else {
            $res = Http::get('https://api.greatschools.org/schools/nearby',[
                'key'   => 'a46e8bc708fdbd23cec422804df8b61c',
                'state' => $state,
                'lat'   => $lat,
                'lon'   => $lng,
                'limit' => 50
            ]);

            if ($res->ok()) {
                $schoolsToBeSaved = [];
                $res = simplexml_load_string($res);

                foreach($res->school as $school) {
                    $schoolGrades = $this->getSchoolGrades((string) $school->gradeRange);
                    $schoolsToBeSaved[] = [
                        'name'          => (string) $school->name,
                        'type'          => (string) $school->type,
                        'grade_range'   => (string) $school->gradeRange,
                        'elementary_school' => $schoolGrades['elementary_school'],
                        'middle_school' => $schoolGrades['middle_school'],
                        'high_school'   => $schoolGrades['high_school'],
                        'enrollment'    => (float) $school->enrollment ?? null,
                        'gs_rating'     => (integer) $school->gsRating ?? null,
                        'parent_rating' => (integer) $school->parentRating ?? null,
                        'city'          => (string) $school->city ?? null,
                        'state'         => (string) $school->state ?? null,
                        'district'      => (string) $school->district ?? null,
                        'address'       => (string) $school->address ?? null,
                        'phone'         => (string) $school->phone ?? null,
                        'website'       => (string) $school->website ?? null,
                        'lat'           => (float) $school->lat ?? null,
                        'lng'           => (float) $school->lon ?? null,
                        'distance'      => (float) $school->distance ?? null
                    ];
                }
                $listing->schools_fetched = true;
                $listing->save();
                return response()->json([
                    'success' => true,
                    'schools' => $schoolsToBeSaved
                ]);
            }

        }

        return response()->json([
            'success' => false,
        ]);
    }

    public function setFeaturedPhoto(Request $request) {
        if (!$request->has(['id', 'listing_id'])) {
            return response()->json(['success' => false, 'message' => "'id' and 'listing_id' are required"]);
        }
        $listing = Listing::where(['id' => $request->listing_id, 'user_id' => $request->user()->id])->first();
        if ($listing === null) return response()->json(['success'=> false, 'message' => 'Listing not found']);
        $listing->featured_photo_id = $request->id;
        $listing->save();
        response()->json(['success' => true]);
    }

    public function showPayment($id) {
        $user = Auth::user();
        $listing = Listing::where([
            'id'        => $id,
            'user_id'   => $user->id
        ])->firstOrFail();
        $options = new Options();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);

        $cards = Stripe\PaymentMethod::all([
            'customer'  => $user->stripe_customer_id,
            'type'      => 'card'
        ]);
        return view('users/payment', [
            'listing' => $listing,
            'price'   => $options->get('listing_price', '19.99'),
            'cards'     => $cards
        ]);
    }

    public function showPaymentNew($id) {
        return view('users/payment-new', ['id' => $id]);
    }

    public function returnPaymentIntent($id) {
        $stripKey = env('STRIPE_SECRET_KEY');
        Stripe\Stripe::setApiKey($stripKey);
        $payment_intent = Stripe\PaymentIntent::create([
            'amount' => 1999,
            'currency' => 'usd',
            'customer' => Auth::user()->stripe_customer_id,
            'metadata' => [
                'listing_id' => $id
            ]
        ]);
        return response()->json([
            'publishable_key' => env('STRIPE_PUBLISHABLE_KEY'),
            'client_secret' => $payment_intent->client_secret
        ]);
    }

    /**
     * @param $grades
     * @return boolean[] {Array} An array with 3 booleans each representing a school grade.
     */
    private function getSchoolGrades($grades) {
        $schoolGrades = [
            'elementary_school' => false,
            'middle_school' => false,
            'high_school' => false,
        ];

        if (!empty($grades)) {
            $grades = explode(', ', $grades);
            foreach ($grades as $grade) {
                $gradeRange = explode('-', $grade);
                foreach($gradeRange as $specificRange) {
                    switch ($specificRange) {
                        case 'PK':
                        case 'K':
                        case '1':
                        case '2':
                        case '3':
                        case '4':
                        case '5':
                        case '6':
                            $schoolGrades['elementary_school'] = true;
                            break;
                        case '7':
                        case '8':
                            $schoolGrades['middle_school'] = true;
                            break;
                        case '9':
                        case '10':
                        case '11':
                        case '12':
                            $schoolGrades['high_school'] = true;
                            break;
                    }
                }
            }
        }

        return $schoolGrades;
    }

}
