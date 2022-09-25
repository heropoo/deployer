CREATE TABLE user(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username VARCHAR(50) NOT NULL,
  password CHAR(50) not NULL,
  login_token CHAR(32) not NULL DEFAULT '',
  create_time timestamp NOT NULL DEFAULT (datetime('now', 'localtime')),
  update_time datetime NOT NULL DEFAULT (datetime('now', 'localtime'))
);
CREATE INDEX idx_username ON user(username);

CREATE TABLE host(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL,
  host VARCHAR(50) not NULL,
  port SMALLINT not NULL,
  user VARCHAR(50) not NULL,
  create_time timestamp NOT NULL DEFAULT (datetime('now', 'localtime')),
  update_time datetime NOT NULL DEFAULT (datetime('now', 'localtime'))
);

CREATE TABLE project(
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name VARCHAR(50) NOT NULL,
  path VARCHAR(255) not NULL,
  branch VARCHAR(50) not NULL,
  hosts VARCHAR(255) not NULL, -- 多个host id 逗号分隔
  create_time timestamp NOT NULL DEFAULT (datetime('now', 'localtime')),
  update_time datetime NOT NULL DEFAULT (datetime('now', 'localtime'))
);

