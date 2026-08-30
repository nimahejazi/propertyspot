<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingStatus;
use App\Models\PropertyPhoto;
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

        return view('users/listing-form-new', [
          'listing_id' => $id,
          'api_token' => Auth::user()->api_token,
        ]);
    }

    // To work with new React listing edit form
    public function saveListingDetails(Request $request) {
      if ($request->has('id')) {
        $listing = Listing::find($request->id);
        if ($listing === null || $listing->user_id !== $request->user()->id) {
            return response()->json(['success'=>false, 'message'=> 'Unauthorized']);
        }
        // from videos page
        if ($request->has('listing_videos')) {
            $videos = $request->listing_videos;
            $property_videos = [];
            foreach ($videos as $video) {
                $property_videos[] = [
                    'provider' => $video['provider'],
                    'video_id' => $video['videoId']
                ];
            }
            $listing->videos()->delete();
            $listing->videos()->createMany($property_videos);
        }
        if ($request->has('custom_amenities')) {
            $custom_amenities = $request->custom_amenities;
            $amenities = $request->amenities;
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
        }
        $listing->fill($request->only(Listing::EDITABLE_FIELDS));
        $listing->save();
        return response()->json([
            'success'=> true
        ]);

      } else {
        $request->validate([
            'street' => 'required',
        ]);
        $listing = new Listing([
          'user_id' =>  $request->user()->id,
          'street' => $request->street,
          'add_line2' => $request->add_line2,
          'county' => $request->county,
          'city' => $request->city,
          'state' => $request->state,
          'zip' => $request->zip,
          'lat' => $request->lat,
          'lng' => $request->lng,
        ]);
        $listing->save();
        return response()->json(['success' => true, 'id' => $listing->id]);
      }
    }

    // to work with old vanilla js app for listing edit
    public function saveListingParts(Request $request) {
        // we know it's new one and data is about address
        if ($request->has('street') && empty($request->street)) {
            return response()->json(['success' => false, 'message' => 'street is required']);
        }
        $listing = Listing::find($request->id);
        if ($listing === null || $listing->user_id !== $request->user()->id) {
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
            $listing->fill($request->only(Listing::EDITABLE_FIELDS));
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

        $fields = explode(',', $request->fields);
        $fields = array_intersect($fields, Listing::EDITABLE_FIELDS);
        $res_fields = [];
        foreach($fields as $field) {
            $res_fields[$field] = $listing[$field];
        }

        if (in_array('listing_videos', $fields)) {
          $videos = $listing->videos()->get();
          foreach($videos as $video) {
            $res_fields['listing_videos'][] = [
              'videoId' => $video->video_id,
              'provider' => $video->provider,
              ];
          }
        }
        if (in_array('amenities', $fields)) {
            $amenities = $listing->amenities()->get();
            foreach($amenities as $amenity) {
                if ($amenity->is_custom) {
                    $res_fields['custom_amenities'][] = $amenity->amenity;
                } else {
                    $res_fields['amenities'][] = $amenity->amenity;
                }
            }
        }
        return response()->json($res_fields);
    }

    public function getNearbySchools(Request $request) {
        $lng = $request->lng;
        $lat = $request->lat;
        $state = $request->state;

        if (!$lng || !$lat || !$state) {
            return response()->json(['success' => false, 'message' => '"lng", "lat" and "state" are required']);
        }

        /* $listing = Listing::where('id', $listing_id)->firstOrFail(); */
        /* if ($listing->schools_fetched) { */
        /*     $schools = $listing->schools()->get(['name', 'elementary_school', 'middle_school', 'high_school']); */
        /*     return response()->json([ */
        /*         'success'   => true, */
        /*         'schools'   => $schools */
        /*     ]); */
        /* } else { */
            $res = Http::get('https://api.greatschools.org/schools/nearby',[
                'key'   => config('services.greatschools.key'),
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
                        'enrollment'    => $school->enrollment != '' ? (float) $school->enrollment : null,
                        'gs_rating'     => $school->gsRating != '' ? (int) $school->gsRating : null,
                        'parent_rating' => $school->parentRating != '' ? (int) $school->parentRating : null,
                        'city'          => (string) $school->city,
                        'state'         => (string) $school->state,
                        'district'      => (string) $school->district,
                        'address'       => (string) $school->address,
                        'phone'         => (string) $school->phone,
                        'website'       => (string) $school->website,
                        'lat'           => $school->lat != '' ? (float) $school->lat : null,
                        'lng'           => $school->lon != '' ? (float) $school->lon : null,
                        'distance'      => $school->distance != '' ? (float) $school->distance : null
                    ];
                }
                //$listing->schools_fetched = true;
                //$listing->save();
                return response()->json([
                    'success' => true,
                    'schools' => $schoolsToBeSaved
                ]);
            }

        /* } */

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
        $photo = PropertyPhoto::where('id', $request->id)->where('key', $listing->id)->first();
        if ($photo === null) return response()->json(['success'=> false, 'message' => 'Photo not found']);
        $listing->featured_photo_id = $photo->id;
        $listing->save();
        return response()->json(['success' => true]);
    }

    public function showPayment($id) {
        $user = Auth::user();
        $listing = Listing::where([
            'id'        => $id,
            'user_id'   => $user->id
        ])->firstOrFail();
        $options = new Options();
        Stripe\Stripe::setApiKey(config('services.stripe.secret'));
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

    public function showListingSettings($id) {
        return $this->showListing($id);
    }

    public function setPaymentProcessing($id) {
        $listing = Listing::where([
            'id'      => $id,
            'user_id' => Auth::user()->id
        ])->first();
        if ($listing === null) {
            return response()->json(['success' => false, 'message' => 'Listing not found']);
        }
        $listing->payment_status = 'processing';
        $listing->save();
        return response()->json([
            'success' => true,
        ]);
    }
    public function returnPaymentIntent($id) {
        $listing = Listing::where([
            'id'      => $id,
            'user_id' => Auth::user()->id
        ])->first();
        if ($listing === null) {
            return response()->json(['success' => false, 'message' => 'Listing not found']);
        }
        Stripe\Stripe::setApiKey(config('services.stripe.secret'));
        $payment_intent = Stripe\PaymentIntent::create([
            'amount' => 1999,
            'currency' => 'usd',
            'customer' => Auth::user()->stripe_customer_id,
            'metadata' => [
                'listing_id' => $listing->id
            ]
        ]);
        return response()->json([
            'publishable_key' => config('services.stripe.key'),
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

    function checkSlug(Request $request) {
        $request->validate(['slug' => 'required|alpha_dash|max:255']);
        $excludedId = $request->listing_id ?? null;
        $query = Listing::where('slug', $request->slug);
        if ($excludedId) {
            $query->where('id', '!=', $excludedId);
        }
        return response()->json([
            'available' => $query->count() < 1
        ]);
    }
    function generateSlug(Request $request) {
        $listing = Listing::where([
            'id'      => $request->listing_id,
            'user_id' => $request->user()->id
        ])->first();
        if ($listing === null) {
            return response()->json(['success' => false, 'message' => 'Listing not found']);
        }
        return response()->json([
            'slug' => $listing->createSlug()
        ]);
    }

    public function getPhotos($key) {
        $listing = Listing::where(['id' => $key, 'user_id' => Auth::user()->id])->first();
        if ($listing === null) {
            return response()->json(['success' => false, 'message' => 'Listing not found']);
        }
        return response()->json(
            PropertyPhoto::where('key', $listing->id)
                ->orderBy('position')
                ->get(['id', 'name', 'image_url', 'image_2x_url', 'thumb_url', 'thumb_2x_url', 'position'])
        );
    }
}
