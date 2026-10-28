<?php

class Add_training_roles_and_courses
{
    private $_lava;

    public function __construct()
    {
        $this->_lava = lava_instance();
        $this->_lava->call->database();
    }

    public function up()
    {
        $roleColumn = $this->_lava->db->raw(
            "SHOW COLUMNS FROM users LIKE 'role'"
        )->fetch(PDO::FETCH_ASSOC);

        if (!$roleColumn) {
            throw new RuntimeException('The users table is missing its role column.');
        }

        $this->_lava->db->raw(
            "ALTER TABLE users MODIFY role VARCHAR(20) NOT NULL DEFAULT 'student'"
        );
        $this->_lava->db->raw(
            "UPDATE users SET role = 'instructor' WHERE role = 'moderator'"
        );
        $this->_lava->db->raw(
            "UPDATE users SET role = 'student' WHERE role = 'user'"
        );

        $fullNameColumn = $this->_lava->db->raw(
            "SHOW COLUMNS FROM users LIKE 'full_name'"
        )->fetch(PDO::FETCH_ASSOC);
        if (!$fullNameColumn) {
            $this->_lava->db->raw(
                'ALTER TABLE users ADD full_name VARCHAR(150) NULL AFTER username'
            );
        }

        $this->_lava->db->raw("
            CREATE TABLE IF NOT EXISTS courses (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                course_code VARCHAR(40) NOT NULL,
                course_name VARCHAR(150) NOT NULL,
                qualification VARCHAR(30) NOT NULL,
                description TEXT NULL,
                capacity INT UNSIGNED NOT NULL DEFAULT 30,
                status VARCHAR(20) NOT NULL DEFAULT 'open',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY course_code_unique (course_code),
                KEY course_status_idx (status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $this->_lava->db->raw("
            CREATE TABLE IF NOT EXISTS enrollments (
                id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                student_id INT UNSIGNED NOT NULL,
                course_id INT UNSIGNED NOT NULL,
                instructor_id INT UNSIGNED NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'enrolled',
                enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY student_course_unique (student_id, course_id),
                KEY enrollment_course_idx (course_id),
                KEY enrollment_instructor_idx (instructor_id),
                CONSTRAINT enrollment_student_fk FOREIGN KEY (student_id)
                    REFERENCES users (id) ON DELETE CASCADE,
                CONSTRAINT enrollment_course_fk FOREIGN KEY (course_id)
                    REFERENCES courses (id) ON DELETE CASCADE,
                CONSTRAINT enrollment_instructor_fk FOREIGN KEY (instructor_id)
                    REFERENCES users (id) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->_lava->db->raw('DROP TABLE IF EXISTS enrollments');
        $this->_lava->db->raw('DROP TABLE IF EXISTS courses');
        $this->_lava->db->raw('ALTER TABLE users DROP COLUMN full_name');
        $this->_lava->db->raw("UPDATE users SET role = 'moderator' WHERE role = 'instructor'");
        $this->_lava->db->raw("UPDATE users SET role = 'user' WHERE role = 'student'");
        $this->_lava->db->raw(
            "ALTER TABLE users MODIFY role ENUM('admin','moderator','user') NOT NULL DEFAULT 'user'"
        );
    }
}
