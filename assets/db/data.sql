DROP DATABASE IF EXISTS `qbee`;
CREATE DATABASE `qbee`;

GRANT ALL PRIVILEGES ON qbee.* TO 'qbee'@'%' WITH GRANT OPTION;

USE `qbee`;

DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    timezone VARCHAR(255) NOT NULL,
    `qbee_thanks` VARCHAR(255),
    `qbee_official` VARCHAR(4),
    `qbee_mine` VARCHAR(4),
    `qbee_163` VARCHAR(4),
    `qbee_wash` DATETIME NOT NULL
);
-- TODO: set qbee_wash
INSERT INTO `settings` (timezone, qbee_thanks, qbee_official, qbee_mine, qbee_163, qbee_wash)
    VALUES ('America/New_York', 'gif', 'gif', 'gif', 'gif', '2012-12-23');

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `folder` VARCHAR(255) NOT NULL,
    `qbee_official` VARCHAR(4),
    `qbee_mine` VARCHAR(4),
    `qbee_163` VARCHAR(4)
);
INSERT INTO `categories` (folder)
    VALUES ("patches"), ("about"), ("special"), ("thanks"), ("donations"), ("events"), ("colonies")
;

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE `galleries` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `folder` VARCHAR(255) NOT NULL,
    `clear_url` VARCHAR(255),
    `bg` VARCHAR(4),
    `ext` VARCHAR(4),
    `columns` INT,
    `spacing` BOOLEAN DEFAULT false,
    `border` BOOLEAN DEFAULT false,
    `reverse` BOOLEAN DEFAULT false,
    `category_id` INT,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
);
INSERT INTO `galleries` (folder, clear_url, bg, ext, columns, spacing, border, reverse, category_id) VALUES
    ("quilt", "/", NULL, "gif", 8, false, false, false, 1);

-- ("retired", "/", NULL, "gif", 8, false, false, false, 1),
INSERT INTO `galleries` (folder) VALUES ("");
DELETE FROM `galleries` WHERE id=LAST_INSERT_ID();

INSERT INTO `galleries` (folder, clear_url, bg, ext, columns, spacing, border, reverse, category_id) VALUES
    ("about-me", NULL, NULL, "png", 8, false, false, false, 2),
    ("old-patches", NULL, NULL, "png", 8, false, false, false, 2),
    ("old-events", NULL, NULL, NULL, 2, true, false, false, 2),
    ("achievements", NULL, NULL, NULL, 3, false, false, false, 3),
    ("from-qbee", NULL, NULL, NULL, 1, false, false, false, 4),
    ("free-bee-interest-patches", NULL, NULL, NULL, 6, false, false, false, 5),
    ("bee-zaar-accessorize-it", NULL, NULL, NULL, 4, true, false, false, 5),
    ("bee-zaar-bee-cosplay", NULL, NULL, NULL, 3, true, true, false, 5),
    ("bee-zaar-beauty-parlour", NULL, NULL, NULL, 1, true, true, false, 5),
    ("2012-tasty-cakes", NULL, "png", NULL, 5, true, false, false, 6),
    ("2012-lucky-dip-made", NULL, NULL, NULL, 7, true, false, false, 6),
    ("2012-lucky-dip-received", NULL, NULL, NULL, 7, true, false, false, 6),
    ("2012-lucky-dip-candy-hearts", NULL, NULL, NULL, 5, true, false, false, 6),
    ("2012-lucky-dip-collected", NULL, NULL, NULL, 3, true, false, false, 6),
    ("2012-disco-party", NULL, NULL, NULL, 1, true, false, false, 6),
    ("2012-kite-flying", NULL, NULL, NULL, 3, true, false, false, 6),
    ("special", NULL, NULL, NULL, 7, false, false, false, 3),
    ("gifts-received", NULL, NULL, NULL, 7, false, false, false, 3),
    ("2012-spring-flowers", NULL, NULL, NULL, 6, true, false, false, 6),
    ("newsletter", NULL, NULL, NULL, 6, false, false, false, 3);

-- ("from-members", NULL, NULL, NULL, 1, false, false, false, 4),
INSERT INTO `galleries` (folder) VALUES ("");
DELETE FROM `galleries` WHERE id=LAST_INSERT_ID();

