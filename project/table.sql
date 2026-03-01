
-- to store temporary file 
CREATE TABLE `temp_uploaded_file` (
  `file_id` varchar(100) NOT NULL,
  `file_name` varchar(100) NOT NULL,
  PRIMARY KEY (`file_id`)
)  ; 

-- to store topic file 
CREATE TABLE `topic` (
  `topic_name` varchar(100) DEFAULT NULL,
  `unit` varchar(10) DEFAULT NULL
) ;

-- to store feedback    
CREATE TABLE `feedback` (
   `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(100) NOT NULL ,
  `email` varchar(100) NOT NULL ,
  `feedback` MEDIUMTEXT NOT NULL,
  `receivedAt`DATETIME default now(), 
  `status` varchar(10)

) ;