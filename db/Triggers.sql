USE japura_events;

DELIMITER //

CREATE TRIGGER after_like_insert
AFTER INSERT ON event_likes
FOR EACH ROW
BEGIN
    UPDATE events
    SET like_count = like_count + 1
    WHERE event_id = NEW.event_id;
END;
//

DELIMITER ;

DELIMITER //

CREATE TRIGGER after_like_delete
AFTER DELETE ON event_likes
FOR EACH ROW
BEGIN
    UPDATE events
    SET like_count = like_count - 1
    WHERE event_id = OLD.event_id;
END;
//

DELIMITER ;