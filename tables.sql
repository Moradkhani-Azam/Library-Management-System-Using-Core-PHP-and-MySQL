CREATE TABLE IF NOT EXISTS users (
	id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	full_name VARCHAR(80) NOT NULL,
	address VARCHAR(100) NOT NULL,
	phone VARCHAR(15) NOT NULL,
	email VARCHAR(50) NOT NULL,
	password CHAR(60) NOT NULL,
	role_id SMALLINT UNSIGNED NOT NULL
);

CREATE TABLE IF NOT EXISTS roles (
	id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(50) NOT NULL
);

INSERT INTO `roles` VALUES
('1','administrator'),
('2','employee'),
('3','member');

INSERT INTO `users` (`full_name`, `address`, `phone`, `email`, `password`, `role_id`) VALUES
('Admin Name', '29221 Hyatt Prairie Suite', '(930) 483-6295', 'admin@gmail.com', '$2y$10$DAQQFTye9H8uYcH8iYBTV.aq1zqcNmuikFIctNTN3hqh3CezJyTaW', '1'),
('Employee Name', '424 Jameson Junction Port Name', '(930) 438-6925', 'employee@gmail.com', '$2y$10$DAQQFTye9H8uYcH8iYBTV.aq1zqcNmuikFIctNTN3hqh3CezJyTaW', '2'),
('Lynn Powlowski DVM', '399 Greenfelder Fork East Lamon', '(930) 514-7541', 'member@gmail.com', '$2y$10$DAQQFTye9H8uYcH8iYBTV.aq1zqcNmuikFIctNTN3hqh3CezJyTaW', '3'),
('Lucy Cechtelar', '426 Jordy Lodge Cartwrightshire', '(888) 937-7238', 'tkshlerin@collins.com', '$2y$10$DAQQFTye9H8uYcH8iYBTV.aq1zqcNmuikFIctNTN3hqh3CezJyTaW', '3'),
('Romaguera Ltd', '426 Jordy Lodge Cartwrightshire', '(938) 937-7238', 'maurice20@example.org', '$2y$10$DAQQFTye9H8uYcH8iYBTV.aq1zqcNmuikFIctNTN3hqh3CezJyTaW', '3');


CREATE TABLE IF NOT EXISTS documents (
	id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(50) NOT NULL,
	author VARCHAR(80) NOT NULL,
	published_year SMALLINT UNSIGNED NOT NULL,
	category VARCHAR(80) NOT NULL,
	type VARCHAR(50) NOT NULL,
	genre VARCHAR(80) NOT NULL,
	description TEXT,
	isbn VARCHAR(13) DEFAULT NULL
);

INSERT INTO `documents` (`title`, `author`, `category`, `published_year`, `type`, `genre`, `description`, `isbn`) VALUES
('The Art of Thinking Clearly', 'Rolf Dobelli', 'Decision making', 2013, 'adult', 'Non-fiction', 'The Art of Thinking Clearly is a 2013 book by the Swiss writer Rolf Dobelli which describes in short chapters 99 of the most common thinking errors - ranging from cognitive biases to envy and social distortions', '978-0-06-221968-8'),
('X-Men: God Loves, Man Kills', 'Chris', 'Comics', 1998, 'teen', 'sci-fi', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '9782616052277'),
('Mike Tyson : Undisputed Truth', 'Larry Sloman, Mike Tyson', 'Sports', 1970,'teen', 'documentary', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL),
('When Breath Becomes Air', 'Paul Kalanithi', 'Medical', 1999,'adult', 'drama','Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', NULL),
('The Great Gatsby', 'F. Scott Fitzgerald', 'Fiction', 2014,'adult', 'horror', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', '9885691200700');

CREATE TABLE IF NOT EXISTS requests (
	user_id BIGINT(20) UNSIGNED NOT NULL,
	document_id BIGINT(20) UNSIGNED NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	status VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS loans (
	user_id BIGINT(20) UNSIGNED NOT NULL,
	document_id BIGINT(20) UNSIGNED NOT NULL,
	created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	expected_return_at TIMESTAMP NULL DEFAULT NULL,
	status VARCHAR(10)
);