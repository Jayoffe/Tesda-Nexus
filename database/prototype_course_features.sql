-- One-time prototype setup for training-center-specific course details.
-- Run after migrations 005 and 006, before applying the updated seed data.

CREATE TABLE IF NOT EXISTS training_centers (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    center_code VARCHAR(40) NOT NULL,
    name VARCHAR(150) NOT NULL,
    region VARCHAR(100) NULL,
    province VARCHAR(100) NULL,
    city VARCHAR(100) NULL,
    address VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY training_center_code_unique (center_code),
    KEY training_center_active_idx (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO training_centers (center_code, name, region, province, city)
SELECT 'DEMO-NCR', 'Demo Training Center - NCR', 'NCR', NULL, 'Quezon City'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM training_centers WHERE center_code = 'DEMO-NCR'
);

INSERT INTO training_centers (center_code, name, region, province, city)
SELECT 'DEMO-CENTRAL-LUZON', 'Demo Training Center - Central Luzon', 'Central Luzon', 'Pampanga', 'San Fernando'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM training_centers WHERE center_code = 'DEMO-CENTRAL-LUZON'
);

INSERT INTO training_centers (center_code, name, region, province, city)
SELECT 'DEMO-CENTRAL-VISAYAS', 'Demo Training Center - Central Visayas', 'Central Visayas', 'Cebu', 'Cebu City'
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM training_centers WHERE center_code = 'DEMO-CENTRAL-VISAYAS'
);

ALTER TABLE users
    ADD COLUMN training_center_id INT UNSIGNED NULL AFTER role,
    ADD KEY user_training_center_idx (training_center_id),
    ADD CONSTRAINT user_training_center_fk FOREIGN KEY (training_center_id)
        REFERENCES training_centers (id) ON DELETE SET NULL;

ALTER TABLE courses
    ADD COLUMN training_center_id INT UNSIGNED NULL AFTER id,
    ADD COLUMN class_days VARCHAR(150) NULL AFTER description,
    ADD COLUMN class_schedule TEXT NULL AFTER class_days,
    ADD COLUMN course_outline TEXT NULL AFTER class_schedule;

UPDATE users
SET training_center_id = (
    SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR'
)
WHERE role IN ('student', 'instructor') AND training_center_id IS NULL;

UPDATE courses
SET training_center_id = (
    SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR'
)
WHERE training_center_id IS NULL;

ALTER TABLE courses
    MODIFY COLUMN training_center_id INT UNSIGNED NOT NULL,
    DROP INDEX course_code_unique,
    ADD UNIQUE KEY course_center_code_unique (training_center_id, course_code),
    ADD KEY course_training_center_idx (training_center_id),
    ADD CONSTRAINT course_training_center_fk FOREIGN KEY (training_center_id)
        REFERENCES training_centers (id) ON DELETE RESTRICT;

CREATE TABLE IF NOT EXISTS course_materials (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    course_id INT UNSIGNED NOT NULL,
    uploaded_by INT UNSIGNED NULL,
    title VARCHAR(150) NOT NULL,
    description VARCHAR(500) NULL,
    resource_url VARCHAR(2048) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY course_material_course_idx (course_id),
    CONSTRAINT course_material_course_fk FOREIGN KEY (course_id)
        REFERENCES courses (id) ON DELETE CASCADE,
    CONSTRAINT course_material_user_fk FOREIGN KEY (uploaded_by)
        REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
