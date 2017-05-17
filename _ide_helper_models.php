<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Booking
 *
 * @property int $id
 * @property string $user_id
 * @property int $offer_id
 * @property int $status
 * @property string $driver_remarks
 * @property int $rating
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Offer $offers
 * @property-read \App\Review $review
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereDriverRemarks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereOfferId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereRating($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Booking whereUserId($value)
 */
	class Booking extends \Eloquent {}
}

namespace App{
/**
 * App\Device
 *
 * @property-read \App\User $users
 */
	class Device extends \Eloquent {}
}

namespace App{
/**
 * App\Offer
 *
 * @property int $id
 * @property string $user_id
 * @property string $meetup_time
 * @property string $start_name
 * @property string $start_addr
 * @property float $start_lat
 * @property float $start_lng
 * @property string $end_name
 * @property string $end_addr
 * @property float $end_lat
 * @property float $end_lng
 * @property int $vacancy
 * @property string $remarks
 * @property int $status
 * @property string $pref_gender
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Booking[] $bookings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @property-read \App\User $users
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereEndAddr($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereEndLat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereEndLng($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereEndName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereMeetupTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer wherePrefGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereRemarks($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereStartAddr($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereStartLat($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereStartLng($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereStartName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Offer whereVacancy($value)
 */
	class Offer extends \Eloquent {}
}

namespace App{
/**
 * App\Review
 *
 * @property-read \App\Booking $bookings
 * @property-read \App\Offer $offers
 * @property-read \App\User $users
 */
	class Review extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $dp
 * @property string $facebook_id
 * @property string $google_id
 * @property string $gender
 * @property int $exp
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Booking[] $bookings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Device[] $devices
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Offer[] $offers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Review[] $reviews
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereExp($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereFacebookId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereGoogleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

