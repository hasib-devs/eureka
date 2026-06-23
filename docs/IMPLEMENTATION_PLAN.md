# Fraud Protection & Order System Enhancement Plan

## 1. Fix Existing Fraud Checker [COMPLETED]
**Analysis**: The `fraud_checker` method in `Admin\Ecommerce\OrderController.php` attempts to return a view `admin.order.fraud_checker` on success, but the file is located at `admin.e-commerce.order.fraud_checker`. The error catch block correctly points to `admin.e-commerce.order.fraud_checker`.
**Action**:
- [x] Update `Admin\Ecommerce\OrderController.php` to use the correct view path: `admin.e-commerce.order.fraud_checker`.
- [x] **Enhancement**: Implemented Hybrid Fraud Checker Report.
    - [x] Added `BDCOURIER_API_KEY` setting in Admin Panel.
    - [x] Added Local Order History lookup (using fuzzy phone matching).
    - [x] Combined Local + External API data in the report.
    - [x] Solved "API call limit reached" error by falling back to local data.

## 2. IP Block System [COMPLETED]
**Goal**: Allow admins to block specific IP addresses from placing orders.
**Steps**:
1.  **Database**: [x] Create a migration for `ip_blocks` table with fields: `id`, `ip_address` (string, unique), `reason` (text, nullable), timestamps.
2.  **Model**: [x] Create `App\Models\IpBlock` model.
3.  **Admin Interface**:
    - [x] Create `Admin\IpBlockController` to list, add, and delete blocked IPs.
    - [x] Add routes in `routes/admin.php` (or `web.php` under admin prefix).
    - [x] Create views for listing/adding IPs.
    - [x] **Fix**: Resolved `MassAssignmentException` when creating blocks.
4.  **Enforcement**:
    - [x] In `Frontend\OrderController`, add a check at the beginning of order placement methods (`orderStore`, `orderStore_minimal`, etc.).
    - [x] Check if `request()->ip()` exists in `ip_blocks` table.
    - [x] If blocked, abort with 403 or redirect with error.
    - [x] **Enhancement**: Added explicit Popup Error Message in checkout views (`c_minimal`, `c_guest`) via Session flash data.
    - [x] **Fix**: Patched all order methods (including "Buy Now") to correctly handle Redirect responses from the fraud check.

## 3. Order Duplicate Handling [COMPLETED]
**Goal**: Prevent multiple orders from the same user/IP in a short timeframe (prevention of accidental double-clicks or bot spam).
**Steps**:
1.  **Logic**: [x] Define a time threshold (e.g., 5 minutes).
2.  **Implementation**:
    - [x] In `Frontend\OrderController`, before creating an order:
        - Query `Order` table for the latest order where `ip_address` (if stored) or `phone` matches the current request.
        - **Note**: The `orders` table migration needs to be checked if it has `ip_address`. If not, we should add it or rely on `phone`/`user_id`. (Migration `2025_09_23_050916_create_orders_table.php` exists, check content).
        - [x] If the latest order was created < 5 minutes ago, reject the request with error message "You have placed an order recently. Please wait 5 minutes before placing another."
    - [x] Applies to: `orderStore`, `orderStore_guest`, `orderStore_minimal`, `orderBuyNowStore`.
