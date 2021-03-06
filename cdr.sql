CREATE TABLE `cdr` (
  `idCDR` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Incremented PK',
  `adId` varchar(25) DEFAULT NULL COMMENT 'unique adId during booking/add advertisement',
  `adType` tinyint(3) DEFAULT '0' COMMENT '1-words/lines/2-length/3-cm etc',
  `callType` varchar(1) DEFAULT NULL COMMENT 'I/O',
  `category` varchar(25) DEFAULT NULL COMMENT 'SE-Services',
  `subcategory` varchar(25) DEFAULT NULL COMMENT 'Sub category',
  `tertiaryCategory` varchar(25) DEFAULT NULL COMMENT '3rd level category',
  `status` tinyint(3) DEFAULT '0' COMMENT '0-NotAnswered/1-Answered',
  `result` tinyint(3) DEFAULT '0' COMMENT '0-NOT_PATCHED,1-PATCHED,2-NBH,3-AGENT_CALLBACK,4-REJECTED,5-UNAVAILABLE',
  `uniqId` bigint(20) DEFAULT '0' COMMENT 'unique call id',
  `branch` varchar(25) DEFAULT NULL COMMENT 'Branch who booked an ad',
  `subOffice` varchar(25) DEFAULT NULL COMMENT 'Sub Office who booked an ad',
  `adPrice` double DEFAULT '0' COMMENT 'ad price',
  `duration` double DEFAULT '0' COMMENT 'call duration in sec',
  `dialType` tinyint(3) DEFAULT '0' COMMENT '0-seq/1-parallel',
  `didNumber` varchar(40) DEFAULT NULL COMMENT 'Virtual Number',
  `prospectNumber` varchar(40) DEFAULT NULL COMMENT 'Prospect Number',
  `actualNumber` varchar(40) DEFAULT NULL COMMENT 'Advertiser Number',
  `locationCode` varchar(32) DEFAULT NULL COMMENT 'Hosted location of TECD service',
  `voiceRecordId` varchar(255) DEFAULT NULL COMMENT 'Voice Record Id; Not sharing to TOI',
  `createdDT` datetime NOT NULL COMMENT 'call received time',
  `answeredTime` datetime DEFAULT NULL COMMENT 'call Answered time',
  `disconnectTime` datetime DEFAULT NULL COMMENT 'call Disconnect time',
  `updatedDT` datetime DEFAULT NULL,
  PRIMARY KEY (`idCDR`),
  KEY `createdDT` (`createdDT`),
  KEY `STATUS` (`status`),
  KEY `result` (`result`),
  KEY `uniqId` (`uniqId`),
  KEY `cdr_didNumber` (`didNumber`),
  KEY `cdr_callType` (`callType`),
  KEY `cdr_adType` (`adType`),
  KEY `cdr_category` (`category`),
  KEY `cdr_subcategory` (`subcategory`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `cdr` */

insert  into `cdr`(`idCDR`,`adId`,`adType`,`callType`,`category`,`subcategory`,`tertiaryCategory`,`status`,`result`,`uniqId`,`branch`,`subOffice`,`adPrice`,`duration`,`dialType`,`didNumber`,`prospectNumber`,`actualNumber`,`locationCode`,`voiceRecordId`,`createdDT`,`answeredTime`,`disconnectTime`,`updatedDT`) values (1,'AD12345',1,'I','SE','AA','BB',0,1,123456,'Mumbai','Andheri East',10,25,0,'+912230123000','+919845618720','+919845712345','Mumbai','1','2016-12-23 10:30:25','2016-12-23 10:30:45','2016-12-23 10:31:25',NULL);