INSERT INTO `galleries` (folder, clear_url, bg, ext, columns, spacing, border, reverse, category_id) VALUES
    ("2012-plants", NULL, "png", NULL, 5, true, false, false, 6);

-- ("gifts-given", NULL, NULL, NULL, 1, false, false, false, 3),
INSERT INTO `galleries` (folder) VALUES ("");
DELETE FROM `galleries` WHERE id=LAST_INSERT_ID();

INSERT INTO `galleries` (folder, clear_url, bg, ext, columns, spacing, border, reverse, category_id) VALUES
    ("pixel-lots-little-people", NULL, NULL, NULL, 4, true, false, false, 7),
    ("bee-chefs-recipe-challenge", NULL, NULL, NULL, 1, true, false, false, 7),
    ("from-me", NULL, NULL, NULL, 1, false, false, false, 4),
    ("2012-advent-calendar", NULL, "png", "png", 5, false, false, false, 6);

-- ("gifts-received-2", NULL, NULL, NULL, 7, false, false, false, 3), -- TODO: what was this?
INSERT INTO `galleries` (folder) VALUES ("");
DELETE FROM `galleries` WHERE id=LAST_INSERT_ID();

INSERT INTO `galleries` (folder, clear_url, bg, ext, columns, spacing, border, reverse, category_id) VALUES
    ("2013-spring-flowers", NULL, NULL, NULL, 4, true, false, false, 6),
    ("awards", NULL, NULL, NULL, 5, false, false, false, 3),
    ("old-shrooms", NULL, NULL, NULL, 2, true, false, false, 2),
    ("2013-egg-hunt", NULL, NULL, NULL, 2, true, false, false, 6);

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `gallery_id` INT NOT NULL,
    `bee_id` INT,
    `name` VARCHAR(255) NOT NULL,
    `url` VARCHAR(255),
    `ext` VARCHAR(4),
    `time` DATETIME DEFAULT NOW(),
    `content` TEXT,
    `required` INT
);
INSERT INTO `images` (gallery_id, bee_id, name, url, ext, time) VALUES
    (1, 262, "Misaki", "http://loveflakes.net", "gif", "2012-01-09 00:00:00"),
    (1, 188, "Melissa", "http://alittlewhimsy.net/quilt", "gif", "2012-01-09 00:00:00"),
    (1, 245, "Clefairykid", "http://rainbowhorizon.radiant-sky.net", "gif", "2012-01-10 00:00:00"),
    (1, 136, "Inger Franz", "http://pixels.ingerssite.de", "gif", "2012-01-11 00:00:00"),
    (1, 54, "Manaka", "http://manaka-quilt.livejournal.com", "gif", "2012-01-13 00:00:00"),
    (1, 140, "Taryn", "http://winter-pear.jynxedpanda.com", "gif", "2012-01-14 00:00:00"),
    (1, 238, "Kat", "http://thelittleseawitch.net", "png", "2012-01-16 00:00:00"),
    (1, 166, "Sherry", "http://fireyred.com", "gif", "2012-01-26 00:00:00"),
    (1, 22, "Erohiel", "http://hivestar.livejournal.com/853.html", "png", "2012-01-28 00:00:00"),
    (1, 148, "Kay", "http://angelgloss.tumblr.com", "png", "2012-02-03 00:00:00"),
    (1, 240, "Karen", "http://ribbonhood.com", "gif", "2012-02-08 00:00:00"),
    (1, 33, "Aiirene", "http://irenewithlove.com", "gif", "2012-02-18 00:00:00"),
    (1, 133, "Nimil", "http://system-overload.org", "png", "2012-02-19 00:00:00"),
    (1, 218, "Kayty", "http://angelicsmile.webs.com", "png", "2012-02-25 00:00:00"),
    (1, 190, "Niwa", "http://niwa-senpai.tumblr.com", "gif", "2012-02-26 00:00:00"),
    (1, 40, "Peach", "http://peachbabypixel.blogspot.com", "gif", "2012-03-23 00:00:00"),
    (1, 112, "Anabel", "http://fandomorama.com", "gif", "2012-03-24 00:00:00"),
    (1, 20, "Karol", "http://arlain.net/carolina", "png", "2012-03-24 00:00:00"),
    (1, 5, "Afef", "http://strawberry-gashes.net", "gif", "2012-04-02 00:00:00"),
    (1, 233, "June", "http://starcatching.tumblr.com/quilt", "png", "2012-04-04 00:00:00"),
    (1, 144, "Serenity", "http://stardust.so-pink.org", "gif", "2012-04-22 00:00:00"),
    (1, 299, "Joanna", "http://beewhisperer101.tumblr.com/quilt", "gif", "2012-04-29 00:00:00"),
    (1, 19, "Tori", "http://oddtribes.wordpress.com/my-quilt", "png", "2013-05-20 00:00:00"),
    (1, 101, "Cassie", "http://accio-diadem.asterous.com", "png", "2013-05-20 00:00:00"),
    (1, 202, "Mercedes", "http://redrarebit.tumblr.com", "png", "2013-06-02 00:00:00"),
    (1, 248, "Ody", "http://ijay.me", "gif", "2013-06-05 00:00:00"),
    (1, 191, "Rhian", "http://rhiandoesstuff.blogspot.com", "gif", "2013-06-12 00:00:00"),
    (1, 43, "Heather", "http://pluscandy.tumblr.com", "png", "2013-12-22 00:00:00"),
    (1, 165, "Fifi", "http://aucasaurus.tumblr.com/qbeequilt", "png", "2013-04-23 00:00:00");
