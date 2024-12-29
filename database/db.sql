CREATE DATABASE QLHT;
USE QLHT;

CREATE TABLE [user] (
                        username VARCHAR(200) NOT NULL,
    password VARCHAR(300) NOT NULL,
    role NVARCHAR(50) NOT NULL
    );

CREATE TABLE student(
                        student_id varchar(10) not null,
                        student_name nvarchar(200) NOT NULL,
                        student_dob date not null,
                        email varchar(200) not null);


CREATE TABLE teacher(
                        teacher_id varchar(10) not null,
                        teacher_name nvarchar(200) not null,
                        teacher_dob date not null,
                        email varchar(200) not null);

CREATE TABLE [subject](
                          subject_id varchar(10) not null,
    subject_name nvarchar(200) not null,
    credit int not null);

CREATE TABLE semester(
                         semester_id varchar(10) not null,
                         semester_name nvarchar(200) not null,
                         begin_date date not null,
                         end_date date not null,
                         status nvarchar(200) DEFAULT N'Sắp diễn ra');

CREATE TABLE class(
                      subject_id varchar(10) not null,
                      semester_id varchar(10) not null,
                      class_id varchar(10) not null);

ALTER TABLE class ADD teacher_id varchar(10) not null;
ALTER TABLE class ALTER COLUMN teacher_id varchar(10) null;
CREATE TABLE enroll (
                        class_id VARCHAR(10) NOT NULL,       -- Mã lớp học
                        student_id VARCHAR(10) NOT NULL,     -- Mã sinh viên
                        registration_date DATETIME DEFAULT GETDATE(), -- Ngày đăng ký
                        status NVARCHAR(20) DEFAULT 'active', -- Trạng thái: active, dropped, completed
);

CREATE TABLE schedule (
                          class_id VARCHAR(10) NOT NULL,    -- Mã lớp học
                          day_of_week NVARCHAR(20),         -- Ngày học: Thứ 2, Thứ 3, ...
                          time_start TIME,                  -- Thời gian bắt đầu
                          time_end TIME,                    -- Thời gian kết thúc
                          location NVARCHAR(100),           -- Địa điểm học
);

CREATE TABLE transcript (
                            transcript_id INT PRIMARY KEY IDENTITY,    -- ID tự động tăng
                            student_id VARCHAR(10) NOT NULL,           -- Mã sinh viên
                            class_id VARCHAR(10) NOT NULL,             -- Mã lớp học
                            semester_id VARCHAR(10) NOT NULL,          -- Mã học kỳ
                            subject_id VARCHAR(10) NOT NULL,           -- Mã môn học
                            score FLOAT CHECK (score >= 0 AND score <= 10),  -- Điểm số (0-10)
                            grade NVARCHAR(2),                         -- Xếp loại (A, B, C, D, F...)
                            status NVARCHAR(20) DEFAULT N'Đạt',        -- Trạng thái: Đạt/Không Đạt
);

-- Thêm khóa chính cho các bảng
ALTER TABLE [user] ADD PRIMARY KEY (username);

ALTER TABLE student ADD PRIMARY KEY (student_id);

ALTER TABLE teacher ADD PRIMARY KEY (teacher_id);

ALTER TABLE [subject] ADD PRIMARY KEY (subject_id);

ALTER TABLE semester ADD PRIMARY KEY (semester_id);

ALTER TABLE class ADD PRIMARY KEY (class_id);

ALTER TABLE enroll ADD PRIMARY KEY (class_id, student_id);

-- Khóa ngoại cho bảng class
ALTER TABLE class
    ADD CONSTRAINT FK_class_subject
        FOREIGN KEY (subject_id) REFERENCES [subject](subject_id);

ALTER TABLE class
    ADD CONSTRAINT FK_class_semester
        FOREIGN KEY (semester_id) REFERENCES semester(semester_id);

ALTER TABLE class
    ADD CONSTRAINT FK_class_teacher
        FOREIGN KEY (teacher_id) REFERENCES teacher(teacher_id);

-- Khóa ngoại cho bảng enroll
ALTER TABLE enroll
    ADD CONSTRAINT FK_enroll_class
        FOREIGN KEY (class_id) REFERENCES class(class_id);

ALTER TABLE enroll
    ADD CONSTRAINT FK_enroll_student
        FOREIGN KEY (student_id) REFERENCES student(student_id);

-- Khóa ngoại cho bảng schedule
ALTER TABLE schedule
    ADD CONSTRAINT FK_schedule_class
        FOREIGN KEY (class_id) REFERENCES class(class_id);

-- Khóa ngoại cho bảng transcript
ALTER TABLE transcript
    ADD CONSTRAINT FK_transcript_student
        FOREIGN KEY (student_id) REFERENCES student(student_id);

ALTER TABLE transcript
    ADD CONSTRAINT FK_transcript_class
        FOREIGN KEY (class_id) REFERENCES class(class_id);

ALTER TABLE transcript
    ADD CONSTRAINT FK_transcript_semester
        FOREIGN KEY (semester_id) REFERENCES semester(semester_id);

ALTER TABLE transcript
    ADD CONSTRAINT FK_transcript_subject
        FOREIGN KEY (subject_id) REFERENCES [subject](subject_id);


SELECT * FROM [user];
SELECT * FROM student;
SELECT * FROM teacher;
SELECT * FROM [subject];
SELECT * FROM semester;
SELECT * FROM class;
SELECT * FROM enroll;

PMC = O
SR = V
USE QLHT;
ALTER TABLE [user] ADD id NVARCHAR(10);

