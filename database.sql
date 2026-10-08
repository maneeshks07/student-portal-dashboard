-- Student Portal database (schema inferred from the PHP code)
CREATE DATABASE IF NOT EXISTS student_portal_showcase;
USE student_portal_showcase;

CREATE TABLE IF NOT EXISTS users (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    fullname       VARCHAR(100) NOT NULL,
    username       VARCHAR(50)  NOT NULL UNIQUE,
    email          VARCHAR(100) NOT NULL,
    phone          VARCHAR(20)  NULL,
    gender         VARCHAR(10)  NULL,
    hobbies        VARCHAR(255) NULL,
    age            INT          NULL,
    qualifications VARCHAR(255) NULL,
    password       VARCHAR(255) NOT NULL,
    dob            DATE         NULL,
    about          TEXT         NULL,
    photo          VARCHAR(255) NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- demo login: demo / demo123 (passwords are bcrypt hashes)
INSERT INTO users (fullname, username, email, phone, gender, hobbies, age, qualifications, password, dob, about, photo) VALUES
    ('Demo Student', 'demo', 'demo@example.com', '555-0100', 'Male', 'Reading, Cricket', 21, 'B.Sc', '$2y$12$JcWsaqwh3Z9jK837rIWquu2FaUh/Z/cgdINdciWmAcFx96wA1gX1e', '2005-01-15', 'Sample student for the demo.', ''),
    ('Jane Doe', 'jane', 'jane@example.com', '555-0101', 'Female', 'Music', 20, 'B.Com', '$2y$12$.tpKrjQ/dzAl5hu.OWIOY.XpK8mlSoqgrYTaQQuZqPTeH4YpWXOxe', '2006-03-02', 'Second sample student.', '');
