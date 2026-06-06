-- Matilda's Salon & Spa — Database Schema
-- Run via setup.php or import directly into MySQL Workbench

CREATE DATABASE IF NOT EXISTS saloon_online CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saloon_online;

-- Users (admin & staff only)
CREATE TABLE IF NOT EXISTS users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    phone      VARCHAR(20),
    role       ENUM('admin','staff') NOT NULL DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Services
CREATE TABLE IF NOT EXISTS services (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    name             VARCHAR(100) NOT NULL,
    description      TEXT,
    price            DECIMAL(10,2) NOT NULL,
    duration_minutes INT NOT NULL DEFAULT 60,
    category         VARCHAR(50) DEFAULT 'General',
    image            VARCHAR(255),
    active           TINYINT(1) DEFAULT 1,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Staff profiles
CREATE TABLE IF NOT EXISTS staff (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    user_id        INT NOT NULL,
    specialization VARCHAR(100),
    bio            TEXT,
    available      TINYINT(1) DEFAULT 1,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Staff availability (per day-of-week)
CREATE TABLE IF NOT EXISTS staff_availability (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    staff_id    INT NOT NULL,
    day_of_week TINYINT NOT NULL COMMENT '0=Sunday…6=Saturday',
    start_time  TIME NOT NULL,
    end_time    TIME NOT NULL,
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Appointments (guest bookings)
CREATE TABLE IF NOT EXISTS appointments (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    client_name      VARCHAR(100) NOT NULL,
    client_email     VARCHAR(100) NOT NULL,
    client_phone     VARCHAR(20),
    service_id       INT NOT NULL,
    staff_id         INT DEFAULT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    status           ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    notes            TEXT,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Payments / invoices
CREATE TABLE IF NOT EXISTS payments (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    appointment_id INT NOT NULL,
    amount         DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash','mobile_money','card') DEFAULT 'cash',
    status         ENUM('pending','paid','refunded') DEFAULT 'pending',
    invoice_number VARCHAR(30) UNIQUE,
    notes          TEXT,
    paid_at        TIMESTAMP NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
) ENGINE=InnoDB;

-- Inventory
CREATE TABLE IF NOT EXISTS inventory (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    product_name  VARCHAR(100) NOT NULL,
    category      VARCHAR(50),
    quantity      DECIMAL(10,2) DEFAULT 0,
    unit          VARCHAR(20) DEFAULT 'pcs',
    reorder_level DECIMAL(10,2) DEFAULT 5,
    cost_price    DECIMAL(10,2) DEFAULT 0,
    supplier      VARCHAR(100),
    last_updated  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Promotions
CREATE TABLE IF NOT EXISTS promotions (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    title            VARCHAR(100) NOT NULL,
    description      TEXT,
    discount_percent DECIMAL(5,2) DEFAULT 0,
    start_date       DATE,
    end_date         DATE,
    active           TINYINT(1) DEFAULT 1,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- FAQs
CREATE TABLE IF NOT EXISTS faqs (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    question   TEXT NOT NULL,
    answer     TEXT NOT NULL,
    category   VARCHAR(50) DEFAULT 'General',
    active     TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0
) ENGINE=InnoDB;

-- Contact messages
CREATE TABLE IF NOT EXISTS contact_messages (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    email      VARCHAR(100) NOT NULL,
    phone      VARCHAR(20),
    subject    VARCHAR(100),
    message    TEXT NOT NULL,
    is_read    TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Notifications
CREATE TABLE IF NOT EXISTS notifications (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    type       ENUM('appointment','message','payment','system') DEFAULT 'system',
    title      VARCHAR(150) NOT NULL,
    body       TEXT,
    url        VARCHAR(255),
    is_read    TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
