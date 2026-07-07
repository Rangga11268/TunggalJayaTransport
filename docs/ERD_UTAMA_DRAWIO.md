# ERD Utama TunggalJayaTransport

Dokumen ini fokus pada tabel bisnis utama (non-bawaan Laravel), termasuk tabel package yang memang dipakai langsung oleh fitur aplikasi.

## Scope

- Termasuk: users, buses, routes, schedules, bookings, payment_histories, promo_codes, drivers, conductors, bus_driver, bus_conductor, categories, news_articles, otp_codes.
- Termasuk package penting: roles, permissions, model_has_roles, role_has_permissions, media, notifications.
- Tidak difokuskan: cache, jobs, failed_jobs, sessions, dll.

---

## ERD (Mermaid)

```mermaid
erDiagram
    USERS {
        BIGINT id PK
        VARCHAR name
        VARCHAR email UK
        VARCHAR google_id UK
        VARCHAR phone UK NULL
        TIMESTAMP email_verified_at NULL
        TIMESTAMP phone_verified_at NULL
        BOOLEAN is_verified
        VARCHAR password
        VARCHAR remember_token NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    BUSES {
        BIGINT id PK
        VARCHAR name
        VARCHAR plate_number UK
        VARCHAR bus_type
        INT capacity
        TEXT description NULL
        YEAR year NULL
        ENUM status "active|maintenance|inactive"
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    ROUTES {
        BIGINT id PK
        VARCHAR name
        VARCHAR origin
        VARCHAR destination
        DECIMAL distance NULL
        INT duration NULL
        TEXT description NULL
        DECIMAL origin_lat NULL
        DECIMAL origin_lng NULL
        DECIMAL destination_lat NULL
        DECIMAL destination_lng NULL
        JSON waypoints NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    SCHEDULES {
        BIGINT id PK
        BIGINT bus_id FK
        BIGINT route_id FK
        DATETIME departure_time
        DATETIME arrival_time
        DECIMAL price
        ENUM status "active|cancelled|delayed"
        BOOLEAN is_daily
        JSON days_of_week NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PROMO_CODES {
        BIGINT id PK
        VARCHAR code UK
        TEXT description NULL
        ENUM discount_type "percentage|fixed"
        DECIMAL discount_amount
        DECIMAL max_discount_amount NULL
        DECIMAL min_purchase_amount
        DATETIME start_date NULL
        DATETIME end_date NULL
        INT usage_limit NULL
        INT usage_count
        BOOLEAN is_active
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    BOOKINGS {
        BIGINT id PK
        BIGINT user_id FK NULL
        BIGINT schedule_id FK
        BIGINT promo_code_id FK NULL
        DATE booking_date NULL
        VARCHAR booking_code UK
        VARCHAR passenger_name
        VARCHAR passenger_phone
        VARCHAR passenger_email
        VARCHAR seat_numbers NULL
        INT number_of_seats
        DECIMAL total_price
        DECIMAL discount_amount
        DECIMAL original_total_price NULL
        ENUM payment_status "pending|paid|failed|refunded"
        VARCHAR midtrans_transaction_id NULL
        ENUM booking_status "pending|confirmed|cancelled|completed"
        TIMESTAMP payment_started_at NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PAYMENT_HISTORIES {
        BIGINT id PK
        BIGINT booking_id FK
        VARCHAR transaction_id
        VARCHAR payment_method
        DECIMAL gross_amount
        VARCHAR transaction_status
        VARCHAR fraud_status
        TEXT payment_url NULL
        JSON metadata NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    DRIVERS {
        BIGINT id PK
        VARCHAR employee_id UK
        VARCHAR name
        VARCHAR license_number UK
        VARCHAR phone
        VARCHAR email NULL
        TEXT address NULL
        ENUM status "active|inactive"
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    CONDUCTORS {
        BIGINT id PK
        VARCHAR employee_id UK
        VARCHAR name
        VARCHAR phone
        VARCHAR email NULL
        TEXT address NULL
        ENUM status "active|inactive"
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    BUS_DRIVER {
        BIGINT id PK
        BIGINT bus_id FK
        BIGINT driver_id FK UK
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    BUS_CONDUCTOR {
        BIGINT id PK
        BIGINT bus_id FK
        BIGINT conductor_id FK UK
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    CATEGORIES {
        BIGINT id PK
        VARCHAR name
        VARCHAR slug UK
        TEXT description NULL
        BIGINT parent_id FK NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    NEWS_ARTICLES {
        BIGINT id PK
        VARCHAR title
        VARCHAR slug UK
        TEXT content
        TEXT excerpt NULL
        BOOLEAN is_published
        TIMESTAMP published_at NULL
        BIGINT category_id FK NULL
        BIGINT author_id FK
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    OTP_CODES {
        BIGINT id PK
        VARCHAR phone NULL
        VARCHAR identifier NULL
        VARCHAR method
        VARCHAR otp
        TIMESTAMP expires_at
        BOOLEAN used
        TINYINT attempts
        VARCHAR ip_address NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    ROLES {
        BIGINT id PK
        VARCHAR name
        VARCHAR guard_name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    PERMISSIONS {
        BIGINT id PK
        VARCHAR name
        VARCHAR guard_name
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    MODEL_HAS_ROLES {
        BIGINT role_id FK
        BIGINT model_id
        VARCHAR model_type
    }

    ROLE_HAS_PERMISSIONS {
        BIGINT role_id FK
        BIGINT permission_id FK
    }

    MEDIA {
        BIGINT id PK
        BIGINT model_id
        VARCHAR model_type
        VARCHAR collection_name
        VARCHAR file_name
        VARCHAR mime_type NULL
        BIGINT size
        JSON custom_properties
    }

    NOTIFICATIONS {
        UUID id PK
        VARCHAR type
        BIGINT notifiable_id
        VARCHAR notifiable_type
        TEXT data
        TIMESTAMP read_at NULL
        TIMESTAMP created_at
        TIMESTAMP updated_at
    }

    BUSES ||--o{ SCHEDULES : has
    ROUTES ||--o{ SCHEDULES : has
    USERS ||--o{ BOOKINGS : creates
    SCHEDULES ||--o{ BOOKINGS : booked_in
    PROMO_CODES ||--o{ BOOKINGS : applied_to
    BOOKINGS ||--o{ PAYMENT_HISTORIES : has
    BUSES ||--o{ BUS_DRIVER : mapped
    DRIVERS ||--o| BUS_DRIVER : assigned_once
    BUSES ||--o{ BUS_CONDUCTOR : mapped
    CONDUCTORS ||--o| BUS_CONDUCTOR : assigned_once
    CATEGORIES ||--o{ CATEGORIES : parent_child
    CATEGORIES ||--o{ NEWS_ARTICLES : categorizes
    USERS ||--o{ NEWS_ARTICLES : authors
    ROLES ||--o{ MODEL_HAS_ROLES : grants
    USERS ||--o{ MODEL_HAS_ROLES : assigned
    ROLES ||--o{ ROLE_HAS_PERMISSIONS : has
    PERMISSIONS ||--o{ ROLE_HAS_PERMISSIONS : includes
```

