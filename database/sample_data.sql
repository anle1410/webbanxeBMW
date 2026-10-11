-- Sample Data for BMW Vehicles
-- Run this file in phpMyAdmin after importing schema.sql

-- Insert vehicle categories
INSERT INTO categories (category_name, category_slug, category_description, display_order) VALUES
('BMW Series M', 'bmw-series-m', 'Dong xe the thao hieu nang cao M Performance', 1),
('BMW X Series', 'bmw-x-series', 'Dong xe SUV crossover', 2),
('BMW i Series', 'bmw-i-series', 'Dong xe dien hoa', 3),
('BMW Sedan', 'bmw-sedan', 'Dong xe sedan classic', 4),
('BMW Coupe', 'bmw-coupe', 'Dong xe coupe the thao', 5);

-- Insert vehicles
INSERT INTO vehicles (vehicle_name, vehicle_code, category_id, model_year, price, promotional_price, description, engine_type, engine_displacement, horsepower, torque, transmission, drivetrain, fuel_type, fuel_efficiency, acceleration, top_speed, dimensions_length, dimensions_width, dimensions_height, wheelbase, curb_weight, seating_capacity, colors, interior_color, features, status, is_featured, is_new, is_best_seller) VALUES
('BMW M4 Competition', 'M4C2024', 1, 2024, 4199000000, 3999000000, 'BMW M4 Competition la bieu tuong cua dang cap the thao voi dong co 3.0L TwinPower Turbo 510 HP.', 'TwinPower Turbo', '3.0L', '510 HP', '650 Nm', 'Auto 8 cap', 'RWD', 'Petrol', '10.5 L/100km', '3.8s', '250 km/h', '4794 mm', '1902 mm', '1403 mm', '2857 mm', '1730 kg', 4, 'Xanh duong, Den, Trang, Xam', 'Da cao cap', 'M Package, Harman Kardon', 'available', 1, 1, 1),

('BMW M3 Competition', 'M3C2024', 1, 2024, 4899000000, 4599000000, 'BMW M3 Competition so huu suc manh 550 HP tu dong co 3.0L TwinPower Turbo.', 'TwinPower Turbo', '3.0L', '550 HP', '650 Nm', 'Manual 6 cap', 'RWD', 'Petrol', '11.0 L/100km', '3.9s', '250 km/h', '4794 mm', '1902 mm', '1403 mm', '2857 mm', '1780 kg', 4, 'Den, Trang, Xam', 'Da cao cap', 'M Drive Pro', 'available', 1, 1, 0),

('BMW X7 xDrive40i', 'X740I2024', 2, 2024, 5099000000, 4799000000, 'BMW X7 xDrive40i la SUV hang sang co lon voi khong gian 7 cho.', 'TwinPower Turbo', '3.0L', '340 HP', '450 Nm', 'Auto 8 cap', 'AWD', 'Petrol', '12.5 L/100km', '6.1s', '250 km/h', '5151 mm', '2000 mm', '1805 mm', '3105 mm', '2360 kg', 7, 'Den, Trang, Xanh, Xam', 'Da Nappa', 'Panorama Roof', 'available', 1, 1, 1),

('BMW X5 xDrive40i', 'X540I2024', 2, 2024, 3499000000, 3299000000, 'BMW X5 xDrive40i mang den trai nghiem SUV hang sang.',
'TwinPower Turbo', '3.0L', '340 HP', '450 Nm', 'Auto 8 cap', 'AWD', 'Petrol', '11.8 L/100km', '5.7s', '250 km/h', '4922 mm', '2004 mm', '1776 mm', '2975 mm', '2130 kg', 5, 'Den, Trang, Xanh, Xam', 'Da cao cap', 'xOffroad Package', 'available', 1, 1, 1),

('BMW X6 xDrive40i', 'X640I2024', 2, 2024, 4299000000, 3999000000, 'BMW X6 la Coupe SUV dau tien tren the gioi.',
'TwinPower Turbo', '3.0L', '340 HP', '450 Nm', 'Auto 8 cap', 'AWD', 'Petrol', '12.0 L/100km', '5.9s', '250 km/h', '4935 mm', '2004 mm', '1696 mm', '2975 mm', '2180 kg', 5, 'Den, Trang, Xam', 'Da cao cap', 'Sport Package', 'available', 1, 1, 0),

('BMW X3 xDrive30i', 'X330I2024', 2, 2024, 2499000000, 2299000000, 'BMW X3 la SUV hang sang co trung.',
'TwinPower Turbo', '2.0L', '252 HP', '350 Nm', 'Auto 8 cap', 'AWD', 'Petrol', '10.5 L/100km', '6.8s', '250 km/h', '4716 mm', '1897 mm', '1676 mm', '2864 mm', '1910 kg', 5, 'Trang, Den, Xanh, Xam', 'Da cao cap', 'Parking Assistant', 'available', 1, 1, 1),

('BMW i8 Roadster', 'I8RO2024', 3, 2024, 7599000000, 6999000000, 'BMW i8 Roadster la sieu xe hybrid.',
'Hybrid', '1.5L', '374 HP', '570 Nm', 'Auto 6 cap', 'AWD', 'Hybrid', '4.7 L/100km', '4.6s', '250 km/h', '4689 mm', '1942 mm', '1295 mm', '2800 kg', 2, 'Xanh duong, Trang, Den', 'Da cao cap', 'Carbon Fiber', 'available', 1, 1, 0),

