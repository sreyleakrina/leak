-- Select the target database
USE flower_shop;
GO

-- Only create the 'students' table if it does not exist
IF OBJECT_ID('dbo.students', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.students (
        id INT IDENTITY(1,1) PRIMARY KEY,
        name NVARCHAR(255) NOT NULL,
        class NVARCHAR(255),
        academic_year NVARCHAR(50),
        level INT,
        pay_type NVARCHAR(50),
        rank NVARCHAR(50),
        date DATE,
        price DECIMAL(10,2)
    );
    PRINT '✅ Table dbo.students created successfully.';
END
ELSE
BEGIN
    PRINT 'ℹ️ Table dbo.students already exists.';
END;
GO

-- Optional: Insert sample student records
INSERT INTO dbo.students (name, class, academic_year, level, pay_type, rank, date, price)
VALUES 
('Alice Johnson', 'Grade 10', '2024-2025', 10, 'Full', 'A', GETDATE(), 1500.00),
('Bob Smith', 'Grade 11', '2024-2025', 11, 'Partial', 'B', GETDATE(), 1200.00);
GO

-- Optional: View the table contents
SELECT * FROM dbo.students;
GO



-- CREATE TABLE IF NOT EXISTS students (
--     id INT AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255),
--     class VARCHAR(255),
--     academic_year VARCHAR(50),
--     level INT,
--     pay_type VARCHAR(50),
--     rank VARCHAR(50),
--     date DATE,
--     price DECIMAL(10,2)
-- );
