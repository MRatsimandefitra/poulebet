CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password V
ARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:array)', salt VARCHAR(255) NOT NULL, isEnable TINYINT(1) NO
T NULL, nomAdmin VARCHAR(255) NOT NULL, prenomAdmin VARCHAR(255) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME
NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE championat (id INT AUTO_INCREMENT NOT NULL, nomChampionat VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHAR
ACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE class_annee (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, nbPointsClass DOUBLE PRECISION NOT
 NULL, nbMatchsJoues INT NOT NULL, nbMatchGagnes INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_uni
code_ci ENGINE = InnoDB;
CREATE TABLE class_em (id INT AUTO_INCREMENT NOT NULL, numeroSemaine INT NOT NULL, libelle VARCHAR(100) NOT NULL, nbPointsC
lass DOUBLE PRECISION NOT NULL, nbMatchsJoues INT NOT NULL, nbMatchsGagnes INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER
 SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE class_mois (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(100) NOT NULL, nbPointsClass DOUBLE PRECISION NOT
NULL, nbMatachsJoues INT NOT NULL, nbMathsGagnes INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_uni
code_ci ENGINE = InnoDB;
CREATE TABLE concours (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, nomConcours VARCHAR(100) NOT NULL, finValidatio
n DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, typeCredit INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 C
OLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE droit (id INT AUTO_INCREMENT NOT NULL, fonctionnalite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTE
R SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE droit_admin (id INT AUTO_INCREMENT NOT NULL, lecture TINYINT(1) DEFAULT NULL, modification TINYINT(1) DEFAULT
NULL, suppression TINYINT(1) DEFAULT NULL, ajout TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLA
TE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE live_score (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_c
i ENGINE = InnoDB;
CREATE TABLE lot (id INT AUTO_INCREMENT NOT NULL, concours_id INT DEFAULT NULL, nomLot VARCHAR(100) NOT NULL, nbPointNecess
aire INT NOT NULL, cheminImage VARCHAR(255) NOT NULL, INDEX IDX_B81291BD11E3C7 (concours_id), PRIMARY KEY(id)) DEFAULT CHAR
ACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE loto_foot15 (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, finValidation DATETIME NOT NULL, PRIMARY KEY
(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE loto_foot7 (id INT AUTO_INCREMENT NOT NULL, numero INT NOT NULL, finValidation DATETIME NOT NULL, PRIMARY KEY(
id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE match_individuel (id INT AUTO_INCREMENT NOT NULL, championat_id INT DEFAULT NULL, concours_id INT DEFAULT NULL
, lotofoot7_id INT DEFAULT NULL, lotofoot15_id INT DEFAULT NULL, dateMatch DATE NOT NULL, equipeDomicile VARCHAR(50) NOT NU
LL, cheminLogoDomicile VARCHAR(255) NOT NULL, score VARCHAR(5) NOT NULL, equipeVisiteur VARCHAR(50) NOT NULL, cheminLogoVis
iteur VARCHAR(255) NOT NULL, cot1Pronostic DOUBLE PRECISION NOT NULL, coteNPronistic DOUBLE PRECISION NOT NULL, cote2Pronos
tic DOUBLE PRECISION NOT NULL, status TINYINT(1) DEFAULT NULL, masterProno TINYINT(1) DEFAULT NULL, resultatDomicile INT DE
FAULT NULL, resultatVisiteur INT DEFAULT NULL, tempsEcoules INT DEFAULT NULL, vote1Concours DOUBLE PRECISION NOT NULL, cote
NConcours DOUBLE PRECISION NOT NULL, cote2Concours DOUBLE PRECISION NOT NULL, INDEX IDX_A2E2F23F8E5873AB (championat_id), I
NDEX IDX_A2E2F23FD11E3C7 (concours_id), INDEX IDX_A2E2F23F83AF5C05 (lotofoot7_id), INDEX IDX_A2E2F23FA6A714B7 (lotofoot15_i
d), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE mvt_credit (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, voteutilisateur_id INT DEFAULT NU
LL, credit_id INT DEFAULT NULL, entreeCredit INT NOT NULL, sortieCredit INT NOT NULL, soldeCredit INT NOT NULL, dateMvt DAT
ETIME NOT NULL, INDEX IDX_14AE9480FB88E14F (utilisateur_id), INDEX IDX_14AE948045B51F25 (voteutilisateur_id), INDEX IDX_14A
E9480CE062FF9 (credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE mvt_lot (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, lot_id INT DEFAULT NULL, entreeLot I
NT NOT NULL, sortieLot INT NOT NULL, soldeLot INT NOT NULL, dateMvtLot DATETIME NOT NULL, INDEX IDX_C18AB575FB88E14F (utili
sateur_id), INDEX IDX_C18AB575A8CBA5F7 (lot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE
 = InnoDB;
CREATE TABLE mvt_point (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT DEFAULT NULL, mvtlot_id INT DEFAULT NULL, voteut
ilisateur_id INT DEFAULT NULL, entreePoint INT NOT NULL, sortiePoint INT NOT NULL, soldePoint INT NOT NULL, dateMvt DATETIM
E NOT NULL, INDEX IDX_B562A069FB88E14F (utilisateur_id), INDEX IDX_B562A06920341A26 (mvtlot_id), INDEX IDX_B562A06945B51F25
 (voteutilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, nomPays VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET ut
f8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE publicite (id INT AUTO_INCREMENT NOT NULL, cheminPub VARCHAR(255) NOT NULL, type TINYINT(1) NOT NULL, PRIMARY
KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, ville_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email
VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Typ
e:array)', isEnable TINYINT(1) DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, sexe TINYINT(1) DEFAU
LT NULL, dateNaissance DATE NOT NULL, dateCreation DATE NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT N
ULL, userToken VARCHAR(255) NOT NULL, cheminPhoto VARCHAR(255) NOT NULL, achatProno TINYINT(1) DEFAULT NULL, dateProno DATE
TIME DEFAULT NULL, validiteProno DATETIME DEFAULT NULL, adresse1 VARCHAR(255) NOT NULL, adresse2 VARCHAR(255) NOT NULL, adr
esse3 VARCHAR(255) NOT NULL, pays VARCHAR(255) NOT NULL, telephone VARCHAR(15) DEFAULT NULL, fax VARCHAR(15) DEFAULT NULL,
INDEX IDX_1D1C63B3A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, pays_id INT DEFAULT NULL, nomVille VARCHAR(255) NOT NULL, codePostal IN
T NOT NULL, INDEX IDX_43C3D9C3A6E44244 (pays_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGIN
E = InnoDB;
CREATE TABLE vote_utilisateur (id INT AUTO_INCREMENT NOT NULL, matchindividuel_id INT DEFAULT NULL, utilisateur_id INT DEFA
ULT NULL, vote INT NOT NULL, gagnant TINYINT(1) DEFAULT NULL, INDEX IDX_AE9155E7347BE9EF (matchindividuel_id), INDEX IDX_AE
9155E7FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE lot ADD CONSTRAINT FK_B81291BD11E3C7 FOREIGN KEY (concours_id) REFERENCES concours (id);
ALTER TABLE match_individuel ADD CONSTRAINT FK_A2E2F23F8E5873AB FOREIGN KEY (championat_id) REFERENCES championat (id);
ALTER TABLE match_individuel ADD CONSTRAINT FK_A2E2F23FD11E3C7 FOREIGN KEY (concours_id) REFERENCES concours (id);
ALTER TABLE match_individuel ADD CONSTRAINT FK_A2E2F23F83AF5C05 FOREIGN KEY (lotofoot7_id) REFERENCES loto_foot7 (id);
ALTER TABLE match_individuel ADD CONSTRAINT FK_A2E2F23FA6A714B7 FOREIGN KEY (lotofoot15_id) REFERENCES loto_foot15 (id);
ALTER TABLE mvt_credit ADD CONSTRAINT FK_14AE9480FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id);
ALTER TABLE mvt_credit ADD CONSTRAINT FK_14AE948045B51F25 FOREIGN KEY (voteutilisateur_id) REFERENCES vote_utilisateur (id)
;
ALTER TABLE mvt_credit ADD CONSTRAINT FK_14AE9480CE062FF9 FOREIGN KEY (credit_id) REFERENCES credit (id);
ALTER TABLE mvt_lot ADD CONSTRAINT FK_C18AB575FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id);
ALTER TABLE mvt_lot ADD CONSTRAINT FK_C18AB575A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id);
ALTER TABLE mvt_point ADD CONSTRAINT FK_B562A069FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id);
ALTER TABLE mvt_point ADD CONSTRAINT FK_B562A06920341A26 FOREIGN KEY (mvtlot_id) REFERENCES mvt_lot (id);
ALTER TABLE mvt_point ADD CONSTRAINT FK_B562A06945B51F25 FOREIGN KEY (voteutilisateur_id) REFERENCES vote_utilisateur (id);

ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id);
ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id);
ALTER TABLE vote_utilisateur ADD CONSTRAINT FK_AE9155E7347BE9EF FOREIGN KEY (matchindividuel_id) REFERENCES match_individue
l (id);
ALTER TABLE vote_utilisateur ADD CONSTRAINT FK_AE9155E7FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateur (id);