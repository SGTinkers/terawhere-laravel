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
[Get Postman Collection](http://terawhere.local/docs/collection.json)
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
<!-- START_1b3f4e11da19ca4099a0cff58be9537d -->
## Get bookings belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all offers belonging to user($id)

> Example request:

```bash
curl -X GET "http://localhost/api/v1/bookings-for-user" \
-H "Accept: application/json" \
    -d "user_id"="incidunt" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/bookings-for-user",
    "method": "GET",
    "data": {
        "user_id": "incidunt"
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
`GET api/v1/bookings-for-user`

`HEAD api/v1/bookings-for-user`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_1b3f4e11da19ca4099a0cff58be9537d -->

<!-- START_4b769e514aedae68a4fa56662e15b112 -->
## Get all bookings belonging to an offer

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all bookings made to an offer or 404

> Example request:

```bash
curl -X GET "http://localhost/api/v1/bookings-for-offer" \
-H "Accept: application/json" \
    -d "offer_id"="fugiat" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/bookings-for-offer",
    "method": "GET",
    "data": {
        "offer_id": "fugiat"
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
`GET api/v1/bookings-for-offer`

`HEAD api/v1/bookings-for-offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    offer_id | string |  required  | 

<!-- END_4b769e514aedae68a4fa56662e15b112 -->

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

#Offer

All offers by drivers are handled here.
<!-- START_4c83e6d62d6e132846ce0b74ca077846 -->
## Get offers belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Send a simple = true, to get a summarised version of offer.

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

Send a simple = true, to get a summarised version of offer.

If no date given, today's date is used.

Returns all offers on a requested date

> Example request:

```bash
curl -X GET "http://localhost/api/v1/offers-for-date" \
-H "Accept: application/json" \
    -d "date"="2017-05-11" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost/api/v1/offers-for-date",
    "method": "GET",
    "data": {
        "date": "2017-05-11"
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

<!-- START_19a81fab66403a93c1f7cdf329c755f7 -->
## Get nearby offers

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Send a simple = true, to get a summarised version of offer.

Returns all nearby offers (To be optimised)

> Example request:

```bash
curl -X POST "http://localhost/api/v1/nearby-offers" \
-H "Accept: application/json" \
    -d "lat"="15" \
    -d "lng"="31" \
    -d "range"="398446" \

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
        "range": 398446
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
    range | numeric |  required  | 

<!-- END_19a81fab66403a93c1f7cdf329c755f7 -->

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
    -d "meetup_time"="2017-05-11 14:17" \
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
        "meetup_time": "2017-05-11 14:17",
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
    meetup_time | date |  required  | Date format: `Y-m-d H:i`
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
    -d "meetup_time"="2017-05-11 14:17" \
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
        "meetup_time": "2017-05-11 14:17",
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
    meetup_time | date |  required  | Date format: `Y-m-d H:i`
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

Status: Cancelled = 0, Pending = 1, Ongoing = 2, Completed = 3

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

