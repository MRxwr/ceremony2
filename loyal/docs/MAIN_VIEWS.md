# Main Views System Documentation

## How the Main Views Work

The main entry point for the application is `index.php` in the root directory. This file controls the loading of views and the flow of the user experience based on URL parameters and database lookups.

### 1. Configuration and Functions
- The script starts by including the main configuration and function files:
  - `dashboard/includes/config.php`
  - `dashboard/includes/functions.php`

### 2. Event and Invitee Validation
- The system checks for a `systemCode` parameter in the request (GET or POST).
- It queries the `events` table for a matching, active, and visible event.
- If found, it loads the event and its category.
- If the `i` parameter is present, it checks for a matching invitee for the event.
- If any check fails, the user is redirected to `default.php`.

### 3. Page Structure
- The header template (`template/header.php`) is always included first.

### 4. Dynamic View Selection
- If the URL contains a `v` parameter (e.g., `?v=Home`), the system looks for a corresponding view file in the `views` folder named `blade{v}.php` (e.g., `bladeHome.php`).
- The function `searchFile("views", "blade{$_GET["v"]}.php")` is used to verify the file exists.
- If found, it is included using `require_once`.
- If not found or `v` is not set, the default view `bladeHome.php` is loaded.

### 5. Footer Inclusion
- The footer template (`template/footer.php`) is always included last to complete the page structure.


## Example
---

## Database Table Schema Standard

When creating new modules (like categories, banners, pages, etc.), follow this standard schema as a base, and extend as needed for the module's requirements. This ensures consistency, maintainability, and scalability across the system.

### Example: Standard Table Schema for Modules

| Column      | Type           | Description                       |
|-------------|----------------|-----------------------------------|
| id          | INT, AUTO_INCREMENT, PRIMARY KEY | Unique identifier |
| date        | TIMESTAMP, DEFAULT CURRENT_TIMESTAMP | Creation date/time |
| enTitle     | VARCHAR(255)   | English title                     |
| arTitle     | VARCHAR(255)   | Arabic title                      |
| enDetails   | LONGTEXT       | English details                   |
| arDetails   | LONGTEXT       | Arabic details                    |
| hidden      | INT            | Hidden flag (0/1/2) (1=visible, 2=hidden) |
| status      | INT            | Status flag (0=active, 1=deleted/archived) |

#### Extending the Schema for Specific Modules

- **Add module-specific fields:** For example, the `categories` table adds `rank`, `imageurl`, and `header` fields:
  - `rank` (INT): Used for ordering categories in the UI.
  - `imageurl` (VARCHAR): Stores the filename or path of the logo image.
  - `header` (VARCHAR): Stores the filename or path of the header/banner image.
- **For other modules:** Add fields relevant to the module's function (e.g., `link` for banners, `content` for pages, etc.).

#### Best Practices for Module Table Design

- **Always include** the base fields (`id`, `date`, `enTitle`, `arTitle`, `enDetails`, `arDetails`, `hidden`, `status`) for consistency.
- **Use `status` for soft deletes** instead of removing records, to allow for recovery and auditing.
- **Use `hidden` for visibility control** (e.g., 1=visible, 2=hidden), so items can be toggled without deletion.
- **Store images as filenames/paths** (not blobs) and keep images in organized folders (e.g., `/logos/categories/`).
- **Use `rank` or similar fields** for modules that require ordering (categories, banners, etc.).
- **URL-encode/decode text fields** when saving/loading to support special characters and multi-language content.
- **Add indexes** on frequently queried fields (e.g., `status`, `hidden`, `rank`) for performance.
- **Document any additional fields** in the code and documentation for clarity.

#### Example: Categories Table (Extended)

| Column      | Type           | Description                       |
|-------------|----------------|-----------------------------------|
| id          | INT, AUTO_INCREMENT, PRIMARY KEY | Unique identifier |
| date        | TIMESTAMP, DEFAULT CURRENT_TIMESTAMP | Creation date/time |
| enTitle     | VARCHAR(255)   | English title                     |
| arTitle     | VARCHAR(255)   | Arabic title                      |
| enDetails   | LONGTEXT       | English details                   |
| arDetails   | LONGTEXT       | Arabic details                    |
| hidden      | INT            | Hidden flag (1=visible, 2=hidden) |
| status      | INT            | Status flag (0=active, 1=deleted) |
| rank        | INT            | Category order                    |
| imageurl    | VARCHAR(255)   | Logo image filename               |
| header      | VARCHAR(255)   | Header image filename             |

