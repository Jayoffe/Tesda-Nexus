# Prototype database setup

This prototype uses standalone SQL setup scripts for training centers, course details, and center applications. These scripts do not add or change framework migrations.

1. Apply migrations `005` and `006` to the selected database.
2. Run `prototype_course_features.sql` once in that same database, for example through phpMyAdmin's SQL or Import page.
3. Run `prototype_center_applications.sql` to create the application review table. It uses `CREATE TABLE IF NOT EXISTS`, so it is safe to run again.
4. Run `seed.sql` to add or refresh the sample accounts and starter courses.

The setup script creates demo centers for NCR, Central Luzon, and Central Visayas. The starter course days, schedules, and outlines are examples for the NCR demo center, not official TESDA schedules or training regulations. Replace them with the details approved by each center. Administrators can add more centers in the portal and assign centers to staff, students, and course offerings.

Course lecture materials are shared as HTTP or HTTPS links, such as links to files stored in the center's existing file-sharing service.

TESDA central administrators appoint center organizers from **Manage users** by creating a **Center organizer** account and assigning a training center. Organizers can review that center's student and instructor applications and manage only that center's accounts and courses. Applicants use `/apply`, select Student or Instructor and an active center, and cannot log in until the selected center approves the application.

The public API registration endpoint follows the same approval flow. Include `full_name`, `username`, `email`, `password`, `role` (`student` or `instructor`), and an active `training_center_id`; registration creates an inactive pending application rather than a login-ready account.
