INSERT INTO `rent_a_car`.`category` VALUES (1,'Manual',100.00,null);
INSERT INTO `rent_a_car`.`category` VALUES (2,'Automatic',100.00,null);
INSERT INTO `rent_a_car`.`category` VALUES (3,'Economy',85.00,1);
INSERT INTO `rent_a_car`.`category` VALUES (4,'Compact',95.00,1);
INSERT INTO `rent_a_car`.`category` VALUES (5,'Standard',115.00,2);
INSERT INTO `rent_a_car`.`category` VALUES (6,'Premium',130.00,2);

INSERT INTO `rent_a_car`.`car` VALUES (1,111111,'Opel','Astra','2016-03-05','Black','NumOfSeats:5, NumOfDoors:4, Fuel: Dizel',4,'111111-Opel-Astra');
INSERT INTO `rent_a_car`.`car` VALUES (2,222222,'BMW',5,'2016-03-05','Blue','NumOfSeats:5, NumOfDoors:4, Fuel: Dizel',5,'222222-BMW-5');

INSERT INTO `rent_a_car`.`reservation` VALUES (1,'2021-07-29','2021-08-03',1);
INSERT INTO `rent_a_car`.`reservation` VALUES (2,'2021-08-09','2021-08-13',1);
