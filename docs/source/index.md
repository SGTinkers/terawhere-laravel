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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/me" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/me",
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
{
    "user": {
        "id": "8226332dabce46fab17b588c1d2df14d",
        "name": "Abdul Aziz",
        "email": "azizsafudin@yahoo.com.sg",
        "dp": "https:\/\/graph.facebook.com\/v2.9\/10154874416708071\/picture?type=normal",
        "facebook_id": "10154874416708071",
        "google_id": null,
        "gender": "male",
        "exp": 500,
        "timezone": "Asia\/Singapore",
        "created_at": "2017-05-18 04:10:34",
        "updated_at": "2017-05-18 04:10:34"
    }
}
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
curl -X POST "http://terawhere.ruqqq.sg/api/v1/auth" \
-H "Accept: application/json" \
    -d "service"="facebook" \
    -d "token"="in" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/auth",
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/auth/refresh" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/auth/refresh",
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
{
    "status": "Ok",
    "message": "Check Authorization Header for new token"
}
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/bookings-for-user" \
-H "Accept: application/json" \
    -d "user_id"="incidunt" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/bookings-for-user",
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
{
    "data": [
        {
            "id": 31,
            "user_id": "8226332dabce46fab17b588c1d2df14d",
            "offer_id": 7,
            "pax": 1,
            "deleted_at": "2017-05-18 06:20:15",
            "created_at": "2017-05-18 06:17:38",
            "updated_at": "2017-05-18 06:20:15"
        }
    ]
}
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/bookings-for-offer" \
-H "Accept: application/json" \
    -d "offer_id"="19383562" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/bookings-for-offer",
    "method": "GET",
    "data": {
        "offer_id": 19383562
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
{
    "offer_id": [
        "The offer id field is required."
    ]
}
```

### HTTP Request
`GET api/v1/bookings-for-offer`

`HEAD api/v1/bookings-for-offer`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    offer_id | integer |  required  | 

<!-- END_4b769e514aedae68a4fa56662e15b112 -->

<!-- START_7af9cd11c6f570507128bd47a1d55065 -->
## Get all bookings

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all bookings in database

> Example request:

```bash
curl -X GET "http://terawhere.ruqqq.sg/api/v1/bookings" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/bookings",
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
{
    "data": [
        {
            "id": 1,
            "user_id": "b28ecd18cc974c27a3b951951b3efb3f",
            "offer_id": 5,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 2,
            "user_id": "79c3dc5941d1461db85ca0fcf7f1f88c",
            "offer_id": 16,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 3,
            "user_id": "dbac2ccee66c407882bd4d701457cf41",
            "offer_id": 26,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 4,
            "user_id": "f1331c71e5924ff78171dd70fe4238ca",
            "offer_id": 17,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 5,
            "user_id": "dbac2ccee66c407882bd4d701457cf41",
            "offer_id": 8,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 6,
            "user_id": "75c6c3da9ae841e2b527e68ab12cca36",
            "offer_id": 4,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 7,
            "user_id": "dbac2ccee66c407882bd4d701457cf41",
            "offer_id": 4,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 8,
            "user_id": "cb75143e79d84ac09feecb00e874a389",
            "offer_id": 26,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 9,
            "user_id": "79c3dc5941d1461db85ca0fcf7f1f88c",
            "offer_id": 13,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 10,
            "user_id": "dd48cc2385d64435af565f94b2f09886",
            "offer_id": 12,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 11,
            "user_id": "cb75143e79d84ac09feecb00e874a389",
            "offer_id": 10,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 12,
            "user_id": "f1331c71e5924ff78171dd70fe4238ca",
            "offer_id": 4,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 13,
            "user_id": "dc74d7f3ee3a470584f4096c26ad0357",
            "offer_id": 25,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 14,
            "user_id": "d4a658980a304a3fba25b3f10caeac96",
            "offer_id": 25,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 15,
            "user_id": "75c6c3da9ae841e2b527e68ab12cca36",
            "offer_id": 9,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 16,
            "user_id": "dbac2ccee66c407882bd4d701457cf41",
            "offer_id": 1,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 17,
            "user_id": "d4a658980a304a3fba25b3f10caeac96",
            "offer_id": 21,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 18,
            "user_id": "dc74d7f3ee3a470584f4096c26ad0357",
            "offer_id": 9,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 19,
            "user_id": "75c6c3da9ae841e2b527e68ab12cca36",
            "offer_id": 29,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 20,
            "user_id": "dbac2ccee66c407882bd4d701457cf41",
            "offer_id": 7,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 21,
            "user_id": "d4a658980a304a3fba25b3f10caeac96",
            "offer_id": 22,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 22,
            "user_id": "c435745975aa479a9f441d073f0a4353",
            "offer_id": 28,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 23,
            "user_id": "cb75143e79d84ac09feecb00e874a389",
            "offer_id": 20,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 24,
            "user_id": "79c3dc5941d1461db85ca0fcf7f1f88c",
            "offer_id": 6,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 25,
            "user_id": "b28ecd18cc974c27a3b951951b3efb3f",
            "offer_id": 29,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 26,
            "user_id": "b28ecd18cc974c27a3b951951b3efb3f",
            "offer_id": 16,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 27,
            "user_id": "f1331c71e5924ff78171dd70fe4238ca",
            "offer_id": 12,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 28,
            "user_id": "d4a658980a304a3fba25b3f10caeac96",
            "offer_id": 22,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 29,
            "user_id": "75c6c3da9ae841e2b527e68ab12cca36",
            "offer_id": 25,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 30,
            "user_id": "dd48cc2385d64435af565f94b2f09886",
            "offer_id": 8,
            "pax": 1,
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        }
    ]
}
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
curl -X POST "http://terawhere.ruqqq.sg/api/v1/bookings" \
-H "Accept: application/json" \
    -d "offer_id"="3331" \
    -d "pax"="3331" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/bookings",
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/bookings/{booking}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/bookings/{booking}",
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
{
    "data": {
        "id": 1,
        "user_id": "b28ecd18cc974c27a3b951951b3efb3f",
        "offer_id": 5,
        "pax": 1,
        "deleted_at": null,
        "created_at": "2017-05-18 05:27:38",
        "updated_at": "2017-05-18 05:27:38",
        "name": "frederique.jenkins",
        "gender": null
    }
}
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
curl -X DELETE "http://terawhere.ruqqq.sg/api/v1/bookings/{booking}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/bookings/{booking}",
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
curl -X POST "http://terawhere.ruqqq.sg/api/v1/devices" \
-H "Accept: application/json" \
    -d "device_token"="possimus" \
    -d "platform"="web" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/devices",
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

<!-- START_721d5d71e2f53268541f847dfdf27a84 -->
## Send test notification

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

> Example request:

```bash
curl -X POST "http://terawhere.ruqqq.sg/api/v1/test-notification" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/test-notification",
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
`POST api/v1/test-notification`


<!-- END_721d5d71e2f53268541f847dfdf27a84 -->

<!-- START_d787f9a72e2a991d0148cce413f7a85e -->
## Get devices belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

> Example request:

```bash
curl -X GET "http://terawhere.ruqqq.sg/api/v1/devices-for-user" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/devices-for-user",
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
{
    "error": "Resource_not_found",
    "message": "There are no devices for this user."
}
```

### HTTP Request
`GET api/v1/devices-for-user`

`HEAD api/v1/devices-for-user`


<!-- END_d787f9a72e2a991d0148cce413f7a85e -->

#Offer

All offers by drivers are handled here.
<!-- START_4c83e6d62d6e132846ce0b74ca077846 -->
## Get offers belonging to a user

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Returns all offers belonging to user($id)

> Example request:

```bash
curl -X GET "http://terawhere.ruqqq.sg/api/v1/offers-for-user" \
-H "Accept: application/json" \
    -d "user_id"="velit" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/offers-for-user",
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
{
    "error": "Trying to get property of non-object",
    "trace": "#0 \/var\/www\/terawhere\/vendor\/sentry\/sentry\/lib\/Raven\/Breadcrumbs\/ErrorHandler.php(36): Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(8, 'Trying to get p...', '\/var\/www\/terawh...', 258, Array)\n#1 \/var\/www\/terawhere\/app\/Http\/Controllers\/OfferController.php(258): Raven_Breadcrumbs_ErrorHandler->handleError(8, 'Trying to get p...', '\/var\/www\/terawh...', 258, Array)\n#2 [internal function]: App\\Http\\Controllers\\OfferController->getUsersOffers(Object(App\\Http\\Requests\\GetUserId))\n#3 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Controller.php(55): call_user_func_array(Array, Array)\n#4 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/ControllerDispatcher.php(44): Illuminate\\Routing\\Controller->callAction('getUsersOffers', Array)\n#5 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php(203): Illuminate\\Routing\\ControllerDispatcher->dispatch(Object(Illuminate\\Routing\\Route), Object(App\\Http\\Controllers\\OfferController), 'getUsersOffers')\n#6 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Route.php(160): Illuminate\\Routing\\Route->runController()\n#7 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(559): Illuminate\\Routing\\Route->run()\n#8 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php(30): Illuminate\\Routing\\Router->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#9 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(102): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#10 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(561): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#11 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(520): Illuminate\\Routing\\Router->runRouteWithinStack(Object(Illuminate\\Routing\\Route), Object(Illuminate\\Http\\Request))\n#12 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Router.php(498): Illuminate\\Routing\\Router->dispatchToRoute(Object(Illuminate\\Http\\Request))\n#13 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php(174): Illuminate\\Routing\\Router->dispatch(Object(Illuminate\\Http\\Request))\n#14 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Routing\/Pipeline.php(30): Illuminate\\Foundation\\Http\\Kernel->Illuminate\\Foundation\\Http\\{closure}(Object(Illuminate\\Http\\Request))\n#15 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Pipeline\/Pipeline.php(102): Illuminate\\Routing\\Pipeline->Illuminate\\Routing\\{closure}(Object(Illuminate\\Http\\Request))\n#16 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php(149): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#17 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Http\/Kernel.php(116): Illuminate\\Foundation\\Http\\Kernel->sendRequestThroughRouter(Object(Illuminate\\Http\\Request))\n#18 \/var\/www\/terawhere\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/LaravelGenerator.php(116): Illuminate\\Foundation\\Http\\Kernel->handle(Object(Illuminate\\Http\\Request))\n#19 \/var\/www\/terawhere\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/AbstractGenerator.php(98): Mpociot\\ApiDoc\\Generators\\LaravelGenerator->callRoute('GET', 'api\/v1\/offers-f...', Array, Array, Array, Array)\n#20 \/var\/www\/terawhere\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Generators\/LaravelGenerator.php(58): Mpociot\\ApiDoc\\Generators\\AbstractGenerator->getRouteResponse(Object(Illuminate\\Routing\\Route), Array, Array)\n#21 \/var\/www\/terawhere\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Commands\/GenerateDocumentation.php(261): Mpociot\\ApiDoc\\Generators\\LaravelGenerator->processRoute(Object(Illuminate\\Routing\\Route), Array, Array, true)\n#22 \/var\/www\/terawhere\/vendor\/mpociot\/laravel-apidoc-generator\/src\/Mpociot\/ApiDoc\/Commands\/GenerateDocumentation.php(83): Mpociot\\ApiDoc\\Commands\\GenerateDocumentation->processLaravelRoutes(Object(Mpociot\\ApiDoc\\Generators\\LaravelGenerator), Array, 'api\/*', NULL)\n#23 [internal function]: Mpociot\\ApiDoc\\Commands\\GenerateDocumentation->handle()\n#24 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php(29): call_user_func_array(Array, Array)\n#25 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php(87): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#26 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/BoundMethod.php(31): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#27 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Container\/Container.php(531): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#28 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php(182): Illuminate\\Container\\Container->call(Array)\n#29 \/var\/www\/terawhere\/vendor\/symfony\/console\/Command\/Command.php(264): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#30 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Console\/Command.php(167): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#31 \/var\/www\/terawhere\/vendor\/symfony\/console\/Application.php(835): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#32 \/var\/www\/terawhere\/vendor\/symfony\/console\/Application.php(200): Symfony\\Component\\Console\\Application->doRunCommand(Object(Mpociot\\ApiDoc\\Commands\\GenerateDocumentation), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#33 \/var\/www\/terawhere\/vendor\/symfony\/console\/Application.php(124): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 \/var\/www\/terawhere\/vendor\/laravel\/framework\/src\/Illuminate\/Foundation\/Console\/Kernel.php(122): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 \/var\/www\/terawhere\/artisan(35): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 {main}",
    "code": 0
}
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/offers-for-date" \
-H "Accept: application/json" \
    -d "date"="2017-05-18" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/offers-for-date",
    "method": "GET",
    "data": {
        "date": "2017-05-18"
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
{
    "error": "Offer_not_found",
    "message": "There are no offers on this date."
}
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

Range accepts 1-12 (Precision of geohash, 1 = ~5000km, 12 = 3.7 cm. Defaults to 4)

Returns all nearby offers in the next 24 hours.

> Example request:

```bash
curl -X POST "http://terawhere.ruqqq.sg/api/v1/nearby-offers" \
-H "Accept: application/json" \
    -d "lat"="15" \
    -d "lng"="31" \
    -d "range"="8" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/nearby-offers",
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

<!-- START_c233fc34839427dff7ef9ad7c3821ae3 -->
## Get all offers

**Requires Authentication Header - ** *Authorization: Bearer [JWTTokenHere]*

Not recommended for use

Returns ALL offers in database

> Example request:

```bash
curl -X GET "http://terawhere.ruqqq.sg/api/v1/offers" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/offers",
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
{
    "data": [
        {
            "id": 2,
            "user_id": "70565c5099db47019d6dc8bb7a0578fe",
            "meetup_time": "2017-05-18 12:23:00",
            "start_name": "djdjd",
            "start_addr": "You are at: 501 Woodlands Drive 14",
            "start_lat": 1.432828,
            "start_lng": 103.791742,
            "start_geohash": "w23b1uffffs62",
            "end_name": "didkks",
            "end_addr": "506 Woodlands Drive 14, Singapore 730506",
            "end_lat": 1.432662,
            "end_lng": 103.790652,
            "end_geohash": "w23b1uf1c7xswq2k6uh",
            "vacancy": 4,
            "status": 1,
            "pref_gender": "male",
            "remarks": "sjsj",
            "vehicle_number": "sjdj",
            "vehicle_desc": "sjdkd",
            "vehicle_model": "ejjs",
            "deleted_at": null,
            "created_at": "2017-05-18 03:23:36",
            "updated_at": "2017-05-18 03:23:36"
        },
        {
            "id": 3,
            "user_id": "7f0f17db103043f0b9315a0d8bf767de",
            "meetup_time": "2017-05-19 01:39:09",
            "start_name": "Caroline Inlet",
            "start_addr": "17299 Frami Centers",
            "start_lat": 1.41852,
            "start_lng": 103.980532,
            "start_geohash": "w23bn4hr6p21",
            "end_name": "Julio Haven",
            "end_addr": "108 Sipes Road Apt. 549",
            "end_lat": 1.381847,
            "end_lng": 103.875936,
            "end_geohash": "w21zgg81udzy",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Swr7783t",
            "vehicle_desc": null,
            "vehicle_model": "Donnie Marvin",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 4,
            "user_id": "6ccc4924cd534a658d3de1b8bb91be7b",
            "meetup_time": "2017-05-18 21:42:58",
            "start_name": "Eloisa Lake",
            "start_addr": "6517 Hudson Loaf Suite 453",
            "start_lat": 1.382963,
            "start_lng": 103.158145,
            "start_geohash": "w21rg7v2r4bp",
            "end_name": "Sanford Hill",
            "end_addr": "958 Major View Suite 647",
            "end_lat": 1.240293,
            "end_lng": 104.945611,
            "end_geohash": "w24xh1g11zr7",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Snn4990a",
            "vehicle_desc": null,
            "vehicle_model": "Karelle Strosin",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 5,
            "user_id": "3f7bc6f7311e46f68177c1c27b224f60",
            "meetup_time": "2017-05-18 17:20:36",
            "start_name": "Fay Drive",
            "start_addr": "47140 Asia Forest",
            "start_lat": 1.353887,
            "start_lng": 104.160568,
            "start_geohash": "w24pdnrqvnuf",
            "end_name": "Mertz Drive",
            "end_addr": "83218 Swaniawski Fort Suite 764",
            "end_lat": 1.224473,
            "end_lng": 103.462438,
            "end_geohash": "w21wfqgj1d33",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sag6906p",
            "vehicle_desc": null,
            "vehicle_model": "Flavio Rogahn",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 6,
            "user_id": "7f0f17db103043f0b9315a0d8bf767de",
            "meetup_time": "2017-05-18 11:00:06",
            "start_name": "Vladimir Expressway",
            "start_addr": "6540 Marcia Streets Apt. 157",
            "start_lat": 1.248464,
            "start_lng": 103.02473,
            "start_geohash": "w21r07k2f4w9",
            "end_name": "Kihn Road",
            "end_addr": "994 Labadie Drive",
            "end_lat": 1.269917,
            "end_lng": 103.888734,
            "end_geohash": "w21zhp1mycwt",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sfq369z",
            "vehicle_desc": null,
            "vehicle_model": "Rahsaan Reichert",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 7,
            "user_id": "3f7bc6f7311e46f68177c1c27b224f60",
            "meetup_time": "2017-05-18 18:21:43",
            "start_name": "Rippin Plaza",
            "start_addr": "7565 Gladyce Ranch",
            "start_lat": 1.383401,
            "start_lng": 103.711284,
            "start_geohash": "w21zb5b6bhdk",
            "end_name": "Botsford Estate",
            "end_addr": "23604 Auer Spring Suite 960",
            "end_lat": 1.230974,
            "end_lng": 104.65457,
            "end_geohash": "w24rj2p4unpm",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Swf7101l",
            "vehicle_desc": null,
            "vehicle_model": "Jacklyn Ullrich",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 8,
            "user_id": "674193acb5274bababfc722dfce1392a",
            "meetup_time": "2017-05-18 18:42:30",
            "start_name": "Rempel Roads",
            "start_addr": "446 Kunde Streets Suite 148",
            "start_lat": 1.413663,
            "start_lng": 104.377031,
            "start_geohash": "w260p1m51jgk",
            "end_name": "Kunde Prairie",
            "end_addr": "974 Anderson Street Apt. 942",
            "end_lat": 1.347417,
            "end_lng": 104.994253,
            "end_geohash": "w24xtjq3r2tr",
            "vacancy": 1,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sqb4002o",
            "vehicle_desc": null,
            "vehicle_model": "Lonzo Kreiger",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 9,
            "user_id": "15f7ecbdcd664d22af90f638b491540f",
            "meetup_time": "2017-05-19 03:01:08",
            "start_name": "Rod Court",
            "start_addr": "3219 Lowell Lodge Suite 076",
            "start_lat": 1.36623,
            "start_lng": 104.863117,
            "start_geohash": "w24xf0wyzexr",
            "end_name": "Aiyana Falls",
            "end_addr": "16695 Santino Expressway Apt. 986",
            "end_lat": 1.311649,
            "end_lng": 103.076016,
            "end_geohash": "w21r3wc8vm05",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Slz58f",
            "vehicle_desc": null,
            "vehicle_model": "Percy Bechtelar",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 10,
            "user_id": "3f7bc6f7311e46f68177c1c27b224f60",
            "meetup_time": "2017-05-18 19:15:37",
            "start_name": "Syble Field",
            "start_addr": "43200 Kihn Wells Suite 889",
            "start_lat": 1.394081,
            "start_lng": 103.285166,
            "start_geohash": "w21rymccng9c",
            "end_name": "Ryan Mall",
            "end_addr": "6138 Bernier Forks Suite 144",
            "end_lat": 1.391597,
            "end_lng": 103.687138,
            "end_geohash": "w21xzmqdtkmz",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sav619d",
            "vehicle_desc": null,
            "vehicle_model": "Shanna Stehr",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 11,
            "user_id": "6ccc4924cd534a658d3de1b8bb91be7b",
            "meetup_time": "2017-05-18 07:41:45",
            "start_name": "Abbie Estate",
            "start_addr": "860 Mafalda Pines Suite 166",
            "start_lat": 1.417206,
            "start_lng": 104.305704,
            "start_geohash": "w260j9cpg465",
            "end_name": "Berge Mills",
            "end_addr": "708 Ondricka Place",
            "end_lat": 1.443995,
            "end_lng": 103.919306,
            "end_geohash": "w23bhwzezk4v",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sbq7467l",
            "vehicle_desc": null,
            "vehicle_model": "Cordia Swift",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 12,
            "user_id": "7f0f17db103043f0b9315a0d8bf767de",
            "meetup_time": "2017-05-18 11:52:52",
            "start_name": "Wyman Inlet",
            "start_addr": "1784 Eugenia Islands Apt. 375",
            "start_lat": 1.213576,
            "start_lng": 103.872386,
            "start_geohash": "w21ygsvtd4bw",
            "end_name": "Jan Rue",
            "end_addr": "750 Monserrate Island Suite 339",
            "end_lat": 1.369409,
            "end_lng": 103.530585,
            "end_geohash": "w21xgck9mkr6",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sec8185m",
            "vehicle_desc": null,
            "vehicle_model": "Leatha Walker",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 13,
            "user_id": "ca7dd240c4794cc2a2257f7635c6c472",
            "meetup_time": "2017-05-18 08:56:23",
            "start_name": "Loyal Cape",
            "start_addr": "23895 Lehner Circle",
            "start_lat": 1.291231,
            "start_lng": 103.54856,
            "start_geohash": "w21xk71cbqfs",
            "end_name": "Ernesto Lodge",
            "end_addr": "267 Konopelski Meadow Suite 618",
            "end_lat": 1.220806,
            "end_lng": 104.459965,
            "end_geohash": "w24qcn1rtwer",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Syi5335h",
            "vehicle_desc": null,
            "vehicle_model": "Shaylee Carter",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 14,
            "user_id": "2b1edfc694654768a613b0d6dc4c016b",
            "meetup_time": "2017-05-18 18:29:31",
            "start_name": "Jennie Streets",
            "start_addr": "39397 Sanford Plains Apt. 538",
            "start_lat": 1.356422,
            "start_lng": 104.488273,
            "start_geohash": "w24r9wyj9prk",
            "end_name": "Price Vista",
            "end_addr": "67833 Caterina Spring Apt. 266",
            "end_lat": 1.417609,
            "end_lng": 103.831126,
            "end_geohash": "w23b4dpd0t7p",
            "vacancy": 1,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Srf1412b",
            "vehicle_desc": null,
            "vehicle_model": "Roel Beer",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 15,
            "user_id": "6ccc4924cd534a658d3de1b8bb91be7b",
            "meetup_time": "2017-05-19 03:26:39",
            "start_name": "Kshlerin Flat",
            "start_addr": "46380 Ward View Suite 449",
            "start_lat": 1.305022,
            "start_lng": 104.762452,
            "start_geohash": "w24rrvtdq1dp",
            "end_name": "Ledner Prairie",
            "end_addr": "61812 Kulas Center Suite 372",
            "end_lat": 1.275386,
            "end_lng": 103.282763,
            "end_geohash": "w21rq20jwv1v",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sir6190y",
            "vehicle_desc": null,
            "vehicle_model": "Ashlynn Kemmer",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 16,
            "user_id": "674193acb5274bababfc722dfce1392a",
            "meetup_time": "2017-05-19 03:03:01",
            "start_name": "Nathanael Road",
            "start_addr": "315 Joe Plains Suite 215",
            "start_lat": 1.224627,
            "start_lng": 104.576715,
            "start_geohash": "w24qgwymyp42",
            "end_name": "Santino Trail",
            "end_addr": "47789 Corrine Unions",
            "end_lat": 1.44589,
            "end_lng": 103.069412,
            "end_geohash": "w2321rhygm7v",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sad6474z",
            "vehicle_desc": null,
            "vehicle_model": "Michelle Pfannerstill",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 17,
            "user_id": "3f7bc6f7311e46f68177c1c27b224f60",
            "meetup_time": "2017-05-18 11:32:49",
            "start_name": "Conroy Forges",
            "start_addr": "723 Roderick Tunnel Suite 888",
            "start_lat": 1.262768,
            "start_lng": 103.512125,
            "start_geohash": "w21x5mzhpm48",
            "end_name": "DuBuque Light",
            "end_addr": "650 Hahn Mills Suite 138",
            "end_lat": 1.353307,
            "end_lng": 104.978717,
            "end_geohash": "w24xsy75t6sy",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sjo980k",
            "vehicle_desc": null,
            "vehicle_model": "Victoria Lowe",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 18,
            "user_id": "6ccc4924cd534a658d3de1b8bb91be7b",
            "meetup_time": "2017-05-18 22:10:53",
            "start_name": "Althea Loaf",
            "start_addr": "58013 Klocko Points",
            "start_lat": 1.422863,
            "start_lng": 103.594967,
            "start_geohash": "w238j758c8yh",
            "end_name": "Schuppe Oval",
            "end_addr": "57443 Leslie Rapids Suite 660",
            "end_lat": 1.26787,
            "end_lng": 103.862904,
            "end_geohash": "w21z5qy9vh47",
            "vacancy": 2,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sxz9704d",
            "vehicle_desc": null,
            "vehicle_model": "Kaleigh Leffler",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 19,
            "user_id": "3f7bc6f7311e46f68177c1c27b224f60",
            "meetup_time": "2017-05-18 08:31:02",
            "start_name": "Damon Roads",
            "start_addr": "146 Pouros Plain Suite 393",
            "start_lat": 1.337538,
            "start_lng": 104.329818,
            "start_geohash": "w24pw56xszns",
            "end_name": "Kellen Lakes",
            "end_addr": "628 Angelita Cape Suite 702",
            "end_lat": 1.438097,
            "end_lng": 104.168281,
            "end_geohash": "w2604mv18bsn",
            "vacancy": 2,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sef1822z",
            "vehicle_desc": null,
            "vehicle_model": "Reggie McClure",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 20,
            "user_id": "cd0eb9d483cf465d9888a1f43b5c1d60",
            "meetup_time": "2017-05-18 18:47:38",
            "start_name": "Walsh Land",
            "start_addr": "2310 Rowe Tunnel Apt. 012",
            "start_lat": 1.342239,
            "start_lng": 104.377698,
            "start_geohash": "w24pxhme0ekx",
            "end_name": "Elmer Street",
            "end_addr": "266 Turcotte Islands",
            "end_lat": 1.368111,
            "end_lng": 104.535742,
            "end_geohash": "w24rfc09g66u",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Svy5006m",
            "vehicle_desc": null,
            "vehicle_model": "Adrianna Denesik",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 21,
            "user_id": "cd0eb9d483cf465d9888a1f43b5c1d60",
            "meetup_time": "2017-05-18 10:12:34",
            "start_name": "Pfeffer Manors",
            "start_addr": "29682 Reinger Junctions Suite 501",
            "start_lat": 1.327987,
            "start_lng": 104.790939,
            "start_geohash": "w24x89f2jfer",
            "end_name": "Ova Route",
            "end_addr": "879 Laura Parkways Apt. 233",
            "end_lat": 1.376981,
            "end_lng": 104.397255,
            "end_geohash": "w24pzdev2rg7",
            "vacancy": 1,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sfb610h",
            "vehicle_desc": null,
            "vehicle_model": "Javonte Nolan",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 22,
            "user_id": "c5ae4f39e48947359834b813743bdd2f",
            "meetup_time": "2017-05-18 18:48:53",
            "start_name": "Douglas Crescent",
            "start_addr": "46244 Rebeca Gardens Apt. 123",
            "start_lat": 1.332733,
            "start_lng": 104.974008,
            "start_geohash": "w24xsdxexxmw",
            "end_name": "Jerald Junction",
            "end_addr": "38883 Krajcik Keys Suite 021",
            "end_lat": 1.291969,
            "end_lng": 103.328831,
            "end_geohash": "w21rr71y222x",
            "vacancy": 2,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Saj603k",
            "vehicle_desc": null,
            "vehicle_model": "Jameson Klocko",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 23,
            "user_id": "6ccc4924cd534a658d3de1b8bb91be7b",
            "meetup_time": "2017-05-18 23:15:08",
            "start_name": "Birdie Pine",
            "start_addr": "653 Margaretta Extensions",
            "start_lat": 1.418639,
            "start_lng": 103.81595,
            "start_geohash": "w23b46k2nvqz",
            "end_name": "Pat Rue",
            "end_addr": "55880 Kihn Landing Suite 649",
            "end_lat": 1.390133,
            "end_lng": 104.341376,
            "end_geohash": "w24pym544794",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sta5130o",
            "vehicle_desc": null,
            "vehicle_model": "Elvis Wiza",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 24,
            "user_id": "6ccc4924cd534a658d3de1b8bb91be7b",
            "meetup_time": "2017-05-18 17:57:52",
            "start_name": "Else Roads",
            "start_addr": "23416 Elwin Greens",
            "start_lat": 1.224329,
            "start_lng": 104.942933,
            "start_geohash": "w24wunch5x6q",
            "end_name": "Wisozk Green",
            "end_addr": "668 Gerardo Flats Suite 951",
            "end_lat": 1.285309,
            "end_lng": 103.044731,
            "end_geohash": "w21r2cfzkqbq",
            "vacancy": 1,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Siv9928k",
            "vehicle_desc": null,
            "vehicle_model": "Simone Adams",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 25,
            "user_id": "ca7dd240c4794cc2a2257f7635c6c472",
            "meetup_time": "2017-05-18 16:09:30",
            "start_name": "Edmund Rapid",
            "start_addr": "33836 Dessie Fields",
            "start_lat": 1.409457,
            "start_lng": 104.17449,
            "start_geohash": "w260489d9tcf",
            "end_name": "Johnson Mountain",
            "end_addr": "1247 Gottlieb Shoals Apt. 914",
            "end_lat": 1.39177,
            "end_lng": 104.825814,
            "end_geohash": "w24xcm7gds8p",
            "vacancy": 2,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sdl8989h",
            "vehicle_desc": null,
            "vehicle_model": "Elsie Bosco",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 26,
            "user_id": "c5ae4f39e48947359834b813743bdd2f",
            "meetup_time": "2017-05-18 18:04:53",
            "start_name": "Abdul Trail",
            "start_addr": "61013 Champlin Terrace",
            "start_lat": 1.376223,
            "start_lng": 104.120315,
            "start_geohash": "w24pc6d154tc",
            "end_name": "Lennie Underpass",
            "end_addr": "4849 Bayer Roads",
            "end_lat": 1.354398,
            "end_lng": 104.154335,
            "end_geohash": "w24pdndcgyj4",
            "vacancy": 1,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sbc3563t",
            "vehicle_desc": null,
            "vehicle_model": "Liana Runte",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 27,
            "user_id": "cd0eb9d483cf465d9888a1f43b5c1d60",
            "meetup_time": "2017-05-18 10:02:35",
            "start_name": "Daniel Coves",
            "start_addr": "23522 Miller Crossroad Apt. 145",
            "start_lat": 1.39128,
            "start_lng": 104.548435,
            "start_geohash": "w24rgj3bg17k",
            "end_name": "Lauryn Fort",
            "end_addr": "5797 Daniel River",
            "end_lat": 1.23591,
            "end_lng": 104.854225,
            "end_geohash": "w24x40bx8w2f",
            "vacancy": 2,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sly5680p",
            "vehicle_desc": null,
            "vehicle_model": "Neal Hackett",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 28,
            "user_id": "c5ae4f39e48947359834b813743bdd2f",
            "meetup_time": "2017-05-18 18:46:20",
            "start_name": "Feest Radial",
            "start_addr": "92430 Ephraim Stream Apt. 708",
            "start_lat": 1.204489,
            "start_lng": 103.055545,
            "start_geohash": "w21qc56b8jpb",
            "end_name": "Rachelle Pines",
            "end_addr": "530 Kaylah Rapids Apt. 597",
            "end_lat": 1.341924,
            "end_lng": 103.928331,
            "end_geohash": "w21zsuq338u2",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sag8033y",
            "vehicle_desc": null,
            "vehicle_model": "Henderson Fritsch",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 29,
            "user_id": "29d6f4b2b82743cb8595dd3a6757bb33",
            "meetup_time": "2017-05-18 19:52:26",
            "start_name": "Feeney Garden",
            "start_addr": "77187 Kaden Prairie",
            "start_lat": 1.274383,
            "start_lng": 103.101318,
            "start_geohash": "w21r4pupff4y",
            "end_name": "Grant Loaf",
            "end_addr": "328 Donnelly Crest",
            "end_lat": 1.28951,
            "end_lng": 103.940187,
            "end_geohash": "w21zm4wzvyhf",
            "vacancy": 3,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sca7939l",
            "vehicle_desc": null,
            "vehicle_model": "Burley Kuphal",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 30,
            "user_id": "ca7dd240c4794cc2a2257f7635c6c472",
            "meetup_time": "2017-05-19 05:26:11",
            "start_name": "Guy Manors",
            "start_addr": "3614 Glover Spring Apt. 929",
            "start_lat": 1.347824,
            "start_lng": 104.239498,
            "start_geohash": "w24psj2gske7",
            "end_name": "Torphy Stravenue",
            "end_addr": "3281 Yundt Isle Apt. 557",
            "end_lat": 1.322682,
            "end_lng": 103.590436,
            "end_geohash": "w21xt2b30jg9",
            "vacancy": 1,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sly3046q",
            "vehicle_desc": null,
            "vehicle_model": "Haylie Thompson",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 31,
            "user_id": "cd0eb9d483cf465d9888a1f43b5c1d60",
            "meetup_time": "2017-05-18 19:01:51",
            "start_name": "Jamel Landing",
            "start_addr": "9039 Deckow Turnpike Suite 341",
            "start_lat": 1.247174,
            "start_lng": 103.548264,
            "start_geohash": "w21xh71936jf",
            "end_name": "Lucy Lodge",
            "end_addr": "320 Annabelle Ford Suite 217",
            "end_lat": 1.445659,
            "end_lng": 104.985755,
            "end_geohash": "w268jp0m96kg",
            "vacancy": 2,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Sun4355r",
            "vehicle_desc": null,
            "vehicle_model": "Randal Cremin",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        },
        {
            "id": 32,
            "user_id": "674193acb5274bababfc722dfce1392a",
            "meetup_time": "2017-05-19 03:58:44",
            "start_name": "Ziemann Manors",
            "start_addr": "60887 Wiley Point Suite 669",
            "start_lat": 1.251601,
            "start_lng": 104.533493,
            "start_geohash": "w24r4eygngmf",
            "end_name": "Toney Extension",
            "end_addr": "5816 Baumbach Crossroad",
            "end_lat": 1.328749,
            "end_lng": 104.603885,
            "end_geohash": "w24rs3fhx0vb",
            "vacancy": 4,
            "status": 1,
            "pref_gender": null,
            "remarks": null,
            "vehicle_number": "Svd356j",
            "vehicle_desc": null,
            "vehicle_model": "Cleora Bogisich",
            "deleted_at": null,
            "created_at": "2017-05-18 05:27:38",
            "updated_at": "2017-05-18 05:27:38"
        }
    ]
}
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
curl -X POST "http://terawhere.ruqqq.sg/api/v1/offers" \
-H "Accept: application/json" \
    -d "meetup_time"="2017-05-18 07:31" \
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
    "url": "http://terawhere.ruqqq.sg/api/v1/offers",
    "method": "POST",
    "data": {
        "meetup_time": "2017-05-18 07:31",
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/offers/{offer}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/offers/{offer}",
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
{
    "error": "Offer_not_found",
    "message": "Offer does not exist"
}
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
curl -X PUT "http://terawhere.ruqqq.sg/api/v1/offers/{offer}" \
-H "Accept: application/json" \
    -d "meetup_time"="2017-05-18 07:31" \
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
    "url": "http://terawhere.ruqqq.sg/api/v1/offers/{offer}",
    "method": "PUT",
    "data": {
        "meetup_time": "2017-05-18 07:31",
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

Status: Cancelled by User= 0, Pending = 1, Ongoing = 2, Completed = 3, Expired by server = 4.

Returns Success or 404.

> Example request:

```bash
curl -X DELETE "http://terawhere.ruqqq.sg/api/v1/offers/{offer}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/offers/{offer}",
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

#Review

All reviews by users are handled here.
<!-- START_af54b900bc64ab6f36a486fb0ef054b2 -->
## Display a listing of the reviews

> Example request:

```bash
curl -X GET "http://terawhere.ruqqq.sg/api/v1/reviews" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/reviews",
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
{
    "data": []
}
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
curl -X POST "http://terawhere.ruqqq.sg/api/v1/reviews" \
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
    "url": "http://terawhere.ruqqq.sg/api/v1/reviews",
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/reviews/{review}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/reviews/{review}",
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
{
    "error": "Resource_not_found",
    "message": "Review does not exist."
}
```

### HTTP Request
`GET api/v1/reviews/{review}`

`HEAD api/v1/reviews/{review}`


<!-- END_e2534a083bf0eb51f62a92e5256ca165 -->

<!-- START_d6af07b065d16bfd491c836974f5ea74 -->
## api/v1/reviews-for-user

> Example request:

```bash
curl -X GET "http://terawhere.ruqqq.sg/api/v1/reviews-for-user" \
-H "Accept: application/json" \
    -d "user_id"="enim" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/reviews-for-user",
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
{
    "error": "Resource_not_found",
    "message": "User has not been reviewed."
}
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
curl -X GET "http://terawhere.ruqqq.sg/api/v1/reviewer-reviews" \
-H "Accept: application/json" \
    -d "user_id"="est" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://terawhere.ruqqq.sg/api/v1/reviewer-reviews",
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
{
    "error": "Resource_not_found",
    "message": "User has not made any reviews."
}
```

### HTTP Request
`GET api/v1/reviewer-reviews`

`HEAD api/v1/reviewer-reviews`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    user_id | string |  optional  | Only alpha-numeric characters allowed

<!-- END_d045b8f72b5dcb49da5193f2c9316efe -->

