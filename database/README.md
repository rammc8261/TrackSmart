# Database setup

The original archive included a database export containing application data, so it is intentionally excluded from this Git-ready project.

Create a MySQL/MariaDB database named `tracksmart` (or choose another name and set `DB_NAME` accordingly), then import a sanitized schema or your private database backup locally. Keep all SQL exports in this folder: `.gitignore` prevents them from being committed.

The application uses tables for users, departments, students, attendance, roles, class-teacher assignments, and HOD assignments. The original project's `includes/db.txt` contains supplementary schema notes.
