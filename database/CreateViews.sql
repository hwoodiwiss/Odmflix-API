-- Full Shows View
CREATE OR REPLACE VIEW `vw_Shows`
AS
SELECT 
	`Shows`.`Id` AS `Id`,
	`ShowTypes`.`Id` AS `ShowTypeId`,
	`ShowTypes`.`Name` AS `ShowType`,
	`Shows`.`Title` AS `Title`,
	`Shows`.`DateAdded` AS `DateAdded`,
	`ReleaseYears`.`Id` AS `ReleaseYearId`,
	`ReleaseYears`.`Year` AS `ReleaseYear`,
	`Ratings`.`Id` AS `RatingId`,
	`Ratings`.`Name` AS `Rating`,
	COALESCE(`MovieDurations`.`DurationMins`, `TvShowDurations`.`DurationSeasons`) AS `Duration`,
	`Shows`.`Description` AS `Description`
FROM `Shows`
JOIN `ShowTypes` ON `Shows`.`ShowTypeId` = `ShowTypes`.`Id`
JOIN `ReleaseYears` ON `Shows`.`ReleaseYearId` = `ReleaseYears`.`Id`
LEFT JOIN `Ratings` ON `Shows`.`RatingId` = `Ratings`.`Id`
LEFT JOIN `MovieDurations` ON `Shows`.`Id` = `MovieDurations`.`ShowId`
LEFT JOIN `TvShowDurations` ON `Shows`.`Id` = `TvShowDurations`.`ShowId`;

-- Only Tv Shows
CREATE OR REPLACE VIEW `vw_TvShows`
AS
SELECT 
	`Id`,
	`Title`,
	`DateAdded`,
	`ReleaseYearId`,
	`ReleaseYear`,
	`RatingId`,
	`Rating`,
	`Duration`,
	`Description`
FROM `vw_Shows`
WHERE `ShowType` = 'TV Show';

-- Only Movies
CREATE OR REPLACE VIEW `vw_Movies`
AS
SELECT
	`Id`,
	`Title`,
	`DateAdded`,
	`ReleaseYearId`,
	`ReleaseYear`,
	`RatingId`,
	`Rating`,
	`Duration`,
	`Description`
FROM `vw_Shows`
WHERE `ShowType` = 'Movie';