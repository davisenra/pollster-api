> **Pollster**, the polling API.

* This is a personal lab to learn/explore Laravel framework.
* Currently this repository only holds the back-end code, but there's also a Vue.js client.
* Here i'm dabbling with topics such as:
  * Unit tests
  * Queues with Redis
  * Jobs/Notifications
  * API Resources
  * Laravel Sail
  * Seeders/factories
  * Custom Exceptions

### Features:

* Guests can create polls.
  * Optional: the owner can set an expiration date.
  * Optional: an email can be included for receiving notifications related to the poll.
  * Polls can be shared with other people.
  * Polls must include at least two options.
* Guests can vote.
  * Only one vote is allowed per IP.
  * Voting on expired polls is not allowed.
* Guests can see poll results.

---

### Instructions to run locally:

`WIP`

---

## API endpoints:

| HTTP Verbs | Endpoints          | Action                                               |
|------------|--------------------|------------------------------------------------------|
| GET        | /api/v1/polls      | Returns a paginated index of all polls               | 
| GET        | /api/v1/polls/{id} | Returns the requested poll                           | 
| POST       | /api/v1/polls      | Creates a new poll                                   | 
| DELETE     | /api/v1/polls/{id} | Deletes the selected poll                            | 
| POST       | /api/v1/vote       | Vote on the poll/option included in the request body |

## Examples:

### GET /api/v1/polls

<details>
  <summary>Response</summary>

  ```json
  {
    "data": [
        {
            "id": 1,
            "title": "Aut saepe nulla qui autem.",
            "email": "jared42@example.org",
            "expired": false,
            "expires_at": "2023-01-04T16:00:21.000000Z",
            "created_at": "2022-12-29T22:28:01.000000Z",
            "updated_at": "2022-12-29T22:28:01.000000Z"
        },
        {
            "id": 2,
            "title": "Quo error et explicabo molestiae odit aut perferendis dolorum.",
            "email": "deonte.kuhlman@example.net",
            "expired": false,
            "expires_at": "2023-01-12T10:19:15.000000Z",
            "created_at": "2022-12-29T22:28:01.000000Z",
            "updated_at": "2022-12-29T22:28:01.000000Z"
        }
    ],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://localhost/api/v1/polls",
        "per_page": 20,
        "to": 2,
        "total": 2
    }
}
  ```
</details>

---

### GET /api/v1/polls/1

**Parameters (endpoint):** 

* id (required|int)

<details>
  <summary>Response</summary>

  ```json
  {
    "data": {
        "id": 1,
        "title": "Aut saepe nulla qui autem.",
        "email": "jared42@example.org",
        "expired": false,
        "total_votes": 5,
        "options": [
            {
                "id": 1,
                "option": "Sequi optio et quis.",
                "votes": 2
            },
            {
                "id": 2,
                "option": "Corrupti rem aut.",
                "votes": 3
            }
        ],
        "expires_at": "2023-01-04T16:00:21.000000Z",
        "created_at": "2022-12-29T22:28:01.000000Z",
        "updated_at": "2022-12-29T22:28:01.000000Z"
    }
}
  ```
</details>

---

### POST /api/v1/polls

**Parameters (request body):** 

* title (required | string)
* options (required | array[...strings] | min:2 | max:10)
* email (optional | string)
* expires_at (optional | string | datetime (Y-m-d H:i:s))

<details>
  <summary>Request</summary>

  ```json
  {
    "title": "Best movie ever",
    "options": [
        "The Tree of Life",
        "The Matrix"
    ],
    "email": "foo@bar.com",
    "expires_at": "2036-01-01 00:00:00"
  }
  ```
</details>

<details>
  <summary>Response</summary>

  ```json
  {
    "data": {
        "id": 3,
        "title": "Best movie ever",
        "email": "foo@bar.com",
        "expired": false,
        "total_votes": 0,
        "options": [
            {
                "id": 48,
                "option": "The Tree of Life",
                "votes": 0
            },
            {
                "id": 49,
                "option": "The Matrix",
                "votes": 0
            }
        ],
        "expires_at": "2036-01-01T00:00:00.000000Z",
        "created_at": "2022-12-29T22:28:01.000000Z",
        "updated_at": "2022-12-29T22:28:01.000000Z"
    }
}
  ```
</details>

---

### DELETE /api/v1/polls/3

**Parameters (endpoint):** 

* id (required|int)

<details>
  <summary>Request</summary>

  ```json
  {
    "message": "Poll deleted successfully"
  }
  ```
</details>

---

### POST /api/v1/vote

**Parameters (request body):**

* poll (required | int | must be a valid poll id)
* option (required | int | must be a valid option id)

<details>
  <summary>Request</summary>

  ```json
  {
    "poll": 3,
    "option": 49
  }
  ```
</details>

<details>
  <summary>Response</summary>

  ```json
  {
    "message": "Vote registered successfully"
  }
  ```
</details>

---
