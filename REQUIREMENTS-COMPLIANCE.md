# E-Commerce System Requirements Compliance

This document maps the implemented system to the teacher's required features.

## A. CRUD Modules

- Products (implemented as `menu` / `menu_items`)
  - Admin Create/Read/Update/Delete: `admin/menu`
- Users
  - Admin Create/Read/Update/Delete: `admin/users`
- Orders
  - Create: customer checkout/place order
  - Read: customer order history + admin order pages
  - Update: admin status update
  - Delete: admin delete order
- Categories
  - Admin Create/Read/Update/Delete: `admin/categories`

## B. Google Authentication (OAuth)

- Login with Google: `/auth/google`
- Logout: `/logout`
- Store Google user data: `google_id`, `google_avatar`, `auth_provider` on `users` table
- Link Google account to user profile: callback links by email when account already exists

## C. Account Types

- User Features
  - Register/Login (local + Google)
  - Browse products/menu
  - Add to cart
  - Place orders
  - View order history/details
  - Update profile (`/profile`)
- Admin Features
  - Dashboard
  - Manage products
  - Manage users
  - View/manage orders
  - Manage categories

## D. Database

- Database used: MySQL
- Stored entities:
  - users
  - menu_items (products)
  - orders
  - categories
  - admin accounts (users with `role=admin`)

## E. Graphic/UI Requirements

- Responsive multi-page interface implemented (customer + admin pages)
- Consistent layouts and interactive forms/tables/actions
- Uses modern utility and component styling with responsive behavior

## F. Suggested Technologies

- Frontend: Bootstrap (admin area) and responsive styling in customer area
- Backend: PHP (Laravel framework)
- Database: MySQL
- API: Google OAuth API (web-server flow)

## Demo Checklist (for 5-10 min video)

1. User registration/login
2. Google login
3. Browse menu/products
4. Add to cart and place order
5. User order history and profile update
6. Admin login and dashboard
7. Admin CRUD on products/users/categories
8. Admin order status update and delete
9. Quick look at database tables in phpMyAdmin