#### Extensibility Notes

- When creating a new module, start with the standard schema and add only the fields necessary for that module's functionality.
- Keep naming conventions consistent (e.g., `enTitle`, `arTitle`, `enDetails`, `arDetails`).
- For relationships (e.g., subcategories), use foreign keys (e.g., `parent_id`).
- Update documentation and code comments whenever the schema is extended.

**All future tables should use this schema as a foundation, extending it as needed for each module.**
Suppose the user accesses:
```
/index.php?systemCode=ABC123&i=INV001&v=Categories
```
- The system will:
  1. Validate the event with code `ABC123` and invitee with code `INV001`.
  2. Look for `views/bladeCategories.php` and include it if it exists.
  3. If not, it will fall back to `views/bladeHome.php`.
  4. The page will always have the header and footer templates.

#### Code Flow Example
```php
require_once("dashboard/includes/config.php");
require_once("dashboard/includes/functions.php");

if( isset($_REQUEST["systemCode"]) && ... ){
    // Validate event and invitee
}else{
    header("Location: default.php");die();
}

require_once("template/header.php");

if( isset($_GET["v"]) && searchFile("views","blade{$_GET["v"]}.php") ){
    require_once("views/".searchFile("views","blade{$_GET["v"]}.php"));
}else{
    require_once("views/bladeHome.php");
}

require_once("template/footer.php");
```

---

## Detailed Example: bladeCategories.php and Database Interaction

The view file `bladeCategories.php` manages the categories table in the database. It provides CRUD operations and UI for category management.

### How bladeCategories.php Works

- **Hide/Show Category:**
  - If `?hide={id}` is set, the category's `hidden` field is updated to `2` (hidden).
  - If `?show={id}` is set, the category's `hidden` field is updated to `1` (visible).
- **Delete Category:**
  - If `?delId={id}` is set, the category's `status` field is updated to `1` (deleted/archived).
- **Update Rank:**
  - On POST with `updateRank`, the ranks of categories are updated in bulk using the `rank` and `id` arrays.
- **Add/Edit Category:**
  - On POST with category details, if `update` is `0`, a new category is inserted. Otherwise, the existing category is updated.
  - Image uploads use `uploadImageBannerFreeImageHost`, saving images to `/logos/categories/`.
  - Details and titles are URL-encoded before saving.
- **Display Categories:**
  - The table lists all categories with `status = 0` (active), showing their English/Arabic titles, rank, and actions.
  - Edit, hide/show, and delete actions are available for each category.

### Database Table Schema Used

The `categories` table follows the standard schema:

| Column      | Type           | Description                       |
|-------------|----------------|-----------------------------------|
| id          | INT, AUTO_INCREMENT, PRIMARY KEY | Unique identifier |
| date        | TIMESTAMP, DEFAULT CURRENT_TIMESTAMP | Creation date/time |
| enTitle     | VARCHAR(255)   | English title                     |
| arTitle     | VARCHAR(255)   | Arabic title                      |
| enDetails   | LONGTEXT       | English details                   |
| arDetails   | LONGTEXT       | Arabic details                    |
| hidden      | INT            | Hidden flag (1=visible, 2=hidden) |
| status      | INT            | Status flag (0=active, 1=deleted) |
| rank        | INT            | Category order                    |
| imageurl    | VARCHAR(255)   | Logo image filename               |
| header      | VARCHAR(255)   | Header image filename             |

### Example Workflow

1. User visits `/index.php?systemCode=ABC123&i=INV001&v=Categories`.
2. The system loads `views/bladeCategories.php`.
3. The view displays a form for adding/editing categories and a table of existing categories.
4. User submits the form to add a new category:
  - Data is validated and images are uploaded.
  - The new category is inserted into the `categories` table.
5. User clicks to hide, show, or delete a category:
  - The corresponding field (`hidden` or `status`) is updated in the database.
6. User updates category ranks and submits:
  - The ranks are updated for each category in the table.

This workflow demonstrates how the view and database schema work together for category management.

---

## Notes
- All main view files follow the naming convention: `blade{Name}.php`.
- Views are stored in the `views/` directory.
- The system ensures only valid events and invitees can access the main content.
- The header and footer templates provide a consistent layout for all views.

---

_Last updated: December 19, 2025_
