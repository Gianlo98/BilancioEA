-- MySQL dump 10.13  Distrib 5.7.16, for Linux (x86_64)
--
-- Host: localhost    Database: bilancioeconomia
-- ------------------------------------------------------
-- Server version	5.7.16-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `accounts_id` varchar(5) NOT NULL,
  `accounts_name` varchar(100) NOT NULL,
  `accounts_id_type` varchar(40) NOT NULL,
  `accounts_nature` enum('E','F') NOT NULL,
  `accounts_eccedence` enum('A','D','AD') NOT NULL,
  `rectified` tinyint(1) NOT NULL DEFAULT '0',
  `accounts_categories_id` varchar(3) NOT NULL,
  `balance_sheet_row_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`accounts_id`),
  KEY `accounts_id_type` (`accounts_id_type`),
  KEY `income_statement_rows` (`balance_sheet_row_id`),
  KEY `accounts_categories_id` (`accounts_categories_id`),
  CONSTRAINT `accounts_ibfk_3` FOREIGN KEY (`balance_sheet_row_id`) REFERENCES `balance_sheet_rows` (`row_id`),
  CONSTRAINT `accounts_ibfk_4` FOREIGN KEY (`accounts_id_type`) REFERENCES `accounts_types` (`name`),
  CONSTRAINT `accounts_ibfk_5` FOREIGN KEY (`accounts_categories_id`) REFERENCES `accounts_categories` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
INSERT INTO `accounts` VALUES ('20.01','Prodotti c/vendite','ricavo d’esercizio ','E','A',0,'20',2),('20.02','Lavorazioni c/terzi','ricavo d’esercizio ','E','A',0,'20',2),('20.04','Lavori su ordinazione','ricavo d’esercizio ','E','A',0,'20',2),('20.05','Prodotti c/vendite interne','ricavo d’esercizio ','E','A',0,'20',2),('20.10','Resi su vendite','rettifica di ricavo','E','D',1,'20',2),('20.11','Ribassi e abbuoni passivi','rettifica di ricavo','E','D',1,'20',2),('20.12','Premi su vendite','rettifica di ricavo','E','D',1,'20',2),('20.20','Prodotti in lavorazione c/esistenze iniziali','costo d’esercizio ','E','D',1,'20',3),('20.21','Semilavorati c/esistenze iniziali','costo d’esercizio ','E','D',1,'20',3),('20.22','Prodotti c/esistenze iniziali','costo d’esercizio ','E','D',1,'20',3),('20.25','Semilavorati c/apporti','costo d’esercizio ','E','D',1,'20',3),('20.26','Prodotti c/apporti','costo d’esercizio ','E','D',1,'20',3),('20.30','Prodotti in lavorazione c/rimanenze finali','rettifica costo d’esercizio','E','A',0,'20',3),('20.31','Semilavorati c/rimanenze finali','rettifica costo d’esercizio','E','A',0,'20',3),('20.32','Prodotti c/rimanenze finali','rettifica costo d’esercizio','E','A',0,'20',3),('20.40','Lavori in corso c/esistenze iniziali','costo d’esercizio ','E','D',0,'20',4),('20.41','Lavori in corso c/rimanenze finali','rettifica costo d’esercizio','E','A',0,'20',4),('20.50','Costruzioni interne','rettifica costo d’esercizio','E','A',0,'20',5),('20.51','Costi di ricerca e sviluppo rinviati','rettifica costo d’esercizio','E','A',0,'20',5),('21.01','Fitti attivi','ricavo d’esercizio ','E','A',0,'21',6),('21.02','Proventi vari','ricavo d’esercizio ','E','A',0,'21',6),('21.10','Arrotondamenti attivi','ricavo d’esercizio ','E','A',0,'21',6),('21.20','Plusvalenza ordinarie','ricavo d’esercizio ','E','A',0,'21',6),('21.21','Plusvalenza straordinaria','ricavo d’esercizio ','E','A',0,'21',6),('21.30','Sopravvenienze attive ordinarie','ricavo d’esercizio ','E','A',0,'21',6),('21.31','Sopravvenienze attive straordinarie','ricavo d’esercizio ','E','A',0,'21',6),('21.40','Rimborsi costi di vendita','ricavo d’esercizio ','E','A',0,'21',6),('21.45','Rivalutazione terreni e fabbricati\r\n','ricavo d’esercizio ','E','A',0,'21',6),('21.46','Rivalutazione impianti e macchinari','ricavo d’esercizio ','E','A',0,'21',6),('21.50','Contributi c/esercizio','ricavo d’esercizio ','E','A',0,'21',6),('21.52','Contributi c/impianti','ricavo d’esercizio ','E','A',0,'21',6),('30.01','Materie prime c/acquisti','costo d’esercizio ','E','D',0,'30',8),('30.02','Materie prime c/acquisti estero','costo d’esercizio ','E','D',0,'30',8),('30.03','Materie sussidiarie c/acquisti','costo d’esercizio ','E','D',0,'30',8),('30.04','Materie di consumo c/acquisti','costo d’esercizio ','E','D',0,'30',8),('30.05','Materie prime c/apporti','costo d’esercizio ','E','D',0,'30',8),('30.06','Materie sussidiarie c/apporti','costo d’esercizio ','E','D',0,'30',8),('30.07','Materie di consumo c/apporti','costo d’esercizio ','E','D',0,'30',8),('30.10','Resi su acquisti','rettifica costo d’esercizio','E','A',1,'30',8),('30.11','Ribassi e abbuoni attivi','rettifica costo d’esercizio','E','A',1,'30',8),('30.12','Premi su acquisti','rettifica costo d’esercizio','E','A',1,'30',8),('31.01','Costi di trasporto','costo d’esercizio ','E','D',0,'31',9),('31.02','Costi per energia','costo d’esercizio ','E','D',0,'31',9),('31.03','Pubblicità','costo d’esercizio ','E','D',0,'31',9),('31.04','Consulenze','costo d’esercizio ','E','D',0,'31',9),('31.05','Costi postali','costo d’esercizio ','E','D',0,'31',9),('31.06','Costi telefonici','costo d’esercizio ','E','D',0,'31',9),('31.07','Assicurazioni','costo d’esercizio ','E','D',0,'31',9),('31.08','Costi di vigilanza','costo d’esercizio ','E','D',0,'31',9),('31.09','Costi per i locali','costo d’esercizio ','E','D',0,'31',9),('31.10','Costi esercizio automezzi','costo d’esercizio ','E','D',0,'31',9),('31.11','Manutenzioni e riparazioni','costo d’esercizio ','E','D',0,'31',9),('31.12','Provvigioni passive','costo d’esercizio ','E','D',0,'31',9),('31.13','Costi d\'incasso','costo d’esercizio ','E','D',0,'31',9),('31.14','Commissioni bancarie','costo d’esercizio ','E','D',0,'31',9),('31.16','Oneri di factoring','costo d’esercizio ','E','D',0,'31',9),('31.19','Lavorazioni presso terzi','costo d’esercizio ','E','D',0,'31',9),('31.20','Competenze amministratori','costo d’esercizio ','E','D',0,'31',9),('31.21','Competenze sindaci','costo d’esercizio ','E','D',0,'31',9),('31.22','Competenze società di revisione','costo d’esercizio ','E','D',0,'31',9),('32.01','Fitti passivi','costo d’esercizio ','E','D',0,'32',10),('32.02','Canoni leasing','costo d’esercizio ','E','D',0,'32',10),('33.01','Salari e stipendi','costo d’esercizio ','E','D',0,'33',12),('33.02','Oneri sociali','costo d’esercizio ','E','D',0,'33',13),('33.03','TFR','costo d’esercizio ','E','D',0,'33',14),('33.04','Costi lavoro internale','costo d’esercizio ','E','D',0,'33',16),('34.01','Ammortamento costi di impianto','costo d’esercizio ','E','D',0,'34',18),('34.02','Ammortamento costi di ampliamento','costo d’esercizio ','E','D',0,'34',18),('34.03','Ammortamento costi di ricerca e sviluppo','costo d’esercizio ','E','D',0,'34',18),('34.04','Ammortamento costi di pubblicità','costo d’esercizio ','E','D',0,'34',18),('34.05','Ammortamento brevetti','costo d’esercizio ','E','D',0,'34',18),('34.06','Ammortamento software','costo d’esercizio ','E','D',0,'34',18),('34.07','Ammortamento concessioni e licenze','costo d’esercizio ','E','D',0,'34',18),('34.08','Ammortamento avviamento','costo d’esercizio ','E','D',0,'34',18),('35.01','Ammortamento fabbricati','costo d’esercizio ','E','D',0,'35',19),('35.02','Ammortamento impianti e macchinari','costo d’esercizio ','E','D',0,'35',19),('35.03','Ammortamento attrezzature industriali','costo d’esercizio ','E','D',0,'35',19),('35.04','Ammortamento attrezzature commerciali','costo d’esercizio ','E','D',0,'35',19),('35.05','Ammortamento macchine d’ufficio','costo d’esercizio ','E','D',0,'35',19),('35.06','Ammortamento arredamento','costo d’esercizio ','E','D',0,'35',19),('35.07','Ammortamento automezzi','costo d’esercizio ','E','D',0,'35',19),('35.08','Ammortamento imballaggi durevoli','costo d’esercizio ','E','D',0,'35',19),('36.01','Svalutazione terreni e fabbricati','costo d’esercizio ','E','D',0,'36',20),('36.02','Svalutazione impianti','costo d’esercizio ','E','D',0,'36',20),('36.05','Svalutazione brevetti industriali','costo d’esercizio ','E','D',0,'36',20),('36.10','Svalutazione crediti','costo d’esercizio ','E','D',0,'36',21),('37.01','Materie prime c/esistenze iniziali','costo d’esercizio ','E','D',0,'37',22),('37.02','Materie sussidiarie c/esistenze iniziali\r\n','costo d’esercizio ','E','D',0,'37',22),('37.03','Materie di consumo c/esistenze iniziali','costo d’esercizio ','E','D',0,'37',22),('37.10','Materie prime c/rimanenze finali','rettifica costo d’esercizio','E','A',1,'37',22),('37.11','Materie sussidiarie c/rimanenze finali\r\n','rettifica costo d’esercizio','E','A',1,'37',22),('37.12','Materie di consumo c/rimanenze finali','rettifica costo d’esercizio','E','A',1,'37',22),('38.04','Accantonamento per responsabilità civile','costo d’esercizio ','E','D',0,'38',23),('38.10','Accantonamento per garanzie prodotti','costo d’esercizio ','E','D',0,'38',24),('38.11','Accant. per manutenzioni programmate','costo d’esercizio ','E','D',0,'38',24),('38.12','Accant. per buoni sconto e concorsi','costo d’esercizio ','E','D',0,'38',24),('39.01','Oneri fiscali diversi','costo d’esercizio ','E','D',0,'39',25),('39.05','Perdite su crediti','costo d’esercizio ','E','D',0,'39',25),('39.10','Arrotondamenti passivi','costo d’esercizio ','E','D',0,'39',25),('39.20','Minusvalenze ordinarie','costo d’esercizio ','E','D',0,'39',25),('39.21','Minusvalenze straordinarie','costo d’esercizio ','E','D',0,'39',25),('39.30','Sopravvenienze passive ordinarie','costo d’esercizio ','E','D',0,'39',25),('39.31','Sopravvenienze passive straordinarie','costo d’esercizio ','E','D',0,'39',25),('39.40','Insussistenze passive ordinarie','costo d’esercizio ','E','D',0,'39',25),('39.41','Insussistenze passive straordinarie','costo d’esercizio ','E','D',0,'41',25),('40.01','Proventi da partecipazioni','ricavo d’esercizio ','E','A',0,'40',27),('40.10','Interessi attivi v/controllate','ricavo d’esercizio ','E','A',0,'40',29),('40.11','Interessi attivi v/collegate','ricavo d’esercizio ','E','A',0,'40',29),('40.12','Interessi attivi v/controllanti','ricavo d’esercizio ','E','A',0,'40',29),('40.15','Proventi su titoli immobilizzati','ricavo d’esercizio ','E','A',0,'40',30),('40.20','Interessi su titoli','ricavo d’esercizio ','E','A',0,'40',31),('40.30','Interessi attivi v/clienti','ricavo d’esercizio ','E','A',0,'40',32),('40.31','Interessi attivi bancari','ricavo d’esercizio ','E','A',0,'40',32),('40.32','Interessi attivi postali','ricavo d’esercizio ','E','A',0,'40',32),('40.60','Proventi finanziari diversi','ricavo d’esercizio ','E','A',0,'40',32),('41.01','Interessi passivi v/fornitori','costo d’esercizio ','E','D',0,'41',33),('41.02','Interessi passivi bancari','costo d’esercizio ','E','D',0,'41',33),('41.03','Sconti passivi bancari','costo d’esercizio ','E','D',0,'41',33),('41.05','Interessi passivi factoring','costo d’esercizio ','E','D',0,'41',33),('41.10','Interessi passivi su mutui','costo d’esercizio ','E','D',0,'41',33),('41.12','Interessi su obbligazioni','costo d’esercizio ','E','D',0,'41',33),('41.20','Interessi passivi v/controllate','costo d’esercizio ','E','D',0,'41',33),('41.21','Interessi passivi v/collegate','costo d’esercizio ','E','D',0,'41',33),('41.22','Interessi passivi v/controllanti','costo d’esercizio ','E','D',0,'41',33),('41.30','Perdite su titoli','costo d’esercizio ','E','D',0,'41',33),('41.40','Oneri finanziari diversi','costo d’esercizio ','E','D',0,'41',33),('41.50','Ammortamento Disaggio','costo d’esercizio ','E','D',0,'41',33),('50.01','Rivalutazione partecipazioni','costo d’esercizio ','E','D',0,'50',37),('50.05','Rivalutazione titoli','costo d’esercizio ','E','D',0,'50',39),('51.01','Svalutazione partecipazioni','costo d’esercizio ','E','D',0,'51',42),('51.05','Svalutazione titoli','costo d’esercizio ','E','D',0,'51',42),('60.01','Imposte d\'esercizio','costo d’esercizio ','E','D',0,'60',46);
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts_categories`
--

