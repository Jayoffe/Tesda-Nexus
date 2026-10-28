-- Apply migrations 005/006 and prototype_course_features.sql before this seed.
INSERT INTO users (username, email, password, role, is_active, training_center_id)
SELECT
    'admin',
    'admin@gmail.com',
    '$2y$10$OVoRnKYrt2/g0xmFjBQd.uWyGbPJAjrciL1DD0eLyVkcSUVxiJlim',
    'admin',
    1,
    NULL
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@gmail.com'
);

UPDATE users
SET full_name = 'TESDA Administrator', training_center_id = NULL
WHERE email = 'admin@gmail.com' AND full_name IS NULL;

INSERT INTO users (username, email, password, role, is_active, full_name, training_center_id)
SELECT
    'instructor',
    'instructor@gmail.com',
    '$2y$10$tCjndaKp9l.9Vhiu8Rmuheb70eGjoK70vYkWaWImwS8UJUhtmqScK',
    'instructor',
    1,
    'TESDA Instructor',
    (SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR')
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'instructor@gmail.com'
);

INSERT INTO users (username, email, password, role, is_active, full_name, training_center_id)
SELECT
    'user',
    'user@gmail.com',
    '$2y$10$mA4sTw39Ohw/B7WTi0iRBOqCeqH13YJryqaVk8CxYw/pDDYiv5nlG',
    'student',
    1,
    'Sample Student',
    (SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR')
FROM DUAL
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'user@gmail.com'
);

INSERT INTO courses (training_center_id, course_code, course_name, qualification, description, capacity, status)
SELECT center.id, 'CSS-NCII', 'Computer Systems Servicing', 'NC II', 'Install, configure, and maintain computer systems and networks.', 30, 'open'
FROM training_centers center
WHERE center.center_code = 'DEMO-NCR'
  AND NOT EXISTS (SELECT 1 FROM courses WHERE course_code = 'CSS-NCII' AND training_center_id = center.id);

INSERT INTO courses (training_center_id, course_code, course_name, qualification, description, capacity, status)
SELECT center.id, 'COOK-NCII', 'Cookery', 'NC II', 'Develop foundational commercial cookery and kitchen operation skills.', 24, 'open'
FROM training_centers center
WHERE center.center_code = 'DEMO-NCR'
  AND NOT EXISTS (SELECT 1 FROM courses WHERE course_code = 'COOK-NCII' AND training_center_id = center.id);

INSERT INTO courses (training_center_id, course_code, course_name, qualification, description, capacity, status)
SELECT center.id, 'BPP-NCII', 'Bread and Pastry Production', 'NC II', 'Prepare bakery and pastry products using standard production methods.', 24, 'open'
FROM training_centers center
WHERE center.center_code = 'DEMO-NCR'
  AND NOT EXISTS (SELECT 1 FROM courses WHERE course_code = 'BPP-NCII' AND training_center_id = center.id);

UPDATE courses
SET class_days = COALESCE(NULLIF(class_days, ''), 'Monday, Wednesday, Friday'),
    class_schedule = COALESCE(NULLIF(class_schedule, ''), '8:00 AM to 12:00 PM, Training Room 2'),
    course_outline = COALESCE(NULLIF(course_outline, ''), 'Workplace safety and housekeeping\nComputer hardware assembly and configuration\nOperating system installation and setup\nBasic networking and connectivity\nHardware and software troubleshooting')
WHERE course_code = 'CSS-NCII'
  AND training_center_id = (SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR');

UPDATE courses
SET class_days = COALESCE(NULLIF(class_days, ''), 'Tuesday, Thursday, Saturday'),
    class_schedule = COALESCE(NULLIF(class_schedule, ''), '1:00 PM to 5:00 PM, Training Kitchen 1'),
    course_outline = COALESCE(NULLIF(course_outline, ''), 'Kitchen safety and sanitation\nTools, equipment, and ingredients\nStocks, soups, and sauces\nPrepare and cook meals\nFood presentation and service')
WHERE course_code = 'COOK-NCII'
  AND training_center_id = (SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR');

UPDATE courses
SET class_days = COALESCE(NULLIF(class_days, ''), 'Monday, Tuesday, Thursday'),
    class_schedule = COALESCE(NULLIF(class_schedule, ''), '8:00 AM to 12:00 PM, Baking Lab'),
    course_outline = COALESCE(NULLIF(course_outline, ''), 'Food safety and sanitation\nIngredient measurement and preparation\nDough and batter methods\nBake breads, cakes, and pastries\nFinishing, presentation, and storage')
WHERE course_code = 'BPP-NCII'
  AND training_center_id = (SELECT id FROM training_centers WHERE center_code = 'DEMO-NCR');

INSERT INTO enrollments (student_id, course_id, instructor_id, status)
SELECT student.id, course.id, instructor.id, 'enrolled'
FROM users AS student
JOIN users AS instructor ON instructor.email = 'instructor@gmail.com' AND instructor.role = 'instructor'
JOIN courses AS course ON course.course_code = 'CSS-NCII'
                    AND course.training_center_id = instructor.training_center_id
WHERE student.email = 'user@gmail.com'
  AND student.role = 'student'
  AND NOT EXISTS (
      SELECT 1 FROM enrollments e
      WHERE e.student_id = student.id AND e.course_id = course.id
  );

INSERT INTO products (product_name, description, price, quantity)
SELECT 'Welding gloves', 'Heat-resistant gloves for supervised welding practice.', 450.00, 18
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name = 'Welding gloves');

INSERT INTO products (product_name, description, price, quantity)
SELECT 'Safety goggles', 'Protective eyewear for workshop and laboratory activities.', 180.00, 32
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name = 'Safety goggles');

INSERT INTO products (product_name, description, price, quantity)
SELECT 'Digital multimeter', 'Basic electrical testing tool for training exercises.', 850.00, 12
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name = 'Digital multimeter');

INSERT INTO products (product_name, description, price, quantity)
SELECT 'First aid kit', 'Restocked first aid kit for a training room or workshop.', 650.00, 8
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM products WHERE product_name = 'First aid kit');
