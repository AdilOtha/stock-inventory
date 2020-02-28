-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 28, 2020 at 02:38 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.1.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbms_project`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `addStock` (IN `id` VARCHAR(10), IN `quant` INT, IN `date` DATE)  NO SQL
BEGIN
	DECLARE fchar varchar(1);
    DECLARE avail int;
	select substring(id,1,1) into fchar;
    if fchar='p' THEN
    	update electronics set received_quantity=received_quantity+quant,in_stock=in_stock+quant,date_purchased=date,availability=1 WHERE pid=id;
    ELSEIF fchar='b' THEN
    	update books set received_quantity=received_quantity+quant,in_stock=in_stock+quant,date_purchased=date,availability=1 WHERE bid=id;
    end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRestockBooks` ()  NO SQL
    DETERMINISTIC
BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE c_bName varchar(30) DEFAULT NULL; 
    DECLARE c_bId varchar(10) DEFAULT NULL;	
    -- declare cursor for product to be ordered
    DECLARE curBook CURSOR FOR SELECT bid,b_name FROM books where in_stock<3;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
 	CREATE TABLE IF NOT EXISTS `dbms_project`.`restockproducts` ( `id` VARCHAR(10),  `name` VARCHAR(30)) ENGINE = InnoDB;
    
    OPEN curBook;
 
    getBook: LOOP
        FETCH curBook INTO c_bId,c_bName;
        IF finished = 1 THEN 
            LEAVE getBook;
        END IF;
        -- build table of products to be reordered
        INSERT INTO `restockproducts` (`id`, `name`) VALUES (c_bId, c_bName);
    END LOOP getBook;
    CLOSE curBook;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getRestockElectronics` ()  BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE c_pName varchar(30) DEFAULT NULL; 
    DECLARE c_pId varchar(10) DEFAULT NULL;	
    -- declare cursor for product to be ordered
    DECLARE curProduct CURSOR FOR SELECT pid,pname FROM electronics where in_stock<3 and category="Basic Components";
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
 	CREATE TABLE IF NOT EXISTS `dbms_project`.`restockproducts` ( `id` VARCHAR(10),  `name` VARCHAR(30)) ENGINE = InnoDB;
    
    OPEN curProduct;
 
    getProduct: LOOP
        FETCH curProduct INTO c_pId,c_pName;
        IF finished = 1 THEN 
            LEAVE getProduct;
        END IF;
        -- build table of products to be reordered
        INSERT INTO `restockproducts` (`id`, `name`) VALUES (c_pId, c_pName);
    END LOOP getProduct;
    CLOSE curProduct;
 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertOrdersElec` (IN `orderid` VARCHAR(10))  BEGIN
    -- exit if the duplicate key occurs
    DECLARE EXIT HANDLER FOR 1062
    BEGIN
     SELECT CONCAT('Duplicate key (',orderid,') occurred') AS message;
    END;
    
    -- insert a new row into the SupplierProducts
    INSERT INTO orders_elec(oeid)
    VALUES(orderid);
  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `paidAmountCheck_books` ()  NO SQL
BEGIN
    DECLARE finished INTEGER DEFAULT 0;    
    DECLARE c_amt int DEFAULT NULL;	
    -- declare cursor for product to be ordered
    DECLARE curNegCheck CURSOR FOR SELECT paid_amount FROM orders_books;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
 	CREATE TABLE IF NOT EXISTS `dbms_project`.`restockproducts` ( `id` VARCHAR(10),  `name` VARCHAR(30)) ENGINE = InnoDB;
    
    OPEN curNegCheck;
 
    getAmt: LOOP
        FETCH curNegCheck INTO c_amt;
        IF finished = 1 THEN 
            LEAVE getAmt;
        END IF;
        IF c_amt<0 then 
        SIGNAL SQLSTATE'45000'
        SET MESSAGE_TEXT="Paid Amount cannot be Negative!";
        END IF;
    END LOOP getAmt;
    CLOSE curNegCheck;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setDiscount_books` (IN `discount` INT, IN `aboveVal` INT)  NO SQL
BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE c_bId varchar(10) DEFAULT NULL;	
    -- declare cursor
    DECLARE curDisc CURSOR FOR SELECT bid FROM books where selling_cost>=aboveVal;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
    
    OPEN curDisc;
 
    getBook: LOOP
        FETCH curDisc INTO c_bId;
        IF finished = 1 THEN 
            LEAVE getBook;
        END IF;
        UPDATE books SET selling_cost=selling_cost-discount WHERE bid=c_bID;
    END LOOP getBook;
    CLOSE curDisc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setDiscount_elec` (IN `disc` INT, IN `aboveVal` INT)  NO SQL