DROP TABLE IF EXISTS `accounts_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts_categories` (
  `category_id` varchar(3) NOT NULL,
  `category_name` varchar(50) NOT NULL,
  `category_note` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts_categories`
--

LOCK TABLES `accounts_categories` WRITE;
/*!40000 ALTER TABLE `accounts_categories` DISABLE KEYS */;
INSERT INTO `accounts_categories` VALUES ('00','CREDITI V/SOCI','Il raggruppamento accoglie\r\nconti finanziari accesi ai\r\ncrediti per versamenti anco- ra dovuti, generalmente a\r\nbreve termine.\r\nIl codice civile richiede la\r\nseparata indicazione della\r\nparte già richiamata.'),('01','IMMOBBILIZZAZZIONI IMMATERIALI',' costi di impianto, amplia- mento, ricerca, sviluppo e\r\npubblicità possono essere\r\niscritti in bilancio fra le immo- bilizzazioni solo con il consenso\r\ndel collegio sindacale, ove\r\nesistente.\r\nIl costo del software di base\r\nviene patrimonializzato insie- me all’hardware.\r\nIl costo del software applica- tivo viene iscritto nella voce\r\nB I 3) se acquistato a titolo\r\ndi proprietà o di licenza d’uso\r\na tempo indeterminato o se\r\nprodotto in economia e tute- lato dalla normativa sui diritti\r\nd’autore, nella voce B I 4) se\r\nacquistato a titolo di licenza\r\nd’uso a tempo determinato\r\npagando un corrispettivo una\r\ntantum, nella voce B I 7) se\r\nprodotto in economia senza\r\nottenerne la tutela giuridica.\r\nL’avviamento può essere\r\niscritto fra le immobilizzazioni\r\nnei limiti del prezzo pagato a\r\ntale titolo e con il consenso\r\ndel collegio sindacale, ove\r\nesistente.\r\nI fondi ammortamento e i\r\nfondi svalutazione sono por- tati in diminuzione delle voci\r\na cui si riferiscono.'),('02','IMMOBILIZZAZIONI MATERIALI',' fondi ammortamento e i\r\nfondi svalutazione sono por- tati in diminuzione delle voci\r\na cui si riferiscono.'),('03','IMMOBILIZZAZIONI FINANZIARIE','Il raggruppamento accoglie\r\nconti economici accesi ai\r\ncosti sospesi (relativamente\r\nai titoli di debito e di capitale\r\ndetenuti per esigenze strate- giche) e conti finanziari accesi\r\nai crediti.\r\nPer ciascuna voce dei crediti\r\ndeve essere indicato l’impor\r\nto esigibile nel l’esercizio\r\nsuccessivo.'),('04','RIMANENZE','Il raggruppamento accoglie\r\nconti economici accesi ai\r\ncosti sospesi, salvo il conto\r\n04.10 Fornitori materie\r\nc/acconti che è un conto\r\nfinanziario acceso a crediti.\r\nI semilavorati affluiscono alla\r\nvoce CI2) se prodotti dall’impresa,\r\nalla voce CI1) se\r\nacquistati all’esterno.'),('05','CREDITI COMMERCIALI','Per ciascuna voce dei crediti\r\ndeve essere indicato l’importo\r\nesigibile oltre l’esercizio successivo.\r\nIl raggruppamento crediti\r\ncommerciali accoglie conti\r\nfinanziari accesi a crediti di\r\nregolamento e alle loro ret- tifiche; in particolare i conti\r\n05.40 Fondo svalutazione\r\ncrediti e 05.41 Fondo rischi\r\nsu crediti rettificano il valore\r\nnominale dei crediti, in fun- zione del rischio specifico e\r\ndel rischio globale di perdite'),('06','CREDITI DIVERSI','Il raggruppamento crediti\r\ndiversi accoglie conti finan- ziari accesi a crediti verso\r\nuna pluralità di debitori\r\ndiversi.'),('07','ATTIVITÀ FINANZIARIE','Il raggruppamento accoglie\r\nconti economici accesi ai\r\ncosti sospesi relativi a titoli di\r\ncapitale e di debito destinati\r\na permanere nel patrimonio\r\naziendale per periodi brevi.'),('08','DISPONIBILITÀ LIQUIDE','Il raggruppamento accoglie\r\nconti finanziari accesi ai\r\ncrediti a vista e ai valori in\r\ncassa.'),('09','RATEI E RISCONTI ATTIVI','Il disaggio su prestiti è una\r\nim mobilizzazione immateriale,\r\ntut ta via il codice civile impo- ne la sua iscrizione alla voce\r\nRatei e risconti.'),('10','PATRIMONIO NETTO','Il raggruppamento accoglie\r\nconti economici di patrimonio\r\nnetto, accesi alle sue parti\r\nideali, positive e negative.'),('11','FONDI PER RISCHI E ONERI','Il Fondo per imposte comprende\r\nle passività per imposte probabili,\r\nil cui ammontare o la cui\r\ndata di sopravvenienza siano\r\nindeterminati, quali accerta- menti non definitivi e contenziosi\r\nin corso. Le sue contropartite\r\nsono: Oneri fiscali diversi\r\nse trattasi di imposte indirette\r\nimputabili all’esercizio; Imposte\r\ndell’esercizio, se trattasi di\r\naccantonamenti per imposte\r\ndirette imputabili all’esercizio;\r\nImposte relative a esercizi pre- cedenti se trattasi di accantonamenti\r\nimputabili a esercizi\r\nprecedenti (oneri straordinari).\r\nIl Fondo imposte differite acco- glie le imposte dirette che, pur\r\nessendo di competenza dell’e- sercizio, si renderanno esigibili\r\nsolo in esercizi futuri (imposte\r\ndifferite).'),('12','TRATTAMENTO FINE RAPPORTO',NULL),('13','DEBITI FINANZIARI','La voce D Debiti accoglie\r\ntutte le passività per le\r\nquali sono certi e determi- nati l’importo e la data di\r\nsopravvenienza.\r\nPer ciascuna voce dei debiti\r\ndeve essere indicato l’im- porto esigibile oltre l’esercizio\r\nsuccessivo.\r\nIl raggruppamento Debiti\r\nfinanziari accoglie conti\r\nfinanziari accesi a debiti di\r\nfinanziamento.'),('14','DEBITI COMMERCIALI','Il raggruppamento Debiti\r\ncommerciali accoglie conti\r\nfinanziari accesi a debiti di\r\nregolamento.\r\n'),('15','DEBITI DIVERSI','Il raggruppamento Debiti\r\ndi versi accoglie conti finan- ziari accesi a debiti verso\r\nuna pluralità di creditori\r\ndiversi.'),('16','RATEI E RISCONTI PASSIVI',NULL),('18','CONTI TRANSITORI E DIVERSI','Questo raggruppamento\r\naccoglie i conti transitori,\r\nindicati con i codici 18.01 e\r\n18.02, che si utilizzano come\r\nconti di contropartita nelle\r\nfasi di apertura e di chiusu- ra della contabilità, e i conti\r\ndiversi, indicati con i codici\r\n18.10 e seguenti, che hanno\r\nnatura finanziaria e che pos- sono presentare alternanza di\r\nsaldi da girare a fine esercizio\r\ntra i crediti o i debiti.'),('19','CONTI DEI SISTEMI MINORI','Il raggruppamento accoglie i\r\nconti che non appartengono\r\nal sistema principale (sistema\r\ndel patrimonio e del risultato\r\neconomico), ma ai sistemi\r\ncosiddetti minori o supple- mentari cioè al sistema dei\r\nbeni di terzi, al sistema degli\r\nimpegni e al sistema dei\r\nrischi; trattasi di conti deno- minati conti di memoria o\r\nconti d’ordine.'),('20','VALORE DELLA PRODUZIONE','I ricavi delle vendite e delle\r\nprestazioni devono essere\r\niscritti in bilancio al netto di\r\nresi, ribassi, sconti, abbuoni\r\ne premi.\r\nLa variazione delle rimanen- ze compare con il segno\r\npositivo se RF > EI, con il\r\nsegno negativo se RF < EI.\r\nAnalogamente per la varia- zione dei lavori in corso su\r\nordinazione.\r\n'),('21','RICAVI E PROVENTI DIVERSI','Il conto 21.52 Contributi in\r\nc/impianti compare alla voce\r\nA 5) per la quota di compe- tenza dell’esercizio in chiusura.\r\n'),('30','COSTI DELLE MATERIE','I costi delle materie prime,\r\nsussidiarie, di consumo e\r\ndelle merci devono essere\r\niscritti in bilancio al netto di\r\nresi, ribassi, sconti, abbuoni\r\ne premi.'),('31','COSTI PER SERVIZI','Tutti questi raggruppamenti\r\naccolgono i costi inerenti\r\nall’acquisto di servizi, al per- sonale e gli altri costi inerenti\r\nalla gestione caratteristica o\r\nalla gestione accessoria; trat- tasi di conti accesi a variazioni\r\neconomiche negative\r\nd’esercizio.'),('32','COSTI PER IL GODIMENTO BENI DI TERZI','Tutti questi raggruppamenti\r\naccolgono i costi inerenti\r\nall’acquisto di servizi, al per- sonale e gli altri costi inerenti\r\nalla gestione caratteristica o\r\nalla gestione accessoria; trat- tasi di conti accesi a variazioni\r\neconomiche negative\r\nd’esercizio.'),('33','COSTI PER IL PERSONALE','Tutti questi raggruppamenti\r\naccolgono i costi inerenti\r\nall’acquisto di servizi, al per- sonale e gli altri costi inerenti\r\nalla gestione caratteristica o\r\nalla gestione accessoria; trat- tasi di conti accesi a variazioni\r\neconomiche negative\r\nd’esercizio.'),('34','AMMORTAMENTO IMMOBILIZZAZIONI IMMATERIALI','Tutti questi raggruppamenti\r\naccolgono i costi inerenti\r\nall’acquisto di servizi, al per- sonale e gli altri costi inerenti\r\nalla gestione caratteristica o\r\nalla gestione accessoria; trat- tasi di conti accesi a variazioni\r\neconomiche negative\r\nd’esercizio.'),('35','AMMORTAMENTO IMMOBILIZZAZIONI MATERIALI','Tutti questi raggruppamenti\r\naccolgono i costi inerenti\r\nall’acquisto di servizi, al per- sonale e gli altri costi inerenti\r\nalla gestione caratteristica o\r\nalla gestione accessoria; trat- tasi di conti accesi a variazioni\r\neconomiche negative\r\nd’esercizio.'),('36','SVALUTAZIONI','Tutti questi raggruppamenti\r\naccolgono i costi inerenti\r\nall’acquisto di servizi, al per- sonale e gli altri costi inerenti\r\nalla gestione caratteristica o\r\nalla gestione accessoria; trat- tasi di conti accesi a variazioni\r\neconomiche negative\r\nd’esercizio.'),('37','VARIAZIONI DELLE RIMANENZE DI MATERIE','La variazione delle rimanenze\r\ncompare con il segno\r\npositivo se RF < EI, con il\r\nsegno negativo se RF > EI.\r\n'),('38','ACCANTONAMENTI','Il raggruppamento accoglie\r\ngli accantonamenti che trova- no contropartita patrimoniale\r\nnella voce del passivo B 3).'),('39','ONERI DIVERSI','Il raggruppamento accoglie\r\ncosti d’esercizio inerenti alla\r\ngestione caratteristica e alla\r\ngestione accessoria.'),('40','PROVENTI FINANZIARI','Questi raggruppamenti accolgono\r\nconti accesi a variazioni\r\neconomiche d’esercizio ine- renti alla gestione finanziaria.\r\nLa voce C 15) accoglie i pro- venti generati sia da partecipazioni\r\nimmobilizzate, sia da\r\npartecipazioni iscritte nell’atti- vo circolante.\r\n'),('41','ONERI FINANZIARI','Questi raggruppamenti accolgono\r\nconti accesi a variazioni\r\neconomiche d’esercizio ine- renti alla gestione finanziaria.\r\nLa voce C 15) accoglie i pro- venti generati sia da partecipazioni\r\nimmobilizzate, sia da\r\npartecipazioni iscritte nell’atti- vo circolante.\r\n'),('50','RIVALUTAZIONI DI ATTIVITÀ FINANZIARIE',NULL),('51','SVALUTAZIONI DI ATTIVITÀ FINANZIARIE',NULL),('60','IMPOSTE D\'ESERCIZIO',''),('90','CONTI DI RISULTATO','Al conto 90.01 affluiscono\r\ntutti i componenti positivi e\r\nnegativi del reddito; il saldo\r\nesprime l’utile o la perdita\r\nd’esercizio.\r\nIl saldo del conto 90.02\r\nconfluisce alla voce (C16 o\r\nC17) a seconda del segno.');
/*!40000 ALTER TABLE `accounts_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts_types`
--

DROP TABLE IF EXISTS `accounts_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts_types` (
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`name`),
  UNIQUE KEY `name_3` (`name`),
  KEY `name` (`name`),
  KEY `name_2` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts_types`
--

LOCK TABLES `accounts_types` WRITE;
/*!40000 ALTER TABLE `accounts_types` DISABLE KEYS */;
INSERT INTO `accounts_types` VALUES ('costo d’esercizio '),('costo pluriennale '),('costo sospeso '),('crediti e debiti '),('credito'),('debito'),('di risultato '),('fondo oneri futuri'),('fondo rischi '),('patrimonio netto '),('rateo'),('rettifica costo d’esercizio'),('rettifica costo pluriennale '),('rettifica credito '),('rettifica di ricavo'),('ricavo d’esercizio '),('ricavo pluriennale'),('ricavo sospeso'),('valori in cassa');
/*!40000 ALTER TABLE `accounts_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `balance_sheet_rows`
--

DROP TABLE IF EXISTS `balance_sheet_rows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `balance_sheet_rows` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `row_char_identificator` varchar(4) NOT NULL,
  `row_name` varchar(500) NOT NULL,
  `row_parent` int(11) DEFAULT NULL,
  PRIMARY KEY (`row_id`),
  KEY `income_statement_parents` (`row_parent`),
  CONSTRAINT `balance_sheet_rows_ibfk_1` FOREIGN KEY (`row_parent`) REFERENCES `balance_sheet_rows` (`row_id`)
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `balance_sheet_rows`
--

LOCK TABLES `balance_sheet_rows` WRITE;
/*!40000 ALTER TABLE `balance_sheet_rows` DISABLE KEYS */;
INSERT INTO `balance_sheet_rows` VALUES (1,'A','Valore della produzione',NULL),(2,'1','ricavi delle vendite e delle prestazioni',1),(3,'2','variazioni delle rimanenze di prodotti in corso di lavorazione, semilavorati e finiti',1),(4,'3','variazioni di lavori in corso su ordinazione',1),(5,'4','incrementi di immobilizzazioni per lavori interni',1),(6,'5','altri ricavi e proventi, con separata indicazione dei contributi in conto esercizio',1),(7,'B','Costi della produzione',NULL),(8,'6','per materie prime, sussidiarie, di consumo e di merci',7),(9,'7','per servizi',7),(10,'8','per godimento di beni di terzi',7),(11,'9','per il personale',7),(12,'a','salari e stipendi',11),(13,'b','oneri sociali',11),(14,'c','trattamento di fine rapporto',11),(15,'d','trattamento di quiescenza e simili',11),(16,'e','altri costi',11),(17,'10','ammortamenti e svalutazioni',7),(18,'a','ammortamento delle immobilizzazioni immateriali',17),(19,'b','ammortamento delle immobilizzazioni materiali',17),(20,'c','altre svalutazioni delle immobilizzazioni',17),(21,'d','svalutazioni dei crediti compresi nell\'attivo circolante e delle disponibilità liquide',17),(22,'11','variazioni delle rimanenze di materie prime, sussidiarie, di consumo e merci',7),(23,'12','accantonamenti per rischi',7),(24,'13','altri accantonamenti',7),(25,'14','oneri diversi di gestione',7),(26,'C','Proventi e oneri finanziari',NULL),(27,'15','proventi da partecipazioni',26),(28,'16','altri proventi finanziari',26),(29,'a','da crediti iscritti nelle immobilizzazioni',28),(30,'b','da titoli inscritti nelle immobilizzazioni che non costituiscono partecipazioni ',28),(31,'c','da titoli inscritti nell\'attivo circolante che non costituiscono partecipazioni',28),(32,'d','proventi diversi dai precedenti',28),(33,'17','interessi e altri oneri finanziari',26),(34,'17-b','utili e perdite su cambi',26),(35,'D','Rettifiche di valore di attività e passività finanziarie',NULL),(36,'18','rivalutazioni',35),(37,'a','di partecipazioni',36),(38,'b','di immobilizzazioni finanziarie che non costituiscono partecipazioni',36),(39,'c','di titoli iscritti nell\'attivo circolante che non costituiscono partecipazioni',36),(40,'d','di strumenti finanziari derivati',36),(41,'19','svalutazioni',35),(42,'a','di immobilizzazzioni',41),(43,'b','di immobilizzazioni finanziarie che non costituiscono partecipazioni',41),(44,'c','di titoli iscritti nell\'attivo circolante che non costituiscono ',41),(45,'d','di strumenti finanziari derivata',41),(46,'20','imposte sul reddito dell\'esercizio, correnti, differite e anticipate',NULL),(47,'A','Crediti verso i soci',NULL),(48,'B','Immobilizzazioni',NULL),(49,'I','Immobilizzazioni immateriali',48),(50,'1','costi di impianto e di ampliamento',49),(51,'2','costi di sviluppo',49),(52,'3','diritti di brevetto, industriale e diritti di utilizzazione delle opere dell\'igegno',49),(53,'4','cencessioni, licenze, marchi e diritti simili',49),(54,'5','avviamento',49),(55,'6','immobilizzazioni in corso e acconti',49),(56,'7','altre',49),(57,'II','Immobilizzazioni materiali',48),(58,'1','Terreni e fabbricati',57),(59,'2','impianti e macchinario',57),(60,'3','Attrezzature industriali e commericiali',57),(61,'4','altri beni',57),(62,'5','Immobilizzazioni in corso e acconti ',57),(63,'III','Immobilizzazioni finanziarie',48),(64,'1','Partecipazioni in',63),(65,'a','imprese controllate',64),(66,'b','Imprese collegate',64),(67,'c','imprese controllanti',64),(68,'b','imprese sottoposte al controllo delle controllanti',64),(69,'bbis','altre imprese',64),(70,'2','crediti',63),(71,'a ','verso imprese controllate',70),(72,'b','verso imprese collegate',70),(73,'c','verso controllanti',70),(74,'d','verso imprese sottoposte al controllo delle controllanti',70),(75,'dbis','verso altri',70),(76,'3','altri titoli',63),(77,'4','strumenti finanziari derivati attivi',63),(78,'C','Attivo circolante',NULL),(79,'I','Rimanenze',78),(80,'1','materie prime, sussidiare e di consumo',79),(81,'2','prodotti in corso di lavorazione e semilavorati',79),(82,'3','lavori in corso di lavorazione',79),(83,'4','prodotti finiti e merci',79),(84,'5','acconti',79),(85,'II','Crediti,con separata indicazione, per ciascuna voce, degli importi esigibili oltre l\'esercizio successivo',78),(86,'1','verso clienti',85),(87,'2','verso imprese controllate',85),(88,'3','verso imprese collegate',85),(89,'4','verso controllanti',85),(90,'5','verso imprese sottoposte al controllo delle controllanti',85),(91,'5bis','crediti tributari',85),(92,'5ter','imposte anticipate',85),(93,'5qua','imposte anticipate',85),(94,'III','Attività finanziare che non costituiscono         immobilizzazioni',78),(95,'1','partecipazioni in imprese controllate',94),(96,'2','partecipazioni in imprese collegate',94),(97,'3','partecipazioni in imprese controllanti',94),(98,'3bis','partecipazioni in imprese sottoposte al controllo delle controllanti',94),(99,'4','altre partecipazioni',94),(100,'5','strumenti finanziari derivati attivi',94),(101,'6','altri titoli',94),(102,'IV','Disponibilità liquide',78),(103,'2','depositi bancari e postali',102),(104,'3','assegni',102),(105,'3','denaro e valori in cassa',102),(106,'D','Ratei e Risconti\r\n',NULL),(107,'A','Patrimonio netto',NULL),(108,'I','capitale sociale',107);
/*!40000 ALTER TABLE `balance_sheet_rows` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-12 19:21:52
