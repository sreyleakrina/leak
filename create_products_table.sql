USE flower_shop;
GO
IF OBJECT_ID('dbo.products', 'U') IS NULL
BEGIN
    CREATE TABLE dbo.products (
        id INT IDENTITY(1,1) PRIMARY KEY,
        name NVARCHAR(255) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        image NVARCHAR(255) NOT NULL
    );
    PRINT '✅ បង្កើតតារាង products រួចរាល់។';
END
ELSE
BEGIN
    PRINT 'ℹ️ តារាង products មានរួចហើយ។';
END
GO
INSERT INTO dbo.products (name, price, image) VALUES
('Pink Roses', 20.00, 'imag/1.jpg'),
('Sunflower Joy', 18.00, 'imag/8.jpg'),
('Romantic Lilies', 25.00, 'imag/lily.jpg'),
('White Orchid', 22.00, 'imag/9.jpg'),
('Blue Hydrangea', 19.00, 'imag/7.jpg'),
('Mixed Bouquet', 30.00, 'imag/14.jpg');