BEGIN
    DECLARE finished INTEGER DEFAULT 0;
    DECLARE c_bId varchar(10) DEFAULT NULL;	
    -- declare cursor
    DECLARE curDisc CURSOR FOR SELECT pid FROM electronics where selling_cost>=aboveVal;
 
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;
    
    OPEN curDisc;
 
    getElec: LOOP
        FETCH curDisc INTO c_bId;
        IF finished = 1 THEN 
            LEAVE getElec;
        END IF;
        UPDATE electronics SET selling_cost=selling_cost-disc WHERE pid=c_bID;
    END LOOP getElec;
    CLOSE curDisc;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setGrandTotal_DueAmount_Books` (IN `orderid` VARCHAR(10), OUT `grandtotal` INT, OUT `da` INT, OUT `ps` BOOLEAN)  BEGIN
    DECLARE gt INT;
    -- call the function 
    SET grandtotal = getGrandTotal_books(orderid);
	update orders_books set grand_total=grandtotal where obid=orderid;
    set da=getDueAmount_books(orderid);
    if da=0 then
    set ps=1;
    update orders_books set due_amount=da, payment_status=1 where obid=orderid;
    else
    set ps=0;
    update orders_books set due_amount=da, payment_status=0 where obid=orderid;
    end if;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `setGrandTotal_DueAmount_Electronics` (IN `orderid` VARCHAR(10), OUT `grandtotal` INT, OUT `da` INT, OUT `ps` BOOLEAN)  NO SQL
BEGIN
    DECLARE gt INT;
    -- call the function 
    SET grandtotal = getGrandTotal_elec(orderid);
	update orders_elec set grand_total=grandtotal where oeid=orderid;
    set da=getDueAmount_elec(orderid);
    if da=0 then
    set ps=1;
    update orders_elec set due_amount=da, payment_status=1 where oeid=orderid;
    else
    set ps=0;
    update orders_elec set due_amount=da, payment_status=0 where oeid=orderid;
    end if;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `getDueAmount_books` (`orderid` VARCHAR(10)) RETURNS INT(11) NO SQL
BEGIN
    DECLARE pa,gt INT;
    DECLARE bk VARCHAR(10);
 	SET bk=orderid;    
    select grand_total,paid_amount into gt,pa from orders_books where obid=orderid;
    -- return the due amount
    RETURN (gt+(-pa));
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getDueAmount_elec` (`orderid` VARCHAR(10)) RETURNS INT(11) NO SQL
BEGIN
    DECLARE pa,gt INT;
    DECLARE bk VARCHAR(10);
 	SET bk=orderid;    
    select grand_total,paid_amount into gt,pa from orders_elec where oeid=orderid;
    -- return the due amount
    RETURN (gt+(-pa));
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getGrandTotal_books` (`orderid` VARCHAR(10)) RETURNS INT(11) BEGIN
    DECLARE sc,oq INT;
    DECLARE bk VARCHAR(10);    
 	SET bk=orderid;
    select b.selling_cost,o.ordered_quantity into sc,oq from books b inner join orders_books o on b.bid=o.bid where o.obid=bk;
    -- return the grand total
    RETURN (sc*oq);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getGrandTotal_elec` (`orderid` VARCHAR(10)) RETURNS INT(11) NO SQL
BEGIN
    DECLARE sc,oq INT;
    DECLARE bk VARCHAR(10);    
 	SET bk=orderid;
    select e.selling_cost,o.ordered_quantity into sc,oq from electronics e inner join orders_elec o on e.pid=o.pid where o.oeid=bk;
    -- return the grand total
    RETURN (sc*oq);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `bid` varchar(10) NOT NULL,
  `b_name` varchar(255) NOT NULL,
  `b_branch` varchar(3) NOT NULL,
  `b_sem` int(2) NOT NULL,
  `b_subject` varchar(30) NOT NULL,
  `received_quantity` int(3) NOT NULL,
  `date_purchased` date NOT NULL,
  `in_stock` int(3) NOT NULL,
  `cost_original` int(4) NOT NULL,
  `selling_cost` int(4) NOT NULL,
  `supplier_name` varchar(30) NOT NULL,
  `availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bid`, `b_name`, `b_branch`, `b_sem`, `b_subject`, `received_quantity`, `date_purchased`, `in_stock`, `cost_original`, `selling_cost`, `supplier_name`, `availability`) VALUES
