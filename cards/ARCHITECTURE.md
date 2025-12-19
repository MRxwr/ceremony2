# System Architecture: Web-Only Loyalty Wallet Platform

## 1. High-Level Overview

The platform is a unified web ecosystem built on **Next.js** (Frontend) and **Firebase** (Backend). It serves three distinct user personas through a single domain, separated by route groups and role-based access control.

### Components

1.  **Client Web App (PWA)**
    *   **URL**: `/` or `/wallet`
    *   **Tech**: Next.js, Tailwind CSS, PWA Manifest
    *   **Features**: Card Wallet, QR Identity, Store Discovery (Maps), News Feed.
    *   **Auth**: Phone Auth (Firebase).

2.  **Merchant Web Portal**
    *   **URL**: `/merchant`
    *   **Tech**: Next.js, html5-qrcode (Scanner)
    *   **Features**: QR Scanner, Point/Stamp Transaction, Store Management, Analytics.
    *   **Auth**: Phone Auth + Custom Claims (`role: merchant`).

3.  **Super Admin Panel**
    *   **URL**: `/admin`
    *   **Tech**: Next.js, Data Tables
    *   **Features**: Store Approvals, CMS (Legal/News), User Support, Audit Logs.
    *   **Auth**: Phone Auth + Custom Claims (`role: admin`).

4.  **Backend (Firebase)**
    *   **Auth**: Firebase Authentication (Phone Provider).
    *   **Database**: Cloud Firestore (NoSQL).
    *   **Storage**: Firebase Storage (Images).
    *   **Logic**: Cloud Functions for Firebase (Node.js) for secure transactions and triggers.
    *   **Hosting**: Firebase Hosting (serves the Next.js app).

## 2. Data Flow

1.  **User Identity**: User signs in via Phone. Firebase Auth returns a UID.
2.  **QR Code**: The Client App generates a QR code containing the user's `qr_id` (a secure, immutable reference mapped to the UID).
3.  **Scanning**: Merchant scans the QR code. The Merchant App sends the `qr_id` and transaction details to a **Cloud Function**.
4.  **Transaction**:
    *   Cloud Function validates Merchant permissions.
    *   Resolves `qr_id` to `uid`.
    *   Reads Store Rules (e.g., 1 stamp per visit).
    *   Updates `loyalty_data` collection atomically.
    *   Writes to `audit_logs`.
5.  **Real-time Update**: The Client App listens to `loyalty_data/{user_store_id}` and updates the UI instantly.

## 3. Security Model

*   **Firestore Rules**: strict `read` access based on `request.auth.uid`. `write` access is blocked for sensitive collections (loyalty balances) and only allowed via Cloud Functions (Admin SDK).
*   **Cloud Functions**: Act as the trusted environment to perform business logic (adding points, redeeming rewards).

---

## 4. Firestore Schema

### `users`
```json
{
  "uid": "string (PK)",
  "phone": "string",
  "name": "string",
  "qr_id": "string (unique, indexed)",
  "role": "user | merchant | admin",
  "created_at": "timestamp"
}
```

### `stores`
```json
{
  "store_id": "string (PK)",
  "owner_uid": "string",
  "name": "string",
  "logo_url": "string",
  "brand_color": "string",
  "status": "pending | active | rejected",
  "reward_logic": {
    "type": "stamps | points",
    "goal": 10,
    "value_per_unit": 1 // e.g. 1 stamp per visit
  },
  "location": { "lat": number, "lng": number }
}
```

### `loyalty_data`
```json
{
  "id": "user_store_id (PK: {uid}_{store_id})",
  "uid": "string",
  "store_id": "string",
  "points": 0,
  "stamps": 0,
  "last_updated": "timestamp"
}
```

### `system_content`
```json
{
  "id": "doc_id",
  "type": "terms | privacy | news",
  "title": "string",
  "content": "string",
  "target_store_id": "string (optional)",
  "published_at": "timestamp"
}
```

### `audit_logs`
```json
{
  "id": "auto_id",
  "action": "add_points | redeem | create_store",
  "actor_uid": "string",
  "target_uid": "string",
  "store_id": "string",
  "details": { "amount": 10, "reason": "..." },
  "timestamp": "timestamp"
}
```
