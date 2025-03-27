create database student_management;
use student_management;

create table student(
id int auto_increment primary key,  
name varchar(100), 
email varchar(150), 
dob date,
phone varchar(15), 
address longtext
)
create table teacher(
id int auto_increment primary key,
name varchar(100),
email varchar(150), 
specialization varchar(100), 
phone varchar(15)
)
create table course(
id int auto_increment primary key, 
title varchar(100), 
description longtext, 
credits int,
)
create table enrollment(
id int auto_increment primary key, 
student_id int, 
course_id int, 
enrollment_date date,
)
-- Drop existing constraints if they exist
ALTER TABLE enrollment 
    DROP FOREIGN KEY IF EXISTS fk_enrollment_student,
    DROP FOREIGN KEY IF EXISTS fk_enrollment_course,
    DROP INDEX IF EXISTS uk_enrollment_student_course;

-- Add NOT NULL constraints
ALTER TABLE student 
    MODIFY name varchar(100) NOT NULL,
    MODIFY dob date NOT NULL,
    MODIFY phone varchar(15) NOT NULL,
    MODIFY address longtext NOT NULL;

ALTER TABLE teacher 
    MODIFY name varchar(100) NOT NULL,
    MODIFY specialization varchar(100) NOT NULL,
    MODIFY phone varchar(15) NOT NULL;

ALTER TABLE class 
    RENAME TO course;

ALTER TABLE course 
    MODIFY title varchar(100) NOT NULL,
    MODIFY description longtext NOT NULL,
    MODIFY credits int NOT NULL;

-- Add foreign key constraints
ALTER TABLE enrollment 
    MODIFY student_id int NOT NULL,
    MODIFY course_id int NOT NULL,
    MODIFY enrollment_date date NOT NULL,
    ADD CONSTRAINT fk_enrollment_student 
        FOREIGN KEY (student_id) REFERENCES student(id) 
        ON DELETE RESTRICT ON UPDATE CASCADE,
    ADD CONSTRAINT fk_enrollment_course 
        FOREIGN KEY (course_id) REFERENCES course(id) 
        ON DELETE RESTRICT ON UPDATE CASCADE,
    ADD CONSTRAINT uk_enrollment_student_course 
        UNIQUE (student_id, course_id);
-- Insert students
INSERT INTO student (name, email, dob, phone, address) VALUES
('Nguyễn Tân An', 'an.nguyenvan@email.com', '2000-05-15', '0912345678', 'Số 123 Lê Lợi, Quận 1, TP.HCM'),
('Trần Quóc Bình', 'binh.tran@email.com', '2001-03-22', '0923456789', '45 Nguyễn Huệ, Quận 3, TP.HCM'),
('Lê Quốc Cường', 'cuong.le@email.com', '2000-11-30', '0934567890', '67 Trần Hưng Đạo, Quận 5, TP.HCM'),
('Phạm Thu Dung', 'dung.pham@email.com', '2001-07-18', '0945678901', '89 Võ Văn Tần, Quận 10, TP.HCM'),
('Hoàng Đức Duy', 'duy.hoang@email.com', '2000-09-25', '0956789012', '234 Cách Mạng Tháng 8, Quận Tân Bình, TP.HCM');

-- Insert teachers
INSERT INTO teacher (name, email, specialization, phone) VALUES
('TS. Võ Thanh Hà', 'ha.vo@faculty.edu', 'Toán học', '0901234567'),
('PGS. Nguyễn Hữu Kim Sơn', 'son.nguyen@faculty.edu', 'Công nghệ thông tin', '0912345678'),
('TS. Trần Hà Linh', 'linh.tran@faculty.edu', 'Vật lý', '0923456789'),
('PGS. Lê Hoàng Mai', 'mai.le@faculty.edu', 'Văn học', '0934567890'),
('TS. Phạm Đức Nam', 'nam.pham@faculty.edu', 'Hóa học', '0945678901');

-- Insert classes
INSERT INTO class (title, description, credits) VALUES
('Giải tích cơ bản', 'Các khái niệm cơ bản về giải tích bao gồm giới hạn, đạo hàm và tích phân', 15),
('Lập trình căn bản', 'Các khái niệm lập trình cơ bản và kỹ thuật giải quyết vấn đề', 15),
('Vật lý hiện đại', 'Giới thiệu về cơ học lượng tử và thuyết tương đối', 15),
('Văn học Việt Nam', 'Tổng quan các tác phẩm văn học Việt Nam qua các thời kỳ', 15),
('Hóa học đại cương', 'Nguyên lý cơ bản về hóa học và phản ứng hóa học', 15);