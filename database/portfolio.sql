CREATE DATABASE IF NOT EXISTS portfolio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE portfolio_db;
CREATE TABLE users(id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) UNIQUE NOT NULL,password_hash VARCHAR(255) NOT NULL,status TINYINT DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE projects(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(150) NOT NULL,description TEXT NOT NULL,category ENUM('web','php','uiux','3d','database') DEFAULT 'web',technologies VARCHAR(255),image VARCHAR(255),live_url VARCHAR(255),github_url VARCHAR(255),featured TINYINT DEFAULT 0,status TINYINT DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,INDEX(category),INDEX(featured));
CREATE TABLE skills(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(60) NOT NULL,category VARCHAR(40) NOT NULL,level TINYINT NOT NULL,status TINYINT DEFAULT 1);
CREATE TABLE testimonials(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),position VARCHAR(120),rating TINYINT DEFAULT 5,review TEXT,status TINYINT DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE blog_posts(id INT AUTO_INCREMENT PRIMARY KEY,title VARCHAR(180),slug VARCHAR(190) UNIQUE,category VARCHAR(60),content TEXT,author VARCHAR(80),status TINYINT DEFAULT 1,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE contact_messages(id INT AUTO_INCREMENT PRIMARY KEY,name VARCHAR(100),email VARCHAR(150),subject VARCHAR(180),message TEXT,is_read TINYINT DEFAULT 0,created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
INSERT INTO users(username,password_hash) VALUES('admin','$6$pjxDwfMXn01WFiFb$zjmsBeA10o0y1xzXFK1N49J0MWHl9Nl0ZpJS8iicsLyxDoZo7SsyjMCj4EIFWUDns2KDp.XBykaDp5y1fFqSW1');
INSERT INTO projects(title,description,category,technologies,live_url,github_url,featured) VALUES
('Orbit Commerce','A headless storefront with a PHP order API, stock tracking and a sub-second product search.','php','PHP,MySQL,JavaScript','#','#',1),
('Pulse Analytics','A live dashboard that turns raw MySQL event logs into charts and weekly summaries.','database','MySQL,Canvas,PHP','#','#',0),
('Helix Studio Site','An agency site with CSS 3D scenes and scroll choreography.','3d','CSS 3D,JavaScript','#','#',0),
('Fieldnote App','A mobile-first note taking interface designed and built end to end.','uiux','Figma,HTML,CSS','#','#',0),
('Lumen Booking','Appointment booking for clinics with email confirmations and an admin calendar.','web','PHP,MySQL,JavaScript','#','#',0);
INSERT INTO skills(name,category,level) VALUES('HTML5','Frontend',95),('CSS3','Frontend',92),('JavaScript','Frontend',88),('PHP','Backend',90),('MySQL','Database',86),('Git','Tools',84),('Figma','Design',78);
INSERT INTO testimonials(name,position,rating,review) VALUES
('Amina Rauf','Founder, Lumen Health',5,'Delivered a booking system our front desk adopted in a day. Clear communication throughout.'),
('Daniel Brooks','CTO, Orbit Retail',5,'Clean code, fast pages and a handover our team could actually maintain.'),
('Sara Malik','Product Lead, Helix',5,'The 3D landing page lifted demo requests and still scored well on speed.');
INSERT INTO blog_posts(title,slug,category,content,author) VALUES('Making CSS 3D feel fast','making-css-3d-feel-fast','Frontend','Keep transforms on the compositor, limit blur and animate only transform and opacity.','Nexa');
