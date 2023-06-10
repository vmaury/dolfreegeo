--
-- Script run when an upgrade of Dolibarr is done. Whatever is the Dolibarr version.
--
CREATE FUNCTION `calcadist`(`lat1` double, `lon1` double, `lat2` double, `lon2` double) RETURNS double
RETURN 6371 * 6.28/360 * sqrt((lat1 - lat2)*(lat1 - lat2) + (0.707*(lon1 - lon2)*0.707*(lon1 - lon2)));;