INSERT INTO `images` (gallery_id, name, ext) VALUES
    (3, "90's kid", "gif"),
    (3, "adobe", "png"),
    (3, "american", "gif"),
    (3, "artist", "gif"),
    (3, "asian food", "gif"),
    (3, "atheist", "png"),
    (3, "baking", "gif"),
    (3, "beach", "gif"),
    (3, "big sister", "gif"),
    (3, "black hair", "gif"),
    (3, "born in ny", "gif"),
    (3, "brown eyes", "gif"),
    (3, "coffee", "gif"),
    (3, "deviantart", "gif"),
    (3, "dewey", "gif"),
    (3, "disney", "gif"),
    (3, "domains", "gif"),
    (3, "dumplings", "gif"),
    (3, "ebay", "gif"),
    (3, "fanlistings", "gif"),
    (3, "friends", "gif"),
    (3, "girl", "gif"),
    (3, "grape juice", "gif"),
    (3, "green", "gif"),
    (3, "ipod", "gif"),
    (3, "italian food", "gif"),
    (3, "june baby", "gif"),
    (3, "music", "png"),
    (3, "nature", "gif"),
    (3, "ocean", "gif"),
    (3, "orange juice", "gif"),
    (3, "paint shop pro 9", "gif"),
    (3, "photoshop cs5", "gif"),
    (3, "php", "png"),
    (3, "piano", "gif"),
    (3, "pixeller", "gif"),
    (3, "seafood", "gif"),
    (3, "shopaholic", "gif"),
    (3, "skype", "gif"),
    (3, "summer", "gif"),
    (3, "sushi", "gif"),
    (3, "tea", "gif"),
    (3, "thursday child", "gif"),
    (3, "toothless", "gif"),
    (3, "travelling", "gif"),
    (3, "tropical zodiac", "gif"),
    (3, "vimeo", "gif"),
    (3, "wine", "gif"),
    (3, "wishes", "gif"),
    (3, "youtube", "png");