---

## Draw.io Format (CSV Import)

Cara pakai di diagrams.net:

1. Buka diagrams.net
2. Arrange -> Insert -> Advanced -> CSV
3. Paste CSV di bawah
4. Klik Import

```csv
# label: %name%
# style: shape=swimlane;html=1;rounded=1;childLayout=stackLayout;horizontal=0;startSize=28;fillColor=#ffffff;strokeColor=#334155;fontStyle=1;
# connect: {"from":"refs","to":"id","invert":false,"style":"endArrow=block;endFill=1;strokeColor=#64748b;"}
# layout: auto
id,name,refs
users,users,
buses,buses,schedules
routes,routes,schedules
schedules,schedules,bookings
promo_codes,promo_codes,bookings
bookings,bookings,payment_histories
payment_histories,payment_histories,
drivers,drivers,bus_driver
conductors,conductors,bus_conductor
bus_driver,bus_driver,
bus_conductor,bus_conductor,
categories,categories,categories|news_articles
news_articles,news_articles,
otp_codes,otp_codes,
roles,roles,model_has_roles|role_has_permissions
permissions,permissions,role_has_permissions
model_has_roles,model_has_roles,
role_has_permissions,role_has_permissions,
media,media,
notifications,notifications,
```

Catatan untuk Draw.io CSV di atas:

- CSV import membuat node + koneksi utama terlebih dulu.
- Relasi polymorphic (`media`, `notifications`, `model_has_roles`) perlu diberi label manual setelah import karena targetnya bisa banyak model.

---

## Relasi Inti (Ringkas)

1. users 1..N bookings
2. schedules 1..N bookings
3. promo_codes 1..N bookings (nullable)
4. bookings 1..N payment_histories
5. buses 1..N schedules
6. routes 1..N schedules
7. buses N..N drivers via bus_driver (driver_id unik)
8. buses N..N conductors via bus_conductor (conductor_id unik)
9. categories self relation via parent_id
10. categories 1..N news_articles
11. users 1..N news_articles via author_id
12. roles N..N permissions via role_has_permissions
13. users N..N roles via model_has_roles (morph)

---

## Catatan Validasi

- Model Booking telah disinkronkan dengan migration inti: atribut non-skema (`snap_token`, `check_in_time`, `departure_date`) dihapus dari definisi model.
- OTP sudah beralih ke pola identifier + method (WA/email), dengan kolom audit attempts dan ip_address.
- `snap_token` tetap dipakai sebagai payload runtime dari proses pembayaran Midtrans, bukan sebagai kolom tersimpan di tabel `bookings`.
- `departure_date` dipakai di form/controller jadwal admin sebagai input tanggal operasional, bukan field pada tabel `bookings`.
- Status saat ini: tidak ada mismatch mayor model-vs-migration pada tabel utama yang dipetakan di dokumen ini.
