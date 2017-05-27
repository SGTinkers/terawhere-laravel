---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->

# Info

Welcome to the generated API reference.
[Get Postman Collection](http://terawhere.ruqqq.sg/docs/collection.json)

<!-- END_INFO -->

#Authentication

The process flow is as such:

1. Mobile App login with Facebook or Google**
2. Mobile App get the token (from Fb/G)
3. Send token to server via `/api/v1/auth` endpoint
4. Then server checks if we already have the user in local db:

 a. If already in, return an auth token

 b. Else, create user, then return an auth token

The auth token is actually JWT token. Basically, to call an authorised endpoint, include the JWT token in the request header: `Authorization: Bearer [JWTTokenHere]`. The request will pass through if the token is valid. The user can also be identified with the token.

The token does expire. If server returns `token_expired`, call `/api/v1/auth/refresh` to get a new token. The token is returned in `Authorization` header.

To get the current logged in user based on the token, call `/api/v1/me`.

** Fb uses same client_id/secret, Google might be different. For example, for Android: https://developers.google.com/identity/sign-in/android/start-integrating
<!-- START_28e685420b0e7112e74031353ec2f4bd -->
## Get Authenticated User

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Retrieves the user associated with the JWT token.

> Example request:

```bash
curl -X GET "http://localhost/api/v1/me" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/me",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/me`

`HEAD api/v1/me`


<!-- END_28e685420b0e7112e74031353ec2f4bd -->

<!-- START_af82433e555a57f31d78233071a5a020 -->
## Authenticate User

Exchanges social network token to JWT bearer token.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/auth" \
-H "Accept: application/json" \
    -d "service"="facebook" \
    -d "token"="in" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/auth",
    "method": "POST",
    "data": {
        "service": "facebook",
        "token": "in"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/auth`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    service | string |  required  | `facebook` or `google`
    token | string |  required  | 

<!-- END_af82433e555a57f31d78233071a5a020 -->

<!-- START_29ad049b182baa84aefd2c96650b36c5 -->
## Refresh Token

* **Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*
Check Authorization header for new token.
Call this API to exchange expired (not invalid!) JWT token with a fresh one.

> Example request:

```bash
curl -X GET "http://localhost/api/v1/auth/refresh" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/auth/refresh",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/auth/refresh`

`HEAD api/v1/auth/refresh`


<!-- END_29ad049b182baa84aefd2c96650b36c5 -->

#Booking

All bookings by passengers are handled here.
<!-- START_116b6b489364b396e385465393ad728e -->
## Get bookings belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all offers belonging to user($id)

> Example request:

```bash
curl -X GET "http://localhost/api/v1/users/me/bookings" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users/me/bookings",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/users/me/bookings`

`HEAD api/v1/users/me/bookings`


<!-- END_116b6b489364b396e385465393ad728e -->

<!-- START_82034b4af81070943c843b25e75bd8b5 -->
## Get all bookings belonging to an offer

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all bookings made to an offer or 404

> Example request:

```bash
curl -X GET "http://localhost/api/v1/offers/{offer_id}/bookings" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers/{offer_id}/bookings",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/offers/{offer_id}/bookings`

`HEAD api/v1/offers/{offer_id}/bookings`


<!-- END_82034b4af81070943c843b25e75bd8b5 -->

<!-- START_7af9cd11c6f570507128bd47a1d55065 -->
## Get all bookings

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all bookings in database

> Example request:

```bash
curl -X GET "http://localhost/api/v1/bookings" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/bookings",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/bookings`

`HEAD api/v1/bookings`


<!-- END_7af9cd11c6f570507128bd47a1d55065 -->

<!-- START_41753c028e1df09b77faeaf7ff5e25a8 -->
## Create a booking

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Do not use pax, pax defaults to 1. For future use ONLY.

Returns Success or error message.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/bookings" \
-H "Accept: application/json" \
    -d "offer_id"="3331" \
    -d "pax"="3331" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/bookings",
    "method": "POST",
    "data": {
        "offer_id": 3331,
        "pax": 3331
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/bookings`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    offer_id | integer |  required  | 
    pax | integer |  optional  | 

<!-- END_41753c028e1df09b77faeaf7ff5e25a8 -->

<!-- START_4ca735a4e89ef20a2ee31f04d7ee721d -->
## Show a particular booking

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns a single booking

> Example request:

```bash
curl -X GET "http://localhost/api/v1/bookings/{booking}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/bookings/{booking}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/bookings/{booking}`

`HEAD api/v1/bookings/{booking}`


<!-- END_4ca735a4e89ef20a2ee31f04d7ee721d -->

<!-- START_039cecacf2fd8264a352659da305ac9a -->
## Cancel a booking

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns success message or 404.

> Example request:

```bash
curl -X DELETE "http://localhost/api/v1/bookings/{booking}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/bookings/{booking}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/bookings/{booking}`


<!-- END_039cecacf2fd8264a352659da305ac9a -->

#Notification

Push notifications stuff with FCM is handled here.
<!-- START_7a1aac2c6fcc438f99fca121eaf1482f -->
## Store device token

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

> Example request:

```bash
curl -X POST "http://localhost/api/v1/devices" \
-H "Accept: application/json" \
    -d "device_token"="possimus" \
    -d "platform"="web" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/devices",
    "method": "POST",
    "data": {
        "device_token": "possimus",
        "platform": "web"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/devices`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    device_token | string |  required  | 
    platform | string |  required  | `ios`, `android` or `web`

<!-- END_7a1aac2c6fcc438f99fca121eaf1482f -->

<!-- START_4aea3d79dac837eacb615d2d562d2d37 -->
## Delete device token

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

> Example request:

```bash
curl -X DELETE "http://localhost/api/v1/devices/{device}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/devices/{device}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/devices/{device}`


<!-- END_4aea3d79dac837eacb615d2d562d2d37 -->

<!-- START_7f44c7fef448e5bed06a4e0c74f7001c -->
## Get devices belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

> Example request:

```bash
curl -X GET "http://localhost/api/v1/users/me/devices" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users/me/devices",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/users/me/devices`

`HEAD api/v1/users/me/devices`


<!-- END_7f44c7fef448e5bed06a4e0c74f7001c -->

<!-- START_80593600c7c815da251a14f276baa979 -->
## Send test notification

> Example request:

```bash
curl -X POST "http://localhost/api/v1/test-notification/{user_id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/test-notification/{user_id}",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/test-notification/{user_id}`


<!-- END_80593600c7c815da251a14f276baa979 -->

#Offer

All offers by drivers are handled here.
<!-- START_4c83e6d62d6e132846ce0b74ca077846 -->
## Get offers belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all offers belonging to user($id)

> Example request:

```bash
curl -X GET "http://localhost/api/v1/offers-for-user" \
-H "Accept: application/json" \
    -d "user_id"="velit" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers-for-user",
    "method": "GET",
    "data": {
        "user_id": "velit"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/offers-for-user`

`HEAD api/v1/offers-for-user`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_4c83e6d62d6e132846ce0b74ca077846 -->

<!-- START_1233e19349dd4db04ed6eb6513e9dd9d -->
## Get offers from Date

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

If no date given, today's date is used.

Returns all offers on a requested date

> Example request:

```bash
curl -X GET "http://localhost/api/v1/offers-for-date" \
-H "Accept: application/json" \
    -d "date"="2017-05-27" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers-for-date",
    "method": "GET",
    "data": {
        "date": "2017-05-27"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/offers-for-date`

`HEAD api/v1/offers-for-date`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    date | date |  optional  | Date format: `Y-m-d`

<!-- END_1233e19349dd4db04ed6eb6513e9dd9d -->

<!-- START_b623e48ab2902753b3c8dd52e4608c16 -->
## Get offers belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all offers belonging to user($id)

> Example request:

```bash
curl -X GET "http://localhost/api/v1/users/me/offers" \
-H "Accept: application/json" \
    -d "user_id"="qui" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users/me/offers",
    "method": "GET",
    "data": {
        "user_id": "qui"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/users/me/offers`

`HEAD api/v1/users/me/offers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_b623e48ab2902753b3c8dd52e4608c16 -->

<!-- START_19a81fab66403a93c1f7cdf329c755f7 -->
## Get nearby offers

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Range accepts 1-12 (Precision of geohash, 1 = ~5000km, 12 = 3.7 cm. Defaults to 4)

Returns all nearby offers in the next 24 hours.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/nearby-offers" \
-H "Accept: application/json" \
    -d "lat"="15" \
    -d "lng"="31" \
    -d "range"="8" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/nearby-offers",
    "method": "POST",
    "data": {
        "lat": 15,
        "lng": 31,
        "range": 8
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/nearby-offers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    lat | numeric |  required  | Between: `-90` and `90`
    lng | numeric |  required  | Between: `-180` and `180`
    range | numeric |  optional  | Between: `1` and `12`

<!-- END_19a81fab66403a93c1f7cdf329c755f7 -->

<!-- START_cc5386f8f83206a976c3413c416133f1 -->
## Set an offer status to ongoing

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.

Returns Success.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/offers/{offer_id}/ongoing" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers/{offer_id}/ongoing",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/offers/{offer_id}/ongoing`


<!-- END_cc5386f8f83206a976c3413c416133f1 -->

<!-- START_dc341f61ea102a1f3c570faf364a6ec1 -->
## Set an offer status to completed

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.

Returns Success.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/offers/{offer_id}/completed" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers/{offer_id}/completed",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/offers/{offer_id}/completed`


<!-- END_dc341f61ea102a1f3c570faf364a6ec1 -->

<!-- START_c233fc34839427dff7ef9ad7c3821ae3 -->
## Get all offers

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Not recommended for use

Returns ALL offers in database

> Example request:

```bash
curl -X GET "http://localhost/api/v1/offers" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/offers`

`HEAD api/v1/offers`


<!-- END_c233fc34839427dff7ef9ad7c3821ae3 -->

<!-- START_71db7b33ed071496bd705bd76a713caa -->
## Store an offer

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns Validation errors OR success message w/ data posted

> Example request:

```bash
curl -X POST "http://localhost/api/v1/offers" \
-H "Accept: application/json" \
    -d "meetup_time"="2017-05-27 15:44:23" \
    -d "start_name"="est" \
    -d "start_addr"="est" \
    -d "start_lat"="40" \
    -d "start_lng"="81" \
    -d "end_name"="est" \
    -d "end_addr"="est" \
    -d "end_lat"="40" \
    -d "end_lng"="81" \
    -d "vacancy"="8099169" \
    -d "remarks"="est" \
    -d "pref_gender"="female" \
    -d "vehicle_number"="est" \
    -d "vehicle_desc"="est" \
    -d "vehicle_model"="est" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers",
    "method": "POST",
    "data": {
        "meetup_time": "2017-05-27 15:44:23",
        "start_name": "est",
        "start_addr": "est",
        "start_lat": 40,
        "start_lng": 81,
        "end_name": "est",
        "end_addr": "est",
        "end_lat": 40,
        "end_lng": 81,
        "vacancy": 8099169,
        "remarks": "est",
        "pref_gender": "female",
        "vehicle_number": "est",
        "vehicle_desc": "est",
        "vehicle_model": "est"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/offers`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    meetup_time | date |  required  | Date format: `Y-m-d H:i:s`
    start_name | string |  required  | 
    start_addr | string |  required  | 
    start_lat | numeric |  required  | Between: `-90` and `90`
    start_lng | numeric |  required  | Between: `-180` and `180`
    end_name | string |  required  | 
    end_addr | string |  required  | 
    end_lat | numeric |  required  | Between: `-90` and `90`
    end_lng | numeric |  required  | Between: `-180` and `180`
    vacancy | integer |  required  | 
    remarks | string |  optional  | 
    pref_gender | string |  optional  | `male` or `female`
    vehicle_number | string |  required  | Only alpha-numeric characters allowed
    vehicle_desc | string |  optional  | 
    vehicle_model | string |  required  | 

<!-- END_71db7b33ed071496bd705bd76a713caa -->

<!-- START_b080d3aee6b5aa058eefcd0265db2c6f -->
## Show a single offer

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns a single offer from offer_id

> Example request:

```bash
curl -X GET "http://localhost/api/v1/offers/{offer}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers/{offer}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/offers/{offer}`

`HEAD api/v1/offers/{offer}`


<!-- END_b080d3aee6b5aa058eefcd0265db2c6f -->

<!-- START_fd575fa3ec9e0082d61bbb9fd4b19a7d -->
## Update an offer

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns success message or 404.

> Example request:

```bash
curl -X PUT "http://localhost/api/v1/offers/{offer}" \
-H "Accept: application/json" \
    -d "meetup_time"="2017-05-27 15:44:23" \
    -d "start_name"="ratione" \
    -d "start_addr"="ratione" \
    -d "start_lat"="-64" \
    -d "start_lng"="-127" \
    -d "end_name"="ratione" \
    -d "end_addr"="ratione" \
    -d "end_lat"="-64" \
    -d "end_lng"="-127" \
    -d "vacancy"="97" \
    -d "remarks"="ratione" \
    -d "pref_gender"="male" \
    -d "vehicle_number"="ratione" \
    -d "vehicle_desc"="ratione" \
    -d "vehicle_model"="ratione" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers/{offer}",
    "method": "PUT",
    "data": {
        "meetup_time": "2017-05-27 15:44:23",
        "start_name": "ratione",
        "start_addr": "ratione",
        "start_lat": -64,
        "start_lng": -127,
        "end_name": "ratione",
        "end_addr": "ratione",
        "end_lat": -64,
        "end_lng": -127,
        "vacancy": 97,
        "remarks": "ratione",
        "pref_gender": "male",
        "vehicle_number": "ratione",
        "vehicle_desc": "ratione",
        "vehicle_model": "ratione"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`PUT api/v1/offers/{offer}`

`PATCH api/v1/offers/{offer}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    meetup_time | date |  required  | Date format: `Y-m-d H:i:s`
    start_name | string |  required  | 
    start_addr | string |  required  | 
    start_lat | numeric |  required  | Between: `-90` and `90`
    start_lng | numeric |  required  | Between: `-180` and `180`
    end_name | string |  required  | 
    end_addr | string |  required  | 
    end_lat | numeric |  required  | Between: `-90` and `90`
    end_lng | numeric |  required  | Between: `-180` and `180`
    vacancy | integer |  required  | 
    remarks | string |  optional  | 
    pref_gender | string |  optional  | `male` or `female`
    vehicle_number | string |  required  | Only alpha-numeric characters allowed
    vehicle_desc | string |  optional  | 
    vehicle_model | string |  required  | 

<!-- END_fd575fa3ec9e0082d61bbb9fd4b19a7d -->

<!-- START_ed9803f1e1dd211d60541a24ba18c0f9 -->
## Cancel an offer

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.

Returns Success or 404.

> Example request:

```bash
curl -X DELETE "http://localhost/api/v1/offers/{offer}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers/{offer}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/offers/{offer}`


<!-- END_ed9803f1e1dd211d60541a24ba18c0f9 -->

#Report

All reports are handled here
<!-- START_1f87a302741aa8883bace4e09185565a -->
## Set a report to Read

Requires admin role

> Example request:

```bash
curl -X POST "http://localhost/api/v1/reports/{report_id}/set-read" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reports/{report_id}/set-read",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/reports/{report_id}/set-read`


<!-- END_1f87a302741aa8883bace4e09185565a -->

<!-- START_9199cf235ed81dfbea9ed7e1f2f326a1 -->
## Set a report to Replied

Requires admin role

> Example request:

```bash
curl -X POST "http://localhost/api/v1/reports/{report_id}/set-replied" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reports/{report_id}/set-replied",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/reports/{report_id}/set-replied`


<!-- END_9199cf235ed81dfbea9ed7e1f2f326a1 -->

<!-- START_816db9c747aee1929d1aa8d679ab152a -->
## Display a listing of reports.

Requires admin role

> Example request:

```bash
curl -X GET "http://localhost/api/v1/reports" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reports",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/reports`

`HEAD api/v1/reports`


<!-- END_816db9c747aee1929d1aa8d679ab152a -->

<!-- START_afabf50414d2aaf2f2b0b1b3ab17b97c -->
## Display the specified report.

Requires admin role

> Example request:

```bash
curl -X GET "http://localhost/api/v1/reports/{report}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reports/{report}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/reports/{report}`

`HEAD api/v1/reports/{report}`


<!-- END_afabf50414d2aaf2f2b0b1b3ab17b97c -->

<!-- START_2248338be892a9b129658f2b9bd359e0 -->
## Store a newly created report in storage.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/reports" \
-H "Accept: application/json" \
    -d "title"="soluta" \
    -d "body"="soluta" \
    -d "email"="hope24@example.com" \
    -d "os"="android" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reports",
    "method": "POST",
    "data": {
        "title": "soluta",
        "body": "soluta",
        "email": "hope24@example.com",
        "os": "android"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/reports`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    title | string |  required  | Maximum: `255`
    body | string |  required  | 
    email | email |  required  | 
    os | string |  required  | `ios` or `android`

<!-- END_2248338be892a9b129658f2b9bd359e0 -->

#Review

All reviews by users are handled here.
<!-- START_af54b900bc64ab6f36a486fb0ef054b2 -->
## Display a listing of the reviews

> Example request:

```bash
curl -X GET "http://localhost/api/v1/reviews" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reviews",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/reviews`

`HEAD api/v1/reviews`


<!-- END_af54b900bc64ab6f36a486fb0ef054b2 -->

<!-- START_e02d35fb51bdc8748c538fde24f87a41 -->
## Create a new review

To review an offer (and its driver), send offer_id.

To review a booking (and its passenger), send booking_id.

But don't send BOTH offer_id and booking_id. will fail.

> Example request:

```bash
curl -X POST "http://localhost/api/v1/reviews" \
-H "Accept: application/json" \
    -d "offer_id"="157163" \
    -d "booking_id"="157163" \
    -d "body"="est" \
    -d "rating"="4" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reviews",
    "method": "POST",
    "data": {
        "offer_id": 157163,
        "booking_id": 157163,
        "body": "est",
        "rating": 4
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/reviews`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    offer_id | integer |  optional  | Required if the parameters `booking_id` are not present.
    booking_id | integer |  optional  | Required if the parameters `offer_id` are not present.
    body | string |  required  | 
    rating | integer |  required  | Between: `1` and `5`

<!-- END_e02d35fb51bdc8748c538fde24f87a41 -->

<!-- START_e2534a083bf0eb51f62a92e5256ca165 -->
## Display the specified review.

> Example request:

```bash
curl -X GET "http://localhost/api/v1/reviews/{review}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reviews/{review}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/reviews/{review}`

`HEAD api/v1/reviews/{review}`


<!-- END_e2534a083bf0eb51f62a92e5256ca165 -->

<!-- START_d6af07b065d16bfd491c836974f5ea74 -->
## api/v1/reviews-for-user

> Example request:

```bash
curl -X GET "http://localhost/api/v1/reviews-for-user" \
-H "Accept: application/json" \
    -d "user_id"="enim" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reviews-for-user",
    "method": "GET",
    "data": {
        "user_id": "enim"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/reviews-for-user`

`HEAD api/v1/reviews-for-user`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_d6af07b065d16bfd491c836974f5ea74 -->

<!-- START_d045b8f72b5dcb49da5193f2c9316efe -->
## api/v1/reviewer-reviews

> Example request:

```bash
curl -X GET "http://localhost/api/v1/reviewer-reviews" \
-H "Accept: application/json" \
    -d "user_id"="est" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/reviewer-reviews",
    "method": "GET",
    "data": {
        "user_id": "est"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/reviewer-reviews`

`HEAD api/v1/reviewer-reviews`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_d045b8f72b5dcb49da5193f2c9316efe -->

#Role

All methods relating to User Roles
<!-- START_fa2b6949a5b281613fd9d9fe2669a2d8 -->
## Display a user&#039;s roles

> Example request:

```bash
curl -X GET "http://localhost/api/v1/users/me/roles" \
-H "Accept: application/json" \
    -d "user_id"="temporibus" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users/me/roles",
    "method": "GET",
    "data": {
        "user_id": "temporibus"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/users/me/roles`

`HEAD api/v1/users/me/roles`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_fa2b6949a5b281613fd9d9fe2669a2d8 -->

<!-- START_d97fba8dbd0d0033960fdc6a25fca8d9 -->
## Display a listing of all roles

> Example request:

```bash
curl -X GET "http://localhost/api/v1/roles" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/roles",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/roles`

`HEAD api/v1/roles`


<!-- END_d97fba8dbd0d0033960fdc6a25fca8d9 -->

<!-- START_5f753b2bffb6b34b6136ddfe1be7bcce -->
## Add a role to user

> Example request:

```bash
curl -X POST "http://localhost/api/v1/roles" \
-H "Accept: application/json" \
    -d "user_id"="quae" \
    -d "role"="admin" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/roles",
    "method": "POST",
    "data": {
        "user_id": "quae",
        "role": "admin"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/roles`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  required  | 
    role | string |  required  | `admin`

<!-- END_5f753b2bffb6b34b6136ddfe1be7bcce -->

<!-- START_04c524fc2f0ea8c793406426144b4c71 -->
## Remove a role from user

> Example request:

```bash
curl -X DELETE "http://localhost/api/v1/roles/{role}" \
-H "Accept: application/json" \
    -d "user_id"="sunt" \
    -d "role"="admin" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/roles/{role}",
    "method": "DELETE",
    "data": {
        "user_id": "sunt",
        "role": "admin"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE api/v1/roles/{role}`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  required  | 
    role | string |  required  | `admin`

<!-- END_04c524fc2f0ea8c793406426144b4c71 -->

#User

Only admins
<!-- START_09fadd33d8737463deb34c4148347f4e -->
## Ban a user

> Example request:

```bash
curl -X POST "http://localhost/api/v1/users/ban" \
-H "Accept: application/json" \
    -d "user_id"="numquam" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users/ban",
    "method": "POST",
    "data": {
        "user_id": "numquam"
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST api/v1/users/ban`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_09fadd33d8737463deb34c4148347f4e -->

<!-- START_080f3ecebb7bcc2f93284b8f5ae1ac3b -->
## Display a listing of all users

> Example request:

```bash
curl -X GET "http://localhost/api/v1/users" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/users`

`HEAD api/v1/users`


<!-- END_080f3ecebb7bcc2f93284b8f5ae1ac3b -->

<!-- START_b4ea58dd963da91362c51d4088d0d4f4 -->
## Display all info about selected user

> Example request:

```bash
curl -X GET "http://localhost/api/v1/users/{user}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/users/{user}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/users/{user}`

`HEAD api/v1/users/{user}`


<!-- END_b4ea58dd963da91362c51d4088d0d4f4 -->

#general
<!-- START_cc3a2eac2f6d8441e6423e4ecf384927 -->
## Place your BotMan logic here.

> Example request:

```bash
curl -X GET "http://localhost/api/v1/fb-webhook" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/fb-webhook",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET api/v1/fb-webhook`

`POST api/v1/fb-webhook`

`HEAD api/v1/fb-webhook`


<!-- END_cc3a2eac2f6d8441e6423e4ecf384927 -->

