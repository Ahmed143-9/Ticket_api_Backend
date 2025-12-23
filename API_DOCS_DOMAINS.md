# Domain Status Management API Documentation

**Base URL**: `https://ticketapi.wineds.com`
**Prefix**: `/api/domains`
**Full Base Endpoint**: `https://ticketapi.wineds.com/api/domains`

This documentation outlines the endpoints for managing domain monitoring statuses.

---

## 1. Get All Domain Statuses
Retrieves the list of all monitored domains and their current statuses.

- **Full URL**: `https://ticketapi.wineds.com/api/domains/status`
- **Method**: `GET`
- **Authentication**: None (currently)
- **Response**: `200 OK`

### Success Response Example
```json
{
    "https://stlautomationtechnology.com": {
        "id": 1,
        "is_up": false,
        "is_active": true,
        "updated_at": "2025-12-23 12:47:59",
        "last_checked_at": null
    },
    "https://facebook.com": {
        "id": 2,
        "is_up": true,
        "is_active": true,
        "updated_at": "2025-12-23 14:31:20",
        "last_checked_at": "2025-12-23 14:30:00"
    }
}
```

---

## 2. Add New Domain (Store)
Adds a new domain to be monitored.

- **Full URL**: `https://ticketapi.wineds.com/api/domains/store`
- **Method**: `POST`
- **Headers**:
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`

### Body Parameters (JSON)
| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `domain` | string | **Yes** | Must be a valid full URL (e.g., `https://example.com`) and unique. |
| `is_active` | boolean | No | Defaults to `true`. Set `false` to add without starting monitoring. |

### Request Body Example
```json
{
    "domain": "https://new-site.com",
    "is_active": true
}
```

### Success Response (201 Created)
```json
{
    "message": "Domain added successfully",
    "data": {
        "domain": "https://new-site.com",
        "is_active": true,
        "is_up": false,
        "last_checked_at": null,
        "updated_at": "2025-12-23 15:00:00",
        "created_at": "2025-12-23 15:00:00",
        "id": 5
    }
}
```

---

## 3. Update Domain
Updates an existing domain's URL or active status.

- **Full URL**: `https://ticketapi.wineds.com/api/domains/{id}`
- **Method**: `PUT`
- **URL Parameter**: `{id}` is the **ID** of the domain (e.g., `3`). **It must be in the URL path.**
    - ✅ Correct: `https://ticketapi.wineds.com/api/domains/3`
    - ❌ Incorrect: `https://ticketapi.wineds.com/api/domains?id=3`
- **Headers**:
  - `Content-Type`: `application/json`
  - `Accept`: `application/json`

### Body Parameters (JSON)
| Parameter | Type | Required | Description |
| :--- | :--- | :--- | :--- |
| `domain` | string | No | New URL. Must be valid and unique (ignoring itself). |
| `is_active` | boolean | No | `true` or `false`. |

### Request Body Example (Update everything)
```json
{
    "domain": "https://updated-domain-name.com",
    "is_active": true
}
```

### Request Body Example (Disable monitoring only)
```json
{
    "is_active": false
}
```

### Success Response (200 OK)
```json
{
    "message": "Domain updated successfully",
    "data": {
        "id": 3,
        "domain": "https://updated-domain-name.com",
        "is_active": true,
        "is_up": false,
        "last_checked_at": null,
        "created_at": "...",
        "updated_at": "..."
    }
}
```

### Not Found Response (404)
If the ID provided in the URL does not exist:
```json
{
    "message": "Domain not found"
}
```

---

## 4. Delete Domain
Permanently removes a domain.

- **Full URL**: `https://ticketapi.wineds.com/api/domains/{id}`
- **Method**: `DELETE`
- **URL Parameter**: `{id}` is the ID of the domain.
    - ✅ Correct: `https://ticketapi.wineds.com/api/domains/3`
- **Headers**:
  - `Accept`: `application/json`

### Success Response (200 OK)
```json
{
    "message": "Domain deleted successfully"
}
```
