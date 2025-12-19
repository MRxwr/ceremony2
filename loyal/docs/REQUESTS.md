# Requests System Documentation

## How the Requests System Works

The `requests` directory provides an API-like interface for the application, dynamically loading PHP files based on the `a` query parameter in the URL. The main entry point is `requests/index.php`.

### 1. Configuration and Functions
- The script first includes the main configuration and functions from the dashboard:
  - `../dashboard/includes/config.php`
  - `../dashboard/includes/functions.php`

### 2. Dynamic API View Selection
- If the URL contains an `a` parameter (e.g., `?a=Events`), the system checks for a corresponding file in the `views` subfolder named `api{a}.php` (e.g., `apiEvents.php`).
- The function `searchFile("views", "api{$_GET["a"]}.php")` is used to verify the file exists.
- If found, it is included using `require_once`.
- If not found or `a` is not set, the default view `apiHome.php` is loaded.

### 3. Example
Suppose the user accesses:
```
/requests/index.php?a=Invitees
```
- The system will look for `requests/views/apiInvitees.php`.
- If the file exists, it will be included and executed.
- If not, it will fall back to `requests/views/apiHome.php`.

#### Code Flow Example
```php
if( isset($_GET["a"]) && searchFile("views","api{$_GET["a"]}.php") ){
    require_once("views/".searchFile("views","api{$_GET["a"]}.php"));
}else{
    require_once("views/apiHome.php");
}
```

---

## Notes
- All API view files follow the naming convention: `api{Name}.php`.
- API views are stored in the `requests/views/` directory.
- This system allows easy addition of new API endpoints by simply creating a new `api{Name}.php` file.
- The logic is similar to the dashboard's view system, but focused on API/data responses.

---

_Last updated: December 19, 2025_