('b001', 'Discreet Maths by CL Liu', 'IT', 3, 'DM', 5, '2019-08-19', 2, 300, 250, 'sooraj_anand', 1),
('b002', 'DBMS by Korth', 'CE', 3, 'DBMS', 8, '2019-10-03', 5, 400, 330, 'lovely_vadodara', 1),
('b003', 'DDC by Moris Mano', 'IT', 3, 'DDC', 6, '2019-08-19', 0, 500, 300, 'sooraj_anand', 0),
('b004', 'Learning Angular by Brad Dayley', 'CE', 5, 'AngularJS', 3, '2019-08-26', 0, 300, 250, 'lovely_vadodara', 0),
('b005', 'Head First Java by Bert Bates', 'IT', 5, 'CJT', 3, '2019-08-26', 0, 200, 150, 'sooraj_anand', 0),
('b006', 'AMP by Douglas', 'IT', 5, 'AMP', 5, '2019-08-26', 1, 500, 400, 'lovely_vadodara', 1),
('b007', 'CCN by Forouzan', 'IT', 3, 'CCN', 6, '2019-08-26', 2, 400, 330, 'sooraj_anand', 1),
('b008', 'DAA by Corman', 'IT', 5, 'DAA', 3, '2019-08-19', 0, 200, 150, 'lovely_vadodara', 0),
('b009', 'Engineering Drawing by ND Bhatt', 'MH', 1, 'Engineering Graphics', 5, '2019-08-19', 0, 250, 200, 'lovely_vadodara', 0),
('b011', 'Data Structures and Algorithms by Sartaj Sahni', 'IT', 4, 'DSA ', 6, '2019-10-18', 1, 500, 380, 'sooraj_anand', 1);

-- --------------------------------------------------------

--
-- Table structure for table `electronics`
--

