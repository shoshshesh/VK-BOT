CREATE TABLE IF NOT EXISTS "user" (
	    "vk_id" BIGINT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS "lesson" (
	    "id"          BIGSERIAL   PRIMARY KEY,
	    "user_id"     BIGINT      REFERENCES "user" ("vk_id") ON DELETE CASCADE,
	    "name"        VARCHAR(20) NOT NULL,
	    "day_of_week" INT         NOT NULL,
	    "time"        TIME        NOT NULL,
	    "room"        VARCHAR(20) NOT NULL,
	    "type"        VARCHAR(20) NOT NULL,
	    UNIQUE ("user_id", "name", "day_of_week", "time")
);

CREATE TABLE IF NOT EXISTS "exam" (
	    "id"      BIGSERIAL   PRIMARY KEY,
        "user_id" BIGINT      REFERENCES "user" ("vk_id") ON DELETE CASCADE,
	    "name"    VARCHAR(20) NOT NULL,
	    "date"    TIMESTAMP   NOT NULL,
	    UNIQUE ("user_id", "name", "date")
);