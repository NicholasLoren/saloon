# Matilda's Salon & Spa — System Overview

**Project:** Online Beauty Salon Management System  
**Institution:** Makerere Institute of Technology  
**Database:** MySQL — `saloon_online` (utf8mb4)  
**Stack:** PHP (custom MVC), Tailwind CSS, Vanilla JS  

---

## Database Schema

### 1. `users`
Stores login accounts for admin and staff only. Clients do not register.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `name` | VARCHAR(100) | Full name |
| `email` | VARCHAR(100) UNIQUE | Login credential |
| `password` | VARCHAR(255) | bcrypt hashed |
| `phone` | VARCHAR(20) | |
| `role` | ENUM(`admin`, `staff`) | Defaults to `staff` |
| `created_at` | TIMESTAMP | |

---

### 2. `services`
Beauty services offered by the salon, displayed on the public website and selectable during booking.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `name` | VARCHAR(100) | e.g. "Box Braids" |
| `description` | TEXT | |
| `price` | DECIMAL(10,2) | In UGX |
| `duration_minutes` | INT | Used for slot calculation |
| `category` | VARCHAR(50) | Hair, Nails, Skin, Beauty |
| `image` | VARCHAR(255) | Path to uploaded image |
| `active` | TINYINT(1) | 1 = visible on website |
| `created_at` | TIMESTAMP | |

---

### 3. `staff`
Extended profile for users with role `staff` or `admin`. Linked 1-to-1 with `users`.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `user_id` | INT FK → `users.id` | Cascades on delete |
| `specialization` | VARCHAR(100) | e.g. "Hair Styling" |
| `bio` | TEXT | Shown on booking page |
| `available` | TINYINT(1) | 1 = selectable in booking |

---

### 4. `staff_availability`
Defines which hours each staff member works on each day of the week.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `staff_id` | INT FK → `staff.id` | Cascades on delete |
| `day_of_week` | TINYINT | 0 = Sunday … 6 = Saturday |
| `start_time` | TIME | e.g. `09:00:00` |
| `end_time` | TIME | e.g. `18:00:00` |

---

### 5. `appointments`
Core booking records. Clients do not need an account — name, email, and phone are stored directly.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `client_name` | VARCHAR(100) | Guest name |
| `client_email` | VARCHAR(100) | Used to group client history |
| `client_phone` | VARCHAR(20) | |
| `service_id` | INT FK → `services.id` | |
| `staff_id` | INT FK → `staff.id` | Nullable — SET NULL on delete |
| `appointment_date` | DATE | Must be a future date |
| `appointment_time` | TIME | Validated against availability |
| `status` | ENUM | `pending`, `confirmed`, `completed`, `cancelled` |
| `notes` | TEXT | Client notes |
| `created_at` | TIMESTAMP | |

---

### 6. `payments`
One payment record per appointment, auto-created when a booking is made.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `appointment_id` | INT FK → `appointments.id` | |
| `amount` | DECIMAL(10,2) | Copied from service price |
| `payment_method` | ENUM | `cash`, `mobile_money`, `card` |
| `status` | ENUM | `pending`, `paid`, `refunded` |
| `invoice_number` | VARCHAR(30) UNIQUE | Auto-generated (INV-YYYYMMDD-XXXXX) |
| `notes` | TEXT | |
| `paid_at` | TIMESTAMP NULL | Set when marked paid |
| `created_at` | TIMESTAMP | |

---

### 7. `inventory`
Tracks salon product stock levels and triggers low-stock alerts on the dashboard.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `product_name` | VARCHAR(100) | |
| `category` | VARCHAR(50) | Hair Care, Nails, Skin Care, Supplies… |
| `quantity` | DECIMAL(10,2) | Current stock |
| `unit` | VARCHAR(20) | pcs, bottles, liters, kg, packs… |
| `reorder_level` | DECIMAL(10,2) | Alert threshold |
| `cost_price` | DECIMAL(10,2) | Per unit in UGX |
| `supplier` | VARCHAR(100) | |
| `last_updated` | TIMESTAMP | Auto-updated on row change |

---

### 8. `promotions`
Time-bound discount promotions displayed on the public website.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `title` | VARCHAR(100) | |
| `description` | TEXT | |
| `discount_percent` | DECIMAL(5,2) | e.g. `20.00` for 20% off |
| `start_date` | DATE | |
| `end_date` | DATE | |
| `active` | TINYINT(1) | Manual on/off toggle |
| `created_at` | TIMESTAMP | |

---

