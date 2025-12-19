# Views System Documentation

## How Views Work

The dashboard uses a dynamic view loading system based on the URL query parameter `v`. The main entry point is `dashboard/index.php`, which determines which view to display:

1. **Header Inclusion**
   - The file `template/header.php` is always included first for consistent layout and assets.

2. **Dynamic View Selection**
   - If the URL contains a `v` parameter (e.g., `?v=Home`), the system checks for a corresponding view file in the `views` folder named `blade{v}.php` (e.g., `bladeHome.php`).
   - The function `searchFile("views", "blade{$_GET["v"]}.php")` is used to verify the file exists.
   - If found, it is included using `require_once`.
   - If not found or `v` is not set, the default view `bladeHome.php` is loaded.

3. **Footer Inclusion**
   - The file `template/footer.php` is always included last to complete the page structure.

---

## Example

Suppose the user navigates to:
```
/dashboard/index.php?v=Categories
```
- The system will look for `views/bladeCategories.php`.
- If the file exists, it will be included and rendered.
- If not, it will fall back to `views/bladeHome.php`.

### Code Flow Example
```php
// ...existing code...
if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
    require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
    require_once("views/bladeHome.php");
}
// ...existing code...
```

---

## Notes
- All view files follow the naming convention: `blade{Name}.php`.
- Views are stored in the `dashboard/views/` directory.
- This system allows easy addition of new views by simply creating a new `blade{Name}.php` file.
- The header and footer templates ensure consistent look and feel across all views.

---

_Last updated: December 19, 2025_
