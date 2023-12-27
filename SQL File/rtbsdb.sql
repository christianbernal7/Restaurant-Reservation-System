
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";



CREATE TABLE `tbladmin` (
  `ID` int(11) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `AdminuserName` varchar(20) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp(),
  `UserType` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `AdminuserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`, `UserType`) VALUES
(1, 'Admin', 'admin', 1234596321, 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2023-05-21 18:30:00', 1);


DELIMITER //

CREATE PROCEDURE AddAdmin(
    IN p_AdminName VARCHAR(120),
    IN p_AdminuserName VARCHAR(20),
    IN p_MobileNumber BIGINT,
    IN p_Email VARCHAR(120),
    IN p_Password VARCHAR(120),
    IN p_UserType INT
)
BEGIN
    INSERT INTO tbladmin (
        AdminName,
        AdminuserName,
        MobileNumber,
        Email,
        Password,
        UserType
    ) VALUES (
        p_AdminName,
        p_AdminuserName,
        p_MobileNumber,
        p_Email,
        p_Password,
        p_UserType
    );
END //

DELIMITER ;








CREATE TABLE `CustomerInfo` (
  `id` INT(11) NOT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `fullName` VARCHAR(200) DEFAULT NULL,
  `emailId` VARCHAR(200) DEFAULT NULL,
  `phoneNumber` VARCHAR(13) DEFAULT NULL,
  `noAdults` BIGINT(20) DEFAULT NULL,
  `noChildrens` BIGINT(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bookingNo_UNIQUE` (`bookingNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `ReservationInfo` (
  `id` INT(11) NOT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `bookingDate` DATE DEFAULT NULL,
  `bookingTime` VARCHAR(100) DEFAULT NULL,  
  `tableId` INT(11) DEFAULT NULL,
  `adminremark` VARCHAR(255) DEFAULT NULL,
  `boookingStatus` VARCHAR(15) DEFAULT NULL,
  `postingDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  `updationDate` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
PRIMARY KEY (`id`),
  INDEX `fk_ReservationInfo_CustomerInfo_idx` (`bookingNo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `ReservationInfo`
ADD CONSTRAINT `fk_ReservationInfo_CustomerInfo`
FOREIGN KEY (`bookingNo`)
REFERENCES `CustomerInfo` (`bookingNo`)
ON DELETE CASCADE;
-- --------------------------------------------------------




CREATE TABLE `CustomerInfo_Audit` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `action` ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  `original_id` INT(11) DEFAULT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `fullName` VARCHAR(200) DEFAULT NULL,
  `emailId` VARCHAR(200) DEFAULT NULL,
  `phoneNumber` VARCHAR(13) DEFAULT NULL,
  `noAdults` BIGINT(20) DEFAULT NULL,
  `noChildrens` BIGINT(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




CREATE TABLE `ReservationInfo_Audit` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `action` ENUM('INSERT', 'UPDATE', 'DELETE') NOT NULL,
  `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  `original_id` INT(11) DEFAULT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `bookingDate` DATE DEFAULT NULL,
  `bookingTime` VARCHAR(100) DEFAULT NULL,  
  `tableId` INT(11) DEFAULT NULL,
  `adminremark` VARCHAR(255) DEFAULT NULL,
  `boookingStatus` VARCHAR(15) DEFAULT NULL,
  `postingDate` TIMESTAMP NULL DEFAULT NULL,
  `updationDate` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Trigger for INSERT
DELIMITER //
CREATE TRIGGER CustomerInfo_Audit_Trigger_Insert
AFTER INSERT ON CustomerInfo
FOR EACH ROW
BEGIN
  INSERT INTO CustomerInfo_Audit (action, original_id, bookingNo, fullName, emailId, phoneNumber, noAdults, noChildrens)
  VALUES ('INSERT', NULL, NEW.bookingNo, NEW.fullName, NEW.emailId, NEW.phoneNumber, NEW.noAdults, NEW.noChildrens);
END;
//

-- Trigger for UPDATE
DELIMITER //
CREATE TRIGGER CustomerInfo_Audit_Trigger_Update
AFTER UPDATE ON CustomerInfo
FOR EACH ROW
BEGIN
  INSERT INTO CustomerInfo_Audit (action, original_id, bookingNo, fullName, emailId, phoneNumber, noAdults, noChildrens)
  VALUES ('UPDATE', OLD.id, NEW.bookingNo, NEW.fullName, NEW.emailId, NEW.phoneNumber, NEW.noAdults, NEW.noChildrens);
END;
//

-- Trigger for DELETE
DELIMITER //
CREATE TRIGGER CustomerInfo_Audit_Trigger_Delete
AFTER DELETE ON CustomerInfo
FOR EACH ROW
BEGIN
  INSERT INTO CustomerInfo_Audit (action, original_id, bookingNo, fullName, emailId, phoneNumber, noAdults, noChildrens)
  VALUES ('DELETE', OLD.id, OLD.bookingNo, OLD.fullName, OLD.emailId, OLD.phoneNumber, OLD.noAdults, OLD.noChildrens);
END;
//
DELIMITER ;



DELIMITER //

CREATE TRIGGER reservation_info_after_insert
AFTER INSERT ON ReservationInfo
FOR EACH ROW
BEGIN
    INSERT INTO ReservationInfo_Audit (action, original_id, bookingNo, bookingDate, bookingTime, tableId, adminremark, boookingStatus, postingDate, updationDate)
    VALUES ('INSERT', NULL, NEW.bookingNo, NEW.bookingDate, NEW.bookingTime, NEW.tableId, NEW.adminremark, NEW.boookingStatus, NEW.postingDate, NEW.updationDate);
END;
//

DELIMITER //
CREATE TRIGGER reservation_info_after_update
AFTER UPDATE ON ReservationInfo
FOR EACH ROW
BEGIN
    INSERT INTO ReservationInfo_Audit (action, original_id, bookingNo, bookingDate, bookingTime, tableId, adminremark, boookingStatus, postingDate, updationDate)
    VALUES ('UPDATE', OLD.id, NEW.bookingNo, NEW.bookingDate, NEW.bookingTime, NEW.tableId, NEW.adminremark, NEW.boookingStatus, NEW.postingDate, NEW.updationDate);
END;
//

DELIMITER //
CREATE TRIGGER reservation_info_after_delete
AFTER DELETE ON ReservationInfo
FOR EACH ROW
BEGIN
    INSERT INTO ReservationInfo_Audit (action, original_id, bookingNo, bookingDate, bookingTime, tableId, adminremark, boookingStatus, postingDate, updationDate)
    VALUES ('DELETE', OLD.id, OLD.bookingNo, OLD.bookingDate, OLD.bookingTime, OLD.tableId, OLD.adminremark, OLD.boookingStatus, OLD.postingDate, OLD.updationDate);
END;

//
DELIMITER ;






CREATE TABLE `tblrestables` (
  `id` int(11) NOT NULL,
  `tableNumber` varchar(100) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT current_timestamp(),
  `AddedBy` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DELIMITER //

CREATE PROCEDURE AddTable(
    IN p_tableNumber VARCHAR(100),
    IN p_AddedBy INT
)
BEGIN
    INSERT INTO tblrestables (tableNumber, AddedBy)
    VALUES (p_tableNumber, p_AddedBy);
END //

DELIMITER ;


INSERT INTO `tblrestables` (`id`, `tableNumber`, `creationDate`, `AddedBy`) VALUES
(1, '1A', '2023-05-27 03:50:35', 2),
(2, '1B', '2023-05-27 03:50:55', 2),
(3, '2A', '2023-05-27 03:51:01', 2),
(4, '2B', '2023-05-27 03:51:07', 2),
(5, '3A', '2023-05-27 03:51:11', 2),
(6, '3B', '2023-05-27 03:51:15', 2);

--
--

--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
--

--
--
ALTER TABLE `tblrestables`
  ADD PRIMARY KEY (`id`);

--
--

--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--

ALTER TABLE `customerinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
ALTER TABLE `tblrestables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;




CREATE TABLE `deleted_customerInfo` (
  `id` INT(11) NOT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `fullName` VARCHAR(200) DEFAULT NULL,
  `emailId` VARCHAR(200) DEFAULT NULL,
  `phoneNumber` VARCHAR(13) DEFAULT NULL,
  `noAdults` BIGINT(20) DEFAULT NULL,
  `noChildrens` BIGINT(20) DEFAULT NULL,
  `deleted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `deleted_reservationInfo` (
  `id` INT(11) NOT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `bookingDate` DATE DEFAULT NULL,
  `bookingTime` VARCHAR(100) DEFAULT NULL,  
  `tableId` INT(11) DEFAULT NULL,
  `adminremark` VARCHAR(255) DEFAULT NULL,
  `boookingStatus` VARCHAR(15) DEFAULT NULL,
  `postingDate` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP(),
  `updationDate` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(),
  `deleted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;






CREATE TABLE `BackupReservationInfo` (
  `id` int(11) NOT NULL,
  `bookingNo` bigint(12) DEFAULT NULL,
  `bookingDate` date DEFAULT NULL,
  `bookingTime` varchar(100) DEFAULT NULL,
  `tableId` int(11) DEFAULT NULL,
  `adminremark` varchar(255) DEFAULT NULL,
  `boookingStatus` varchar(15) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



CREATE TABLE `backupcustomerInfo` (
  `id` INT(11) NOT NULL,
  `bookingNo` BIGINT(12) UNSIGNED NOT NULL,
  `fullName` VARCHAR(200) DEFAULT NULL,
  `emailId` VARCHAR(200) DEFAULT NULL,
  `phoneNumber` VARCHAR(13) DEFAULT NULL,
  `noAdults` BIGINT(20) DEFAULT NULL,
  `noChildrens` BIGINT(20) DEFAULT NULL,
  `backupDate` TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DELIMITER //

CREATE PROCEDURE CheckNameCount (IN p_fullname VARCHAR(255), OUT p_nameCount INT)
BEGIN
    SELECT COUNT(*) INTO p_nameCount
    FROM CustomerInfo
    WHERE fullname = p_fullname;
END //

DELIMITER ;







-- VIEWS


-- Create a view for BackupReservationInfo
CREATE VIEW view_BackupReservationInfo AS
SELECT
    r.id AS reservationId,
    r.bookingNo,
    r.bookingDate,
    r.bookingTime,
    r.tableId,
    r.adminremark,
    r.boookingStatus,
    r.postingDate,
    r.updationDate,
    c.fullName AS customerFullName,
    c.emailId AS customerEmailId,
    c.phoneNumber AS customerPhoneNumber,
    c.noAdults,
    c.noChildrens,
    c.backupDate AS customerBackupDate
FROM
    BackupReservationInfo r
JOIN
    backupcustomerInfo c ON r.bookingNo = c.bookingNo;


-- View for joining CustomerInfo and ReservationInfo
CREATE VIEW `audit_CustomerReservationInfo` AS
SELECT
    ci.`id` AS `customer_id`,
    ci.`action` AS `actionCi`,
    ci.`bookingNo`,
    ci.`fullName`,
    ci.`emailId`,
    ci.`phoneNumber`,
    ci.`noAdults`,
    ci.`noChildrens`,
    ri.`id` AS `reservation_id`,
    ri.`action` AS `actionRi`,
    ri.`bookingDate`,
    ri.`bookingTime`,
    ri.`tableId`,
    ri.`adminremark`,
    ri.`boookingStatus`,
    ri.`postingDate`,
    ri.`updationDate`
FROM
    `customerinfo_audit` ci
JOIN
    `reservationinfo_audit` ri ON ci.`bookingNo` = ri.`bookingNo`;



DELIMITER //
CREATE PROCEDURE audit_CustomerReservationInfo()
BEGIN
    SELECT COUNT(*) AS auditBookingsCount FROM audit_CustomerReservationInfo;
    
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE audit_CustomerReservationInfos()
BEGIN
    SELECT * FROM audit_CustomerReservationInfo;
END //
DELIMITER ;



DELIMITER //
CREATE PROCEDURE view_BackupReservationInfo()
BEGIN
    SELECT COUNT(*) AS backupBookingsCount FROM view_BackupReservationInfo;


END //
DELIMITER ;



DELIMITER //
CREATE PROCEDURE view_BackupReservationInfos()
BEGIN
    SELECT * FROM view_BackupReservationInfo;
END //
DELIMITER ;




CREATE UNIQUE INDEX unique_email ON CustomerInfo(emailId);

SET GLOBAL event_scheduler = ON;  


DELIMITER //

CREATE EVENT delete_expired_booking
ON SCHEDULE EVERY 1 WEEK
DO
BEGIN
    DECLARE expired_id INT;

    SELECT id INTO expired_id FROM ReservationInfo 
    WHERE bookingDate < NOW() AND bookingTime < NOW() AND boookingStatus = 'Accepted'
    LIMIT 1;

    IF expired_id IS NOT NULL THEN
        DELETE FROM ReservationInfo WHERE id = expired_id;

        DELETE FROM CustomerInfo WHERE id = expired_id;
       
    END IF;
END //

DELIMITER ;






DELIMITER //

CREATE EVENT delete_old_rejected_bookings
ON SCHEDULE EVERY 1 WEEK
DO
BEGIN
  DELETE FROM `ReservationInfo`
  WHERE `boookingStatus` = 'Rejected' AND `rejectionDate` < NOW() - INTERVAL 1 WEEK;
END//

DELIMITER ;









DELIMITER //

CREATE TRIGGER before_delete_customerInfo
BEFORE DELETE
ON CustomerInfo FOR EACH ROW
BEGIN
  INSERT INTO deleted_customerInfo (id, bookingNo, fullName, emailId, phoneNumber, noAdults, noChildrens)
  VALUES (OLD.id, OLD.bookingNo, OLD.fullName, OLD.emailId, OLD.phoneNumber, OLD.noAdults, OLD.noChildrens);
END//

CREATE TRIGGER before_delete_reservationInfo
BEFORE DELETE
ON ReservationInfo FOR EACH ROW
BEGIN
  INSERT INTO deleted_reservationInfo (id, bookingNo, bookingDate, bookingTime, tableId, adminremark, boookingStatus, postingDate, updationDate)
  VALUES (OLD.id, OLD.bookingNo, OLD.bookingDate, OLD.bookingTime, OLD.tableId, OLD.adminremark, OLD.boookingStatus, OLD.postingDate, OLD.updationDate);
END//

DELIMITER ;




DELIMITER //

CREATE TRIGGER after_update_customerInfo
AFTER INSERT
ON CustomerInfo FOR EACH ROW
BEGIN
  INSERT INTO backupcustomerInfo (id, bookingNo, fullName, emailId, phoneNumber, noAdults, noChildrens)
  VALUES (NEW.id, NEW.bookingNo, NEW.fullName, NEW.emailId, NEW.phoneNumber, NEW.noAdults, NEW.noChildrens);
END//

DELIMITER ;





DELIMITER //

CREATE TRIGGER backup_reservation_info
AFTER UPDATE ON ReservationInfo
FOR EACH ROW
BEGIN
    INSERT INTO BackupReservationInfo (
        id, bookingNo, bookingDate, bookingTime, tableId, adminremark, 
        boookingStatus, postingDate, updationDate
    )
    VALUES (
        OLD.id, NEW.bookingNo, NEW.bookingDate, NEW.bookingTime, NEW.tableId, 
        NEW.adminremark, NEW.boookingStatus, NEW.postingDate, NEW.updationDate
    );
END //

DELIMITER ;






CREATE VIEW all_booking_info AS
    SELECT CustomerInfo.*, ReservationInfo.bookingDate, ReservationInfo.bookingTime, ReservationInfo.tableId, ReservationInfo.adminremark, ReservationInfo.boookingStatus, ReservationInfo.postingDate, ReservationInfo.updationDate FROM CustomerInfo JOIN reservationinfo ON CustomerInfo.id = ReservationInfo.id;


CREATE VIEW deleted_booking AS
    SELECT deleted_customerInfo.id, deleted_customerInfo.bookingNo, deleted_customerInfo.fullName, deleted_customerInfo.emailId, deleted_customerInfo.phoneNumber, deleted_customerInfo.noAdults, deleted_customerInfo.noChildrens, deleted_reservationinfo.bookingDate, deleted_reservationinfo.bookingTime,  deleted_reservationinfo.tableId,  deleted_reservationinfo.adminremark, deleted_reservationinfo.boookingStatus,  deleted_reservationinfo.postingDate,  deleted_reservationinfo.updationDate
    FROM deleted_customerInfo JOIN deleted_reservationinfo ON deleted_customerInfo.id = deleted_reservationinfo.id;



DELIMITER //
CREATE PROCEDURE all_booking_info()
BEGIN
    SELECT * FROM all_booking_info;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE deleted_bookings()
BEGIN
    SELECT * FROM deleted_booking;
END //
DELIMITER ;



DELIMITER //
CREATE PROCEDURE deleted_booking(IN booking_id INT)
BEGIN
    SELECT * FROM deleted_booking WHERE id = booking_id;
END //
DELIMITER ;






DELIMITER //

CREATE PROCEDURE UpdateReservation (
    IN p_id INT,
    IN p_bookingDate DATE,
    IN p_bookingTime VARCHAR(100),
    IN p_noAdults INT,
    IN p_noChildrens INT
)
BEGIN
   
    UPDATE ReservationInfo
    SET 
        bookingDate = p_bookingDate,
        bookingTime = p_bookingTime
    WHERE id = p_id;

  
    UPDATE CustomerInfo
    SET 
        noAdults = p_noAdults,
        noChildrens = p_noChildrens
    WHERE id = p_id;

    SELECT "Updated Successfully" AS message;
END //

DELIMITER ;



DELIMITER //

CREATE PROCEDURE DeleteTable(
    IN p_tid INT
)
BEGIN
    DELETE FROM tblrestables WHERE id = p_tid;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE DeleteSubadmin(
    IN p_subadminid INT
)
BEGIN
    DELETE FROM tbladmin WHERE ID = p_subadminid;
END //

DELIMITER ;



DELIMITER //

CREATE PROCEDURE GetTableData()
BEGIN
    SELECT id, tableNumber FROM tblrestables;
END //

DELIMITER ;







DELIMITER //

CREATE PROCEDURE UpdateReservationInfo(
    IN p_id INT,
    IN p_adminremark VARCHAR(255),
    IN p_boookingStatus VARCHAR(15),
    IN p_tableId INT
)
BEGIN
    UPDATE ReservationInfo
    SET adminremark = p_adminremark,
        boookingStatus = p_boookingStatus,
        tableId = p_tableId
    WHERE id = p_id;
END //

DELIMITER ;



DELIMITER //

CREATE PROCEDURE GetConflictingReservations(
    IN p_btime VARCHAR(100),
    IN p_endTime VARCHAR(100),
    IN p_tableId INT,
    IN p_bdate DATE
)
BEGIN
    SELECT * FROM ReservationInfo
    WHERE
        (TIME(p_btime) BETWEEN TIME(bookingTime) AND p_endTime
        OR p_endTime BETWEEN TIME(bookingTime) AND p_endTime
        OR TIME(bookingTime) BETWEEN TIME(p_btime) AND p_endTime)
        AND tableId = p_tableId
        AND bookingDate = p_bdate
        AND boookingStatus = 'Accepted';
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE GetTableDetails()
BEGIN
    SELECT 
        tbladmin.AdminName,
        tblrestables.id AS tid,
        tblrestables.tableNumber,
        tblrestables.creationDate
    FROM 
        tblrestables
    LEFT JOIN 
        tbladmin ON tbladmin.ID = tblrestables.AddedBy;
END //

DELIMITER ;










DELIMITER //

CREATE PROCEDURE GetAcceptedBookings()
BEGIN
    SELECT 
        ci.*,
        ri.bookingDate,
        ri.bookingTime,
        ri.boookingStatus,
        ri.updationDate,
        ri.postingDate
    FROM 
        CustomerInfo ci
    LEFT JOIN 
        ReservationInfo ri ON ci.id = ri.id
    WHERE 
        ri.boookingstatus = 'Accepted';
END //

DELIMITER ;









DELIMITER //

CREATE PROCEDURE GetCustomerReservationInfo(IN bid_param INT)
BEGIN
    SELECT ci.*, ri.bookingDate, ri.bookingTime, ri.boookingStatus, ri.updationDate, ri.adminremark
    FROM CustomerInfo ci
    LEFT JOIN ReservationInfo ri ON ci.id = ri.id
    WHERE ci.id = bid_param;
END //

DELIMITER ;





DELIMITER //

CREATE PROCEDURE GetNewBookings()
BEGIN
    SELECT 
        ci.*,
        ri.bookingDate,
        ri.bookingTime,
        ri.postingDate
    FROM 
        CustomerInfo ci
    LEFT JOIN 
        ReservationInfo ri ON ci.id = ri.id
    WHERE 
        ri.boookingStatus IS NULL OR ri.boookingStatus = '';
END //

DELIMITER ;



DELIMITER //

CREATE PROCEDURE GetRejectedBookings()
BEGIN
    SELECT 
        ci.*,
        ri.bookingDate,
        ri.bookingTime,
        ri.boookingStatus,
        ri.updationDate,
        ri.postingDate
    FROM 
        CustomerInfo ci
    LEFT JOIN 
        ReservationInfo ri ON ci.id = ri.id
    WHERE 
        ri.boookingStatus = 'Rejected';
END //

DELIMITER ;








DELIMITER //

CREATE PROCEDURE ChangeAdminPassword(
    IN p_admin_id INT,
    IN p_current_password VARCHAR(255),
    IN p_new_password VARCHAR(255)
)
BEGIN
    DECLARE existing_password VARCHAR(255);

    SELECT Password INTO existing_password
    FROM tbladmin
    WHERE ID = p_admin_id;

    IF existing_password = p_current_password THEN
        UPDATE tbladmin
        SET Password = p_new_password
        WHERE ID = p_admin_id;

        SELECT 'success' AS result;
    ELSE
        SELECT 'error' AS result;
    END IF;
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE CheckUsernameAvailability(
    IN p_username VARCHAR(255),
    OUT p_result VARCHAR(50)
)
BEGIN
    DECLARE username_count INT;

    SELECT COUNT(*) INTO username_count
    FROM tbladmin
    WHERE AdminuserName = p_username;

    IF username_count > 0 THEN
        SET p_result = 'exists';
    ELSE
        SET p_result = 'available';
    END IF;
END //

DELIMITER ;






DELIMITER //

CREATE PROCEDURE GetSubAdminCount(
    OUT p_subadmin_count INT
)
BEGIN
    SELECT COUNT(*) INTO p_subadmin_count
    FROM tbladmin
    WHERE UserType = 0;
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE AuthenticateUser(
    IN p_username VARCHAR(255),
    IN p_password_hash VARCHAR(255),
    OUT p_user_id INT,
    OUT p_user_name VARCHAR(255),
    OUT p_user_type INT
)
BEGIN
    SELECT ID, AdminuserName, UserType INTO p_user_id, p_user_name, p_user_type
    FROM tbladmin
    WHERE AdminuserName = p_username AND Password = p_password_hash;
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE ResetPassword(
    IN p_username VARCHAR(255),
    IN p_mobile_number VARCHAR(15),
    IN p_new_password VARCHAR(255),
    OUT p_success INT
)
BEGIN
    DECLARE user_id INT;

    SELECT ID INTO user_id
    FROM tbladmin
    WHERE AdminuserName = p_username AND MobileNumber = p_mobile_number;

    IF user_id IS NOT NULL THEN
        UPDATE tbladmin
        SET Password = p_new_password
        WHERE ID = user_id;
        
        SET p_success = 1; 
    ELSE
        SET p_success = 0; 
    END IF;
END //

DELIMITER ;




DELIMITER //
CREATE PROCEDURE UpdateAdminProfile(
    IN p_adminid INT,
    IN p_fname VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_mobileno VARCHAR(15)
)
BEGIN
    UPDATE tbladmin
    SET
        AdminName = p_fname,
        MobileNumber = p_mobileno,
        Email = p_email
    WHERE ID = p_adminid;

    SELECT ROW_COUNT() AS success;
END //
DELIMITER ;




DELIMITER //

CREATE PROCEDURE UpdateSubAdminProfile(
    IN p_subadminid INT,
    IN p_fname VARCHAR(255),
    IN p_email VARCHAR(255),
    IN p_mobileno VARCHAR(15)
)
BEGIN
    UPDATE tbladmin
    SET AdminName = p_fname,
        MobileNumber = p_mobileno,
        Email = p_email
    WHERE UserType = 0 AND ID = p_subadminid;
END //

DELIMITER ;



DELIMITER //

CREATE PROCEDURE UpdateSubAdminPassword(
    IN p_subadminid INT,
    IN p_password VARCHAR(255)
)
BEGIN
    UPDATE tbladmin
    SET Password = p_password
    WHERE UserType = 0 AND ID = p_subadminid;
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE GetSubAdminById(
    IN p_said INT
)
BEGIN
    SELECT ID, AdminuserName, AdminName, Email, MobileNumber
    FROM tbladmin
    WHERE UserType = 0 AND ID = p_said;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE GetAdminDetailsById(
    IN p_admin_id INT
)
BEGIN
    SELECT *
    FROM tbladmin
    WHERE ID = p_admin_id;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE CheckEmailCount(
    IN p_email VARCHAR(200),
    OUT p_count INT
)
BEGIN
    SELECT COUNT(*) INTO p_count
    FROM CustomerInfo
    WHERE emailId = p_email;
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE GetAllSubAdmins()
BEGIN
    SELECT *
    FROM tbladmin
    WHERE UserType = 0;
END //

DELIMITER ;





DELIMITER //

CREATE PROCEDURE GetBookingDetails(
    IN p_booking_status VARCHAR(15)
)
BEGIN
    SELECT 
        c.id,
        c.bookingNo,
        c.fullName,
        c.emailId,
        c.phoneNumber,
        c.noAdults,
        c.noChildrens,
        r.bookingDate,
        r.bookingTime,
        r.tableId,
        r.adminremark,
        r.boookingStatus,
        r.postingDate,
        r.updationDate
    FROM
        CustomerInfo c
    LEFT JOIN ReservationInfo r ON c.id = r.id
    WHERE
        r.boookingStatus = p_booking_status;

END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE GetBookingsByDateRange(
    IN p_fdate DATE,
    IN p_tdate DATE
)
BEGIN
    SELECT 
        bookingNo,
        fullName,
        emailId,
        phoneNumber,
        noAdults,
        noChildrens,
        bookingDate,
        bookingTime,
        postingDate
    FROM
        tblbookings
    WHERE
        bookingDate BETWEEN p_fdate AND p_tdate;

END //

DELIMITER ;

DELIMITER //

CREATE PROCEDURE GetAllBookingsCount()
BEGIN
    SELECT COUNT(*) AS totalBookings FROM ReservationInfo;
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE GetNewBookingsCount()
BEGIN
    SELECT COUNT(*) AS newBookingsCount
    FROM ReservationInfo
    WHERE boookingStatus IS NULL OR boookingStatus = '';
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE GetAcceptedBookingsCount()
BEGIN
    SELECT COUNT(*) AS acceptedBookingsCount
    FROM ReservationInfo
    WHERE boookingStatus = 'Accepted';
END //

DELIMITER ;


DELIMITER //

CREATE PROCEDURE GetRejectedBookingsCount()
BEGIN
    SELECT COUNT(*) AS rejectedBookingsCount
    FROM ReservationInfo
    WHERE boookingStatus = 'Rejected';
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE GetBookingDetailsByID(
    IN p_id INT
)
BEGIN
    SELECT 
        ci.bookingNo,
        ci.fullName,
        ci.emailId,
        ci.phoneNumber,
        ci.noAdults,
        ci.noChildrens,
        ri.bookingDate,
        ri.bookingTime,
        ri.boookingStatus,
        ri.updationDate,
        ri.adminremark,
        ri.postingDate
    FROM
        CustomerInfo ci
    LEFT JOIN ReservationInfo ri ON ci.id = ri.id
    WHERE
        ci.id = p_id;

END //

DELIMITER ;








DELIMITER //

CREATE PROCEDURE Getinfobookings(
    IN p_id INT
)
BEGIN
    SELECT 
        c.bookingNo,
        c.fullName,
        c.emailId,
        c.phoneNumber,
        c.noAdults,
        c.noChildrens,
        r.bookingDate,
        r.bookingTime,
        r.tableId,
        r.adminremark,
        r.boookingStatus,
        r.postingDate,
        r.updationDate
    FROM
        CustomerInfo c
    LEFT JOIN ReservationInfo r ON c.id = r.id
    WHERE
        c.id = p_id;

END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE DeleteBooking (
  IN p_id INT
)
BEGIN
    DELETE FROM ReservationInfo
    WHERE id = p_id;

    DELETE FROM CustomerInfo
    WHERE id = p_id;


    SELECT 'Deleted Successfully' AS message;
END //

DELIMITER ;




DELIMITER //

CREATE PROCEDURE InsertBooking (
  IN p_bookingno INT,
  IN p_fullname VARCHAR(200),
  IN p_emailid VARCHAR(200),
  IN p_phonenumber VARCHAR(13),
  IN p_bookingdate DATE,
  IN p_bookingtime VARCHAR(100),
  IN p_noadults INT(20),
  IN p_nochildrens INT(20)
)
BEGIN
    DECLARE v_customer_id INT;

    IF p_fullname = '' OR p_emailid = '' OR p_phonenumber = '' OR p_bookingdate IS NULL OR p_bookingtime = '' OR p_noadults IS NULL OR p_nochildrens IS NULL THEN
        SELECT 'Please fill up the required parameters' AS msg;
    ELSE
        SELECT id INTO v_customer_id FROM CustomerInfo WHERE fullName = p_fullname;

        IF v_customer_id IS NULL THEN
            INSERT INTO CustomerInfo (bookingNo, fullName, emailId, phoneNumber, noAdults, noChildrens) VALUES (p_bookingno, p_fullname, p_emailid, p_phonenumber, p_noadults, p_nochildrens);
            SELECT LAST_INSERT_ID() INTO v_customer_id;
        END IF;

        INSERT INTO ReservationInfo (id, bookingNo, bookingDate, bookingTime) VALUES (v_customer_id, p_bookingno, p_bookingdate, p_bookingtime);

        SELECT 'Booking Added Successfully' AS msg;
    END IF;
END //

DELIMITER ;