### 9. `faqs`
Frequently asked questions shown on the public FAQ page, grouped by category.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `question` | TEXT | |
| `answer` | TEXT | |
| `category` | VARCHAR(50) | Booking, Services, General… |
| `active` | TINYINT(1) | 1 = visible on website |
| `sort_order` | INT | Lower number = shown first |

---

### 10. `contact_messages`
Messages submitted via the public Contact Us page.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `name` | VARCHAR(100) | Sender name |
| `email` | VARCHAR(100) | |
| `phone` | VARCHAR(20) | |
| `subject` | VARCHAR(100) | |
| `message` | TEXT | |
| `is_read` | TINYINT(1) | 0 = unread |
| `created_at` | TIMESTAMP | |

---

### 11. `notifications`
Internal admin notifications auto-created when bookings or messages arrive.

| Column | Type | Notes |
|---|---|---|
| `id` | INT PK AUTO | |
| `type` | ENUM | `appointment`, `message`, `payment`, `system` |
| `title` | VARCHAR(150) | Short headline |
| `body` | TEXT | Optional detail |
| `url` | VARCHAR(255) | Dashboard URL to redirect to on click |
| `is_read` | TINYINT(1) | 0 = unread |
| `created_at` | TIMESTAMP | |

---

## Table Relationships

```
users ──< staff ──< staff_availability
             │
             └──< appointments >── services
                       │
                       └──< payments

contact_messages ──> notifications (auto-created)
appointments     ──> notifications (auto-created)
```

---

## System Functionalities

### Public Website

| Feature | Description |
|---|---|
| **Home** | Hero section, featured services, about snippet, promotions banner, testimonials, call-to-action |
| **Services** | Full service catalogue with prices, durations, and categories |
| **Book Appointment** | Guest booking form — select service, preferred stylist, date & time; available time slots loaded via AJAX based on staff availability and existing bookings |
| **About** | Salon story, team values, stats |
| **FAQ** | Accordion-style questions grouped by category, managed from dashboard |
| **Contact** | Contact form (saved to `contact_messages`), location, phone, email, hours |

---

### Dashboard (Admin / Staff)

#### Authentication
- Session-based login with bcrypt password verification
- Role-aware access: `admin` sees all sections; `staff` logs in to manage bookings
- Logout clears session

#### Appointments
- Paginated list with sort, filter by status/date, search by client name
- Create appointment manually (admin/staff)
- View appointment detail
- Update appointment status: `pending → confirmed → completed / cancelled`
- Delete appointment

#### Services
- CRUD with image upload (JPEG/PNG/WebP, max 5 MB)
- Toggle active/inactive (hides from public website)
- Category grouping

#### Staff
- CRUD — creates both a `users` record and a `staff` profile in one form
- Role assignment (`admin` or `staff`)
- Set specialization, bio, availability status

#### Payments
- Auto-created at booking time from service price
- Mark as paid (records `paid_at` timestamp, sets payment method)
- Invoice number auto-generated per payment
- View payment detail with appointment summary

#### Clients
- Derived view — no client registration required
- Groups all `appointments` by `client_email` to show booking history per client

#### Inventory
- CRUD for salon products
- Low-stock alert: items where `quantity <= reorder_level` highlighted in red
- Dashboard stat card shows count of low-stock items

#### Promotions
- CRUD with date range and discount percentage
- Status badge: Active / Upcoming / Expired (derived from dates + `active` flag)

#### FAQs
- CRUD with category, sort order, active toggle
- Displayed grouped by category on public FAQ page

#### Messages
- View all contact form submissions
- Mark as read on open
- Reply via Email or WhatsApp deep-link
- Delete message

#### Notifications
- Auto-created when: a new booking is submitted, a new contact message arrives
- Dashboard bell icon and sidebar badge show unread count
- Click a notification → marks it read and redirects to the relevant record
- Bulk actions: Mark all read, Delete read, Clear all
- Grouped by Today / Yesterday / date label

#### Dashboard Home
- Stat cards: Today's bookings, Pending appointments, Active services, Low-stock items, Unread messages
- Revenue cards: Total revenue, This month's revenue
- Recent appointments table (last 10)
- Revenue trend line chart (last 6 months)
- Quick-action buttons

---

## Default Credentials (after running `setup.php`)

| Role | Email | Password |
|---|---|---|
| Admin | admin@matildassalon.com | admin123 |
| Staff | sarah@matildassalon.com | staff123 |

> Change these immediately after first login. Delete or restrict `setup.php` in production.