USE QLHT;
DELETE  FROM [user];
DELETE FROM student;
DELETE FROM transcript;
DELETE FROM enroll;
delete from semester;
delete from schedule;
delete from class;
delete from teacher;
delete from [subject];

INSERT INTO [subject] VALUES
    (N'IT3090', N'Cơ sở dữ liệu', 3),
    (N'IT3030', N'Kiến trúc máy tính', 3),
    (N'IT1110', N'Tin học đại cương', 3),
    (N'IT3070', N'Hệ điều hành', 3);

INSERT INTO [student] VALUES
    (N'20224941', N'Trịnh Việt Cường', '2004-02-18', N'cuong.tv224941@sis.hust.edu.vn'),
    (N'20224942', N'Nguyễn Thị Lan', '2004-05-20', N'lan.nt24942@sis.hust.edu.vn'),
    (N'20224943', N'Lê Hoàng Minh', '2004-08-15', N'minh.lh24943@sis.hust.edu.vn'),
    (N'20224944', N'Phạm Văn An', '2004-11-03', N'an.pv24944@sis.hust.edu.vn'),
    (N'20224945', N'Vũ Thanh Hằng', '2004-03-28', N'hang.vt24945@sis.hust.edu.vn');

INSERT INTO [teacher] VALUES
    (N'001.123456', N'Phạm Thị Mai', '1978-06-15', N'mai.pt001123456@sis.hust.edu.vn'),
    (N'002.654321', N'Lê Văn Bình', '1975-09-25', N'binh.lv002654321@sis.hust.edu.vn'),
    (N'003.987654', N'Nguyễn Minh Hòa', '1980-02-10', N'hoa.nm003987654@sis.hust.edu.vn'),
    (N'004.456789', N'Trần Quốc Anh', '1972-12-01', N'anh.tq004456789@sis.hust.edu.vn');

INSERT INTO [user] VALUES
    ('vietcuong', '123456', 'student', '20224941'),
    ('nguyenvana', '123456', 'teacher', '001.123456'),
    ('admin', '123456', 'admin', 'admin1');

INSERT INTO semester VALUES
                         ('2023.2', N'Kì 2023.2', '2024-02-19', '2024-06-21', N'Đã diễn ra'),
                         ('2024.1', N'Kì 2024.1', '2024-09-09', '2025-01-04', N'Đang diễn ra'),
                         ('2024.2', N'Kì 2024.2', '2025-02-19', '2025-06-01', N'Sắp diễn ra');

INSERT INTO class (subject_id, semester_id, class_id, teacher_id) VALUES
                                                                      ('IT3090', '2024.1', '100100', '001.123456'),
                                                                      ('IT3030', '2024.1', '100101', '002.654321'),
                                                                      ('IT1110', '2024.2', '100102', '003.987654'),
                                                                      ('IT3070', '2023.2', '100103', '004.456789'),
                                                                      ('IT3090', '2024.2', '100104', '001.123456');

INSERT INTO schedule (class_id, day_of_week, time_start, time_end, location) VALUES
                                                                                 ('100100', N'Thứ 2', '08:00:00', '10:00:00', 'D6-101'),
                                                                                 ('100101', N'Thứ 3', '10:15:00', '12:15:00', 'D6-102'),
                                                                                 ('100102', N'Thứ 4', '13:30:00', '15:30:00', 'D6-103'),
                                                                                 ('100103', N'Thứ 5', '08:00:00', '10:00:00', 'D6-104'),
                                                                                 ('100104', N'Thứ 6', '10:15:00', '12:15:00', 'D6-105');

INSERT INTO transcript (student_id, class_id, semester_id, subject_id, score, grade, status) VALUES
                                                                                                 ('20224941', '100100', '2024.1', 'IT3090', 8.5, N'A', N'Đạt'),
                                                                                                 ('20224942', '100100', '2024.1', 'IT3090', 7.0, N'B', N'Đạt'),
                                                                                                 ('20224943', '100101', '2024.1', 'IT3030', 6.0, N'C', N'Đạt'),
                                                                                                 ('20224944', '100103', '2023.2', 'IT3070', 5.5, N'D', N'Đạt'),
                                                                                                 ('20224945', '100102', '2024.2', 'IT1110', 9.0, N'A', N'Đạt'),
                                                                                                 ('20224941', '100104', '2024.2', 'IT3090', 4.0, N'F', N'Không Đạt'),
                                                                                                 ('20224942', '100102', '2024.2', 'IT1110', 8.0, N'A', N'Đạt'),
                                                                                                 ('20224943', '100101', '2024.1', 'IT3030', 7.5, N'B', N'Đạt'),
                                                                                                 ('20224944', '100100', '2024.1', 'IT3090', 6.5, N'C', N'Đạt');

SELECT * FROM [user];
SELECT * FROM student;
SELECT * FROM transcript;
SELECT * FROM enroll;
SELECT * FROM semester;
SELECT * FROM schedule;
SELECT * FROM class;
SELECT * FROM teacher;
SELECT * FROM [subject];
-- Thêm dữ liệu vào bảng enroll từ bảng transcript
INSERT INTO enroll (class_id, student_id, registration_date, status) VALUES
                                                                         ('100100', '20224941', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100100', '20224942', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100103', '20224944', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100102', '20224945', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100104', '20224941', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100102', '20224942', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100101', '20224943', '2024-12-28 14:00:00.000', N'Thành công'),
                                                                         ('100100', '20224944', '2024-12-28 14:00:00.000', N'Thành công');

SELECT * FROM enroll;