CREATE TABLE `electronics` (
  `pid` varchar(10) NOT NULL,
  `pname` varchar(30) NOT NULL,
  `category` varchar(30) NOT NULL,
  `partof_kit` tinyint(1) NOT NULL,
  `received_quantity` int(3) DEFAULT NULL,
  `date_purchased` date NOT NULL,
  `in_stock` int(3) DEFAULT NULL,
  `cost_original` int(3) NOT NULL,
  `selling_cost` int(4) NOT NULL,
  `supplier_name` varchar(30) NOT NULL,
  `availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `electronics`
--

INSERT INTO `electronics` (`pid`, `pname`, `category`, `partof_kit`, `received_quantity`, `date_purchased`, `in_stock`, `cost_original`, `selling_cost`, `supplier_name`, `availability`) VALUES
('p001', '5V Adaptor', 'Basic Components', 0, 18, '2019-10-16', 9, 150, 115, 'lovely_vadodara', 1),
('p002', 'Heat Sink', 'Basic Components', 0, 15, '2019-08-26', 15, 15, 10, 'lovely_vadodara', 1),
('p003', 'Banana Pins', 'Basic Components', 0, 21, '2019-10-16', 16, 20, 10, 'lovely_vadodara', 1),
('p004', 'Crocodile Pins', 'Basic Components', 0, 36, '2019-10-16', 26, 20, 10, 'sooraj_anand', 1),
('p006', 'LiPo Charger', 'Basic Components', 0, 5, '2019-07-15', 1, 650, 535, 'sooraj_anand', 1),
('p007', 'Basic Electronics Kit', 'Kits', 0, NULL, '2019-08-26', NULL, 570, 394, 'lovely_vadodara', 1),
('p008', 'Connecting Wires', 'Basic Components', 0, 20, '2019-08-26', 15, 35, 27, 'sooraj_anand', 1),
('p009', 'Resistor Box', 'Basic Components', 1, 15, '2019-08-26', 10, 35, 27, 'lovely_vadodara', 1),
('p010', 'Breadboard', 'Basic Components', 1, 25, '2019-08-26', 18, 90, 65, 'sooraj_anand', 1),
('p011', 'Transformer', 'Basic Components', 1, 5, '2019-09-10', 10, 150, 115, 'lovely_vadodara', 1),
('p012', 'PCB', 'Basic Components', 1, 15, '2019-09-10', 15, 200, 115, 'lovely_vadodara', 1),
('p014', 'Kit for Variable Power Supply', 'Kits', 0, NULL, '2019-09-10', NULL, 300, 235, 'lovely_vadodara', 1);

-- --------------------------------------------------------

--
-- Table structure for table `kits`
--

CREATE TABLE `kits` (
  `kid` varchar(10) NOT NULL,
  `kname` varchar(30) NOT NULL,
  `kit_contents` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kits`
--

INSERT INTO `kits` (`kid`, `kname`, `kit_contents`) VALUES
('k001', 'Basic  Electronics Kit', 'Breadboard, Connecting Wires, Resistor Box'),
('k002', 'Kit For Variable Power Supply', '12-0-12 transformer*4 x 6 inch PCB*Capacitor x 3 (10uf, 100uf, 1000uf)*Connecting wires*Diode x 6*Potentiometer*Voltage regulator IC 7805*LM317 IC*LED'),
('k003', 'Robot Kit For Beginners', 'Two wheels metal chassis body*DC motor x 2*rubber coated wheels x 2*caster wheel*PCB*9V batteries with cap x 2*ive volt regulator IC*IR sensor*AND,OR,NOT gate ic*L293D module*Male Header*f2f and m2f jumper pin'),
('k004', 'Tool Kit', 'Soldering Wire*Solder Iron* Cutter*Double-sided tape-Screwdriver kit'),
('k005', 'Play with Arduino Kit', 'RGB led*Jumper wires m2m,m2f*Buzzer*Arduino with cable* Ultrasonic*LCD display*Potentiometer*Push switch*9v battery*LDR*Jack cable'),
('k006', 'Fingerprint Sensor GT511C3', 'Fingerprint Sensor*Wires'),
('k007', 'Engineering Graphics Kit', 'Mini Drafter (Omega)*Set Squares*Rounder Compass*Drawing Board Clips*Protractor*Ruler*Master circle'),
('k008', 'The Radar Kit', 'Servo Motor : 1x*\r\nSonic Sensor : 1x*\r\nJumper Cables : F2F 10x | M2M 10x\r\n'),
('k009', 'Constant Power Supply Kit', '12-0-12 transformer*\r\n4 x 6 inch PCB*\r\nCapacitor x 3 (10uf, 100uf, 1000uf)*\r\nConnecting wires*\r\nDiode x 6*\r\nVoltage regulator IC 7805*\r\nLM317 IC*\r\nLED'),
('k010', 'Capacitor Bank', '0.1uF X 2*1uF X 2*10uF X 2*100uF X 2');

-- --------------------------------------------------------

--
-- Table structure for table `kits_elec`
--

CREATE TABLE `kits_elec` (
  `kid` varchar(10) NOT NULL,
  `pid` varchar(10) NOT NULL,
  `kit_pro_quantity` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kits_elec`
--

INSERT INTO `kits_elec` (`kid`, `pid`, `kit_pro_quantity`) VALUES
('k001', 'p009', 2),
('k001', 'p010', 1),
('k002', 'p011', 1),
('k002', 'p012', 1);

--
-- Triggers `kits_elec`
--
DELIMITER $$
CREATE TRIGGER `removeKitProduct` AFTER DELETE ON `kits_elec` FOR EACH ROW begin
	declare a INT;
	update electronics set partof_kit=0 where pid=old.pid;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `nid` varchar(10) NOT NULL,
  `bid` varchar(10) NOT NULL,
  `n_branch` varchar(10) NOT NULL,
  `n_subject` varchar(30) NOT NULL,
  `n_sem` int(3) NOT NULL,
  `sessional_no.` int(1) DEFAULT NULL,
  `availability` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`nid`, `bid`, `n_branch`, `n_subject`, `n_sem`, `sessional_no.`, `availability`) VALUES
('n001', 'b001', 'IT', 'DM', 3, 2, 1),
('n002', 'b011', 'CE', 'DSA', 3, 1, 0),
('n003', 'b009', 'MH', 'EG', 1, 2, 1),
('n004', 'b007', 'IT', 'CCN', 3, 3, 0),
('n005', 'b004', 'CE', 'Angular JS', 5, 3, 1),
('n006', 'b003', 'IT', 'DDC', 6, 1, 0),
('n007', 'b002', 'CE', 'DBMS', 5, NULL, 1),
('n008', 'b005', 'IT', 'CJT', 3, NULL, 0),
('n009', 'b008', 'IT', 'DAA', 6, 2, 1),
('n010', 'b006', 'IT', 'AMP', 5, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders_books`
--

CREATE TABLE `orders_books` (
  `obid` varchar(10) NOT NULL,
  `sid` varchar(10) NOT NULL,
  `bid` varchar(10) NOT NULL,
  `order_date` date NOT NULL,
  `ordered_quantity` int(11) DEFAULT NULL,
  `grand_total` int(11) DEFAULT NULL,
  `paid_amount` int(11) DEFAULT NULL,
  `due_amount` int(11) DEFAULT NULL,
  `payment_status` tinyint(1) DEFAULT NULL,
  `order_status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_books`
--

INSERT INTO `orders_books` (`obid`, `sid`, `bid`, `order_date`, `ordered_quantity`, `grand_total`, `paid_amount`, `due_amount`, `payment_status`, `order_status`) VALUES
('ob001', 's001', 'b001', '2019-08-05', 1, 250, 250, 0, 1, 1),
('ob002', 's001', 'b002', '2019-08-05', 2, 660, 600, 60, 0, 1),
('ob003', 's002', 'b003', '2019-08-06', 2, 600, 600, 0, 1, 1),
('ob004', 's002', 'b006', '2019-10-11', 2, 800, 400, 400, 0, 1),
('ob005', 's002', 'b001', '2019-10-17', 1, 250, 200, 50, 0, 1),
('ob006', 's001', 'b001', '2019-10-17', 1, 250, 250, 0, 1, 1);

--
-- Triggers `orders_books`
--
DELIMITER $$
CREATE TRIGGER `updateBookStock` AFTER INSERT ON `orders_books` FOR EACH ROW begin
	declare a INT;
	update books set in_stock=(in_stock-new.ordered_quantity) where bid=new.bid;
    select in_stock into @a from books where bid=new.bid;
    if @a=0 then
		update books set availability=0 where bid=new.bid;
    end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders_elec`
--

CREATE TABLE `orders_elec` (
  `oeid` varchar(10) NOT NULL,
  `sid` varchar(10) NOT NULL,
  `pid` varchar(10) NOT NULL,
  `order_date` date NOT NULL,
  `ordered_quantity` int(11) NOT NULL,
  `grand_total` int(11) DEFAULT NULL,
  `paid_amount` int(11) NOT NULL,
  `due_amount` int(11) DEFAULT NULL,
  `payment_status` tinyint(1) DEFAULT NULL,
  `order_status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders_elec`
--

INSERT INTO `orders_elec` (`oeid`, `sid`, `pid`, `order_date`, `ordered_quantity`, `grand_total`, `paid_amount`, `due_amount`, `payment_status`, `order_status`) VALUES
('oe001', 's001', 'p003', '2019-08-07', 1, 10, 10, 0, 1, 1),
('oe002', 's003', 'p010', '2019-08-08', 2, 130, 120, 10, 0, 1),
('oe003', 's004', 'p001', '2019-08-07', 2, 240, 280, -40, 0, 1),
('oe004', 's005', 'p003', '2019-08-07', 19, 190, 100, 90, 0, 1),
('oe005', 's001', 'p001', '2019-10-17', 1, 120, 100, 20, 0, 1),
('oe006', 's002', 'p010', '2019-10-17', 1, 65, 65, 0, 1, 1),
('oe007', 's007', 'p007', '2019-10-20', 1, 399, 399, 0, 1, 1);

--
-- Triggers `orders_elec`
--
DELIMITER $$
CREATE TRIGGER `updateElecStock` AFTER INSERT ON `orders_elec` FOR EACH ROW begin
	declare a INT;
	update electronics set in_stock=(in_stock-new.ordered_quantity) where pid=new.pid;
    select in_stock into @a from electronics where pid=new.pid;
    if @a=0 then
		update electronics set availability=0 where pid=new.pid;
    end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `restockproducts`
--

CREATE TABLE `restockproducts` (
  `id` varchar(10) DEFAULT NULL,
  `name` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `restockproducts`
--

INSERT INTO `restockproducts` (`id`, `name`) VALUES
('b001', 'Discreet Maths by CL Liu'),
('b003', 'DDC by Moris Mano'),
('b004', 'Learning Angular by Brad Dayle'),
('b005', 'Head First Java by Bert Bates'),
('b006', 'AMP by Douglas'),
('b007', 'CCN by Forouzan'),
('b008', 'DAA by Corman'),
('b009', 'Engineering Drawing by ND Bhat'),
('b011', 'Data Structures and Algorithms'),
('p006', 'LiPo Charger'),
('b001', 'Discreet Maths by CL Liu'),
('b003', 'DDC by Moris Mano'),
('b004', 'Learning Angular by Brad Dayle'),
('b005', 'Head First Java by Bert Bates'),
('b006', 'AMP by Douglas'),
('b007', 'CCN by Forouzan'),
('b008', 'DAA by Corman'),
('b009', 'Engineering Drawing by ND Bhat'),
('b011', 'Data Structures and Algorithms'),
('p006', 'LiPo Charger'),
('b001', 'Discreet Maths by CL Liu'),
('b003', 'DDC by Moris Mano'),
('b004', 'Learning Angular by Brad Dayle'),
('b005', 'Head First Java by Bert Bates'),
('b006', 'AMP by Douglas'),
('b007', 'CCN by Forouzan'),
('b008', 'DAA by Corman'),
('b009', 'Engineering Drawing by ND Bhat'),
('b011', 'Data Structures and Algorithms'),
('p006', 'LiPo Charger');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `sid` varchar(10) NOT NULL,
  `sname` varchar(30) NOT NULL,
  `sbranch` varchar(30) NOT NULL,
  `ssem` int(1) NOT NULL,
  `elec_check` tinyint(1) NOT NULL,
  `books_check` tinyint(1) NOT NULL,
  `notes_check` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`sid`, `sname`, `sbranch`, `ssem`, `elec_check`, `books_check`, `notes_check`) VALUES
('s001', 'Adil', 'IT', 5, 1, 0, 1),
('s002', 'Himanshu', 'CE', 5, 1, 0, 0),
('s003', 'Sameep', 'MH', 3, 0, 1, 0),
('s004', 'Devanshu', 'IT', 5, 0, 1, 1),
('s005', 'Meet', 'CL', 7, 0, 0, 1),
('s006', 'Kush', 'EC', 1, 0, 1, 0),
('s007', 'Ramya', 'CE', 4, 1, 0, 0),
('s008', 'Tejas', 'IC', 5, 0, 1, 0),
('s009', 'Khilan', 'CE', 6, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `uploads`
--

CREATE TABLE `uploads` (
  `nid` varchar(10) NOT NULL,
  `sid` varchar(10) NOT NULL,
  `upload_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `uploads`
--

INSERT INTO `uploads` (`nid`, `sid`, `upload_date`) VALUES
('n001', 's001', '2019-08-08'),
('n002', 's001', '2019-08-07'),
('n003', 's003', '2019-08-08'),
('n007', 's006', '2019-08-09'),
('n009', 's002', '2019-08-09'),
('n010', 's002', '2019-08-09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`email`, `password`) VALUES
('adilotha@gmail.com', 'adil7797'),
('adilotha@gmail.com', 'adil7797');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`bid`);

--
-- Indexes for table `electronics`
--
ALTER TABLE `electronics`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `kits`
--
ALTER TABLE `kits`
  ADD PRIMARY KEY (`kid`);

--
-- Indexes for table `kits_elec`
--
ALTER TABLE `kits_elec`
  ADD PRIMARY KEY (`kid`,`pid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `bid` (`bid`);

--
-- Indexes for table `orders_books`
--
ALTER TABLE `orders_books`
  ADD PRIMARY KEY (`obid`),
  ADD KEY `bid` (`bid`),
  ADD KEY `sid` (`sid`);

--
-- Indexes for table `orders_elec`
--
ALTER TABLE `orders_elec`
  ADD PRIMARY KEY (`oeid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`sid`);

--
-- Indexes for table `uploads`
--
ALTER TABLE `uploads`
  ADD PRIMARY KEY (`nid`),
  ADD KEY `sid` (`sid`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kits_elec`
--
ALTER TABLE `kits_elec`
  ADD CONSTRAINT `kits_elec_ibfk_1` FOREIGN KEY (`kid`) REFERENCES `kits` (`kid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kits_elec_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `electronics` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `books` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders_books`
--
ALTER TABLE `orders_books`
  ADD CONSTRAINT `orders_books_ibfk_1` FOREIGN KEY (`bid`) REFERENCES `books` (`bid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_books_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `student` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders_elec`
--
ALTER TABLE `orders_elec`
  ADD CONSTRAINT `orders_elec_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `student` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_elec_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `electronics` (`pid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `uploads`
--
ALTER TABLE `uploads`
  ADD CONSTRAINT `uploads_ibfk_1` FOREIGN KEY (`nid`) REFERENCES `notes` (`nid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `uploads_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `student` (`sid`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
