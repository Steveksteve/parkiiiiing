-- =========================================================
--  Création de la base de données
-- =========================================================
CREATE DATABASE IF NOT EXISTS parking_partage
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE parking_partage;

-- =========================================================
--  TABLE users
--  (utilisateurs + propriétaires, différenciés par "role")
-- =========================================================
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role ENUM('USER', 'OWNER', 'BOTH') NOT NULL DEFAULT 'USER',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE INDEX idx_users_role ON users(role);

-- =========================================================
--  TABLE parkings
-- =========================================================
CREATE TABLE IF NOT EXISTS parkings (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    owner_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    latitude DECIMAL(9,6) NOT NULL,
    longitude DECIMAL(9,6) NOT NULL,
    capacity INT UNSIGNED NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_parkings_owner
        FOREIGN KEY (owner_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_parkings_owner ON parkings(owner_id);
CREATE INDEX idx_parkings_geo ON parkings(latitude, longitude);

-- =========================================================
--  TABLE parking_opening_hours
--  (horaires d’ouverture par jour de la semaine)
-- =========================================================
CREATE TABLE IF NOT EXISTS parking_opening_hours (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parking_id BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT UNSIGNED NOT NULL, -- 0=dimanche ... 6=samedi
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    CONSTRAINT fk_opening_parking
        FOREIGN KEY (parking_id) REFERENCES parkings(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_opening_parking_day
    ON parking_opening_hours(parking_id, day_of_week);

-- =========================================================
--  TABLE parking_tariffs
--  (grille tarifaire par tranche de minutes)
-- =========================================================
CREATE TABLE IF NOT EXISTS parking_tariffs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parking_id BIGINT UNSIGNED NOT NULL,
    valid_from DATETIME NOT NULL,
    valid_to DATETIME NULL,
    min_minutes INT UNSIGNED NOT NULL,   -- durée min de la tranche
    max_minutes INT UNSIGNED NULL,       -- NULL = pas de limite
    price_per_15min DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_tariff_parking
        FOREIGN KEY (parking_id) REFERENCES parkings(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_tariffs_parking
    ON parking_tariffs(parking_id);

-- =========================================================
--  TABLE subscription_plans
--  (types d’abonnements par parking)
-- =========================================================
CREATE TABLE IF NOT EXISTS subscription_plans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parking_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,           -- "Total", "Week-end", "Soir", etc.
    description TEXT NULL,
    monthly_price DECIMAL(10,2) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sub_plan_parking
        FOREIGN KEY (parking_id) REFERENCES parkings(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_sub_plan_parking
    ON subscription_plans(parking_id);

-- =========================================================
--  TABLE subscription_plan_slots
--  (créneaux hebdomadaires d’un type d’abonnement)
-- =========================================================
CREATE TABLE IF NOT EXISTS subscription_plan_slots (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    plan_id BIGINT UNSIGNED NOT NULL,
    day_of_week TINYINT UNSIGNED NOT NULL,  -- 0=dimanche ... 6=samedi
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    CONSTRAINT fk_sub_plan_slot_plan
        FOREIGN KEY (plan_id) REFERENCES subscription_plans(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_sub_plan_slots_plan_day
    ON subscription_plan_slots(plan_id, day_of_week);

-- =========================================================
--  TABLE subscriptions
--  (abonnements souscrits par les utilisateurs)
-- =========================================================
CREATE TABLE IF NOT EXISTS subscriptions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    parking_id BIGINT UNSIGNED NOT NULL,
    plan_id BIGINT UNSIGNED NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status ENUM('ACTIVE', 'CANCELLED', 'EXPIRED') NOT NULL DEFAULT 'ACTIVE',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sub_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_sub_parking
        FOREIGN KEY (parking_id) REFERENCES parkings(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_sub_plan
        FOREIGN KEY (plan_id) REFERENCES subscription_plans(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_subscriptions_user ON subscriptions(user_id);
CREATE INDEX idx_subscriptions_parking ON subscriptions(parking_id);

-- =========================================================
--  TABLE reservations
-- =========================================================
CREATE TABLE IF NOT EXISTS reservations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    parking_id BIGINT UNSIGNED NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status ENUM('EN_ATTENTE', 'CONFIRMEE', 'TERMINEE', 'ANNULEE')
        NOT NULL DEFAULT 'CONFIRMEE',
    estimated_price DECIMAL(10,2) NULL,
    final_price DECIMAL(10,2) NULL,
    penalty_amount DECIMAL(10,2) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_res_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_res_parking
        FOREIGN KEY (parking_id) REFERENCES parkings(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE INDEX idx_res_user ON reservations(user_id);
CREATE INDEX idx_res_parking ON reservations(parking_id);
CREATE INDEX idx_res_parking_time ON reservations(parking_id, start_time, end_time);

-- =========================================================
--  TABLE parking_stays
--  (stationnements réels)
-- =========================================================
CREATE TABLE IF NOT EXISTS parking_stays (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    parking_id BIGINT UNSIGNED NOT NULL,
    reservation_id BIGINT UNSIGNED NULL,
    subscription_id BIGINT UNSIGNED NULL,
    entry_time DATETIME NOT NULL,
    exit_time DATETIME NULL,
    billed_minutes INT UNSIGNED NULL,
    billed_amount DECIMAL(10,2) NULL,
    penalty_amount DECIMAL(10,2) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_stay_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_stay_parking
        FOREIGN KEY (parking_id) REFERENCES parkings(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_stay_reservation
        FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_stay_subscription
        FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_stay_user ON parking_stays(user_id);
CREATE INDEX idx_stay_parking ON parking_stays(parking_id);
CREATE INDEX idx_stay_parking_entry ON parking_stays(parking_id, entry_time);

-- =========================================================
--  TABLE invoices
--  (factures générées après stationnement / réservation)
-- =========================================================
CREATE TABLE IF NOT EXISTS invoices (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    reservation_id BIGINT UNSIGNED NULL,
    parking_stay_id BIGINT UNSIGNED NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    details_json JSON NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_invoice_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_invoice_res
        FOREIGN KEY (reservation_id) REFERENCES reservations(id)
        ON DELETE SET NULL,
    CONSTRAINT fk_invoice_stay
        FOREIGN KEY (parking_stay_id) REFERENCES parking_stays(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE INDEX idx_invoice_user ON invoices(user_id);