INSERT INTO `images` (gallery_id, name, ext) VALUES
    (4, "2004 official patch", "png"),
    (4, "2004 my patch", "gif"),
    (4, "2004 my patch", "gif"),
    (4, "2004 bee no. 168", "gif"),
    (4, "2004 bumble bee", "gif"),
    (4, "2004 trade filler", "gif"),
    (4, "2004 honeycomb event award", "gif"),
    (4, "2004 updated info ty", "gif"),
    (4, "2004 gift for me", "gif"),
    (4, "2004 gift for me", "gif"),
    (4, "2004 gift for me", "gif"),
    (4, "2004 gift for a bee", "gif"),
    (4, "2007 official patch", "gif"),
    (4, "2007 my patch", "gif"),
    (4, "2007 bee no. 286", "gif"),
    (4, "2007 trade filler", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 5: about
    (5, "for honeycomb event 1", "gif"),
    (5, "for honeycomb event 2", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 6: achievements
    (6, "bought the q*bee shirt", "gif"),
    (6, "bumble bee", "gif"),
    (6, "collected 10 candy hearts in lucky dip 2012", "gif"),
    (6, "made 50 gifts in lucky dip 2012", "png"),
    (6, "read the rules", "png"),
    (6, "washed my quilt", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 7: thanks, from qbee
    (7, "voted in the 2013 sandcastles event", "png");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 8: donations, free-bee interest patches
    (8, "american flag", "gif"),
    (8, "canadian flag", "gif"),
    (8, "german flag", "gif"),
    (8, "japanese flag", "gif"),
    (8, "south korean flag", "gif"),
    (8, "british flag", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 9: donations, bee-zaar accessorize it
    (9, "blue pacifier", "gif"),
    (9, "green pacifier", "gif"),
    (9, "purple pacifier", "gif"),
    (9, "red pacifier", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 10: donations, bee-zaar bee cosplay
    (10, "aurora's necklace", "gif"),
    (10, "aurora's gown", "gif"),
    (10, "cinderella's earrings", "gif"),
    (10, "cinderella's gloves", "gif"),
    (10, "cinderella's neckband", "gif"),
    (10, "cinderella's gown", "gif"),
    (10, "jasmine's earrings", "gif"),
    (10, "jasmine's necklace", "gif"),
    (10, "jasmine's pants", "gif"),
    (10, "jasmine's shoes", "gif"),
    (10, "jasmine's top", "gif"),
    (10, "mulan's dress", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 11: donations, bee-zaar beauty parlour
    (11, "eye scar", "gif");
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 12: events, 2012 tasty cakes
    (12, NULL, "my entry", NULL, "gif"),
    (12, 19, "Tori", "http://oddtribes.wordpress.com/my-quilt", "png"),
    (12, 22, "Erohiel", "http://hivestar.livejournal.com/853.html", "png"),
    (12, 46, "Christy", NULL, "gif"),
    (12, 54, "Manaka", "http://manaka-quilt.livejournal.com", "gif"),
    (12, 140, "Taryn", "http://winter-pear.jynxedpanda.com", "gif"),
    (12, 245, "Clefairykid", "http://rainbowhorizon.radiant-sky.net", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 13: events, 2012 lucky dip made
    (13, "alarm clock", "gif"),
    (13, "apple", "gif"),
    (13, "balloon", "gif"),
    (13, "basketball", "gif"),
    (13, "bee", "gif"),
    (13, "binoculars", "gif"),
    (13, "blocks", "gif"),
    (13, "bloody knife", "gif"),
    (13, "bookmark", "gif"),
    (13, "books", "gif"),
    (13, "bowling ball", "gif"),
    (13, "browser window", "gif"),
    (13, "bubbles", "gif"),
    (13, "candlelight", "gif"),
    (13, "chinese take-out", "gif"),
    (13, "chopsticks", "gif"),
    (13, "clefs", "gif"),
    (13, "clock", "gif"),
    (13, "computer mouse", "gif"),
    (13, "concrete printing", "gif"),
    (13, "contact lens kit", "gif"),
    (13, "cookie", "gif"),
    (13, "cross-stitch", "gif"),
    (13, "cupcake", "gif"),
    (13, "definition", "gif"),
    (13, "die", "gif"),
    (13, "dihydrogen monoxide", "gif"),
    (13, "dna translation", "gif"),
    (13, "doodles", "gif"),
    (13, "door", "gif"),
    (13, "drink", "gif"),
    (13, "earth", "gif"),
    (13, "electrical outlet", "gif"),
    (13, "enter key", "gif"),
    (13, "envelope", "gif"),
    (13, "eyeball", "gif"),
    (13, "floppy disk", "gif"),
    (13, "gun", "gif"),
    (13, "hangman's noose", "gif"),
    (13, "hourglass", "gif"),
    (13, "htc thunderbolt", "gif"),
    (13, "human heart", "gif"),
    (13, "ice cream", "gif"),
    (13, "ipad", "gif"),
    (13, "key", "gif"),
    (13, "kite", "gif"),
    (13, "leaves", "gif"),
    (13, "lego", "gif"),
    (13, "license plate", "gif"),
    (13, "lightning", "gif"),
    (13, "lollipop", "gif"),
    (13, "mouse cursor", "gif"),
    (13, "mug", "gif"),
    (13, "mushroom", "gif"),
    (13, "necktie", "gif"),
    (13, "night sky", "gif"),
    (13, "nintendo 3ds", "gif"),
    (13, "notebook", "gif"),
    (13, "oreo", "gif"),
    (13, "origami", "gif"),
    (13, "paint", "gif"),
    (13, "paper clip", "gif"),
    (13, "pendant", "gif"),
    (13, "periodic table", "gif"),
    (13, "piano keys", "gif"),
    (13, "pillow", "gif"),
    (13, "plant", "gif"),
    (13, "quilt", "gif"),
    (13, "quote", "gif"),
    (13, "racket", "gif"),
    (13, "ribbon bow", "gif"),
    (13, "riceball", "gif"),
    (13, "ring", "gif"),
    (13, "sai", "gif"),
    (13, "sewn", "gif"),
    (13, "shooting star", "gif"),
    (13, "snow", "gif"),
    (13, "soccer ball", "gif"),
    (13, "stethoscope", "gif"),
    (13, "sunrise", "gif"),
    (13, "superman", "gif"),
    (13, "sword", "gif"),
    (13, "syringe", "gif"),
    (13, "tail", "gif"),
    (13, "tetris", "gif"),
    (13, "tiara", "gif"),
    (13, "tic-tac-toe", "gif"),
    (13, "tissue box", "gif"),
    (13, "tooth", "gif"),
    (13, "traffic light", "gif"),
    (13, "trp operon", "gif"),
    (13, "turtle", "gif"),
    (13, "utensils", "gif"),
    (13, "water drop", "gif"),
    (13, "window", "gif"),
    (13, "yo-yo", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 14: events, 2012 lucky dip received
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "png"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "gif"),
    (14, "", "png"),
    (14, "", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 15: events, 2012 lucky dip candy hearts
    (15, "bee mine", "png"),
    (15, "honey bear", "png"),
    (15, "bee love", "png"),
    (15, "buzz buzz", "png"),
    (15, "honey pie", "png"),
    (15, "sweet bee", "png"),
    (15, "sweet bee 2", "png"),
    (15, "honey love", "png"),
    (15, "nom nom", "png"),
    (15, "honey bun", "png");
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 16: events, 2012 lucky dip collected
    (16, 22, "Erohiel", "http://hivestar.livejournal.com/853.html", "png"),
    (16, 54, "Manaka", "http://manaka-quilt.livejournal.com", "gif"),
    (16, 54, "Manaka", "http://manaka-quilt.livejournal.com", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 17: events, 2012 disco party
    (17, "my entry", "gif");
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 18: events, 2012 kite flying
    (18, NULL, "my entry", NULL, "png"),
    (18, 54, "Manaka", "http://manaka-quilt.livejournal.com", "gif"),
    (18, 289, "Diane", NULL, "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 19: special, special
    (19, "bee*chefs colony", "gif"),
    (19, "bookworms colony", "gif"),
    (19, "fanlisting", "gif"),
    (19, "lean bees colony", "gif"),
    (19, "maneki neko", "gif"),
    (19, "member for 3 months", "gif"),
    (19, "nominate me", "gif");
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 20: special, gifts received
    (20, 188, "welcoming gift - Melissa", "http://alittlewhimsy.net/quilt", "gif"),
    (20, NULL, "happy birthday", null, "png");
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 21: events, 2012 spring flowers
    (21, NULL, "my entry", NULL, "gif"),
    (21, 19, "Tori", "http://oddtribes.wordpress.com/my-quilt", "png"),
    (21, 54, "Manaka", "http://manaka-quilt.livejournal.com", "gif"),
    (21, 124, "Jilly", NULL, "gif"),
    (21, 136, "Inger Franz", "http://pixels.ingerssite.de", "gif"),
    (21, 289, "Diane", NULL, "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 22: special, newsletter
    (22, "issue 25", "gif"),
    (22, "january 2012", "gif"),
    (22, "february 2012", "gif"),
    (22, "march 2012", "gif"),
    (22, "april 2012", "gif"),
    (22, "may 2012", "gif");
-- 23: thanks, from members
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 24: events, planets
    (24, 57, "Angelica", NULL, "gif"),
    (24, 136, "Inger Franz", "http://pixels.ingerssite.de", "gif"),
    (24, 140, "Taryn", "http://winter-pear.jynxedpanda.com", "gif"),
    (24, 191, "Rhian", "http://rhiandoesstuff.blogspot.com", "gif"),
    (24, 289, "Diane", NULL, "gif");
-- 25: gifts, from me
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 26: colonies, pixel lots little people
    (26, "bee", "gif"),
    (26, "bikini", "gif"),
    (26, "minnie mouse", "gif"),
    (26, "school girl", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 27: colonies, bee chefs recipe challenge
    (27, "daikon soup", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 28: thanks, from me
    (28, "for trading", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 29: events, 2012 advent calendar
    (29, "1st", "gif"),
    (29, "2nd", "gif"),
    (29, "", "png"),
    (29, "4th", "gif"),
    (29, "5th", "gif"),
    (29, "6th", "gif"),
    (29, "", "png"), -- TODO: 7th or 8th missing?
    (29, "8th", "gif"), -- TODO: 7th or 8th missing?
    (29, "9th", "gif"),
    (29, "10th", "gif"),
    (29, "11th", "gif"),
    (29, "12th", "gif"),
    (29, "13th", "gif"),
    (29, "14th", "gif"),
    (29, "15th", "gif"),
    (29, "16th", "gif"),
    (29, "17th", "gif"),
    (29, "18th", "gif"),
    (29, "19th", "gif"),
    (29, "20th", "gif"),
    (29, "21st", "gif"),
    (29, "22nd", "gif"),
    (29, "23rd", "gif");
-- 30: gifts, received 2
INSERT INTO `images` (gallery_id, bee_id, name, url, ext) VALUES -- 31: events, 2013 spring flowers
    (31, NULL, "my entry", NULL, "png"),
    (31, 82, "Elixabeth", NULL, "png"),
    (31, 95, "Sammy", NULL, "gif"),
    (31, 256, "Mimi", NULL, "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 32: special, awards
    (32, "3rd best pixelling in tasty cakes 2012 event", "png"),
    (32, "1st shiniest oufit in disco party 2012 event", "png"),
    (32, "3rd best pixelling in disco party 2012 event", "png"),
    (32, "1st most creative in kite flying 2012 event", "png"),
    (32, "2nd best pixelling in kite flying 2012 event", "png"),
    (32, "1st best pixelling in spring flowers 2012 event", "gif"),
    (32, "2nd most creative in spring flowers 2012 event", "gif"),
    (32, "2nd loveliest flower in spring flowers 2012 event", "gif"),
    (32, "1st best pixelling in planets 2012 event", "png"),
    (32, "2nd most creative in planets 2012 event", "png");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 3: about, shrooms
    (33, "decorate a honeycomb 2004 activity", "gif"),
    (33, "cozy quilt", "gif");
INSERT INTO `images` (gallery_id, name, ext) VALUES -- 3: events, 2013 egg hunt
    (34, "6th", "gif"),
    (34, "12th", "gif");

DROP TABLE IF EXISTS `errors`;
CREATE TABLE `errors` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `code` INT NOT NULL,
    `description` TEXT,
    `location` VARCHAR(255) NOT NULL,
    `time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `ip` VARCHAR(45) NULL,
    `refer` TEXT
);

DROP TABLE IF EXISTS `journal`;
CREATE TABLE `journal` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `bee_id` INT NOT NULL,
    `url` VARCHAR(255),
    `time` DATETIME NOT NULL,
    `content` TEXT,
    `retired` INT
);