('BMW i4 eDrive40', 'I4ED2024', 3, 2024, 3399000000, 3199000000, 'BMW i4 la sedan dien hoa dau tien cua BMW.',
'Electric', '0', '340 HP', '430 Nm', 'Single Speed', 'RWD', 'Electric', '0', '5.7s', '180 km/h', '4783 mm', '1852 mm', '1448 mm', '2856 mm', '2050 kg', 5, 'Xanh, Trang, Den, Xam', 'Da Veganza', 'iDrive 8', 'available', 1, 1, 1),

('BMW iX xDrive40', 'IXDR2024', 3, 2024, 5999000000, 5599000000, 'BMW iX la SUV dien hang sang.',
'Dual Electric', '0', '523 HP', '765 Nm', 'Single Speed', 'AWD', 'Electric', '0', '4.6s', '250 km/h', '4953 mm', '2230 mm', '1696 mm', '3000 kg', 7, 'Xanh, Den, Trang', 'Da cao cap', 'Panorama Roof', 'available', 1, 1, 1),

('BMW 530Li M Sport', '530LI2024', 4, 2024, 2699000000, 2499000000, 'BMW 530Li M Sport la sedan hang sang co trung.',
'TwinPower Turbo', '2.0L', '252 HP', '350 Nm', 'Auto 8 cap', 'RWD', 'Petrol', '9.0 L/100km', '6.1s', '250 km/h', '5175 mm', '1900 mm', '1500 mm', '3108 mm', '1750 kg', 5, 'Den, Trang, Xam, Nau', 'Da Nappa', 'M Sport Package', 'available', 1, 1, 1),

('BMW 320i Sport', '320IS2024', 4, 2024, 1899000000, 1699000000, 'BMW 320i Sport la sedan entry-level.',
'TwinPower Turbo', '2.0L', '184 HP', '300 Nm', 'Auto 8 cap', 'RWD', 'Petrol', '8.0 L/100km', '7.0s', '235 km/h', '4735 mm', '1827 mm', '1435 mm', '2851 mm', '1560 kg', 5, 'Trang, Den, Xanh, Xam', 'Da cao cap', 'Live Cockpit', 'available', 1, 1, 1),

('BMW 840i Gran Coupe', '840IGC2024', 5, 2024, 5299000000, 4899000000, 'BMW 840i Gran Coupe la coupe 4 cua.',
'TwinPower Turbo', '3.0L', '340 HP', '500 Nm', 'Auto 8 cap', 'RWD', 'Petrol', '10.5 L/100km', '5.2s', '250 km/h', '5088 mm', '1942 mm', '1405 mm', '3023 mm', '1870 kg', 4, 'Den, Trang, Xam, Xanh', 'Da Nappa', 'Parking Assistant', 'available', 1, 0, 0);

-- Insert vehicle images
INSERT INTO vehicle_images (vehicle_id, image_url, image_type, display_order, is_primary) VALUES
(1, 'https://images.unsplash.com/photo-1617788138017-80a406023947?w=800', 'exterior', 1, 1),
(1, 'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=800', 'interior', 2, 0),

(2, 'https://images.unsplash.com/photo-1570789212138-a2bfbc7cb4e8?w=800', 'exterior', 1, 1),
(3, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800', 'exterior', 1, 1),
(4, 'https://images.unsplash.com/photo-1519641471654-76ce0103ad03?w=800', 'exterior', 1, 1),
(4, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800', 'interior', 2, 0),
(5, 'https://images.unsplash.com/photo-1619427405426-986f9ae3b7b7?w=800', 'exterior', 1, 1),
(6, 'https://images.unsplash.com/photo-1542362567-b07e543587a0?w=800', 'exterior', 1, 1),
(7, 'https://images.unsplash.com/photo-1544636331-e26879cd4e9b?w=800', 'exterior', 1, 1),
(8, 'https://images.unsplash.com/photo-1618614917418-09c9464b02c4?w=800', 'exterior', 1, 1),
(9, 'https://images.unsplash.com/photo-1637699807181-129093fb0702?w=800', 'exterior', 1, 1),
(10, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800', 'exterior', 1, 1),
(11, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800', 'exterior', 1, 1),
(12, 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=800', 'exterior', 1, 1);

-- Insert showrooms
INSERT INTO showrooms (showroom_name, address, phone, email, opening_hours, is_primary) VALUES
('BMW Showroom Ha Noi', 'So 1 Dai lo Thang Long, Quan Cau Giay, Ha Noi', '024 1234 5678', 'hanoi@bmw.com.vn', '08:00 - 20:00', 1),
('BMW Showroom TP.HCM', 'So 188 Nguyen Van Troi, Quan Phu Nhuan, TP.HCM', '028 1234 5678', 'hcmc@bmw.com.vn', '08:00 - 20:00', 0);

-- Verify
SELECT COUNT(*) as total FROM categories;
SELECT COUNT(*) as total FROM vehicles;
SELECT COUNT(*) as total FROM vehicle_images;