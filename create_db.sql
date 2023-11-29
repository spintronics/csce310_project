-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `mydb` ;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `mydb` DEFAULT CHARACTER SET utf8 ;
USE `mydb` ;

-- -----------------------------------------------------
-- Table `mydb`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user` (
  `UIN` INT NOT NULL,
  `first_name` VARCHAR(45) NULL,
  `m_initial` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `username` VARCHAR(45) NULL,
  `password` VARCHAR(45) NULL,
  `user_type` VARCHAR(45) NULL,
  `email` VARCHAR(45) NULL,
  `discord_name` VARCHAR(45) NULL,
  `active_account` TINYINT NULL DEFAULT 1,
  PRIMARY KEY (`UIN`),
  INDEX `idx_username` (`username` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`programs`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`programs` (
  `program_num` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` MEDIUMTEXT NULL,
  PRIMARY KEY (`program_num`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`event`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`event` (
  `event_id` INT NOT NULL AUTO_INCREMENT,
  `start_date` DATE NULL,
  `time` TIME NULL,
  `location` VARCHAR(45) NULL,
  `end_date` DATE NULL,
  `event_type` VARCHAR(45) NULL,
  `UIN` INT NOT NULL,
  `program_num` INT NOT NULL,
  PRIMARY KEY (`event_id`),
  INDEX `fk_event_user_idx` (`UIN` ASC) VISIBLE,
  INDEX `fk_event_programs1_idx` (`program_num` ASC) VISIBLE,
  CONSTRAINT `fk_event_user`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`user` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_programs1`
    FOREIGN KEY (`program_num`)
    REFERENCES `mydb`.`programs` (`program_num`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`event_tracking`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`event_tracking` (
  `et_num` INT NOT NULL AUTO_INCREMENT,
  `event_event_id` INT NOT NULL,
  `user_UIN` INT NOT NULL,
  PRIMARY KEY (`et_num`),
  INDEX `fk_event_has_user_user1_idx` (`user_UIN` ASC) VISIBLE,
  INDEX `fk_event_has_user_event1_idx` (`event_event_id` ASC) VISIBLE,
  CONSTRAINT `fk_event_has_user_event1`
    FOREIGN KEY (`event_event_id`)
    REFERENCES `mydb`.`event` (`event_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_event_has_user_user1`
    FOREIGN KEY (`user_UIN`)
    REFERENCES `mydb`.`user` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`college_student`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`college_student` (
  `UIN` INT NOT NULL,
  `gender` VARCHAR(45) NULL,
  `hispanic_latino` VARCHAR(45) NULL,
  `race` VARCHAR(45) NULL,
  `us_citizen` TINYINT NULL,
  `first_generation` TINYINT NULL,
  `date_of_birth` DATE NULL,
  `gpa` FLOAT NULL,
  `major` VARCHAR(45) NULL,
  `minor_1` VARCHAR(45) NULL,
  `minor_2` VARCHAR(45) NULL,
  `expected_graduation` DATE NULL,
  `school` VARCHAR(45) NULL,
  `current_classification` VARCHAR(45) NULL,
  `student_type` VARCHAR(45) NULL,
  `phone` VARCHAR(45) NULL,
  INDEX `fk_college_student_user1_idx` (`UIN` ASC) VISIBLE,
  PRIMARY KEY (`UIN`),
  CONSTRAINT `fk_college_student_user1`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`user` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`documentation`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`documentation` (
  `doc_num` INT NOT NULL AUTO_INCREMENT,
  `link` VARCHAR(45) NULL,
  `doc_type` VARCHAR(45) NULL,
  PRIMARY KEY (`doc_num`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`application`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`application` (
  `app_num` INT NOT NULL AUTO_INCREMENT,
  `uncom_cert` TINYINT NULL,
  `com_cert` TINYINT NULL,
  `purpose_statement` MEDIUMTEXT NULL,
  `program_num` INT NOT NULL,
  `UIN` INT NOT NULL,
  PRIMARY KEY (`app_num`),
  INDEX `fk_application_programs1_idx` (`program_num` ASC) VISIBLE,
  INDEX `fk_application_college_student1_idx` (`UIN` ASC) VISIBLE,
  CONSTRAINT `fk_application_programs1`
    FOREIGN KEY (`program_num`)
    REFERENCES `mydb`.`programs` (`program_num`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_application_college_student1`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`college_student` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`track`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`track` (
  `tracking_num` INT NOT NULL AUTO_INCREMENT,
  `program_num` INT NOT NULL,
  `UIN` INT NOT NULL,
  PRIMARY KEY (`tracking_num`),
  INDEX `fk_track_programs1_idx` (`program_num` ASC) VISIBLE,
  INDEX `fk_track_college_student1_idx` (`UIN` ASC) VISIBLE,
  CONSTRAINT `fk_track_programs1`
    FOREIGN KEY (`program_num`)
    REFERENCES `mydb`.`programs` (`program_num`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_track_college_student1`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`college_student` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`certification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`certification` (
  `cert_id` INT NOT NULL AUTO_INCREMENT,
  `level` VARCHAR(45) NULL,
  `name` VARCHAR(45) NULL,
  `description` MEDIUMTEXT NULL,
  PRIMARY KEY (`cert_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`cert_enrollment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`cert_enrollment` (
  `cert_num` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL,
  `training_status` VARCHAR(45) NULL,
  `semester` VARCHAR(45) NULL,
  `year` INT NULL,
  `program_num` INT NOT NULL,
  `UIN` INT NOT NULL,
  `cert_id` INT NOT NULL,
  PRIMARY KEY (`cert_num`),
  INDEX `fk_cert_enrollment_programs1_idx` (`program_num` ASC) VISIBLE,
  INDEX `fk_cert_enrollment_college_student1_idx` (`UIN` ASC) VISIBLE,
  INDEX `fk_cert_enrollment_certification1_idx` (`cert_id` ASC) VISIBLE,
  CONSTRAINT `fk_cert_enrollment_programs1`
    FOREIGN KEY (`program_num`)
    REFERENCES `mydb`.`programs` (`program_num`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cert_enrollment_college_student1`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`college_student` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_cert_enrollment_certification1`
    FOREIGN KEY (`cert_id`)
    REFERENCES `mydb`.`certification` (`cert_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`internship`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`internship` (
  `intern_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` MEDIUMTEXT NULL,
  `is_gov` TINYINT NULL,
  PRIMARY KEY (`intern_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`intern_app`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`intern_app` (
  `ia_num` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL,
  `year` INT NULL,
  `intern_id` INT NOT NULL,
  `UIN` INT NOT NULL,
  PRIMARY KEY (`ia_num`),
  INDEX `fk_intern_app_internship1_idx` (`intern_id` ASC) VISIBLE,
  INDEX `fk_intern_app_college_student1_idx` (`UIN` ASC) VISIBLE,
  CONSTRAINT `fk_intern_app_internship1`
    FOREIGN KEY (`intern_id`)
    REFERENCES `mydb`.`internship` (`intern_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_intern_app_college_student1`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`college_student` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`class`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`class` (
  `class_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` MEDIUMTEXT NULL,
  `type` VARCHAR(45) NULL,
  PRIMARY KEY (`class_id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `mydb`.`class_enrollment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`class_enrollment` (
  `ce_num` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL,
  `semester` VARCHAR(45) NULL,
  `year` INT NULL,
  `class_id` INT NOT NULL,
  `UIN` INT NOT NULL,
  PRIMARY KEY (`ce_num`),
  INDEX `fk_class_enrollment_class1_idx` (`class_id` ASC) VISIBLE,
  INDEX `fk_class_enrollment_college_student1_idx` (`UIN` ASC) VISIBLE,
  CONSTRAINT `fk_class_enrollment_class1`
    FOREIGN KEY (`class_id`)
    REFERENCES `mydb`.`class` (`class_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_class_enrollment_college_student1`
    FOREIGN KEY (`UIN`)
    REFERENCES `mydb`.`college_student` (`UIN`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

USE `mydb` ;

-- -----------------------------------------------------
-- Placeholder table for view `mydb`.`user_student`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `mydb`.`user_student` (`UIN` INT, `first_name` INT, `m_initial` INT, `last_name` INT, `username` INT, `password` INT, `user_type` INT, `email` INT, `discord_name` INT, `active_account` INT, `gender` INT, `hispanic_latino` INT, `race` INT, `us_citizen` INT, `first_generation` INT, `date_of_birth` INT, `gpa` INT, `major` INT, `minor_1` INT, `minor_2` INT, `expected_graduation` INT, `school` INT, `current_classification` INT, `student_type` INT, `phone` INT);

-- -----------------------------------------------------
-- View `mydb`.`user_student`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mydb`.`user_student`;
USE `mydb`;
CREATE  OR REPLACE VIEW `user_student` AS
select * from `user` join `college_student` on `user`.UIN = `college_student`.UIN;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
