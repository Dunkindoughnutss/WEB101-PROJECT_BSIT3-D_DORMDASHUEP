--22/12/2025 changes --

ALTER TABLE bh_listing
MODIFY roomtype VARCHAR(255),
MODIFY amenities VARCHAR(255);

ALTER TABLE bh_listing
modify roomtype enum('Single Room', 'Studio Room', 'Shared Room');


ALTER TABLE users ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE feedback 
MODIFY COLUMN fdbk_id INT AUTO_INCREMENT;

SELECT u.user_id, u.email, r.renterName 
FROM users u
LEFT JOIN renter_details r ON u.user_id = r.user_id
WHERE u.role = 'renter'
ORDER BY u.user_id ASC;
