-- Run after prototype_course_features.sql and the 005/006 migrations.
-- This is an additive setup script, not a LavaLust migration.
CREATE TABLE IF NOT EXISTS center_applications (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    training_center_id INT UNSIGNED NOT NULL,
    applied_role VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    applied_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    reviewed_by INT UNSIGNED NULL,
    reviewed_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY center_application_user_unique (user_id),
    KEY center_application_queue_idx (training_center_id, status, applied_at),
    CONSTRAINT center_application_user_fk
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT center_application_center_fk
        FOREIGN KEY (training_center_id) REFERENCES training_centers (id) ON DELETE CASCADE,
    CONSTRAINT center_application_reviewer_fk
        FOREIGN KEY (reviewed_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
